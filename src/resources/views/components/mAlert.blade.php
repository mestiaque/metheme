<!-- Canvas container updated for responsiveness -->
<canvas id="mAlertCanvas"
    style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
        pointer-events: none;
        max-width: 95vw;
        display: none;
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3)); /* সুন্দর শ্যাডো */
    ">
</canvas>

<script>
function mAlert(message, type = 'success') {
    const canvas = document.getElementById('mAlertCanvas');
    if (!canvas) return;

    canvas.style.display = 'block';
    canvas.style.pointerEvents = 'auto';
    const ctx = canvas.getContext('2d');

    const screenWidth = window.innerWidth;
    const targetWidth = Math.min(400, screenWidth * 0.9);

    const theme = {
        success: { bg: '#219653', icon: '✓', label: 'Success' },
        error: { bg: '#EB5757', icon: '✕', label: 'Error' },
        info: { bg: '#2F80ED', icon: 'ℹ', label: 'Info' }
    };
    const t = theme[type] || theme.success;

    const padding = 25;
    const iconSize = 40;
    const fontSize = 16;
    const lineHeight = 22;
    const maxWidth = targetWidth - (padding * 2);

    ctx.font = `400 ${fontSize}px sans-serif`;
    const words = message.split(' ');
    let lines = [];
    let currentLine = '';

    for (let n = 0; n < words.length; n++) {
        let testLine = currentLine + words[n] + ' ';
        if (ctx.measureText(testLine).width > maxWidth && n > 0) {
            lines.push(currentLine.trim());
            currentLine = words[n] + ' ';
        } else {
            currentLine = testLine;
        }
    }
    lines.push(currentLine.trim());

    const textHeight = lines.length * lineHeight;
    const canvasHeight = padding + iconSize + 15 + textHeight + padding;

    canvas.width = targetWidth;
    canvas.height = canvasHeight;

    let opacity = 0;
    let scale = 0.9;
    let isClosing = false;
    let animationFrame;

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.save();
        ctx.globalAlpha = opacity;

        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.scale(scale, scale);
        ctx.translate(-canvas.width / 2, -canvas.height / 2);

        // কার্ড ব্যাকগ্রাউন্ড
        const r = 20;
        ctx.fillStyle = t.bg;
        ctx.beginPath();
        ctx.roundRect(0, 0, canvas.width, canvas.height, r);
        ctx.fill();

        // ১. আইকন সার্কেল ও সিম্বল
        const iconX = canvas.width / 2;
        const iconY = padding + (iconSize / 2);

        ctx.fillStyle = 'rgba(255,255,255,0.2)';
        ctx.beginPath();
        ctx.arc(iconX, iconY, iconSize / 2, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = '#fff';
        ctx.font = 'bold 22px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(t.icon, iconX, iconY);

        // ২. মেসেজ টেক্সট
        ctx.font = `400 ${fontSize}px sans-serif`;
        let startY = iconY + (iconSize / 2) + 15 + (lineHeight / 2);

        lines.forEach((line, index) => {
            ctx.fillText(line, canvas.width / 2, startY + (index * lineHeight));
        });

        // ৩. ক্লোজ বাটন (ডান কোণায়)
        ctx.fillStyle = 'rgba(255,255,255,0.7)';
        ctx.font = 'bold 18px Arial';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'top';
        // পজিশন সেট করা (ডান থেকে ১৫পিএক্স এবং উপর থেকে ১৫পিএক্স দূরে)
        ctx.fillText('✕', canvas.width - 15, 15);


        ctx.restore();

        // এনিমেশন লজিক
        if (!isClosing) {
            opacity = Math.min(opacity + 0.1, 1);
            scale = Math.min(scale + 0.02, 1);
        } else {
            opacity -= 0.1;
            scale -= 0.02;
        }

        if (opacity > 0) {
            animationFrame = requestAnimationFrame(draw);
        } else {
            cancelAnimationFrame(animationFrame);
            canvas.style.display = 'none';
            canvas.style.pointerEvents = 'none';
        }
    }

    // ক্লোজ ইভেন্ট হ্যান্ডলিং
    canvas.onclick = (e) => {
        isClosing = true;
    };


    canvas.onmousemove = (e) => {
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // X বাটনের এরিয়া চেক করা (ডান কোণায় ৩০x৩০ পিক্সেল এরিয়া)
        const closeBtnArea = 40;
        if (x > canvas.width - closeBtnArea && y < closeBtnArea) {
            canvas.style.cursor = 'pointer';
        } else {
            canvas.style.cursor = 'default';
        }
    };

    draw();

    // ৫ সেকেন্ড পর অটো বন্ধ হবে
    setTimeout(() => { isClosing = true; }, 3000);
}

// টেস্ট করার জন্য নিচের লাইনটি আনকমেন্ট করুন
// mAlert("আপনার পেমেন্ট সফল হয়েছে। এটি একটি লম্বা মেসেজ যা একাধিক লাইনে সুন্দরভাবে দেখানো হবে এবং উপরে-নিচে সমান প্যাডিং থাকবে।", "success");
</script>
