<?php include 'app/views/shares/header.php'; ?>

<style>
    body::before {
        content: ''; position: fixed; inset: 0;
        background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px; pointer-events: none; z-index: 0;
    }
    .page { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; padding: 2rem 1rem; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    
    .topbar { margin-bottom: 2.5rem; animation: fadeUp 0.4s ease both; }
    .sys-label { font-family: var(--mono); font-size: 0.65rem; color: var(--accent); letter-spacing: 0.16em; text-transform: uppercase; margin-bottom: 0.4rem; }
    .page-title { font-size: 2.5rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1; } .page-title span { color: var(--accent); }

    /* New Layout */
    .cart-layout { display: grid; grid-template-columns: 1fr 350px; gap: 2rem; animation: fadeUp 0.4s 0.1s ease both; }
    @media (max-width: 800px) { .cart-layout { grid-template-columns: 1fr; } }

    /* Left Column: Items */
    .cart-items { display: flex; flex-direction: column; gap: 1rem; }
    .cart-item { 
        background: var(--surface); border: 1px solid var(--border); border-radius: 6px; 
        padding: 1.25rem; display: flex; align-items: center; gap: 1.5rem; transition: border-color 0.2s;
    }
    .cart-item:hover { border-color: var(--accent); background: var(--surface2); box-shadow: 0 4px 12px rgba(255,255,255,0.03); }
    
    .item-img-wrap { width: 80px; height: 80px; border-radius: 4px; background: var(--surface2); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border); flex-shrink: 0; }
    .item-img { width: 100%; height: 100%; object-fit: cover; }
    .item-no-img { font-family: var(--mono); font-size: 0.6rem; color: var(--muted); }

    .item-info { flex-grow: 1; display: flex; flex-direction: column; gap: 0.4rem; }
    .item-name { font-size: 1.1rem; font-weight: 700; color: var(--text); line-height: 1.3; }
    .item-name a { color: inherit; text-decoration: none; transition: color 0.2s; }
    .item-name a:hover { color: var(--accent); }
    .item-price { font-family: var(--mono); font-size: 0.85rem; color: var(--muted); }

    .item-actions { display: flex; align-items: center; gap: 2rem; flex-shrink: 0; }
    
    .qty-control { display: flex; align-items: center; gap: 0.5rem; background: var(--surface2); padding: 0.25rem; border-radius: 4px; border: 1px solid var(--border); }
    .btn-qty { background: var(--surface); color: var(--text); border: none; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 2px; cursor: pointer; font-family: var(--mono); font-size: 1rem; transition: background 0.2s; outline: none; }
    .btn-qty:hover { background: var(--border); }
    .qty-input { font-family: var(--mono); font-size: 0.9rem; font-weight: 700; width: 40px; text-align: center; background: transparent; border: none; color: var(--text); outline: none; -moz-appearance: textfield; }
    .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .item-total { font-family: var(--mono); font-size: 1.05rem; color: var(--teal); font-weight: 700; min-width: 120px; text-align: right; }

    .btn-remove { 
        color: var(--muted); background: transparent; border: none; font-size: 1.5rem; cursor: pointer; transition: color 0.2s; text-decoration: none; padding: 0.5rem; line-height: 1;
    }
    .btn-remove:hover { color: var(--danger); text-decoration: none; }

    /* Right Column: Sidebar */
    .cart-sidebar { position: sticky; top: 2rem; align-self: start; }
    .summary-card { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 1.75rem; }
    .summary-title { font-family: var(--sans); font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem; letter-spacing: 0.02em; padding-bottom: 1rem; border-bottom: 1px solid var(--border); }
    
    .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .summary-label { font-family: var(--mono); font-size: 0.8rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .summary-val { font-family: var(--mono); font-size: 1.3rem; color: var(--accent); font-weight: 700; text-align: right; }

    .btn-checkout-new {
        display: block; width: 100%; background: var(--accent); color: #ffffff; border: none;
        font-family: var(--sans); font-size: 0.95rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
        padding: 1.15rem; border-radius: 3px; transition: background 0.2s, transform 0.15s; text-align: center; text-decoration: none; margin-bottom: 1.5rem;
    }
    .btn-checkout-new:hover { background: var(--accent-dim); transform: translateY(-2px); text-decoration: none; color: #ffffff; }

    .sidebar-links { display: flex; flex-direction: column; gap: 0.75rem; text-align: center; }
    .sidebar-links a { font-family: var(--mono); font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--muted); text-decoration: none; transition: color 0.2s; }
    .sidebar-links a:hover { color: var(--text); }
    .sidebar-links a.text-danger:hover { color: var(--danger); }

    .empty-state { padding: 5rem 2rem; text-align: center; background: var(--surface); border: 1px solid var(--border); border-radius: 6px; animation: fadeUp 0.4s 0.1s ease both; }
    .empty-icon { font-family: var(--mono); font-size: 2.5rem; color: var(--border2); margin-bottom: 1.5rem; }
    .empty-title { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
    .empty-sub { font-family: var(--mono); font-size: 0.8rem; color: var(--muted); }
    .btn-shop { display: inline-block; background: var(--accent); color: #ffffff; font-weight: 700; text-transform: uppercase; padding: 0.9rem 2rem; border-radius: 3px; font-size: 0.85rem; margin-top: 2rem; text-decoration: none; transition: transform 0.2s; }
    .btn-shop:hover { transform: translateY(-2px); text-decoration: none; color: #ffffff; }
</style>

<div class="page">
    <div class="topbar">
        <div>
            <p class="sys-label">// Shopping Cart</p>
            <h1 class="page-title">Giỏ <span>Hàng</span></h1>
        </div>
    </div>

    <?php if (empty($cart)): ?>
        <div class="empty-state">
            <div class="empty-icon">[ 0 ]</div>
            <p class="empty-title">Giỏ hàng của bạn đang trống</p>
            <p class="empty-sub">// Hãy chọn mua một vài sản phẩm công nghệ</p>
            <a href="<?php echo BASE_URL; ?>/Product/list" class="btn-shop">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <!-- Left Column: Items -->
            <div class="cart-items">
                <?php 
                    $totalAmount = 0;
                    foreach ($cart as $id => $item): 
                        $itemTotal = $item['price'] * $item['quantity'];
                        $totalAmount += $itemTotal;
                        
                        $imgSrc = $item['image'];
                        if ($imgSrc && !filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                            $imgSrc = ltrim($imgSrc, '/');
                            if (strpos($imgSrc, 'webbanhang/') === 0) {
                                $imgSrc = substr($imgSrc, 11);
                            }
                            $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                        }
                ?>
                    <div class="cart-item">
                        <div class="item-img-wrap">
                            <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $id; ?>" style="display: block; width: 100%; height: 100%;">
                                <?php if ($item['image']): ?>
                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" class="item-img">
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                        <span class="item-no-img">N/A</span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                        
                        <div class="item-info">
                            <div class="item-name">
                                <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $id; ?>">
                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </div>
                            <div class="item-price"><?php echo number_format($item['price'], 0, '.', ','); ?> VNĐ</div>
                        </div>

                        <div class="item-actions">
                            <div class="qty-control">
                                <button class="btn-qty btn-minus" data-id="<?php echo $id; ?>">-</button>
                                <input type="number" class="qty-input" id="qty-<?php echo $id; ?>" data-id="<?php echo $id; ?>" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" min="1">
                                <button class="btn-qty btn-plus" data-id="<?php echo $id; ?>">+</button>
                            </div>
                            
                            <div class="item-total" id="total-<?php echo $id; ?>"><?php echo number_format($itemTotal, 0, '.', ','); ?> VNĐ</div>
                            
                            <a href="<?php echo BASE_URL; ?>/Product/removeFromCart/<?php echo $id; ?>" class="btn-remove" title="Xóa sản phẩm">&times;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Right Column: Summary -->
            <div class="cart-sidebar">
                <div class="summary-card">
                    <div class="summary-title">Tóm tắt đơn hàng</div>
                    <div class="summary-row">
                        <span class="summary-label">Tổng tiền:</span>
                        <strong class="summary-val" id="cart-total"><?php echo number_format($totalAmount, 0, '.', ','); ?> VNĐ</strong>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/Product/checkout" class="btn-checkout-new">Tiến hành Thanh toán</a>
                    
                    <div class="sidebar-links">
                        <a href="<?php echo BASE_URL; ?>/Product/list">← Tiếp tục mua sắm</a>
                        <a href="<?php echo BASE_URL; ?>/Product/clearCart" class="text-danger mt-3">Xóa toàn bộ giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const qtyButtons = document.querySelectorAll('.btn-qty');
    const qtyInputs = document.querySelectorAll('.qty-input');
    const cartTotalEl = document.getElementById('cart-total');
    
    qtyButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const id = btn.getAttribute('data-id');
            const isPlus = btn.classList.contains('btn-plus');
            const action = isPlus ? 'increase' : 'decrease';
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/Product/updateCartQuantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, action })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    document.getElementById('qty-' + id).value = data.newQuantity;
                    document.getElementById('total-' + id).textContent = data.itemTotal;
                    cartTotalEl.textContent = data.cartTotal;
                    
                    // Update header badge
                    const badge = document.querySelector('.nav-link .badge');
                    if (badge) {
                        badge.textContent = data.totalItems;
                    }
                }
            } catch (err) {
                console.error('Lỗi khi cập nhật giỏ hàng:', err);
            }
        });
    });

    qtyInputs.forEach(input => {
        input.addEventListener('change', async (e) => {
            const id = input.getAttribute('data-id');
            let quantity = parseInt(input.value);
            
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                input.value = 1;
            }
            
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/Product/updateCartQuantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, action: 'set', quantity })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    input.value = data.newQuantity;
                    document.getElementById('total-' + id).textContent = data.itemTotal;
                    cartTotalEl.textContent = data.cartTotal;
                    
                    const badge = document.querySelector('.nav-link .badge');
                    if (badge) {
                        badge.textContent = data.totalItems;
                    }
                }
            } catch (err) {
                console.error('Lỗi khi cập nhật giỏ hàng:', err);
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
