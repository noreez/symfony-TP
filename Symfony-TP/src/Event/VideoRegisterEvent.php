<?php

namespace App\Event;

use App\Entity\Video;
use Symfony\Component\EventDispatcher\Event;

class VideoRegisterEvent extends Event
{
    const NAME = 'video.register';
    protected $video;

    public function __construct(Video $video
    )
    {
        $this->video = $video;
    }

    public function getUser(): Video
    {
        return $this->video;
    }
}