document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector(".choice-container");
  const dots = document.querySelectorAll(".scroll-dot");

  if (container && dots.length > 0 && window.innerWidth <= 768) {
    container.addEventListener("scroll", function () {
      const scrollPercentage =
        (container.scrollLeft /
          (container.scrollWidth - container.clientWidth)) *
        100;

      dots.forEach((dot, index) => {
        if (scrollPercentage < 50 && index === 0) {
          dot.classList.add("active");
        } else if (scrollPercentage >= 50 && index === 1) {
          dot.classList.add("active");
        } else {
          dot.classList.remove("active");
        }
      });
    });
  }
});
