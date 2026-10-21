/* ============================================================
   .btn-savex - login (.btn-blank) er moto goo-bubble hover + click animation
   Blade e shudhu class="btn btn-savex" boshale-i hobe, baki shob ekhan theke
============================================================ */
(function () {
    // goo filter - bubble gulo ke torol er moto mishiye dey (css e filter: url('#savex-goo'))
    function injectFilter() {
        if (document.getElementById('savex-goo')) return;
        var wrap = document.createElement('div');
        wrap.innerHTML =
            '<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>' +
            '<filter id="savex-goo">' +
            '<feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"></feGaussianBlur>' +
            '<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7" result="goo"></feColorMatrix>' +
            '<feBlend in2="goo" in="SourceGraphic" result="mix"></feBlend>' +
            '</filter></defs></svg>';
        document.body.appendChild(wrap.firstChild);
    }

    // button er bhitore bubble markup (asol text/icon ke na chuye)
    function injectBlobs(btn) {
        if (btn.querySelector('.btn-savex__inner')) return;
        var inner = document.createElement('span');
        inner.className = 'btn-savex__inner';
        var blobs = document.createElement('span');
        blobs.className = 'btn-savex__blobs';
        for (var i = 0; i < 4; i++) {
            var blob = document.createElement('span');
            blob.className = 'btn-savex__blob';
            blobs.appendChild(blob);
        }
        inner.appendChild(blobs);
        btn.appendChild(inner);
    }

    function init() {
        injectFilter();
        document.querySelectorAll('.btn-savex').forEach(injectBlobs);
    }

    // modal/ajax e pore add howa button o jate bubble pay
    document.addEventListener('mouseover', function (e) {
        var btn = e.target.closest && e.target.closest('.btn-savex');
        if (btn) injectBlobs(btn);
    });

    // submit er age click animation (pill -> golla + spinner) dekhiye tarpor form pathano
    var ANIM_MS = 1100; // shrink (~0.65s) + cloud/tir ekbar dekha porjonto

    function isSavexSubmit(e) {
        return e.submitter && e.submitter.classList.contains('btn-savex') && !e.target.dataset.savexSubmitting;
    }

    // capture phase e (master er page loader er age) form e shamoyik 'no-loader' boshano,
    // jate animation er majhe loader na ashe. form.submit() er por beforeunload theke loader nijei ashbe
    window.addEventListener('submit', function (e) {
        if (!isSavexSubmit(e) || e.target.classList.contains('no-loader')) return;
        e.target.classList.add('no-loader');
        e.target.dataset.savexLoaderOff = '1';
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        var btn = e.submitter;
        if (!isSavexSubmit(e)) return;

        if (form.dataset.savexLoaderOff) {
            form.classList.remove('no-loader');
            delete form.dataset.savexLoaderOff;
        }

        // onno kono handler (ajax) age-i submit atkiye dile shudhu loading dekhabo, reload korbo na
        btn.style.width = btn.offsetWidth + 'px';
        void btn.offsetWidth; // width transition jate shuru hoy
        btn.classList.add('is-loading');
        if (e.defaultPrevented) return;

        e.preventDefault();
        form.dataset.savexSubmitting = '1';
        setTimeout(function () { form.submit(); }, ANIM_MS);
    });

    // back button e page cache theke ferot ele button abar shabhabik hobe
    window.addEventListener('pageshow', function () {
        document.querySelectorAll('.btn-savex.is-loading').forEach(function (btn) {
            btn.classList.remove('is-loading');
            btn.style.width = '';
            if (btn.form) delete btn.form.dataset.savexSubmitting;
        });
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
