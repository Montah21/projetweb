<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\Categorie;
use App\Form\CateoryType;
use App\Controller\CategorieController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AbstractController
{
    #[Route('/store', name: 'app_home')]
    public function index(ManagerRegistry $doctrine,ManagerRegistry $doctrine1): Response
    {
        {
            $repository=$doctrine->getRepository(Categorie::class);
            $categorie=$repository->findAll();
            $repository=$doctrine1->getRepository(Produit::class);
            $produit=$repository->findAll();
            return $this->render('base.html.twig',compact('categorie','produit'
    
                
            ));
    
    
        }
        
    }



}