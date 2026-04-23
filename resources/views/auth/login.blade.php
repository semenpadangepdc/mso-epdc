<x-guest-layout>

{{-- ============================================================
     LOGIN PAGE — MSO Visual Theme (Enhanced)
     ============================================================ --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    :root {
        --red: #DC2626;
        --red-dark: #991B1B;
        --red-deeper: #7F1D1D;
        --red-light: #FEE2E2;
        --red-glow: rgba(220, 38, 38, 0.18);
        --white: #FFFFFF;
        --off-white: #F9FAFB;
        --gray-200: #E5E7EB;
        --gray-400: #9CA3AF;
        --gray-600: #4B5563;
        --gray-800: #1F2937;
        --gray-900: #111827;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        font-family: 'Barlow', sans-serif;
        background: var(--gray-900);
    }

    /* LEFT PANEL */
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
        transition: var(--transition);
    }

    .auth-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .auth-left::after {
        content: '';
        position: absolute;
        bottom: -120px;
        right: -120px;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.08);
        pointer-events: none;
    }

    .deco-circle-inner {
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.12);
        pointer-events: none;
    }

    .brand-block {
        position: relative;
        z-index: 1;
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
        transition: var(--transition);
    }

    .brand-badge:hover {
        background: rgba(255,255,255,0.18);
        transform: translateY(-2px);
    }

    .brand-icon {
        font-size: 1.5rem;
        line-height: 1;
    }

    .brand-name {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--white);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .left-headline {
        position: relative;
        z-index: 1;
    }

    .left-headline h2 {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 900;
        color: var(--white);
        line-height: 1.05;
        margin: 0 0 1rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        text-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }

    .left-headline p {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
        margin: 0;
        max-width: 320px;
    }

    .left-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-item {
        border-left: 3px solid rgba(255,255,255,0.3);
        padding-left: 0.75rem;
        transition: var(--transition);
    }

    .stat-item:hover {
        border-left-color: rgba(255,255,255,0.6);
        transform: translateX(4px);
    }

    .stat-num {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--white);
        line-height: 1;
    }

    .stat-desc {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-top: 0.2rem;
    }

    /* RIGHT PANEL */
    .auth-right {
        flex: 1;
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
        overflow-y: auto;
    }

    .auth-right::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%);
    }

    .form-box {
        width: 100%;
        max-width: 400px;
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        margin-bottom: 2.5rem;
    }

    .form-eyebrow {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-eyebrow::before {
        content: '';
        display: inline-block;
        width: 20px;
        height: 2px;
        background: var(--red);
        transition: var(--transition);
    }

    .form-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 2.25rem;
        font-weight: 900;
        color: var(--gray-900);
        line-height: 1.1;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    /* FORM FIELDS */
    .field-group {
        margin-bottom: 1.25rem;
        position: relative;
    }

    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .field-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
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
        transition: var(--transition);
        box-sizing: border-box;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--red);
        background: var(--white);
        box-shadow: 0 0 0 3px var(--red-glow);
    }

    .field-input.error {
        border-color: var(--red);
        background: var(--red-light);
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 4px;
        color: var(--gray-400);
        transition: var(--transition);
    }

    .password-toggle:hover {
        color: var(--red);
    }

    .field-error {
        font-size: 0.75rem;
        color: var(--red);
        margin-top: 0.375rem;
        font-weight: 500;
        animation: shake 0.3s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
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
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .remember-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .remember-row input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--red);
        cursor: pointer;
        flex-shrink: 0;
    }

    .remember-row label {
        font-size: 0.813rem;
        color: var(--gray-600);
        cursor: pointer;
        user-select: none;
    }

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
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.35);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-submit.loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-submit.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid var(--white);
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .forgot-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        font-size: 0.813rem;
        color: var(--gray-400);
        text-decoration: none;
        transition: var(--transition);
    }

    .forgot-link:hover {
        color: var(--red);
        transform: translateX(4px);
    }

    .form-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 1.5rem 0;
        position: relative;
    }

    .form-footer-note {
        text-align: center;
        font-size: 0.75rem;
        color: var(--gray-400);
        line-height: 1.5;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .auth-left {
            display: none;
        }
        
        .auth-right {
            padding: 2rem 1.5rem;
        }
        
        .auth-right::before {
            display: none;
        }
        
        .form-title {
            font-size: 1.75rem;
        }
    }

    /* Loading state untuk submit */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid var(--white);
        border-top-color: var(--red);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
</style>

<div class="auth-page">
    <div class="auth-left">
        <div class="deco-circle-inner"></div>
        
        <div class="brand-block">
            <div class="brand-badge">
                <span class="brand-icon">🔩</span>
                <span class="brand-name">MSO System</span>
            </div>
        </div>

        <div class="left-headline">
            <h2>Maintenance<br>Service<br>Order</h2>
            <p>Platform terpadu untuk monitoring pekerjaan maintenance dan permintaan material secara real-time.</p>

            <div class="left-stats">
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-desc">Digital</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">Real</div>
                    <div class="stat-desc">Time Data</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">24/7</div>
                    <div class="stat-desc">Accessible</div>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-box">
            <div class="form-header">
                <div class="form-eyebrow">Portal Akses</div>
                <div class="form-title">Masuk ke<br>Sistem</div>
            </div>

            @if (session('status'))
                <div class="session-status" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="session-status" style="background: #FEE2E2; border-left-color: var(--red); color: var(--red-dark);">
                        ⚠️ {{ $error }}
                    </div>
                @endforeach
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Email</label>
                    <input id="email"
                           class="field-input @error('email') error @enderror"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="nama@perusahaan.com"
                           aria-label="Alamat email">
                    @error('email')
                        <div class="field-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-input-wrapper">
                        <input id="password"
                               class="field-input @error('password') error @enderror"
                               type="password"
                               name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               aria-label="Password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Tampilkan password">
                            👁️
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    Masuk →
                </button>

                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </form>

            <div class="form-divider"></div>
            <div class="form-footer-note">
                MSO System &copy; {{ date('Y') }} — Akses terbatas untuk pengguna terdaftar
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.type === 'password' ? 'text' : 'password';
        field.type = type;
    }

    // Loading state on form submit
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });

    // Remove error styling on focus
    document.querySelectorAll('.field-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.remove('error');
            const errorDiv = this.parentElement?.querySelector('.field-error');
            if (errorDiv) errorDiv.remove();
        });
    });
</script>

</x-guest-layout>