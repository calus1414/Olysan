<?php

namespace  App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entity;
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;

    public function __construct(EntityManagerInterface $entity, ProductRepository $productRepo, CategoryRepository $categoryRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->entity = $entity;
    }

    /**
     * @Route("/admin",   name="admin")
     */
    public function admin()
    {
        return $this->render('admin/admin.html.twig');
    }




    /**
     * @Route("/admin/nouveau-produit",   name="product_form")
     */

    public function createProduct(Request $request)
    {
        $product = new Product();


        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entity->persist($product);
            $this->entity->flush();
            return $this->redirectToRoute('show_product');
        }
        return $this->render('admin/product/createProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/produit" , name="show_product")
     */
    public function showProduct()
    {
        $product = $this->productRepo->findAll();
        $category = $this->categoryRepo->findAll();
        // dd($product);

        return $this->render('admin/product/product.html.twig', [
            "products" => $product,
            "categorys" => $category
        ]);
    }
    /**
     * @Route("admin/supprime_produit/{id}" , name="delete_product")
     */
    public function delete_product(Product $product)
    {
        // $product = new Product();
        $this->entity->remove($product);
        $this->entity->flush();
        $this->addFlash('success', "Le Produit à bien été supprimer!");
        return $this->redirectToRoute('show_product');
    }


    /**
     * @Route("/admin/modifier-produit/{id}", name="update_product")
     */
    public function update_product(Request $request, Product $productId)
    {

        $form = $this->createForm(ProductType::class, $productId);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entity->flush();
            $this->addFlash('success',  "Le Produit à bien été modifier");
            return $this->redirectToRoute('show_product');
        }
        return $this->render('admin/product/updateProduct.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
