<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class GerantFormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];

        // Vérifier le prénom
        $prenom = trim($form->get('prenomGerant')->getData());
        if (empty($prenom)) {
            $errors['prenomGerant'] = "⚠️ Le prénom est obligatoire 🚀";
        } elseif (strlen($prenom) < 2) {
            $errors['prenomGerant'] = "⚠️ Le prénom doit contenir au moins 2 caractères 📏";
        } elseif (preg_match('/\d/', $prenom)) {
            $errors['prenomGerant'] = "⚠️ Le prénom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier le nom
        $nom = trim($form->get('nomGerant')->getData());
        if (empty($nom)) {
            $errors['nomGerant'] = "⚠️ Le nom est obligatoire 📝";
        } elseif (preg_match('/\d/', $nom)) {
            $errors['nomGerant'] = "⚠️ Le nom ne peut pas contenir de chiffres ❌";
        }

        // Vérifier l'email
        $email = trim($form->get('emailGerant')->getData());
        if (empty($email)) {
            $errors['emailGerant'] = "⚠️ L'email est obligatoire 📧";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailGerant'] = "⚠️ Veuillez entrer un email valide 💡";
        }

        // Vérifier le téléphone
        $tel = trim($form->get('telGerant')->getData());
        if (empty($tel)) {
            $errors['telGerant'] = "⚠️ Le téléphone est requis 📞";
        } elseif (!preg_match("/^(\+?\d{1,3})?\s?\d{9,15}$/", $tel)) {
            $errors['telGerant'] = "⚠️ Numéro de téléphone invalide ❌";
        }

        // Vérifier les rôles
        $roles = $form->get('roles')->getData();
        if (empty($roles)) {
            $errors['roles'] = "⚠️ Les rôles sont obligatoires 🏅";
        }

        // Vérifier les centres (si des centres sont requis)
        $centres = $form->get('centres')->getData();
        if (empty($centres)) {
            $errors['centres'] = "⚠️ Les centres sont obligatoires 🏢";
        }

        // Vérifier le mot de passe (uniquement à l'ajout)
        if ($form->getConfig()->getOptions()['is_add']) {
            $password = trim($form->get('password')->getData());
            if (empty($password)) {
                $errors['password'] = "⚠️ Le mot de passe est obligatoire 🔐";
            } elseif (strlen($password) < 6) {
                $errors['password'] = "⚠️ Le mot de passe doit contenir au moins 6 caractères 🛡️";
            }
        }

        return $errors;
    }
}
