document.addEventListener("DOMContentLoaded", () => {
  /***********************************/
  /* Animation barre de recherche */
  /***********************************/
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");

  if (searchBtn && searchInput) {
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
  }

  /***********************************/
  /* Suggestion mot de passe */
  /***********************************/
  const suggestPasswordBtn = document.getElementById("suggestPasswordBtn");
  const passwordSuggestionDiv = document.getElementById("password-suggestion");

  if (suggestPasswordBtn && passwordSuggestionDiv) {
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
  }

  /***********************************/
  /* Animation bouton d'envoi */
  /***********************************/
  const form = document.getElementById("catForm");
  const submitBtn = document.querySelector(".submit-btn");
  const submitText = submitBtn?.querySelector(".submit-text");
  const animationContainer = submitBtn?.querySelector(
    ".btn-animation-container"
  );
  const loaderDots = submitBtn?.querySelector(".loader-dots");

  if (form && submitBtn && submitText && animationContainer && loaderDots) {
    form.addEventListener("submit", () => {
      submitText.style.display = "none";
      animationContainer.style.display = "flex";
      loaderDots.style.display = "flex";
    });
  }

  /***********************************/
  /* Réinitialisation du formulaire avec confirmation */
  /***********************************/
  const resetBtn = document.querySelector(".reset-btn");
  if (resetBtn) {
    resetBtn.addEventListener("click", (event) => {
      event.preventDefault();
      if (
        confirm(
          "Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données seront effacées."
        )
      ) {
        document.getElementById("catForm").reset();
        // Réinitialise les messages d'erreur
        document.querySelectorAll(".error-message").forEach((el) => {
          el.textContent = "";
        });
      }
    });
  }

  /***********************************/
  /* Chatbot */
  /***********************************/
  const chatToggleBtn = document.getElementById("chat-toggle-btn");
  const chatbotContainer = document.getElementById("chatbot-container");
  const closeChatBtn = document.getElementById("close-chat-btn");
  const chatbotForm = document.getElementById("chatbot-form");
  const chatbotInput = document.getElementById("chatbot-input");
  const chatbotMessages = document.getElementById("chatbotMessages");

  const chatbotResponses = {
    salutations: [
      "Bonjour ! 😊 Comment puis-je vous aider aujourd'hui ?",
      "Salut à toi, humain ! Que puis-je faire pour toi ?",
      "Miaou ! Bienvenue sur le Forum des Chats. Comment puis-je t'aider ?",
      "Hello ! Je suis le chat-bot officiel du forum. Que puis-je faire pour toi ?",
    ],
    remerciements: [
      "De rien ! 😊 Reviens quand tu veux !",
      "Avec plaisir ! N'hésite pas si tu as d'autres questions.",
      "Je suis là pour ça ! 🐾",
      "Pas de souci, c'est un plaisir de t'aider !",
    ],
    forum: [
      "Le Forum des Chats est un espace dédié aux passionnés de félins, où tu peux discuter, partager des photos, et poser des questions sur tout ce qui concerne les chats !",
      "Notre forum est une communauté chaleureuse pour tous les amoureux des chats. Tu peux t'inscrire pour participer aux discussions !",
      "Ici, on parle chats, races, soins, comportements, et bien plus ! Rejoins-nous pour échanger avec d'autres membres.",
    ],
    inscription: [
      "Pour t'inscrire, remplis simplement le formulaire sur cette page avec tes informations. C'est rapide et gratuit !",
      "L'inscription te permet d'accéder à toutes les fonctionnalités du forum : discussions, partage de photos, et bien plus !",
      "Tu peux t'inscrire en cliquant sur le bouton 'S'inscrire' après avoir rempli les champs requis.",
    ],
    chats: [
      "Les chats sont des animaux fascinants ! Savais-tu qu'ils communiquent autant par leur queue que par leurs miaulements ? 🐱",
      "Les chats domestiques descendent du chat sauvage d'Afrique. Ils ont été apprivoisés il y a plus de 4 000 ans !",
      "Chaque chat a une personnalité unique. Certains sont câlins, d'autres indépendants, mais tous sont adorables !",
      "Les chats passent environ 70% de leur vie à dormir. Une vraie vie de roi ! 😴",
    ],
    questions: [
      "Quelle est ta race de chat préférée ?",
      "As-tu un chat ? Si oui, comment s'appelle-t-il ?",
      "Que penses-tu des chats noirs ? Moi, je les adore ! 🐈‍⬛",
      "Préfères-tu les chats ou les chiens ? (La bonne réponse est chat, bien sûr ! 😼)",
    ],
    humour: [
      "Pourquoi les chats n'utilisent-ils pas les ordinateurs ? Parce qu'ils ont déjà des souris ! 🖱️🐭",
      "Qu'est-ce qu'un chat dit à un autre chat qui a gagné à la loterie ? 'Félicitations, tu as gagné 9 vies supplémentaires !' 🎉",
      "Comment appelle-t-on un chat qui fait du ski ? Un chat-pine ! ⛷️🐱",
    ],
    aide: [
      "Tu peux me demander des infos sur le forum, les chats, ou même me poser des questions générales !",
      "Si tu as besoin d'aide pour t'inscrire ou naviguer sur le forum, je suis là !",
      "N'hésite pas à me dire ce que tu cherches, je ferai de mon mieux pour t'aider !",
    ],
    defaut: [
      "Désolé, je ne comprends pas bien ta question. Peux-tu reformuler ?",
      "Miaou ? Je n'ai pas bien saisi... Peux-tu me donner plus de détails ?",
      "Je suis un chat-bot, donc mes connaissances sont limitées. Mais je peux te parler des chats ou du forum ! 😊",
      "Intéressant ! Mais je ne suis pas sûr de comprendre. Veux-tu parler de chats ou du forum ?",
    ],
  };

  function getRandomResponse(category) {
    const responses = chatbotResponses[category];
    return responses[Math.floor(Math.random() * responses.length)];
  }

  function getBotResponse(message) {
    const lowerCaseMessage = message.toLowerCase();

    if (
      lowerCaseMessage.includes("bonjour") ||
      lowerCaseMessage.includes("salut") ||
      lowerCaseMessage.includes("hello") ||
      lowerCaseMessage.includes("coucou")
    ) {
      return getRandomResponse("salutations");
    } else if (
      lowerCaseMessage.includes("merci") ||
      lowerCaseMessage.includes("thanks") ||
      lowerCaseMessage.includes("sympa")
    ) {
      return getRandomResponse("remerciements");
    } else if (
      lowerCaseMessage.includes("forum") ||
      lowerCaseMessage.includes("communauté") ||
      lowerCaseMessage.includes("site")
    ) {
      return getRandomResponse("forum");
    } else if (
      lowerCaseMessage.includes("inscription") ||
      lowerCaseMessage.includes("s'inscrire") ||
      lowerCaseMessage.includes("compte")
    ) {
      return getRandomResponse("inscription");
    } else if (
      lowerCaseMessage.includes("chat") ||
      lowerCaseMessage.includes("félin") ||
      lowerCaseMessage.includes("matou") ||
      lowerCaseMessage.includes("minou")
    ) {
      return getRandomResponse("chats");
    } else if (
      lowerCaseMessage.includes("?") &&
      !lowerCaseMessage.includes("chat") &&
      !lowerCaseMessage.includes("forum")
    ) {
      return getRandomResponse("questions");
    } else if (
      lowerCaseMessage.includes("blague") ||
      lowerCaseMessage.includes("drôle") ||
      lowerCaseMessage.includes("humour")
    ) {
      return getRandomResponse("humour");
    } else if (
      lowerCaseMessage.includes("aide") ||
      lowerCaseMessage.includes("help") ||
      lowerCaseMessage.includes("besoin")
    ) {
      return getRandomResponse("aide");
    } else {
      return getRandomResponse("defaut");
    }
  }

  if (
    chatToggleBtn &&
    chatbotContainer &&
    closeChatBtn &&
    chatbotForm &&
    chatbotInput &&
    chatbotMessages
  ) {
    chatToggleBtn.addEventListener("click", () => {
      chatbotContainer.style.display = "flex";
    });

    closeChatBtn.addEventListener("click", () => {
      chatbotContainer.style.display = "none";
    });

    chatbotForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const userMessage = chatbotInput.value.trim();
      if (userMessage === "") return;

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
  }
});
