<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Waste_deposit;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userHistory(Request $request)
    {
        try{
            $wasteHistory = Waste_deposit::where('user_id', $request->user()->id)
            ->with(['dropPoint'])
            ->orderBy('created_at', 'desc');
            return ResponseHelper::success(
            
                'User waste history fetched successfully.',
                $wasteHistory
            );
        }
        
        
        catch (\Exception $e) {
           Return ResponseHelper::error(
                'Failed to fetch user waste history.',
                $e->getMessage()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}