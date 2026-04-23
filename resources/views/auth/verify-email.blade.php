<x-guest-layout>

{{-- ============================================================
     VERIFY EMAIL — MSO Visual Theme
     ============================================================ --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap');

    body, html { margin:0; padding:0; }

    :root {
        --red:        #DC2626;
        --red-dark:   #991B1B;
        --red-deeper: #7F1D1D;
        --red-light:  #FEE2E2;
        --red-glow:   rgba(220,38,38,0.18);
        --white:      #FFFFFF;
        --off-white:  #F9FAFB;
        --gray-200:   #E5E7EB;
        --gray-400:   #9CA3AF;
        --gray-600:   #4B5563;
        --gray-900:   #111827;
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Barlow', sans-serif;
        background: var(--gray-900);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .auth-page::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(220,38,38,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(220,38,38,0.05) 1px, transparent 1px);
        background-size: 50px 50px;
    }

    .auth-page::after {
        content: '';
        position: absolute;
        bottom: -200px; left: -200px;
        width: 600px; height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(220,38,38,0.1) 0%, transparent 70%);
    }

    .verify-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .card-header {
        background: linear-gradient(135deg, var(--red-deeper) 0%, var(--red) 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    /* Animasi envelope */
    .envelope-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 0.75rem;
        z-index: 1;
    }

    .envelope-icon {
        font-size: 3.5rem;
        display: block;
        animation: float 3s ease-in-out infinite;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }

    .ping-dot {
        position: absolute;
        top: 0; right: -4px;
        width: 14px; height: 14px;
        background: #FCD34D;
        border-radius: 50%;
        border: 2px solid var(--red-deeper);
        animation: ping-anim 1.5s ease-in-out infinite;
    }

    @keyframes ping-anim {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.4); opacity: 0.6; }
    }

    .card-eyebrow {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .card-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--white);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        line-height: 1.1;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .card-body { padding: 2rem; }

    /* Status success */
    .status-sent {
        background: #D1FAE5;
        border-left: 4px solid #10B981;
        color: #065F46;
        padding: 0.875rem 1rem;
        border-radius: 0 8px 8px 0;
        font-size: 0.813rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .desc-text {
        font-size: 0.875rem;
        color: var(--gray-600);
        line-height: 1.7;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--off-white);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    /* Checklist steps */
    .email-steps {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .email-step {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: var(--off-white);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        font-size: 0.813rem;
        color: var(--gray-600);
        transition: border-color 0.2s;
    }

    .email-step:hover { border-color: var(--red-light); }

    .step-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Buttons */
    .btn-primary {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        color: var(--white);
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: block;
        text-align: center;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.35);
    }

    .btn-logout {
        width: 100%;
        padding: 0.75rem;
        background: transparent;
        color: var(--gray-400);
        font-family: 'Barlow', sans-serif;
        font-size: 0.813rem;
        font-weight: 600;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-top: 0.75rem;
        display: block;
        text-align: center;
        width: 100%;
    }

    .btn-logout:hover {
        border-color: var(--red-light);
        color: var(--red);
        background: var(--red-light);
    }

    .brand-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.75rem;
        color: var(--gray-400);
    }
</style>

<div class="auth-page">

    <div class="verify-card">

        <div class="card-header">
            <div class="envelope-wrap">
                <span class="envelope-icon">📧</span>
                <div class="ping-dot"></div>
            </div>
            <div class="card-eyebrow">Verifikasi Akun</div>
            <h1 class="card-title">Cek Email<br>Anda</h1>
        </div>

        <div class="card-body">

            @if (session('status') == 'verification-link-sent')
                <div class="status-sent">
                    ✅ Link verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif

            <div class="desc-text">
                Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda melalui link yang kami kirimkan saat registrasi.
            </div>

            <div class="email-steps">
                <div class="email-step">
                    <span class="step-icon">📬</span>
                    Buka inbox email Anda
                </div>
                <div class="email-step">
                    <span class="step-icon">🔗</span>
                    Klik link verifikasi di email
                </div>
                <div class="email-step">
                    <span class="step-icon">✅</span>
                    Akun aktif & siap digunakan
                </div>
            </div>

            {{-- Resend --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    📨 Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Keluar dari Akun
                </button>
            </form>

            <div class="brand-footer">
                🔩 MSO System &copy; {{ date('Y') }}
            </div>

        </div>
    </div>

</div>

</x-guest-layout>