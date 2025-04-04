<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class GroupeFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le nom du groupe
        $nomGroupe = $form->get('nomGroupe')->getData();
        if ($nomGroupe === null || trim($nomGroupe) === '') {
            $errors['nomGroupe'] = "⚠️ Le nom du groupe est obligatoire 📛";
        } elseif (is_string($nomGroupe) && strlen($nomGroupe) < 3) {
            $errors['nomGroupe'] = "⚠️ Le nom du groupe doit contenir au moins 3 caractères 🏷️";
        }

        // Vérifier le type de groupe
        $typeGroupe = $form->get('typeGroupe')->getData();
        if ($typeGroupe === null || trim($typeGroupe) === '') {
            $errors['typeGroupe'] = "⚠️ Le type de groupe est obligatoire 📌";
        }

        // Vérifier le niveau
        $niveauGroupe = $form->get('niveauGroupe')->getData();
        if ($niveauGroupe === null || trim($niveauGroupe) === '') {
            $errors['niveauGroupe'] = "⚠️ Le niveau du groupe est requis 🎓";
        }

        // Vérifier la capacité
        $capaciteGroupe = $form->get('capaciteGroupe')->getData();
        if ($capaciteGroupe === null || !is_numeric($capaciteGroupe) || $capaciteGroupe <= 0) {
            $errors['capaciteGroupe'] = "⚠️ La capacité du groupe doit être un nombre positif 🔢";
        }

        // Vérifier la date de début et de fin
        $dateDebut = $form->get('dateDebut')->getData();
        $dateFin = $form->get('dateFin')->getData();

        if (!$dateDebut instanceof \DateTimeInterface) {
            $errors['dateDebut'] = "⚠️ La date de début est obligatoire 📅";
        }

        if (!$dateFin instanceof \DateTimeInterface) {
            $errors['dateFin'] = "⚠️ La date de fin est obligatoire 📆";
        }

        // Comparaison uniquement si les deux valeurs sont valides
        if ($dateDebut instanceof \DateTimeInterface && $dateFin instanceof \DateTimeInterface) {
            if ($dateDebut > $dateFin) {
                $errors['dateDebut'] = "⚠️ La date de début doit être antérieure à la date de fin 📅";
            }
        }

        // Vérifier l'heure de début et de fin
        $heureDebut = $form->get('heureDebut')->getData();
        $heureFin = $form->get('heureFin')->getData();

        if (!$heureDebut instanceof \DateTimeInterface) {
            $errors['heureDebut'] = "⚠️ L'heure de début est obligatoire ⏰";
        }

        if (!$heureFin instanceof \DateTimeInterface) {
            $errors['heureFin'] = "⚠️ L'heure de fin est obligatoire ⏰";
        }

        // Comparaison uniquement si les deux valeurs sont valides
        if ($heureDebut instanceof \DateTimeInterface && $heureFin instanceof \DateTimeInterface) {
            if ($heureDebut >= $heureFin) {
                $errors['heureDebut'] = "⚠️ L'heure de début doit être avant l'heure de fin ⏰";
            }
        }

        // Vérifier la salle
        $salle = $form->get('salle')->getData();
        if ($salle === null) {
            $errors['salle'] = "⚠️ Une salle doit être attribuée au groupe 🏫";
        }

        // Vérifier le professeur
        $prof = $form->get('prof')->getData();
        if ($prof === null) {
            $errors['prof'] = "⚠️ Un professeur doit être attribué au groupe 👨‍🏫";
        }

        return $errors;
    }
}
