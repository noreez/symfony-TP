<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{byid}", name="user-profile")
     * @ParamConverter("user", options={"mapping"={"byid"="id"}})
     */
    public function id(User $user)
    {

        //$profile = $userRepository->findOneBy(['id'=>$id]);
        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
