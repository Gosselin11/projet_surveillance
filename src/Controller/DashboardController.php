<?php

namespace App\Controller;

use App\Repository\WebsiteRepository;
use App\Service\WebsiteChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(WebsiteRepository $websiteRepository, WebsiteChecker $checker, ?int $id = null): Response
    {

        $allWebsites = $websiteRepository->findAll();
// var_dump($id);
// die;
    // $selectedWebsite = $id ? $websiteRepository->find($id) : ($allWebsites[0] ?? null);

    // if ($selectedWebsite) {
    //    $checker->checkWebsite($selectedWebsite);
   // }

   // $checks = $selectedWebsite ? $selectedWebsite->getChecks() : [];

        foreach ($allWebsites as $site) {
            $checker->checkWebsite($site);
        }

        $selectedWebsite = !empty($allWebsites) ? $allWebsites[0] : null;
        $checks = $selectedWebsite ? $selectedWebsite->getChecks() : [];

        $labels = [];
        $dataStatus = [];
        if ($selectedWebsite) {
            $history = array_slice($checks->toArray(), -10);
            foreach ($history as $check) {
                $labels[] = $check->getCheckedAt()->format('H:i');
                $dataStatus[] = $check->isUp() ? 1 : 0;
            }
        }
// dd($allWebsites);
        return $this->render('dashboard.html.twig', [
            'websites' => $allWebsites,
            'selectedWebsite' => $selectedWebsite,
            'checks' => $checks,
            'labels' => $labels,
            'dataStatus' => $dataStatus,
        ]);
    }
}
