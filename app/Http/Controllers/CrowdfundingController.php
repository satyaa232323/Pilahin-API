<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\DonateRequest;
use App\Models\Crowdfunding_campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CrowdfundingController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function campaigns()
    {
        try {
            $campaigns = Crowdfunding_campaign::where('status', 'active')
                ->with(['dropPoint'])
                ->get();

            return ResponseHelper::success('Campaigns retrieved successfully', $campaigns);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve campaigns: ' . $e->getMessage());
        }
    }

    public function campaignDetail($id)
    {
        try {
            $campaign = Crowdfunding_campaign::where('id', $id)
                ->where('status', 'active')
                ->with(['dropPoint'])
                ->first();

            if (!$campaign) {
                return ResponseHelper::notFound('Campaign not found');
            }

            return ResponseHelper::success('Campaign detail retrieved successfully', $campaign);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve campaign detail: ' . $e->getMessage());
        }
    }

    public function donate(DonateRequest $request, $id)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!$user) {
            return ResponseHelper::notFound('User not found');
        }

        $campaign = Crowdfunding_campaign::where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$campaign) {
            return ResponseHelper::notFound('Campaign not found or inactive');
        }

        // Create donation record first
        $donation = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'amount' => $validated['amount'],
            'payment_status' => 'pending',
        ]);

        $orderId = 'DONATION-' . $donation->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $validated['amount'],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'item_details' => [
                [
                    'id' => 'donation-' . $campaign->id,
                    'price' => $validated['amount'],
                    'quantity' => 1,
                    'name' => 'Donasi untuk ' . $campaign->title,
                ],
            ],
            // Tambahkan redirect URLs
            'callbacks' => [
                'finish' => url('  https://0a32e6553e3a.ngrok-free.app/api/crowdfunding/payment/finish?donation_id=' . $donation->id),
                'unfinish' => url('  https://0a32e6553e3a.ngrok-free.app/api/crowdfunding/payment/unfinish?donation_id=' . $donation->id),
                'error' => url('  https://0a32e6553e3a.ngrok-free.app/api/crowdfunding/payment/error?donation_id=' . $donation->id),
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 30
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $donation->update([
                'payment_id' => $orderId
            ]);

            return ResponseHelper::success('Payment token generated successfully', [
                'snap_token' => $snapToken,
                'donation_id' => $donation->id,
                'order_id' => $orderId,
                'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'campaign' => [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'current_amount' => $campaign->current_amount,
                    'target_amount' => $campaign->target_amount,
                    'progress_percentage' => round(($campaign->current_amount / $campaign->target_amount) * 100, 2),
                ],
                'amount' => $validated['amount'],
                'expires_at' => now()->addMinutes(30)->toISOString(),
            ]);
        } catch (\Exception $e) {
            if (isset($donation)) {
                $donation->delete();
            }
            return ResponseHelper::serverError('Midtrans Error: ' . $e->getMessage());
        }
    }

    public function donationHistory(Request $request)
    {
        try {
            $donations = Donation::where('user_id', $request->user()->id)
                ->with(['campaign'])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($donations->isEmpty()) {
                return ResponseHelper::notFound('No donation history found');
            }

            return ResponseHelper::success('Donation history retrieved successfully', $donations);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve donation history: ' . $e->getMessage());
        }
    }

    // Payment redirect handlers
    public function paymentFinish(Request $request)
    {
        $donationId = $request->get('donation_id');

        if ($donationId) {
            $donation = Donation::with('campaign')->find($donationId);

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully',
                'data' => [
                    'donation_id' => $donation->id,
                    'payment_status' => $donation->payment_status,
                    'amount' => $donation->amount,
                    'campaign' => $donation->campaign->title ?? null,
                ],
                'redirect' => 'mobile://payment/success'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment completed',
            'redirect' => 'mobile://payment/success'
        ]);
    }

    public function paymentUnfinish(Request $request)
    {
        $donationId = $request->get('donation_id');

        return response()->json([
            'success' => false,
            'message' => 'Payment not completed',
            'data' => [
                'donation_id' => $donationId,
                'status' => 'pending'
            ],
            'redirect' => 'mobile://payment/pending'
        ]);
    }

    public function paymentError(Request $request)
    {
        $donationId = $request->get('donation_id');

        return response()->json([
            'success' => false,
            'message' => 'Payment failed',
            'data' => [
                'donation_id' => $donationId,
                'status' => 'failed'
            ],
            'redirect' => 'mobile://payment/error'
        ]);
    }

    // Webhook untuk update status payment dari Midtrans
    public function handleWebhook(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            $donation = Donation::where('payment_id', $orderId)->first();

            if ($donation) {
                $paymentStatus = $this->mapMidtransStatus($transactionStatus, $fraudStatus);
                $donation->update(['payment_status' => $paymentStatus]);

                // Update campaign current amount if payment is successful
                if ($paymentStatus === 'paid') {
                    $campaign = $donation->campaign;
                    $campaign->increment('current_amount', $donation->amount);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Map Midtrans transaction status to our payment status
     */
    private function mapMidtransStatus($transactionStatus, $fraudStatus)
    {
        switch ($transactionStatus) {
            case 'capture':
                return ($fraudStatus === 'accept') ? 'paid' : 'failed';
            case 'settlement':
                return 'paid';
            case 'pending':
                return 'pending';
            case 'deny':
            case 'expire':
            case 'cancel':
                return 'failed';
            default:
                return 'pending';
        }
    }

    // Method untuk cek status donation
    public function checkDonationStatus($donationId)
    {
        try {
            $donation = Donation::with('campaign')->findOrFail($donationId);

            return ResponseHelper::success('Donation status retrieved successfully', [
                'donation_id' => $donation->id,
                'payment_status' => $donation->payment_status,
                'amount' => $donation->amount,
                'campaign' => [
                    'id' => $donation->campaign->id,
                    'title' => $donation->campaign->title,
                ],
                'created_at' => $donation->created_at,
                'updated_at' => $donation->updated_at,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::notFound('Donation not found');
        }
    }
}