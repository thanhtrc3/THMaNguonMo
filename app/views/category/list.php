<?php /** @var CategoryModel[] $categories */ ?>
<?php include 'app/views/shares/header.php'; ?>

<style>
    /* Page specific styles */
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(232,255,71,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(232,255,71,0.025) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .page { max-width: 800px; margin: 0 auto; position: relative; z-index: 1; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    
    .topbar { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2.5rem; animation: fadeUp 0.4s ease both; }
    .sys-label { font-family: var(--mono); font-size: 0.65rem; color: var(--accent); letter-spacing: 0.16em; text-transform: uppercase; margin-bottom: 0.4rem; }
    .page-title { font-size: 2.5rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1; } .page-title span { color: var(--accent); }
    .meta-row { display: flex; align-items: center; gap: 1.5rem; margin-top: 0.5rem; }
    .meta-item { font-family: var(--mono); font-size: 0.68rem; color: var(--muted); letter-spacing: 0.06em; } .meta-item strong { color: var(--teal); }
    
    .btn-add { display: flex; align-items: center; gap: 0.6rem; background: transparent; color: var(--accent); text-decoration: none; font-family: var(--mono); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; padding: 0.75rem 1.4rem; white-space: nowrap; position: relative; transition: color 0.2s; }
    .btn-add::before, .btn-add::after { content: ''; position: absolute; width: 10px; height: 10px; transition: width 0.25s ease, height 0.25s ease; }
    .btn-add::before { top: 0; left: 0; border-top: 1.5px solid var(--accent); border-left: 1.5px solid var(--accent); }
    .btn-add::after { bottom: 0; right: 0; border-bottom: 1.5px solid var(--accent); border-right: 1.5px solid var(--accent); }
    .btn-add .corner-tr, .btn-add .corner-bl { position: absolute; width: 10px; height: 10px; transition: width 0.25s ease, height 0.25s ease; pointer-events: none; }
    .btn-add .corner-tr { top: 0; right: 0; border-top: 1.5px solid var(--accent); border-right: 1.5px solid var(--accent); }
    .btn-add .corner-bl { bottom: 0; left: 0; border-bottom: 1.5px solid var(--accent); border-left: 1.5px solid var(--accent); }
    .btn-add:hover::before, .btn-add:hover::after, .btn-add:hover .corner-tr, .btn-add:hover .corner-bl { width: 100%; height: 100%; }

    .table-wrap { border: 1px solid var(--border); border-radius: 4px; overflow: hidden; animation: fadeUp 0.4s 0.14s ease both; background: var(--surface); }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--surface); border-bottom: 1px solid var(--border); }
    th { font-family: var(--mono); font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--muted); padding: 0.9rem 1.25rem; text-align: left; white-space: nowrap; }
    th:last-child  { width: 160px; text-align: center; }
    th.id-col { width: 60px; text-align: center; }
    tbody tr { border-bottom: 1px solid var(--border2); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; } tbody tr:hover { background: var(--surface2); }
    td { padding: 1rem 1.25rem; vertical-align: middle; font-size: 0.88rem; }
    
    .td-id { font-family: var(--mono); color: var(--muted); text-align: center; }
    .td-name { font-weight: 700; }
    .td-actions { text-align: center; } .action-row { display: flex; gap: 0.4rem; justify-content: center; }

    .btn-edit { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.06em; color: var(--teal); background: rgba(71,232,208,0.08); border: 1px solid rgba(71,232,208,0.2); border-radius: 3px; padding: 0.32rem 0.7rem; text-decoration: none; transition: background 0.2s, border-color 0.2s; white-space: nowrap; }
    .btn-edit:hover { background: rgba(71,232,208,0.16); border-color: rgba(71,232,208,0.5); }
    .btn-delete { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.06em; color: var(--danger); background: rgba(255,77,77,0.06); border: 1px solid rgba(255,77,77,0.18); border-radius: 3px; padding: 0.32rem 0.7rem; text-decoration: none; transition: background 0.2s, border-color 0.2s; white-space: nowrap; }
    .btn-delete:hover { background: rgba(255,77,77,0.14); border-color: rgba(255,77,77,0.45); }

    .empty-state { padding: 5rem 2rem; text-align: center; }
    .empty-icon { font-family: var(--mono); font-size: 2rem; color: #2a2a2a; margin-bottom: 1.25rem; }
    .empty-title { font-size: 1.1rem; font-weight: 700; color: #3a3a3a; margin-bottom: 0.4rem; }
    .empty-sub { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); }
    
    /* Modal */
    .cyber-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.78); z-index: 10000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
    .cyber-modal-backdrop.is-open { opacity: 1; pointer-events: all; }
    .cyber-modal { background: var(--surface); border: 1px solid var(--border); border-radius: 4px; width: 100%; max-width: 380px; margin: 1rem; position: relative; transform: translateY(20px) scale(0.97); transition: transform 0.25s cubic-bezier(0.16,1,0.3,1); overflow: hidden; display: block; }
    .cyber-modal-backdrop.is-open .cyber-modal { transform: translateY(0) scale(1); }
    .cyber-modal-strip { height: 3px; background: var(--danger); }
    .cyber-modal-body { padding: 1.75rem 1.75rem 1rem; }
    .cyber-modal-tag { font-family: var(--mono); font-size: 0.62rem; letter-spacing: 0.14em; color: var(--danger); background: rgba(255,77,77,0.08); border: 1px solid rgba(255,77,77,0.2); border-radius: 2px; display: inline-block; padding: 0.22rem 0.55rem; margin-bottom: 0.9rem; text-transform: uppercase; }
    .cyber-modal-title { font-size: 1.25rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text); margin-bottom: 0.45rem; }
    .cyber-modal-desc { font-family: var(--mono); font-size: 0.7rem; color: var(--muted); line-height: 1.65; letter-spacing: 0.02em; }
    .cyber-modal-cat-name { display: block; font-family: var(--mono); font-size: 0.72rem; color: var(--text); background: var(--bg); border: 1px solid var(--border); border-left: 2px solid var(--danger); border-radius: 3px; padding: 0.6rem 0.85rem; margin-top: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cyber-modal-footer { display: flex; gap: 0.75rem; padding: 1.25rem 1.75rem 1.75rem; }
    .cyber-modal-btn-cancel { flex: 1; background: transparent; border: 1px solid var(--border); border-radius: 3px; color: var(--muted); font-family: var(--mono); font-size: 0.72rem; letter-spacing: 0.08em; padding: 0.72rem; cursor: pointer; transition: color 0.2s, border-color 0.2s; } .cyber-modal-btn-cancel:hover { color: var(--text); border-color: #4a4a4a; }
    .cyber-modal-btn-confirm { flex: 1; background: var(--danger); border: none; border-radius: 3px; color: #fff; font-family: var(--mono); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.72rem; cursor: pointer; transition: background 0.2s; } .cyber-modal-btn-confirm:hover { background: #cc3333; }
</style>
<div class="page">
    <div class="topbar">
        <div>
            <p class="sys-label">// Quản lý danh mục</p>
            <h1 class="page-title">Danh <span>Mục</span></h1>
            <div class="meta-row">
                <span class="meta-item">Tổng: <strong><?php echo count($categories); ?></strong> mục</span>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/Category/add" class="btn-add">
            <span class="corner-tr"></span>
            <span class="corner-bl"></span>
            + Thêm danh mục
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tên danh mục</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="2">
                        <div class="empty-state">
                            <div class="empty-icon">[ _ ]</div>
                            <p class="empty-title">Chưa có danh mục nào</p>
                            <p class="empty-sub">// Nhấn "Thêm danh mục" để bắt đầu</p>
                        </div>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="td-name" data-name="<?php echo htmlspecialchars($c->getName(), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($c->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-row">
                                    <a href="<?php echo BASE_URL; ?>/Category/edit/<?php echo $c->getID(); ?>" class="btn-edit">Sửa</a>
                                    <a href="<?php echo BASE_URL; ?>/Category/delete/<?php echo $c->getID(); ?>" class="btn-delete" onclick="openDeleteModal(this); return false;">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="cyber-modal-backdrop" id="deleteModal">
        <div class="cyber-modal">
            <div class="cyber-modal-strip"></div>
            <div class="cyber-modal-body">
                <span class="cyber-modal-tag">! Cảnh báo</span>
                <h2 class="cyber-modal-title">Xóa danh mục</h2>
                <p class="cyber-modal-desc">Hành động này không thể hoàn tác.<br>Sản phẩm thuộc danh mục này sẽ không còn hiển thị đúng.</p>
                <span class="cyber-modal-cat-name" id="modalCatName">—</span>
            </div>
            <div class="cyber-modal-footer">
                <button class="cyber-modal-btn-cancel" id="modalCancel">Hủy bỏ</button>
                <button class="cyber-modal-btn-confirm" id="modalConfirm">Xóa ngay</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteTarget = null;
    function openDeleteModal(link) {
        deleteTarget = link.href;
        const tr = link.closest('tr');
        const name = tr ? tr.querySelector('.td-name').dataset.name : '—';
        document.getElementById('modalCatName').textContent = name;
        document.getElementById('deleteModal').classList.add('is-open');
    }
    document.getElementById('modalCancel').addEventListener('click', () => {
        document.getElementById('deleteModal').classList.remove('is-open');
        deleteTarget = null;
    });
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('is-open');
            deleteTarget = null;
        }
    });
    document.getElementById('modalConfirm').addEventListener('click', () => {
        if (deleteTarget) window.location.href = deleteTarget;
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('deleteModal').classList.remove('is-open');
            deleteTarget = null;
        }
    });
</script>
<?php include 'app/views/shares/footer.php'; ?>
