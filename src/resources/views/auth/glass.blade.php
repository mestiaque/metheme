@push('css')
<style>
.liquid-bg {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at center, #0a110e 0%, #000 100%);
    overflow: hidden;
    position: relative;
}
.liquid-bg::before,
.liquid-bg::after {
    content: "";
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(120px);
    animation: float 10s infinite alternate;
}

.liquid-bg::before {
    background: rgba(0, 255, 102, 0.18);
    top: 10%;
    left: 10%;
}

.liquid-bg::after {
    background: rgba(0, 204, 255, 0.12);
    bottom: 10%;
    right: 10%;
    animation-duration: 8s;
    animation-direction: alternate-reverse;
}

@keyframes float {
    from { transform: translate(0, 0); }
    to { transform: translate(20px, -30px); }
}



.glass-card {
    position: relative;
    width: 100%;
    max-width: 900px;
    padding: 40px;
    border-radius: 24px;

    background: linear-gradient(
        135deg,
        rgba(255,255,255,0.06),
        rgba(255,255,255,0.01)
    );

    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);

    border: 1px solid rgba(255,255,255,0.12);

    box-shadow:
        0 8px 32px rgba(0,0,0,0.8),
        inset 0 0 0.5px rgba(255,255,255,0.4);

    overflow: hidden;
    z-index: 1;
}


.glass-card::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(
        90deg,
        transparent,
        #00ff66,
        transparent
    );
    box-shadow: 0 0 12px #00ff66;
}

.glass-form-control {
    background: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    backdrop-filter: blur(6px);
}
</style>
@endpush
