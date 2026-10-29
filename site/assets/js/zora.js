/* Zora's House — Home & About page interactions.
   Plain JS, no dependencies. Every feature is optional: pages read fine without it. */
(function () {
  'use strict';

  var roots = document.querySelectorAll('.zh');
  roots.forEach(function (el) { el.classList.add('zh-js'); });

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Mobile nav (preview header only) ---- */
  document.querySelectorAll('.zh-nav-toggle').forEach(function (btn) {
    var nav = document.getElementById(btn.getAttribute('aria-controls'));
    if (!nav) return;
    btn.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* ---- Hero headline options ----
     The hero <h1> lists the approved alternatives in data-zh-options (separated by |).
     Preview another with ?hero=2 or ?hero=3 in the URL. */
  var heroTitle = document.querySelector('[data-zh-options]');
  if (heroTitle) {
    var match = /[?&]hero=(\d)/.exec(window.location.search);
    if (match) {
      var options = heroTitle.getAttribute('data-zh-options').split('|');
      var pick = options[parseInt(match[1], 10) - 1];
      if (pick) heroTitle.textContent = pick.trim();
    }
  }

  /* ---- Scroll reveal ---- */
  var reveals = document.querySelectorAll('.zh-reveal');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---- "I'd like to…" tabs (WAI-ARIA tabs pattern) ---- */
  document.querySelectorAll('[data-zh-tabs]').forEach(function (group) {
    var tabs = Array.prototype.slice.call(group.querySelectorAll('[role="tab"]'));
    var current = document.querySelector('[data-zh-tabs-current]');

    function select(tab, focus) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        t.tabIndex = on ? 0 : -1;
        var panel = document.getElementById(t.getAttribute('aria-controls'));
        if (panel) {
          panel.hidden = !on;
          panel.classList.toggle('is-active', on);
        }
      });
      if (current) current.textContent = tab.getAttribute('data-zh-verb') || '';
      if (focus) tab.focus();
    }

    tabs.forEach(function (tab, i) {
      tab.addEventListener('click', function () { select(tab, false); });
      tab.addEventListener('keydown', function (e) {
        var next = null;
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
        if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
        if (e.key === 'Home') next = tabs[0];
        if (e.key === 'End') next = tabs[tabs.length - 1];
        if (next) { e.preventDefault(); select(next, true); }
      });
    });
  });

  /* ---- Count-up numbers in the impact banner ----
     Only runs on elements with a numeric data-zh-count, so placeholder stats stay untouched. */
  var counters = document.querySelectorAll('[data-zh-count]');
  function runCount(el) {
    var target = parseFloat(el.getAttribute('data-zh-count'));
    var prefix = el.getAttribute('data-zh-prefix') || '';
    var suffix = el.getAttribute('data-zh-suffix') || '';
    if (isNaN(target)) return;
    if (reduceMotion) { el.textContent = prefix + target.toLocaleString() + suffix; return; }
    var start = null, dur = 1600;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = prefix + Math.round(target * eased).toLocaleString() + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if (counters.length && 'IntersectionObserver' in window) {
    var co = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { runCount(entry.target); co.unobserve(entry.target); }
      });
    }, { threshold: 0.6 });
    counters.forEach(function (el) { co.observe(el); });
  }

  /* ---- Timeline scroll buttons ---- */
  document.querySelectorAll('[data-zh-timeline]').forEach(function (wrap) {
    var track = wrap.querySelector('.zh-timeline__track');
    wrap.querySelectorAll('[data-zh-dir]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var dir = parseInt(btn.getAttribute('data-zh-dir'), 10);
        track.scrollBy({ left: dir * track.clientWidth * 0.8, behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    });
  });
})();
