<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalleController extends AbstractController
{
    #[Route('/salle', name: 'app_salle')]
    public function index(): Response
    {
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    #[Route('/Ajoutsalle', name: 'Ajoutsalle')]
    public function Ajoutsalle(ManagerRegistry $managerRegistry,Request $req): Response
    {


        $x = $managerRegistry->getManager();
        $salle= new Salle();
        $form = $this->createForm(DepartementType::class,$salle);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($salle);
            $x->flush();
            return $this->redirectToRoute('Affichagesalle');
        }
        return $this->renderForm('Salle/Ajoutsalle.html.twig', [
            'n' => $form
        ]);
    }

    #[Route('/AffichageSalle', name: 'AffichageSalle')]
    public function AffichageSalle(SalleRepository $SalleRepository): Response
    {
        $x = $SalleRepository->findAll();
        
        return $this->render('Departement/AffichageSalle.html.twig', [
            'Affichage' => $x,
        ]);
    }




    
    #[Route('/ModifierSalle{id}', name: 'ModifierSalle')]
    public function ModifierSalle( SalleRepository $SalleRepository, ManagerRegistry $managerRegistry,Request $req,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $SalleRepository->find($id);
        $form = $this->createForm(SalleType::class,$a);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $x->persist($a);
            $x->flush();
            return $this->redirectToRoute('AffichageSalle');
        }
        return $this->renderForm('Departement/Modifiersalle.html.twig', [
            'c' => $form
        ]);
    }

    #[Route('/SupprimerSalle/{id}', name: 'SupprimSalle')]
    public function SupprimerSalle(SalleRepository $SalleRepository, ManagerRegistry $managerRegistry,$id): Response
    {
        $x = $managerRegistry->getManager();
        $a = $SalleRepository->find($id);
        $x->remove($a);
        $x->flush();
        return $this->redirectToRoute('AffichageSalle');
    }
    





}
