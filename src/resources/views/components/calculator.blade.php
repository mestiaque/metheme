

<!-- কাস্টম স্টাইল (ক্যালকুলেটর পজিশন এবং ডিজাইনের জন্য) -->
<style>
    :root {
        --calc-glass-bg: rgba(15, 23, 42, 0.46);
        --calc-glass-border: rgba(255, 255, 255, 0.22);
        --calc-glass-highlight: rgba(255, 255, 255, 0.36);
        --calc-btn-dark: rgba(16, 24, 38, 0.44);
        --calc-btn-op: rgba(244, 170, 58, 0.55);
        --calc-btn-eq: rgba(22, 163, 74, 0.56);
    }

    /* গোল টগল বাটন */
    #calc-toggle-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1050;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 1px solid var(--calc-glass-border);
        background: linear-gradient(145deg, rgba(56, 189, 248, 0.55), rgba(14, 116, 144, 0.6));
        backdrop-filter: blur(10px) saturate(140%);
        -webkit-backdrop-filter: blur(10px) saturate(140%);
        box-shadow: 0 14px 35px rgba(2, 6, 23, 0.35), inset 0 1px 0 var(--calc-glass-highlight);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    #calc-toggle-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 18px 35px rgba(2, 6, 23, 0.46), inset 0 1px 0 rgba(255, 255, 255, 0.46);
    }

    /* ক্যালকুলেটর বক্স */
    #popup-calculator {
        display: none;
        position: fixed;
        bottom: 90px;
        right: 24px;
        z-index: 1050;
        width: min(320px, 92vw);
        background:
            radial-gradient(circle at 15% 15%, rgba(56, 189, 248, 0.24), transparent 55%),
            radial-gradient(circle at 85% 0%, rgba(244, 114, 182, 0.2), transparent 60%),
            var(--calc-glass-bg);
        border: 1px solid var(--calc-glass-border);
        border-radius: 18px;
        backdrop-filter: blur(20px) saturate(150%);
        -webkit-backdrop-filter: blur(20px) saturate(150%);
        box-shadow: 0 30px 60px rgba(2, 6, 23, 0.45), inset 0 1px 0 var(--calc-glass-highlight);
        overflow: hidden;
        animation: calc-float-in 260ms ease-out;
    }

    @keyframes calc-float-in {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ডিসপ্লে স্ক্রিন */
    .calc-screen {
        background: linear-gradient(180deg, rgba(2, 6, 23, 0.42), rgba(2, 6, 23, 0.18));
        border-top: 1px solid rgba(255, 255, 255, 0.13);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
        text-align: right;
        min-height: 80px;
    }
    #calc-history {
        font-size: 12px;
        color: #6c757d;
        height: 18px;
        overflow: hidden;
    }
    #calc-display {
        font-size: 28px;
        color: #e2f7ec;
        text-shadow: 0 0 15px rgba(34, 197, 94, 0.35);
        font-weight: 300;
        word-break: break-all;
    }
    /* বাটনের গ্রিড গ্রাউন্ড */
    .calc-buttons {
        padding: 10px;
    }
    .calc-buttons .btn {
        font-size: 18px;
        font-weight: 500;
        padding: 12px 0;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #e5edf7;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: transform 0.15s ease, filter 0.2s ease;
    }

    .calc-buttons .btn:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .calc-buttons .btn:active {
        transform: translateY(0);
        filter: brightness(0.95);
    }

    .calc-buttons .btn.btn-dark {
        background: var(--calc-btn-dark);
    }

    .calc-buttons .btn.btn-secondary {
        background: rgba(71, 85, 105, 0.44);
    }

    .calc-buttons .btn.btn-danger {
        background: rgba(239, 68, 68, 0.52);
    }

    .calc-buttons .btn.btn-warning {
        background: var(--calc-btn-op);
        color: #fffaf0;
    }

    .calc-buttons .btn.btn-success {
        background: var(--calc-btn-eq);
        color: #f0fff4;
    }

    #popup-calculator .bg-dark {
        background: rgba(2, 6, 23, 0.38) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    @media (max-width: 576px) {
        #calc-toggle-btn {
            bottom: 16px;
            right: 16px;
        }

        #popup-calculator {
            right: 16px;
            bottom: 80px;
        }
    }
</style>

<!-- ক্যালকুলেটর টগল বাটন -->
<button id="calc-toggle-btn" class="btn btn-primary d-flex align-items-center justify-content-center">
    <!-- SVG ক্যালকুলেটর আইকন -->
    <svg xmlns="http://w3.org" width="24" height="24" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
        <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
        <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm2 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-2-2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm6-2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
    </svg>
</button>

<!-- ক্যালকুলেটর ইন্টারফেস -->
<div id="popup-calculator" class="text-white">
    <!-- হেডার -->
    <div class="bg-dark px-3 py-2 d-flex justify-content-between align-items-center border-b border-secondary">
        <small class="fw-bold text-muted text-uppercase tracking-wider">ক্যালকুলেটর</small>
        <button id="calc-close-btn" class="btn-close btn-close-white btn-sm" aria-label="Close"></button>
    </div>

    <!-- ডিসপ্লে -->
    <div class="calc-screen font-monospace">
        <div id="calc-history"></div>
        <div id="calc-display">0</div>
    </div>

    <!-- বাটনের গ্রিড (Bootstrap Grid System) -->
    <div class="calc-buttons">
        <!-- Row 1 -->
        <div class="row g-1 mb-1">
            <div class="col-3"><button class="btn btn-danger w-100 calc-btn" data-val="C">C</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100 calc-btn" data-val="(">(</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100 calc-btn" data-val=")">)</button></div>
            <div class="col-3"><button class="btn btn-warning w-100 calc-btn fw-bold" data-val="/">/</button></div>
        </div>
        <!-- Row 2 -->
        <div class="row g-1 mb-1">
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="7">7</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="8">8</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="9">9</button></div>
            <div class="col-3"><button class="btn btn-warning w-100 calc-btn fw-bold" data-val="*">*</button></div>
        </div>
        <!-- Row 3 -->
        <div class="row g-1 mb-1">
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="4">4</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="5">5</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="6">6</button></div>
            <div class="col-3"><button class="btn btn-warning w-100 calc-btn fw-bold" data-val="-">-</button></div>
        </div>
        <!-- Row 4 -->
        <div class="row g-1 mb-1">
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="1">1</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="2">2</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val="3">3</button></div>
            <div class="col-3"><button class="btn btn-warning w-100 calc-btn fw-bold" data-val="+">+</button></div>
        </div>
        <!-- Row 5 -->
        <div class="row g-1">
            <div class="col-6"><button class="btn btn-dark w-100 calc-btn" data-val="0">0</button></div>
            <div class="col-3"><button class="btn btn-dark w-100 calc-btn" data-val=".">.</button></div>
            <div class="col-3"><button class="btn btn-success w-100 calc-btn fw-bold" data-val="=">=</button></div>
        </div>
    </div>
</div>

<!-- Vanilla JS logic (no jQuery dependency) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentInput = '';

        const toggleBtn = document.getElementById('calc-toggle-btn');
        const closeBtn = document.getElementById('calc-close-btn');
        const popup = document.getElementById('popup-calculator');
        const history = document.getElementById('calc-history');
        const display = document.getElementById('calc-display');
        const calcButtons = document.querySelectorAll('.calc-btn');

        function togglePopup() {
            popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
        }

        function closePopup() {
            popup.style.display = 'none';
        }

        toggleBtn.addEventListener('click', togglePopup);
        closeBtn.addEventListener('click', closePopup);

        calcButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const val = this.dataset.val;

                if (val === 'C') {
                    // ক্লিয়ার করা
                    currentInput = '';
                    history.textContent = '';
                    display.textContent = '0';
                } else if (val === '=') {
                    // হিসাব করা
                    if (currentInput) {
                        try {
                            // ব্র্যাকেটের ভেতরের এবং সাধারণ হিসাব BODMAS নিয়মে করার জন্য Function কনস্ট্রাক্টর
                            // এটি সিকিউরড এবং শুধুমাত্র ম্যাথ ক্যারেক্টার ও সংখ্যা রান করবে
                            const sanitizedInput = currentInput.replace(/[^0-9+\-*/().]/g, '');
                            const result = new Function('return ' + sanitizedInput)();

                            history.textContent = currentInput + ' =';

                            // ডেসিমেল সংখ্যার অতিরিক্ত শূন্য ফিক্স করার জন্য
                            currentInput = Number.isInteger(result) ? result.toString() : result.toFixed(4).replace(/\.?0+$/, '');
                            display.textContent = currentInput;
                        } catch (e) {
                            display.textContent = 'Error';
                            currentInput = '';
                        }
                    }
                } else {
                    // সংখ্যা ও সাইন ইনপুট নেওয়া
                    if (currentInput === '0' && !isNaN(val)) {
                        currentInput = val;
                    } else {
                        currentInput += val;
                    }
                    display.textContent = currentInput;
                }
            });
        });
    });
</script>
