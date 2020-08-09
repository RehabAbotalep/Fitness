<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Transformer\UserTransformer;
use App\Notifications\ApproveTrainer;
use App\Notifications\CancelApprovement;
use App\User;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    use ApiResponse;

    //get All Trainers
    public function trainers()
    {
    	$trainers =  fractal()
                    ->collection(User::role('trainer')->get())
                    ->transformWith(new UserTransformer('profile'))
                    ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                    ->includeRoles()
                    ->toArray();

        return $this->dataResponse($trainers,null,200);
    }

    //approve trainer
    public function approve($id)
    {
    	$trainer = User::findByHashidOrFail($id);
    	$trainer->is_approved = 1;
    	$trainer->save();

    	$trainer->notify( new ApproveTrainer());

    	return $this->dataResponse(null,trans('all.approved'),200);
    }

    //cancel approvement 
    public function cancel($id)
    {
    	$trainer = User::findByHashidOrFail($id);
    	$trainer->is_approved = 0;
    	$trainer->save();

    	$trainer->notify( new CancelApprovement());

    	return $this->dataResponse(null,trans('all.cancelled'),200);
    }
}
