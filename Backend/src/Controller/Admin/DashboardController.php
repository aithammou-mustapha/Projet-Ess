<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Form\GroupeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/dashboard', name: 'admin_dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }

    #[Route('/events', name: 'events', methods: ['GET'])]
    public function events(GroupeRepository $groupeRepository): Response
    {
        $events = [];
        foreach ($groupeRepository->findAll() as $groupe) {
            $events[] = [
                'title' => $groupe->getNomGroupe(),
                'start' => $groupe->getDateDebut()->format('Y-m-d') . 'T' . $groupe->getHeureDebut()->format('H:i:s'),
                'end' => $groupe->getDateFin()->format('Y-m-d') . 'T' . $groupe->getHeureFin()->format('H:i:s'),
                'url' => $this->generateUrl('admin_groupe_edit', ['id' => $groupe->getId()]),
                'backgroundColor' => $groupe->getBackgroundColor() ?? '#1a252f',
                'textColor' => 'white',
                'matieres' => $groupe->getMatieresGroupe() ?? 'Non spécifiée' // ✅ champ ajouté
            ];
        }

        return $this->json($events);
    }

    #[Route('/groupe/add', name: 'groupe_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $groupe = new Groupe();

        if ($request->query->get('date')) {
            $groupe->setDateDebut(new \DateTime($request->query->get('date')));
            $groupe->setDateFin(new \DateTime($request->query->get('date')));
        }

        $form = $this->createForm(GroupeFormType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($groupe);
            $em->flush();

            $this->addFlash('success', '✅ Groupe ajouté !');
            return $this->redirectToRoute('admin_dashboard_index');
        }

        return $this->render('admin/groupe/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
