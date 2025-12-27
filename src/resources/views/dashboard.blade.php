@extends('me::master')

@section('title', __('Dashboard'))

@section('content')
<div style="min-height:80vh; background-color:#000; color:#00ff66; display:flex; align-items:center; justify-content:center; flex-direction:column; font-family: 'Fira Code', 'Courier New', monospace; padding: 20px;">

    <div style="max-width:800px; width:100%; text-align:left;">
        <p style="font-size:clamp(14px, 2vw, 20px); margin:0; color:#00cc55;">
            <span style="color:#888;">encodex@server</span>:<span style="color:#0ff;">~</span>$ echo "ENCODEX"
        </p>
        <h1 style="font-size:clamp(36px, 8vw, 58px); margin:10px 0 0; color:#00ff66; letter-spacing: 0.5rem; font-weight: bold; word-break: break-word;">
            ENCODEX<span class="cursor">█</span>
        </h1>
        <p style="font-size:clamp(14px, 2vw, 18px); margin-top:10px; color:#ccc;">
            <span style="color:#00cc55;">→</span> Software Solution
        </p>
        <p style="font-size:clamp(12px, 1.8vw, 18px); margin-top:10px; color:#cccccc73;">
            <span style="color:#ccc90088;">→</span> Developed by M. Estiaque Ahmed Khan
        </p>
    </div>

</div>
@endsection

@push('js')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const cursor = document.querySelector(".cursor");
    setInterval(() => {
        cursor.style.visibility = (cursor.style.visibility === "hidden") ? "visible" : "hidden";
    }, 600);
});
</script>
@endpush
