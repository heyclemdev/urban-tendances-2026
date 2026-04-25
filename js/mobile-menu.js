document.addEventListener("DOMContentLoaded", function () {
  // Active les transitions CSS après le premier paint pour éviter le flash au chargement
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      document.body.classList.add("ui-ready");
    });
  });

  const hamburgerBtn = document.getElementById("hamburgerBtn");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeMenuBtn = document.getElementById("closeMenuBtn");

  function openMenu() {
    mobileMenu.classList.add("active");
    hamburgerBtn.classList.add("is-active"); // bascule MENU → ✕
    document.body.classList.add("menu-open");
  }

  function closeMenu() {
    mobileMenu.classList.remove("active");
    hamburgerBtn.classList.remove("is-active"); // bascule ✕ → MENU
    document.body.classList.remove("menu-open");
  }

  // Hamburger = toggle (ouvre et ferme)
  hamburgerBtn.addEventListener("click", function () {
    mobileMenu.classList.contains("active") ? closeMenu() : openMenu();
  });
  closeMenuBtn.addEventListener("click", closeMenu);

  // Fermer en cliquant sur la zone sombre (droite)
  mobileMenu.addEventListener("click", function (e) {
    if (e.target === mobileMenu) closeMenu();
  });

  // Fermer avec Escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && mobileMenu.classList.contains("active")) closeMenu();
  });
});

// Gérer les sous-menus accordéon
document.querySelectorAll(".mobile-submenu-toggle").forEach((toggle) => {
  toggle.addEventListener("click", function () {
    const parent = this.closest(".mobile-submenu");
    parent.classList.toggle("active");
  });
});
