const flottants = document.querySelectorAll(".flottant");

flottants.forEach((flottant) => {
  flottant.addEventListener("mousemove", (e) => {
    const rect = flottant.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;

    const moveX = x / 5;
    const moveY = y / 5;

    flottant.style.transform = `translate(${moveX}px, ${moveY}px)`;
  });

  flottant.addEventListener("mouseleave", () => {
    flottant.style.transform = "translate(0, 0)";
  });
});

// === TEXTE QUI APPARAIT HERO === //

// Au chargement de la page
window.addEventListener("load", () => {
  const topBlock = document.querySelector(".hero__block--top");
  const botBlock = document.querySelector(".hero__block--bot");

  setTimeout(() => {
    topBlock?.classList.add("is-visible");
    botBlock?.classList.add("is-visible");
  }, 300); // démarre après 300ms
});

// === WORD REVEAL pour le titre nature-section === //
const natureTitle = document.querySelector(".nature-section__title");
if (natureTitle) {
  const words = natureTitle.textContent.trim().split(" ");
  natureTitle.innerHTML = words
    .map(
      (word, i) =>
        `<span class="word-wrap"><span class="word" style="transition-delay:${i * 0.08}s">${word}</span></span>`
    )
    .join(" ");
}

// OU avec Intersection Observer (au scroll)
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
      }
    });
  },
  { threshold: 0.2 }
);

document
  .querySelectorAll(".hero__block--top, .hero__block--bot, .fade-in-up, .nature-section__title")
  .forEach((block) => {
    observer.observe(block);
  });

// ENVOI MESSAGE PAGE CONTACT //

const form = document.getElementById("contactForm");

form.addEventListener("submit", async (e) => {
  e.preventDefault(); // Stopper l’envoi classique

  const response = await fetch("sendmail.php", {
    method: "POST",
    body: new FormData(form),
  });

  const result = await response.text();

  if (result.trim() === "success") {
    alert("Votre message a bien été envoyé !");
    form.reset();
  } else {
    alert("Une erreur est survenue. Veuillez réessayer.");
  }
});
