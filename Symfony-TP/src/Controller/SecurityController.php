<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginUserType;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager)
    {
        $user = new User();
        $form = $this->createForm(LoginUserType::class, $user);
        if (is_null($authenticationUtils->getLastAuthenticationError())) {
            $error = $authenticationUtils->getLastAuthenticationError();
        } else {
            $error = 'Wrong Password or Email';
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);
    }


    
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {

        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, Account create !');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/signin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
