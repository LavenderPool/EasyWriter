(function () {
    'use strict';

    var data = window.READER_DATA;
    if (!data || !data.pages || !data.pages.length) {
        return;
    }

    var pages = data.pages;
    var total = data.totalPages;
    var storageKey = data.progressKey;
    var current = loadProgress();

    var pageImage = document.getElementById('pageImage');
    var pageLoader = document.getElementById('pageLoader');
    var pageStatus = document.getElementById('pageStatus');
    var progressText = document.getElementById('progressText');
    var pageSlider = document.getElementById('pageSlider');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var tapPrev = document.getElementById('tapPrev');
    var tapNext = document.getElementById('tapNext');
    var jumpBtn = document.getElementById('jumpBtn');
    var toggleUi = document.getElementById('toggleUi');
    var body = document.body;
    var stage = document.getElementById('readerStage');

    var preloadCache = {};
    var touchStartX = null;
    var touchStartY = null;
    var uiHideTimer = null;

    function clamp(n) {
        return Math.max(1, Math.min(total, n));
    }

    function loadProgress() {
        try {
            var raw = localStorage.getItem(storageKey);
            if (!raw) {
                return 1;
            }
            var parsed = JSON.parse(raw);
            if (parsed && parsed.page) {
                return clamp(parseInt(parsed.page, 10) || 1);
            }
        } catch (e) {}
        return 1;
    }

    function saveProgress(page) {
        try {
            localStorage.setItem(storageKey, JSON.stringify({
                page: page,
                updatedAt: Date.now(),
                mangaId: data.mangaId
            }));
        } catch (e) {}
    }

    function pageAt(index) {
        return pages[index - 1];
    }

    function preload(index) {
        var page = pageAt(index);
        if (!page || preloadCache[page.url]) {
            return;
        }
        var img = new Image();
        img.src = page.url;
        preloadCache[page.url] = img;
    }

    function updateChrome() {
        pageStatus.textContent = 'Page ' + current + ' / ' + total;
        progressText.textContent = current + ' / ' + total;
        pageSlider.value = String(current);
        prevBtn.disabled = current <= 1;
        nextBtn.disabled = current >= total;
        tapPrev.disabled = current <= 1;
        tapNext.disabled = current >= total;
        document.title = pageStatus.textContent + ' — ' + (document.title.split(' — ').slice(-1)[0] || 'Reader');
    }

    function showPage(index, direction) {
        current = clamp(index);
        var page = pageAt(current);
        if (!page) {
            return;
        }

        pageLoader.classList.add('show');
        pageImage.classList.add('is-loading');

        if (direction === 'next') {
            body.classList.remove('page-turn-prev');
            body.classList.add('page-turn-next');
        } else if (direction === 'prev') {
            body.classList.remove('page-turn-next');
            body.classList.add('page-turn-prev');
        }

        var img = new Image();
        img.onload = function () {
            pageImage.src = page.url;
            pageImage.alt = 'Page ' + page.number;
            pageImage.classList.remove('is-loading');
            pageLoader.classList.remove('show');
        };
        img.onerror = function () {
            pageLoader.textContent = 'Failed to load page';
            pageLoader.classList.add('show');
            pageImage.classList.remove('is-loading');
        };
        img.src = page.url;

        updateChrome();
        saveProgress(current);
        preload(current + 1);
        preload(current + 2);
        preload(current - 1);
        scheduleUiHide();
    }

    function next() {
        if (current < total) {
            showPage(current + 1, 'next');
        }
    }

    function prev() {
        if (current > 1) {
            showPage(current - 1, 'prev');
        }
    }

    function toggleChrome() {
        body.classList.toggle('ui-hidden');
    }

    function scheduleUiHide() {
        body.classList.remove('ui-hidden');
        clearTimeout(uiHideTimer);
        uiHideTimer = setTimeout(function () {
            body.classList.add('ui-hidden');
        }, 3500);
    }

    prevBtn.addEventListener('click', prev);
    nextBtn.addEventListener('click', next);
    tapPrev.addEventListener('click', prev);
    tapNext.addEventListener('click', next);
    toggleUi.addEventListener('click', toggleChrome);

    pageSlider.addEventListener('input', function () {
        showPage(parseInt(pageSlider.value, 10) || 1);
    });

    jumpBtn.addEventListener('click', function () {
        var value = window.prompt('Go to page (1-' + total + ')', String(current));
        if (value === null) {
            return;
        }
        var n = parseInt(value, 10);
        if (!isNaN(n)) {
            showPage(n);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight' || e.key === 'PageDown' || e.key === ' ') {
            e.preventDefault();
            next();
        } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
            e.preventDefault();
            prev();
        } else if (e.key === 'h' || e.key === 'H') {
            toggleChrome();
        } else if (e.key === 'Home') {
            showPage(1);
        } else if (e.key === 'End') {
            showPage(total);
        }
    });

    stage.addEventListener('touchstart', function (e) {
        if (!e.changedTouches || !e.changedTouches[0]) {
            return;
        }
        touchStartX = e.changedTouches[0].clientX;
        touchStartY = e.changedTouches[0].clientY;
    }, { passive: true });

    stage.addEventListener('touchend', function (e) {
        if (touchStartX === null || !e.changedTouches || !e.changedTouches[0]) {
            return;
        }
        var dx = e.changedTouches[0].clientX - touchStartX;
        var dy = e.changedTouches[0].clientY - touchStartY;
        touchStartX = null;
        touchStartY = null;

        if (Math.abs(dx) < 50 || Math.abs(dx) < Math.abs(dy)) {
            if (Math.abs(dx) < 10 && Math.abs(dy) < 10) {
                var mid = window.innerWidth / 2;
                var x = e.changedTouches[0].clientX;
                if (x > mid * 0.7 && x < mid * 1.3) {
                    toggleChrome();
                }
            }
            return;
        }

        if (dx < 0) {
            next();
        } else {
            prev();
        }
    }, { passive: true });

    pageImage.addEventListener('click', function (e) {
        var rect = pageImage.getBoundingClientRect();
        var x = e.clientX - rect.left;
        if (x < rect.width * 0.35) {
            prev();
        } else if (x > rect.width * 0.65) {
            next();
        } else {
            toggleChrome();
        }
    });

    ['mousemove', 'mousedown', 'touchstart'].forEach(function (evt) {
        document.addEventListener(evt, scheduleUiHide, { passive: true });
    });

    showPage(current);
})();
