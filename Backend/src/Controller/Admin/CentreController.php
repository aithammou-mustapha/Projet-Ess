<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentreRepository;
use App\Entity\Centre;
use App\Form\CentreFormType;
use App\Form\CentreFormValidator;

#[Route('/admin/centre', name: 'admin_centre_')]
class CentreController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CentreRepository $centreRepository): Response
    {
        $centres = $centreRepository->findAll();

        return $this->render('admin/centre/index.html.twig', [
            'centres' => $centres,
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, CentreRepository $centreRepository): Response
    {
        $centre = new Centre();
        $centreForm = $this->createForm(CentreFormType::class, $centre);
        $centreForm->handleRequest($request);

        if ($centreForm->isSubmitted() && $centreForm->isValid()) {
            // Valider le formulaire avec `CentreFormValidator`
            $errors = CentreFormValidator::validate($centreForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/centre/add.html.twig', [
                    'centreForm' => $centreForm,
                    'errors' => $errors
                ]);
            }

            // Vérifier si un centre avec le même nom existe
            $existingCentre = $centreRepository->findOneBy(['nomCentre' => $centre->getNomCentre()]);
            if ($existingCentre) {
                $this->addFlash('error', '⚠️ Un centre avec ce nom existe déjà.');
                return $this->renderForm('admin/centre/add.html.twig', [
                    'centreForm' => $centreForm,
                    'errors' => $errors
                ]);
            }

            $em->persist($centre);
            $em->flush();

            $this->addFlash('success', '🎉 Le centre a été ajouté avec succès.');
            return $this->redirectToRoute('admin_centre_index');
        }

        return $this->renderForm('admin/centre/add.html.twig', [
            'centreForm' => $centreForm,
            'errors' => []
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Centre $centre, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $centreForm = $this->createForm(CentreFormType::class, $centre);
        $centreForm->handleRequest($request);

        if ($centreForm->isSubmitted() && $centreForm->isValid()) {
            $errors = CentreFormValidator::validate($centreForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/centre/edit.html.twig', [
                    'centreForm' => $centreForm,
                    'errors' => $errors
                ]);
            }

            $em->persist($centre);
            $em->flush();

            $this->addFlash('success', '🏗️ Centre modifié avec succès.');
            return $this->redirectToRoute('admin_centre_index');
        }

        return $this->renderForm('admin/centre/edit.html.twig', [
            'centreForm' => $centreForm,
            'errors' => []
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Centre $centre, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($centre);
        $em->flush();

        $this->addFlash('success', '🗑️ Centre supprimé avec succès.');
        return $this->redirectToRoute('admin_centre_index');
    }
}
