/**
 * Bastovan Tema — gallery.js
 * Galerija: scroll track, split reveal, reviews, projekat filtracija
 */

(function () {
  "use strict";

  // ─── GALLERY SCROLL (dugmad) ─────────────────────────────
  const track   = document.getElementById("gallery-track");
  const btnPrev = document.getElementById("gallery-prev");
  const btnNext = document.getElementById("gallery-next");

  if (track && btnPrev && btnNext) {
    btnNext.addEventListener("click", () => {
      track.scrollBy({ left: 480, behavior: "smooth" });
    });
    btnPrev.addEventListener("click", () => {
      track.scrollBy({ left: -480, behavior: "smooth" });
    });
  }

  // ─── GALLERY SPLIT REVEAL ────────────────────────────────
  document.querySelectorAll(".gallery__split").forEach((split) => {
    const layerAfter = split.querySelector(".gallery__layer--after");
    const divider    = split.querySelector(".gallery__divider");
    const imgAfter   = layerAfter ? layerAfter.querySelector("img") : null;

    if (!layerAfter || !divider) return;

    let pos        = 50;
    let isDragging = false;
    let startX     = 0;
    let startPos   = 50;

    function syncImgWidth() {
      if (imgAfter) {
        imgAfter.style.width = split.offsetWidth + "px";
      }
    }

    function setPos(pct) {
      pos = Math.max(0, Math.min(100, pct));
      layerAfter.style.width = pos + "%";
      divider.style.left     = pos + "%";
    }

    syncImgWidth();
    setPos(50);

    window.addEventListener("resize", syncImgWidth);

    // ── MOUSE ──
    split.addEventListener("mousedown", (e) => {
      isDragging = true;
      startX     = e.clientX;
      startPos   = pos;
      e.preventDefault();
    });

    window.addEventListener("mousemove", (e) => {
      if (!isDragging) return;
      const rect  = split.getBoundingClientRect();
      const delta = ((e.clientX - startX) / rect.width) * 100;
      setPos(startPos + delta);
    });

    window.addEventListener("mouseup", () => {
      isDragging = false;
    });

    // ── TOUCH ──
    split.addEventListener("touchstart", (e) => {
      startX   = e.touches[0].clientX;
      startPos = pos;
    }, { passive: true });

    split.addEventListener("touchmove", (e) => {
      const rect  = split.getBoundingClientRect();
      const delta = ((e.touches[0].clientX - startX) / rect.width) * 100;
      setPos(startPos + delta);
    }, { passive: true });
  });

  // ─── REVIEWS SCROLL ─────────────────────────────────────
  const reviewsTrack = document.getElementById("reviews-track");
  const reviewsPrev  = document.getElementById("reviews-prev");
  const reviewsNext  = document.getElementById("reviews-next");

  if (reviewsTrack && reviewsPrev && reviewsNext) {
    reviewsNext.addEventListener("click", () => {
      reviewsTrack.scrollBy({ left: 380, behavior: "smooth" });
    });
    reviewsPrev.addEventListener("click", () => {
      reviewsTrack.scrollBy({ left: -380, behavior: "smooth" });
    });
  }

  // ─── GALERIJA PROJEKTI SCROLL ────────────────────────────
  document.querySelectorAll(".projekat__btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const t   = document.getElementById(btn.dataset.track);
      const dir = btn.dataset.dir === "next" ? 500 : -500;
      if (t) t.scrollBy({ left: dir, behavior: "smooth" });
    });
  });

  // ─── GALERIJA FILTRACIJA ─────────────────────────────────
  const filterBtns = document.querySelectorAll(".galerija-filter__btn");
  const subBtns    = document.querySelectorAll(".galerija-filter__sub");
  const projekti   = document.querySelectorAll(".projekat");
  const subRows    = document.querySelectorAll(".galerija-filter__row--usluge");

  function filterProjects(tip, uslugaId) {
    projekti.forEach((p) => {
      const pTip    = p.dataset.tip    || "";
      const pUsluge = p.dataset.usluge || "";
      let show = false;

      if (tip === "*") {
        show = true;
      } else if (uslugaId === "*") {
        show = pTip.includes(tip);
      } else {
        show = pTip.includes(tip) && pUsluge.includes(uslugaId);
      }

      if (show) {
        p.classList.remove("is-hiding");
        setTimeout(() => p.classList.remove("is-hidden"), 10);
      } else {
        p.classList.add("is-hiding");
        setTimeout(() => {
          p.classList.add("is-hidden");
          p.classList.remove("is-hiding");
        }, 300);
      }
    });
  }

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const filter = btn.dataset.filter;
      filterBtns.forEach((b) => b.classList.remove("is-active"));
      btn.classList.add("is-active");

      subRows.forEach((row) => {
        if (filter !== "*" && row.dataset.parent === filter) {
          row.style.display = "flex";
        } else {
          row.style.display = "none";
          row.querySelectorAll(".galerija-filter__sub").forEach((s, i) => {
            s.classList.toggle("is-active", i === 0);
          });
        }
      });

      filterProjects(filter, "*");
    });
  });

  subBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const tip    = btn.dataset.filter;
      const usluga = btn.dataset.usluga;

      btn
        .closest(".galerija-filter__row--usluge")
        .querySelectorAll(".galerija-filter__sub")
        .forEach((b) => b.classList.remove("is-active"));
      btn.classList.add("is-active");

      filterProjects(tip, usluga);
    });
  });

})();
