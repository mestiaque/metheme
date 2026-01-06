<div id="mAlertBlur"
    style="
        position: fixed;
        z-index: 9999;
        display: none;
        backdrop-filter: blur(16px) saturate(160%);
        -webkit-backdrop-filter: blur(16px) saturate(160%);
        background: rgba(255,255,255,0.04);
        border-radius: 28px;
        pointer-events: none;
    ">
</div>
<canvas id="mAlertCanvas"
    style="
        position: fixed;
        z-index: 10000;
        display: none;
        pointer-events: auto;
        filter: drop-shadow(0 30px 60px rgba(0,0,0,.35));
    ">
</canvas>

<script>
function mAlert(message, type = 'success') {

    const canvas = document.getElementById('mAlertCanvas');
    const blur   = document.getElementById('mAlertBlur');
    const ctx    = canvas.getContext('2d');

    const theme = {
        success: 'rgba(52,199,89,0.35)',
        error:   'rgba(255,69,58,0.35)',
        info:    'rgba(0,122,255,0.35)'
    };

    const padding = 26;
    const iconSize = 42;
    const fontSize = 16;
    const lineHeight = 22;
    const width = Math.min(400, window.innerWidth * 0.9);

    ctx.font = `${fontSize}px system-ui`;

    // text wrap
    const words = message.split(' ');
    let lines = [], line = '';
    words.forEach(w => {
        const test = line + w + ' ';
        if (ctx.measureText(test).width > width - padding * 2) {
            lines.push(line.trim());
            line = w + ' ';
        } else line = test;
    });
    lines.push(line.trim());

    const height = padding*2 + iconSize + lines.length*lineHeight + 20;

    canvas.width = width;
    canvas.height = height;

    // 🎯 CENTER POSITION
    const cx = window.innerWidth / 2;
    const cy = window.innerHeight / 2;

    canvas.style.left = (cx - width/2) + 'px';
    canvas.style.top  = (cy - height/2) + 'px';

    // 🔥 BLUR AREA = canvas + 10%
    const blurPad = 0.01;
    const bw = width  * (1 + blurPad);
    const bh = height * (1 + blurPad);

    blur.style.width  = bw + 'px';
    blur.style.height = bh + 'px';
    blur.style.left   = (cx - bw/2) + 'px';
    blur.style.top    = (cy - bh/2) + 'px';

    blur.style.display   = 'block';
    canvas.style.display = 'block';

    let opacity = 0, scale = 0.92, closing = false;

    function draw() {
        ctx.clearRect(0,0,width,height);
        ctx.save();

        ctx.globalAlpha = opacity;
        ctx.translate(width/2,height/2);
        ctx.scale(scale,scale);
        ctx.translate(-width/2,-height/2);

        // glass card
        ctx.fillStyle = theme[type] || theme.success;
        ctx.beginPath();
        ctx.roundRect(0,0,width,height,22);
        ctx.fill();

        // highlight
        const g = ctx.createLinearGradient(0,0,width,0);
        g.addColorStop(0,'rgba(255,255,255,.25)');
        g.addColorStop(1,'rgba(255,255,255,.05)');
        ctx.fillStyle = g;
        ctx.fill();

        // icon
        ctx.fillStyle = 'rgba(255,255,255,.35)';
        ctx.beginPath();
        ctx.arc(width/2,padding+iconSize/2,iconSize/2,0,Math.PI*2);
        ctx.fill();

        ctx.fillStyle = '#fff';
        ctx.font = 'bold 22px system-ui';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(type==='error'?'✕':type==='info'?'ℹ':'✓',
            width/2, padding+iconSize/2);

        // text
        ctx.font = `${fontSize}px system-ui`;
        let y = padding + iconSize + 18;
        lines.forEach(l=>{
            ctx.fillText(l,width/2,y);
            y+=lineHeight;
        });

        ctx.restore();

        if (!closing) {
            opacity = Math.min(opacity+.1,1);
            scale = Math.min(scale+.02,1);
        } else {
            opacity -= .1;
            scale -= .02;
        }

        if (opacity>0) requestAnimationFrame(draw);
        else {
            canvas.style.display='none';
            blur.style.display='none';
        }
    }

    canvas.onclick = ()=> closing=true;
    setTimeout(()=> closing=true,3000);
    draw();
}
</script>


