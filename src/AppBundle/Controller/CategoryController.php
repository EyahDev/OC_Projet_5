<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class CategoryController extends Controller
{
    /* Gestion des catégories */

    /**
     * @Route("/dashboard/categorie/{slug}/edition/", name="edit_category")
     */
    public function editCategoryAction($slug, BlogManager $blogManager, Request $request) {

        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // récupère le formulaire d'ajout d'une question / réponse
            $updateCategoryForm = $blogManager->getFormUpdateCategory($slug);

            // récupère la categorie
            $category = $blogManager->getCategory($slug);

            // Récupération du fichier existant
            $existingFile = $updateCategoryForm->getData()->getPhotoPath();

            // Hydration de l'entitée avec les valeurs du formulaire
            $updateCategoryForm->handleRequest($request);
            // teste si la requete est en POST et si les données sont valides
            if($updateCategoryForm->isSubmitted()) {
                // Récupération de l'entitée Catégory avec les valeurs hydratées
                $category = $updateCategoryForm->getData();

                // Valide la question/réponse et récupère les erreurs de formulaire si il y en a
                $validation = $blogManager->validateCategory($category);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement de la nouvelle catégorie
                $blogManager->setUpdateCategory($category, $existingFile);
                // renvoie la ligne de tableau pour l'affichage en JS
                return $this->render('default/dashboard/blogManagement/categoriesManagement/reloadImg.html.twig', array(
                    'category' => $category,
                ));
            }
            // renvoie le formulaire d'ajout pour l'affichage en JS
            return $this->render('default/dashboard/blogManagement/categoriesManagement/editCategory.html.twig', array(
                'updateCategoryForm' => $updateCategoryForm->createView(),
                'category' => $category
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @Route("/dashboard/creer-categorie", name="create_category")
     */
    public function createCategoryAction(Request $request, BlogManager $blogManager)
    {
        if($request->isXmlHttpRequest()) {
            // Récupération du formulaire de création d'une nouvelle catégorie
            $createCategory = $blogManager->getFormCreateCategory();

            // Hydration de l'entitée avec les valeurs du formulaire
            $createCategory->handleRequest($request);

            // Soumission du formulaire
            if ($createCategory->isSubmitted()) {

                // Récupération de l'entitée Category avec les valeurs hydratées
                $category = $createCategory->getData();

                // récupère le résultat de la validation
                $validation = $blogManager->validateCategory($category);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }

                // Enregistrement de la nouvelle catégorie
                $blogManager->setCategory($category);

                // Rédirection vers le dashboard
                return new Response("Nouvelle catégorie ajoutée");
            }
        }
        throw new AccessDeniedHttpException('Vous ne pouvez pas acceder a cette page');
    }

    /**
     * @param Request $request
     * @param BlogManager $blogManager
     * @return Response
     * @Route("/dashboard/creer-categorie-rapidement", name="create_category_quickly")
     */
    public function createCategoryQuicklyAction(Request $request, BlogManager $blogManager)
    {

        // Récupération du formulaire de création d'une nouvelle catégorie
        $createCategory = $blogManager->getFormCreateQuicklyCategory();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createCategory->handleRequest($request);

        // Soumission du formulaire
        if ($createCategory->isSubmitted()) {

            // Récupération de l'entitée Category avec les valeurs hydratées
            $category = $createCategory->getData();

            // récupère le résultat de la validation
            $validation = $blogManager->validateCategory($category);
            // si la validation n'est pas ok on renvoie les erreurs du validateur
            if($validation !== true) {
                return new Response($validation,500);
            }

            // Enregistrement de la nouvelle catégorie
            $blogManager->setCategory($category);

            // Rédirection vers le dashboard
            return new Response("Nouvelle catégorie ajoutée");
        }
        return new Response('Agnagna a marche pas', 500);

    }


    /**
     * @Route("/dashboard/categorie/{slug}/suppression", name="category_delete")
     */
    public function deleteCategoryAction(Request $request, $slug, BlogManager $blogManager) {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // Supression de la catégorie
            $blogManager->deleteCategory($slug);
            // envoie le message de confirmation pour l'afficher en JS
            return new Response("Catégorie supprimée");
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /* PAGINATEUR */

    /**
     * @param Request $request
     * @return Response
     * @Route("dashboard/categories", name="pagination_categories")
     */
    public function paginationCategoriesAction(Request $request, BlogManager $blogManager)
    {
        if($request->isXmlHttpRequest()) {

            // Récupération de la liste des catégories
            $categoriesList = $blogManager->getPaginatedCategoriesList();
            return $this->render('default/dashboard/blogManagement/categoriesManagement/paginatedTable.html.twig', array(
                'categoriesList' => $categoriesList,
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }
}