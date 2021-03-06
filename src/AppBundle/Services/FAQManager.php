<?php

namespace AppBundle\Services;

use AppBundle\Entity\Faq;
use AppBundle\Form\Type\Faq\EditFaqType;
use AppBundle\Form\Type\Faq\NewFaqType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FAQManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $validator;
    private $paginator;

    /**
     * FAQManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param ValidatorInterface $validator
     * @param $paginator
     */
    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em,
                                RequestStack $request, ValidatorInterface $validator, $paginator) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     *  Récupère du formulaire pour ajouter une nouvelle question/ réponse
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormNewFaq() {
        $faq = new Faq();

        return $this->formBuilder->create(NewFaqType::class, $faq);
    }

    /**
     * Enregistre la question / réponse
     * @param $newFaq
     */
    public function setFaq($faq)
    {
        // Enregistrement de la nouvelle question/réponse
        $this->em->persist($faq);
        $this->em->flush();
    }

    /**
     * Supprime la question / réponse
     * @param $faqId
     * @return string
     */
    public function removeFaq($faqId)
    {
        $faq = $this->em->getRepository('AppBundle:Faq')->find($faqId);
        $this->em->remove($faq);
        $this->em->flush();
        return 'Question supprimée';
    }

    /**
     * récupère le formulaire d'édition de la question/réponse
     * @param $faqId
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormEditFaq($faqId)
    {
        $faq = $this->em->getRepository('AppBundle:Faq')->find($faqId);
        return $this->formBuilder->create(EditFaqType::class, $faq);
    }

    /**
     * Valide la question / réponse
     * @param Faq $faq
     * @return bool|string
     */
    public function validateFaq (Faq $faq)
    {
        $errors = $this->validator->validate($faq);
        if (count($errors) > 0) {
            $errorsString = "";
            foreach ($errors as $error) {
                $errorsString .=$error->getmessage().'<br>';
            }
            return $errorsString;
        }
        return true;
    }

    /**
     * Retourne la liste paginée des questions / réponses
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPaginatedFaqList()
    {
        // récupère la liste des questions/réponses
        $faqList = $this->em->getRepository('AppBundle:Faq')->findAll();
        // récupère le service knp paginator
        $paginator  = $this->paginator;
        // retourne les questions /réponse paginé selon la page passé en get
        return $paginator->paginate(
            $faqList/*$query*/, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
    }
}
