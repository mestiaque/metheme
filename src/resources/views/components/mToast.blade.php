<!-- Toast Container -->
<canvas id="mToastCanvas" width="450" height="120"
    style="position: fixed; top: 20px; right: 20px; z-index: 10000; pointer-events: none; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));">
</canvas>

<script>
    /**
     * Global Toast Function
     * @param {string} message - টেক্সট মেসেজ
     * @param {string} type - 'success' অথবা 'error'
     */
    function mToast(message, type = 'success') {
        const canvas = document.getElementById('mToastCanvas');
        const ctx = canvas.getContext('2d');

        let opacity = 0;
        let xPos = 450; // ডান থেকে স্লাইড শুরু হবে
        let timer = 0;
        let isFadingOut = false;

        // কালার সেটিংস (২০২৬ ডিজাইন ট্রেন্ড অনুযায়ী)
        const theme = {
            success: { bg: 'rgba(33, 150, 83, 0.95)', icon: '✓' },
            error: { bg: 'rgba(235, 87, 87, 0.95)', icon: '✕' },
            info: { bg: 'rgba(47,128,237,0.95)', icon: 'ℹ' }
        };

        const activeTheme = theme[type] || theme.success;

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.globalAlpha = opacity;

            // ১. ড্র কার্ড (Rounded Box)
            const x = xPos - 400, y = 10, w = 380, h = 70, r = 12;

            ctx.fillStyle = activeTheme.bg;
            ctx.beginPath();
            ctx.moveTo(x + r, y);
            ctx.arcTo(x + w, y, x + w, y + h, r);
            ctx.arcTo(x + w, y + h, x, y + h, r);
            ctx.arcTo(x, y + h, x, y, r);
            ctx.arcTo(x, y, x + w, y, r);
            ctx.closePath();
            ctx.fill();

            // ২. ড্র আইকন সার্কেল
            ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
            ctx.beginPath();
            ctx.arc(x + 35, y + 35, 18, 0, Math.PI * 2);
            ctx.fill();

            // ৩. টেক্সট এবং আইকন
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 20px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(activeTheme.icon, x + 35, y + 42);

            ctx.textAlign = 'left';
            ctx.font = '500 15px "Segoe UI", Roboto, sans-serif';

            // বড় মেসেজ হলে র‍্যাপ করা (ঐচ্ছিক)
            let displayMsg = message.length > 45 ? message.substring(0, 42) + '...' : message;
            ctx.fillText(displayMsg, x + 65, y + 40);

            // ৪. এনিমেশন লজিক
            if (!isFadingOut) {
                if (opacity < 1) opacity += 0.1;
                if (xPos > 410) xPos -= 4;
            } else {
                opacity -= 0.05;
                xPos += 2;
            }

            if (opacity > 0) {
                requestAnimationFrame(draw);
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }

        draw();

        // ৫ সেকেন্ড পর অটো ক্লোজ
        setTimeout(() => {
            isFadingOut = true;
        }, 4000);
    }
</script>
