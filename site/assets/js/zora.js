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

  /* ---- Hero background video: stays still for visitors who prefer reduced motion ---- */
  document.querySelectorAll('[data-zh-hero-video]').forEach(function (video) {
    if (reduceMotion) { video.removeAttribute('autoplay'); video.pause(); }
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
     A link like contact.html?topic=Membership preselects the topic.
     After sending, the matching "what happens next" answer (details[data-zh-topic]) is added to the notice. */
  document.querySelectorAll('[data-zh-contact]').forEach(function (form) {
    var notice = form.querySelector('.zh-form__notice');
    var topic = form.querySelector('[name="topic"]');
    var wanted = new URLSearchParams(window.location.search).get('topic');
    if (topic && wanted) {
      Array.prototype.forEach.call(topic.options, function (o) { if (o.text === wanted) o.selected = true; });
    }
    function nextSteps(chosen) {
      var match = null;
      document.querySelectorAll('[data-zh-topic]').forEach(function (d) {
        if (d.getAttribute('data-zh-topic') === chosen) match = d;
      });
      if (!match) match = document.querySelector('[data-zh-topic="Other"]');
      var p = match && match.querySelector('p');
      return p ? ' ' + p.textContent.trim() : '';
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
      var next = nextSteps(data.get('topic'));
      if (endpoint) {
        fetch(endpoint, { method: 'POST', body: data, headers: { Accept: 'application/json' } })
          .then(function (r) {
            if (!r.ok) throw new Error(r.status);
            form.reset();
            say('Your message is on its way.' + next);
          })
          .catch(function () { say('Sorry, your message could not be sent. Please email us instead.'); });
        return;
      }
      var subject = (data.get('topic') || 'Message') + ' — ' + data.get('name');
      var body = data.get('message') + '\n\n' + data.get('name') + '\n' + data.get('email');
      window.location.href = 'mailto:' + (form.getAttribute('data-zh-email') || '') +
        '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
      say('Your email app should open with your message ready to send.' + next);
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

  /* ---- Events page: filters, List / Calendar views and "Show more events" ----
     Everything is read from each event row's data- attributes (see the note at the top of news-events.html).
     Past events drop off the list; the calendar still shows them in earlier months. */
  document.querySelectorAll('[data-zh-events]').forEach(function (wrap) {
    var list = wrap.querySelector('.zh-elist');
    if (!list) return;
    var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var PAGE = 5;
    var limit = PAGE;
    var uid = 0;

    function parseDate(s) {
      var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(s || '');
      return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null;
    }
    function monthName(d) { return MONTHS[d.getMonth()] + ' ' + d.getFullYear(); }
    function valuesOf(ev, key) {
      if (key === 'month') return ev.date ? [monthName(ev.date)] : [];
      return (ev.row.getAttribute('data-' + key) || '').split(',')
        .map(function (v) { return v.trim(); }).filter(Boolean);
    }

    var now = new Date();
    var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    var events = Array.prototype.slice.call(list.querySelectorAll('.zh-erow')).map(function (row) {
      var link = row.querySelector('.zh-btn') || row.querySelector('a');
      var title = row.querySelector('.zh-event__title');
      return {
        row: row,
        date: parseDate(row.getAttribute('data-date')),
        link: link,
        title: title ? title.textContent.trim() : ''
      };
    });
    if (!events.length) return;
    events.sort(function (a, b) { return (a.date || 0) - (b.date || 0); });
    events.forEach(function (ev) { list.appendChild(ev.row); });
    function upcoming(ev) { return !ev.date || ev.date >= today; }

    /* filter bar: one select per attribute that at least one event uses */
    var FILTERS = [
      { key: 'category', label: 'Category', all: 'All categories' },
      { key: 'audience', label: 'Audience', all: 'Everyone' },
      { key: 'month', label: 'Date', all: 'All dates' },
      { key: 'format', label: 'In person / virtual', all: 'Any' },
      { key: 'cost', label: 'Free / paid', all: 'Any' },
      { key: 'host', label: 'Host', all: 'All hosts' }
    ];
    var bar = document.createElement('div');
    bar.className = 'zh-efilter';
    var active = [];
    FILTERS.forEach(function (f) {
      var values = [];
      events.forEach(function (ev) {
        valuesOf(ev, f.key).forEach(function (v) { if (values.indexOf(v) === -1) values.push(v); });
      });
      if (!values.length) return;
      var lab = document.createElement('label');
      var sel = document.createElement('select');
      sel.id = 'zh-efilter-' + (++uid);
      lab.setAttribute('for', sel.id);
      lab.textContent = f.label;
      [f.all].concat(values).forEach(function (v, i) {
        var o = document.createElement('option');
        o.value = i ? v : '';
        o.textContent = v;
        sel.appendChild(o);
      });
      lab.appendChild(sel);
      bar.appendChild(lab);
      active.push({ key: f.key, select: sel });
    });
    var count = document.createElement('p');
    count.className = 'zh-efilter__count';
    count.setAttribute('aria-live', 'polite');
    bar.appendChild(count);

    /* List / Calendar toggle */
    var toggle = document.createElement('div');
    toggle.className = 'zh-eview';
    toggle.setAttribute('role', 'group');
    toggle.setAttribute('aria-label', 'View');
    var views = {};
    ['List', 'Calendar'].forEach(function (name, i) {
      var b = document.createElement('button');
      b.type = 'button';
      b.textContent = name + ' view';
      b.setAttribute('aria-pressed', i ? 'false' : 'true');
      b.addEventListener('click', function () { setView(name); });
      toggle.appendChild(b);
      views[name] = b;
    });

    var tools = document.createElement('div');
    tools.className = 'zh-etools';
    tools.appendChild(bar);
    tools.appendChild(toggle);
    list.parentNode.insertBefore(tools, list);

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

    /* calendar: a month grid, starting at the month of the next upcoming event */
    var cal = document.createElement('div');
    cal.className = 'zh-cal';
    cal.hidden = true;
    var calHead = document.createElement('div');
    calHead.className = 'zh-cal__head';
    var prev = document.createElement('button');
    prev.type = 'button';
    prev.className = 'zh-timeline__btn';
    prev.setAttribute('aria-label', 'Previous month');
    prev.innerHTML = '&larr;';
    var next = prev.cloneNode(true);
    next.setAttribute('aria-label', 'Next month');
    next.innerHTML = '&rarr;';
    var calTitle = document.createElement('h3');
    calTitle.className = 'zh-h3 zh-cal__title';
    calTitle.setAttribute('aria-live', 'polite');
    calHead.appendChild(prev);
    calHead.appendChild(calTitle);
    calHead.appendChild(next);
    var calTable = document.createElement('table');
    calTable.className = 'zh-cal__grid';
    cal.appendChild(calHead);
    cal.appendChild(calTable);

    list.parentNode.insertBefore(empty, list.nextSibling);
    list.parentNode.insertBefore(more, empty.nextSibling);
    list.parentNode.insertBefore(cal, more.nextSibling);

    var firstUpcoming = events.filter(function (ev) { return ev.date && ev.date >= today; })[0];
    var start = firstUpcoming ? firstUpcoming.date : today;
    var calMonth = new Date(start.getFullYear(), start.getMonth(), 1);
    var view = 'List';

    function matches(ev) {
      return active.every(function (f) {
        return !f.select.value || valuesOf(ev, f.key).indexOf(f.select.value) !== -1;
      });
    }

    function renderCalendar(shown) {
      calTitle.textContent = monthName(calMonth);
      var y = calMonth.getFullYear(), m = calMonth.getMonth();
      var days = new Date(y, m + 1, 0).getDate();
      var lead = new Date(y, m, 1).getDay();
      calTable.innerHTML = '<thead><tr>' + ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(function (d) {
        return '<th scope="col">' + d + '</th>';
      }).join('') + '</tr></thead><tbody></tbody>';
      var tbody = calTable.querySelector('tbody');
      var cells = [];
      for (var i = 0; i < lead; i++) cells.push(null);
      for (var d = 1; d <= days; d++) cells.push(d);
      while (cells.length % 7) cells.push(null);
      var tr;
      cells.forEach(function (day, idx) {
        if (idx % 7 === 0) { tr = document.createElement('tr'); tbody.appendChild(tr); }
        var td = document.createElement('td');
        if (day) {
          var num = document.createElement('span');
          num.className = 'zh-cal__day';
          num.textContent = day;
          td.appendChild(num);
          var date = new Date(y, m, day);
          if (date.getTime() === today.getTime()) td.className = 'is-today';
          shown.forEach(function (ev) {
            if (!ev.date || ev.date.getTime() !== date.getTime()) return;
            var a = document.createElement('a');
            a.className = 'zh-cal__ev';
            a.href = ev.link ? ev.link.getAttribute('href') : '#';
            if (ev.link && ev.link.hasAttribute('data-zh-pending')) a.setAttribute('data-zh-pending', ev.link.getAttribute('data-zh-pending'));
            a.textContent = ev.title;
            td.appendChild(a);
            td.classList.add('has-events');
          });
        } else {
          td.className = 'is-blank';
        }
        tr.appendChild(td);
      });
    }

    function apply() {
      var listed = 0;
      var shown = events.filter(matches);
      events.forEach(function (ev) {
        var ok = matches(ev) && upcoming(ev);
        if (ok) listed++;
        ev.row.hidden = !ok || listed > limit;
      });
      var isList = view === 'List';
      list.hidden = !isList;
      cal.hidden = isList;
      empty.hidden = !isList || listed > 0;
      more.hidden = !isList || listed <= limit;
      var n = isList ? listed : shown.length;
      count.textContent = n + (n === 1 ? ' event' : ' events');
      if (!isList) renderCalendar(shown);
    }

    function setView(name) {
      view = name;
      Object.keys(views).forEach(function (k) { views[k].setAttribute('aria-pressed', k === name ? 'true' : 'false'); });
      apply();
    }

    active.forEach(function (f) {
      f.select.addEventListener('change', function () {
        limit = PAGE;
        if (f.key === 'month' && f.select.value) {
          var hit = events.filter(function (ev) { return valuesOf(ev, 'month')[0] === f.select.value; })[0];
          if (hit) calMonth = new Date(hit.date.getFullYear(), hit.date.getMonth(), 1);
        }
        apply();
      });
    });
    moreBtn.addEventListener('click', function () { limit += PAGE; apply(); });
    prev.addEventListener('click', function () { calMonth = new Date(calMonth.getFullYear(), calMonth.getMonth() - 1, 1); apply(); });
    next.addEventListener('click', function () { calMonth = new Date(calMonth.getFullYear(), calMonth.getMonth() + 1, 1); apply(); });
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
