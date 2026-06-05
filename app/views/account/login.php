<?php include 'app/views/shares/header.php'; ?>

<style>
    /* Specific Login Styles */
    .auth-wrapper {
        position: relative;
        min-height: calc(100vh - 200px); /* Adjust based on header/footer */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
        overflow: hidden;
    }
    
    /* Abstract background blobs */
    .blob {
        position: absolute;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.6;
        animation: float 10s infinite ease-in-out alternate;
    }
    .blob-1 {
        top: 10%;
        left: 20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(88,166,255,0.4) 0%, rgba(0,0,0,0) 70%);
        animation-delay: 0s;
    }
    .blob-2 {
        bottom: 10%;
        right: 20%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(46,160,67,0.3) 0%, rgba(0,0,0,0) 70%);
        animation-delay: -5s;
    }
    
    @keyframes float {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, -50px) scale(1.1); }
    }

    .auth-card {
        background: rgba(22, 27, 34, 0.65);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        width: 100%;
        max-width: 440px;
        padding: 3rem 2.5rem;
        position: relative;
        z-index: 1;
        animation: formFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes formFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .auth-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
    }
    .auth-subtitle {
        color: var(--muted);
        font-size: 0.9rem;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .form-control-custom {
        width: 100%;
        background: rgba(13, 17, 23, 0.7);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        outline: none;
    }
    .form-control-custom:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15);
        background: rgba(13, 17, 23, 0.9);
    }
    .form-control-custom::placeholder {
        color: #5c646c;
    }

    .auth-btn {
        width: 100%;
        background: var(--accent);
        color: #0d1117;
        border: none;
        border-radius: 8px;
        padding: 0.85rem;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
    }
    .auth-btn:hover {
        background: var(--accent-dim);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(88, 166, 255, 0.25);
    }
    .auth-btn:active {
        transform: translateY(0);
    }

    .auth-footer {
        margin-top: 2rem;
        text-align: center;
        font-size: 0.85rem;
        color: var(--muted);
    }
    .auth-footer a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .auth-footer a:hover {
        color: #79c0ff;
        text-decoration: underline;
    }

    .social-login {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }
    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        color: var(--text);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 1.1rem;
    }
    .social-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        transform: translateY(-2px);
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 2rem 0;
        color: var(--muted);
        font-size: 0.8rem;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border);
    }
    .divider:not(:empty)::before { margin-right: 1rem; }
    .divider:not(:empty)::after { margin-left: 1rem; }
    
    .error-box {
        background: rgba(248, 81, 73, 0.1);
        border: 1px solid rgba(248, 81, 73, 0.3);
        color: var(--danger);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }
    .error-box ul { margin: 0; padding-left: 1.2rem; }
</style>

<div class="auth-wrapper">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Sign in to continue to Cyber Store</p>
        </div>

        <form action="<?php echo BASE_URL; ?>/account/checklogin" method="post">
            <?php
            if (isset($errors) && count($errors) > 0) {
                echo "<div class='error-box'><ul>";
                foreach ($errors as $err) {
                    echo "<li>$err</li>";
                }
                echo "</ul></div>";
            }
            ?>

            <div class="form-floating">
                <input type="text" name="username" class="form-control-custom" placeholder="Tên đăng nhập" required autocomplete="username" />
            </div>
            
            <div class="form-floating">
                <input type="password" name="password" class="form-control-custom" placeholder="Mật khẩu" required autocomplete="current-password" />
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--muted); cursor: pointer;">
                    <input type="checkbox" name="remember_me" style="accent-color: var(--accent);"> Ghi nhớ tôi
                </label>
                <a href="<?php echo BASE_URL; ?>/account/forgotPassword" style="color: var(--accent); text-decoration: none;">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="auth-btn">Đăng Nhập</button>
            
            

            <div class="auth-footer">
                Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>/account/register">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
