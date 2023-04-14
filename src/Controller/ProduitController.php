<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\ProduitRepository;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    #[Route('/addp/{id?0}', name: 'app_produitadd')]
    public function addproduit(Produit $produit=null,ManagerRegistry $doctrine,Request $request): Response
    {$new=false;
        if(!$produit)
         {    $new=true;
             $produit=new Produit();
         }
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('image')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
                $produit->setImage($newFilename);
            }
            $entitymanager=$doctrine->getManager();
            $entitymanager->persist($produit);
            $entitymanager->flush();
          
             if($new)
            {
                $message=" a eté ajouté avec succes";
            }
            else
            {$message=" a eté modifié avec succes";
    
            }
            $this->addFlash('success', $produit->getNom().$message);
            return $this->redirectToRoute('app-afficherproduit');

        }
        return $this->render('produit/addproduit.html.twig',[
            'form'=>$form->createView()
        ]
            
        );

    }
    #[Route('/afficheproduit', name: 'app-afficherproduit')]

    public function afficherproduit(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Produit::class);
        $produit=$repository->findAll();
        return $this->render('produit/afficherproduit.html.twig',[

            'produits'=>$produit
        ]);


    }
    #[Route('/deleteproduit/{id}', name: 'app_deleteproduit')]
    public function deletePerson(Produit $produit=null,ManagerRegistry $doctrine,$id):RedirectResponse
    {
        if($produit)
        {$manager=$doctrine->getManager();

            $manager->remove($produit);
            $manager->flush();
            $this->addFlash('success','la produit a ete supprimé avec succe');

        }
        else {
            $this->addFlash('error','la produit inexistant');

        }
        return $this->redirectToRoute('app-afficherproduit');

    }
    #[Route('/root/{id}', name: 'app_deleteproduit')]
    public function test(ManagerRegistry $doctrine,$id,ProduitRepository $repository):Response
    {

        
        $produit=$repository->findByCategory($id);
        return $this->render('produit/test.html.twig',[

            'produits'=>$produit
        ]);


    }
    
}
