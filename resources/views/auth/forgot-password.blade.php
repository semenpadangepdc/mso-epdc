<x-guest-layout>

{{-- ============================================================
     FORGOT PASSWORD — MSO Visual Theme
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
        font-family: 'Barlow', sans-serif;
        background: var(--gray-900);
    }

    .auth-left {
        width: 45%;
        background: linear-gradient(160deg, var(--red-deeper) 0%, var(--red-dark) 40%, var(--red) 100%);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem;
        overflow: hidden;
        flex-shrink: 0;
    }

    .auth-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .auth-left::after {
        content: '';
        position: absolute;
        bottom: -120px; right: -120px;
        width: 400px; height: 400px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.08);
    }

    .deco-circle-inner {
        position: absolute;
        bottom: -60px; right: -60px;
        width: 260px; height: 260px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.12);
    }

    .brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 0.6rem 1.25rem;
        border-radius: 6px;
        backdrop-filter: blur(8px);
        position: relative;
        z-index: 1;
    }

    .brand-name {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--white);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .left-content {
        position: relative;
        z-index: 1;
    }

    .left-icon-big {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        display: block;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
    }

    .left-content h2 {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: clamp(2rem, 3.5vw, 3rem);
        font-weight: 900;
        color: var(--white);
        line-height: 1.05;
        margin: 0 0 1rem;
        text-transform: uppercase;
    }

    .left-content p {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.7;
        margin: 0;
        max-width: 300px;
    }

    .steps-list {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 0.813rem;
        font-weight: 800;
        color: var(--white);
        flex-shrink: 0;
    }

    .step-text {
        font-size: 0.813rem;
        color: rgba(255,255,255,0.75);
    }

    /* === RIGHT === */
    .auth-right {
        flex: 1;
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
    }

    .auth-right::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%);
    }

    .form-box { width: 100%; max-width: 400px; }

    .form-eyebrow {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 0.5rem;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .form-eyebrow::before {
        content: '';
        display: inline-block;
        width: 20px; height: 2px;
        background: var(--red);
    }

    .form-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 2.25rem;
        font-weight: 900;
        color: var(--gray-900);
        line-height: 1.1;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    .form-desc {
        font-size: 0.875rem;
        color: var(--gray-600);
        line-height: 1.6;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--off-white);
        border-left: 3px solid var(--red-light);
        border-radius: 0 6px 6px 0;
    }

    .session-status {
        background: #D1FAE5;
        border-left: 3px solid #10B981;
        color: #065F46;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.813rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .field-group { margin-bottom: 1.5rem; }

    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .field-input {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
        font-family: 'Barlow', sans-serif;
        font-size: 0.9rem;
        color: var(--gray-900);
        background: var(--off-white);
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--red);
        background: var(--white);
        box-shadow: 0 0 0 3px var(--red-glow);
    }

    .field-error { font-size: 0.75rem; color: var(--red); margin-top: 0.375rem; font-weight: 500; }

    .btn-submit {
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
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.35);
    }

    .back-link {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        margin-top: 1.25rem;
        font-size: 0.813rem;
        color: var(--gray-400);
        text-decoration: none;
        transition: color 0.2s;
        justify-content: center;
    }

    .back-link:hover { color: var(--red); }

    @media (max-width: 768px) {
        .auth-left { display: none; }
        .auth-right::before { display: none; }
        .auth-right { padding: 2rem 1.5rem; }
    }
</style>

<div class="auth-page">

    <div class="auth-left">
        <div class="deco-circle-inner"></div>

        <div class="brand-badge">
            <span style="font-size:1.25rem;">🔩</span>
            <span class="brand-name">MSO System</span>
        </div>

        <div class="left-content">
            <span class="left-icon-big">🔑</span>
            <h2>Reset<br>Password<br>Anda</h2>
            <p>Ikuti langkah berikut untuk mendapatkan kembali akses ke akun MSO Anda.</p>

            <div class="steps-list">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text">Masukkan alamat email yang terdaftar</div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text">Cek inbox email untuk link reset</div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text">Buat password baru dan login</div>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-box">

            <div class="form-eyebrow">Pemulihan Akun</div>
            <div class="form-title">Lupa<br>Password?</div>

            <div class="form-desc">
                Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link untuk membuat password baru.
            </div>

            @if (session('status'))
                <div class="session-status">✅ {{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <input id="email"
                           class="field-input"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autofocus
                           placeholder="nama@perusahaan.com">
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Kirim Link Reset →
                </button>

                <a href="{{ route('login') }}" class="back-link">
                    ← Kembali ke halaman login
                </a>
            </form>

        </div>
    </div>

</div>

</x-guest-layout>