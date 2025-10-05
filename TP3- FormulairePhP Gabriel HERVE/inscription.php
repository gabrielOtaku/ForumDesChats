<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="CSS/monCSS.css">
    <title>TP3 - Forum des Chats 🐾</title>
    <link rel="icon" type="image/png" href="IMG/logoChat.png" />
</head>

<body>
    <?php require_once 'Php/fonctions.php'; ?>

    <!--  afficher les résultats  -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content <?php echo (isset($formStatus) && $formStatus === 'success') ? 'modal-success' : 'modal-error'; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">
                        <?php
                        if (isset($formStatus) && $formStatus === 'success') {
                            echo "<span class='emoji'>🎉</span> Formulaire validé !";
                        } elseif (isset($formStatus) && $formStatus === 'error') {
                            echo "<span class='emoji'>❌</span> Erreur de validation";
                        }
                        ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($formStatus) && $formStatus === 'success') {
                        echo "
                            <div class='text-center mb-3'>
                                <div class='emoji' style='font-size: 3rem;'>🐾</div>
                                <h4 class='mt-2'>Félicitations " . htmlspecialchars($formData['nom'] ?? '') . " !</h4>
                            </div>
                            <p class='text-center'>Votre inscription a été enregistrée avec succès. Voici un récapitulatif de vos informations :</p>
                            <div class='mt-3'>
                                <ul>
                                    <li><strong>Nom :</strong> " . ($formData['nom'] ?? '') . "</li>
                                    <li><strong>Courriel :</strong> " . ($formData['courriel'] ?? '') . "</li>
                                    <li><strong>Langue :</strong> " . ($formData['langue'] ?? '') . "</li>
                                    <li><strong>Intérêts :</strong> " . implode(', ', $formData['interets'] ?? []) . "</li>
                                    <li><strong>Fan de chats :</strong> " . (($formData['fanChat'] ?? '') === 'vrai' ? 'Oui ❤️' : 'Non 😢') . "</li>
                                    <li><strong>Commentaires :</strong> " . ($formData['commentaires'] ?? '') . "</li>
                                </ul>
                            </div>
                            <div class='text-center mt-3 emoji'>🐱💕</div>
                        ";
                    } elseif (isset($formStatus) && $formStatus === 'error') {
                        echo "
                            <div class='text-center mb-3'>
                                <div class='emoji' style='font-size: 3rem;'>😾</div>
                                <h4 class='mt-2'>Oups, il y a des erreurs !</h4>
                            </div>
                            <p class='text-center'>Veuillez corriger les informations suivantes :</p>
                            <div class='mt-3'>
                                <ul class='error-list'>
                                    ";
                        foreach ($erreurs as $error) {
                            echo "<li><span class='emoji'>⚠️</span> $error</li>";
                        }
                        echo "
                                </ul>
                            </div>
                            <div class='text-center mt-3 emoji'>🚫</div>
                        ";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg glass-nav">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a class="navbar-brand me-2" href="inscription.php">
                    <img src="IMG/logoChat.png" alt="Logo" width="70" height="50" class="d-inline-block align-text-top logo-rounded">
                    <i class="fa-solid fa-paw paw-icon"></i>
                </a>
                <h1 class="page-title text-white mb-0 ms-2">Forum des chats</h1>
            </div>
            <div class="d-flex align-items-center">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass-effect active" aria-current="page" href="inscription.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass-effect" href="inscription.php">Inscription</a>
                    </li>
                </ul>
                <div class="search-container ms-3">
                    <input type="text" class="search-input" placeholder="Rechercher..." id="searchInput">
                    <button class="search-btn" id="searchBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Formulaire -->
    <main class="main-content">
        <div class="container mt-5">
            <h2 class="form-title text-center neon-title">
                <span>I</span><span>n</span><span>s</span><span>c</span><span>r</span><span>i</span><span>p</span><span>t</span><span>i</span><span>o</span><span>n</span>
            </h2>
        </div>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 form-content-wrapper">
                    <div class="form-container p-4 glass-form" id="formulaire">
                        <form id="catForm" class="p-3 position-relative" action="inscription.php" method="post">
                            <i class="fa-solid fa-paw cat-paw-icon cat-paw-icon-1"></i>
                            <i class="fa-solid fa-paw cat-paw-icon cat-paw-icon-2"></i>
                            <i class="fa-solid fa-paw cat-paw-icon cat-paw-icon-3"></i>
                            <i class="fa-solid fa-paw cat-paw-icon cat-paw-icon-4"></i>

                            <div class="mb-3">
                                <label for="nom" class="form-label text-white">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $formData['nom'] ?? ''; ?>" required>
                                <?php if (isset($erreurs['nom'])): ?>
                                    <div class="error-message"><?php echo $erreurs['nom']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="courriel" class="form-label text-white">Courriel</label>
                                <input type="email" class="form-control" id="courriel" name="courriel" value="<?php echo $formData['courriel'] ?? ''; ?>" required>
                                <?php if (isset($erreurs['courriel'])): ?>
                                    <div class="error-message"><?php echo $erreurs['courriel']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="motDePasse" class="form-label text-white">Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
                                    <button class="btn btn-outline-secondary" type="button" id="suggestPasswordBtn"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                                </div>
                                <?php if (isset($erreurs['motDePasse'])): ?>
                                    <div class="error-message"><?php echo $erreurs['motDePasse']; ?></div>
                                <?php endif; ?>
                                <div id="password-suggestion" class="text-white mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label for="langue" class="form-label text-white">Langue de la page</label>
                                <select class="form-select" id="langue" name="langue">
                                    <option value="fr" <?php echo (($formData['langue'] ?? '') === 'fr') ? 'selected' : ''; ?>>Français</option>
                                    <option value="en" <?php echo (($formData['langue'] ?? '') === 'en') ? 'selected' : ''; ?>>Anglais</option>
                                    <option value="zh" <?php echo (($formData['langue'] ?? '') === 'zh') ? 'selected' : ''; ?>>Chinois</option>
                                    <option value="ja" <?php echo (($formData['langue'] ?? '') === 'ja') ? 'selected' : ''; ?>>Japonais</option>
                                    <option value="es" <?php echo (($formData['langue'] ?? '') === 'es') ? 'selected' : ''; ?>>Espagnol</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white">Intérêts</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="interets[]" value="jeux" id="interetJeux" <?php echo in_array('jeux', $formData['interets'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label text-white" for="interetJeux">Jeux vidéos</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="interets[]" value="chien" id="interetChien" <?php echo in_array('chien', $formData['interets'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label text-white" for="interetChien">Chien 🐶</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="interets[]" value="chat" id="interetChat" <?php echo in_array('chat', $formData['interets'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label text-white" for="interetChat">Chat 😻</label>
                                    </div>
                                </div>
                                <?php if (isset($erreurs['interets'])): ?>
                                    <div class="error-message"><?php echo $erreurs['interets']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <p class="form-label text-white">Êtes-vous un VRAI fan de chats? 🤔</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="fanChat" id="fanVrai" value="vrai" <?php echo (($formData['fanChat'] ?? '') === 'vrai') ? 'checked' : ''; ?>>
                                    <label class="form-check-label text-white fan-label" for="fanVrai">Vrai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="fanChat" id="fanFaux" value="faux" <?php echo (($formData['fanChat'] ?? '') === 'faux') ? 'checked' : ''; ?>>
                                    <label class="form-check-label text-white fan-label" for="fanFaux">Faux</label>
                                </div>
                                <?php if (isset($erreurs['fanChat'])): ?>
                                    <div class="error-message"><?php echo $erreurs['fanChat']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="consentement" name="consentement" <?php echo isset($formData['consentement']) ? 'checked' : ''; ?> required>
                                <label class="form-check-label text-white" for="consentement">J'accepte de donner mes informations 🐾</label>
                                <?php if (isset($erreurs['consentement'])): ?>
                                    <div class="error-message"><?php echo $erreurs['consentement']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="commentaires" class="form-label text-white">Commentaires</label>
                                <textarea class="form-control" id="commentaires" name="commentaires" rows="3"><?php echo $formData['commentaires'] ?? ''; ?></textarea>
                                <?php if (isset($erreurs['commentaires'])): ?>
                                    <div class="error-message"><?php echo $erreurs['commentaires']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary submit-btn">
                                    <span class="submit-text">S'inscrire <i class="fa-solid fa-arrow-right-long submit-arrow"></i></span>
                                    <div class="btn-animation-container">
                                        <div class="loader-dots">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                </button>
                                <button type="reset" class="btn btn-secondary reset-btn">Réinitialiser</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Chatbot -->
    <button id="chat-toggle-btn" class="chatbot-toggle-btn"><i class="fa-solid fa-comment-dots"></i></button>
    <div id="chatbot-container" class="chatbot-container">
        <div class="chatbot-header">
            <span>Chatbot Félin</span>
            <button id="close-chat-btn" class="close-chat-btn">&times;</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot-message">Bonjour ! Je suis le chat-bot du Forum. Pose-moi une question ! 🐾</div>
        </div>
        <form id="chatbot-form" class="chatbot-form">
            <input type="text" id="chatbot-input" placeholder="Écris ton message..." required>
            <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/monJS.js"></script>

    <?php if (isset($formStatus) && ($formStatus === 'success' || $formStatus === 'error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            });
        </script>
    <?php endif; ?>
</body>

</html>