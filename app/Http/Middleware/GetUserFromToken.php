<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class GetUserFromToken extends BaseMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    const HEADER_TOKEN = 'x-access-token';
    const CLIENT_ID = 'x-client-id';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $force_success = 0) {
        $token = $request->header(self::HEADER_TOKEN) !== NULL ? $request->header(self::HEADER_TOKEN) : $request->input(self::HEADER_TOKEN);
        $request_client_id = $request->header(self::CLIENT_ID) !== NULL ? $request->header(self::CLIENT_ID) : $request->input(self::CLIENT_ID);

        $client_id = config('app.client-id');

        if ($client_id) {
            if (strcasecmp($request_client_id, $client_id)) {
                http_response_code(400);
                header('Content-Type: application/json');
                $result = array('code' => 4045, 'description' => 'Invalid application id.');
                echo json_encode($result, JSON_UNESCAPED_SLASHES);
                exit();
            }
        } else {
            http_response_code(400);
            header('Content-Type: application/json');
            $result = array('code' => 4045, 'description' => 'Application id not found.');
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            exit();
        }


        if (!$token) {
            if (!$force_success) {
                http_response_code(400);
                header('Content-Type: application/json');
                $result = array('code' => 4014, 'description' => 'Token no provided.');
                echo json_encode($result, JSON_UNESCAPED_SLASHES);
                exit();
            }
            $request->request->add(['account_id' => null]);
            $request->request->add(['token' => null]);
            return $next($request);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            $result = array('code' => 4014, 'description' => 'Token expired.');
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            exit();
        } catch (JWTException $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            $result = array('code' => 4044, 'description' => 'Token invalid.');
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            exit();
        }

        if (!$user || $user->delete_status != 0) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }
        $request->request->add(['person' => $user->id]);
        $request->request->add(['token' => $token]);

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }

}
