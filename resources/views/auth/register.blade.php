<x-guest-layout>

{{-- ============================================================
     REGISTER — MSO Visual Theme (Enhanced)
     ============================================================ --}}

<style>
    /* ... (sama seperti di login, tapi disesuaikan) ... */
    @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body, html { margin: 0; padding: 0; height: 100%; }

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
        width: 40%;
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
        background-image: linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .auth-left::after {
        content: '';
        position: absolute;
        top: -100px;
        left: -100px;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.08);
    }

    .deco-bottom-circle {
        position: absolute;
        bottom: -80px;
        right: -80px;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.1);
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
        transition: var(--transition);
    }

    .brand-badge:hover {
        background: rgba(255,255,255,0.18);
        transform: translateY(-2px);
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

    .left-content h2 {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: clamp(2rem, 3vw, 2.75rem);
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
        margin: 0 0 2rem;
        max-width: 280px;
    }

    .feature-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.813rem;
        color: rgba(255,255,255,0.8);
        transition: var(--transition);
    }

    .feature-item:hover {
        transform: translateX(4px);
        color: var(--white);
    }

    .feature-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        flex-shrink: 0;
    }

    .login-link-block {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .login-link-block p {
        font-size: 0.813rem;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0.75rem;
    }

    .btn-to-login {
        display: inline-block;
        padding: 0.625rem 1.5rem;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 6px;
        color: var(--white);
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-to-login:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
        color: var(--white);
    }

    /* RIGHT PANEL */
    .auth-right {
        flex: 1;
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2.5rem;
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
        max-width: 420px;
        padding: 1rem 0;
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
        margin-bottom: 2rem;
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
    }

    .form-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--gray-900);
        line-height: 1.1;
        text-transform: uppercase;
    }

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .field-group {
        margin-bottom: 1rem;
    }

    .field-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gray-600);
        margin-bottom: 0.4rem;
    }

    .field-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .field-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
        font-family: 'Barlow', sans-serif;
        font-size: 0.875rem;
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
        font-size: 0.7rem;
        color: var(--red);
        margin-top: 0.3rem;
        font-weight: 500;
        animation: shake 0.3s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 1.25rem 0;
        color: var(--gray-400);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--gray-200);
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
        margin-top: 0.5rem;
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

    .btn-submit.loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .password-strength {
        margin-top: 0.5rem;
    }

    .strength-bar {
        height: 4px;
        background: var(--gray-200);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 0.25rem;
    }

    .strength-bar-fill {
        height: 100%;
        width: 0%;
        transition: width 0.3s, background 0.3s;
    }

    .strength-text {
        font-size: 0.7rem;
        margin-top: 0.25rem;
        color: var(--gray-400);
    }

    @media (max-width: 900px) {
        .auth-left {
            display: none;
        }
        
        .auth-right::before {
            display: none;
        }
        
        .auth-right {
            padding: 2rem 1.5rem;
        }
        
        .field-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="auth-page">
    <div class="auth-left">
        <div class="deco-bottom-circle"></div>

        <div class="brand-badge">
            <span style="font-size:1.25rem;">🔩</span>
            <span class="brand-name">MSO System</span>
        </div>

        <div class="left-content">
            <h2>Bergabung<br>dengan<br>MSO</h2>
            <p>Daftarkan akun Anda untuk mengakses platform monitoring maintenance terpadu.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    Monitoring permintaan material real-time
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    Tracking status pekerjaan maintenance
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    Laporan dan analitik pengadaan
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>
                    Notifikasi dan alert otomatis
                </div>
            </div>
        </div>

        <div class="login-link-block">
            <p>Sudah memiliki akun?</p>
            <a href="{{ route('login') }}" class="btn-to-login">← Login Sekarang</a>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-box">
            <div class="form-header">
                <div class="form-eyebrow">Registrasi Akun</div>
                <div class="form-title">Buat Akun<br>Baru</div>
            </div>

            @if ($errors->any())
                <div class="session-status" style="background: #FEE2E2; border-left-color: var(--red); color: var(--red-dark); margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 6px;">
                    ⚠️ Silakan periksa form di bawah
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="name">Nama Lengkap</label>
                    <input id="name"
                           class="field-input @error('name') error @enderror"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           placeholder="Nama lengkap Anda"
                           aria-label="Nama lengkap">
                    @error('name')
                        <div class="field-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <input id="email"
                           class="field-input @error('email') error @enderror"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autocomplete="username"
                           placeholder="nama@perusahaan.com"
                           aria-label="Alamat email">
                    @error('email')
                        <div class="field-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="section-divider">Password</div>

                <div class="field-row">
                    <div>
                        <label class="field-label" for="password">Password</label>
                        <div class="field-input-wrapper">
                            <input id="password"
                                   class="field-input @error('password') error @enderror"
                                   type="password"
                                   name="password"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   aria-label="Password"
                                   oninput="checkPasswordStrength(this.value)">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Tampilkan password">
                                👁️
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-bar-fill" id="strengthBar"></div>
                            </div>
                            <div class="strength-text" id="strengthText"></div>
                        </div>
                        @error('password')
                            <div class="field-error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label" for="password_confirmation">Konfirmasi</label>
                        <div class="field-input-wrapper">
                            <input id="password_confirmation"
                                   class="field-input"
                                   type="password"
                                   name="password_confirmation"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   aria-label="Konfirmasi password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Tampilkan password">
                                👁️
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="field-error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    Daftar Sekarang →
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.type === 'password' ? 'text' : 'password';
        field.type = type;
    }

    function checkPasswordStrength(password) {
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        const strengths = [
            { width: '0%', color: '', text: '' },
            { width: '25%', color: '#EF4444', text: 'Lemah' },
            { width: '50%', color: '#F97316', text: 'Cukup' },
            { width: '75%', color: '#EAB308', text: 'Baik' },
            { width: '100%', color: '#10B981', text: 'Kuat' }
        ];
        
        bar.style.width = strengths[strength].width;
        bar.style.background = strengths[strength].color;
        text.textContent = strengths[strength].text;
        text.style.color = strengths[strength].color || '#9CA3AF';
    }

    // Loading state on form submit
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });

    // Remove error styling on focus
    document.querySelectorAll('.field-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.remove('error');
            const errorDiv = this.parentElement?.parentElement?.querySelector('.field-error');
            if (errorDiv) errorDiv.remove();
        });
    });
</script>

</x-guest-layout>