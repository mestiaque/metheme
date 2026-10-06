{{--
    ===== ME File Drop — drag & drop for any <input type="file"> =====

    Auto mode (default): every input[type=file] on the page becomes a drop zone,
    including inputs added later via AJAX / modals (MutationObserver).

        <input type="file" name="photo" accept="image/*">

    Class mode: put data-file-drop="class" on <body> and only inputs with the
    `me-drop` class get a drop zone (the rest still get a preview).

        <body data-file-drop="class">
        <input type="file" class="me-drop" name="docs[]" multiple>

    Opt out a single input:      <input type="file" data-drop="false">  (or class="no-drop")
    Preview only (no drop zone, page's own button/UI kept): hidden inputs (hidden /
    d-none / style="display:none"), and in class mode every input without `me-drop`.
    Left alone entirely: inputs inside .mepy-dropzone or [data-drop-ignore] (own preview).
    Custom texts:                data-drop-text="Drop logo here"  data-drop-hint="PNG, max 2MB"
    Max size per file (KB):      data-drop-max="2048"
    Existing file preview:       data-drop-preview="{{ asset('storage/logo.png') }}"  (edit forms)
    Selected files show as preview cards (image/video thumbnail, icon otherwise);
    click a card to open a full preview (image, video, audio, PDF, text).

    Manual init (JS):            MeFileDrop.init(el)  /  MeFileDrop.scan(container)

    The original input stays in the DOM (visually hidden), so form submit,
    `required`, `.is-invalid ~ .invalid-feedback` and existing `change`
    listeners (image previews etc.) keep working.
--}}
<style>
    .me-drop-input {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        border: 0 !important;
        opacity: 0 !important;
    }
    .me-dropzone {
        --me-drop-accent: #0f9bd6;
        display: block;
        position: relative;
        width: 100%;
        padding: 18px 16px;
        border: 2px dashed rgba(15, 155, 214, 0.45);
        border-radius: 14px;
        background: rgba(15, 155, 214, 0.04);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        text-align: center;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease, transform .2s ease;
        margin-bottom: 0;
        font-weight: normal;
    }
    .me-dropzone:hover,
    .me-drop-input:focus-visible + .me-dropzone {
        border-color: var(--me-drop-accent);
        background: rgba(15, 155, 214, 0.08);
    }
    .me-dropzone.is-dragover {
        border-style: solid;
        border-color: var(--me-drop-accent);
        background: rgba(15, 155, 214, 0.14);
        box-shadow: 0 0 0 4px rgba(15, 155, 214, 0.18);
        transform: scale(1.01);
    }
    .me-drop-input.is-invalid + .me-dropzone,
    .me-dropzone.is-rejected {
        border-color: #e74c3c;
        background: rgba(231, 76, 60, 0.06);
    }
    .me-drop-input:disabled + .me-dropzone {
        opacity: .55;
        cursor: not-allowed;
        pointer-events: none;
    }
    .me-dropzone-icon {
        font-size: 1.8rem;
        color: var(--me-drop-accent);
        line-height: 1;
        margin-bottom: 6px;
        transition: transform .2s ease;
    }
    .me-dropzone.is-dragover .me-dropzone-icon { transform: translateY(-4px); }
    .me-dropzone-text { font-size: .9rem; font-weight: 600; }
    .me-dropzone-text u { color: var(--me-drop-accent); text-decoration: none; }
    .me-dropzone-hint { font-size: .75rem; opacity: .65; margin-top: 2px; }
    .me-dropzone-error { font-size: .75rem; color: #e74c3c; margin-top: 4px; }
    .me-dropzone-error:empty { display: none; }
    .me-dropzone-files {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-top: 10px;
    }
    .me-dropzone-files:empty { display: none; }
    .me-drop-file {
        position: relative;
        width: 130px;
        border-radius: 12px;
        background: rgba(15, 155, 214, 0.08);
        border: 1px solid rgba(15, 155, 214, 0.25);
        font-size: .75rem;
        text-align: left;
        overflow: hidden;
        cursor: default;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .me-drop-file:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 45, 74, 0.18);
    }
    .me-drop-file-thumb {
        position: relative;
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 155, 214, 0.12);
        color: var(--me-drop-accent);
        font-size: 2.2rem;
        cursor: zoom-in;
        overflow: hidden;
    }
    .me-drop-file-thumb img,
    .me-drop-file-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        pointer-events: none;
    }
    .me-drop-file-thumb .me-drop-play {
        position: absolute;
        font-size: 1.6rem;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, .6);
    }
    .me-drop-file-badge {
        position: absolute;
        left: 6px;
        bottom: 6px;
        padding: 1px 6px;
        border-radius: 6px;
        background: rgba(15, 45, 74, 0.75);
        color: #fff;
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .me-drop-file-meta { display: block; padding: 5px 8px 6px; line-height: 1.25; }
    .me-drop-file-name {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
    }
    .me-drop-file-size { opacity: .6; font-size: .68rem; }
    .me-drop-file-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 22px;
        height: 22px;
        border: 0;
        border-radius: 50%;
        background: rgba(231, 76, 60, 0.92);
        color: #fff;
        line-height: 22px;
        font-size: .95rem;
        padding: 0;
        cursor: pointer;
        z-index: 2;
    }
    .me-drop-file.is-current { border-style: dashed; }
    .me-drop-preview-only .me-dropzone-files { justify-content: flex-start; margin: 8px 0; }
    .me-drop-preview-only .me-dropzone-error { margin-top: 6px; }

    /* Full-size preview */
    .me-drop-lightbox {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 24px;
        background: rgba(0, 0, 0, 0);
        opacity: 0;
        transition: opacity .2s ease, background .2s ease;
    }
    .me-drop-lightbox.show { background: rgba(0, 0, 0, 0.85); opacity: 1; }
    .me-drop-lightbox-body > * {
        max-width: 90vw;
        max-height: 82vh;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    .me-drop-lightbox-body iframe { width: 90vw; height: 82vh; border: 0; background: #fff; }
    .me-drop-lightbox-body pre {
        width: min(900px, 90vw);
        overflow: auto;
        padding: 16px;
        margin: 0;
        background: #0f2d4a;
        color: #e8f4fb;
        font-size: .8rem;
        white-space: pre-wrap;
    }
    .me-drop-lightbox-body .me-drop-lightbox-icon { font-size: 5rem; color: #fff; box-shadow: none; }
    .me-drop-lightbox-caption { color: #fff; font-size: .85rem; opacity: .85; text-align: center; }
    .me-drop-lightbox-close {
        position: absolute;
        top: 14px;
        right: 18px;
        border: 0;
        background: transparent;
        color: #fff;
        font-size: 2rem;
        line-height: 1;
        cursor: pointer;
    }
</style>

<script>
(function (window, document) {
    'use strict';

    if (window.MeFileDrop) return;

    var DONE = 'meDropReady';
    var uid = 0;

    function classOnly() {
        return document.body && document.body.getAttribute('data-file-drop') === 'class';
    }

    // 'zone' = drop zone + preview, 'preview' = preview cards only, null = leave alone.
    function modeFor(input) {
        if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return null;
        if (input.dataset[DONE]) return null;
        if (input.dataset.drop === 'false' || input.classList.contains('no-drop')) return null;
        // Inside another dropzone that already shows its own preview.
        if (input.closest('.mepy-dropzone, [data-drop-ignore]')) return null;
        if (input.classList.contains('me-drop')) return 'zone';
        if (classOnly()) return 'preview';
        // Hidden inputs are opened from a page's own button/JS: keep that UI, add the preview.
        if (input.hidden || input.style.display === 'none' || input.classList.contains('d-none')) return 'preview';
        return 'zone';
    }

    // An input used as an invisible overlay inside a button/label trigger:
    // put the zone after that trigger, not inside it.
    function anchorFor(input) {
        var trigger = input.closest('button, .btn');
        if (!trigger) return input;
        // A lone button in a wrapper (e.g. d-flex centering div): go below the wrapper.
        var parent = trigger.parentElement;
        return parent && parent.children.length === 1 && parent !== document.body ? parent : trigger;
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function iconFor(file) {
        var t = file.type || '';
        if (t.indexOf('image/') === 0) return 'fa-file-image';
        if (t.indexOf('video/') === 0) return 'fa-file-video';
        if (t.indexOf('audio/') === 0) return 'fa-file-audio';
        if (t === 'application/pdf') return 'fa-file-pdf';
        if (/zip|rar|7z|tar|gzip/.test(t)) return 'fa-file-archive';
        if (/sheet|excel|csv/.test(t)) return 'fa-file-excel';
        if (/word|document/.test(t)) return 'fa-file-word';
        return 'fa-file';
    }

    // Same matching rules the browser uses for the `accept` attribute.
    function accepts(input, file) {
        var accept = (input.getAttribute('accept') || '').trim();
        if (!accept) return true;
        var name = file.name.toLowerCase();
        var type = (file.type || '').toLowerCase();
        return accept.split(',').some(function (rule) {
            rule = rule.trim().toLowerCase();
            if (!rule) return false;
            if (rule.charAt(0) === '.') return name.slice(-rule.length) === rule;
            if (rule.slice(-2) === '/*') return type.indexOf(rule.slice(0, -1)) === 0;
            return type === rule;
        });
    }

    function setFiles(input, files) {
        var dt = new DataTransfer();
        files.forEach(function (f) { dt.items.add(f); });
        input.files = dt.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    var EXT_TYPES = {
        jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png', gif: 'image/gif', webp: 'image/webp',
        svg: 'image/svg+xml', ico: 'image/x-icon', bmp: 'image/bmp', avif: 'image/avif',
        mp4: 'video/mp4', webm: 'video/webm', ogg: 'video/ogg', mov: 'video/quicktime',
        mp3: 'audio/mpeg', wav: 'audio/wav', m4a: 'audio/mp4',
        pdf: 'application/pdf', txt: 'text/plain', csv: 'text/csv', json: 'application/json'
    };

    function extOf(name) {
        var m = /\.([a-z0-9]+)(?:[?#].*)?$/i.exec(name || '');
        return m ? m[1].toLowerCase() : '';
    }

    // image | video | audio | pdf | text | other
    function kindOf(type, name) {
        type = type || EXT_TYPES[extOf(name)] || '';
        if (type.indexOf('image/') === 0) return 'image';
        if (type.indexOf('video/') === 0) return 'video';
        if (type.indexOf('audio/') === 0) return 'audio';
        if (type === 'application/pdf') return 'pdf';
        if (type.indexOf('text/') === 0 || type === 'application/json') return 'text';
        return 'other';
    }

    function openLightbox(entry) {
        var kind = kindOf(entry.type, entry.name);
        var box = document.createElement('div');
        box.className = 'me-drop-lightbox';
        box.innerHTML =
            '<button type="button" class="me-drop-lightbox-close" title="Close">&times;</button>' +
            '<div class="me-drop-lightbox-body"></div>' +
            '<div class="me-drop-lightbox-caption"></div>';
        var body = box.querySelector('.me-drop-lightbox-body');
        box.querySelector('.me-drop-lightbox-caption').textContent =
            entry.name + (entry.size != null ? ' · ' + formatSize(entry.size) : '');

        var el;
        if (kind === 'image') {
            el = document.createElement('img');
            el.src = entry.url;
        } else if (kind === 'video' || kind === 'audio') {
            el = document.createElement(kind);
            el.src = entry.url;
            el.controls = true;
            el.autoplay = true;
        } else if (kind === 'pdf') {
            el = document.createElement('iframe');
            el.src = entry.url;
        } else if (kind === 'text' && entry.file) {
            el = document.createElement('pre');
            el.textContent = 'Loading…';
            entry.file.slice(0, 200 * 1024).text().then(function (t) {
                el.textContent = t + (entry.file.size > 200 * 1024 ? '\n\n… (truncated)' : '');
            });
        } else {
            el = document.createElement('i');
            el.className = 'fas ' + iconFor({ type: entry.type || EXT_TYPES[extOf(entry.name)] }) + ' me-drop-lightbox-icon';
        }
        body.appendChild(el);

        function close() {
            box.classList.remove('show');
            document.removeEventListener('keydown', onKey);
            setTimeout(function () { box.remove(); }, 200);
        }
        function onKey(e) { if (e.key === 'Escape') close(); }
        box.addEventListener('click', function (e) {
            if (e.target === box || e.target.closest('.me-drop-lightbox-close')) close();
        });
        document.addEventListener('keydown', onKey);

        document.body.appendChild(box);
        requestAnimationFrame(function () { box.classList.add('show'); });
    }

    function buildThumb(entry) {
        var kind = kindOf(entry.type, entry.name);
        var thumb = document.createElement('div');
        thumb.className = 'me-drop-file-thumb';
        thumb.title = 'Preview';

        if (kind === 'image') {
            var img = document.createElement('img');
            img.src = entry.url;
            img.alt = entry.name;
            thumb.appendChild(img);
        } else if (kind === 'video') {
            var video = document.createElement('video');
            video.src = entry.url + '#t=0.5';
            video.muted = true;
            video.preload = 'metadata';
            thumb.appendChild(video);
            thumb.insertAdjacentHTML('beforeend', '<i class="fas fa-play-circle me-drop-play"></i>');
        } else {
            thumb.innerHTML = '<i class="fas ' + iconFor({ type: entry.type || EXT_TYPES[extOf(entry.name)] }) + '"></i>';
        }

        var ext = extOf(entry.name);
        if (ext) {
            var badge = document.createElement('span');
            badge.className = 'me-drop-file-badge';
            badge.textContent = entry.current ? 'current' : ext;
            thumb.appendChild(badge);
        }

        thumb.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openLightbox(entry);
        });
        return thumb;
    }

    // (Re)build the zone's contents. Also used to self-heal when another script
    // overwrites the input's next sibling (e.g. old `.custom-file-label` handlers).
    function fillZone(input, zone) {
        if (zone.classList.contains('me-drop-preview-only')) {
            zone.innerHTML = '<div class="me-dropzone-error"></div><div class="me-dropzone-files"></div>';
            return;
        }
        var accept = input.getAttribute('accept');
        var hint = input.dataset.dropHint
            || [accept ? accept.split(',').join(', ') : '', input.dataset.dropMax ? 'max ' + formatSize(input.dataset.dropMax * 1024) : '']
                .filter(Boolean).join(' · ');

        zone.innerHTML =
            '<div class="me-dropzone-icon"><i class="fas fa-cloud-upload-alt"></i></div>' +
            '<div class="me-dropzone-text"></div>' +
            (hint ? '<div class="me-dropzone-hint"></div>' : '') +
            '<div class="me-dropzone-error"></div>' +
            '<div class="me-dropzone-files"></div>';
        var text = zone.querySelector('.me-dropzone-text');
        if (input.dataset.dropText) {
            text.textContent = input.dataset.dropText;
        } else {
            text.innerHTML = 'Drag &amp; drop ' + (input.multiple ? 'files' : 'a file') + ' here or <u>browse</u>';
        }
        if (hint) zone.querySelector('.me-dropzone-hint').textContent = hint;
    }

    function render(input, zone) {
        if (!zone.querySelector('.me-dropzone-files')) fillZone(input, zone);
        var list = zone.querySelector('.me-dropzone-files');

        // Object URLs from the previous render are no longer shown anywhere.
        (zone._meUrls || []).forEach(function (u) { URL.revokeObjectURL(u); });
        zone._meUrls = [];
        list.innerHTML = '';

        var entries = Array.prototype.map.call(input.files || [], function (file, index) {
            var url = URL.createObjectURL(file);
            zone._meUrls.push(url);
            return { name: file.name, size: file.size, type: file.type, url: url, file: file, index: index };
        });

        // Existing file on edit forms: data-drop-preview="{{ asset('...') }}"
        if (!entries.length && input.dataset.dropPreview) {
            var src = input.dataset.dropPreview;
            entries.push({ name: decodeURIComponent(src.split(/[?#]/)[0].split('/').pop()) || 'current', size: null, type: '', url: src, current: true });
        }

        entries.forEach(function (entry) {
            var item = document.createElement('div');
            item.className = 'me-drop-file' + (entry.current ? ' is-current' : '');

            var meta = document.createElement('span');
            meta.className = 'me-drop-file-meta';
            var name = document.createElement('span');
            name.className = 'me-drop-file-name';
            name.textContent = entry.name;
            name.title = entry.name;
            var size = document.createElement('span');
            size.className = 'me-drop-file-size';
            size.textContent = entry.current ? 'Current file' : formatSize(entry.size);
            meta.appendChild(name);
            meta.appendChild(size);

            item.appendChild(buildThumb(entry));
            item.appendChild(meta);

            if (!entry.current) {
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'me-drop-file-remove';
                remove.title = 'Remove';
                remove.innerHTML = '&times;';
                remove.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var rest = Array.prototype.filter.call(input.files, function (_, i) { return i !== entry.index; });
                    setFiles(input, rest);
                });
                item.appendChild(remove);
            }

            item.addEventListener('click', function (e) { e.preventDefault(); });
            list.appendChild(item);
        });
    }

    function showError(zone, message) {
        var box = zone.querySelector('.me-dropzone-error');
        box.textContent = message || '';
        zone.classList.toggle('is-rejected', !!message);
    }

    function handleFiles(input, zone, incoming) {
        var maxKb = parseFloat(input.dataset.dropMax || '0');
        var rejected = [];
        var files = Array.prototype.filter.call(incoming, function (file) {
            if (!accepts(input, file)) { rejected.push(file.name + ' (type)'); return false; }
            if (maxKb && file.size > maxKb * 1024) { rejected.push(file.name + ' (> ' + formatSize(maxKb * 1024) + ')'); return false; }
            return true;
        });

        if (!input.multiple) files = files.slice(0, 1);

        showError(zone, rejected.length ? 'Not allowed: ' + rejected.join(', ') : '');
        if (files.length) setFiles(input, files);
    }

    function init(input) {
        var mode = modeFor(input);
        if (!mode) return;
        input.dataset[DONE] = '1';

        if (mode === 'preview') {
            var strip = document.createElement('div');
            strip.className = 'me-drop-preview-only';
            fillZone(input, strip);
            anchorFor(input).insertAdjacentElement('afterend', strip);
            watch(input, strip);
            return;
        }

        if (!input.id) input.id = 'me-drop-' + (++uid);
        input.classList.add('me-drop-input');

        // <label for> opens the native picker on click/keyboard with no extra JS.
        var zone = document.createElement('label');
        zone.className = 'me-dropzone';
        zone.setAttribute('for', input.id);
        fillZone(input, zone);

        anchorFor(input).insertAdjacentElement('afterend', zone);

        var depth = 0;
        zone.addEventListener('dragenter', function (e) {
            e.preventDefault();
            depth++;
            zone.classList.add('is-dragover');
        });
        zone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
        });
        zone.addEventListener('dragleave', function () {
            if (--depth <= 0) { depth = 0; zone.classList.remove('is-dragover'); }
        });
        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            depth = 0;
            zone.classList.remove('is-dragover');
            if (input.disabled) return;
            handleFiles(input, zone, e.dataTransfer.files);
        });

        watch(input, zone);
    }

    // Re-render the preview whenever the input's files change.
    function watch(input, zone) {
        input.addEventListener('change', function () {
            if (input.files && input.files.length) showError(zone, '');
            render(input, zone);
            // Page scripts listening on the same change may clobber the zone after us.
            setTimeout(function () {
                if (!zone.querySelector('.me-dropzone-files')) render(input, zone);
            }, 0);
        });

        if (input.form) {
            input.form.addEventListener('reset', function () {
                setTimeout(function () { showError(zone, ''); render(input, zone); }, 0);
            });
        }

        render(input, zone);
    }

    function scan(root) {
        root = root || document;
        if (root.matches && root.matches('input[type="file"]')) init(root);
        if (root.querySelectorAll) {
            Array.prototype.forEach.call(root.querySelectorAll('input[type="file"]'), init);
        }
    }

    // Dropping a file outside a zone would otherwise navigate the browser to it.
    ['dragover', 'drop'].forEach(function (evt) {
        window.addEventListener(evt, function (e) {
            if (!e.target.closest || !e.target.closest('.me-dropzone')) e.preventDefault();
        });
    });

    function start() {
        scan(document);
        new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                Array.prototype.forEach.call(m.addedNodes, function (node) {
                    if (node.nodeType === 1) scan(node);
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
    }

    window.MeFileDrop = { init: init, scan: scan };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})(window, document);
</script>
