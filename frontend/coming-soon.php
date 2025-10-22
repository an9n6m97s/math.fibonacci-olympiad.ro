<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
        background: linear-gradient(135deg,
                rgba(220, 38, 38, 0.9) 0%,
                rgba(153, 27, 27, 0.95) 25%,
                rgba(0, 0, 0, 0.98) 75%,
                rgba(0, 0, 0, 1) 100%), url('/assets/images/coming-soon/hero-area.webp') no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        overflow: hidden;
        position: relative;
    }

    /* Animated background particles */
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }

    .particle {
        position: absolute;
        background: rgba(220, 38, 38, 0.6);
        border-radius: 50%;
        animation: float 8s infinite ease-in-out;
    }

    .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 60%;
        left: 80%;
        animation-delay: 1s;
    }

    .particle:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 40%;
        left: 20%;
        animation-delay: 2s;
    }

    .particle:nth-child(4) {
        width: 5px;
        height: 5px;
        top: 80%;
        left: 60%;
        animation-delay: 3s;
    }

    .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 30%;
        left: 90%;
        animation-delay: 4s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) translateX(0px) scale(1);
            opacity: 0.7;
        }

        25% {
            transform: translateY(-20px) translateX(10px) scale(1.1);
            opacity: 1;
        }

        50% {
            transform: translateY(-10px) translateX(-5px) scale(0.9);
            opacity: 0.8;
        }

        75% {
            transform: translateY(-30px) translateX(15px) scale(1.05);
            opacity: 0.9;
        }
    }

    .main-container {
        position: relative;
        z-index: 10;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .glass-card {
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: 2rem;
        color: #fff;
        box-shadow:
            0 32px 64px rgba(0, 0, 0, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.05) inset,
            0 0 80px rgba(220, 38, 38, 0.3);
        max-width: 650px;
        width: 100%;
        padding: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: cardFloat 6s ease-in-out infinite;
    }

    @keyframes cardFloat {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(220, 38, 38, 0.1),
                transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }

        100% {
            left: 100%;
        }
    }

    .logo {
        width: 80px;
        height: 80px;
        margin-bottom: 1.5rem;
        filter: drop-shadow(0 8px 16px rgba(220, 38, 38, 0.5));
        animation: logoGlow 2s ease-in-out infinite alternate;
    }

    @keyframes logoGlow {
        from {
            filter: drop-shadow(0 8px 16px rgba(220, 38, 38, 0.5));
        }

        to {
            filter: drop-shadow(0 12px 24px rgba(220, 38, 38, 0.8));
        }
    }

    .main-title {
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .coming-soon-badge {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(220, 38, 38, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 8px 32px rgba(220, 38, 38, 0.4);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 12px 40px rgba(220, 38, 38, 0.6);
        }
    }

    .countdown {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .countdown-item {
        background: linear-gradient(135deg,
                rgba(220, 38, 38, 0.15) 0%,
                rgba(153, 27, 27, 0.2) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: 1.5rem;
        padding: 1.5rem 1rem;
        text-align: center;
        box-shadow:
            0 8px 32px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .countdown-item:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow:
            0 16px 40px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        border-color: rgba(220, 38, 38, 0.5);
    }

    .countdown-number {
        display: block;
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #ffffff 0%, #dc2626 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .countdown-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .description {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2.5rem;
        line-height: 1.6;
        font-weight: 400;
    }

    .gradient-btn {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
        border: none;
        border-radius: 50px;
        padding: 1rem 3rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 32px rgba(220, 38, 38, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .gradient-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent);
        transition: left 0.5s;
    }

    .gradient-btn:hover::before {
        left: 100%;
    }

    .gradient-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 48px rgba(220, 38, 38, 0.6);
        color: white;
        text-decoration: none;
    }

    .gradient-btn:active {
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .glass-card {
            padding: 2rem;
            margin: 1rem;
        }

        .countdown {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .countdown-item {
            padding: 1rem 0.5rem;
        }

        .countdown-number {
            font-size: 2rem;
        }

        .main-title {
            font-size: 1.6rem;
        }

        .gradient-btn {
            padding: 0.875rem 2rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .glass-card {
            padding: 1.5rem;
        }

        .logo {
            width: 60px;
            height: 60px;
        }

        .countdown-number {
            font-size: 1.75rem;
        }

        .countdown-label {
            font-size: 0.8rem;
        }

        .coming-soon-badge {
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>

<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="main-container">
    <div class="glass-card">
        <img src="/assets/images/logo/logo.webp" alt="Logo" class="logo">

        <h1 class="main-title">
            <?= $settings['competition_name'] ?><br>
            <span style="color: #dc2626; font-weight: 900;"><?= $settings['edition_year'] ?></span>
        </h1>

        <div class="coming-soon-badge">Coming Soon</div>

        <div class="countdown" id="countdown">
            <div class="countdown-item">
                <span class="countdown-number" id="days">--</span>
                <div class="countdown-label">Days</div>
            </div>
            <div class="countdown-item">
                <span class="countdown-number" id="hours">--</span>
                <div class="countdown-label">Hours</div>
            </div>
            <div class="countdown-item">
                <span class="countdown-number" id="minutes">--</span>
                <div class="countdown-label">Minutes</div>
            </div>
            <div class="countdown-item">
                <span class="countdown-number" id="seconds">--</span>
                <div class="countdown-label">Seconds</div>
            </div>
        </div>

        <p class="description">
            We're live!<br>
            We are preparing to launch an extraordinary experience. Stay close!
        </p>

        <a href="/ucp/login" id="loginBtn" class="gradient-btn">
            Login
        </a>
    </div>
</div>

<!-- Timer Script -->
<script>
    const countdown = () => {
        const endDate = new Date('2025-09-01T00:00:00').getTime();
        const now = new Date().getTime();
        const distance = endDate - now;

        if (distance <= 0) {
            document.getElementById("countdown").innerHTML = "<div class='text-light fs-4'>We're live!</div>";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").textContent = days;
        document.getElementById("hours").textContent = hours;
        document.getElementById("minutes").textContent = minutes;
        document.getElementById("seconds").textContent = seconds;
    };

    setInterval(countdown, 1000);
    countdown();
</script>

<script>
    $(document).ready(function() {
        $("#loginBtn").on("click", function() {

            let parolaIntrodusa = prompt("Te rog introdu parola de administrator:");

            const parolaCorecta = "RRC2026!@";

            if (parolaIntrodusa === parolaCorecta) {
                let expiryDate = new Date();
                expiryDate.setDate(expiryDate.getDate() + 7);
                document.cookie = "AdminLogin=true; expires=" + expiryDate.toUTCString() + "; path=/";

                alert("Autentificare reușită! Cookie-ul a fost setat.");
                window.location.href = "/";
            } else {
                alert("Parolă incorectă!");
            }
        });
    });
</script>