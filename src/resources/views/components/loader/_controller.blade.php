{{--
    Shared show/hide behaviour for every page loader (included at the end of each loader view).
    A loader only provides its look: one root element with data-me-loader, visible by default.

    - visible while the page loads, hidden as soon as it has loaded (7 s safety if load never fires)
    - shown again on same-tab navigation: link click, form submit, beforeunload
    - never stuck: back/forward cache, returning to the tab, and a 15 s limit when a
      navigation never leaves the page (e.g. a link that downloads a file)
    - skipped for: target="_blank" / download / #, javascript:, mailto:, tel: links,
      Ctrl/Shift/middle clicks, forms opening elsewhere, and .no-loader / .ajax-form

    Loaders that need their own work on show/hide (restart an animation, pause a canvas)
    use MELoader.onShow(fn) / MELoader.onHide(fn). Pages can call MELoader.show() / hide().
--}}
<style>
    [data-me-loader] {
        transition: opacity .25s ease, visibility 0s linear 0s;
    }
    [data-me-loader].me-loader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transition: opacity .25s ease, visibility 0s linear .25s;
    }
</style>

<script>
(function () {
    var overlay = document.querySelector('[data-me-loader]');
    if (!overlay || window.MELoader) return;

    var hidden = false;
    var showCallbacks = [];
    var hideCallbacks = [];
    var timer = setTimeout(hide, 7000);   // first load: never wait longer than this

    function hide() {
        if (hidden) return;
        hidden = true;
        clearTimeout(timer);
        overlay.classList.add('me-loader-hidden');
        hideCallbacks.forEach(function (fn) { fn(); });
    }

    function show() {
        if (!hidden) return;
        hidden = false;
        overlay.classList.remove('me-loader-hidden');
        showCallbacks.forEach(function (fn) { fn(); });
        // A navigation normally unloads this page long before this; if it didn't, nothing is coming
        clearTimeout(timer);
        timer = setTimeout(hide, 15000);
    }

    window.MELoader = {
        show: show,
        hide: hide,
        isVisible: function () { return !hidden; },
        onShow: function (fn) { showCallbacks.push(fn); },
        onHide: function (fn) { hideCallbacks.push(fn); }
    };

    if (document.readyState === 'complete') {
        // let the loader's own script register its callbacks first
        setTimeout(hide, 0);
    } else {
        window.addEventListener('load', hide, { once: true });
    }

    // Restored from the back/forward cache
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hide();
    });

    function isSameTabNavigableLink(link) {
        if (!link) return false;
        var targetAttr = link.getAttribute('target');
        if (targetAttr && targetAttr !== '_self') return false;
        if (link.hasAttribute('download')) return false;
        if (link.classList.contains('no-loader')) return false;
        var href = link.getAttribute('href');
        if (!href) return false;
        if (href.startsWith('#')) return false;
        if (href.startsWith('javascript:')) return false;
        if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;
        return true;
    }

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
        if (!isSameTabNavigableLink(e.target.closest('a'))) return;
        show();
    }, true);

    // A submit that opens in another tab/window (target / formtarget, e.g. Print, Excel)
    // never unloads this page, so showing the loader would leave it up for nothing
    function submitOpensElsewhere(form, submitter) {
        var target = (submitter && submitter.getAttribute('formtarget')) || form.getAttribute('target');
        return !!target && target !== '_self';
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.classList.contains('no-loader') || form.classList.contains('ajax-form')) return;
        if (submitOpensElsewhere(form, e.submitter)) return;
        show();
    });

    // Back on this tab while it's still here (a window opened from script, a download, ...)
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') hide();
    });

    window.addEventListener('beforeunload', show);
})();
</script>
