<?php

namespace AppBundle\Command;

use AppBundle\Entity\Species;
use AppBundle\Entity\SpeciesFamily;
use AppBundle\Entity\SpeciesType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // Nom et description pour la commande de bin/console
        $this
            ->setName('import:csv')
            ->setDescription('Importe les espèces depuis un fichier CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Montre quand le script est lancé
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        // Importe le csv on DB via Doctrine
        $this->import($input, $output);

        // Montre quand le script est terminé
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Récupère un tableau de données depuis le csv
        $data = $this->get($input, $output);

        // Récupère l'entity manager
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Desactive l'enregistrement des logs de Doctrine pour economiser de la mémoire
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        // Détermine la taille du tableau, la fréquence des flush et la position actuelle dans le tableau
        $size = count($data);

        // Démarre la barre de progression
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Parcourt chaque ligne du tableau
        foreach($data as $row) {
            // récupère l'ordre dans la bdd en fonction de l'ordre renseigné sur la ligne
            $type = $em->getRepository('AppBundle:SpeciesType')->findOneBy(array('name' => $row['type']));
            // si l'ordre n'existe pas on le crée
            if(!is_object($type)){
                $type = new SpeciesType();
                $type->setName($row['type']);
            }
            // récupère la famille dans la bdd en fonction de la famille renseignée sur la ligne
            $family = $em->getRepository('AppBundle:SpeciesFamily')->findOneBy(array('name' => $row['family']));
            // si la famille n'existe pas on le crée
            if(!is_object($family)){
                $family = new SpeciesFamily();
                $family->setName($row['family']);
            }
            // récupère l'espece dans la bdd en fonction de l'espèce renseignée sur la ligne
            $species = $em->getRepository('AppBundle:Species')->findOneBy(array('referenceName' => $row['referenceName']));
            // si l'espèce n'existe pas on le crée
            if(!is_object($species)){
                $species = new Species();
                $species->setReferenceName($row['referenceName']);
            }

            // mise a jour de l'espèce
            $species->setVernacularName($row['vernacularName']);
            $species->setType($type);
            $species->setFamily($family);

            // mise à jour de la famille
            $family->addSpecy($species);
            $family->setType($type);

            // mise à jour de l'ordre
            $type->addSpecy($species);
            $type->addFamily($family);



            // persiste les objets
            $em->persist($species);
            $em->persist($family);
            $em->persist($type);

            $em->flush();
            // Détache tous les objets de doctrine pour économiser de la memoire
            $em->clear();
        }

        $em->flush();
        $em->clear();

        // Termine la progression
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // récupère le fichier à importer
        $fileName = 'web/uploads/import/species-taxref.csv';

        // Utilise un service pour convertir le csv en tableau php
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');

        return $data;
    }
}
