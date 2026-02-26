<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un utilisateur admin',
)]

class CreateAdminCommand extends Command
{
    protected static $defaultName = "app:create-admin";
    protected static $defaultDescription = 'Crée un compte admin par défaut';

    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;


    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $existingAdmin = $this->em->getRepository(User::class)->findOneBy([
            'email' => 'admin@admin.com',
        ]);

        if ($existingAdmin) {
            $output->writeln('<comment>L\'admin existe déjà !</comment>');
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin123'));

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('<info>Admin créé avec succès !</info>');

        return Command::SUCCESS;
    }
}
