<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /* Gestion des catégories */

    /**
     * @Route("/dasboard/categorie/{id}/edition/", name="edit_category")
     */
    public function editCategoryAction($id, BlogManager $blogManager, Request $request) {

        // Récupération du formulaire de modification de la catégorie
        $updateCategoryForm = $blogManager->getFormUpdateCategory($id);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateCategoryForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateCategoryForm->isSubmitted() && $updateCategoryForm->isValid()) {

            // Récupération de l'entitée Catégory avec les valeurs hydratées
            $category = $updateCategoryForm->getData();

            // Enregistrement de la nouvelle catégorie
            $blogManager->setCategory($category);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/blogManagement/editCategory.html.twig", array(
            'updateCategoryForm' => $updateCategoryForm->createView()
        ));
    }

    /**
     * @Route("/dashboard/categorie/{id}/confirmation-suppression", name="advice_delete")
     */
    public function deleteConfirmationCategoryAction($id, BlogManager $blogManager) {
        // Récupération des informations lié à la catégorie
        $category = $blogManager->getCategory($id);

        return $this->render("default/dashboard/blogManagement/deleteCategory.html.twig", array(
            'infoCategory' => $category
        ));
    }

    /**
     * @Route("/dashboard/categorie/{id}/suppression", name="category_delete")
     */
    public function deleteCategoryAction($id, BlogManager $blogManager) {
        // Supression de la catégorie
        $blogManager->deleteCategory($id);

        // Rédirection vers le dashboard
        return $this->redirectToRoute('dashboard');
    }

    /* Gestion des articles */
}