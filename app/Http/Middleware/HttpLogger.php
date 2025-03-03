<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\HttpRequest;
use Illuminate\Http\Request;
use \App\DTOs\HttpRequest\HttpRequestDTO;
use Symfony\Component\HttpFoundation\Response;
use bootstrap\Helpers;

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
            $httpRequest = HttpRequest::create((new HttpRequestDTO($request))->toArray());
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
        $httpRequest = HttpRequest::find($request['request_log_id'] ?? null);
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
