<?php include 'app/views/shares/header.php'; ?>

<style>
    .auth-wrapper {
        position: relative;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
        overflow: hidden;
    }
    
    .blob {
        position: absolute;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.6;
        animation: float 10s infinite ease-in-out alternate;
    }
    .blob-1 { top: 10%; left: 20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(88,166,255,0.4) 0%, rgba(0,0,0,0) 70%); animation-delay: 0s; }
    .blob-2 { bottom: 10%; right: 20%; width: 350px; height: 350px; background: radial-gradient(circle, rgba(46,160,67,0.3) 0%, rgba(0,0,0,0) 70%); animation-delay: -5s; }
    
    @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(30px, -50px) scale(1.1); } }

    .auth-card {
        background: rgba(22, 27, 34, 0.65);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        width: 100%; max-width: 450px; padding: 3rem 2.5rem; position: relative; z-index: 1;
        animation: formFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes formFadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .auth-header { text-align: center; margin-bottom: 2.5rem; }
    .auth-title { font-size: 1.75rem; font-weight: 800; color: #ffffff; letter-spacing: -0.03em; margin-bottom: 0.5rem; }
    .auth-subtitle { color: var(--muted); font-size: 0.9rem; }

    .form-floating { position: relative; margin-bottom: 1.25rem; }
    .form-control-custom { width: 100%; background: rgba(13, 17, 23, 0.7); border: 1px solid var(--border); border-radius: 8px; color: var(--text); padding: 1rem 1.25rem; font-size: 0.95rem; transition: all 0.3s ease; outline: none; }
    .form-control-custom:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15); background: rgba(13, 17, 23, 0.9); }
    .form-control-custom::placeholder { color: #5c646c; }

    .auth-btn { width: 100%; background: var(--accent); color: #0d1117; border: none; border-radius: 8px; padding: 0.85rem; font-weight: 700; font-size: 1rem; letter-spacing: 0.02em; cursor: pointer; transition: all 0.2s ease; }
    .auth-btn:hover { background: var(--accent-dim); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(88, 166, 255, 0.25); }

    .error-box { background: rgba(248, 81, 73, 0.1); border: 1px solid rgba(248, 81, 73, 0.3); color: var(--danger); padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.85rem; }
    .success-box { background: rgba(46, 160, 67, 0.1); border: 1px solid rgba(46, 160, 67, 0.3); color: var(--teal); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center; }
</style>

<div class="auth-wrapper">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Đổi Mật Khẩu</h2>
            <p class="auth-subtitle">Nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="success-box"><?php echo $success; ?></div>
            <a href="<?php echo BASE_URL; ?>/account/login" class="auth-btn" style="text-align: center; display: block; text-decoration: none; margin-top: 20px;">Trở về Đăng nhập</a>
        <?php else: ?>
            <form action="<?php echo BASE_URL; ?>/account/resetPassword" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ''); ?>">
                
                <?php if (isset($error)): ?>
                    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="form-floating">
                    <input type="password" name="password" class="form-control-custom" placeholder="Mật khẩu mới" required />
                </div>
                
                <div class="form-floating">
                    <input type="password" name="confirmpassword" class="form-control-custom" placeholder="Xác nhận mật khẩu mới" required />
                </div>

                <button type="submit" class="auth-btn" style="margin-top: 1rem;">Cập Nhật Mật Khẩu</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
