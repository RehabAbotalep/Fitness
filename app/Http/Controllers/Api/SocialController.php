<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\User;
use Illuminate\Http\Request;
use Validator;

class SocialController extends Controller
{
    use ApiResponse;

    public function socialLogin(Request $request)
    {
    	$user = User::where('social_id',$request->social_id)->first();

    	$validator = Validator::make($request->all(),[
	    	'social_id'   => 'required',
	    	'social_type' => 'required|in:facebook,apple,google',
	    	'email'       => 'sometimes|nullable|email',

    	]);
    	if($validator->fails()){
    			
    		return $this->errorResponse($validator->errors()->first(),$validator->errors(),422);
    	}

    	if( !$user )
    	{
    		$user = new User;
    		$user->social_id   = $request->social_id;
    		$user->social_type = $request->social_type;
    		if( !empty( $request->email ) )
    		{
    			$user->email = $request->email;
    			$user->is_verified = 1;
    		}

    		$user->save();

    	}
    	$token = $user->createToken('Fitness')->accessToken;
    	
     
    	return $this->fullDataResponse($token,new UserResource($user),trans('all.registed'),200);
    }
}
