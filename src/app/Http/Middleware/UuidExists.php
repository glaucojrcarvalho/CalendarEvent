<?php

namespace App\Http\Middleware;

use App\Models\EventModel;

class UuidExists
{
    public function handle($request, $next)
    {
        $uuid = $request->route('uuid');
        if (!EventModel::where('uuid', $uuid)->exists()) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return $next($request);
    }
}
