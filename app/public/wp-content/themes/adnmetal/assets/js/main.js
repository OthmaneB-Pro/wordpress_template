/* ==========================================================================
   ADN MÉTAL — Interactions
   Vanilla JS, performant, respects prefers-reduced-motion
   ========================================================================== */
(function () {
  "use strict";
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var d = document;
  var on = function (el, ev, fn, o) { el && el.addEventListener(ev, fn, o || false); };
  var $ = function (s, c) { return (c || d).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || d).querySelectorAll(s)); };

  /* ---------- 1. Sticky header state ---------- */
  var header = $(".site-header");
  var hero = $(".hero");
  function headerState() {
    if (!header) return;
    var y = window.scrollY || window.pageYOffset;
    header.classList.toggle("is-stuck", y > 40);
    if (hero) {
      var heroBottom = hero.offsetTop + hero.offsetHeight - 90;
      header.classList.toggle("is-light", y < heroBottom);
    }
  }
  headerState();
  on(window, "scroll", headerState, { passive: true });

  /* ---------- 2. Mobile nav ---------- */
  var toggle = $(".nav-toggle");
  function closeNav() { d.body.classList.remove("nav-open"); if (toggle) toggle.setAttribute("aria-expanded", "false"); }
  on(toggle, "click", function () {
    var open = d.body.classList.toggle("nav-open");
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
  });
  $$(".mobile-nav a").forEach(function (a) { on(a, "click", closeNav); });
  on(d, "keydown", function (e) { if (e.key === "Escape") closeNav(); });

  /* ---------- 3. Scroll reveal ---------- */
  var revealEls = $$("[data-reveal], [data-reveal-stagger], .step");
  if (reduce || !("IntersectionObserver" in window)) {
    revealEls.forEach(function (el) { el.classList.add("is-in"); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add("is-in"); io.unobserve(en.target); }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    revealEls.forEach(function (el) { io.observe(el); });
  }

  /* ---------- 4. Hero headline mask reveal ---------- */
  var heroLines = $$(".hero h1 .ln > span");
  if (heroLines.length && !reduce) {
    heroLines.forEach(function (s, i) {
      s.style.transition = "transform 1s cubic-bezier(.16,1,.3,1)";
      s.style.transitionDelay = (0.25 + i * 0.12) + "s";
    });
    requestAnimationFrame(function () {
      requestAnimationFrame(function () { heroLines.forEach(function (s) { s.style.transform = "translateY(0)"; }); });
    });
  } else {
    heroLines.forEach(function (s) { s.style.transform = "none"; });
  }

  /* ---------- 5. Subtle hero parallax ---------- */
  var heroBg = $(".hero__bg");
  if (heroBg && !reduce) {
    on(window, "scroll", function () {
      var y = window.scrollY || 0;
      if (y < window.innerHeight * 1.2) heroBg.style.transform = "translateY(" + (y * 0.18) + "px) scale(1.05)";
    }, { passive: true });
  }

  /* ---------- 6. Marquee — seamless infinite single stream ---------- */
  var mqTrack = $(".marquee__track");
  if (mqTrack && !reduce) {
    var mq = mqTrack.parentElement;
    var base = mqTrack.innerHTML;
    // Repeat the base items until one stream comfortably overflows the viewport
    var guard = 0;
    while (mqTrack.scrollWidth < mq.clientWidth * 1.5 && guard < 40) {
      mqTrack.insertAdjacentHTML("beforeend", base);
      guard++;
    }
    var halfWidth = mqTrack.scrollWidth;            // width of the single stream
    mqTrack.insertAdjacentHTML("beforeend", mqTrack.innerHTML); // two identical halves -> -50% loops seamlessly
    // Constant speed (~70px/s) whatever the content width
    mqTrack.style.animationDuration = Math.max(20, Math.round(halfWidth / 70)) + "s";
  }

  /* ---------- 7. Portfolio filtering ---------- */
  var filters = $$(".filter");
  var cards = $$(".work-card");
  filters.forEach(function (btn) {
    on(btn, "click", function () {
      var cat = btn.getAttribute("data-filter");
      filters.forEach(function (f) { f.classList.remove("is-active"); });
      btn.classList.add("is-active");
      cards.forEach(function (card) {
        var match = cat === "all" || card.getAttribute("data-cat") === cat;
        card.classList.toggle("is-hidden", !match);
      });
    });
  });

  /* ---------- 8. Lightbox ---------- */
  var lb = $(".lightbox");
  if (lb) {
    var lbMedia = $(".lightbox__media", lb);
    var lbTitle = $(".lightbox__cap h3", lb);
    var lbCat = $(".lightbox__cap .cat", lb);
    var lbDesc = $(".lightbox__cap p", lb);
    var triggers = [];
    function refreshTriggers() { triggers = $$(".work-card:not(.is-hidden) a"); }
    var current = -1;

    function openAt(i) {
      refreshTriggers();
      if (i < 0) i = triggers.length - 1;
      if (i >= triggers.length) i = 0;
      current = i;
      var a = triggers[i];
      if (!a) return;
      var card = a.closest(".work-card");
      lbMedia.innerHTML = a.querySelector(".work-card__media").innerHTML;
      if (lbTitle) lbTitle.textContent = card.getAttribute("data-title") || "";
      if (lbCat) lbCat.textContent = card.getAttribute("data-cat-label") || "";
      if (lbDesc) lbDesc.textContent = card.getAttribute("data-desc") || "";
      lb.classList.add("is-open");
      d.body.style.overflow = "hidden";
    }
    function closeLb() { lb.classList.remove("is-open"); d.body.style.overflow = ""; }

    $$(".work-card a").forEach(function (a, i) {
      on(a, "click", function (e) {
        e.preventDefault();
        refreshTriggers();
        openAt(triggers.indexOf(a));
      });
    });
    on($(".lightbox__close", lb), "click", closeLb);
    on($(".lightbox__nav--prev", lb), "click", function () { openAt(current - 1); });
    on($(".lightbox__nav--next", lb), "click", function () { openAt(current + 1); });
    on(lb, "click", function (e) { if (e.target === lb) closeLb(); });
    on(d, "keydown", function (e) {
      if (!lb.classList.contains("is-open")) return;
      if (e.key === "Escape") closeLb();
      if (e.key === "ArrowLeft") openAt(current - 1);
      if (e.key === "ArrowRight") openAt(current + 1);
    });
  }

  /* ---------- 9. Devis form (AJAX) ---------- */
  var form = $(".form[data-ajax]");
  if (form && window.AdnMetal) {
    var note = $(".form__note", form);
    on(form, "submit", function (e) {
      e.preventDefault();
      if (note) { note.className = "form__note"; note.textContent = ""; }
      form.classList.add("is-sending");
      var data = new FormData(form);
      data.append("action", "adnmetal_devis");
      data.append("nonce", window.AdnMetal.nonce);
      fetch(window.AdnMetal.ajax, { method: "POST", body: data, credentials: "same-origin" })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          form.classList.remove("is-sending");
          if (note) {
            note.textContent = res.data && res.data.message ? res.data.message : (res.success ? "Message envoyé." : "Une erreur est survenue.");
            note.classList.add(res.success ? "is-ok" : "is-err");
          }
          if (res.success) form.reset();
        })
        .catch(function () {
          form.classList.remove("is-sending");
          if (note) { note.textContent = "Connexion impossible. Réessayez ou appelez-nous directement."; note.classList.add("is-err"); }
        });
    });
  }

  /* ---------- 10. Current year ---------- */
  var yr = $("[data-year]");
  if (yr) yr.textContent = new Date().getFullYear();

  /* ---------- 11. Smooth in-page anchor (with header offset) ---------- */
  $$('a[href^="#"]').forEach(function (a) {
    on(a, "click", function (e) {
      var id = a.getAttribute("href");
      if (id.length < 2) return;
      var t = $(id);
      if (!t) return;
      e.preventDefault();
      var top = t.getBoundingClientRect().top + window.scrollY - 70;
      window.scrollTo({ top: top, behavior: reduce ? "auto" : "smooth" });
      closeNav();
    });
  });
})();
