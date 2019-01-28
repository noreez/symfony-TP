<?php

namespace App\Manager;

use App\Repository\UserRepository;
use App\Repository\VideoRepository;

class UserManager
{
    private $userRepository;
    private $articleRepository;

    public function __construct(UserRepository $userRepository, VideoRepository $videoRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $videoRepository;
    }

    public function getNumberVideo($email)
    {
        return $this->articleRepository->countVideo($email);
    }

    public function getUserVideo($email)
    {
        return $this->articleRepository->getUserVideo($email);
    }
}