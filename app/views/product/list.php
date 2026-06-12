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
    .view-btn + .view-btn { border-left: 1px solid var(--border); } .view-btn.active { background: var(--accent); color: #0d0d0d; }

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
    .card-actions { display: flex; gap: 0.4rem; z-index: 10; position: relative; }

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

    .btn-cart-list { font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.06em; color: var(--accent); background: rgba(37,99,235,0.06); border: 1px solid rgba(37,99,235,0.18); border-radius: 3px; padding: 0.32rem 0.7rem; text-decoration: none; transition: background 0.2s, border-color 0.2s, color 0.2s; white-space: nowrap; position: relative; z-index: 2; cursor: pointer; }
    .btn-cart-list:hover { background: rgba(37,99,235,0.14); border-color: rgba(37,99,235,0.45); color: var(--accent); text-decoration: none; }

    .empty-state { padding: 5rem 2rem; text-align: center; grid-column: 1/-1; }
    .empty-icon { font-family: var(--mono); font-size: 2rem; color: #2a2a2a; margin-bottom: 1.25rem; }
    .empty-title { font-size: 1.1rem; font-weight: 700; color: #3a3a3a; margin-bottom: 0.4rem; }
    .empty-sub { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); }
    .footer-bar { display: flex; align-items: center; justify-content: space-between; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border2); }
    .footer-note { font-family: var(--mono); font-size: 0.62rem; color: #2e2e2e; letter-spacing: 0.08em; }
    .is-filtered { display: none !important; }
</style>

<div class="page">
    <div class="topbar">
        <div>
            <p class="sys-label">// Cửa hàng công nghệ</p>
            <h1 class="page-title">Danh Sách <span>Sản Phẩm</span></h1>
            <div class="meta-row">
                <span class="meta-item">Tổng: <strong id="totalItems">0</strong> mục</span>
                <span class="meta-item">Trạng thái: <strong>Đã kết nối API</strong></span>
            </div>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>/Product/add" class="btn-cart-list" style="font-size: 0.8rem; padding: 0.6rem 1rem;">+ Thêm Sản Phẩm</a>
        </div>
    </div>

    <div class="toolbar">
        <div class="search-wrap">
            <span class="search-icon">⌕</span>
            <input type="text" class="search-input" id="searchInput" placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
        </div>
        <select class="sort-select" id="categoryFilter">
            <option value="all">Tất cả danh mục</option>
            <!-- Sẽ được điền bằng jQuery -->
        </select>
        <select class="sort-select" id="sortSelect">
            <option value="default">Sắp xếp mặc định</option>
            <option value="name-asc">Tên A → Z</option>
            <option value="name-desc">Tên Z → A</option>
            <option value="price-asc">Giá thấp → cao</option>
            <option value="price-desc">Giá cao → thấp</option>
        </select>
        <span class="result-count">Hiển thị <strong id="visibleCount">0</strong> kết quả</span>
        <div class="view-toggle">
            <button class="view-btn active" id="btnCard" title="Dạng thẻ">⊞</button>
            <button class="view-btn" id="btnTable" title="Dạng bảng">☰</button>
        </div>
    </div>

    <!-- ══ CARD GRID ══ -->
    <div class="card-grid" id="cardGrid"></div>

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
            <tbody id="tableBody"></tbody>
        </table>
    </div>

    <div class="footer-bar">
        <span class="footer-note">CYBER STORE © <?php echo date('Y'); ?></span>
        <span class="footer-note">v1.0.0 (API Edition)</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const baseUrl = '<?php echo BASE_URL; ?>';
    
    // Tải danh mục
    $.ajax({
        url: baseUrl + '/api/category',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const catSelect = $('#categoryFilter');
            data.forEach(function(cat) {
                catSelect.append(`<option value="${cat.name}">${cat.name}</option>`);
            });
        }
    });

    // Tải sản phẩm
    function loadProducts() {
        $.ajax({
            url: baseUrl + '/api/product',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const cardGrid = $('#cardGrid');
                const tableBody = $('#tableBody');
                
                cardGrid.empty();
                tableBody.empty();
                
                $('#totalItems').text(data.length);
                $('#visibleCount').text(data.length);

                if(data.length === 0) {
                    cardGrid.append(`<div class="empty-state"><div class="empty-icon">[ _ ]</div><p class="empty-title">Chưa có sản phẩm nào</p><p class="empty-sub">// Quay lại sau nhé!</p></div>`);
                    tableBody.append(`<tr><td colspan="6"><div class="empty-state"><div class="empty-icon">[ _ ]</div><p class="empty-title">Chưa có sản phẩm nào</p></div></td></tr>`);
                    return;
                }

                data.forEach(function(product) {
                    let imgSrc = product.image ? product.image : '';
                    if (imgSrc && !imgSrc.startsWith('http')) {
                        let path = imgSrc.replace(/^\/+/, '');
                        if (path.startsWith('webbanhang/')) path = path.substring(11);
                        imgSrc = baseUrl + '/' + path;
                    }
                    
                    let categoryName = product.category_name ? product.category_name : 'Khác';
                    let priceFormatted = Number(product.price).toLocaleString('en-US') + ' VNĐ';
                    let searchText = (product.name + ' ' + (product.description || '') + ' ' + categoryName).toLowerCase();
                    
                    // Card
                    let cardHtml = `
                        <div class="product-card" data-search="${searchText}" data-name="${product.name.toLowerCase()}" data-price="${product.price}" data-category="${categoryName}">
                            <div class="card-img-wrap">
                                ${imgSrc ? `<img src="${imgSrc}" class="card-img" alt="${product.name}">` : `<span class="card-no-img">CHƯA CÓ ẢNH</span>`}
                            </div>
                            <div class="card-content">
                                <div style="margin-bottom: 0.4rem;">
                                    <span class="modal-tag" style="margin-bottom: 0; display: inline-block; font-size: 0.6rem; border-color: var(--accent); background: rgba(37, 99, 235, 0.08); color: var(--accent);">${categoryName}</span>
                                </div>
                                <h2 class="card-name"><a href="${baseUrl}/Product/show/${product.id}">${product.name}</a></h2>
                                <p class="card-desc">${product.description || ''}</p>
                                <div class="card-footer">
                                    <span class="card-price">${priceFormatted}</span>
                                    <div class="card-actions">
                                        <a href="${baseUrl}/Product/edit/${product.id}" class="btn-cart-list" style="color:var(--accent); border-color:var(--accent)">Sửa</a>
                                        <button class="btn-cart-list btn-delete" data-id="${product.id}" style="color:var(--danger); border-color:var(--danger)">Xóa</button>
                                        <a href="${baseUrl}/Product/addToCart/${product.id}" class="btn-cart-list">+ Giỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    cardGrid.append(cardHtml);

                    // Table row
                    let rowHtml = `
                        <tr class="product-row" data-search="${searchText}" data-name="${product.name.toLowerCase()}" data-price="${product.price}" data-category="${categoryName}">
                            <td>
                                <div class="td-img-wrap">
                                    ${imgSrc ? `<img src="${imgSrc}" class="td-img">` : `<span class="td-no-img">TRỐNG</span>`}
                                </div>
                            </td>
                            <td class="td-name"><a href="${baseUrl}/Product/show/${product.id}">${product.name}</a></td>
                            <td class="td-category"><span class="modal-tag" style="margin-bottom: 0; border-color: var(--accent); background: rgba(37, 99, 235, 0.08); color: var(--accent);">${categoryName}</span></td>
                            <td class="td-desc">${product.description || ''}</td>
                            <td class="td-price">${priceFormatted}</td>
                            <td class="td-actions">
                                <div class="action-row">
                                    <a href="${baseUrl}/Product/edit/${product.id}" class="btn-cart-list" style="color:var(--accent); border-color:var(--accent)">Sửa</a>
                                    <button class="btn-cart-list btn-delete" data-id="${product.id}" style="color:var(--danger); border-color:var(--danger)">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.append(rowHtml);
                });
                
                setupFiltering();
            }
        });
    }

    loadProducts();

    // Xóa sản phẩm
    $(document).on('click', '.btn-delete', function() {
        if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            const id = $(this).data('id');
            $.ajax({
                url: baseUrl + '/api/product/' + id,
                method: 'DELETE',
                success: function(res) {
                    loadProducts();
                },
                error: function() {
                    alert('Xóa thất bại');
                }
            });
        }
    });

    // Filtering logic
    function setupFiltering() {
        let currentView = localStorage.getItem('pv') || 'card';
        function setView(v) {
            currentView = v;
            localStorage.setItem('pv', v);
            const isCard = v === 'card';
            $('#cardGrid').toggleClass('is-hidden', !isCard);
            $('#tableWrap').toggleClass('is-hidden', isCard);
            $('#btnCard').toggleClass('active', isCard);
            $('#btnTable').toggleClass('active', !isCard);
        }
        
        $('#btnCard').click(() => setView('card'));
        $('#btnTable').click(() => setView('table'));
        setView(currentView);

        function applyFilters() {
            const q = $('#searchInput').val().toLowerCase().trim();
            const cat = $('#categoryFilter').val();
            const sort = $('#sortSelect').val();
            
            let visibleCards = 0;
            
            $('.product-card').each(function() {
                const el = $(this);
                const matchesSearch = q === '' || el.data('search').includes(q);
                const matchesCat = cat === 'all' || String(el.data('category')) === cat;
                if (matchesSearch && matchesCat) {
                    el.removeClass('is-filtered');
                    visibleCards++;
                } else {
                    el.addClass('is-filtered');
                }
            });

            $('.product-row').each(function() {
                const el = $(this);
                const matchesSearch = q === '' || el.data('search').includes(q);
                const matchesCat = cat === 'all' || String(el.data('category')) === cat;
                el.toggleClass('is-filtered', !(matchesSearch && matchesCat));
            });
            
            $('#visibleCount').text(visibleCards);

            if (sort !== 'default') {
                const sortArr = sort.split('-');
                const key = sortArr[0];
                const asc = sortArr[1] === 'asc';
                
                const cardGrid = $('#cardGrid');
                const cards = cardGrid.children('.product-card').get();
                cards.sort(function(a, b) {
                    const va = key === 'price' ? parseFloat($(a).data('price')) : $(a).data('name');
                    const vb = key === 'price' ? parseFloat($(b).data('price')) : $(b).data('name');
                    if (va < vb) return asc ? -1 : 1;
                    if (va > vb) return asc ? 1 : -1;
                    return 0;
                });
                $.each(cards, function(i, el) { cardGrid.append(el); });
                
                const tableBody = $('#tableBody');
                const rows = tableBody.children('.product-row').get();
                rows.sort(function(a, b) {
                    const va = key === 'price' ? parseFloat($(a).data('price')) : $(a).data('name');
                    const vb = key === 'price' ? parseFloat($(b).data('price')) : $(b).data('name');
                    if (va < vb) return asc ? -1 : 1;
                    if (va > vb) return asc ? 1 : -1;
                    return 0;
                });
                $.each(rows, function(i, el) { tableBody.append(el); });
            }
        }

        $('#searchInput').off('input').on('input', applyFilters);
        $('#categoryFilter').off('change').on('change', applyFilters);
        $('#sortSelect').off('change').on('change', applyFilters);
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
