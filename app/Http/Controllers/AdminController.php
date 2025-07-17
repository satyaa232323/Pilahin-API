<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function scanQr(Request $request)
    {
        try {

            $validated = $request->validate([
                'qr_code' => 'required|string',
            ]);

            // check if authenticated is admin
            $admin = $request->user();
            if ($admin->role !== 'admin') {
                return ResponseHelper::forbidden('Olny admin and superadmin can scan QR codes');
            }

            // find user by QR code
            $user = User::where('qr_code', $validated['qr_code'])->first();

            if (!$user) {
                return ResponseHelper::notFound('User not found with the provided QR code');
            }

            // Get user profile with additional information

            $userProfile = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'points' => $user->points,
                'role' => $user->role,
                'qr_code' => $user->qr_code,
                'created_at' => $user->created_at,
                'last_scan_by' => $admin->name,
                'scan_time' => now()
            ];
            return ResponseHelper::success('User profile retrieved successfully', $userProfile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Scan failed: ' . $e->getMessage());
        }
    }
}