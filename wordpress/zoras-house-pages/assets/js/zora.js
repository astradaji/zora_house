/* Zora's House — front-end enhancements for the block-built sections.
   Plain JS, no dependencies. Everything is built from whatever blocks the client has on the page,
   so adding/removing cards, tabs or timeline items in the editor just works.
   Without JS the content still reads fine (cards grid, wrapped timeline, static list). */
(function () {
  'use strict';

  var sections = document.querySelectorAll('.zh');
  if (!sections.length) return;
  sections.forEach(function (el) { el.classList.add('zh-js'); });

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var uid = 0;

  /* ---- "As Seen In" list -> seamless scrolling marquee ---- */
  document.querySelectorAll('.zh-press .zh-marquee__list').forEach(function (list) {
    var wrap = document.createElement('div');
    var track = document.createElement('div');
    wrap.className = 'zh-marquee';
    track.className = 'zh-marquee__track';
    list.parentNode.insertBefore(wrap, list);
    wrap.appendChild(track);
    track.appendChild(list);
    var copy = list.cloneNode(true);
    copy.setAttribute('aria-hidden', 'true');
    track.appendChild(copy);
  });

  /* ---- "I'd like to…" cards -> tabs (WAI-ARIA tabs pattern) ---- */
  document.querySelectorAll('.zh-paths').forEach(function (group) {
    var panels = Array.prototype.slice.call(group.querySelectorAll(':scope > .zh-path'));
    if (panels.length < 2) return;

    var tablist = document.createElement('div');
    tablist.className = 'zh-tabs';
    tablist.setAttribute('role', 'tablist');
    var tabs = panels.map(function (panel) {
      var n = ++uid;
      var tag = panel.querySelector('.zh-path__tag');
      var title = panel.querySelector('h1, h2, h3, h4');
      var tab = document.createElement('button');
      tab.type = 'button';
      tab.className = 'zh-tab';
      tab.id = 'zh-tab-' + n;
      tab.setAttribute('role', 'tab');
      panel.id = panel.id || 'zh-panel-' + n;
      panel.setAttribute('role', 'tabpanel');
      panel.setAttribute('aria-labelledby', tab.id);
      tab.setAttribute('aria-controls', panel.id);
      if (tag) { var s = document.createElement('small'); s.textContent = tag.textContent; tab.appendChild(s); }
      var t = document.createElement('strong');
      t.textContent = title ? title.textContent : 'Option ' + n;
      tab.appendChild(t);
      tablist.appendChild(tab);
      return tab;
    });

    var stage = document.createElement('div');
    panels.forEach(function (p) { stage.appendChild(p); });
    group.appendChild(tablist);
    group.appendChild(stage);
    group.classList.add('zh-paths--tabs');

    function select(tab, focus) {
      tabs.forEach(function (t, i) {
        var on = t === tab;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        t.tabIndex = on ? 0 : -1;
        panels[i].hidden = !on;
        panels[i].classList.toggle('is-active', on);
      });
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
    select(tabs[0], false);
  });

  /* ---- Impact numbers: "[...]" placeholders get a dashed style; numbers count up ---- */
  function runCount(el, target, prefix, suffix) {
    var start = null, dur = 1600;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      el.textContent = prefix + Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString() + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  var counters = [];
  document.querySelectorAll('.zh-stat').forEach(function (stat) {
    var num = stat.querySelector('.zh-stat__num');
    if (!num) return;
    var text = num.textContent.trim();
    if (text.charAt(0) === '[') { stat.classList.add('zh-stat--pending'); return; }
    // e.g. "5,000+", "$2M", "85%". Plain years (1900–2099) are left as they are.
    var m = /^([^\d]*)([\d,]+)([^\d]*)$/.exec(text);
    if (!m || /^(19|20)\d\d$/.test(text)) return;
    var value = parseInt(m[2].replace(/,/g, ''), 10);
    if (!isNaN(value)) counters.push({ el: num, value: value, prefix: m[1], suffix: m[3] });
  });
  if (counters.length && 'IntersectionObserver' in window && !reduceMotion) {
    var co = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        counters.forEach(function (c) {
          if (c.el === entry.target) runCount(c.el, c.value, c.prefix, c.suffix);
        });
        co.unobserve(entry.target);
      });
    }, { threshold: 0.6 });
    counters.forEach(function (c) { co.observe(c.el); });
  }

  /* ---- Timeline: add back/forward arrows next to the "Our Journey" heading ---- */
  document.querySelectorAll('.zh-timeline').forEach(function (wrap) {
    var track = wrap.querySelector('.zh-timeline__track');
    var head = wrap.querySelector('.zh-timeline__head');
    if (!track || !head) return;
    [['-1', '←', 'Scroll timeline back'], ['1', '→', 'Scroll timeline forward']].forEach(function (b) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'zh-timeline__btn';
      btn.textContent = b[1];
      btn.setAttribute('aria-label', b[2]);
      btn.addEventListener('click', function () {
        track.scrollBy({ left: parseInt(b[0], 10) * track.clientWidth * 0.8, behavior: reduceMotion ? 'auto' : 'smooth' });
      });
      head.appendChild(btn);
    });
    track.setAttribute('tabindex', '0');
  });

  /* ---- Scroll reveal ---- */
  var reveals = document.querySelectorAll('.zh-split > *, .zh-card, .zh-pcard, .zh-person, .zh-stat, .zh-news, .zh-paths');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          el.classList.add('is-in');
          io.unobserve(el);
          // drop the stagger once revealed so hover effects aren't delayed
          setTimeout(function () { el.style.transitionDelay = ''; }, 1200);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(function (el, i) {
      el.classList.add('zh-reveal');
      el.style.transitionDelay = (i % 4) * 0.1 + 's';
      io.observe(el);
    });
  }
})();
