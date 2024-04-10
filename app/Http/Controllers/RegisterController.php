<?php

namespace App\Http\Controllers;

use App\Http\Common\MessageConstant;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RegisterController extends Controller
{
    use ApiResponseTrait;


    public function __invoke(RegisterRequest $request): JsonResponse
    {

        $user = User::create(['email' => $request->email, 'password' => $request->password, 'role' => $request->role]);

        return $this->successWithData($user);
    }
}
