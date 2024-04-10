<?php

namespace App\Http\Common;

use App\Models\Sessions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommonFunction
{
    public static function setSessionStartAt($user)
    {
        $user->session_start_at = Carbon::now();
        $user->save();
    }

    public static function setCookie($sessionId)
    {
        $minutes = 60;
        $response = response('Set Cookie');
        $cookie = Cookie::make('SESSIONID', $sessionId, $minutes);
        return $response->withCookie($cookie);
    }

    public static function existingSession($user, $sites)
    {
        $existingSession = Sessions::where('user_id', $user->id)->where('sites', $sites)->first();

        if ($existingSession) {
            $sessionStartAt = Carbon::parse($user->session_start_at);
            $sessionLifetime = Config::get('session.lifetime') * 60;

            $currentTime = Carbon::now();

            $elapsedTime = $currentTime->diffInSeconds($sessionStartAt);

            if ($elapsedTime <= $sessionLifetime) {
                throw new BadRequestHttpException(MessageConstant::SESSION_IS_EXISTING);
            }
        }
    }
}
