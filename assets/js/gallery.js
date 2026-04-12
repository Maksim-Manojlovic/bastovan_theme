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
    function getCardWidth() {
      const card = track.querySelector(".gallery__card");
      if (!card) return 480;
      return card.offsetWidth + 20; // card width + gap
    }

    function updateBtns() {
      const maxScroll = track.scrollWidth - track.clientWidth;
      btnPrev.disabled = track.scrollLeft <= 2;
      btnNext.disabled = track.scrollLeft >= maxScroll - 2;
      btnPrev.style.opacity = btnPrev.disabled ? "0.3" : "1";
      btnNext.style.opacity = btnNext.disabled ? "0.3" : "1";
    }

    btnNext.addEventListener("click", () => {
      track.scrollBy({ left: getCardWidth(), behavior: "smooth" });
    });
    btnPrev.addEventListener("click", () => {
      track.scrollBy({ left: -getCardWidth(), behavior: "smooth" });
    });

    track.addEventListener("scroll", updateBtns, { passive: true });
    updateBtns();

    // Disable touch swipe on track on mobile — arrows only
    track.addEventListener("touchstart", (e) => {
      if (!e.target.closest(".gallery__split")) {
        e.preventDefault();
      }
    }, { passive: false });

    track.addEventListener("touchmove", (e) => {
      if (!e.target.closest(".gallery__split")) {
        e.preventDefault();
      }
    }, { passive: false });
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
    let isSplitDragging = false;

    split.addEventListener("touchstart", (e) => {
      isSplitDragging = true;
      startX   = e.touches[0].clientX;
      startPos = pos;
    }, { passive: true });

    split.addEventListener("touchmove", (e) => {
      if (!isSplitDragging) return;
      e.preventDefault(); // stop page/track scroll while dragging split
      const rect  = split.getBoundingClientRect();
      const delta = ((e.touches[0].clientX - startX) / rect.width) * 100;
      setPos(startPos + delta);
    }, { passive: false });

    split.addEventListener("touchend", () => {
      isSplitDragging = false;
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

  // ─── LIGHTBOX ────────────────────────────────────────────
  (function () {
    // Inject lightbox DOM once
    const lb = document.createElement("div");
    lb.id        = "lb";
    lb.className = "lb";
    lb.setAttribute("role", "dialog");
    lb.setAttribute("aria-modal", "true");
    lb.setAttribute("aria-label", "Pregled slike");
    lb.innerHTML = `
      <button class="lb__close" aria-label="Zatvori">✕</button>
      <button class="lb__arrow lb__arrow--prev" aria-label="Prethodna slika">&#8592;</button>
      <div class="lb__stage">
        <img class="lb__img" src="" alt="">
      </div>
      <button class="lb__arrow lb__arrow--next" aria-label="Sledeća slika">&#8594;</button>
      <div class="lb__counter"></div>
    `;
    document.body.appendChild(lb);

    const lbImg     = lb.querySelector(".lb__img");
    const lbCounter = lb.querySelector(".lb__counter");
    const lbPrev    = lb.querySelector(".lb__arrow--prev");
    const lbNext    = lb.querySelector(".lb__arrow--next");
    const lbClose   = lb.querySelector(".lb__close");

    let images  = []; // [{src, alt}] for current project
    let current = 0;

    function open(imgs, idx) {
      images  = imgs;
      current = idx;
      render();
      lb.classList.add("is-open");
      document.body.style.overflow = "hidden";
      lbClose.focus();
    }

    function close() {
      lb.classList.remove("is-open");
      document.body.style.overflow = "";
    }

    function render() {
      const item = images[current];
      lbImg.classList.remove("lb__img--in");
      // force reflow then animate
      void lbImg.offsetWidth;
      lbImg.src = item.src;
      lbImg.alt = item.alt;
      lbImg.classList.add("lb__img--in");
      lbCounter.textContent = (current + 1) + " / " + images.length;
      lbPrev.style.visibility = images.length > 1 ? "visible" : "hidden";
      lbNext.style.visibility = images.length > 1 ? "visible" : "hidden";
    }

    function prev() { current = (current - 1 + images.length) % images.length; render(); }
    function next() { current = (current + 1) % images.length; render(); }

    // Bind arrows + close
    lbPrev.addEventListener("click",  (e) => { e.stopPropagation(); prev(); });
    lbNext.addEventListener("click",  (e) => { e.stopPropagation(); next(); });
    lbClose.addEventListener("click", close);
    lb.addEventListener("click", (e) => { if (e.target === lb) close(); });

    // Keyboard
    document.addEventListener("keydown", (e) => {
      if (!lb.classList.contains("is-open")) return;
      if (e.key === "Escape")      close();
      if (e.key === "ArrowLeft")   prev();
      if (e.key === "ArrowRight")  next();
    });

    // Touch swipe
    let touchStartX = 0;
    lb.addEventListener("touchstart", (e) => {
      touchStartX = e.touches[0].clientX;
    }, { passive: true });
    lb.addEventListener("touchend", (e) => {
      const dx = e.changedTouches[0].clientX - touchStartX;
      if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); }
    }, { passive: true });

    // Wire up all projekt images
    document.querySelectorAll(".projekat").forEach((projekat) => {
      const slike = projekat.querySelectorAll(".projekat__slika img");
      if (!slike.length) return;

      const imgs = Array.from(slike).map((img) => ({
        src: img.src,
        alt: img.alt || "",
      }));

      slike.forEach((img, idx) => {
        img.style.cursor = "zoom-in";
        img.addEventListener("click", () => open(imgs, idx));
      });
    });
  })();

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
