/**
 * Bastovan Tema — gallery.js
 * Galerija: scroll track, split reveal, reviews, projekat filtracija, modal, lightbox
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
      return card.offsetWidth + 20;
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

    track.addEventListener("touchstart", (e) => {
      if (!e.target.closest(".gallery__split")) e.preventDefault();
    }, { passive: false });

    track.addEventListener("touchmove", (e) => {
      if (!e.target.closest(".gallery__split")) e.preventDefault();
    }, { passive: false });
  }

  // ─── GALLERY SPLIT REVEAL ────────────────────────────────
  function initSplits(container) {
    const root = container || document;
    root.querySelectorAll(".gallery__split").forEach((split) => {
      if (split._splitInited) return;
      split._splitInited = true;

      const layerAfter = split.querySelector(".gallery__layer--after");
      const divider    = split.querySelector(".gallery__divider");
      const imgAfter   = layerAfter ? layerAfter.querySelector("img") : null;

      if (!layerAfter || !divider) return;

      let pos        = 50;
      let isDragging = false;
      let startX     = 0;
      let startPos   = 50;

      function syncImgWidth() {
        if (imgAfter) imgAfter.style.width = split.offsetWidth + "px";
      }

      function setPos(pct) {
        pos = Math.max(0, Math.min(100, pct));
        layerAfter.style.width = pos + "%";
        divider.style.left     = pos + "%";
      }

      syncImgWidth();
      setPos(50);
      window.addEventListener("resize", syncImgWidth);

      // Mouse
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
      window.addEventListener("mouseup", () => { isDragging = false; });

      // Touch
      let isSplitDragging = false;
      split.addEventListener("touchstart", (e) => {
        isSplitDragging = true;
        startX   = e.touches[0].clientX;
        startPos = pos;
      }, { passive: true });
      split.addEventListener("touchmove", (e) => {
        if (!isSplitDragging) return;
        e.preventDefault();
        const rect  = split.getBoundingClientRect();
        const delta = ((e.touches[0].clientX - startX) / rect.width) * 100;
        setPos(startPos + delta);
      }, { passive: false });
      split.addEventListener("touchend", () => {
        isSplitDragging = false;
      }, { passive: true });
    });
  }

  // Init splits on page load (for gallery section on home/landing)
  initSplits(document);

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
  const lb = document.createElement("div");
  lb.id = "lb";
  lb.className = "lb";
  lb.setAttribute("role", "dialog");
  lb.setAttribute("aria-modal", "true");
  lb.setAttribute("aria-label", "Pregled slike");
  lb.innerHTML = `
    <button class="lb__close" aria-label="Zatvori">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <line x1="1" y1="1" x2="17" y2="17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="17" y1="1" x2="1" y2="17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>
    <button class="lb__arrow lb__arrow--prev" aria-label="Prethodna slika">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <polyline points="13,3 6,10 13,17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="lb__main">
      <div class="lb__stage">
        <div class="lb__spinner"></div>
        <img class="lb__img" src="" alt="">
      </div>
      <div class="lb__caption">
        <span class="lb__title"></span>
        <span class="lb__counter"></span>
      </div>
      <div class="lb__thumbs-wrap">
        <div class="lb__thumbs"></div>
      </div>
    </div>
    <button class="lb__arrow lb__arrow--next" aria-label="Sledeća slika">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <polyline points="7,3 14,10 7,17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  `;
  document.body.appendChild(lb);

  const lbImg     = lb.querySelector(".lb__img");
  const lbSpinner = lb.querySelector(".lb__spinner");
  const lbTitle   = lb.querySelector(".lb__title");
  const lbCounter = lb.querySelector(".lb__counter");
  const lbThumbs  = lb.querySelector(".lb__thumbs");
  const lbPrev    = lb.querySelector(".lb__arrow--prev");
  const lbNext    = lb.querySelector(".lb__arrow--next");
  const lbClose   = lb.querySelector(".lb__close");

  let lbImages  = [];
  let lbCurrent = 0;

  function lbOpen(imgs, idx) {
    lbImages  = imgs;
    lbCurrent = idx;
    lbBuildThumbs();
    lbRender("none");
    lb.classList.add("is-open");
    document.body.style.overflow = "hidden";
    lbClose.focus();
  }

  function lbClose_() {
    lb.classList.remove("is-open");
    document.body.style.overflow = "";
  }

  function lbRender(dir) {
    const item       = lbImages[lbCurrent];
    const enterClass = dir === "next" ? "lb__img--from-right"
                     : dir === "prev" ? "lb__img--from-left"
                     : "lb__img--fade";

    lbImg.className          = "lb__img";
    lbSpinner.style.opacity  = "1";
    lbImg.src                = "";
    void lbImg.offsetWidth;
    lbImg.src = item.src;
    lbImg.alt = item.alt;
    lbImg.onload = () => {
      lbSpinner.style.opacity = "0";
      lbImg.classList.add(enterClass);
    };

    lbTitle.textContent   = item.title || "";
    lbCounter.textContent = (lbCurrent + 1) + " / " + lbImages.length;

    const multi = lbImages.length > 1;
    lbPrev.style.display = multi ? "flex" : "none";
    lbNext.style.display = multi ? "flex" : "none";
    lbUpdateThumbs();
  }

  function lbBuildThumbs() {
    lbThumbs.innerHTML = "";
    const wrap = lbThumbs.closest(".lb__thumbs-wrap");
    if (lbImages.length < 2) { wrap.style.display = "none"; return; }
    wrap.style.display = "flex";

    lbImages.forEach((img, idx) => {
      const btn = document.createElement("button");
      btn.className = "lb__thumb" + (idx === lbCurrent ? " is-active" : "");
      btn.setAttribute("aria-label", "Slika " + (idx + 1));
      const el = document.createElement("img");
      el.src = img.src; el.alt = ""; el.loading = "lazy";
      btn.appendChild(el);
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const d = idx > lbCurrent ? "next" : "prev";
        lbCurrent = idx;
        lbRender(d);
      });
      lbThumbs.appendChild(btn);
    });
  }

  function lbUpdateThumbs() {
    lbThumbs.querySelectorAll(".lb__thumb").forEach((t, i) => {
      t.classList.toggle("is-active", i === lbCurrent);
    });
    const active = lbThumbs.querySelector(".lb__thumb.is-active");
    if (active) active.scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
  }

  function lbPrev_() { lbCurrent = (lbCurrent - 1 + lbImages.length) % lbImages.length; lbRender("prev"); }
  function lbNext_() { lbCurrent = (lbCurrent + 1) % lbImages.length; lbRender("next"); }

  lbPrev.addEventListener("click",  (e) => { e.stopPropagation(); lbPrev_(); });
  lbNext.addEventListener("click",  (e) => { e.stopPropagation(); lbNext_(); });
  lbClose.addEventListener("click", lbClose_);
  lb.addEventListener("click", (e) => { if (e.target === lb) lbClose_(); });

  document.addEventListener("keydown", (e) => {
    if (!lb.classList.contains("is-open")) return;
    if (e.key === "Escape")     lbClose_();
    if (e.key === "ArrowLeft")  lbPrev_();
    if (e.key === "ArrowRight") lbNext_();
  });

  let lbTouchX = 0;
  lb.addEventListener("touchstart", (e) => { lbTouchX = e.touches[0].clientX; }, { passive: true });
  lb.addEventListener("touchend",   (e) => {
    const dx = e.changedTouches[0].clientX - lbTouchX;
    if (Math.abs(dx) > 50) { dx < 0 ? lbNext_() : lbPrev_(); }
  }, { passive: true });

  // Wire a container's images to the lightbox
  function wireLightboxImages(container, title) {
    const slike = (container || document).querySelectorAll(".pd__slika img");
    if (!slike.length) return;

    const projectTitle = title ||
      container?.closest("[data-id]")?.querySelector(".projekat-card__naslov")?.textContent?.trim() || "";

    const imgs = Array.from(slike).map((img) => ({
      src:   img.src,
      alt:   img.alt || "",
      title: projectTitle,
    }));

    slike.forEach((img, idx) => {
      img.style.cursor = "zoom-in";
      img.addEventListener("click", () => lbOpen(imgs, idx));
    });
  }

  // ─── PROJECT MODAL ───────────────────────────────────────
  const modal     = document.getElementById("pd-modal");
  const backdrop  = modal?.querySelector(".pd-modal__backdrop");
  const closeBtn  = modal?.querySelector(".pd-modal__close");
  const modalBody = modal?.querySelector(".pd-modal__body");
  const pdPrev    = document.getElementById("pd-prev");
  const pdNext    = document.getElementById("pd-next");
  const pdCounter = document.getElementById("pd-counter");

  if (modal) {
    let visibleCards = [];
    let currentIdx   = 0;

    function getVisibleCards() {
      return Array.from(document.querySelectorAll(".projekat-card:not(.is-hidden)"));
    }

    function loadContent(projekatId) {
      const source = document.getElementById("detail-" + projekatId);
      if (!source) return;

      modalBody.innerHTML = "";
      const clone = source.cloneNode(true);
      clone.removeAttribute("hidden");
      clone.removeAttribute("id");
      modalBody.appendChild(clone);

      initSplits(modal);

      const title = document.querySelector(
        `.projekat-card[data-id="${projekatId}"] .projekat-card__naslov`
      )?.textContent?.trim() || "";
      wireLightboxImages(modal, title);
    }

    function updateNav() {
      if (pdCounter) pdCounter.textContent = (currentIdx + 1) + " / " + visibleCards.length;
      if (pdPrev)    pdPrev.disabled  = currentIdx === 0;
      if (pdNext)    pdNext.disabled  = currentIdx === visibleCards.length - 1;
    }

    function openModal(projekatId) {
      visibleCards = getVisibleCards();
      currentIdx   = visibleCards.findIndex((c) => c.dataset.id === String(projekatId));
      if (currentIdx === -1) currentIdx = 0;

      loadContent(projekatId);
      updateNav();

      modal.removeAttribute("hidden");
      document.body.style.overflow = "hidden";
    }

    function goTo(idx) {
      if (idx < 0 || idx >= visibleCards.length) return;
      currentIdx = idx;

      modalBody.classList.add("is-transitioning");
      // Scroll dialog to top smoothly
      modal.querySelector(".pd-modal__dialog").scrollTop = 0;

      setTimeout(() => {
        loadContent(visibleCards[currentIdx].dataset.id);
        updateNav();
        modalBody.classList.remove("is-transitioning");
      }, 180);
    }

    function closeModal() {
      modal.setAttribute("hidden", "");
      document.body.style.overflow = "";
      modalBody.innerHTML = "";
    }

    pdPrev?.addEventListener("click", () => goTo(currentIdx - 1));
    pdNext?.addEventListener("click", () => goTo(currentIdx + 1));

    document.querySelectorAll(".projekat-card").forEach((card) => {
      card.addEventListener("click", () => openModal(card.dataset.id));
      card.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          openModal(card.dataset.id);
        }
      });
    });

    closeBtn?.addEventListener("click", closeModal);
    backdrop?.addEventListener("click", closeModal);

    document.addEventListener("keydown", (e) => {
      if (modal.hasAttribute("hidden")) return;
      if (lb.classList.contains("is-open")) return; // lightbox handles arrows
      if (e.key === "Escape")     closeModal();
      if (e.key === "ArrowLeft")  goTo(currentIdx - 1);
      if (e.key === "ArrowRight") goTo(currentIdx + 1);
    });
  }

  // ─── GALERIJA FILTRACIJA ─────────────────────────────────
  const filterBtns = document.querySelectorAll(".galerija-filter__btn");
  const subBtns    = document.querySelectorAll(".galerija-filter__sub");
  const cards      = document.querySelectorAll(".projekat-card");
  const subRows    = document.querySelectorAll(".galerija-filter__row--usluge");
  const emptyEl    = document.getElementById("galerija-empty");

  // ── 1. Filter count badges ──────────────────────────────
  const countsByTip = {};
  cards.forEach((card) => {
    (card.dataset.tip || "").split(" ").filter(Boolean).forEach((tip) => {
      countsByTip[tip] = (countsByTip[tip] || 0) + 1;
    });
  });

  filterBtns.forEach((btn) => {
    const filter = btn.dataset.filter;
    const count  = filter === "*" ? cards.length : (countsByTip[filter] || 0);
    if (count > 0) {
      const badge = document.createElement("span");
      badge.className   = "filter-count";
      badge.textContent = count;
      btn.appendChild(badge);
    }
  });

  // ── 2. Scroll entrance animations ──────────────────────
  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.remove("card--will-animate");
        // Reset delay after entrance so hover is instant
        entry.target.addEventListener("transitionend", () => {
          entry.target.style.transitionDelay = "0ms";
        }, { once: true });
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.08, rootMargin: "0px 0px -32px 0px" });

    cards.forEach((card, i) => {
      card.classList.add("card--will-animate");
      card.style.transitionDelay = (i % 3) * 80 + "ms";
      observer.observe(card);
    });
  }

  // ── 3. Filter logic with empty state ───────────────────
  function filterProjects(tip, uslugaId) {
    let visibleCount = 0;

    cards.forEach((card) => {
      const pTip    = card.dataset.tip    || "";
      const pUsluge = card.dataset.usluge || "";
      let show = false;

      if (tip === "*") {
        show = true;
      } else if (uslugaId === "*") {
        show = pTip.includes(tip);
      } else {
        show = pTip.includes(tip) && pUsluge.includes(uslugaId);
      }

      if (show) {
        visibleCount++;
        card.classList.remove("is-hiding");
        setTimeout(() => card.classList.remove("is-hidden"), 10);
      } else {
        card.classList.add("is-hiding");
        setTimeout(() => {
          card.classList.add("is-hidden");
          card.classList.remove("is-hiding");
        }, 300);
      }
    });

    // Show/hide empty state after hide animation finishes
    if (emptyEl) {
      setTimeout(() => {
        emptyEl.hidden = visibleCount > 0;
      }, 320);
    }
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

      btn.closest(".galerija-filter__row--usluge")
        .querySelectorAll(".galerija-filter__sub")
        .forEach((b) => b.classList.remove("is-active"));
      btn.classList.add("is-active");

      filterProjects(tip, usluga);
    });
  });

})();
