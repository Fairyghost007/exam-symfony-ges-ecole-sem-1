<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NiveauController extends AbstractController
{
    #[Route('/niveau', name: 'app_niveau')]
    public function index(): Response
    {
        return $this->render('views/niveau/index.html');
    }

    #[Route('/api/niveaux', name: 'api_niveaux', methods: ['GET'])]
    public function apiNiveaux(NiveauRepository $niveauRepository): JsonResponse
    {
        $niveaux = $niveauRepository->findAll();

        // Convert the entities to an array
        $niveauxData = array_map(function (Niveau $niveau) {
            return [
                'id' => $niveau->getId(),
                'nomNiveau' => $niveau->getNomNiveau(),
            ];
        }, $niveaux);

        return new JsonResponse($niveauxData);
    }

    #[Route('/niveau/store', name: 'niveau_store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('views/niveau/form.html');
        }

        $data = json_decode($request->getContent(), true);

        // Log the incoming data
        if ($data === null) {
            return new JsonResponse(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['nomNiveau'])) {
            return new JsonResponse(['message' => 'Invalid input fields'], Response::HTTP_BAD_REQUEST);
        }

        $niveau = new Niveau();
        $niveau->setNomNiveau($data['nomNiveau']);

        try {
            $entityManager->persist($niveau);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Niveau added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Log the error
            error_log('Error adding niveau: ' . $e->getMessage());
            return new JsonResponse(['message' => 'Error adding niveau: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
