<?php

namespace App\Command;

use App\Entity\Website;
use App\Entity\WebsiteCheck;
use App\Service\WebsiteChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-websites',
    description: 'Check all websites and store history'
)]
class CheckWebsitesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private WebsiteChecker $checker
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $websites = $this->em->getRepository(Website::class)->findAll();

        foreach ($websites as $website) {

            $result = $this->checker->checkWebsite($website);
            $website->setLastStatus($result['status']);
            $website->setIsUp($result['isUp']);

            $check = new WebsiteCheck();
            $check->setWebsite($website);
            $check->setStatus($result['status']);
            $check->setIsUp($result['isUp']);
            $check->setCheckedAt(new \DateTime());

            $this->em->persist($check);

            $output->writeln("Checked: " . $website->getUrl());
        }

        $this->em->flush();

        $output->writeln("All websites checked successfully.");

        return Command::SUCCESS;
    }
}
