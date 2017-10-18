<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SpeciesFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {


        // creation d'une éspèce famille accipitridae
        $accipiterBicolor = new Species();
      
        $accipiterBicolor
            ->setReferenceName('Accipiter bicolor (Vieillot, 1817)')
            ->setVernacularName('Épervier bicolore')
            ->setType('Accipitriformes')
            ->setFamily('Accipitridae')
            ->setDescription('La description de cette espèce est très complexe car il y a de multiples formes de plumage. 
            Chez les adultes, les parties supérieures varient du gris-ardoise au gris-noir. 
            Le capuchon est plus sombre. La queue noirâtre a une extrémité blanche et est ornée de 2 ou 3 bandes grises, 
            brunes ou gris-brun. Les cuisses, qui sont toujours rousses, sont parfois masquées par les plumes du ventre. 
            La zone anale est blanche, le reste du dessous est très variable avec un apport plus ou moins important de stries sombres.');

        $manager->persist($accipiterBicolor);
        $this->addReference('accipiterBicolor', $accipiterBicolor);


        // creation d'une éspèce famille sturnidae
        $acridotheres = new Species();
        $acridotheres
            ->setReferenceName('Acridotheres fuscus (Wagler, 1827)')
            ->setVernacularName('')
            ->setType('Passeriformes (Passereaux)')
            ->setFamily('Sturnidae (Étourneaux)e')
            ->setDescription('');

        $manager->persist($acridotheres);
        $this->addReference('acridotheres', $acridotheres);


        // creation d'une éspèce famille apodidae
        $acrocephalus = new Species();
        $acrocephalus
            ->setReferenceName('Acrocephalus atyphus atyphus (Wetmore, 1919)')
            ->setVernacularName('Salangane des Mascareignes')
            ->setType('Caprimulgiformes')
            ->setFamily('Apodidae')
            ->setDescription('');
        $manager->persist($acrocephalus);

        $this->addReference('acrocephalus', $acrocephalus);

        // creation d'une éspèce famille accipitridae
        $falcoAmurensis = new Species();
        $falcoAmurensis

            ->setReferenceName('Falco amurensis Radde, 1863')
            ->setVernacularName('Faucon de l\'Amour')
            ->setType('Falconiformes (Rapaces diurnes)')
            ->setFamily('Falconidae')
            ->setDescription('Le faucon de l\'Amour est un faucon de petite taille. Chez cet oiseau migrateur, le dimorphisme sexuel est très marqué. 
            Le mâle ressemble au Faucon kobez, sauf que ses axillaires et ses couvertures sous-alaires sont blanches. 
            Le plumage est entièrement gris foncé. Le bas ventre est châtain, les cuisses, les pattes et les pieds sont rouge orangé. 
            Le bec crochu est noir avec la cire rouge orangé, les yeux ont une couleur brun foncé et un cercle orbital rouge orangé. ');
      
        $manager->persist($falcoAmurensis);
        $this->addReference('falcoAmurensis', $falcoAmurensis);
        

        $manager->flush();
    }
}