<?php
session_start();

/***********************************/
/* Fonctions de validations */
/***********************************/

/**
 * 
 * @param array 
 * @return bool
 */
function validerDonnees($donnees)
{
    global $erreurs;
    $erreurs = [];

    if (empty($donnees['nom'])) {
        $erreurs['nom'] = 'Le nom est requis.';
    }

    if (empty($donnees['courriel'])) {
        $erreurs['courriel'] = 'L\'adresse courriel est requise.';
    } elseif (!filter_var($donnees['courriel'], FILTER_VALIDATE_EMAIL)) {
        $erreurs['courriel'] = 'L\'adresse courriel n\'est pas valide.';
    }

    if (empty($donnees['motDePasse'])) {
        $erreurs['motDePasse'] = 'Le mot de passe est requis.';
    } elseif (strlen($donnees['motDePasse']) < 8) {
        $erreurs['motDePasse'] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }

    if (!isset($donnees['interets']) || count($donnees['interets']) === 0) {
        $erreurs['interets'] = 'Veuillez sélectionner au moins un centre d\'intérêt.';
    }

    if (!isset($donnees['fanChat'])) {
        $erreurs['fanChat'] = 'Veuillez répondre à la question "Êtes-vous un VRAI fan de chats?".';
    }

    $commentaires_trim = trim($donnees['commentaires'] ?? '');
    if (empty($commentaires_trim)) {
        $erreurs['commentaires'] = 'Veuillez inscrire un commentaire.';
    } elseif (strlen($commentaires_trim) > 500) {
        $erreurs['commentaires'] = 'Le commentaire ne doit pas dépasser 500 caractères.';
    }

    if (!isset($donnees['consentement'])) {
        $erreurs['consentement'] = 'Vous devez accepter les termes et conditions.';
    }

    return empty($erreurs);
}

/***********************************/
/* Traitement de formulaire */
/***********************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (validerDonnees($_POST)) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_status'] = 'success';
        $_SESSION['message_success'] = "Bienvenue **" . htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8') . "** ! Votre formulaire a été complété avec succès.";
        $_SESSION['form_errors'] = null;
    } else {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_status'] = 'error';
        $_SESSION['form_errors'] = $erreurs;
    }

    header('Location: ../inscription.php');
    exit;
}

if (isset($_SESSION['form_status'])) {
    $formStatus = $_SESSION['form_status'];
    $formData = isset($_SESSION['form_data']) ? array_map('htmlspecialchars', $_SESSION['form_data']) : null;
    $erreurs = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
    $messageSuccess = $_SESSION['message_success'] ?? '';

    unset($_SESSION['form_status']);
    unset($_SESSION['form_data']);
    unset($_SESSION['form_errors']);
    unset($_SESSION['message_success']);
} else {
    $formStatus = 'initial';
    $formData = null;
}
