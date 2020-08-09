<?php  

namespace App\Http\Transformer;

use App\Video;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
    

    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(Video $video)
    {
        $array = [
            'id'         => $video->hashid(),
            'title'      => $video->title,
            'body_focus' => $video->body_focus,
            'video'      => $video->getFirstMediaUrl('video')
        ];

        return $array;
    }



}




?>