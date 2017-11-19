<?php

namespace AppBundle\Controller;

use AppBundle\Services\ContactManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @param ContactManager $contactManager
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard-nous-ecrire", name="contact_us")
     * @Method("POST")
     */
    public function contactUsAction(Request $request, ContactManager $contactManager, ValidatorInterface $validator)
    {
        if ($request->isXmlHttpRequest()) {

            /* Nous contacter */

            // Récupération du formulaire de contact
            $contactForm = $contactManager->getFormCreateContactUs();

            // Hydration de l'entitée avec les valeurs du formulaire
            $contactForm->handleRequest($request);

            // Soumission du formulaire
            if ($contactForm->isSubmitted()) {
                // Récupération des données du formulaire
                $data = $contactForm->getData();
                // récupère le résultat de la validation dui formulaire
                $validation = $contactManager->validateForm($contactForm);
                // traite l'envoi du mail si le formulaire est valide
                if ($validation === true) {
                    // Préparation de l'email et envoi
                    $contactManager->sendMailUser($data);
                    return new Response("Votre email a bien été envoyé nous le traiterons dans les meilleurs délais");
                }
                // renvoie les erreurs de validation si le formulaire n'est pas valide
                return new Response($validation, 500);
            }
            return new Response("Une erreur est survenue, veuillez réessayer", 500);
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @return Response
     *
     * @Route("test-email", name="test_email")
     */
    public function testEmailAction()
    {
        return $this->render('default/email/signUpMail.html.twig', array('user' => $this->getUser()));
    }
}
