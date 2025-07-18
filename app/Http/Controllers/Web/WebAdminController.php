<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Waste_deposit;
use App\Models\WasteDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebAdminController
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Check if user is admin
                if ($user->role !== 'admin' && $user->role !== 'superadmin') {
                    Auth::logout();
                    return back()->with('error', 'Access denied. Admin privileges required.');
                }

                $request->session()->regenerate();
                return redirect()->intended('/home');
            }

            return back()->with('error', 'Invalid credentials');
        } catch (\Exception $e) {
            return back()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function scanQr(Request $request)
    {
        try {
            $validated = $request->validate([
                'qr_code' => 'required|string'
            ]);

            // Check if user is authenticated and is admin/superadmin
            $admin = Auth::user();
            if (!$admin || ($admin->role !== 'admin' && $admin->role !== 'superadmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admin and superadmin can scan QR codes'
                ], 403);
            }

            // Log the QR code being searched
            Log::info('Scanning QR Code: ' . $validated['qr_code']);

            $user = User::where('qr_code', $validated['qr_code'])->first();

            if (!$user) {
                // Log all existing QR codes for debugging
                $allQrCodes = User::whereNotNull('qr_code')->pluck('qr_code')->toArray();
                Log::info('Available QR Codes: ' . implode(', ', $allQrCodes));

                return response()->json([
                    'success' => false,
                    'message' => 'User not found with QR code: ' . $validated['qr_code'],
                    'debug' => [
                        'searched_qr' => $validated['qr_code'],
                        'available_qr_codes' => $allQrCodes
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'User found',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'points' => $user->points,
                    'qr_code' => $user->qr_code,
                    'last_scan_by' => $admin->name,
                    'scan_time' => now()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('QR Scan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitWaste(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'category' => 'required|string',
                'weight_kg' => 'required|numeric|min:0.01',
                'location_id' => 'required|string',
                'photo_url' => 'nullable|image|max:2048'
            ]);

            // Calculate points (10 points per kg)
            $points = (int) ($validated['weight_kg'] * 10);

            // Handle photo upload if provided
            $photoPath = null;
            if ($request->hasFile('photo_url')) {
                $photoPath = $request->file('photo_url')->store('waste_photos', 'public');
            }

            // Create waste deposit record (you need to create this model)
            $wasteDeposit = Waste_deposit::create([
                'user_id' => $validated['user_id'],
                'category' => $validated['category'],
                'weight_kg' => $validated['weight_kg'],
                'location_id' => $validated['location_id'],
                'points_earned' => $points,
                'photo_url' => $photoPath,
            ]);

            // Update user points
            $user = User::find($validated['user_id']);
            $user->points += $points;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Waste deposit saved successfully',
                'data' => [
                    'points_earned' => $points,
                    'user_total_points' => $user->points
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
