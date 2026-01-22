<?php

namespace App\Http\Controllers;

use App\Enums\FeedbackStatus;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $ipAddress = $request->ip();
        $limitSeconds = 60;

        $lastMessage = Feedback::where('ip_address', $ipAddress)
            ->latest()
            ->first();

        if ($lastMessage && $lastMessage->created_at->gt(Carbon::now()->subSeconds($limitSeconds))) {

            $diffInSeconds = $lastMessage->created_at->diffInSeconds(Carbon::now());
            $roundedSecondsLeft = (int) max(1, $limitSeconds - $diffInSeconds);

            $errorMessage = "Limit Reached! To prevent duplicate submissions, please wait.";

            return redirect()->to(url()->previous() . '#feedback-form')
                ->withInput()
                ->withErrors(['limit' => $errorMessage])
                ->with('retry_seconds', $roundedSecondsLeft);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:3|max:500',
        ], [
            'message.required' => 'Mohon isi pesan masukan Anda.',
            'message.min' => 'Masukan terlalu pendek.',
            'message.max' => 'Masukan maksimal 500 karakter.',
        ]);

        Feedback::create([
            'message' => strip_tags($validated['message']),
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'status' => FeedbackStatus::NEW ,
        ]);

        return redirect()->to(url()->previous() . '#feedback-form')
            ->with('success', 'Terima kasih! Masukan Anda sangat berarti bagi kami.');
    }
}
