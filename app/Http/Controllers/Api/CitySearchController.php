<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CitySearchController extends Controller
{
    /**
     * Search for cities using OpenStreetMap Nominatim API
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:3|max:255',
            'limit' => 'nullable|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $request->get('q');
        $limit = $request->get('limit', 5);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'OmniChain/1.0 (https://omnichain.com)'
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'format' => 'json',
                'q' => $query,
                'limit' => $limit,
                'addressdetails' => 1,
                'extratags' => 1
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to search cities',
                    'error' => 'External API error'
                ], 502);
            }

            $results = $response->json();

            // Transform the results to match our frontend expectations
            $transformedResults = array_map(function ($place) {
                return [
                    'place_id' => $place['place_id'],
                    'display_name' => $place['display_name'],
                    'lat' => (float) $place['lat'],
                    'lon' => (float) $place['lon'],
                    'address' => $place['address'] ?? [],
                    'name' => $place['name'] ?? ''
                ];
            }, $results);

            return response()->json([
                'success' => true,
                'data' => $transformedResults
            ]);

        } catch (\Exception $e) {
            \Log::error('City search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to search cities',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get city details by place ID
     */
    public function details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'place_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $placeId = $request->get('place_id');

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'OmniChain/1.0 (https://omnichain.com)'
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/details', [
                'format' => 'json',
                'place_id' => $placeId,
                'addressdetails' => 1,
                'extratags' => 1
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get city details',
                    'error' => 'External API error'
                ], 502);
            }

            $result = $response->json();

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            \Log::error('City details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get city details',
                'error' => 'Internal server error'
            ], 500);
        }
    }
}