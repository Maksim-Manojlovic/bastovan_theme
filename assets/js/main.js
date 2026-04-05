/**
 * Bastovan Tema — main.js
 * Globalne interakcije: navigacija, smooth scroll, header
 */

(function () {
  "use strict";

  // ─── MOBILNA NAVIGACIJA ──────────────────────────────────
  const toggle = document.getElementById("nav-toggle");
  const nav    = document.getElementById("site-nav");

  if (toggle && nav) {
    const iconOpen  = toggle.querySelector(".toggle-icon--open");
    const iconClose = toggle.querySelector(".toggle-icon--close");

    toggle.addEventListener("click", function () {
      const isOpen = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", isOpen);
      if (iconOpen)  iconOpen.style.display  = isOpen ? "none" : "";
      if (iconClose) iconClose.style.display = isOpen ? ""     : "none";

      document
        .getElementById("site-header")
        ?.classList.toggle("site-header--open", isOpen);
    });

    document.addEventListener("click", function (e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        if (iconOpen)  iconOpen.style.display  = "";
        if (iconClose) iconClose.style.display = "none";
        document.getElementById("site-header")
          ?.classList.remove("site-header--open");
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && nav.classList.contains("is-open")) {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.focus();
        document.getElementById("site-header")
          ?.classList.remove("site-header--open");
      }
    });
  }

  // ─── ACTIVE NAV LINK ────────────────────────────────────
  const currentPath = window.location.pathname;
  document.querySelectorAll(".site-nav__link").forEach(function (link) {
    const linkPath = new URL(link.href, window.location.origin).pathname;
    if (
      currentPath === linkPath ||
      (currentPath.startsWith(linkPath) && linkPath !== "/")
    ) {
      link.classList.add("is-active");
    }
  });

  // ─── SMOOTH SCROLL ──────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener("click", function (e) {
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        e.preventDefault();
        const headerHeight =
          document.querySelector(".site-header")?.offsetHeight || 80;
        const top =
          target.getBoundingClientRect().top +
          window.pageYOffset -
          headerHeight -
          16;
        window.scrollTo({ top, behavior: "smooth" });
      }
    });
  });

  // ─── FAQ ACCORDION ──────────────────────────────────────
  function initFaqAccordion() {
    document.querySelectorAll(".faq__question").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const isOpen = this.classList.contains("faq__question--open");
        const answer = document.getElementById(this.getAttribute("aria-controls"));

        // Close all
        document.querySelectorAll(".faq__question--open").forEach(function (openBtn) {
          openBtn.classList.remove("faq__question--open");
          openBtn.setAttribute("aria-expanded", "false");
          openBtn.closest(".faq__item")?.classList.remove("faq__item--open");
          const openAnswer = document.getElementById(openBtn.getAttribute("aria-controls"));
          if (openAnswer) openAnswer.classList.remove("faq__answer--open");
        });

        // Open clicked (if it wasn't already open)
        if (!isOpen && answer) {
          this.classList.add("faq__question--open");
          this.setAttribute("aria-expanded", "true");
          this.closest(".faq__item")?.classList.add("faq__item--open");
          answer.classList.add("faq__answer--open");
        }
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFaqAccordion);
  } else {
    initFaqAccordion();
  }

  // ─── ZONE ACCORDION ─────────────────────────────────────
  function initZoneAccordion() {
    document.querySelectorAll(".zone__trigger").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const item   = this.closest(".zone__item");
        const panel  = document.getElementById(this.getAttribute("aria-controls"));
        const isOpen = item.classList.contains("zone__item--open");

        // Close all
        document.querySelectorAll(".zone__item--open").forEach(function (openItem) {
          openItem.classList.remove("zone__item--open");
          const openBtn   = openItem.querySelector(".zone__trigger");
          const openPanel = openBtn ? document.getElementById(openBtn.getAttribute("aria-controls")) : null;
          if (openBtn)   openBtn.setAttribute("aria-expanded", "false");
          if (openPanel) openPanel.classList.remove("zone__panel--open");
        });

        // Open clicked (if it was closed)
        if (!isOpen && panel) {
          item.classList.add("zone__item--open");
          this.setAttribute("aria-expanded", "true");
          panel.classList.add("zone__panel--open");
        }
      });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initZoneAccordion);
  } else {
    initZoneAccordion();
  }

  // ─── HEADER SCROLL SHADOW ────────────────────────────────
  const siteHeader = document.getElementById("site-header");
  const headerEl   = document.querySelector(".site-header");
  let scrollRaf    = false;
  let wasScrolled  = false;

  window.addEventListener("scroll", function () {
    if (scrollRaf) return;
    scrollRaf = true;
    requestAnimationFrame(function () {
      const scrolled = window.scrollY > 10;
      if (scrolled !== wasScrolled) {
        siteHeader?.classList.toggle("is-scrolled", scrolled);
        headerEl?.classList.toggle("is-scrolled", scrolled);
        wasScrolled = scrolled;
      }
      scrollRaf = false;
    });
  }, { passive: true });

})();

