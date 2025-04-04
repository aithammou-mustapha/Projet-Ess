<?php

namespace App\Controller\Admin;

use App\Entity\Gerant;
use App\Form\GerantFormType;
use App\Form\GerantFormValidator;
use App\Repository\GerantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/gerant', name: 'admin_gerant_')]
class GerantController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'index')]
    public function index(GerantRepository $gerantRepository): Response
    {
        return $this->render('admin/gerant/index.html.twig', [
            'gerants' => $gerantRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, GerantRepository $gerantRepository): Response
    {
        $gerant = new Gerant();
        $gerantForm = $this->createForm(GerantFormType::class, $gerant, ['is_add' => true]);
        $gerantForm->handleRequest($request);

        if ($gerantForm->isSubmitted() && $gerantForm->isValid()) {
            $errors = GerantFormValidator::validate($gerantForm);
            
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/gerant/add.html.twig', compact('gerantForm', 'errors'));
            }

            if ($gerantRepository->findOneBy(['emailGerant' => $gerant->getEmailGerant()])) {
                $this->addFlash('error', '⚠️ Cet email est déjà utilisé par un autre gérant.');
                return $this->renderForm('admin/gerant/add.html.twig', compact('gerantForm', 'errors'));
            }

            $avatarFile = $gerantForm->get('avatarGerant')->getData();
            if ($avatarFile) {
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();
                $avatarFile->move('uploads/', $newFilename); // Stocké dans public/uploads/
                $gerant->setAvatarGerant($newFilename);
            }
            // 🔹 Gestion du centre sélectionné (Assigne un seul centre)
            $centre = $gerantForm->get('centres')->getData();
            if ($centre) {
                $gerant->addCentre($centre);
            }

            // 🔹 Gestion des rôles
            $gerant->setRoles(["ROLE_ADMIN"]);

            // 🔹 Gestion du mot de passe
            if ($gerantForm->has('password')) {
                $password = $gerantForm->get('password')->getData();
                if (!empty($password)) {
                    $gerant->setPassword($this->passwordHasher->hashPassword($gerant, $password));
                }
            }

            $em->persist($gerant);
            $em->flush();

            $this->addFlash('success', '🎉 Gérant ajouté avec succès.');
            return $this->redirectToRoute('admin_gerant_index');
        }

        return $this->renderForm('admin/gerant/add.html.twig', compact('gerantForm'));
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Gerant $gerant, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $gerantForm = $this->createForm(GerantFormType::class, $gerant, ['is_add' => false]);
        $gerantForm->handleRequest($request);

        if ($gerantForm->isSubmitted() && $gerantForm->isValid()) {
            $errors = GerantFormValidator::validate($gerantForm);
            if (!empty($errors)) {
                foreach ($errors as $message) {
                    $this->addFlash('error', $message);
                }
                return $this->renderForm('admin/gerant/edit.html.twig', compact('gerantForm', 'errors'));
            }

            // 🔹 Suppression des anciens centres avant d'ajouter le nouveau
            $gerant->getCentres()->clear();

            $avatarFile = $gerantForm->get('avatarGerant')->getData();
            if ($avatarFile) {
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();
                $avatarFile->move('uploads/', $newFilename); // Stocké dans public/uploads/
                $gerant->setAvatarGerant($newFilename);
            }

            // 🔹 Ajout du nouveau centre sélectionné
            $centre = $gerantForm->get('centres')->getData();
            if ($centre) {
                $gerant->addCentre($centre);
            }

            // 🔹 Gestion du mot de passe (si présent)
            if ($gerantForm->has('password')) {
                $password = $gerantForm->get('password')->getData();
                if (!empty($password)) {
                    $gerant->setPassword($this->passwordHasher->hashPassword($gerant, $password));
                }
            }

            $em->persist($gerant);
            $em->flush();

            $this->addFlash('success', '✅ Gérant modifié avec succès.');
            return $this->redirectToRoute('admin_gerant_index');
        }

        return $this->renderForm('admin/gerant/edit.html.twig', compact('gerantForm'));
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Gerant $gerant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // 🔹 Suppression sécurisée des centres avant suppression du gérant
        foreach ($gerant->getCentres() as $centre) {
            $gerant->removeCentre($centre); // Supprime la relation
        }

        $em->remove($gerant);
        $em->flush();

        $this->addFlash('success', '🗑️ Gérant supprimé avec succès.');

        return $this->redirectToRoute('admin_gerant_index');
    }
}
