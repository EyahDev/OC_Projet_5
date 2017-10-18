<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Observation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\DataFixtures\ORM\UserFixtures;
use AppBundle\DataFixtures\ORM\SpeciesFixtures;
class ObservationFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $observation1 = new Observation();
        $observation1
            ->setSpecies($this->getReference('falcoAmurensis'))
            ->setObserver($this->getReference('user'))
            ->setValidator($this->getReference('pro'))
            ->setBirdNumber('1')
            ->setEggsNumber('0')
            ->setEggsDescription('')
            ->setLongitude('46.149807')
            ->setLatitude('-1.128526')
            ->setAltitude('3.66')
            ->setPhotoPath('')
            ->setObservationDate(\DateTime::createFromFormat('Y-m-d', '2017-10-08'))
            ->setValidate('1')
            ->setValidateDate (\DateTime::createFromFormat('Y-m-d', '2017-10-10'));
        $manager->persist($observation1);

        $observation2 = new Observation();
        $observation2
            ->setSpecies($this->getReference('acridotheres'))
            ->setObserver($this->getReference('user'))
            ->setValidator($this->getReference('admin'))
            ->setBirdNumber('3')
            ->setEggsNumber('4')
            ->setEggsDescription('')
            ->setLongitude('46.128524')
            ->setLatitude('-1.149807')
            ->setAltitude('4.25')
            ->setPhotoPath('')
            ->setObservationDate(\DateTime::createFromFormat('Y-m-d', '2017-10-10'))
            ->setValidate('1')
            ->setValidateDate (\DateTime::createFromFormat('Y-m-d', '2017-10-11'));
        $manager->persist($observation2);

        $manager->flush();

        $observation3 = new Observation();
        $observation3
            ->setSpecies($this->getReference('acridotheres'))
            ->setObserver($this->getReference('user'))
            ->setValidator($this->getReference('admin'))
            ->setBirdNumber('1')
            ->setEggsNumber('0')
            ->setEggsDescription('')
            ->setLongitude('46.128076')
            ->setLatitude('-1.185212')
            ->setAltitude('3.56')
            ->setPhotoPath('')
            ->setObservationDate(\DateTime::createFromFormat('Y-m-d', '2017-10-15'))
            ->setValidate('0');
        $manager->persist($observation3);

        $observation4 = new Observation();
        $observation4
            ->setSpecies($this->getReference('accipiterBicolor'))
            ->setObserver($this->getReference('user'))
            ->setValidator($this->getReference('admin'))
            ->setBirdNumber('1')
            ->setEggsNumber('0')
            ->setEggsDescription('')
            ->setLongitude('46.128523')
            ->setLatitude('-1.149809')
            ->setAltitude('3.56')
            ->setPhotoPath('')
            ->setObservationDate(\DateTime::createFromFormat('Y-m-d', '2017-10-17'))
            ->setValidate('0');
        $manager->persist($observation4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SpeciesFixtures::Class,
        );
    }
}