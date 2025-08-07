<?php

namespace App\Controller;

use App\Operation\CategoryService; // ✅ correct namespace
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        $data = array_map(function($category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'type' => $category->getType(),
                'description' => $category->getDescription(),
            ];
        }, $categories);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['type'])) {
            return $this->json(['error' => 'Missing required fields: name, type'], 400);
        }

        $category = $this->categoryService->create($data);

        return $this->json([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'type' => $category->getType(),
            'description' => $category->getDescription(),
        ], 201);
    }
}
