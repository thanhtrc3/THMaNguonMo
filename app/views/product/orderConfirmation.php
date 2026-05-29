<?php 
$orderId = $_SESSION['last_order_id'] ?? null;
$paymentMethod = $_SESSION['last_payment_method'] ?? 'COD';
$totalAmount = $_SESSION['last_total_amount'] ?? 0;
$shippingFee = $_SESSION['last_shipping_fee'] ?? 0;
$discountAmt = $_SESSION['last_discount_amount'] ?? 0;

// Clear after reading
unset($_SESSION['last_order_id']);
unset($_SESSION['last_payment_method']);
unset($_SESSION['last_total_amount']);
unset($_SESSION['last_shipping_fee']);
unset($_SESSION['last_discount_amount']);

include 'app/views/shares/header.php'; 
?>
<style>
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .page { max-width: 600px; margin: 0 auto; position: relative; z-index: 1; animation: fadeUp 0.4s ease both; padding-top: 3rem; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    
    .success-box { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 3rem 2rem; margin-bottom: 2rem; text-align: center; }
    .success-icon { font-size: 4rem; color: var(--teal); margin-bottom: 1rem; line-height: 1; }
    
    .page-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 1rem; color: var(--text); }
    .success-text { font-size: 1.05rem; color: var(--muted); line-height: 1.6; margin-bottom: 2rem; }
    
    .order-meta { background: var(--surface2); border: 1px solid var(--border); border-radius: 4px; padding: 1.5rem; text-align: left; margin-bottom: 2rem; }
    .meta-row { display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.95rem; }
    .meta-row:last-child { margin-bottom: 0; border-top: 1px dashed var(--border); padding-top: 0.8rem; font-weight: 700; color: var(--accent); }
    .meta-label { color: var(--muted); font-family: var(--mono); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    .meta-val { color: var(--text); font-weight: 600; font-family: var(--mono); }

    .bank-info { background: rgba(88, 166, 255, 0.05); border: 1px solid rgba(88, 166, 255, 0.2); border-radius: 4px; padding: 1.5rem; text-align: left; margin-bottom: 2rem; }
    .bank-title { font-size: 1rem; font-weight: 700; color: var(--accent); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .bank-detail { margin-bottom: 0.5rem; font-size: 0.95rem; color: var(--text); }
    .bank-detail strong { display: inline-block; width: 120px; color: var(--muted); font-family: var(--mono); font-size: 0.8rem; text-transform: uppercase; }

    .btn-action { display: inline-block; background: var(--accent); color: #ffffff; border: none; font-family: var(--sans); font-size: 0.95rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.15rem 2.5rem; border-radius: 3px; transition: background 0.2s, transform 0.15s; text-decoration: none; cursor: pointer; width: 100%; text-align: center; }
    .btn-action:hover { background: var(--accent-dim); transform: translateY(-2px); text-decoration: none; color: #ffffff; }
</style>

<div class="page">
    <div class="success-box">
        <div class="success-icon">✓</div>
        <h1 class="page-title">Đặt Hàng Thành Công</h1>
        <p class="success-text">Cảm ơn bạn đã tin tưởng mua sắm tại hệ thống của chúng tôi.<br>Đơn hàng của bạn đang được hệ thống xử lý.</p>
        
        <?php if ($orderId): ?>
        <div class="order-meta">
            <div class="meta-row">
                <span class="meta-label">Mã Đơn Hàng</span>
                <span class="meta-val">#<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Hình Thức</span>
                <span class="meta-val"><?php echo $paymentMethod === 'BANK' ? 'Chuyển Khoản' : 'Thanh Toán Khi Nhận Hàng (COD)'; ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Phí Vận Chuyển</span>
                <span class="meta-val"><?php echo $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, '.', ',') . ' VNĐ'; ?></span>
            </div>
            <?php if($discountAmt > 0): ?>
            <div class="meta-row" style="color: var(--teal);">
                <span class="meta-label" style="color: var(--teal);">Giảm Giá</span>
                <span class="meta-val">-<?php echo number_format($discountAmt, 0, '.', ','); ?> VNĐ</span>
            </div>
            <?php endif; ?>
            <div class="meta-row">
                <span class="meta-label">Tổng Thanh Toán</span>
                <span class="meta-val"><?php echo number_format($totalAmount, 0, '.', ','); ?> VNĐ</span>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($paymentMethod === 'BANK'): ?>
        <div class="bank-info" style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap-reverse; justify-content: center;">
            <div style="flex-grow: 1; min-width: 200px;">
                <div class="bank-title">💳 Hướng dẫn chuyển khoản</div>
                <div class="bank-detail"><strong>Ngân Hàng:</strong> Vietcombank (VCB)</div>
                <div class="bank-detail"><strong>Chủ TK:</strong> NGUYEN VAN A</div>
                <div class="bank-detail"><strong>Số TK:</strong> 01234567890</div>
                <div class="bank-detail"><strong>Nội Dung:</strong> DH<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?></div>
                <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--muted); line-height: 1.5;">* Vui lòng quét mã QR hoặc chuyển đúng nội dung để được duyệt tự động. Đơn sẽ bị hủy nếu chưa nhận thanh toán trong 24h.</p>
            </div>
            
            <div style="background: white; padding: 0.5rem; border-radius: 8px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <?php
                $qrUrl = "https://img.vietqr.io/image/970436-01234567890-compact2.png?amount=" . $totalAmount . "&addInfo=DH" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . "&accountName=NGUYEN VAN A";
                ?>
                <img src="<?php echo htmlspecialchars($qrUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="VietQR" style="width: 180px; height: 180px; display: block; border-radius: 4px;">
            </div>
        </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>/Product" class="btn-action">Tiếp Tục Mua Sắm</a>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>
