<?php

namespace App\Http\Controllers\Admin\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Models\Admin;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    use RespondsWithHttpStatus;
    public function login(LoginRequest $request)
    {
        $admin = Admin::query()->where('email', $request->email)->first();
        if ($admin){
            if ( \Hash::check($request->password,$admin->password) )
            {
                $token = $admin->createToken('API Token')->accessToken;
                return $this->success('this is access token', [$token]);
            }else{
                return $this->failure('Password mismatch');
            }
        }else{
            return $this->failure('User does not exist');
        }
    }
}
