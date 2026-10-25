<!-- Premium Loader 4: Dot-Matrix / Equalizer Wave -->
<div id="pl-dot-wave" class="pl-dot-wave-overlay" data-me-loader aria-hidden="true">
    <div class="pl-dot-wave-wrap">
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
    </div>
</div>

<style>
    .pl-dot-wave-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(12px) saturate(160%);
        -webkit-backdrop-filter: blur(12px) saturate(160%);
    }
    @media (prefers-color-scheme: dark) {
        .pl-dot-wave-overlay { background: rgba(16, 18, 24, 0.88); }
    }
    .pl-dot-wave-wrap {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 40px;
    }
    .pl-dot-wave-bar {
        width: 6px;
        height: 12px;
        border-radius: 4px;
        background: linear-gradient(180deg, #4285f4, #9b51e0);
        animation: pl-dot-wave-anim 1s ease-in-out infinite;
    }
    .pl-dot-wave-bar:nth-child(1) { animation-delay: 0s; }
    .pl-dot-wave-bar:nth-child(2) { animation-delay: .12s; }
    .pl-dot-wave-bar:nth-child(3) { animation-delay: .24s; }
    .pl-dot-wave-bar:nth-child(4) { animation-delay: .36s; }
    .pl-dot-wave-bar:nth-child(5) { animation-delay: .48s; }
    @keyframes pl-dot-wave-anim {
        0%, 100% { height: 12px; opacity: .6; }
        50% { height: 40px; opacity: 1; }
    }
</style>

{{-- show/hide: shared with every loader --}}
@include('me::components.loader._controller')
