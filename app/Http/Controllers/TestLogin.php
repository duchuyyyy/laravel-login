<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestLogin extends Controller
{
    public function index()
    {
        $arr = [
            'email' => 'kinphan189@gmail.com',
            'password' => '123456'
        ];

        if (Auth::attempt($arr)) {
            echo "acb";
        } else {
            echo "asdas";
        }
    }
}
