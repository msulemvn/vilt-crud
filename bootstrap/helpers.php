<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Models\ErrorLog;
use App\Models\ActivityLog;
use Illuminate\Http\Resources\Json\JsonResource;

if (!function_exists('apiResponse')) {
    function apiResponse(
        ?string $message = null,
        array|JsonResource $data = [],
        array $errors = [],
        int $statusCode = SymfonyResponse::HTTP_OK,
        ?Request $request = null,
        ?Throwable $exception = null
    ): JsonResponse {
        $result = [];
        if ($exception && $request) {
            try {
                ErrorLog::create([
                    'request' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                    'exception' => $exception->getMessage(),
                    'function' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown',
                ]);
                Log::error('Error Log:', ['exception' => $exception]);
            } catch (Throwable $e) {
                Log::critical('Error logging failed', ['exception' => $e]);
            }

            $message = $message ?? 'Something went wrong';
            $errors = ['server' => ['An error occurred']];
        }

        if ($statusCode) {
            $result['status'] = SymfonyResponse::$statusTexts[$statusCode];
        }

        if ($message) {
            $result['message'] = $message;
        }

        if (!empty($data)) {
            $result['data'] = $data;
        }

        if (!empty($errors)) {
            $result['errors'] = ['validation' => $errors];
        }

        return response()->json($result, $statusCode);
    }
}

if (!function_exists('logActivity')) {
    function logActivity(Request $request, string $description, bool $showable = false): void
    {
        try {
            ActivityLog::create([
                'request_log_id' => $request["request_log_id"],
                'description' => $description,
                'showable' => $showable,
            ]);
        } catch (Throwable $e) {
            Log::warning('Activity logging failed', ['exception' => $e]);
        }
    }
}
