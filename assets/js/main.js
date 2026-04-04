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

