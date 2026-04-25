document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("catalogue-form");
  const successMessage = document.getElementById("catalogue-success");

  if (!form) return;

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const emailInput = document.getElementById("catalogue-email");
    const submitButton = form.querySelector(".btn-catalogue");
    const email = emailInput.value.trim();

    // Validation simple
    if (!email || !email.includes("@")) {
      alert("Veuillez entrer une adresse email valide");
      return;
    }

    // Désactive le bouton
    submitButton.disabled = true;
    submitButton.textContent = "⏳ Envoi en cours...";

    try {
      // ✅ CORRECTION ICI : Change l'URL et le format des données
      const response = await fetch(
        "https://urban-tendances.fr/send-catalogue.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: email,
          }),
        }
      );

      const result = await response.json();

      console.log("Réponse serveur:", result); // Pour debug

      if (result.success) {
        // Affiche le message de succès
        form.style.display = "none";
        successMessage.style.display = "block";

        // Tracking Google Analytics (si disponible)
        if (typeof gtag !== "undefined") {
          gtag("event", "catalogue_request", {
            event_category: "Lead",
            event_label: email,
          });
        }
      } else {
        alert("Erreur : " + result.message);
        submitButton.disabled = false;
        submitButton.textContent = "📥 Recevoir le catalogue";
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Une erreur est survenue. Veuillez réessayer plus tard.");

      // Réactive le bouton
      submitButton.disabled = false;
      submitButton.textContent = "📥 Recevoir le catalogue";
    }
  });
});
