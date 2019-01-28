<?php

namespace App\Manager;

use App\Repository\UserRepository;
use App\Repository\VideoRepository;

class VideoManager
{
    private $userRepository;
    private $videoRepository;

    public function __construct(UserRepository $userRepository, VideoRepository $videoRepository)
    {
        $this->userRepository = $userRepository;
        $this->videoRepository = $videoRepository;
    }

    public function allVideo()
    {
        return $this->videoRepository->findAll();
    }

    public function urlVideo()
    {
        return $this->videoRepository->getVideoUrl();
    }

    public function finalUrl($url)
    {
        if (preg_match("#^(http|https)://www.youtube.com/#", $url))  // Si c’est une url Youtube 
        {
            $youtube = "https://www.youtube.com/embed/";
        }
       
        $youtube = "https://www.youtube.com/embed/";

        $urldecoupe = explode("=", $url);
        return $youtube.$urldecoupe[1];
    }
}