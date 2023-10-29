<?php
namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/Affichageauthor', name: 'Affichageauthor')]
    public function Affichageauthor(AuthorRepository $AuthorRepository,Request $req,$min=null,$max=null,ManagerRegistry $manager): Response
    {
       
        //$author = $AuthorRepository->findAll();
        //$author = $AuthorRepository->orderByUserName();

        //$author = $AuthorRepository->searchByAlphabet();

        $form = $this->createForm(MinmaxType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $datainput=$form->get('username')->getData();
            //var_dump($datainput);
            $author=$AuthorRepository->searchByUserName($datainput);
            

        }
        

        $em = $manager->getManager();
        if ($form->isSubmitted()){
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
            //var_dump($datainput);
            $author=$AuthorRepository->minmax($min,$max);
           
            return $this->renderForm('author/Affichageauthor.html.twig', [
                'Affichage' => $author,
                'f' => $form
            ]);
        }





    }






    
    #[Route('/Ajoutauthor', name: 'Ajoutauthor')]
    public function Ajoutauthor(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $y = new Author();
        $form = $this->createForm(AuthorType::class,$y);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($y);
            $x->flush();
            return $this->redirectToRoute('Affichageauthor');
        }
        return $this->renderForm('author/Ajoutauthor.html.twig', [
            'n' => $form
        ]);
    }

    #[Route('/Modifierauthor/{id}', name: 'Modifierauthor')]
    public function Modifierauthor(AuthorRepository $authorRepository, ManagerRegistry $managerRegistry,Request $req,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $authorRepository->find($id);
        $form = $this->createForm(AuthorType::class,$a);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($a);
            $x->flush();
            return $this->redirectToRoute('Affichageauthor');
        }
        return $this->renderForm('author/Modifierauthor.html.twig', [
            'c' => $form
        ]);
    }

    #[Route('/Supprimerauthor/{id}', name: 'Supprimerauthor')]
    public function Supprimerauthor(AuthorRepository $authorRepository, ManagerRegistry $managerRegistry,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $authorRepository->find($id);
        $x->remove($a);
        $x->flush();
        return $this->redirectToRoute('Affichageauthor');
    }














}
