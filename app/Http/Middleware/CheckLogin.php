<?php

namespace App\Http\Middleware;

use Closure;
use App\Cache\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Xử lý các request đến.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::user()) {
            $content = json_encode([
                'code' => 401,
			    'message' => 'Unauthorized'
            ]);
            $response = new Response($content, 401);
            $response->headers->set('Content-Type', 'application/json');
            return $response;

            // abort(401, 'Unauthorized');
            // return;
        }

        // Thực hiện nghiệp vụ chính
        return $next($request);
    }
}
