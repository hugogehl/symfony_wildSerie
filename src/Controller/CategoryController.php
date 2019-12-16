<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategorySearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/form", name="form_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        // On crée un objet Category
        $advert = new Category();

        $form = $this->createForm(CategorySearchType::class, $advert);

        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                // On enregistre notre objet $advert dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('form_isValide', array('id' => $advert->getId()));
            }
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('form/form.html.twig', [
            'add_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/isValide", name="isValide")
     */
    public function isValide()
    {
        return $this->render(
            'form/isValide.html.twig');
    }
}
