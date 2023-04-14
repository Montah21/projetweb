<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CateoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
     #[Route('/back', name: 'app_categorieback')]
    public function showback(): Response
    {
        return $this->render('back.html.twig'
            
        );
    }
    #[Route('/add/{id?0}', name: 'app_categorieadd')]
    public function add(Categorie $categorie=null,ManagerRegistry $doctrine,Request $request): Response
    { $new=false;
       if(!$categorie)
        {    $new=true;
            $categorie=new Categorie();
        }
        

        $form=$this->createForm(CateoryType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() 
        && $form->isValid())
        {
            $entitymanager=$doctrine->getManager();
            $entitymanager->persist($categorie);
            $entitymanager->flush();
          
             if($new)
            {
                $message=" a eté ajouté avec succes";
            }
            else
            {$message=" a eté modifié avec succes";
    
            }
            $this->addFlash('success', $categorie->getLibelle().$message);
            return $this->redirectToRoute('app-afficher');

        }

     



        return $this->render('categorie/addcategorie.html.twig',[
            'form'=>$form->createView()
        ]
            
        );
     
       
   
        

        
        
  }



#[Route('/affiche', name: 'app-afficher')]

    public function afficher(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Categorie::class);
        $categorie=$repository->findAll();
        return $this->render('categorie/afficher.html.twig',[

            'categories'=>$categorie
        ]);


    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function deletePerson(Categorie $categorie=null,ManagerRegistry $doctrine,$id):RedirectResponse
    {
        if($categorie)
        {$manager=$doctrine->getManager();

            $manager->remove($categorie);
            $manager->flush();
            $this->addFlash('success','la categorie a ete supprimé avec succe');

        }
        else {
            $this->addFlash('error','la categorie inexistant');

        }
        return $this->redirectToRoute('app-afficher');

    }
    

}
