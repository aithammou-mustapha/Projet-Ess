<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class SalleFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le numéro de la salle (numSalle) au lieu de nomSalle
        $numSalle = $form->get('numSalle')->getData();
        if ($numSalle === null) {
            $errors['numSalle'] = "⚠️ Le numéro de la salle est obligatoire 🏢";
        } elseif (is_string($numSalle) && strlen($numSalle) < 3) {
            $errors['numSalle'] = "⚠️ Le numéro de la salle doit contenir au moins 3 caractères 📏";
        }

        // Vérifier la capacité de la salle (capaciteSalle)
        $capaciteSalle = $form->get('capaciteSalle')->getData();
        if ($capaciteSalle === null) {
            $errors['capaciteSalle'] = "⚠️ La capacité est requise 📊";
        } elseif (!is_numeric($capaciteSalle) || $capaciteSalle < 1) {
            $errors['capaciteSalle'] = "⚠️ La capacité doit être un nombre positif 🔢";
        }

        // Vérifier les disponibilités de la salle (disponibilitesSalle)
        $disponibilitesSalle = $form->get('disponibilitesSalle')->getData();
        if ($disponibilitesSalle !== null && !is_string($disponibilitesSalle)) {
            $errors['disponibilitesSalle'] = "⚠️ Les disponibilités doivent être un texte valide 📅";
        }

        return $errors;
    }
}
