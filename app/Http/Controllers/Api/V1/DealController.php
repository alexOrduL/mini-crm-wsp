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
    * @OA\Get(
    *     path="/api/v1/deals",
    *     summary="List all deals",
    *     tags={"Deals"},
    *     @OA\Response(
    *         response=200,
    *         description="List all deals"
    *     )
    * )
    */
    public function index(Request $request)
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
    * @OA\Post(
    *     path="/api/v1/deals",
    *     summary="Updates a del",
    *     tags={"Deals"},
    *     @OA\Response(
    *         response=200,
    *         description="Updates a deal"
    *     )
    * )
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
    * @OA\Put(
    *     path="/api/v1/deals/{deal}",
    *     summary="Updates a Deal",
    *     tags={"Deals"},
    *     @OA\Response(
    *         response=200,
    *         description="Updates a deal"
    *     )
    * )
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
    * @OA\Delete(
    *     path="/api/v1/deals/{deal}",
    *     summary="Deletes a Deal",
    *     tags={"Deals"},
    *     @OA\Response(
    *         response=200,
    *         description="it makes a Soft Delete of an specific Deal"
    *     )
    * )
    */
    public function destroy(Deal $deal)
    {
        $deal->delete();

        return response()->json([
            'message' => 'Deal deleted successfully'
        ]);
    }
}
