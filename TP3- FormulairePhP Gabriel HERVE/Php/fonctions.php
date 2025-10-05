<?php
session_start();
session_regenerate_id(true); // Génerer un nouvel ID de session

/**
 
 * @param array .
 * @return bool 
 */
function validerDonnees(&$donnees)
{
    global $erreurs;
    $erreurs = [];

    // Nettoyage des données (sauf pour les tableaux)
    foreach ($donnees as $key => $value) {
        if (!is_array($value)) {
            $donnees[$key] = trim($value);
        }
    }

    if (empty($donnees['nom'])) {
        $erreurs['nom'] = 'Le nom est requis.';
    } elseif (!preg_match('/^[a-zA-Z\s\-]+$/', $donnees['nom'])) {
        $erreurs['nom'] = 'Le nom ne doit contenir que des lettres, espaces ou tirets.';
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
    } else {
        $interets_valides = ['jeux', 'chien', 'chat'];
        foreach ($donnees['interets'] as $interet) {
            if (!in_array($interet, $interets_valides)) {
                $erreurs['interets'] = 'Une valeur d\'intérêt n\'est pas valide.';
                break;
            }
        }
    }

    if (!isset($donnees['fanChat']) || !in_array($donnees['fanChat'], ['vrai', 'faux'])) {
        $erreurs['fanChat'] = 'Veuillez répondre à la question "Êtes-vous un VRAI fan de chats?".';
    }

    if (empty($donnees['commentaires'])) {
        $erreurs['commentaires'] = 'Veuillez inscrire un commentaire.';
    } elseif (strlen($donnees['commentaires']) > 500) {
        $erreurs['commentaires'] = 'Le commentaire ne doit pas dépasser 500 caractères.';
    }

    if (!isset($donnees['consentement'])) {
        $erreurs['consentement'] = 'Vous devez accepter les termes et conditions.';
    }

    return empty($erreurs);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST)) {
        $_SESSION['form_status'] = 'error';
        $_SESSION['form_errors'] = ['global' => 'Le formulaire est vide.'];
    } elseif (validerDonnees($_POST)) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_status'] = 'success';
        $_SESSION['message_success'] = "Bienvenue **" . htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8') . "** ! Votre formulaire a été complété avec succès.";
    } else {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_status'] = 'error';
        $_SESSION['form_errors'] = $erreurs;
    }
    header('Location: inscription.php');
    exit;
}

// Récupération des données de session
if (isset($_SESSION['form_status'])) {
    $formStatus = $_SESSION['form_status'];
    $formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : null;
    // Traitement spécifique pour échapper les chaînes de caractères
    if ($formData !== null) {
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $formData[$key] = array_map(function ($item) {
                    return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
                }, $value);
            } else {
                $formData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
    }
    $erreurs = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
    $messageSuccess = $_SESSION['message_success'] ?? '';

    // Nettoyage des données de session
    unset($_SESSION['form_status'], $_SESSION['form_data'], $_SESSION['form_errors'], $_SESSION['message_success']);
} else {
    $formStatus = 'initial';
    $formData = null;
}
