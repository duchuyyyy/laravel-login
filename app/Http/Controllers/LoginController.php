<?php

namespace App\Http\Controllers;

use App\Http\Common\CommonFunction;
use App\Http\Common\MessageConstant;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(LoginRequest $request)
    {
        $user = $this->findUserByEmail($request->email);
        CommonFunction::existingSession($user, 'user');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $sessionId = Session::getId();
            CommonFunction::setSessionStartAt($user);
            CommonFunction::setCookie($sessionId);

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
}
