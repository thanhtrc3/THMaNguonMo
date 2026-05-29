<?php include 'app/views/shares/header.php'; ?>

<style>
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(232, 255, 71, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(232, 255, 71, 0.03) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .wrapper { width: 100%; max-width: 520px; margin: 2rem auto; position: relative; z-index: 1; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .header-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .breadcrumb-link { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); text-decoration: none; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s; }
    .breadcrumb-link:hover { color: var(--accent); text-decoration: none; } .breadcrumb-link::before { content: '←'; font-size: 1rem; }
    .tag { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.12em; color: var(--accent); background: rgba(232, 255, 71, 0.08); border: 1px solid rgba(232, 255, 71, 0.25); padding: 0.3rem 0.75rem; border-radius: 2px; text-transform: uppercase; }
    .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 4px; overflow: hidden; }
    .panel-header { padding: 2rem 2.5rem 1.5rem; border-bottom: 1px solid var(--border); position: relative; }
    .panel-header::after { content: ''; position: absolute; bottom: -1px; left: 2.5rem; width: 3rem; height: 2px; background: var(--accent); }
    .panel-title { font-family: var(--sans); font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text); line-height: 1; }
    .panel-subtitle { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); margin-top: 0.5rem; letter-spacing: 0.04em; }
    .panel-body { padding: 2rem 2.5rem 2.5rem; }
    .alert-error { background: rgba(255, 77, 77, 0.08); border: 1px solid rgba(255, 77, 77, 0.3); border-left: 3px solid var(--danger); border-radius: 3px; padding: 1rem 1.25rem; margin-bottom: 1.75rem; }
    .alert-error ul { list-style: none; margin: 0; padding: 0; }
    .alert-error li { font-family: var(--mono); font-size: 0.75rem; color: #ff8080; padding: 0.2rem 0; letter-spacing: 0.02em; }
    .alert-error li::before { content: '✕  '; }
    .field { margin-bottom: 1.5rem; }
    .field-label { display: block; font-family: var(--mono); font-size: 0.68rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); margin-bottom: 0.6rem; }
    .field-label span { color: var(--accent); margin-left: 0.15rem; }
    .field-input { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 3px; color: var(--text); font-family: var(--sans); font-size: 0.95rem; font-weight: 400; padding: 0.75rem 1rem; outline: none; transition: border-color 0.2s, box-shadow 0.2s; -webkit-appearance: none; appearance: none; }
    .field-input::placeholder { color: #3a3a3a; }
    .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(232, 255, 71, 0.08); }
    .actions { margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
    .btn-primary { width: 100%; background: var(--accent); color: #0d0d0d; border: none; border-radius: 3px; font-family: var(--sans); font-size: 0.9rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.9rem 1rem; cursor: pointer; transition: background 0.2s, transform 0.15s; }
    .btn-primary:hover { background: var(--accent-dim); transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }
    .btn-secondary { width: 100%; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 3px; font-family: var(--mono); font-size: 0.75rem; letter-spacing: 0.08em; padding: 0.8rem 1rem; cursor: pointer; text-align: center; text-decoration: none; display: block; transition: color 0.2s, border-color 0.2s; }
    .btn-secondary:hover { color: var(--text); border-color: #4a4a4a; text-decoration: none; }
</style>

<div class="wrapper">
    <div class="header-bar">
        <a href="<?php echo BASE_URL; ?>/Category/list" class="breadcrumb-link">Quay lại danh sách</a>
        <span class="tag">Danh mục mới</span>
    </div>
    <div class="panel">
        <div class="panel-header">
            <h1 class="panel-title">Thêm danh mục</h1>
            <p class="panel-subtitle">// Tạo một danh mục mới cho sản phẩm</p>
        </div>
        <div class="panel-body">
            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo BASE_URL; ?>/Category/save">
                <div class="field">
                    <label class="field-label" for="name">Tên danh mục <span>*</span></label>
                    <input type="text" class="field-input" id="name" name="name" placeholder="Nhập tên danh mục..." autocomplete="off" required>
                </div>
                <div class="actions">
                    <button type="submit" class="btn-primary">Lưu danh mục →</button>
                    <a href="<?php echo BASE_URL; ?>/Category/list" class="btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
