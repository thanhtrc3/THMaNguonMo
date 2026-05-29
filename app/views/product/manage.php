<?php /** @var ProductModel[] $products */ ?>
<?php include 'app/views/shares/header.php'; ?>

<style>
    /* Page specific styles */
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .page { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; }
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
    .btn-add .btn-icon { font-size: 1rem; font-weight: 300; font-family: var(--sans); opacity: 0.6; transition: opacity 0.2s, transform 0.25s; }
    .btn-add:hover .btn-icon { opacity: 1; transform: rotate(90deg); }

    .toolbar { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; animation: fadeUp 0.4s 0.08s ease both; }
    .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
    .search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-size: 0.85rem; color: var(--muted); pointer-events: none; }
    .search-input { width: 100%; background: var(--surface); border: 1px solid var(--border); border-radius: 3px; color: var(--text); font-family: var(--mono); font-size: 0.78rem; padding: 0.62rem 0.85rem 0.62rem 2.2rem; outline: none; letter-spacing: 0.04em; transition: border-color 0.2s; }
    .search-input::placeholder { color: #3a3a3a; } .search-input:focus { border-color: var(--accent); }
    .sort-select { background: var(--surface); border: 1px solid var(--border); border-radius: 3px; color: var(--muted); font-family: var(--mono); font-size: 0.72rem; padding: 0.62rem 0.85rem; outline: none; cursor: pointer; letter-spacing: 0.04em; transition: border-color 0.2s, color 0.2s; appearance: none; -webkit-appearance: none; }
    .sort-select:focus { border-color: var(--accent); color: var(--text); }
    .result-count { font-family: var(--mono); font-size: 0.68rem; color: var(--muted); letter-spacing: 0.06em; white-space: nowrap; margin-left: auto; } .result-count strong { color: var(--text); }
    .view-toggle { display: flex; border: 1px solid var(--border); border-radius: 3px; overflow: hidden; }
    .view-btn { background: var(--surface); border: none; color: var(--muted); padding: 0.6rem 0.9rem; cursor: pointer; font-size: 1rem; line-height: 1; transition: background 0.15s, color 0.15s; }
    .view-btn + .view-btn { border-left: 1px solid var(--border); } .view-btn.active { background: var(--accent); color: #ffffff; }

    .card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1px; background: var(--border); border: 1px solid var(--border); border-radius: 4px; overflow: hidden; animation: fadeUp 0.4s 0.14s ease both; }
    .card-grid.is-hidden { display: none; }
    .product-card { background: var(--surface); display: flex; flex-direction: column; transition: background 0.15s; position: relative; overflow: hidden; }
    .product-card:hover { background: var(--surface2); }
    .product-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--accent); transform: scaleX(0); transform-origin: left; transition: transform 0.25s cubic-bezier(0.16,1,0.3,1); z-index: 2; }
    .product-card:hover::before { transform: scaleX(1); }
    
    .card-img-wrap { width: 100%; height: 200px; background: var(--surface2); overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center; }
    .card-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: opacity 0.3s, transform 0.5s; }
    .product-card:hover .card-img { opacity: 1; transform: scale(1.05); }
    .card-no-img { font-family: var(--mono); font-size: 0.7rem; color: var(--muted); letter-spacing: 0.1em; }
    
    .card-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
    .card-name { font-size: 1.05rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text); line-height: 1.3; margin-bottom: 0.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .card-name a { color: var(--text); text-decoration: none; transition: color 0.2s; }
    .card-name a::after { content: ''; position: absolute; inset: 0; z-index: 1; }
    .card-name a:hover { color: var(--accent); }
    .card-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.6; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 1.25rem; }
    .card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid var(--border2); }
    .card-price { font-family: var(--mono); font-size: 0.9rem; font-weight: 700; color: var(--teal); }
    .card-actions { display: flex; gap: 0.4rem; }

    .table-wrap { border: 1px solid var(--border); border-radius: 4px; overflow: hidden; animation: fadeUp 0.4s 0.14s ease both; }
    .table-wrap.is-hidden { display: none; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--surface); border-bottom: 1px solid var(--border); }
    th { font-family: var(--mono); font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--muted); padding: 0.9rem 1.25rem; text-align: left; white-space: nowrap; cursor: pointer; user-select: none; transition: color 0.2s; }
    th:hover { color: var(--text); }
    th.sort-asc::after  { content: ' ↑'; color: var(--accent); font-style: normal; } th.sort-desc::after { content: ' ↓'; color: var(--accent); font-style: normal; }
    th.no-sort { cursor: default; } th.no-sort:hover { color: var(--muted); }
    th:last-child  { width: 160px; text-align: center; }
    th.price-col   { width: 150px; }
    th.img-col { width: 80px; text-align: center; }
    tbody tr { border-bottom: 1px solid var(--border2); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; } tbody tr:hover { background: var(--surface2); }
    td { padding: 1rem 1.25rem; vertical-align: middle; font-size: 0.88rem; }
    
    .td-img-wrap { width: 40px; height: 40px; border-radius: 3px; background: var(--surface2); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border); }
    .td-img { width: 100%; height: 100%; object-fit: cover; }
    .td-no-img { font-family: var(--mono); font-size: 0.5rem; color: var(--muted); }

    .td-name { font-weight: 700; }
    .td-name a { color: var(--text); text-decoration: none; transition: color 0.2s; }
    .td-name a:hover { color: var(--accent); }
    .td-desc { color: var(--muted); font-size: 0.8rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .td-price { font-family: var(--mono); font-size: 0.82rem; color: var(--teal); font-weight: 700; white-space: nowrap; }
    .td-actions { text-align: center; } .action-row { display: flex; gap: 0.4rem; justify-content: center; }

    .btn-edit { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.06em; color: var(--accent); background: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 3px; padding: 0.32rem 0.7rem; text-decoration: none; transition: background 0.2s, border-color 0.2s; white-space: nowrap; position: relative; z-index: 2; }
    .btn-edit:hover { background: rgba(37, 99, 235, 0.16); border-color: rgba(37, 99, 235, 0.5); }
    .btn-delete { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.06em; color: var(--danger); background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.18); border-radius: 3px; padding: 0.32rem 0.7rem; text-decoration: none; transition: background 0.2s, border-color 0.2s; white-space: nowrap; position: relative; z-index: 2; }
    .btn-delete:hover { background: rgba(239, 68, 68, 0.14); border-color: rgba(239, 68, 68, 0.45); }

    .empty-state { padding: 5rem 2rem; text-align: center; }
    .empty-icon { font-family: var(--mono); font-size: 2rem; color: #2a2a2a; margin-bottom: 1.25rem; }
    .empty-title { font-size: 1.1rem; font-weight: 700; color: #3a3a3a; margin-bottom: 0.4rem; }
    .empty-sub { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); }
    .footer-bar { display: flex; align-items: center; justify-content: space-between; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border2); }
    .footer-note { font-family: var(--mono); font-size: 0.62rem; color: #2e2e2e; letter-spacing: 0.08em; }
    .is-filtered { display: none !important; }

    /* Modal */
    .cyber-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
    .cyber-modal-backdrop.is-open { opacity: 1; pointer-events: all; }
    .cyber-modal { background: var(--surface); border: 1px solid var(--border); border-radius: 4px; width: 100%; max-width: 380px; margin: 1rem; position: relative; transform: translateY(20px) scale(0.97); transition: transform 0.25s cubic-bezier(0.16,1,0.3,1); overflow: hidden; display: block; }
    .cyber-modal-backdrop.is-open .cyber-modal { transform: translateY(0) scale(1); }
    .cyber-modal-strip { height: 3px; background: var(--danger); }
    .cyber-modal-body { padding: 1.75rem 1.75rem 1rem; }
    .cyber-modal-tag { font-family: var(--mono); font-size: 0.62rem; letter-spacing: 0.14em; color: var(--danger); background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 2px; display: inline-block; padding: 0.22rem 0.55rem; margin-bottom: 0.9rem; text-transform: uppercase; }
    .cyber-modal-title { font-size: 1.25rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text); margin-bottom: 0.45rem; }
    .cyber-modal-desc { font-family: var(--mono); font-size: 0.7rem; color: var(--muted); line-height: 1.65; letter-spacing: 0.02em; }
    .cyber-modal-product-name { display: block; font-family: var(--mono); font-size: 0.72rem; color: var(--text); background: var(--bg); border: 1px solid var(--border); border-left: 2px solid var(--danger); border-radius: 3px; padding: 0.6rem 0.85rem; margin-top: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cyber-modal-footer { display: flex; gap: 0.75rem; padding: 1.25rem 1.75rem 1.75rem; }
    .cyber-modal-btn-cancel { flex: 1; background: transparent; border: 1px solid var(--border); border-radius: 3px; color: var(--muted); font-family: var(--mono); font-size: 0.72rem; letter-spacing: 0.08em; padding: 0.72rem; cursor: pointer; transition: color 0.2s, border-color 0.2s; } .cyber-modal-btn-cancel:hover { color: var(--text); border-color: #4a4a4a; }
    .cyber-modal-btn-confirm { flex: 1; background: var(--danger); border: none; border-radius: 3px; color: #fff; font-family: var(--mono); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.72rem; cursor: pointer; transition: background 0.2s; } .cyber-modal-btn-confirm:hover { background: #cc3333; }
</style>

<div class="page">
    <!-- Topbar -->
    <div class="topbar">
        <div>
            <p class="sys-label">// Hệ Thống Quản Lý Sản Phẩm</p>
            <h1 class="page-title">Quản Lý <span>Sản Phẩm</span></h1>
            <div class="meta-row">
                <span class="meta-item">Tổng: <strong><?php echo count($products); ?></strong> mục</span>
                <span class="meta-item">Trạng thái: <strong>Hoạt động</strong></span>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/Product/add" class="btn-add">
            <span class="corner-tr"></span>
            <span class="corner-bl"></span>
            <span class="btn-icon">+</span> Thêm sản phẩm
        </a>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="search-wrap">
            <span class="search-icon">⌕</span>
            <input type="text" class="search-input" id="searchInput"
                   placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
        </div>

        <select class="sort-select" id="sortSelect">
            <option value="default">Sắp xếp mặc định</option>
            <option value="name-asc">Tên A → Z</option>
            <option value="name-desc">Tên Z → A</option>
            <option value="price-asc">Giá thấp → cao</option>
            <option value="price-desc">Giá cao → thấp</option>
        </select>

        <span class="result-count">
            Hiển thị <strong id="visibleCount"><?php echo count($products); ?></strong> kết quả
        </span>

        <div class="view-toggle">
            <button class="view-btn active" id="btnCard" title="Dạng thẻ">⊞</button>
            <button class="view-btn"        id="btnTable" title="Dạng bảng">☰</button>
        </div>
    </div>

    <!-- ══ CARD GRID ══ -->
    <div class="card-grid" id="cardGrid">
        <?php if (empty($products)): ?>
            <div class="empty-state" style="grid-column:1/-1">
                <div class="empty-icon">[ _ ]</div>
                <p class="empty-title">Chưa có sản phẩm nào</p>
                <p class="empty-sub">// Nhấn "Thêm sản phẩm" để bắt đầu</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="product-card"
                     data-search="<?php echo htmlspecialchars(mb_strtolower($p->getName().' '.$p->getDescription().' '.($p->getCategory() ?? ''), 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                     data-name="<?php echo htmlspecialchars(mb_strtolower($p->getName(), 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                     data-price="<?php echo $p->getPrice(); ?>">
                     
                    <!-- Khối ảnh -->
                    <div class="card-img-wrap">
                        <?php 
                            $imgSrc = $p->getImage();
                            if ($imgSrc): 
                                if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                                    $imgSrc = ltrim($imgSrc, '/');
                                    if (strpos($imgSrc, 'webbanhang/') === 0) {
                                        $imgSrc = substr($imgSrc, 11);
                                    }
                                    $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                                }
                        ?>
                            <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>" class="card-img">
                        <?php else: ?>
                            <span class="card-no-img">CHƯA CÓ ẢNH</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-content">
                        <div style="margin-bottom: 0.4rem;">
                            <span class="modal-tag" style="margin-bottom: 0; display: inline-block; font-size: 0.6rem; border-color: var(--accent); background: rgba(37, 99, 235, 0.08); color: var(--accent);"><?php echo $p->getCategory() ? htmlspecialchars($p->getCategory(), ENT_QUOTES, 'UTF-8') : 'Khác'; ?></span>
                        </div>
                        <h2 class="card-name">
                            <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $p->getID(); ?>">
                                <?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h2>
                        <p class="card-desc"><?php echo htmlspecialchars($p->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="card-footer">
                            <span class="card-price"><?php echo number_format($p->getPrice(), 0, '.', ','); ?> VNĐ</span>
                            <div class="card-actions">
                                <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $p->getID(); ?>" class="btn-edit">Sửa</a>
                                <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $p->getID(); ?>" class="btn-delete" onclick="openDeleteModal(this); return false;">Xóa</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ══ TABLE VIEW ══ -->
    <div class="table-wrap is-hidden" id="tableWrap">
        <table>
            <thead>
                <tr>
                    <th class="img-col no-sort">Ảnh</th>
                    <th data-sort="name">Tên sản phẩm</th>
                    <th class="no-sort">Danh mục</th>
                    <th class="no-sort">Mô tả</th>
                    <th class="price-col" data-sort="price">Đơn giá</th>
                    <th class="no-sort">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($products)): ?>
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">[ _ ]</div>
                            <p class="empty-title">Chưa có sản phẩm nào</p>
                            <p class="empty-sub">// Nhấn "Thêm sản phẩm" để bắt đầu</p>
                        </div>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr data-search="<?php echo htmlspecialchars(mb_strtolower($p->getName().' '.$p->getDescription().' '.($p->getCategory() ?? ''), 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                            data-name="<?php echo htmlspecialchars(mb_strtolower($p->getName(), 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                            data-price="<?php echo $p->getPrice(); ?>">
                            
                            <!-- Cột ảnh -->
                            <td>
                                <div class="td-img-wrap">
                                    <?php 
                                        $imgSrc = $p->getImage();
                                        if ($imgSrc): 
                                            if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                                                $imgSrc = ltrim($imgSrc, '/');
                                                if (strpos($imgSrc, 'webbanhang/') === 0) {
                                                    $imgSrc = substr($imgSrc, 11);
                                                }
                                                $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                                            }
                                    ?>
                                        <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" class="td-img">
                                    <?php else: ?>
                                        <span class="td-no-img">TRỐNG</span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="td-name">
                                <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $p->getID(); ?>">
                                    <?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </td>
                            <td class="td-category"><span class="modal-tag" style="margin-bottom: 0; border-color: var(--accent); background: rgba(37, 99, 235, 0.08); color: var(--accent);"><?php echo $p->getCategory() ? htmlspecialchars($p->getCategory(), ENT_QUOTES, 'UTF-8') : 'Khác'; ?></span></td>
                            <td class="td-desc"><?php echo htmlspecialchars($p->getDescription(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="td-price"><?php echo number_format($p->getPrice(), 0, '.', ','); ?> VNĐ</td>
                            <td class="td-actions">
                                <div class="action-row">
                                    <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $p->getID(); ?>" class="btn-edit">Sửa</a>
                                    <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $p->getID(); ?>" class="btn-delete" onclick="openDeleteModal(this); return false;">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer bar -->
    <div class="footer-bar">
        <span class="footer-note">HỆ THỐNG © <?php echo date('Y'); ?> — QUẢN LÝ SẢN PHẨM</span>
        <span class="footer-note">v1.0.0</span>
    </div>

    <!-- ══ DELETE MODAL ══ -->
    <div class="cyber-modal-backdrop" id="deleteModal">
        <div class="cyber-modal">
            <div class="cyber-modal-strip"></div>
            <div class="cyber-modal-body">
                <span class="cyber-modal-tag">! Cảnh báo</span>
                <h2 class="cyber-modal-title">Xóa sản phẩm</h2>
                <p class="cyber-modal-desc">Hành động này không thể hoàn tác.<br>Sản phẩm sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
                <span class="cyber-modal-product-name" id="modalProductName">—</span>
            </div>
            <div class="cyber-modal-footer">
                <button class="cyber-modal-btn-cancel" id="modalCancel">Hủy bỏ</button>
                <button class="cyber-modal-btn-confirm" id="modalConfirm">Xóa ngay</button>
            </div>
        </div>
    </div>

</div>

<script>
(function () {
    const searchInput  = document.getElementById('searchInput');
    const sortSelect   = document.getElementById('sortSelect');
    const visibleCount = document.getElementById('visibleCount');
    const cardGrid     = document.getElementById('cardGrid');
    const tableWrap    = document.getElementById('tableWrap');
    const btnCard      = document.getElementById('btnCard');
    const btnTable     = document.getElementById('btnTable');

    let currentView = localStorage.getItem('pv') || 'card';

    function setView(v) {
        currentView = v;
        localStorage.setItem('pv', v);
        const isCard = v === 'card';
        cardGrid.classList.toggle('is-hidden', !isCard);
        tableWrap.classList.toggle('is-hidden', isCard);
        btnCard.classList.toggle('active', isCard);
        btnTable.classList.toggle('active', !isCard);
        applyAll();
    }
    btnCard.addEventListener('click',  () => setView('card'));
    btnTable.addEventListener('click', () => setView('table'));

    function applyAll() {
        const q    = searchInput.value.toLowerCase().trim();
        const sort = sortSelect.value;
        const cards = [...cardGrid.querySelectorAll('.product-card')];
        const rows  = [...tableWrap.querySelectorAll('tbody tr[data-search]')];

        cards.forEach(el => el.classList.toggle('is-filtered', q !== '' && !el.dataset.search.includes(q)));
        rows.forEach( el => el.classList.toggle('is-filtered', q !== '' && !el.dataset.search.includes(q)));

        if (sort !== 'default') {
            const [key, dir] = sort.split('-');
            const asc = dir === 'asc';
            const cmp = (a, b) => {
                const va = key === 'price' ? parseFloat(a.dataset.price) : a.dataset.name;
                const vb = key === 'price' ? parseFloat(b.dataset.price) : b.dataset.name;
                if (va < vb) return asc ? -1 : 1;
                if (va > vb) return asc ?  1 : -1;
                return 0;
            };
            cards.sort(cmp).forEach(el => cardGrid.appendChild(el));
            const tbody = tableWrap.querySelector('tbody');
            rows.sort(cmp).forEach(el => tbody.appendChild(el));
        }

        const total   = cards.length;
        const visible = cards.filter(el => !el.classList.contains('is-filtered')).length;
        visibleCount.textContent = (q || sort !== 'default') ? visible + '/' + total : total;

        tableWrap.querySelectorAll('th[data-sort]').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
            if (sort.startsWith(th.dataset.sort)) {
                th.classList.add(sort.endsWith('asc') ? 'sort-asc' : 'sort-desc');
            }
        });
    }

    searchInput.addEventListener('input', applyAll);
    sortSelect.addEventListener('change', applyAll);

    tableWrap.querySelectorAll('th[data-sort]').forEach(th => {
        th.addEventListener('click', () => {
            const col = th.dataset.sort;
            const cur = sortSelect.value;
            if (cur === col + '-asc') sortSelect.value = col + '-desc';
            else if (cur === col + '-desc') sortSelect.value = 'default';
            else sortSelect.value = col + '-asc';
            applyAll();
        });
    });

    setView(currentView);
})();

    let deleteTarget = null;
    function openDeleteModal(link) {
        deleteTarget = link.href;
        const name = link.closest('[data-search]')?.dataset?.name || '—';
        document.getElementById('modalProductName').textContent = name;
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
