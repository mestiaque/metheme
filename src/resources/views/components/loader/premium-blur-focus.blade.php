<!-- Premium Loader 5: Blur-to-Focus Reveal -->
<div id="pl-blur-focus" class="pl-blur-focus-overlay" data-me-loader aria-hidden="true">
    <div class="pl-blur-focus-spinner"></div>
</div>

<style>
    .pl-blur-focus-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(28px) saturate(140%);
        -webkit-backdrop-filter: blur(28px) saturate(140%);
    }
    @media (prefers-color-scheme: dark) {
        .pl-blur-focus-overlay { background: rgba(15, 17, 22, 0.35); }
    }
    /* This design's own slower reveal: the blur melts away (more specific than the shared .25s fade) */
    .pl-blur-focus-overlay[data-me-loader] {
        transition: opacity .6s cubic-bezier(0.4, 0, 0.2, 1),
                    backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    -webkit-backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0s linear 0s;
    }
    .pl-blur-focus-overlay[data-me-loader].me-loader-hidden {
        backdrop-filter: blur(0);
        -webkit-backdrop-filter: blur(0);
        transition: opacity .6s cubic-bezier(0.4, 0, 0.2, 1),
                    backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    -webkit-backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0s linear .6s;
    }
    .pl-blur-focus-spinner {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 3px solid rgba(15, 155, 214, 0.2);
        border-top-color: #0f9bd6;
        animation: pl-blur-focus-spin .8s linear infinite;
        transition: opacity .3s ease;
    }
    .me-loader-hidden .pl-blur-focus-spinner { opacity: 0; }
    @keyframes pl-blur-focus-spin {
        to { transform: rotate(360deg); }
    }
</style>

{{-- show/hide: shared with every loader (this one used to only cover the first load) --}}
@include('me::components.loader._controller')
