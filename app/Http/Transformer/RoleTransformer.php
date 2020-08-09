<?php  

namespace App\Http\Transformer;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{

    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(Role $role)
    {
        $array = [

            'name'      => $role->name,
            
        ];

        return $array;
    }  

}


?>
