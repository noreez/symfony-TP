<?php

namespace App\Controller;

use App\Entity\Video;
use App\Event\VideoRegisterEvent;
use App\Event\VideoRemoveEvent;
use App\Form\OnlyUserType;
use App\Form\VideoType;
use App\Manager\UserManager;
use App\Manager\VideoManager;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function user()
    {
        return $this->render('user-template.html.twig', [
        ]);
    }

    /**
     * @Route("/user/my-account", name="my-account")
     */
    public function profile(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(OnlyUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, Profil Update!');
            return $this->redirectToRoute('my-account');
        }
        return $this->render('user/user/my-account.html.twig',
            ['form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/user/video", name="user-video")
     */
    public function video(VideoRepository $videoRepository, UserManager $userManager)
    {
        $user = $this->getUser();
        $videosPublished = $videoRepository->findByDate();
        $videosNotPublished = $videoRepository->findByDateSup();

        return $this->render('user/video/video.html.twig', [
            'user' => $user,
            'videosPublished' => $videosPublished,
            'videosNotPublished' => $videosNotPublished,
        ]);
    }

    /**
     * @Route("user/video/remove/{id}", name="user-remove-video")
     * @ParamConverter("article", options={"mapping"={"id"="id"}})
     */
    public function VideoRemove(Video $video, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $entityManager->remove($video);
        $entityManager->flush();
        $event = new VideoRemoveEvent($video);
        $eventDispatcher->dispatch(VideoRemoveEvent::NAME,$event);
        $this->addFlash('success', 'Congratulations, Video remove!');
        return $this->redirectToRoute('user-video');
    }
    

    /**
     * @Route("user/add/video", name="user-add-video")
     */
    public function VideoAdd(Request $request, VideoManager $videoManager, EventDispatcherInterface $eventDispatcher)
    {
        $video = new Video();
        $user = $this->getUser();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        $videos = $this->getDoctrine()->getRepository(Video::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $url = $video->getUrl();
            $urlfinal = $videoManager->finalUrl($url);
            $video->setFinalUrl($urlfinal);
            $video->setUser($user);
            $entityManager->persist($video);
            $entityManager->flush();
            $event = new VideoRegisterEvent($video);
            $eventDispatcher->dispatch(VideoRegisterEvent::NAME,$event);
            $this->addFlash('success', 'Congratulations, Video add!');
            return $this->redirectToRoute('user-video');
        }

        return $this->render('user/video/add-video.html.twig', [
            'form' => $form->createView(),
            'video' => $videos,
        ]);
    }


    /**
     * @Route("user/video/update/{id}", name="user-update-video")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     */
    public function VideoUpdate(Request $request, VideoManager $videoManager, $id, EventDispatcherInterface $eventDispatcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $video = $entityManager->getRepository(Video::class)->find($id);
        $namepage = 'Update Video';

        if (!$video) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $url = $video->getUrl();
            $urlfinal = $videoManager->finalUrl($url);
            $video->setFinalUrl($urlfinal);
            $entityManager->persist($video);
            $entityManager->flush();
            $event = new VideoRemoveEvent($video);
            $eventDispatcher->dispatch(VideoRemoveEvent::NAME,$event);
            $this->addFlash('success', 'Congratulations, Video Update!');
            return $this->redirectToRoute('user-video');
        }
        return $this->render('user/video/update-video.html.twig', [
            'form' => $form->createView(),
            'namepage' => $namepage,
        ]);
    }
}