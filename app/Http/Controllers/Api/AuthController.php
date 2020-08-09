<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\UserTransformer;
use App\Notifications\ActivationCode;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{	
	use ApiResponse;

    //register User
    public function register(RegisterRequest $request)
    {
    	$data = $request->except('password');
    	$data['password'] = Hash::make($request->password);
    	$data['activation_code'] = rand ( 1000 , 9999 );

    	$user = User::create($data);
    	if( $user )
    	{
    		$this->userNotify($user);

            $user->assignRole($request->role);

            if( $request->role == 'trainee')
            {
                $user->is_completed = 1;
                $user->save();
            }
    	}
    	
    	$token = $user->createToken('Fitness')->accessToken;

        return $this->fullDataResponse($token,$this->userData($user),trans('all.email_send'),200);
    }

    public function userNotify($user)
    {
    	$user->notify(new ActivationCode($user->activation_code));
    }

    //verify User
    public function verify(VerifyRequest $request)
    {
        $user = User::where('activation_code',$request->code)->first();

        if( !$user )
        {
            return $this->errorResponse(trans('all.wrong_code'),null,422);
        }

        $user->is_verified = 1;
        $user->activation_code = null;
        $user->save();

       $token = $user->createToken('Fitness')->accessToken;
       return $this->fullDataResponse($token,$this->userData($user),trans('all.verified'),200);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
      
            $user = Auth::user();
        
            if ( !$user->is_verified ) {
              return $this->dataResponse($this->userData($user),trans('all.notConfirmed'),403); 
            }
        
            $token = $user->createToken('Fitness')->accessToken;
            return $this->fullDataResponse($token,$this->userData($user),trans('all.login'),200);
        } 
        return $this->errorResponse(trans('all.incorrect_login'),null,401);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        return $this->setActivation($request);

    }

    public function newPassword(NewPasswordRequest $request)
    {
        $user = User::where('email',$request->email)
                    ->where('activation_code',$request->code)
                    ->first();

        if( !$user )
        {
            return $this->errorResponse(trans('all.wrong_code'),null,422);
        }

        $user->password = Hash::make($request->password);
        $user->activation_code = null;
        (!$user->is_verified) ?? 1;
        $user->save();

        return $this->dataResponse(null,trans('all.password_changed'),200); 
    }


    public function resend(ForgetPasswordRequest $request)
    {
        return $this->setActivation($request);

    }

    private function setActivation($request)
    {
        $user = User::where('email',$request->email)->first();

        $user->update([
            'activation_code' => rand(1000,9999)

        ]);
        $this->userNotify($user);
        return $this->dataResponse(null,trans('all.email_send'),200);
    }

    private function userData($user)
    {
        return fractal()
                ->item($user)
                ->transformWith(new UserTransformer())
                ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                ->includeRoles()
                ->toArray();

    }


}
