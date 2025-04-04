<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class CentreFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le nom du centre
        $nomCentre = $form->get('nomCentre')->getData();
        if ($nomCentre === null) {
            $errors['nomCentre'] = "⚠️ Le nom du centre est obligatoire 🏢";
        } elseif (is_string($nomCentre) && strlen($nomCentre) < 3) {
            $errors['nomCentre'] = "⚠️ Le nom du centre doit contenir au moins 3 caractères 📏";
        } elseif (is_string($nomCentre) && preg_match('/\d/', $nomCentre)) {
            $errors['nomCentre'] = "⚠️ Le nom du centre ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le nombre d'inscrits
        $nbInscrits = $form->get('nbInscrits')->getData();
        if ($nbInscrits === null) {
            $errors['nbInscrits'] = "⚠️ Le nombre d'inscrits est requis 📊";
        } elseif (!is_int($nbInscrits) || $nbInscrits < 0) {
            $errors['nbInscrits'] = "⚠️ Le nombre d'inscrits doit être un nombre positif 🔢";
        }

        // Vérifier le gérant
        // $gerant = $form->get('gerant')->getData();
        // if (!$gerant) {
        //     $errors['gerant'] = "⚠️ Un gérant doit être sélectionné 👨‍💼";
        // }

        return $errors;
    }
}
