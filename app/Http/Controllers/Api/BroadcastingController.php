<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BroadcastingController extends Controller
{
    /**
     * Authenticate broadcasting channel
     */
    public function auth(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $channelName = $request->input('channel_name');
        $socketId = $request->input('socket_id');

        // Validate channel access
        if (!$this->canAccessChannel($user, $channelName)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Generate authentication signature
        $auth = $this->generateAuthSignature($channelName, $socketId);

        return response()->json([
            'auth' => $auth
        ]);
    }

    /**
     * Check if user can access the channel
     */
    protected function canAccessChannel($user, string $channelName): bool
    {
        // Public channels
        $publicChannels = [
            'freight-orders',
            'manufacturing',
            'inventory',
            'delivery',
            'returns',
            'suppliers',
            'notifications',
            'tracking',
            'alerts'
        ];

        if (in_array($channelName, $publicChannels)) {
            return true;
        }

        // User-specific channels
        if (str_starts_with($channelName, 'user-')) {
            $userId = (int) str_replace('user-', '', $channelName);
            return $user->id === $userId;
        }

        // Company-specific channels
        if (str_starts_with($channelName, 'company-')) {
            $companyId = (int) str_replace('company-', '', $channelName);
            return $user->company_id === $companyId;
        }

        return false;
    }

    /**
     * Generate authentication signature for Pusher
     */
    protected function generateAuthSignature(string $channelName, string $socketId): string
    {
        $secret = config('broadcasting.connections.pusher.secret');
        $stringToSign = $socketId . ':' . $channelName;
        
        return hash_hmac('sha256', $stringToSign, $secret);
    }
}
