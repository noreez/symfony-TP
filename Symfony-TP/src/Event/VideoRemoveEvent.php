<?php

namespace App\Event;

use App\Entity\Video;
use Symfony\Component\EventDispatcher\Event;

class VideoRemoveEvent extends Event
{
    const NAME = 'video.remove';
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