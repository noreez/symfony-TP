<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Video;
use App\Entity\User;
use App\Event\VideoRegisteredEvent;
use App\Event\VideoRemoveEvent;
use App\Event\VideoUpdateEvent;
use App\Form\CategoryFormType;
use App\Form\RegisterUserType;
use App\Form\UserType;
use App\Form\VideoType;
use App\Manager\VideoManager;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $userRepository)
    {
        $users = $userRepository->findLastUser();

        return $this->render('admin-template.html.twig', [

        ]);
    }

    /**
     * @Route("/admin/account", name="admin-profile")
     */
    public function profile(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, Profil Update sucess !');
            return $this->redirectToRoute('admin-profile');
        }
        return $this->render('admin/user/admin-account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users", name="admin-users")
     */
    public function users(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin-user")
     * @ParamConverter("user", options={"mapping"={"id"="id"}})
     */
    public function user(VideoRepository $videoRepository, UserRepository $userRepository, $id)
    {
        $namepage = 'User';

        $user = $userRepository->find($id);
        $videosPublished = $videoRepository->findByDate();
        $videosNotPublished = $videoRepository->findByDateSup();

        return $this->render('admin/video/user-videos.html.twig', [
            'user' => $user,
            'videosPublished' => $videosPublished,
            'videosNotPublished' => $videosNotPublished,
            'namepage' => $namepage,
        ]);
    }



    /**
     * @Route("/admin/users/remove/{id}", name="user-remove")
     */
    public function removeUser(User $user, EntityManagerInterface $entityManager, $id)
    {
        $videos = $user->getVideo();
        foreach ($videos as $video) {
                $video->setUser(null);
                $entityManager->remove($user);
        }
        $user = $this->getUser();
        foreach ($videos as $video) {
            $video->setUser($user);
        }
        $entityManager->flush();
        $this->addFlash('success', 'Congratulations, User Remove sucess!');
        return $this->redirectToRoute('admin-users');
    }

    /**
     * @Route("/admin/add/users", name="add-user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {

        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, User register sucess !');
            return $this->redirectToRoute('admin-users');
        }

        return $this->render('admin/user/add-user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/video", name="admin-video")
     */
    public function video(VideoRepository $videoRepository)
    {
        $videosPublished = $videoRepository->findByDate();
        $videosNotPublished = $videoRepository->findByDateSup();

        return $this->render('admin/video/video.html.twig', [
            'videosPublished' => $videosPublished,
            'videosNotPublished' => $videosNotPublished,
        ]);
    }


    /**
     * @Route("/admin/update/video/{id}", name="admin-update-video")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     */
    public function VideoAdminUpdate (Request $request, $id, EventDispatcherInterface $eventDispatcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $video = $entityManager->getRepository(Video::class)->find($id);
        if (!$video) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();
            $event = new VideoUpdateEvent($video);
            $eventDispatcher->dispatch(VideoUpdateEvent::NAME,$event);
            $this->addFlash('success', 'Congratulations, Video Update !');
            return $this->redirectToRoute('admin-video');
        }
        return $this->render('admin/video/update-video.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/add/video", name="admin-add-video")
     */
    public function VideoAdd(Request $request, VideoManager $videoManager, EventDispatcherInterface $eventDispatcher)
    {
        $video = new Video();
        $user = $this->getUser();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $url = $video->getUrl();
            $urlfinal = $videoManager->finalUrl($url);
            $video->setFinalUrl($urlfinal);
            $video->setUser($user);
            $entityManager->persist($video);
            $entityManager->flush();
            $event = new VideoRegisteredEvent($video);
            $eventDispatcher->dispatch(VideoRegisteredEvent::NAME,$event);
            $this->addFlash('success', 'Congratulations, Video added !');
            return $this->redirectToRoute('admin-video');
        }

        return $this->render('admin/video/add-video.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/video/remove/{id}", name="video-remove")
     * @ParamConverter("video", options={"mapping"={"id"="id"}})
     */
    public function VideoRemove(Video $video, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $entityManager->remove($video);
        $entityManager->flush();
        $event = new VideoRemoveEvent($video);
        $eventDispatcher->dispatch(VideoRemoveEvent::NAME,$event);
        $this->addFlash('success', 'You are successfully remove Video!');
        return $this->redirectToRoute('admin-video');
    }

    /**
     * @Route("/admin/category", name="admin-category")
     */
    public function category(CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findAll();

        return $this->render('admin/category/category.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin/add/category", name="add-category")
     */
    public function CategoryAdd(Request $request, EntityManagerInterface $entityManager)
    {

        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, Category add!');
            return $this->redirectToRoute('admin-category');
        }

        return $this->render('admin/category/add-category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/category/remove/{id}", name="remove-category")
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     */
    public function CategoryRemove(Category $category, VideoRepository $videoRepository, EntityManagerInterface $entityManager)
    {
        $videos = $category->getVideo();
        foreach ($videos as $video) {
            $video->setCategory(null);
        }
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'Congratulations, Category remove!');
        return $this->redirectToRoute('admin-category');
    }

    /**
     * @Route("/admin/update/category/{id}", name="update-category")
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     */
    public function VideoCategoryUpdate(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations, Category update!');
            return $this->redirectToRoute('admin-category');
        }

        return $this->render('admin/category/update-category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
}
