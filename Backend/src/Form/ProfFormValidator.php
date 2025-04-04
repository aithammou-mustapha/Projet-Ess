<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class ProfFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le prénom
        $prenom = trim($form->get('prenomProf')->getData());
        if (empty($prenom)) {
            $errors['prenomProf'] = "⚠️ Le prénom est obligatoire 🚀";
        } elseif (strlen($prenom) < 2) {
            $errors['prenomProf'] = "⚠️ Le prénom doit contenir au moins 2 caractères 📏";
        } elseif (preg_match('/\d/', $prenom)) {
            $errors['prenomProf'] = "⚠️ Le prénom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le nom
        $nom = trim($form->get('nomProf')->getData());
        if (empty($nom)) {
            $errors['nomProf'] = "⚠️ Le nom est obligatoire 📝";
        } elseif (preg_match('/\d/', $nom)) {
            $errors['nomProf'] = "⚠️ Le nom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier l'email
        $email = trim($form->get('emailProf')->getData());
        if (empty($email)) {
            $errors['emailProf'] = "⚠️ L'email est obligatoire 📧";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailProf'] = "⚠️ Veuillez entrer un email valide 💡";
        }

        // Vérifier le téléphone
        $tel = trim($form->get('telProf')->getData());
        if (empty($tel)) {
            $errors['telProf'] = "⚠️ Le téléphone est requis 📞";
        } elseif (!preg_match("/^(
?\+?\d{1,3})?\s?\d{9,15}$/", $tel)) {
            $errors['telProf'] = "⚠️ Numéro de téléphone invalide ❌";
        }

        // Vérifier la disponibilité
        $disponibilites = $form->get('disponibilitesProf')->getData();
        if (empty($disponibilites)) {
            $errors['disponibilitesProf'] = "⚠️ Veuillez renseigner les disponibilités 📅";
        }

        // Vérifier le centre
        $centre = $form->get('centres')->getData();
        if (empty($centre)) {
            $errors['centres'] = "⚠️ Veuillez sélectionner un centre 🏫";
        }

        // Vérifier le mot de passe (uniquement à l'ajout)
        if ($form->getConfig()->getOptions()['is_add']) {
            $password = trim($form->get('motDePasse')->getData());
            if (empty($password)) {
                $errors['motDePasse'] = "⚠️ Le mot de passe est obligatoire 🔐";
            } elseif (strlen($password) < 6) {
                $errors['motDePasse'] = "⚠️ Le mot de passe doit contenir au moins 6 caractères 🛡️";
            }
        }

        return $errors;
    }
}
