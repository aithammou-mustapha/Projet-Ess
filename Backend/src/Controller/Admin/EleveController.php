<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EleveRepository;
use App\Entity\Eleve;
use App\Form\EleveFormType;
use App\Form\EleveFormValidator;
use App\Repository\GroupeRepository;

#[Route('/admin/eleve', name: 'admin_eleve_')]
class EleveController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EleveRepository $eleveRepository, GroupeRepository $groupeRepository): Response
    {
        $searchEleve = $request->query->get('eleve');
        $niveau = $request->query->get('niveau');
        $groupe = $request->query->get('groupe');
    
        $eleves = $eleveRepository->findByFilters($searchEleve, $niveau, $groupe);
    
        // Récupérer les niveaux et groupes uniques pour les listes déroulantes
        $niveaux = $eleveRepository->findDistinctNiveaux();
        $groupes = $groupeRepository->findDistinctNomGroupes();
    
        return $this->render('admin/eleve/index.html.twig', [
            'eleves' => $eleves,
            'niveaux' => $niveaux,
            'groupes' => $groupes,
        ]);
    }
    

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $eleve = new Eleve();
        $eleveForm = $this->createForm(EleveFormType::class, $eleve);

        
        $eleveForm->handleRequest($request);
        if ($eleveForm->isSubmitted() && $eleveForm->isValid()) {
            $errors = EleveFormValidator::validate($eleveForm);
            if (!empty($errors)) {
                // dump($errors); die; // Vérifie si les erreurs sont bien générées

                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/eleve/add.html.twig', [
                    'eleveForm' => $eleveForm,
                    'errors' => $errors
                ]);
            }

            $em->persist($eleve);
            $em->flush();

            $this->addFlash('success', "\ud83c\udf89 L'élève a été ajouté avec succès.");
            return $this->redirectToRoute('admin_eleve_index');
        }

        return $this->renderForm('admin/eleve/add.html.twig', [
            'eleveForm' => $eleveForm,
            'errors' => []
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Eleve $eleve, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eleveForm = $this->createForm(EleveFormType::class, $eleve);
        $eleveForm->handleRequest($request);

        if ($eleveForm->isSubmitted() && $eleveForm->isValid()) {
            $errors = EleveFormValidator::validate($eleveForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/eleve/edit.html.twig', [
                    'eleveForm' => $eleveForm,
                    'errors' => $errors
                ]);
            }

            $em->flush();

            $this->addFlash('success', 'Élève modifié avec succès');
            return $this->redirectToRoute('admin_eleve_index');
        }

        return $this->renderForm('admin/eleve/edit.html.twig', [
            'eleveForm' => $eleveForm,
            'errors' => []
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Eleve $eleve, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($eleve);
        $em->flush();

        $this->addFlash('success', 'Élève supprimé avec succès');
        return $this->redirectToRoute('admin_eleve_index');
    }
}