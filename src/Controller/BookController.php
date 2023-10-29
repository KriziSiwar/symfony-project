<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SiwarType; 
use App\Form\SubmitType; 




use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/AjoutBook', name: 'AjoutBook')]
    public function AjoutBook(ManagerRegistry $managerRegistry,Request $req): Response
    {


        $x = $managerRegistry->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($book);
            $x->flush();
            return $this->redirectToRoute('AffichageBook');
        }
        return $this->renderForm('book/AjoutBook.html.twig', [
            'n' => $form
        ]);
    }

    #[Route('/AffichageBook', name: 'AffichageBook')]
    public function AffichageBook(BookRepository $BookRepository ,EntityManagerInterface $entityManager,Request $req): Response
    {
    // Calculate the number of published books
    $publishedCount = $BookRepository->count(['published' => true]);
    // Calculate the number of unpublished books
    $unpublishedCount =$BookRepository->count(['published' => false]);


        $books = $BookRepository->findAll();
        
        
        $form = $this->createForm(SiwarType::class);
               $form->handleRequest($req);
       
               
                   $datainput = $form->get('id')->getData();
                   
                   $book= $BookRepository->findById($datainput); 
                    $books=$BookRepository->orderByAuthorName(); 
                    $books = $BookRepository->findBooksBeforeYearWithnbbooks();
       
              
       $shakespeareBooks = $BookRepository->updateCategoryForShakespeareBooks();
       
               foreach ($shakespeareBooks as $books) {
                   $books->setCategory('Romance');
               }
       
               $entityManager->flush();
       
       return $this->render('book/Affichagebook.html.twig', [
                   'books' => $books ,
                   'form' => $form->createView(),
                   'book'=>$book, 

                   'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
            'Affichage' => $books,
        ]);
    }
            










    

    #[Route('/ModifierBook/{id}', name: 'ModifierBook')]
    public function ModifierBook( BookRepository $BookRepository, ManagerRegistry $managerRegistry,Request $req,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $BookRepository->find($id);
        $form = $this->createForm(BookType::class,$a);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($a);
            $x->flush();
            return $this->redirectToRoute('AffichageBook');
        }
        return $this->renderForm('book/ModifierBook.html.twig', [
            'c' => $form
        ]);
    }

    #[Route('/SupprimerBook/{id}', name: 'SupprimerBook')]
    public function SupprimerBook(BookRepository $BookRepository, ManagerRegistry $managerRegistry,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $BookRepository->find($id);
        $x->remove($a);
        $x->flush();
        return $this->redirectToRoute('AffichageBook');
    }
    #[Route('/bookPublished', name: 'bookPublished')]
    public function bookPublished(BookRepository $BookRepository): Response
    {
        $nbbookPub=$BookRepository->count(['published'=>true]);
        $nbbookUnpub=$BookRepository->count(['published'=>false]);
        return $this->render('book/AffichageBook.html.twig',[
            'nbbookPub'=>$nbbookPub,
            'nbbookUnpub'=>$nbbookUnpub,
        ]);
    }




    





}
