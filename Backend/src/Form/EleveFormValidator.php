<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class EleveFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le prénom
        $prenom = $form->get('prenomEleve')->getData();
        if ($prenom === null) {
            $errors['prenomEleve'] = "⚠️ Le prénom est obligatoire 🚀";
        } elseif (is_string($prenom) && strlen($prenom) < 2) {
            $errors['prenomEleve'] = "⚠️ Le prénom doit contenir au moins 2 caractères 📏";
        } elseif (is_string($prenom) && preg_match('/\d/', $prenom)) {
            $errors['prenomEleve'] = "⚠️ Le prénom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le nom
        $nom = $form->get('nomEleve')->getData();
        if ($nom === null) {
            $errors['nomEleve'] = "⚠️ Le nom est obligatoire 📝";
        } elseif (is_string($nom) && strlen($nom) < 2) {
            $errors['nomEleve'] = "⚠️ Le nom doit contenir au moins 2 caractères 📏";    
        } elseif (is_string($nom) && preg_match('/\d/', $nom)) {
            $errors['nomEleve'] = "⚠️ Le nom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le téléphone
        $tel = $form->get('telEleve')->getData();
        if ($tel === null) {
            $errors['telEleve'] = "⚠️ Le téléphone est requis 📞";
        } elseif (is_string($tel) && !preg_match("/^(\+?\d{1,3})?\s?\d{9,15}$/", $tel)) {
            $errors['telEleve'] = "⚠️ Numéro de téléphone invalide ❌";
        }

        // Vérifier le niveau scolaire
        $niveau = $form->get('niveau')->getData();
        if ($niveau === null) {
            $errors['niveau'] = "⚠️ Le niveau scolaire est requis 🎓";
        }

        // Vérifier l’établissement scolaire (anciennement lycée)
        $etablissement = $form->get('etablissementScolaire')->getData();
        if ($etablissement === null) {
            $errors['etablissementScolaire'] = "⚠️ L’établissement scolaire est obligatoire 🏫";
        }

        // Vérifier le centre
        $centre = $form->get('centre')->getData();
        if (!$centre) {
            $errors['centre'] = "⚠️ Le centre est obligatoire 🏢";
        }

        // Vérifier le parent
        $parent = $form->get('parent')->getData();
        if (!$parent) {
            $errors['parent'] = "⚠️ Un parent doit être sélectionné 👨‍👩‍👧";
        }

        // Vérifier le(s) groupe(s) (optionnel ou actif si besoin)
        // $groupes = $form->get('groupes')->getData();
        // if (!$groupes || count($groupes) === 0) {
        //     $errors['groupes'] = "⚠️ Au moins un groupe doit être sélectionné 🏫";
        // }

        return $errors;
    }
}
