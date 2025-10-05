<?php
session_start();

/***********************************/
/* Fonctions de validations */
/***********************************/

/**
 
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

// On verifie si le formulaire a ete soumis 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On appelle la fonction de validation pour vérifier les données.
    if (validerDonnees($_POST)) {
        // --- Cas de succès : les données sont valides ---
        // On stocke les données et le message de succès dans la session.
        $_SESSION['form_data'] = $_POST;
        $_SESSION['message_success'] = "Bienvenue **" . htmlspecialchars($_POST['nom']) . "** ! Votre formulaire a été complété avec succès.";
        $_SESSION['form_errors'] = null; // On vide les erreurs pour l'affichage.
    } else {
        // --- Cas d'erreur : la validation a échoué ---
        // On stocke les données soumises et le tableau d'erreurs dans la session.
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_errors'] = $erreurs;
        $_SESSION['message_success'] = null; // On s'assure qu'il n'y a pas de message de succès.
    }

    // On redirige l'utilisateur vers la page du formulaire pour éviter les soumissions en double.
    header('Location: ../inscription.php');
    exit;
}

// Ces lignes s'exécutent lors du chargement initial de la page après la redirection.
// Elles récupèrent les messages d'erreur ou de succès de la session.
if (isset($_SESSION['form_errors'])) {
    $erreurs = $_SESSION['form_errors'];
}

if (isset($_SESSION['message_success'])) {
    $messageSuccess = $_SESSION['message_success'];
}

// On vide les variables de session pour ne pas afficher les messages lors d'un rafraîchissement manuel de la page.
unset($_SESSION['form_errors']);
unset($_SESSION['message_success']);
