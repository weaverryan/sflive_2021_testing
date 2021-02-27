<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\AddItemToCartFormType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Route("/category/{id}", name="app_category")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository, Category $category = null): Response
    {
        $searchTerm = $request->query->get('q');
        $products = $productRepository->search(
            $searchTerm,
            $category
        );

        if ($request->query->get('preview')) {
            return $this->render('product/_searchPreview.html.twig', [
                'products' => $products,
            ]);
        }

        return $this->render('product/index.html.twig', [
            'currentCategory' => $category,
            'categories' => $categoryRepository->findAll(),
            'products' => $products,
            'searchTerm' => $searchTerm
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product", methods={"GET"})
     */
    public function showProduct(Product $product, CategoryRepository $categoryRepository): Response
    {
        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
            'product' => $product
        ]);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'currentCategory' => $product->getCategory(),
            'categories' => $categoryRepository->findAll(),
            'addToCartForm' => $addToCartForm->createView()
        ]);
    }

    /**
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product', ['id' => $product->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_product', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
