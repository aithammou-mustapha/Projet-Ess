<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfRepository;
use App\Entity\Prof;
use App\Form\ProfFormType;
use App\Form\ProfFormValidator;
use App\Repository\CentreRepository;

#[Route('/admin/professeurs', name: 'admin_prof_')]
class ProfController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, ProfRepository $profRepository, CentreRepository $centreRepository): Response
    {
        $searchProf = $request->query->get('prof');
        $centre = $request->query->get('centre');
    
        $profs = $profRepository->findByFilters($searchProf, $centre);
    
        // Récupérer la liste des centres pour les filtres
        $centres = $centreRepository->findDistinctNomCentres();
    
        return $this->render('admin/prof/index.html.twig', [
            'profs' => $profs,
            'centres' => $centres,
        ]);
    }
    

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, ProfRepository $profRepository): Response
    {
        $prof = new Prof();
        
        if ($prof->getDateEnregistrementProf() === null) {
            $prof->setDateEnregistrementProf(new \DateTimeImmutable());
        }
        
    
        $profForm = $this->createForm(ProfFormType::class, $prof, ['is_add' => true]);
        $profForm->handleRequest($request);
        
        $errors = [];
    
        if ($profForm->isSubmitted() && $profForm->isValid()) {
            $errors = ProfFormValidator::validate($profForm);
            if (!empty($errors)) {
                foreach ($errors as $field => $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/prof/add.html.twig', [
                    'profForm' => $profForm,
                    'errors' => $errors
                ]);
            }
    
            $existingProf = $profRepository->findOneBy(['emailProf' => $prof->getEmailProf()]);
    
            if ($existingProf) {
                $this->addFlash('error', 'Cet email est déjà utilisé par un autre professeur.');
                return $this->renderForm('admin/prof/add.html.twig', [
                    'profForm' => $profForm,
                    'errors' => $errors
                ]);
            }
    
            $selectedCentre = $profForm->get('centres')->getData();
            if ($selectedCentre) {
                $prof->getCentres()->clear();
                $prof->addCentre($selectedCentre);
            }
    
            $em->persist($prof);
            $em->flush();
    
            $this->addFlash('success', 'Le professeur a été ajouté avec succès.');
            return $this->redirectToRoute('admin_prof_index');
        }
    
        return $this->renderForm('admin/prof/add.html.twig', [
            'profForm' => $profForm,
            'errors' => $errors
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Prof $prof, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $profForm = $this->createForm(ProfFormType::class, $prof, ['is_add' => false]);
        $profForm->handleRequest($request);
    
        $errors = [];
    
        if ($profForm->isSubmitted() && $profForm->isValid()) {
            $errors = ProfFormValidator::validate($profForm);
            if (!empty($errors)) {
                foreach ($errors as $field => $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/prof/edit.html.twig', [
                    'profForm' => $profForm,
                    'errors' => $errors
                ]);
            }
            
            if ($prof->getMotDePasse()) {
                $prof->setMotDePasse($prof->getMotDePasse());
            }
    
            $selectedCentre = $profForm->get('centres')->getData();
            if ($selectedCentre) {
                $prof->getCentres()->clear();
                $prof->addCentre($selectedCentre);
            }
    
            $em->persist($prof);
            $em->flush();
    
            $this->addFlash('success', 'Professeur modifié avec succès');
            return $this->redirectToRoute('admin_prof_index');
        }
    
        return $this->renderForm('admin/prof/edit.html.twig', [
            'profForm' => $profForm,
            'errors' => $errors
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Prof $prof, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($prof);
        $em->flush();

        $this->addFlash('success', 'Professeur supprimé avec succès');
        return $this->redirectToRoute('admin_prof_index');
    }
}
