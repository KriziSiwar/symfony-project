<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index(): Response
    {
        return $this->render('reader/index.html.twig', [
            'controller_name' => 'ReaderController',
        ]);
    }

    #[Route('/AffichageReader', name: 'AffichageReader')]
    public function Affichageauthor(ReaderRepository $readerRepository): Response
    {
        $reader = $readerRepository->findAll();
        return $this->render('reader/AffichageReader.html.twig', [
            'Affichage' => $reader,
        ]);
    }
    
    #[Route('/AjoutReader', name: 'AjoutReader')]
    public function Ajoutauthor(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $x = $managerRegistry->getManager();
$
        $y = new Reader();
        $form = $this->createForm(AuthorType::class,$y);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($y);
            $x->flush();
            return $this->redirectToRoute('AffichageReader');
        }
        return $this->renderForm('reader/AjoutReader.html.twig', [
            'n' => $form
        ]);
    }

    #[Route('/ModifierReader/{id}', name: 'ModifierReader')]
    public function ModifierReader(ReaderRepository $readerRepository, ManagerRegistry $managerRegistry,Request $req,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $readerRepository->find($id);
        $form = $this->createForm(ReaderType::class,$a);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($a);
            $x->flush();
            return $this->redirectToRoute('AffichageReader');
        }
        return $this->renderForm('reader/ModifierReader.html.twig', [
            'c' => $form
        ]);
    }

    #[Route('/Supprimerreader/{id}', name: 'Supprimerreader')]
    public function Supprimerauthor(ReaderRepository $readerRepository, ManagerRegistry $managerRegistry,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $readerRepository->find($id);
        $x->remove($a);
        $x->flush();
        return $this->redirectToRoute('AffichageReader');
    }





}
