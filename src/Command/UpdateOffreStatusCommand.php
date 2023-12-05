<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateOffreStatusCommand extends Command
{
    protected static $defaultName = 'app:update-offre-status';

    private $entityManager;
    private $offreRepository;

    public function __construct(EntityManagerInterface $entityManager, OffreRepository $offreRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->offreRepository = $offreRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Update status of offres based on end date')
            ->setHelp('This command updates the status of offres based on their end date.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Get the current date and time
        $now = new \DateTime();

        // Get offres with end date less than or equal to the current date
        $offresToUpdate = $this->offreRepository->findByEndDate($now);

        foreach ($offresToUpdate as $offre) {
            // Update the status of each offre as needed
            $offre->setStatus('NouveauStatut'); // Mettez à jour avec le statut approprié

            // Persist and flush changes
            $this->entityManager->persist($offre);
        }

        $this->entityManager->flush();

        $io->success('Offre statuses updated successfully.');

        return Command::SUCCESS;
    }
}
