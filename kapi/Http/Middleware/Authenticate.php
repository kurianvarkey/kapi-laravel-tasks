<?php

namespace Kapi\Http\Middleware;

use Closure;
use Kapi\Models\User;
use Illuminate\Http\Request;
use Kapi\Traits\AppResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    use AppResponseTrait;

    /**
     * Handle an incoming request to validate the api key
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->bearerToken();
        $access_token = (empty($access_token) ? $request->header('Authorization') : $access_token);
        $key = explode(' ', $access_token);
        if (is_array($key) && count($key) > 1) {
            $access_token = $key[1];
        }

        if ($access_token) {
            $user = User::apiKey($access_token)->first(['id']);
            if (!empty($user)) {
                $request->request->add(['user_id' => $user->id]);
                return $next($request);
            }
        }

        return $this->sendErrorResponse(
            httpStatus: Response::HTTP_UNAUTHORIZED,
            errors: ['Unauthorized attempt']
        );
    }
}
