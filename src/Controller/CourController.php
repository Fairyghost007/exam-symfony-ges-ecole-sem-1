<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Professeur;
use App\Enums\Module;
use App\Repository\CourRepository;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{
    #[Route('/cours', name: 'app_cour')]
    public function index(): Response
    {
        return $this->render('views/cours/index.html');
    }

    #[Route('/api/cours', name: 'api_cours', methods: ['GET'])]
    public function apiCours(CourRepository $courRepository): JsonResponse
    {
        $cours = $courRepository->findAll();

        // Convert the entities to an array
        $coursData = array_map(function (Cour $cour) {
            return [
                'id' => $cour->getId(),
                'nomCours' => $cour->getNomCours(),
                'module' => $cour->getModule()?->value,
                'professeur' => $cour->getProfesseur()?->getNom(), // Assuming Professeur has a name
            ];
        }, $cours);

        return new JsonResponse($coursData);
    }

    #[Route('/api/modules', name: 'api_modules', methods: ['GET'])]
    public function apiModules(): JsonResponse
    {
        $modules = Module::cases();
        $modulesData = array_map(function (Module $module) {
            return [
                'value' => $module->value,
                'name' => $module->name,
            ];
        }, $modules);

        return new JsonResponse(['modules' => $modulesData]);
    }

    #[Route('/api/professeurs', name: 'api_professeurs', methods: ['GET'])]
    public function apiProfesseurs(ProfesseurRepository $professeurRepository): JsonResponse
    {
        $professeurs = $professeurRepository->findAll();
        $professeursData = array_map(function (Professeur $professeur) {
            return [
                'id' => $professeur->getId(),
                'nom' => $professeur->getNom(),
            ];
        }, $professeurs);

        return new JsonResponse(['professeurs' => $professeursData]);
    }

    #[Route('/cours/store', name: 'cours_store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, ProfesseurRepository $professeurRepository): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('views/cours/form.html');
        }

        $data = json_decode($request->getContent(), true);

        // Log the incoming data
        if ($data === null) {
            return new JsonResponse(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['nomCours'], $data['module'], $data['professeur'])) {
            return new JsonResponse(['message' => 'Invalid input fields'], Response::HTTP_BAD_REQUEST);
        }

        $professeur = $professeurRepository->find($data['professeur']);

        if (!$professeur) {
            return new JsonResponse(['message' => 'Professeur not found'], Response::HTTP_BAD_REQUEST);
        }

        $cour = new Cour();
        $cour->setNomCours($data['nomCours']);

        // Log the module value
        error_log('Module value: ' . $data['module']);

        // Ensure the module value is a valid enum case
        if (!in_array($data['module'], array_column(Module::cases(), 'value'))) {
            return new JsonResponse(['message' => 'Invalid module value'], Response::HTTP_BAD_REQUEST);
        }

        $module = Module::from($data['module']);
        $cour->setModule($module);
        $cour->setProfesseur($professeur);

        try {
            $entityManager->persist($cour);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Cour added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Log the error
            error_log('Error adding cour: ' . $e->getMessage());
            return new JsonResponse(['message' => 'Error adding cour: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
