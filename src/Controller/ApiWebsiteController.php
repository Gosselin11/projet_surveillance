<?php

namespace App\Controller;

use App\Entity\Website;
use App\Entity\WebsiteCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\WebsiteChecker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class ApiWebsiteController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
#[Route('/api/website', name: 'create_website', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['url'])) {
            return $this->json(['error' => 'Champs manquants'], 400);
        }

        $website = new Website();
        $website->setName($data['name']);
        $website->setUrl($data['url']);
        $website->setIsUp(true);
        $website->setLastStatus(null);
         $website->setUser($this->getUser());

        $em->persist($website);
        $em->flush();

        return $this->json([
            'message' => 'Website ajouté avec succès',
            'id' => $website->getId()
        ], 201);
    }

    #[Route('/api/website', name: 'list_websites', methods: ['GET'])]
public function list(EntityManagerInterface $em): JsonResponse
{
    $this->denyAccessUnlessGranted('ROLE_USER');

    $websites = $em->getRepository(Website::class)->findAll();

    $data = [];

    foreach ($websites as $website) {
        $data[] = [
            'id' => $website->getId(),
            'name' => $website->getName(),
            'url' => $website->getUrl(),
            'isUp' => $website->isUp(),
            'lastStatus' => $website->getLastStatus()
        ];
    }

    return $this->json($data);
}

#[Route('/api/website/{id}/check', name: 'check_website', methods: ['POST'])]
public function check(
    int $id,
    EntityManagerInterface $em,
    WebsiteChecker $checker
): JsonResponse {

    $website = $em->getRepository(Website::class)->find($id);

    if (!$website) {
        return $this->json(['error' => 'Website not found'], 404);
    }

    $result = $checker->check($website->getUrl());

    $website->setLastStatus($result['status']);
    $website->setIsUp($result['isUp']);

    $em->flush();

    return $this->json([
        'id' => $website->getId(),
        'status' => $result['status'],
        'isUp' => $result['isUp']
    ]);
}

#[IsGranted('ROLE_ADMIN')]
#[Route('/api/website/{id}', name: 'delete_website', methods: ['DELETE'])]
public function delete(int $id, EntityManagerInterface $em): JsonResponse
{
    $website = $em->getRepository(Website::class)->find($id);

    if (!$website || $website->getUser() !== $this->getUser()) {
        return $this->json(['error' => 'Not found'], 404);
    }

    $em->remove($website);
    $em->flush();

    return $this->json(['message' => 'Website deleted']);
}

#[Route('/api/website/{id}/history', name: 'website_history', methods: ['GET'])]
public function history(int $id, EntityManagerInterface $em): JsonResponse
{
    $website = $em->getRepository(Website::class)->find($id);

    if (!$website || $website->getUser() !== $this->getUser()) {
        return $this->json(['error' => 'Not found'], 404);
    }

    $checks = $em->getRepository(WebsiteCheck::class)
                 ->findBy(['website' => $website], ['checkedAt' => 'ASC']);

    $data = [];
    foreach ($checks as $check) {
        $data[] = [
            'status' => $check->getStatus(),
            'isUp' => $check->IsUp(),
            'checkedAt' => $check->getCheckedAt()->format('Y-m-d H:i:s')
        ];
    }

    return $this->json($data);
}

#[IsGranted('ROLE_USER')]
#[Route('/dashboard', name: 'dashboard')]
public function dashboard(): Response
{
    return $this->render('dashboard.html.twig');
}


}
