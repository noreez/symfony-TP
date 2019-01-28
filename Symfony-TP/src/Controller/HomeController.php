<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function Articleall(UserRepository $userRepository, VideoRepository $videoRepository)
    {
        $users = $userRepository->findAll();
        $videosPublished = $videoRepository->findByDate();

        return $this->render('home/index.html.twig', [
            'users' => $users,
            'videosPublished' => $videosPublished,
        ]);
    }

    /**
     * @Route("/logout_message", name="logout_message")
     */
    public function logoutMessage()
    {
        $this->addFlash('success', 'Disconnect sucess !');
        return $this->redirectToRoute('home');
    }
}
