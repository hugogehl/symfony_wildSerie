<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
Class WildController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/show/{slug}",
     *     requirements={"slug"="[a-z0-9\.-]+"},
     *     defaults={"slug"="Aucune série sélectionnée"},
     *     name="show"
     * )
     */
    public function show($slug): Response
    {
        $slug = ucwords(str_replace("-", " ", $slug));
        return $this->render('wild/show.html.twig', ['slug' => $slug]);
    }
}