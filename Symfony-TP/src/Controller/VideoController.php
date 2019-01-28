<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Manager\VideoManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class VideoController extends AbstractController
{
    /**
     * @Route("/video/{byid}", name="video-id")
     * @ParamConverter("video", options={"mapping"={"byid"="id"}})
     */
    public function Video (EntityManagerInterface $entityManager, $byid)
    {
        $user = $this->getUser();
        $video = $entityManager->getRepository(Video::class)->find($byid);

        return $this->render('video/single.html.twig', [
            'video' => $video,
            'user' => $user,
        ]);
    }
}
