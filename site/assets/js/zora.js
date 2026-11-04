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

  /* ---- Contact form ----
     With data-zh-endpoint (e.g. a Formspree URL) the message is posted there.
     Without one, the visitor's email app opens with the message filled in, addressed to data-zh-email.
     A link like contact.html?topic=Events preselects the topic. */
  document.querySelectorAll('[data-zh-contact]').forEach(function (form) {
    var notice = form.querySelector('.zh-form__notice');
    var topic = form.querySelector('[name="topic"]');
    var wanted = new URLSearchParams(window.location.search).get('topic');
    if (topic && wanted) {
      Array.prototype.forEach.call(topic.options, function (o) { if (o.text === wanted) o.selected = true; });
    }
    function say(text) {
      if (!notice) return;
      notice.textContent = text;
      notice.hidden = false;
    }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var data = new FormData(form);
      var endpoint = form.getAttribute('data-zh-endpoint');
      if (endpoint) {
        fetch(endpoint, { method: 'POST', body: data, headers: { Accept: 'application/json' } })
          .then(function (r) {
            if (!r.ok) throw new Error(r.status);
            form.reset();
            say('Thank you — your message is on its way. We’ll be in touch soon.');
          })
          .catch(function () { say('Sorry, your message could not be sent. Please email us instead.'); });
        return;
      }
      var subject = (data.get('topic') || 'Message') + ' — ' + data.get('name');
      var body = data.get('message') + '\n\n' + data.get('name') + '\n' + data.get('email');
      window.location.href = 'mailto:' + (form.getAttribute('data-zh-email') || '') +
        '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
      say('Your email app should open with your message ready to send.');
    });
  });

  /* ---- Newsletter signup ----
     Once a mailing-list provider's form URL is set as the form's action, it submits there as normal. */
  document.querySelectorAll('[data-zh-newsletter]').forEach(function (form) {
    if (form.getAttribute('action')) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var msg = form.querySelector('.zh-signup__msg');
      if (msg) {
        msg.textContent = 'Our newsletter signup is coming soon. Thanks for your interest!';
        msg.hidden = false;
      }
    });
  });

  /* ---- Events page: Category / Month filters and "Show more events" ----
     Options come from each event row's tag (.zh-erow__tag) and month (the date badge's <small>). */
  document.querySelectorAll('[data-zh-events]').forEach(function (wrap) {
    var list = wrap.querySelector('.zh-elist');
    if (!list) return;
    var rows = Array.prototype.slice.call(list.querySelectorAll('.zh-erow'));
    if (!rows.length) return;
    var PAGE = 5;
    var limit = PAGE;
    var uid = 0;

    function textOf(row, sel) {
      var el = row.querySelector(sel);
      return el ? el.textContent.trim() : '';
    }
    function uniq(values) {
      return values.filter(function (v, i) { return v && values.indexOf(v) === i; });
    }
    function makeSelect(label, allLabel, values) {
      var lab = document.createElement('label');
      var sel = document.createElement('select');
      sel.id = 'zh-efilter-' + (++uid);
      lab.setAttribute('for', sel.id);
      lab.textContent = label;
      [allLabel].concat(values).forEach(function (v, i) {
        var o = document.createElement('option');
        o.value = i ? v : '';
        o.textContent = v;
        sel.appendChild(o);
      });
      lab.appendChild(sel);
      return { label: lab, select: sel };
    }

    var cat = makeSelect('Category', 'All events', uniq(rows.map(function (r) { return textOf(r, '.zh-erow__tag'); })));
    var mon = makeSelect('Month', 'Upcoming', uniq(rows.map(function (r) { return textOf(r, '.zh-event__date small'); })));
    var count = document.createElement('p');
    count.className = 'zh-efilter__count';
    count.setAttribute('aria-live', 'polite');
    var bar = document.createElement('div');
    bar.className = 'zh-efilter';
    bar.appendChild(cat.label);
    bar.appendChild(mon.label);
    bar.appendChild(count);
    wrap.insertBefore(bar, wrap.firstChild);

    var empty = document.createElement('p');
    empty.className = 'zh-eempty';
    empty.textContent = 'No events match these filters yet.';
    var more = document.createElement('div');
    more.className = 'zh-emore';
    var moreBtn = document.createElement('button');
    moreBtn.type = 'button';
    moreBtn.className = 'zh-btn zh-btn--ghost';
    moreBtn.textContent = 'Show more events';
    more.appendChild(moreBtn);
    list.parentNode.insertBefore(empty, list.nextSibling);
    list.parentNode.insertBefore(more, empty.nextSibling);

    function apply() {
      var c = cat.select.value;
      var m = mon.select.value;
      var matches = 0;
      rows.forEach(function (row) {
        var ok = (!c || textOf(row, '.zh-erow__tag') === c) && (!m || textOf(row, '.zh-event__date small') === m);
        if (ok) matches++;
        row.hidden = !ok || matches > limit;
      });
      empty.hidden = matches > 0;
      more.hidden = matches <= limit;
      count.textContent = matches + (matches === 1 ? ' event' : ' events');
    }
    [cat.select, mon.select].forEach(function (s) {
      s.addEventListener('change', function () { limit = PAGE; apply(); });
    });
    moreBtn.addEventListener('click', function () { limit += PAGE; apply(); });
    apply();
  });

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
