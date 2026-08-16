<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Save or update token
        FcmToken::firstOrCreate([
            'user_id' => $user->id,
            'token' => $request->token,
        ]);

        return response()->json(['success' => true, 'message' => 'Token saved successfully']);
    }
}
