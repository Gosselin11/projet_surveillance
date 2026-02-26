<?php

namespace App\Controller;

use App\Entity\Website;
use App\Repository\WebsiteCheckRepository;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
public function index(
    WebsiteRepository $websiteRepository
): Response {

    $this->denyAccessUnlessGranted('ROLE_USER');

    $websites = $websiteRepository->findAll();

    return $this->render('dashboard.html.twig', [
        'websites' => $websites
    ]);
}
}
