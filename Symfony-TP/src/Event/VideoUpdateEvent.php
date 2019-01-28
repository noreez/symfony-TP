<?php

namespace App\Event;

use App\Entity\Video;
use Symfony\Component\EventDispatcher\Event;

class VideoUpdateEvent extends Event
{
    const NAME = 'video.update';
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