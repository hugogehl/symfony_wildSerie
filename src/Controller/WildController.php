<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
Class WildController extends AbstractController
{
    /**
     * * Show all rows from Program’s entity
     *
     * @Route("/index", name="index")
     * @return Response A response instance
     */

    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug}",
     *     requirements={"slug"="[a-z0-9\.-]+"},
     *     defaults={"slug"="Aucune série sélectionnée"},
     *     name="show"
     * )
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug
        ]);
    }

    /**
     * Show selected category
     *
     * @param string $categoryName The category name
     * @Route("/category/{categoryName}",
     *     requirements={"categoryName"="[a-zA-Z0-9\.-]+"},
     *     defaults={"categoryName"="null"},
     *     name="show_category"
     * )
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a category in category\'s table.');
        }

        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $category = $this->getDoctrine()
            ->getManager()
            ->getRepository(Program::class);
        $listeFilms = $category->findBy(
            array('category' => $program),
            array('id' => 'desc'),
            3,
            0
        );

        return $this->render('wild/category.html.twig', [
            'categoryName' => $listeFilms
        ]);
    }

    /**
     * @param string $programName
     * @Route("/program/{programName}",
     * defaults={"programName"="null"},
     * name="show_program"
     * )
     * @return Response
     */
    public function showByProgram(string $programName): Response
    {
        if (!$programName) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a category in category\'s table.');
        }

        $programName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programName)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $programName]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findAll();

        return $this->render('wild/program.html.twig', [
            'programName' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @param int $seasonId
     * @Route("/season/{seasonId}",
     * requirements={"seasonId"="[0-9]+"},
     * defaults={"seasonId"="null"},
     * name="show_season"
     * )
     * @return Response
     */
    public function showBySeason(int $seasonId): Response
    {
        $seasonId = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $seasonId]);

        $episodes = $seasonId->getEpisodes();
        $program = $seasonId->getProgram();

        return $this->render('wild/season.html.twig', [
            'seasonId' => $seasonId,
            'episodes' => $episodes,
            'program' => $program
        ]);
    }

    /**
     * @param Episode $episodeDetails
     * @param EpisodeRepository $episodeRepository
     * @Route("/episode/{id}",
     * requirements={"id"="[0-9]+"},
     * defaults={"id"="null"},
     * name="show_episode"
     * )
     * @return Response
     */
    public function showEpisode(Episode $episodeDetails, EpisodeRepository $episodeRepository): Response
    {
        $saisons = $episodeDetails->getProgram();
        $episodes = $episodeDetails->getSeason();

        return $this->render('wild/episode.html.twig', [
            'episodeDetails'=>$episodeRepository->findById($episodeDetails),
            'episodes' => $episodes,
            'saisons' => $saisons
        ]);
    }

}