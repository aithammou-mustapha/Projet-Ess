<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParentsRepository;
use App\Entity\Parents;
use App\Form\ParentsFormType;
use App\Form\ParentsFormValidator;

#[Route('/admin/parents', name: 'admin_parents_')]
class ParentsController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request, ParentsRepository $parentsRepository): Response
    {
        $search = $request->query->get('parent'); // Récupère le filtre
    
        $parents = $parentsRepository->findByNomPrenom($search);
    
        return $this->render('admin/parents/index.html.twig', [
            'parents' => $parents,
        ]);
    }
    

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, ParentsRepository $parentsRepository): Response
    {
        $parent = new Parents();
        $parentsForm = $this->createForm(ParentsFormType::class, $parent, ['is_add' => true]);
        $parentsForm->handleRequest($request);

        $errors = [];

        if ($parentsForm->isSubmitted() && $parentsForm->isValid()) {
            // Valider le formulaire avec la méthode custom `ParentsFormValidator`
            $errors = ParentsFormValidator::validate($parentsForm);
            if (!empty($errors)) {
                foreach ($errors as $field => $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/parents/add.html.twig', [
                    'parentsForm' => $parentsForm,
                    'errors' => $errors
                ]);
            }

            // Vérifier si un parent avec le même email existe
            $existingParent = $parentsRepository->findOneBy(['emailParent' => $parent->getEmailParent()]);
            if ($existingParent) {
                $this->addFlash('error', '⚠️ Cet email est déjà utilisé par un autre parent.');
                return $this->renderForm('admin/parents/add.html.twig', [
                    'parentsForm' => $parentsForm,
                    'errors' => $errors
                ]);
            }

            // Hacher le mot de passe si celui-ci est rempli
            $password = $parentsForm->get('motDePasse')->getData();
            if ($password) {
                // Utiliser password_hash pour hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $parent->setMotDePasse($hashedPassword);
            }

            // Sauvegarder le parent dans la base de données
            $em->persist($parent);
            $em->flush();

            $this->addFlash('success', '🎉 Le parent a été ajouté avec succès.');
            return $this->redirectToRoute('admin_parents_index');
        }

        return $this->renderForm('admin/parents/add.html.twig', [
            'parentsForm' => $parentsForm,
            'errors' => $errors
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Parents $parent, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $parentsForm = $this->createForm(ParentsFormType::class, $parent, ['is_add' => false]);
        $parentsForm->handleRequest($request);

        $errors = [];

        if ($parentsForm->isSubmitted() && $parentsForm->isValid()) {
            // Valider le formulaire avec la méthode custom `ParentsFormValidator`
            $errors = ParentsFormValidator::validate($parentsForm);
            if (!empty($errors)) {
                foreach ($errors as $field => $message) {
                    $this->addFlash('error', $message);
                }

                return $this->renderForm('admin/parents/edit.html.twig', [
                    'parentsForm' => $parentsForm,
                    'errors' => $errors
                ]);
            }

    

            // Sauvegarder les modifications du parent en base de données
            $em->persist($parent);
            $em->flush();

            $this->addFlash('success', 'Parent modifié avec succès');
            return $this->redirectToRoute('admin_parents_index');
        }

        return $this->renderForm('admin/parents/edit.html.twig', [
            'parentsForm' => $parentsForm,
            'errors' => $errors
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Parents $parent, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($parent);
        $em->flush();

        $this->addFlash('success', 'Parent supprimé avec succès');
        return $this->redirectToRoute('admin_parents_index');
    }
}
