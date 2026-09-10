/* Aberdeen Piano — front-end interactions (extracted from the static design) */
(function () {
  var reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var loader = document.querySelector('.loader');
  var counter = document.querySelector('.loader-count');
  // Duration comes from the markup so the Journal can run shorter than home.
  var loadDuration = reduceMotion
    ? 700
    : parseInt((loader && loader.dataset.duration) || 3000, 10);

  if (loader && counter) {
    var count = 0;
    var countTimer = setInterval(function () {
      count = Math.min(99, count + Math.ceil(Math.random() * 6));
      counter.textContent = String(count).padStart(2, '0');
    }, 70);
    window.addEventListener('load', function () {
      setTimeout(function () {
        clearInterval(countTimer);
        counter.textContent = '100';
        setTimeout(function () {
          loader.classList.add('done');
          document.body.classList.remove('locked');
        }, 180);
      }, loadDuration);
    });
  } else {
    document.body.classList.remove('locked');
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('visible'); } });
  }, { threshold: 0.12 });

  /**
   * Observe .reveal elements inside a root. Exposed so content added later
   * (the Journal's AJAX grid) animates in exactly like server-rendered content.
   */
  function observeReveals(root) {
    (root || document).querySelectorAll('.reveal').forEach(function (el) {
      if (!el.classList.contains('visible')) { io.observe(el); }
    });
  }

  observeReveals(document);

  var menuButton = document.querySelector('.menu');
  var navLinks = document.querySelector('.nav-links');
  if (menuButton && navLinks) {
    /*
     * The header is absolute until 120px of scroll, then flips to fixed with
     * the navDrop animation. Scrolling with the panel open therefore either
     * dragged the menu off-screen or replayed that drop with the panel
     * attached, so the page is held still while the menu is open — the panel
     * scrolls internally through its own max-height.
     */
    function setMenu(open) {
      navLinks.classList.toggle('open', open);
      document.body.classList.toggle('menu-open', open);
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }

    setMenu(false);

    menuButton.addEventListener('click', function () {
      setMenu(!navLinks.classList.contains('open'));
    });

    navLinks.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () { setMenu(false); });
    });

    // Escape closes, as does a tap anywhere outside the panel.
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && navLinks.classList.contains('open')) {
        setMenu(false);
        menuButton.focus();
      }
    });

    document.addEventListener('click', function (e) {
      if (!navLinks.classList.contains('open')) { return; }
      if (navLinks.contains(e.target) || menuButton.contains(e.target)) { return; }
      setMenu(false);
    });

    // Never leave the body locked when the bar returns at desktop widths.
    // Matches the CSS breakpoint where .nav-links goes back to a row.
    addEventListener('resize', function () {
      if (innerWidth > 1100 && navLinks.classList.contains('open')) { setMenu(false); }
    });
  }

  /*
   * Calendar filters only. The Journal's filters are links to category
   * archives — filtering happens in the query so it works with pagination —
   * so they must not be hijacked here.
   */
  document.querySelectorAll('.schedule-tools .filter').forEach(function (button) {
    button.addEventListener('click', function () {
      document.querySelectorAll('.schedule-tools .filter').forEach(function (b) { b.classList.remove('active'); });
      button.classList.add('active');
      var kind = button.dataset.filter;
      document.querySelectorAll('.schedule-item').forEach(function (item) {
        item.classList.toggle('hidden', kind !== 'all' && item.dataset.kind !== kind);
      });
    });
  });

  /*
   * A generic version of the same idea for the interior pages, scoped so more
   * than one filter bar can live on a site. Markup:
   *
   *   <div data-filter-scope>
   *     <div class="filter-bar"><button class="filter" data-filter="all">…
   *     <div data-filter-group>          <- optional; hidden when it empties
   *       <article data-kind="recital">  <- the things being filtered
   */
  document.querySelectorAll('[data-filter-scope]').forEach(function (scope) {
    var buttons = [].slice.call(scope.querySelectorAll('.filter-bar .filter'));
    var items = [].slice.call(scope.querySelectorAll('[data-kind]'));
    var groups = [].slice.call(scope.querySelectorAll('[data-filter-group]'));

    if (!buttons.length || !items.length) { return; }

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        var kind = button.dataset.filter;

        buttons.forEach(function (b) {
          b.classList.toggle('active', b === button);
          b.setAttribute('aria-pressed', String(b === button));
        });

        items.forEach(function (item) {
          item.classList.toggle('hidden', kind !== 'all' && item.dataset.kind !== kind);
        });

        // A month or category heading with nothing left under it is noise.
        groups.forEach(function (group) {
          var visible = group.querySelectorAll('[data-kind]:not(.hidden)').length;
          group.classList.toggle('hidden', !visible);
        });
      });
    });
  });

  /*
   * Lightbox for [data-lightbox] galleries.
   *
   * Written here rather than pulled from a library: the whole behaviour is
   * about forty lines, and the markup degrades to plain links to the full-size
   * photograph when this never runs.
   */
  (function () {
    var galleries = [].slice.call(document.querySelectorAll('[data-lightbox]'));
    if (!galleries.length) { return; }

    var box = document.createElement('div');
    box.className = 'lightbox';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.setAttribute('aria-label', 'Photograph');
    box.hidden = true;
    box.innerHTML =
      '<button class="lightbox-close" type="button" aria-label="Close"></button>' +
      '<button class="lightbox-nav is-prev" type="button" aria-label="Previous"></button>' +
      '<button class="lightbox-nav is-next" type="button" aria-label="Next"></button>' +
      '<figure class="lightbox-figure">' +
        '<img alt="Photograph">' +
        '<figcaption><strong></strong><span></span><em></em></figcaption>' +
      '</figure>';
    document.body.appendChild(box);

    var image = box.querySelector('img');
    var title = box.querySelector('figcaption strong');
    var meta = box.querySelector('figcaption span');
    var count = box.querySelector('figcaption em');
    var links = [];
    var index = 0;
    var opener = null;

    function show(i) {
      // Only the tiles still visible after filtering are in the set, so the
      // arrows never step onto a hidden photograph.
      var total = links.length;
      index = (i + total) % total;

      var link = links[index];
      box.classList.add('is-loading');
      image.src = link.href;
      // The tile's alt describes the photograph; the caption is the fallback so
      // the enlarged image is never shown with an empty alt.
      var tileImg = link.querySelector('img');
      image.alt = (tileImg && tileImg.alt) || link.dataset.caption || 'Photograph';
      title.textContent = link.dataset.caption || '';
      meta.textContent = link.dataset.meta || '';
      count.textContent = (index + 1) + ' / ' + total;
      box.classList.toggle('is-single', total < 2);
    }

    image.addEventListener('load', function () { box.classList.remove('is-loading'); });

    function open(gallery, link) {
      links = [].slice.call(gallery.querySelectorAll('.gallery-link'))
        .filter(function (a) { return !a.closest('.hidden'); });

      opener = link;
      box.hidden = false;
      document.body.classList.add('lightbox-open');
      show(links.indexOf(link));
      box.querySelector('.lightbox-close').focus();
    }

    function close() {
      box.hidden = true;
      document.body.classList.remove('lightbox-open');
      image.removeAttribute('src');
      if (opener) { opener.focus(); }
    }

    galleries.forEach(function (gallery) {
      gallery.addEventListener('click', function (e) {
        var link = e.target.closest('.gallery-link');
        if (!link || !gallery.contains(link)) { return; }
        e.preventDefault();
        open(gallery, link);
      });
    });

    box.querySelector('.lightbox-close').addEventListener('click', close);
    box.querySelector('.is-prev').addEventListener('click', function () { show(index - 1); });
    box.querySelector('.is-next').addEventListener('click', function () { show(index + 1); });

    // A click on the backdrop — not on the photograph or a control — closes.
    box.addEventListener('click', function (e) {
      if (e.target === box || e.target.classList.contains('lightbox-figure')) { close(); }
    });

    document.addEventListener('keydown', function (e) {
      if (box.hidden) { return; }
      if (e.key === 'Escape') { close(); }
      if (e.key === 'ArrowLeft') { show(index - 1); }
      if (e.key === 'ArrowRight') { show(index + 1); }
    });
  })();

  /**
   * Bind the pointer-tilt effect to cards inside a root. Also exposed so the
   * Journal's AJAX grid gets the same behaviour as server-rendered cards.
   */
  function bindTilt(root) {
    if (reduceMotion) { return; }

    (root || document).querySelectorAll('.card,.price-card,.post-card').forEach(function (card) {
      if (card.dataset.tiltBound) { return; }
      card.dataset.tiltBound = '1';

      card.addEventListener('pointermove', function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5;
        var y = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = 'perspective(800px) rotateY(' + (x * 7) + 'deg) rotateX(' + (-y * 7) + 'deg) translateY(-8px)';
      });
      card.addEventListener('pointerleave', function () { card.style.transform = ''; });
    });
  }

  // Copy-link button in the article share row.
  var copyLink = document.querySelector("[data-copy-link]");

  if (copyLink && navigator.clipboard) {
    copyLink.addEventListener("click", function (e) {
      e.preventDefault();
      var original = copyLink.getAttribute("title");

      navigator.clipboard.writeText(copyLink.href).then(function () {
        copyLink.classList.add("copied");
        copyLink.setAttribute("title", copyLink.dataset.copiedTitle || "Link copied");
        setTimeout(function () {
          copyLink.classList.remove("copied");
          copyLink.setAttribute("title", original);
        }, 1800);
      });
    });
  }

  // Small public surface for scripts loaded alongside this one.
  window.aberdeenPiano = {
    observeReveals: observeReveals,
    bindTilt: bindTilt,
    reduceMotion: reduceMotion
  };

  if (reduceMotion) { return; }

  var hero = document.querySelector('.hero');
  var nav = document.querySelector('.nav');
  var scenes = [].slice.call(document.querySelectorAll('main > section:not(.hero)'));
  var ticking = false;

  var paintDepth = function () {
    var mid = innerHeight / 2;
    var maxScroll = document.documentElement.scrollHeight - innerHeight;
    var progress = maxScroll ? (scrollY / maxScroll) * 100 : 0;
    document.documentElement.style.setProperty('--progress', progress + '%');
    if (nav) { nav.classList.toggle('scrolled', scrollY > 120); }
    scenes.forEach(function (scene) {
      var r = scene.getBoundingClientRect();
      var distance = (r.top + r.height / 2 - mid) / innerHeight;
      var near = Math.max(0, 1 - Math.abs(distance));
      scene.style.setProperty('--zoom', (0.965 + near * 0.035).toFixed(3));
      scene.style.setProperty('--depth', (-Math.min(Math.abs(distance) * 25, 42)).toFixed(1) + 'px');
    });
    if (hero) { hero.style.setProperty('--hero-zoom', (1.05 + Math.min(scrollY / 2200, 0.12)).toFixed(3)); }
    ticking = false;
  };

  addEventListener('scroll', function () {
    if (!ticking) { requestAnimationFrame(paintDepth); ticking = true; }
  }, { passive: true });
  paintDepth();

  addEventListener('pointermove', function (e) {
    document.documentElement.style.setProperty('--x', e.clientX + 'px');
    document.documentElement.style.setProperty('--y', e.clientY + 'px');
    var heroContent = document.querySelector('.hero-content');
    if (heroContent && scrollY < innerHeight) {
      heroContent.style.transform = 'translate3d(' + ((e.clientX / innerWidth - 0.5) * -15) + 'px,' + ((e.clientY / innerHeight - 0.5) * -10) + 'px,0)';
    }
  }, { passive: true });

  bindTilt(document);
})();
