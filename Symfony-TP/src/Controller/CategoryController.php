<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\CategoryRepository;
use App\Repository\VideoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="single-category")
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     */
    public function VideoAdminUpdate(CategoryRepository $categoryRepository, VideoRepository $videoRepository, $id)
    {
        $category = $categoryRepository->find($id);
        $videos = $videoRepository->getCategoryVideo($id);
        return $this->render('category/category.html.twig', [
            'videos' => $videos,
            'category' => $category,
        ]);
    }
}
