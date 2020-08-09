<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use Closure;

class ApprovedTrainer
{   
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( auth('api')->user()->is_approved == 1 ){

            return $next($request);
        }

        return $this->errorResponse(trans('all.not_approved'),null,403);
    }
}
