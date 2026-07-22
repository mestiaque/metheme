<style>
    /* ===== Global (page / content) scrollbar — liquid-glass, spider on the thumb =====
       (Firefox only understands scrollbar-width/scrollbar-color, not the
       ::-webkit-scrollbar-* rules below. adminlte.css sets its own
       `.sidebar-wrapper{scrollbar-color:...}` with a class selector, which beats
       a plain `html{...}` rule on specificity — that silently ate this in
       Firefox before, so it needs !important + its own `.sidebar-wrapper`
       override too.) */
    html {
        scrollbar-width: thin !important;
        scrollbar-color: #0f9bd6 rgba(255, 255, 255, 0.08) !important;
    }
    /* Firefox can't do gradients/blur/images in scrollbar-color (flat colours
       only), so the closest match to the sidebar's glass look is a translucent
       white thumb instead of solid blue — still visually distinct from the
       main content scrollbar above. */
    .sidebar-wrapper {
        scrollbar-width: thin !important;
        scrollbar-color: #673AB7 rgba(255, 255, 255, 0.06) !important;
    }
    ::-webkit-scrollbar {
        width: 14px !important;
        height: 14px !important;
    }
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05) !important;
    }
    ::-webkit-scrollbar-thumb {
        border-radius: 999px !important;
        border: 3px solid transparent !important;
        background-clip: padding-box !important;
        background-image: url('{{ asset('assets/img/scrollbar-spider.svg') }}'), linear-gradient(180deg, #0f9bd6, #9b51e0) !important;
        background-repeat: no-repeat, no-repeat !important;
        background-position: center, center !important;
        background-size: 65% auto, 100% 100% !important;
        transition: filter .2s ease;
    }
    ::-webkit-scrollbar-thumb:hover {
        filter: brightness(1.15);
    }
    ::-webkit-scrollbar-corner {
        background: transparent !important;
    }

    /* Native fallback for the sidebar specifically (mobile / before OverlayScrollbars
       JS takes over) — adminlte.css ships a more specific `.sidebar-wrapper::-webkit-*`
       rule that otherwise wins over the generic one above, so it needs its own
       override. Liquid-glass: translucent frosted pill, not a solid fill.
       Selector is written twice — once as-is, once prefixed with `html body` —
       to out-specificity anything else touching this element, since the plain
       class-only version wasn't visibly taking effect. */
    .sidebar-wrapper::-webkit-scrollbar,
    html body .app-sidebar .sidebar-wrapper::-webkit-scrollbar {
        width: 8px !important;
    }
    .sidebar-wrapper::-webkit-scrollbar-track,
    html body .app-sidebar .sidebar-wrapper::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.06) !important;
        border-radius: 999px !important;
    }
    .sidebar-wrapper::-webkit-scrollbar-thumb,
    html body .app-sidebar .sidebar-wrapper::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(6px) saturate(180%);
        -webkit-backdrop-filter: blur(6px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 999px !important;
        box-shadow: 0 0 8px rgba(15, 155, 214, 0.35);
    }
    .sidebar-wrapper::-webkit-scrollbar-thumb:hover,
    html body .app-sidebar .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
        background: rgba(52, 209, 255, 0.55) !important;
        box-shadow: 0 0 12px rgba(15, 155, 214, 0.6) !important;
    }

    /* Belt-and-braces: whatever inside .app-sidebar actually owns the scroll
       (in case it isn't exactly `.sidebar-wrapper` on the live page), catch it
       too. */
    html body .app-sidebar *::-webkit-scrollbar {
        width: 8px !important;
    }
    html body .app-sidebar *::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.06) !important;
        border-radius: 999px !important;
    }
    html body .app-sidebar *::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.35) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 999px !important;
        box-shadow: 0 0 8px rgba(15, 155, 214, 0.35);
    }
    html body .app-sidebar *::-webkit-scrollbar-thumb:hover {
        background: rgba(52, 209, 255, 0.55) !important;
        box-shadow: 0 0 12px rgba(15, 155, 214, 0.6) !important;
    }

    /* ===== Sidebar's real scrollbar on desktop — OverlayScrollbars (JS-rendered,
       replaces the native one entirely, so it needs its own theme instead of
       ::-webkit-scrollbar). Themed via the CSS custom properties the library
       exposes on its `os-theme-light` class, plus a direct override on the
       handle/track for backdrop-filter (custom properties alone can't carry
       that). Liquid-glass: frosted translucent pill, matches header/sidebar. ===== */
    .os-theme-light {
        --os-size: 8px !important;
        --os-padding-perpendicular: 1px !important;
        --os-padding-axis: 2px !important;
        --os-track-border-radius: 999px !important;
        --os-track-bg: rgba(255, 255, 255, 0.06) !important;
        --os-track-bg-hover: rgba(255, 255, 255, 0.06) !important;
        --os-track-bg-active: rgba(255, 255, 255, 0.06) !important;
        --os-handle-border-radius: 999px !important;
        --os-handle-bg: rgba(255, 255, 255, 0.35) !important;
        --os-handle-bg-hover: rgba(52, 209, 255, 0.55) !important;
        --os-handle-bg-active: rgba(52, 209, 255, 0.55) !important;
        --os-handle-border: 1px solid rgba(255, 255, 255, 0.5) !important;
    }
    .os-theme-light .os-scrollbar-handle {
        backdrop-filter: blur(6px) saturate(180%);
        -webkit-backdrop-filter: blur(6px) saturate(180%);
        box-shadow: 0 0 8px rgba(15, 155, 214, 0.35);
    }
</style>
