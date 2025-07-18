<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Waste_deposit;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return ResponseHelper::success('User list retrieved successfully', $user);
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return ResponseHelper::success('Profile retrieved successfully', $user);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve profile: ' . $e->getMessage());
        }
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            // chek if user authenticated
            if ($request->user()->id !== $user->id) {
                return ResponseHelper::forbidden('You are not authorized to update this profile');
            }

            $validated = $request->validated();

            // hash password if provided

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);

                // Remove password_confirmation from validated data
                unset($validated['password_confirmation']);
            }

            // Update user profile
            $user->update($validated);

            $user->refresh();


              return ResponseHelper::success('Profile updated successfully', [
                'user' => $user,
            ]);
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ResponseHelper::notFound('User not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Profile update failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function GetQrText(Request $request)
    {
        try {
            $user = $request->user();
            return ResponseHelper::success('QR text retrieved successfully', [
                'qr_text' => $user->qr_code,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve QR text: ' . $e->getMessage());
        }
    }




    /**
     * Update the specified resource in storage.
     */

    public function getQrImage(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->qr_image_url) {
                return ResponseHelper::notFound('QR code image not found');
            }

            return ResponseHelper::success('QR image retrieved successfully', [
                'qr_image_url' => $user->qr_image_url,
                'qr_code' => $user->qr_code,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve QR image: ' . $e->getMessage());
        }
    }

    // Waste History

    public function wasteHistory()
{
    $user = Auth::user(); // ambil user dari token

    // Ambil semua data waste_deposits milik user ini
    $wasteDeposits = Waste_deposit::where('user_id', $user->id)->get();

    return response()->json([
        'success' => true,
        'message' => 'Waste history retrieved successfully',
        'data' => $wasteDeposits
    ]);
    }

    // Waste Detail

    public function wasteDetail($id)
{
    return response()->json([
        'success' => true,
        'message' => 'Detail sampah ditemukan',
        'data' => [
            'id' => $id,
            'type' => 'Plastik',
            'weight' => 1.5,
            'collected_at' => '2025-07-17'
        ]
    ]);
}

// Redeem Voucher

public function redeemVoucher(Request $request)
{
    $user = Auth::user(); // ✅ Ambil user dari token
    $voucher = Voucher::find($request->voucher_id);

    if (!$voucher) {
        return response()->json([
            'success' => false,
            'message' => 'Voucher tidak ditemukan',
        ], 200);
    }

    if ($user->points < $voucher->required_points) {
        return response()->json([
            'success' => false,
            'message' => 'Poin tidak mencukupi untuk redeem voucher ini',
        ], 400);
    }
}

    // Kurangi poin user





    /**
     * Remove the specified resource from storage.
     */
}