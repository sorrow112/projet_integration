<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\Publication1Type;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository, EntityManagerInterface $entityManager, Request $request,): Response
    {

        
        if ($this->getUser() == null) {
        return $this->renderForm('publication/index.html.twig', [
            'publications' => $publicationRepository->findAllP(),
            'user' => '',
        ]);}
        else{
            return $this->renderForm('publication/index.html.twig', [
                'publications' => $publicationRepository->findAllP(),
                'user' => $this->getUser(),
            ]);
        }
    }

    #[Route('/new', name: 'publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $publication = new Publication();
        $form = $this->createForm(Publication1Type::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setUser($this->getUser());
            $publication->setOwnerName($this->getUser()->nomUtilisateur);
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('publication_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->getUser() == null) {
            return $this->renderForm('publication/new.html.twig', [
                'publication' => $publication,
                'form' => $form,
                'user' => '',
            ]);}
        else{
            return $this->renderForm('publication/new.html.twig', [
                'publication' => $publication,
                'form' => $form,
                'user' =>$this-> getUser(),
            ]);
        }
       
    }

    #[Route('/{id}', name: 'publication_show', methods: ['GET', 'POST'])]
    public function show(Publication $publication, Request $request, EntityManagerInterface $entityManager): Response
    {
        $Commentaire = new Commentaire();
        $cform = $this->createForm(CommentaireType::class, $Commentaire);
        $cform->handleRequest($request);

        if ($cform->isSubmitted() && $cform->isValid()) {
            $Commentaire->setUser($this->getUser());
            $Commentaire->setPublication($publication);
            $Commentaire->setDateCree();
            $Commentaire->setDateMod();
            $Commentaire->setNomUtil($this->getUser()->nomUtilisateur);
            $entityManager->persist($Commentaire);
            $entityManager->flush();
        
        }
        if ($this->getUser() == null) {
        return $this->renderForm('publication/show.html.twig', [
            'Commentaire' => $Commentaire,
            'publication' => $publication,
            'Cform' => $cform,
            'user' => '',   
        ]);}
        else{
            return $this->renderForm('publication/show.html.twig', [
                'Commentaire' => $Commentaire,
                'publication' => $publication,
                'Cform' => $cform,
                'user' =>$this-> getUser(),
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if($publication->getUser() != $this->getUser()){
            return $this->render('main/getOut.html.twig');
        }
        $form = $this->createForm(Publication1Type::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('publication_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->getUser() == null) {
            return $this->renderForm('publication/edit.html.twig', [
                'publication' => $publication,
                'form' => $form,
                'user' => '',  
            ]);}
        else{
            return $this->renderForm('publication/edit.html.twig', [
                'publication' => $publication,
                'form' => $form,
                'user' => $this->getUser(),  
            ]);
        }
    }

    #[Route('/{id}/delete', name: 'publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager,): Response
    {
        if($publication->getUser() != $this->getUser()){
            return $this->render('main/getOut.html.twig');
        }
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);

            $entityManager->flush();
        }

        return $this->redirectToRoute('publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
