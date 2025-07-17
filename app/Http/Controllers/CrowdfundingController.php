<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Crowdfunding_campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

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

    public function donate(Request $request)
    {
        try {
            $validated = $request->validate([
                'campaign_id' => 'required|exists:crowdfunding_campaigns,id',
                'amount' => 'required|integer|min:10000'
            ]);

            $user = $request->user();
            $campaign = Crowdfunding_campaign::find($validated['campaign_id']);

            // Create donation record
            $donation = Donation::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'amount' => $validated['amount'],
                'payment_status' => 'pending'
            ]);

            // Prepare Midtrans transaction
            $params = [
                'transaction_details' => [
                    'order_id' => 'DONATION-' . $donation->id . '-' . time(),
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
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update donation with payment_id
            $donation->update(['payment_id' => $params['transaction_details']['order_id']]);

            return ResponseHelper::success('Payment token generated successfully', [
                'snap_token' => $snapToken,
                'donation_id' => $donation->id,
                'order_id' => $params['transaction_details']['order_id']
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to create donation: ' . $e->getMessage());
        }
    }

    public function donationHistory(Request $request)
    {
        try {
            $donations = Donation::where('user_id', $request->user()->id)
                ->with(['campaign'])
                ->orderBy('created_at', 'desc');
            
            if(!$donations->exists()) {
                return ResponseHelper::notFound('No donation history found');
            }

            return ResponseHelper::success('Donation history retrieved successfully', $donations);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve donation history: ' . $e->getMessage());
        }
    }

    // Webhook untuk update status payment dari Midtrans
    public function handleWebhook(Request $request)
    {
        try {
            $notification = $request->all();
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];

            $donation = Donation::where('payment_id', $orderId)->first();

            if ($donation) {
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    $donation->update(['payment_status' => 'paid']);

                    // Update campaign current amount
                    $campaign = $donation->campaign;
                    $campaign->increment('current_amount', $donation->amount);
                } elseif ($transactionStatus == 'pending') {
                    $donation->update(['payment_status' => 'pending']);
                } else {
                    $donation->update(['payment_status' => 'failed']);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}