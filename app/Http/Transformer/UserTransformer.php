<?php  

namespace App\Http\Transformer;

use App\Http\Transformer\RoleTransformer;
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles'];

    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(User $user)
    {
        $array = [
            'id'           => $user->hashid(),
            'name'         => $user->name,
            'email'        => $user->email,
            'gender'       => $user->gender,
            'dob'          => $user->dob,
            'weight'       => $user->weight,
            'height'       => $user->height,
            'goal'         => (int)$user->goal,
            'is_verified'  => (int)$user->is_verified,
            'is_completed' => (int)$user->is_completed,
            'is_approved'  => (int)$user->is_approved,
            
        ];

        if( $this->paramter == 'profile')
        {
            $array['description'] = $user->description;
            $array['experience'] = $user->experience;
            $array['studies'] = $user->studies;
            $array['education'] = $user->education;
            $array['freelance'] = (int)$user->freelance;
            $array['gym_name']  = $user->gym_name;
        }

        return $array;
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles()->get();
        return $this->collection($roles, new RoleTransformer) ; 
    }



}




?>