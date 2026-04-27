<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function markAsRead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'read_at' => ['nullable', 'date'],
            'redirect_to' => ['nullable', 'string'],
        ]);

        $currentReadAt = $request->user()->notifications_read_at;
        $requestedReadAt = isset($validated['read_at'])
            ? Carbon::parse($validated['read_at'])
            : now();
        $nextReadAt = $currentReadAt && $currentReadAt->gt($requestedReadAt)
            ? $currentReadAt
            : $requestedReadAt;

        $request->user()->forceFill([
            'notifications_read_at' => $nextReadAt,
        ])->save();

        $redirectTo = $validated['redirect_to'] ?? null;

        if (is_string($redirectTo) && $redirectTo !== '' && (Str::startsWith($redirectTo, '/') || Str::startsWith($redirectTo, url('/')))) {
            return redirect($redirectTo);
        }

        return back();
    }
}
