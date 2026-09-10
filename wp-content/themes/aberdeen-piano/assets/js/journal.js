/**
 * Aberdeen Piano — AJAX filtering and pagination for the Journal.
 *
 * Progressive enhancement. Every filter pill and page link is a real URL that
 * works on its own; this script intercepts the click, swaps the grid in place
 * and rewrites the address bar to that same URL. Back/forward buttons work,
 * links stay shareable, and with JavaScript off nothing changes.
 */
(function () {
  'use strict';

  var config = window.aberdeenPianoJournal;
  var api = window.aberdeenPiano || {};

  if (!config || !window.fetch || !window.history || !window.history.pushState) {
    return;
  }

  var container = document.querySelector('[data-journal-posts]');
  var filterBar = document.querySelector('[data-journal-filters]');
  var section = document.getElementById('articles');

  if (!container) {
    return;
  }

  var searchTerm = new URLSearchParams(location.search).get('s') || '';
  var request = 0;
  var busy = false;

  /** The filter pill currently marked active. */
  function activeSlug() {
    var current = filterBar && filterBar.querySelector('.filter.active');
    return current ? current.dataset.filter : 'all';
  }

  /** Move the .active state (and aria-current) onto one pill. */
  function setActive(slug) {
    if (!filterBar) { return; }

    filterBar.querySelectorAll('.filter').forEach(function (pill) {
      var on = pill.dataset.filter === slug;
      pill.classList.toggle('active', on);

      if (on) {
        pill.setAttribute('aria-current', 'page');
      } else {
        pill.removeAttribute('aria-current');
      }
    });
  }

  /**
   * Reveal freshly inserted cards.
   *
   * .reveal starts at opacity 0 and is normally released by the scroll
   * observer. Content the visitor just asked for must not wait for a scroll
   * that may never happen, so it is revealed straight away with a short
   * stagger — the same animation, triggered by the click instead.
   */
  function revealIn(root) {
    var items = root.querySelectorAll('.reveal');

    items.forEach(function (el, i) {
      if (api.reduceMotion) {
        el.classList.add('visible');
        return;
      }

      // A timer rather than requestAnimationFrame: rAF is throttled or skipped
      // in background tabs and headless renderers, which would leave the cards
      // invisible. The small delay lets the start state paint so the CSS
      // transition still runs, and staggers the cards in.
      setTimeout(function () {
        el.classList.add('visible');
      }, 30 + Math.min(i * 70, 420));
    });
  }

  /** Scroll the grid back into view, but only when it has been scrolled past. */
  function scrollIntoView() {
    if (!section) { return; }

    var top = section.getBoundingClientRect().top;

    if (top < -40) {
      window.scrollTo({
        top: window.scrollY + top - 20,
        behavior: api.reduceMotion ? 'auto' : 'smooth'
      });
    }
  }

  /**
   * Load a view and swap it in.
   *
   * @param {string} slug   Category slug, or "all".
   * @param {number} paged  Page number.
   * @param {boolean} push  Whether to add a history entry.
   * @param {string} fallback URL to navigate to if the request fails.
   */
  function load(slug, paged, push, fallback) {
    var token = ++request;

    busy = true;
    container.setAttribute('aria-busy', 'true');
    container.classList.add('is-loading');

    var body = new URLSearchParams();
    body.set('action', config.action);
    body.set('nonce', config.nonce);
    body.set('category', slug);
    body.set('paged', paged);
    body.set('search', searchTerm);

    fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: body.toString()
    })
      .then(function (response) {
        if (!response.ok) { throw new Error('HTTP ' + response.status); }
        return response.json();
      })
      .then(function (payload) {
        // A newer click has already been fired — discard this stale response.
        if (token !== request) { return; }
        if (!payload || !payload.success) { throw new Error('Bad payload'); }

        container.innerHTML = payload.data.html;
        setActive(payload.data.category);

        if (push) {
          history.pushState(
            { journal: true, category: payload.data.category, paged: payload.data.paged },
            '',
            payload.data.url
          );
        }

        revealIn(container);
        if (api.bindTilt) { api.bindTilt(container); }

        scrollIntoView();
      })
      .catch(function () {
        if (token !== request) { return; }
        // Fall back to a normal page load rather than leaving a broken grid.
        window.location.href = fallback;
      })
      .then(function () {
        if (token !== request) { return; }
        busy = false;
        container.setAttribute('aria-busy', 'false');
        container.classList.remove('is-loading');
      });
  }

  /** Page number out of a /page/N/ or ?paged=N URL. */
  function pageFromUrl(url) {
    var path = url.match(/\/page\/(\d+)/);
    if (path) { return parseInt(path[1], 10); }

    var query = url.match(/[?&]paged=(\d+)/);
    return query ? parseInt(query[1], 10) : 1;
  }

  // Ignore clicks the user means to handle themselves (new tab, etc.).
  function isPlainClick(event) {
    return !event.defaultPrevented && event.button === 0 &&
      !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;
  }

  document.addEventListener('click', function (event) {
    var pill = event.target.closest('[data-journal-filters] .filter');
    var page = event.target.closest('[data-journal-posts] a.page-numbers');

    if (!pill && !page) { return; }
    if (!isPlainClick(event)) { return; }

    event.preventDefault();

    if (busy) { return; }

    if (pill) {
      if (pill.classList.contains('active')) { return; }
      load(pill.dataset.filter, 1, true, pill.href);
      return;
    }

    load(activeSlug(), pageFromUrl(page.getAttribute('href')), true, page.href);
  });

  // Back / forward.
  window.addEventListener('popstate', function (event) {
    var state = event.state;

    if (!state || !state.journal) {
      // Not one of ours — let the browser do a real navigation.
      window.location.reload();
      return;
    }

    load(state.category, state.paged, false, location.href);
  });

  // Seed history so the first Back returns to the page as first rendered.
  history.replaceState(
    { journal: true, category: activeSlug(), paged: pageFromUrl(location.href) },
    '',
    location.href
  );
})();
