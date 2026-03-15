/**
 * NeonMagic Theme — Frontend JavaScript
 *
 * @package NeonMagic
 */

(function () {
  'use strict';

  function qs(selector, context) {
    return (context || document).querySelector(selector);
  }
  function qsa(selector, context) {
    return (context || document).querySelectorAll(selector);
  }

  var menuBtn    = qs('#mobile-menu-btn');
  var closeBtn   = qs('#close-menu');
  var mobileMenu = qs('#mobile-menu');

  function openMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.remove('translate-x-full');
    mobileMenu.setAttribute('aria-hidden', 'false');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.add('translate-x-full');
    mobileMenu.setAttribute('aria-hidden', 'true');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (menuBtn)  menuBtn.addEventListener('click',  openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);

  // Close on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });

  // Close on backdrop click (outside the menu panel)
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function (e) {
      if (e.target === mobileMenu) closeMenu();
    });
  }

  var scrollBtn = qs('#scrolltop');
  if (scrollBtn) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 300) {
        scrollBtn.classList.remove('hidden');
        scrollBtn.classList.add('flex');
      } else {
        scrollBtn.classList.add('hidden');
        scrollBtn.classList.remove('flex');
      }
    }, { passive: true });

    scrollBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  var thumbStrip = qs('#thumb-strip');
  var mainImg    = qs('#main-preview-img');

  if (thumbStrip && mainImg) {
    thumbStrip.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-thumb]');
      if (!btn) return;

      var src = btn.dataset.src;
      if (!src) return;

      // Update main image
      mainImg.src = src;

      // Update active state on all thumbs
      qsa('[data-thumb]', thumbStrip).forEach(function (el) {
        el.classList.remove('border-4', 'border-pop-black', 'shadow-hard-sm');
        el.classList.add('border-2', 'border-gray-300');
      });
      btn.classList.remove('border-2', 'border-gray-300');
      btn.classList.add('border-4', 'border-pop-black', 'shadow-hard-sm');
    });
  }

  window.nmSwitchTab = function (id) {
    // Hide all panels
    qsa('.nm-tab-panel').forEach(function (panel) {
      panel.classList.add('hidden');
    });

    // Reset all tab buttons
    qsa('.nm-tab-btn').forEach(function (btn) {
      btn.classList.remove('bg-pop-black', 'text-white');
      btn.classList.add('bg-white');
      btn.setAttribute('aria-selected', 'false');
    });

    // Show target panel
    var panel = qs('#panel-' + id);
    if (panel) panel.classList.remove('hidden');

    // Activate tab button
    var activeBtn = qs('#tab-' + id);
    if (activeBtn) {
      activeBtn.classList.remove('bg-white');
      activeBtn.classList.add('bg-pop-black', 'text-white');
      activeBtn.setAttribute('aria-selected', 'true');
    }
  };

  var dlBtn = qs('#download-btn');
  if (dlBtn) {
    dlBtn.addEventListener('click', function () {
      var postId      = dlBtn.dataset.postId;
      var downloadUrl = dlBtn.dataset.downloadUrl;

      if (!postId || !NeonMagic) return;

      // Optimistic UI — show spinner
      dlBtn.disabled = true;
      dlBtn.innerHTML = '<i class="fa fa-spinner fa-spin text-4xl"></i>&nbsp;Preparing...';

      // AJAX: track download
      var fd = new FormData();
      fd.append('action',  'neonmagic_track_download');
      fd.append('nonce',   NeonMagic.nonce);
      fd.append('post_id', postId);

      fetch(NeonMagic.ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          dlBtn.innerHTML = '<i class="fa fa-check text-4xl"></i>&nbsp;Download Started!';
          dlBtn.classList.remove('bg-pop-cyan');
          dlBtn.classList.add('bg-pop-yellow');

          // Redirect to the file after a brief delay
          var url = (data.success && data.data.download_url) ? data.data.download_url : downloadUrl;
          if (url) {
            setTimeout(function () { window.location.href = url; }, 600);
          }

          // Reset button
          setTimeout(function () {
            dlBtn.innerHTML = '<i class="fa fa-download text-4xl"></i>&nbsp;Free Download';
            dlBtn.classList.add('bg-pop-cyan');
            dlBtn.classList.remove('bg-pop-yellow');
            dlBtn.disabled = false;
          }, 3500);
        })
        .catch(function () {
          // Fallback: direct redirect
          if (downloadUrl) window.location.href = downloadUrl;
          dlBtn.disabled = false;
          dlBtn.innerHTML = '<i class="fa fa-download text-4xl"></i>&nbsp;Free Download';
        });
    });
  }

  var shareBtn = qs('#share-btn');
  if (shareBtn) {
    shareBtn.addEventListener('click', function () {
      var url   = shareBtn.dataset.url || window.location.href;
      var title = shareBtn.dataset.title || document.title;

      if (navigator.share) {
        navigator.share({ title: title, url: url }).catch(function () {});
      } else {
        // Clipboard fallback
        navigator.clipboard.writeText(url).then(function () {
          var orig = shareBtn.innerHTML;
          shareBtn.innerHTML = '<i class="fa fa-check"></i>&nbsp;' + (NeonMagic.copySuccess || 'Copied!');
          setTimeout(function () { shareBtn.innerHTML = orig; }, 2000);
        }).catch(function () {
          window.prompt('Copy this link:', url);
        });
      }
    });
  }

  var saveBtn   = qs('#save-btn');
  var saveLabel = qs('#save-label');

  if (saveBtn && saveLabel) {
    var postId    = saveBtn.dataset.postId;
    var SAVED_KEY = 'nm_saved';

    function getSaved() {
      try { return JSON.parse(localStorage.getItem(SAVED_KEY)) || []; }
      catch (e) { return []; }
    }
    function setSaved(arr) {
      localStorage.setItem(SAVED_KEY, JSON.stringify(arr));
    }
    function updateSaveBtn(saved) {
      var isSaved = saved.indexOf(postId) !== -1;
      saveLabel.textContent = isSaved
        ? (NeonMagic.savedText || 'Saved!')
        : (NeonMagic.unsavedText || 'Save');
      saveBtn.classList.toggle('bg-pop-pink', isSaved);
    }

    // Init state
    updateSaveBtn(getSaved());

    saveBtn.addEventListener('click', function () {
      var saved  = getSaved();
      var idx    = saved.indexOf(postId);
      if (idx === -1) {
        saved.push(postId);
      } else {
        saved.splice(idx, 1);
      }
      setSaved(saved);
      updateSaveBtn(saved);
    });
  }

  function initStarWidget(container) {
    if (!container) return;
    var hiddenInput = qs('#nm_rating_value');
    var stars       = qsa('.nm-star-btn', container);
    var currentVal  = hiddenInput ? parseInt(hiddenInput.value, 10) : 5;

    function paintStars(hoverVal) {
      stars.forEach(function (star, i) {
        var val = parseInt(star.dataset.value, 10);
        star.classList.toggle('bg-pop-yellow', val <= hoverVal);
        star.classList.toggle('bg-white',      val > hoverVal);
        star.classList.toggle('border-pop-black', true);
      });
    }

    // Hover
    stars.forEach(function (star) {
      star.addEventListener('mouseenter', function () {
        paintStars(parseInt(star.dataset.value, 10));
      });
      star.addEventListener('mouseleave', function () {
        paintStars(currentVal);
      });
      // Click / select
      star.addEventListener('click', function () {
        currentVal = parseInt(star.dataset.value, 10);
        if (hiddenInput) hiddenInput.value = currentVal;
        paintStars(currentVal);
      });
    });

    // Initial paint
    paintStars(currentVal);
  }

  // The widget inside the reviews tab
  initStarWidget(qs('#nm-review-stars'));

  var loadMoreBtn = qs('#load-more-btn');
  var fileGrid    = qs('#file-grid');

  if (loadMoreBtn && fileGrid && typeof NeonMagic !== 'undefined') {
    loadMoreBtn.addEventListener('click', function () {
      var page    = parseInt(loadMoreBtn.dataset.page, 10);
      var maxPage = parseInt(loadMoreBtn.dataset.max, 10);
      var catId   = fileGrid.dataset.catId   || '';
      var tagSlug = fileGrid.dataset.tagSlug || '';
      var search  = fileGrid.dataset.search  || '';

      // Show loading state
      loadMoreBtn.textContent = NeonMagic.loadingText || 'Loading...';
      loadMoreBtn.disabled    = true;

      var fd = new FormData();
      fd.append('action',   'neonmagic_load_more');
      fd.append('nonce',    NeonMagic.nonce);
      fd.append('page',     page);
      fd.append('cat_id',   catId);
      fd.append('tag_slug', tagSlug);
      fd.append('search',   search);

      fetch(NeonMagic.ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success && data.data.html) {
            // Append new cards
            var tmp = document.createElement('div');
            tmp.innerHTML = data.data.html;
            while (tmp.firstChild) {
              fileGrid.appendChild(tmp.firstChild);
            }

            var nextPage = page + 1;
            if (nextPage > maxPage) {
              // All loaded — hide button
              var wrap = qs('#load-more-wrap');
              if (wrap) {
                loadMoreBtn.textContent = NeonMagic.noMoreText || 'All Files Loaded';
                loadMoreBtn.classList.remove('bg-pop-cyan');
                loadMoreBtn.classList.add('bg-gray-200');
                loadMoreBtn.disabled = true;
              }
            } else {
              loadMoreBtn.dataset.page = nextPage;
              loadMoreBtn.textContent  = NeonMagic.loadMoreText || 'Load More Files';
              loadMoreBtn.disabled     = false;
            }
          } else {
            // Nothing returned
            loadMoreBtn.textContent = NeonMagic.noMoreText || 'All Files Loaded';
            loadMoreBtn.disabled    = true;
          }
        })
        .catch(function () {
          loadMoreBtn.textContent = NeonMagic.loadMoreText || 'Load More Files';
          loadMoreBtn.disabled    = false;
        });
    });
  }

})();
