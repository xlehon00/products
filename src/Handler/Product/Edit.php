<?php
declare(strict_types=1);

namespace App\Handler\Product;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;

class Edit
{
    private EntityManagerInterface $entityManager;

    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(Product $product, Request $request)
    {
        $data = $request->request->get('product');
        $category = $this->categoryRepository->find((int) $data['category']);

        $product->setName($data['name']);
        $product->setCode($data['code']);
        $product->setPrice((int) $data['price']);
        $product->setCategory($category);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
