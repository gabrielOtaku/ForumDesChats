document.addEventListener("DOMContentLoaded", () => {
  /***********************************/
  /*Animation barre de recherche */
  /***********************************/
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");

  searchBtn.addEventListener("click", () => {
    searchInput.classList.toggle("active");
    if (searchInput.classList.contains("active")) {
      searchInput.focus();
    }
  });

  document.addEventListener("click", (event) => {
    const isClickInside =
      searchBtn.contains(event.target) || searchInput.contains(event.target);
    if (!isClickInside) {
      searchInput.classList.remove("active");
    }
  });

  /***********************************/
  /*Suggestion mot de passe */
  /***********************************/
  const suggestPasswordBtn = document.getElementById("suggestPasswordBtn");
  const passwordSuggestionDiv = document.getElementById("password-suggestion");

  suggestPasswordBtn.addEventListener("click", (event) => {
    event.preventDefault();
    const characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+";
    let password = "";
    for (let i = 0; i < 12; i++) {
      password += characters.charAt(
        Math.floor(Math.random() * characters.length)
      );
    }
    document.getElementById("motDePasse").value = password;
    passwordSuggestionDiv.innerHTML = `Mot de passe suggéré : <strong>${password}</strong>`;
  });

  /***********************************/
  /*Animation et validation formulaire */
  /***********************************/
  const form = document.getElementById("catForm");
  const submitBtn = document.querySelector(".submit-btn");
  const submitText = submitBtn.querySelector(".submit-text");
  const animationContainer = submitBtn.querySelector(
    ".btn-animation-container"
  );
  const loaderDots = submitBtn.querySelector(".loader-dots");
  const successIcon = submitBtn.querySelector(".success-icon");
  const errorIcon = submitBtn.querySelector(".error-icon");
  const resetBtn = document.querySelector(".reset-btn");

  const modal = new bootstrap.Modal(document.getElementById("formModal"));
  const modalTitle = document.getElementById("formModalLabel");
  const modalBody = document.getElementById("modal-body-content");

  const resetFormState = () => {
    submitText.style.display = "block";
    animationContainer.style.display = "none";
    loaderDots.style.display = "flex";
    successIcon.style.display = "none";
    errorIcon.style.display = "none";
  };

  // Fonction pour afficher le pop-up de validation
  const showValidationPopup = (isSuccess, errors = null) => {
    if (isSuccess) {
      modalTitle.textContent = "Formulaire Validé ! 🎉";
      modalTitle.style.color = "#39ff14";
      let successMessage = `
      <h5>Félicitations !</h5>
      <p>Votre formulaire a été complété avec succès. Voici un récapitulatif de vos informations :</p>
      <ul>
          <li><strong>Nom :</strong> ${
            document.getElementById("nom").value
          }</li>
          <li><strong>Courriel :</strong> ${
            document.getElementById("courriel").value
          }</li>
          <li><strong>Mot de passe :</strong> ${
            document.getElementById("motDePasse").value
          }</li>
          <li><strong>Langue :</strong> ${
            document.getElementById("langue").value
          }</li>
          <li><strong>Intérêts :</strong> ${Array.from(
            document.querySelectorAll('input[name="interets[]"]:checked')
          )
            .map((el) => el.value)
            .join(", ")}</li>
          <li><strong>Fan de chats :</strong> ${
            document.querySelector('input[name="fanChat"]:checked')?.value
          }</li>
          <li><strong>Commentaires :</strong> ${
            document.getElementById("commentaires")?.value
          }</li>
      </ul>
    `;
      modalBody.innerHTML = successMessage;
    } else {
      modalTitle.textContent = "Erreur de validation ! ❌";
      modalTitle.style.color = "#ff3333";
      let errorMessage =
        "<h5>Désolé, une erreur s'est produite.</h5><p>Veuillez vérifier les informations suivantes :</p><ul class='error-list'>";
      for (const key in errors) {
        errorMessage += `<li>${errors[key]}</li>`;
      }
      errorMessage += "</ul>";
      modalBody.innerHTML = errorMessage;
    }
    modal.show();
  };

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    submitText.style.display = "none";
    animationContainer.style.display = "flex";
    loaderDots.style.display = "flex";

    // Validation du formulaire côté client
    let errors = {};
    const nom = document.getElementById("nom").value.trim();
    const courriel = document.getElementById("courriel").value.trim();
    const motDePasse = document.getElementById("motDePasse").value;
    const langue = document.getElementById("langue").value;
    const interets = Array.from(
      document.querySelectorAll('input[name="interets[]"]:checked')
    ).map((el) => el.value);
    const fanChat = document.querySelector('input[name="fanChat"]:checked')
      ? document.querySelector('input[name="fanChat"]:checked').value
      : null;
    const consentement = document.getElementById("consentement").checked;
    const commentaires = document.getElementById("commentaires").value.trim();

    if (nom === "") errors.nom = "Nom manquant ou invalide.";
    if (courriel === "" || !/^\S+@\S+\.\S+$/.test(courriel))
      errors.courriel = "Courriel manquant ou invalide.";
    if (motDePasse.length < 8)
      errors.motDePasse =
        "Le mot de passe doit contenir au moins 8 caractères.";
    if (langue === "") errors.langue = "Vous devez sélectionner une langue.";
    if (interets.length === 0)
      errors.interets = "Vous devez sélectionner au moins un intérêt.";
    if (fanChat === null)
      errors.fanChat = "Vous devez choisir si vous aimez les chats.";
    if (!consentement)
      errors.consentement = "Vous devez accepter les termes et conditions.";
    if (commentaires.length > 500)
      errors.commentaires =
        "Le commentaire ne doit pas dépasser 500 caractères.";
    if (commentaires === "")
      errors.commentaires = "Le commentaire ne doit pas être vide.";

    // Fin de la validation
    setTimeout(() => {
      loaderDots.style.display = "none";
      if (Object.keys(errors).length === 0) {
        successIcon.style.display = "block";
        showValidationPopup(true);
      } else {
        errorIcon.style.display = "block";
        showValidationPopup(false, errors);
      }
      setTimeout(resetFormState, 2000);
    }, 2000);
  });

  resetBtn.addEventListener("click", () => {
    form.reset();
    resetFormState();
    const formErrors = document.querySelectorAll(".error-message");
    formErrors.forEach((el) => (el.style.display = "none"));
  });

  /***********************************/
  /*Traitement chatBox */
  /***********************************/
  const chatToggleBtn = document.getElementById("chat-toggle-btn");
  const chatbotContainer = document.getElementById("chatbot-container");
  const closeChatBtn = document.getElementById("close-chat-btn");
  const chatbotForm = document.getElementById("chatbot-form");
  const chatbotInput = document.getElementById("chatbot-input");
  const chatbotMessages = document.getElementById("chatbotMessages");

  chatToggleBtn.addEventListener("click", () => {
    chatbotContainer.style.display = "flex";
  });

  closeChatBtn.addEventListener("click", () => {
    chatbotContainer.style.display = "none";
  });

  chatbotForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const userMessage = chatbotInput.value;
    if (userMessage.trim() === "") return;

    const userMsgElement = document.createElement("div");
    userMsgElement.classList.add("message", "user-message");
    userMsgElement.textContent = userMessage;
    chatbotMessages.appendChild(userMsgElement);
    chatbotInput.value = "";
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

    setTimeout(() => {
      const botResponse = getBotResponse(userMessage);
      const botMsgElement = document.createElement("div");
      botMsgElement.classList.add("message", "bot-message");
      botMsgElement.textContent = botResponse;
      chatbotMessages.appendChild(botMsgElement);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }, 1000);
  });

  function getBotResponse(message) {
    const lowerCaseMessage = message.toLowerCase();
    if (lowerCaseMessage.includes("forum")) {
      return "Le Forum des chats est une communauté pour les passionnés de félins !";
    } else if (lowerCaseMessage.includes("inscription")) {
      return "Le formulaire d'inscription se trouve sur la page d'accueil. Remplissez les champs pour nous rejoindre !";
    } else if (lowerCaseMessage.includes("chat")) {
      return "Miaou ! 🐾 Les chats sont les meilleurs, n'est-ce pas ?";
    } else if (
      lowerCaseMessage.includes("bonjour") ||
      lowerCaseMessage.includes("salut")
    ) {
      return "Bonjour ! Comment puis-je vous aider aujourd'hui ?";
    } else if (lowerCaseMessage.includes("merci")) {
      return "De rien ! N'hésitez pas si vous avez d'autres questions.";
    } else {
      return "Je suis un chat-bot, mes connaissances sont limitées aux chats et au forum. Essayez de me poser une autre question !";
    }
  }
});
