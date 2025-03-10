<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\RequestLog;
use Illuminate\Http\Request;
use \App\DTOs\RequestLog\StoreRequestLogDTO;
use Symfony\Component\HttpFoundation\Response;

class HttpLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): Response  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $httpRequest = RequestLog::create((new StoreRequestLogDTO($request))->toArray());
            $request['request_log_id'] = $httpRequest->id;
        } catch (\Exception $e) {
            apiResponse(request: $request, exception: $e);
        }
        $request['request_log_id'] = $httpRequest->id;

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  Request  $request
     * @param  Response  $response
     */
    public function terminate(Request $request, Response $response): void
    {
        $httpRequest = RequestLog::find($request['request_log_id'] ?? null);
        if ($httpRequest) {
            $httpRequest->update([
                'user_id' => $request->user()->id ?? null,
                'response' => json_encode($response->getOriginalContent()),
                'status_code' => $response->getStatusCode(),
            ]);
        }
        unset($request['request_log_id']);
    }
}
