<?php
declare(strict_types=1);

namespace App\Controller;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Handler\Product\Edit;
use App\Handler\Product\Delete;
use App\Form\ProductType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function list(
        Request $request
    )
    {
        return $this->render('product/list.html.twig', [
           'products' => $this->productRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detail(
        Request $request,
        int $productId)
    {
        return $this->render('product/detail.html.twig', [
            'product' => $this->productRepository->find($productId)
        ]);
    }

    public function add(
        Request $request,
        Edit $handler
    )
    {
        $productCode = $request->request->get('product') ? $request->request->get('product')['code'] : null;
        if ($productCode && $this->productRepository->findOneBy(['code' => $productCode])) {
            $this->addFlash('error', 'Produkt s tímto kódem je už v databázi');
            return $this->redirect($this->generateUrl('add_product'));
        }
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($product, $request);
            return $this->redirectToRoute('list_products', [
                'products' => $this->productRepository->findAll()
            ]);
        }
        return $this->render('product/edit.html.twig', [
           'form' => $form->createView()
        ]);
    }

    public function edit(
        Request $request,
        int $productId,
        Edit $handler
    )
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Product is not found');
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($product, $request);
            return $this->redirectToRoute('show_detail', [
                'productId' => $product->getId()
            ]);
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    public function delete(
        Request $request,
        int $productId,
        Delete $handler
    )
    {
        $product = $this->productRepository->find($productId);
        $handler->handle($product);
        return $this->redirectToRoute('list_products');
    }
}

