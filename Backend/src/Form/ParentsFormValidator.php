<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class ParentsFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le prénom
        $prenom = trim($form->get('prenomParent')->getData());
        if (empty($prenom)) {
            $errors['prenomParent'] = "⚠️ Le prénom est obligatoire 🚀";
        } elseif (strlen($prenom) < 2) {
            $errors['prenomParent'] = "⚠️ Le prénom doit contenir au moins 2 caractères 📏";
        } elseif (preg_match('/\d/', $prenom)) {
            $errors['prenomParent'] = "⚠️ Le prénom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le nom
        $nom = trim($form->get('nomParent')->getData());
        if (empty($nom)) {
            $errors['nomParent'] = "⚠️ Le nom est obligatoire 📝";
        } elseif (preg_match('/\d/', $nom)) {
            $errors['nomParent'] = "⚠️ Le nom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier l'email
        $email = trim($form->get('emailParent')->getData());
        if (empty($email)) {
            $errors['emailParent'] = "⚠️ L'email est obligatoire 📧";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailParent'] = "⚠️ Veuillez entrer un email valide 💡";
        }

        // Vérifier le téléphone
        $tel = trim($form->get('telParent')->getData());
        if (empty($tel)) {
            $errors['telParent'] = "⚠️ Le téléphone est requis 📞";
        } elseif (!preg_match("/^(\+?\d{1,3})?\s?\d{9,15}$/", $tel)) {
            $errors['telParent'] = "⚠️ Numéro de téléphone invalide ❌";
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
