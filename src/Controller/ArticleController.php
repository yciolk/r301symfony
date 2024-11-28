<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    // #[Route('/article/creer', name: 'app_article_create')]
    // public function create(EntityManagerInterface $entityManager) : Response
    // {
    //     $article = new Article();//creation d'objet/association des valeurs
    //     $article->setTitre('Mon premier article')
    //             ->setTexte('Accenderat super his')
    //             ->setPublie(1)
    //             ->setDate(new DateTimeImmutable()); 
    //     //dd($article);
    //     $entityManager->persist($article); //preparation/check avant mise dans bdd

    //     $entityManager->flush();//mise en bdd

    //     return $this->render('article/creer.html.twig', [
    //         'controller_name' => 'ArticleController',
    //         'titre'=>'Article',
    //         'article'=>$article
    //     ]);
    // }

    // inclure le form dans le twig avec {{}}
    #[Route('/article/creer', name: 'app_article_create')]
    public function create(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $article = new Article();
        // $article->setTitre('Mon premier article')
        //         ->setTexte('Accenderat super his')
        //         ->setPublie(1)
        //         ->setDate(new DateTimeImmutable()); 
        
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();

            $entityManager->persist($article); 

            $entityManager->flush();

            $this->addFlash('success', 'Article Created! Knowledge is power!');
        }

        return $this->render('article/creer.html.twig', [ //render lance la vue associée en lui passant les paramètres renseignés
            'controller_name' => 'ArticleController',
            'titre'=>'Article',
            'article'=>$article,
            'form' => $form
        ]);
    }

    #[Route('/article/liste', name: 'app_article_liste')]
    public function liste(EntityManagerInterface $entityManager) : Response
    {
        $liste_article = $entityManager -> getRepository(Article::Class) -> findAll();

        return $this->render('article/liste.html.twig', [
            'controller_name' => 'ArticleController',
            'titre'=>'Liste',
            'liste_article'=>$liste_article
        ]);
    }

    #[Route('/article/modifier/{id}', name: 'app_article_modifier')]
    public function modifier(EntityManagerInterface $entityManager, Request $request, int $id) : Response
    {
        $a = $entityManager -> getRepository(Article::Class) -> find($id);

        $form = $this->createForm(ArticleType::class, $a);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $a = $form->getData();

            $entityManager->persist($a);

            $entityManager->flush();

            $this->addFlash('success', 'Article Updated! Maybe.');
            
        }

        return $this->render('article/modifier.html.twig', [
            'controller_name' => 'ArticleController',
            'titre'=>'Modification',
            'article'=>$a,
            'form_modif' => $form
        ]);
    }


    #[Route('/article/supprimer/{id}', name: 'app_article_supprimer')]
    public function supprimer(EntityManagerInterface $entityManager, Request $request, int $id) : Response
    {
        try{
            $a = $entityManager -> getRepository(Article::Class) -> find($id);
            $entityManager -> remove($a);
            $entityManager->flush();
            $message = ("Article numéro $id supprimé avec succès.");
        }
        catch(Exception $e){
            $message = ("Une erreur est survenue : $e");
        }


        return $this->render('article/supprimer.html.twig', [
            'controller_name' => 'ArticleController',
            'titre'=>'Suppression',
            'message' => $message
        ]);
    }
    
}
