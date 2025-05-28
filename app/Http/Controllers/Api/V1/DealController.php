<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DealRequest;
use App\Http\Resources\Api\V1\DealResource;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(Request $request)
    {
        $query = Deal::query()
            ->with(['contact']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('withTrashed') && $request->boolean('withTrashed')) {
            $query->withTrashed();
        }

        return DealResource::collection($query->paginate());
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(DealRequest $request)
    {
        try {
            $deal = Deal::create($request->validated());

            return response()->json([
                'data' => new DealResource($deal),
                'message' => 'Deal created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create contact',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealRequest $dealRequest, Deal $deal)
    {
        try {
            $validated = $dealRequest->validated();

            $deal->update($validated);

            return response()->json([
                'data' => new DealResource($deal),
                'message' => 'Deal updated successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update deal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deal $deal)
    {
        $deal->delete();

        return response()->json([
            'message' => 'Deal deleted successfully'
        ]);
    }
}
