<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SalleRepository;
use App\Entity\Salle;
use App\Form\SalleFormType;
use App\Form\SalleFormValidator;

#[Route('/admin/salle', name: 'admin_salle_')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SalleRepository $salleRepository): Response
    {
        $salles = $salleRepository->findAll();

        return $this->render('admin/salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SalleRepository $salleRepository): Response
    {
        $salle = new Salle();
        $salleForm = $this->createForm(SalleFormType::class, $salle);
        $salleForm->handleRequest($request);

        if ($salleForm->isSubmitted() && $salleForm->isValid()) {
            // Valider avec `SalleFormValidator`
            $errors = SalleFormValidator::validate($salleForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/salle/add.html.twig', [
                    'salleForm' => $salleForm,
                    'errors' => $errors
                ]);
            }

            // Vérifier si une salle avec le même numéro existe déjà
            $existingSalle = $salleRepository->findOneBy(['numSalle' => $salle->getNumSalle()]);
            if ($existingSalle) {
                $this->addFlash('error', '⚠️ Une salle avec ce numéro existe déjà.');
                return $this->renderForm('admin/salle/add.html.twig', [
                    'salleForm' => $salleForm,
                    'errors' => $errors
                ]);
            }

            $em->persist($salle);
            $em->flush();

            $this->addFlash('success', '🎉 Salle ajoutée avec succès.');
            return $this->redirectToRoute('admin_salle_index');
        }

        return $this->renderForm('admin/salle/add.html.twig', [
            'salleForm' => $salleForm,
            'errors' => []
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Salle $salle, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $salleForm = $this->createForm(SalleFormType::class, $salle);
        $salleForm->handleRequest($request);

        if ($salleForm->isSubmitted() && $salleForm->isValid()) {
            $errors = SalleFormValidator::validate($salleForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/salle/edit.html.twig', [
                    'salleForm' => $salleForm,
                    'errors' => $errors
                ]);
            }

            $em->persist($salle);
            $em->flush();

            $this->addFlash('success', '🏗️ Salle modifiée avec succès.');
            return $this->redirectToRoute('admin_salle_index');
        }

        return $this->renderForm('admin/salle/edit.html.twig', [
            'salleForm' => $salleForm,
            'errors' => []
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Salle $salle, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($salle);
        $em->flush();

        $this->addFlash('success', '🗑️ Salle supprimée avec succès.');
        return $this->redirectToRoute('admin_salle_index');
    }
}
