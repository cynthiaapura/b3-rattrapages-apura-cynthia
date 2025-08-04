<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CartController extends AbstractController
{
    private EntityManagerInterface $em;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $em,
        CartRepository $cartRepository,
        ProductRepository $productRepository,
    ) 
    {
        $this->em = $em;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartRepository->findOneBy(['status' => 'pending']);
        if (!$cart) {
            $cart = new Cart();
            $this->em->persist($cart);
            $this->em->flush();
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id): RedirectResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('app_cart');
        }

        $cart = $this->cartRepository->findOneBy(['status' => 'pending']);
        if (!$cart) {
            $cart = new Cart();
            $this->em->persist($cart);
        }

        $cart->addProduct($product, 1);

        $this->em->flush();

        $this->addFlash('success', 'Produit ajouté au panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id): RedirectResponse
    {
        $cart = $this->cartRepository->findOneBy(['status' => 'pending']);
        if (!$cart) {
            $this->addFlash('error', 'Panier introuvable.');
            return $this->redirectToRoute('app_cart');
        }

        $product = $this->productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_cart');
        }

        $cart->removeProduct($product);
        $this->em->flush();

        $this->addFlash('success', 'Produit supprimé du panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/validate', name: 'app_cart_validate', methods: ['POST'])]
    public function validate(): RedirectResponse
    {
        $cart = $this->cartRepository->findOneBy(['status' => 'pending']);

        if (!$cart) {
            $this->addFlash('error', 'Aucun panier en cours trouvé.');
            return $this->redirectToRoute('app_cart');
        }

        try {
            $cart->validate();
            $this->em->flush();
            $this->addFlash('success', 'Votre panier a été validé avec succès.');
        } catch (\LogicException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }
}
