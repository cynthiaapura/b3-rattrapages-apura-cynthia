<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'app_product_detail')]
    public function detail(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }
    
    #[Route('/products/{id}/rate', name: 'app_product_rate', methods: ['POST'])]
    public function rate(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $em): RedirectResponse
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $rating = (float) $request->request->get('rating');
        if ($rating < 0 || $rating > 5) {
            $this->addFlash('error', 'Note invalide. Elle doit être entre 0 et 5.');
            return $this->redirectToRoute('app_product_detail', ['id' => $id]);
        }

        $product->setRating($rating);
        $em->flush();

        $this->addFlash('success', 'Merci pour votre note');

        return $this->redirectToRoute('app_product_detail', ['id' => $id]);
    }

}
