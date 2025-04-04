<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Form\GroupeFormType;
use App\Form\GroupeFormValidator;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/groupe', name: 'admin_groupe_')]
class GroupeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, GroupeRepository $groupeRepository): Response
    {
        $nomGroupe = $request->query->get('nomGroupe');
        $niveau = $request->query->get('niveau');
        $typeGroupe = $request->query->get('typeGroupe');
        $eleve = $request->query->get('eleve'); // si tu veux chercher aussi par élève
    
        $groupes = $groupeRepository->findByFilters($nomGroupe, $niveau, $typeGroupe, $eleve);
    
        // Récupérer les niveaux et types de groupes distincts depuis la base de données
        $niveaux = $groupeRepository->findDistinctNiveaux();
        $typesGroupes = $groupeRepository->findDistinctTypesGroupes();
    
        return $this->render('admin/groupe/index.html.twig', [
            'groupes' => $groupes,
            'niveaux' => $niveaux,
            'typesGroupes' => $typesGroupes,
        ]);
    }
    
    

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, GroupeRepository $groupeRepository): Response
    {
        $groupe = new Groupe();
        $groupeForm = $this->createForm(GroupeFormType::class, $groupe);
        $groupeForm->handleRequest($request);
        
        $errors = [];

        if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
            $errors = GroupeFormValidator::validate($groupeForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/groupe/add.html.twig', [
                    'groupeForm' => $groupeForm,
                    'errors' => $errors
                ]);
            }

            // $heureDebut = $groupeForm->get('heureDebut')->getData();
            // $heureFin = $groupeForm->get('heureFin')->getData();
            // if ($heureDebut instanceof \DateTime) {
            //     $groupe->setHeureDebut($heureDebut);
            // }
            // if ($heureFin instanceof \DateTime) {
            //     $groupe->setHeureFin($heureFin);
            // }

            $avatarFile = $groupeForm->get('avatarGroupe')->getData();
            if ($avatarFile) {
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();
                $avatarFile->move('uploads/', $newFilename);
                $groupe->setAvatarGroupe($newFilename);
            }

            $em->persist($groupe);
            $em->flush();

            $this->addFlash('success', 'Le groupe a été ajouté avec succès.');
            return $this->redirectToRoute('admin_groupe_index');
        }

        return $this->renderForm('admin/groupe/add.html.twig', [
            'groupeForm' => $groupeForm,
            'errors' => $errors
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Groupe $groupe, Request $request, EntityManagerInterface $em): Response
    {
        $groupeForm = $this->createForm(GroupeFormType::class, $groupe);
        $groupeForm->handleRequest($request);
        
        $errors = [];
        
        if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
            $errors = GroupeFormValidator::validate($groupeForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/groupe/edit.html.twig', [
                    'groupeForm' => $groupeForm,
                    'errors' => $errors
                ]);
            }
            
            // $heureDebut = $groupeForm->get('heureDebut')->getData();
            // $heureFin = $groupeForm->get('heureFin')->getData();
            // if ($heureDebut instanceof \DateTime) {
            //     $groupe->setHeureDebut($heureDebut);
            // }
            // if ($heureFin instanceof \DateTime) {
            //     $groupe->setHeureFin($heureFin);
            // }
            
            $avatarFile = $groupeForm->get('avatarGroupe')->getData();
            if ($avatarFile) {
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();
                $avatarFile->move('uploads/', $newFilename);
                $groupe->setAvatarGroupe($newFilename);
            }

            $em->persist($groupe);
            $em->flush();

            $this->addFlash('success', 'Le groupe a été modifié avec succès.');
            return $this->redirectToRoute('admin_groupe_index');
        }

        return $this->renderForm('admin/groupe/edit.html.twig', [
            'groupeForm' => $groupeForm,
            'errors' => $errors
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Groupe $groupe, EntityManagerInterface $em): Response
    {
        $em->remove($groupe);
        $em->flush();

        $this->addFlash('success', 'Groupe supprimé avec succès.');
        return $this->redirectToRoute('admin_groupe_index');
    }
}
