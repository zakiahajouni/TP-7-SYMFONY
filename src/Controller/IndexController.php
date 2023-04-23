<?php

namespace App\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Category;
<<<<<<< HEAD
use App\Entity\PriceSearch;
use App\Form\CategoryType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;
use App\Form\PriceSearchType;
=======
use App\Form\CategoryType;
>>>>>>> 209fcf9c6f3fb10d901bccd3f611ec90af61f9c8

class IndexController extends AbstractController
{
    /**
     *@Route("/",name="article_list")
     */
    /**
 *@Route("/",name="article_list")
 */
 public function home(Request $request,ManagerRegistry $doctirine)
 {
 $entityManager = $doctirine->getManager();
 $propertySearch = new PropertySearch();
 $form = $this->createForm(PropertySearchType::class,$propertySearch);
 $form->handleRequest($request);
 //initialement le tableau des articles est vide,
 //c.a.d on affiche les articles que lorsque l'utilisateur
 //clique sur le bouton rechercher
 $articles= [];

 if($form->isSubmitted() && $form->isValid()) {
 //on récupère le nom d'article tapé dans le formulaire
 $nom = $propertySearch->getNom();
 if ($nom!="")
 //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
 $articles= $doctirine->getRepository(Article::class)->findBy(['nom' => $nom] );
 else
 //si si aucun nom n'est fourni on affiche tous les articles
 $articles= $doctirine->getRepository(Article::class)->findAll();
 }
 return $this->render('articles/index.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
 }
    /**
     * @Route("/article/save")
     */
    public function save(ManagerRegistry $doctirine)
    {
        $entityManager = $doctirine->getManager();
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(2500);
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Saved an article with the id of ' . $article->getId());
    }
/**
 * @Route("/article/new", name="new_article")
 * Method({"GET", "POST"})
 */
public function new(Request $request,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = new Article();
    $form = $this->createForm(ArticleType::class,$article);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $article = $form->getData();
    $entityManager = $doctirine->getManager();
    $entityManager->persist($article);
    $entityManager->flush();
    return $this->redirectToRoute('article_list');
    }
    return $this->render('articles/new.html.twig',['form' => $form->createView()]);
    }
   
    /**
 * @Route("/article/{id}", name="article_show")
 */
 public function show($id,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = $doctirine->getRepository(Article::class)->find($id);
    return $this->render('articles/show.html.twig',
    array('article' => $article));
     }
/**
 * @Route("/article/edit/{id}", name="edit_article")
 * Method({"GET", "POST"})
 */
public function edit(Request $request, $id,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = new Article();
   $article =  $doctirine->getRepository(Article::class)->find($id);
   
    $form = $this->createForm(ArticleType::class,$article);
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
   
    $entityManager =  $doctirine->getManager();
    $entityManager->flush();
   
    return $this->redirectToRoute('article_list');
    }
   
    return $this->render('articles/edit.html.twig', ['form' =>
   $form->createView()]);
    }
    /**
 * @Route("/article/delete/{id}",name="delete_article")
 * @Method({"DELETE"})
  */
  public function delete(Request $request, $id,ManagerRegistry $doctirine)
  {
      $entityManager = $doctirine->getManager();
    $article = $doctirine->getRepository(Article::class)->find($id);
   
    $entityManager = $doctirine->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
   
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('article_list');
    }
/**
 * @Route("/category/newCat", name="new_category")
 * Method({"GET", "POST"})
 */
#[Route('/category/newCat', name: 'new_category')]
public function newCategory(Request $request, ManagerRegistry $doctrine)
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->flush();
        
    }
    
    return $this->render('articles/newCategory.html.twig', ['form' =>$form->createView()]);
}

<<<<<<< HEAD
/**
 * @Route("/art_cat/", name="article_par_cat")
 * Method({"GET", "POST"})
 */
public function articlesParCategorie(Request $request, ManagerRegistry $managerRegistry)
{
    $categorySearch = new CategorySearch();
    $form = $this->createForm(CategorySearchType::class,$categorySearch);
    $form->handleRequest($request);
    $articles= [];

    if($form->isSubmitted() && $form->isValid()) {
        $category = $categorySearch->getCategory();
     
        if ($category) {
            $entityManager = $managerRegistry->getManager();
            $category = $entityManager->getRepository(Category::class)->find($category->getId());

            if ($category) {
                $articles = $category->getArticles();
            }
        } else {
            $entityManager = $managerRegistry->getManager();
            $articles = $entityManager->getRepository(Article::class)->findAll();
        }
    }

    return $this->render('articles/articlesParCategorie.html.twig', [
        'form' => $form->createView(),
        'articles' => $articles
    ]);
}


/**
* @Route("/art_prix/", name="article_par_prix")
* Method({"GET"})
*/
public function articlesParPrix(Request $request, ManagerRegistry $managerRegistry)
{
    $priceSearch = new PriceSearch();
    $form = $this->createForm(PriceSearchType::class, $priceSearch);
    $form->handleRequest($request);

    $entityManager = $managerRegistry->getManager();
    $articleRepository = $entityManager->getRepository(Article::class);

    $articles = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $minPrice = $priceSearch->getMinPrice();
        $maxPrice = $priceSearch->getMaxPrice();

        $articles = $articleRepository->findByPriceRange($minPrice, $maxPrice);
    }

    return $this->render('articles/articlesParPrix.html.twig', [
        'form' => $form->createView(),
        'articles' => $articles,
    ]);
}

}
=======
>>>>>>> 209fcf9c6f3fb10d901bccd3f611ec90af61f9c8




<<<<<<< HEAD


    
=======
    }
>>>>>>> 209fcf9c6f3fb10d901bccd3f611ec90af61f9c8
