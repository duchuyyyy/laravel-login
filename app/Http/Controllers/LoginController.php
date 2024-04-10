<?php

namespace App\Http\Controllers;

use App\Http\Common\MessageConstant;
use App\Http\Requests\LoginRequest;
use App\Models\Sessions;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(LoginRequest $request)
    {
        $user = $this->findUserByEmail($request->email);
        $this->existingSession($user->id);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $sessionId = Session::getId();
            $this->setCookie($sessionId);

            return $this->successWithData($user);
        } else {
            throw new BadRequestHttpException(MessageConstant::INCORRECT_PASSWORD);
        }
    }

    private function findUserByEmail($email)
    {
        $user = User::where("email", $email)->first();
        if (!$user) {
            throw new BadRequestHttpException(MessageConstant::ACCOUNT_IS_NOT_EXISTED);
        }
        return $user;
    }

    private function setCookie($sessionId)
    {
        $minutes = 60;
        $response = response('Set Cookie');
        $cookie = Cookie::make('SESSIONID', $sessionId, $minutes);
        return $response->withCookie($cookie);
    }

    private function existingSession($userId)
    {
        $existingSession = Sessions::where('user_id', $userId)->first();

        if ($existingSession) {
            $lastActivity = $existingSession->last_activity;
            $sessionLifetime = Config::get('session.lifetime') * 60;

            $lastActivityTimestamp = Carbon::parse($lastActivity);

            // Calculate the current time
            $currentTime = Carbon::now();

            // Calculate the difference in seconds between current time and last activity
            $elapsedTime = $currentTime->diffInSeconds($lastActivityTimestamp);

            // Check if the elapsed time exceeds the session lifetime
            if ($elapsedTime > $sessionLifetime) {
                throw new BadRequestHttpException(MessageConstant::SESSION_EXPIRED);
            } else {
                throw new BadRequestHttpException(MessageConstant::SESSION_IS_EXISTING);
            }
        }
    }
}
