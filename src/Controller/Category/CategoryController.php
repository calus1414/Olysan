<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Form\CategoryType;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    private EntityManagerInterface $entity;
    private CategoryRepository $categoryRepo;
    private ProductRepository $productRepo;

    public function __construct(EntityManagerInterface $entity, ProductRepository $productRepo, CategoryRepository $categoryRepo)
    {
        $this->entity = $entity;
        $this->categoryRepo =  $categoryRepo;
        $this->productRepo = $productRepo;
    }


    /**
     * @Route("/admin/category", name="category")
     */
    public function category(Request $request)
    {
        $category = new Category();
        $allCategory = $this->categoryRepo->findAll();

        foreach ($allCategory as $category) {
            $categoryName[] = $category->getName();
            $countProduct = $this->productRepo->countProductPerCategory($category->getId());
            if ($countProduct[0]["category_id"] == null) {
                $countProduct[0]["category_id"] = "empty";
            }
            dump($countProduct);
            $counts[] = $countProduct[0];
        }





        // dump($categoryName);
        dump($category);
        // dd($counts);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entity->persist($category);
            $this->entity->flush();
            return $this->redirectToRoute('category');
        }
        return $this->render('admin/category/category.html.twig', [
            "form" => $form->createView(),
            "categorys" => $allCategory,
            "counts" => $counts
        ]);
    }
    /**
     * @Route("admin/supprime_categorie/{id}" , name="delete_category")
     */

    public function delete_category(Category $category)
    {

        $this->entity->remove($category);
        $this->entity->flush();
        $this->addFlash('success', "La Catègorie a bien été supprimer");
        return  $this->redirectToRoute('category');
    }
}
