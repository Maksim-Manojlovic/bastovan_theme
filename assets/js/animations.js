/**
 * Bastovan Tema — animations.js
 * Scroll animacije i accordion interakcije
 */

(function () {
  "use strict";

  // ─── USLUGE ACCORDION ────────────────────────────────────
  document.querySelectorAll(".usluga-accordion__btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const panel    = btn.nextElementSibling;
      const expanded = btn.getAttribute("aria-expanded") === "true";
      btn.setAttribute("aria-expanded", !expanded);
      panel.hidden = expanded;
    });
  });

  // ─── ANIMACIJE: PRIMARNE USLUGE (slide in from sides) ────
  const rows = document.querySelectorAll(".usluga-row");

  if (rows.length) {
    rows.forEach((row) => {
      const isLevo  = row.classList.contains("usluga-row--levo");
      const visual  = row.querySelector(".usluga-row__visual");
      const content = row.querySelector(".usluga-row__content");

      // Visual dolazi s leve strane za --levo red, s desne za --desno
      // Content obrnuto — efekt spajanja ka sredini
      const visualFrom  = isLevo ? "-80px" : "80px";
      const contentFrom = isLevo ? "80px"  : "-80px";

      const hide = (el, x) => {
        if (!el) return;
        el.style.opacity          = "0";
        el.style.transform        = `translateX(${x})`;
        el.style.transition       = "opacity 0.65s ease, transform 0.65s ease";
      };

      const show = (el) => {
        if (!el) return;
        el.style.opacity   = "1";
        el.style.transform = "translateX(0)";
      };

      hide(visual,  visualFrom);
      hide(content, contentFrom);

      // Content kasni 0.15s — vizuelno se "sreću" u sredini
      if (content) content.style.transitionDelay = "0.15s";

      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              show(visual);
              show(content);
              observer.unobserve(row);
            }
          });
        },
        { threshold: 0.2 }
      );

      observer.observe(row);
    });
  }

  // ─── ANIMACIJE: DODATNE USLUGE (fade-in staggered) ───────
  const cards = document.querySelectorAll(".usluga-card");

  if (cards.length) {
    cards.forEach((card, i) => {
      card.style.opacity         = "0";
      card.style.transform       = "translateY(24px)";
      card.style.transition      = "opacity 0.5s ease, transform 0.5s ease";
      card.style.transitionDelay = `${i * 0.08}s`;
    });

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity   = "1";
            entry.target.style.transform = "translateY(0)";
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    cards.forEach((card) => observer.observe(card));
  }

})();
