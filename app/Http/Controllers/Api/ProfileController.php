<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\UserTransformer;
use App\Notifications\ActivationCode;
use App\UpdatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    
    use ApiResponse;

    public function __construct()
	{
	    $this->middleware('auth:api');
	}

    /*
    	Update User Profile
    */
    public function update(Request $request)
    {	
    	$user = auth('api')->user();

    	$request->validate([
            'email'     => 'sometimes|nullable|email|unique:users,email,'. $user->id,
        	'password'  => 'sometimes|nullable|min:8',
        	'freelance' => 'nullable|in:0,1',
        	'gym_name'  => 'required_if:freelance,0'
        ]);

        $data = $request->except('email');
        

        if( !empty($request->email) && $user->email != $request->email)
        {
        	$this->updateMail($user->id,$request->email);
        }

        if( !empty($request->new_password))
        {
        	if(($user->password != null) && !Hash::check($request->old_password, $user->password))
        	{
        		return $this->errorResponse(trans('all.wrong_old_password'),null,422);
        	}
        	$data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);
        $this->completeProfile($user);

        return $this->dataResponse(null,trans('all.updated'),200);
    }

    public function verifyUpdatedMail(Request $request)
    {	
    	$user = auth('api')->user();

    	$updatedmail = Updatedmail::where('activation_code',$request->code)
    							  ->where('user_id',$user->id)->first();

    	if( !$updatedmail )
      	{
        	return $this->errorResponse(trans('all.wrong_code'),null,404);
      	}

      	$user->email = $updatedmail->email;
      	$user->save();
      	$updatedmail->delete();
      	$token = $user->createToken('Fitness')->accessToken;
      	return $this->dataResponse($token,trans('all.email_updated'),200);

    }

    private function updateMail($user_id,$email)
    {
    	$updatedmail = UpdatedMail::where('user_id',$user_id);
    	if( $updatedmail )
    	{
    		$updatedmail->delete();
    	}
    	$updatedmail = Updatedmail::create([
    		'email'            => $email,
    		'activation_code' => rand ( 1000 , 9999 ),
    		'user_id'          => $user_id,
    	]);
    	
      	$updatedmail->notify(new ActivationCode($updatedmail->activation_code));
    }

    private function completeProfile($user)
    {
    	if( $user->hasRole('trainer'))
    	{
    		if($user->name && $user->email && $user->dob && $user->weight && $user->height && 
    			$user->gender && $user->description && $user->experience && $user->studies && 
    			$user->education
    		)
    		{
    			$user->is_completed = 1;
    		}
    	}

    	if( $user->hasRole('trainee'))
    	{
    		if($user->name && $user->email && $user->dob && $user->weight && $user->height && 
    			$user->gender && $user->goal
    		)
    		{
    			$user->is_completed = 1;
    		}
    	}
    	$user->save();
    }

    //get User Profile
    public function profile()
    {
        $user  =  fractal()
                ->item(auth('api')->user())
                ->transformWith(new UserTransformer('profile'))
                ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                ->includeRoles()
                ->toArray();
        return $this->dataResponse($user,null,200);
    }


}
