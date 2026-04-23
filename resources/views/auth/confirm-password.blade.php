<x-guest-layout>

{{-- ============================================================
     CONFIRM PASSWORD — MSO Visual Theme
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

    /* Background dekoratif */
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
        top: -200px; right: -200px;
        width: 600px; height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(220,38,38,0.12) 0%, transparent 70%);
    }

    /* Card terpusat */
    .confirm-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        width: 100%;
        max-width: 440px;
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

    .lock-icon {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
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

    .card-body { padding: 2rem; }

    .security-notice {
        background: var(--off-white);
        border: 1px solid var(--gray-200);
        border-left: 4px solid var(--red);
        border-radius: 0 8px 8px 0;
        padding: 0.875rem 1rem;
        font-size: 0.813rem;
        color: var(--gray-600);
        line-height: 1.5;
        margin-bottom: 1.75rem;
        display: flex;
        gap: 0.625rem;
        align-items: flex-start;
    }

    .security-icon { font-size: 1rem; flex-shrink: 0; margin-top: 0.05rem; }

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

    .brand-footer {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.75rem;
        color: var(--gray-400);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }
</style>

<div class="auth-page">

    <div class="confirm-card">

        <div class="card-header">
            <span class="lock-icon">🔒</span>
            <div class="card-eyebrow">Area Aman</div>
            <h1 class="card-title">Konfirmasi<br>Password</h1>
        </div>

        <div class="card-body">

            <div class="security-notice">
                <span class="security-icon">🛡️</span>
                <span>Anda memasuki area aman. Masukkan password Anda untuk melanjutkan akses ke fitur ini.</span>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <input id="password"
                           class="field-input"
                           type="password"
                           name="password"
                           required autocomplete="current-password"
                           placeholder="Masukkan password Anda"
                           autofocus>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Konfirmasi & Lanjutkan →
                </button>
            </form>

            <div class="brand-footer">
                🔩 MSO System &mdash; Akses Terproteksi
            </div>

        </div>
    </div>

</div>

</x-guest-layout>