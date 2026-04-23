<x-guest-layout>

{{-- ============================================================
     RESET PASSWORD — MSO Visual Theme
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
        position: relative; z-index: 1;
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
        position: relative; z-index: 1;
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

    /* Password strength tips */
    .tips-list {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }

    .tip-item {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.7);
    }

    .tip-icon {
        width: 20px; height: 20px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.625rem;
        flex-shrink: 0;
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
        margin-bottom: 2rem;
    }

    .field-group { margin-bottom: 1.25rem; }

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

    /* Password strength bar */
    .strength-bar-wrap {
        margin-top: 0.5rem;
        height: 4px;
        background: var(--gray-200);
        border-radius: 999px;
        overflow: hidden;
    }

    .strength-bar {
        height: 100%;
        border-radius: 999px;
        width: 0%;
        transition: width 0.3s, background 0.3s;
    }

    .strength-label {
        font-size: 0.7rem;
        color: var(--gray-400);
        margin-top: 0.25rem;
        height: 1rem;
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
        transition: all 0.25s ease;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220,38,38,0.35);
    }

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
            <span class="left-icon-big">🔐</span>
            <h2>Buat<br>Password<br>Baru</h2>
            <p>Gunakan password yang kuat untuk melindungi akun MSO Anda.</p>

            <div class="tips-list">
                <div class="tip-item">
                    <div class="tip-icon">✓</div>
                    Minimal 8 karakter
                </div>
                <div class="tip-item">
                    <div class="tip-icon">✓</div>
                    Kombinasi huruf besar & kecil
                </div>
                <div class="tip-item">
                    <div class="tip-icon">✓</div>
                    Mengandung angka atau simbol
                </div>
                <div class="tip-item">
                    <div class="tip-icon">✓</div>
                    Tidak sama dengan password lama
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-box">

            <div class="form-eyebrow">Reset Password</div>
            <div class="form-title">Password<br>Baru</div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div class="field-group">
                    <label class="field-label" for="email">Email</label>
                    <input id="email"
                           class="field-input"
                           type="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username"
                           placeholder="nama@perusahaan.com">
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label class="field-label" for="password">Password Baru</label>
                    <input id="password"
                           class="field-input"
                           type="password"
                           name="password"
                           required autocomplete="new-password"
                           placeholder="••••••••"
                           oninput="checkStrength(this.value)">
                    <div class="strength-bar-wrap">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel"></div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation"
                           class="field-input"
                           type="password"
                           name="password_confirmation"
                           required autocomplete="new-password"
                           placeholder="••••••••">
                    @error('password_confirmation')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Simpan Password →
                </button>
            </form>

        </div>
    </div>

</div>

<script>
    function checkStrength(val) {
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const map = [
            { w: '0%',   bg: '',          txt: '' },
            { w: '25%',  bg: '#EF4444',   txt: 'Lemah' },
            { w: '50%',  bg: '#F97316',   txt: 'Cukup' },
            { w: '75%',  bg: '#EAB308',   txt: 'Baik' },
            { w: '100%', bg: '#16a34a',   txt: 'Kuat ✓' },
        ];

        bar.style.width      = map[score].w;
        bar.style.background = map[score].bg;
        label.textContent    = map[score].txt;
        label.style.color    = map[score].bg;
    }
</script>

</x-guest-layout>