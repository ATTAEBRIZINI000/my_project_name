<?php

namespace App\Operation;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create and save a new Category entity from data array
     *
     * Expected keys in $data: 'name', 'type', optionally 'description'
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        $category = new Category();
        $category->setName($data['name'] ?? '');
        $category->setType($data['type'] ?? '');
        $category->setDescription($data['description'] ?? null);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     * Get all Category entities
     *
     * @return Category[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Category::class)->findAll();
    }
}
