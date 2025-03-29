<?php

if (!function_exists('handleResponse')) {
    function handleResponse($message, $type = 'success')
    {
        if (request()->ajax()) {
            return response()->json([
                'status' => $type,
                'message' => $message
            ], $type === 'error' ? 500 : 200);
        }

        return redirect()->back()->with($type, $message);
    }
}
