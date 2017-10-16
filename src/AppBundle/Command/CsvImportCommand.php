<?php

namespace AppBundle\Command;

use AppBundle\Entity\Species;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CsvImportCommand
 * @package AppBundle\ConsoleCommand
 */
class CsvImportCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvImportCommand constructor.
     *
     * @param EntityManagerInterface $em
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    /**
     * Configure
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Imports the entite-especes CSV data file')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $reader = Reader::createFromPath('%kernel.root_dir%/../src/AppBundle/Data/entite-especes.csv');

        // https://github.com/thephpleague/csv/issues/208
        $results = $reader->fetchAssoc();

        foreach ($results as $row) {

            // create new species
            $species = (new Species())
                ->setReferenceName($row['referenceName'])
                ->setVernacularName($row['vernacularName'])
                ->setType($row['type'])
                ->setFamily($row['family']);


            $this->em->persist($species);

        }

        $this->em->flush();

        $io->success('Command exited cleanly!');
    }
}