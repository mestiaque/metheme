@extends('me::master')

@section('title', __('Dashboard'))

@section('content')
<div style="min-height: 85vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at center, #0a110e 0%, #000 100%); overflow: hidden; position: relative; font-family: 'Fira Code', monospace; padding: 20px; margin-top:2rem">

    <!-- Animated Liquid Background Elements -->
    <div style="position: absolute; width: 300px; height: 300px; background: rgba(0, 255, 102, 0.15); filter: blur(100px); border-radius: 50%; top: 10%; left: 10%; animation: float 10s infinite alternate;"></div>
    <div style="position: absolute; width: 250px; height: 250px; background: rgba(0, 204, 255, 0.1); filter: blur(80px); border-radius: 50%; bottom: 10%; right: 10%; animation: float 8s infinite alternate-reverse;"></div>

    <!-- Liquid Glass Card -->
    <div style="
        position: relative;
        max-width: 800px;
        width: 100%;
        padding: 40px;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
        z-index: 1;
        overflow: hidden;
    ">
        <!-- Corner Glow -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(90deg, transparent, #00ff66, transparent);"></div>

        <div style="text-align: left;">
            <div style="margin-bottom: 20px; font-size: clamp(14px, 2vw, 18px); color: #00ff66; opacity: 0.8;">
                <span style="color: #888;">user@encodex:</span><span style="color: #0ff;">~</span>$ <span id="typed-text"></span><span class="cursor">█</span>
            </div>

            <h1 style="
                font-size: clamp(32px, 7vw, 54px);
                margin: 0;
                color: #fff;
                font-weight: 800;
                letter-spacing: -1px;
                text-shadow: 0 0 20px rgba(0, 255, 102, 0.4);
            ">
                M. ESTIAQUE <span class="hide-mobile">AHMED K</span><span style="color: #00ff66;">.</span>
            </h1>

            <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
                <span style="background: rgba(0, 255, 102, 0.1); border: 1px solid rgba(0, 255, 102, 0.2); padding: 5px 15px; border-radius: 50px; color: #00ff66; font-size: 14px;">
                    <i class="fas fa-code me-1"></i> Software Solution
                </span>
                <span style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 5px 15px; border-radius: 50px; color: #ccc; font-size: 14px;">
                    <i class="fas fa-terminal me-1"></i> Full Stack
                </span>
            </div>

            <div style="margin-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 20px;">
                <p style="font-size: 13px; color: rgba(255,255,255,0.4); margin: 0;">
                    <span style="color: #00ff66;">&lt;developer&gt;</span>
                    M. Estiaque Ahmed Khan
                    <span style="color: #00ff66;">&lt;/developer&gt;</span>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    #breadcrumb{display: none !important}
@keyframes float {
    0% { transform: translateY(0px) translateX(0px); }
    100% { transform: translateY(-20px) translateX(10px); }
}

.cursor {
    display: inline-block;
    color: #00ff66;
    margin-left: 5px;
}

#typed-text {
    color: #fff;
}
</style>
@endsection

@push('js')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Cursor Blink
    const cursor = document.querySelector(".cursor");
    setInterval(() => {
        cursor.style.opacity = (cursor.style.opacity === "0") ? "1" : "0";
    }, 500);

    // Auto Typing Effect
    const text = 'echo "M.ESTIAQUE AHMED KHAN"';
    const typedTextElement = document.getElementById("typed-text");
    let i = 0;

    function typeWriter() {
        if (i < text.length) {
            typedTextElement.innerHTML += text.charAt(i);
            i++;
            setTimeout(typeWriter, 100);
        }
    }

    setTimeout(typeWriter, 500);
});
</script>
@endpush
