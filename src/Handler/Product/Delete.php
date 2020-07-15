<?php
declare(strict_types=1);

namespace App\Handler\Product;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class Delete
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(Product $product)
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
