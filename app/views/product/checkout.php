<?php include 'app/views/shares/header.php'; ?>
<style>
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .page { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; padding: 2rem 1rem; animation: fadeUp 0.4s ease both; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    
    .sys-label { font-family: var(--mono); font-size: 0.65rem; color: var(--accent); letter-spacing: 0.16em; text-transform: uppercase; margin-bottom: 0.4rem; }
    .page-title { font-size: 2.5rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1; margin-bottom: 2rem; } .page-title span { color: var(--accent); }

    .checkout-grid { display: grid; grid-template-columns: 1fr 420px; gap: 2.5rem; }
    @media (max-width: 900px) { .checkout-grid { grid-template-columns: 1fr; } }

    /* Layout Sections */
    .section-title { font-family: var(--sans); font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: flex-end; }
    .checkout-block { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 2rem; margin-bottom: 2rem; }
    
    /* Form */
    .form-row { display: flex; gap: 1rem; margin-bottom: 1.25rem; }
    .form-row > * { flex: 1; }
    .form-group { margin-bottom: 1.25rem; position: relative; }
    .form-group.error .form-control { border-color: var(--danger) !important; }
    .form-group.error::after { content: attr(data-error); position: absolute; bottom: -20px; left: 0; color: var(--danger); font-size: 0.75rem; font-family: var(--sans); }
    .form-group.success .form-control { border-color: var(--teal) !important; }

    .form-label { display: block; font-family: var(--mono); font-size: 0.75rem; color: var(--muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 600; }
    .form-control { width: 100%; background: var(--surface2); border: 1px solid var(--border2); border-radius: 4px; color: var(--text); padding: 0.85rem 1rem; font-family: var(--sans); font-size: 0.95rem; line-height: 1.5; box-sizing: border-box; transition: all 0.2s; outline: none; }
    .form-control:focus { border-color: var(--accent); background: var(--surface); box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.1); }
    .form-control:disabled { background: var(--surface); color: var(--muted); opacity: 0.6; cursor: not-allowed; border-color: var(--border); }
    textarea.form-control { resize: vertical; min-height: 80px; }
    select.form-control { height: 48px; padding: 0 2.5rem 0 1rem; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23707A8A%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto; }

    /* Checkbox & Radios */
    .check-wrap { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; color: var(--muted); margin-top: 1rem; }
    .check-wrap input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
    
    .radio-card-list { display: flex; flex-direction: column; gap: 1rem; }
    .radio-card { display: block; position: relative; cursor: pointer; }
    .radio-card input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .rc-inner { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: var(--surface2); border: 1px solid var(--border); border-radius: 6px; transition: all 0.2s; }
    .radio-card:hover .rc-inner { border-color: var(--border2); }
    .radio-card input:checked ~ .rc-inner { border-color: var(--accent); background: rgba(88, 166, 255, 0.05); }
    .radio-card input:checked ~ .rc-inner .rc-check { border-color: var(--accent); background: var(--accent); }
    .radio-card input:checked ~ .rc-inner .rc-check::after { display: block; }
    .rc-check { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--border2); position: relative; transition: all 0.2s; flex-shrink: 0; }
    .rc-check::after { content: ''; position: absolute; top: 5px; left: 5px; width: 6px; height: 6px; border-radius: 50%; background: white; display: none; }
    .rc-content { flex-grow: 1; }
    .rc-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem; }
    .rc-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.4; }
    .rc-price { font-family: var(--mono); font-weight: 700; color: var(--text); }

    /* Summary Side */
    .summary-card { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 2rem; position: sticky; top: 2rem; }
    
    .summary-items { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; max-height: 350px; overflow-y: auto; padding-right: 0.5rem; }
    .summary-items::-webkit-scrollbar { width: 4px; }
    .summary-items::-webkit-scrollbar-track { background: var(--surface2); border-radius: 4px; }
    .summary-items::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }
    .summary-item { display: flex; gap: 1rem; align-items: center; }
    .s-item-img { width: 64px; height: 64px; background: var(--surface2); border: 1px solid var(--border); border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; position: relative; }
    .s-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .s-item-qty { position: absolute; top: -5px; right: -5px; background: var(--accent); color: white; font-family: var(--mono); font-size: 0.7rem; font-weight: 700; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 2px solid var(--surface); }
    .s-item-info { flex-grow: 1; }
    .s-item-name { font-size: 0.95rem; font-weight: 700; color: var(--text); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .s-item-price { font-family: var(--mono); font-size: 0.9rem; font-weight: 700; color: var(--text); text-align: right; white-space: nowrap; }

    /* Discount section */
    .discount-wrap { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; }
    .discount-wrap input { flex-grow: 1; margin: 0; text-transform: uppercase; }
    .btn-apply { background: var(--surface2); color: var(--text); border: 1px solid var(--border); border-radius: 4px; padding: 0 1.25rem; font-weight: 700; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .btn-apply:hover { background: var(--border); }
    .discount-msg { font-size: 0.8rem; color: var(--teal); margin-top: -1rem; margin-bottom: 1.5rem; display: none; }

    .summary-totals { margin-bottom: 1.5rem; }
    .s-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--muted); }
    .s-row.discount-row { color: var(--teal); display: none; }
    .s-row.total { font-size: 1.4rem; font-weight: 800; color: var(--accent); border-top: 1px solid var(--border); padding-top: 1.25rem; margin-top: 0.75rem; }
    .s-row.total .s-label { color: var(--text); font-family: var(--sans); }

    .btn-action { display: block; width: 100%; background: var(--accent); color: #ffffff; border: none; font-family: var(--sans); font-size: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem; border-radius: 4px; transition: background 0.2s, transform 0.15s; text-align: center; cursor: pointer; text-decoration: none; }
    .btn-action:hover { background: var(--accent-dim); transform: translateY(-2px); color: #ffffff; text-decoration: none; }
    .btn-action:disabled { background: var(--muted); cursor: not-allowed; transform: none; }
</style>

<div class="page mt-4">
    <p class="sys-label">// Checkout Process</p>
    <h1 class="page-title">Hoàn Tất <span>Đơn Hàng</span></h1>

    <form method="POST" action="<?php echo BASE_URL; ?>/Product/processCheckout" id="checkout-form">
        <div class="checkout-grid">
            <!-- Left Column -->
            <div>
                <!-- 1. Thông tin liên hệ -->
                <h3 class="section-title">1. Thông tin liên hệ</h3>
                <div class="checkout-block">
                    <div class="form-row">
                        <div class="form-group" id="fg-name">
                            <label class="form-label">Họ Tên *</label>
                            <input type="text" id="name" name="name" class="form-control" required placeholder="Nhập họ tên của bạn">
                        </div>
                        <div class="form-group" id="fg-phone">
                            <label class="form-label">Số Điện Thoại *</label>
                            <input type="tel" id="phone" name="phone" class="form-control" required placeholder="Ví dụ: 0912345678">
                        </div>
                    </div>
                    <div class="form-group" id="fg-email" style="margin-bottom: 0;">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email để nhận biên lai (tùy chọn)">
                    </div>
                </div>

                <!-- 2. Địa chỉ giao hàng -->
                <h3 class="section-title">2. Địa chỉ giao hàng</h3>
                <div class="checkout-block">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tỉnh / Thành phố *</label>
                            <select id="province" class="form-control" required>
                                <option value="" disabled selected>Chọn Tỉnh / Thành</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quận / Huyện *</label>
                            <select id="district" class="form-control" required disabled>
                                <option value="" disabled selected>Chọn Quận / Huyện</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phường / Xã *</label>
                            <select id="ward" class="form-control" required disabled>
                                <option value="" disabled selected>Chọn Phường / Xã</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ cụ thể *</label>
                        <input type="text" id="address_detail" class="form-control" required placeholder="Số nhà, Tên đường, Tòa nhà...">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0; margin-top: 1.5rem;">
                        <label class="form-label">Ghi chú đơn hàng</label>
                        <textarea id="notes" name="notes" class="form-control" placeholder="Thời gian nhận hàng, dặn dò shipper..."></textarea>
                    </div>

                    <!-- Hidden field to store full concatenated address -->
                    <input type="hidden" id="full_address" name="address" value="">
                    <!-- Hidden field to store JSON address for DB -->
                    <input type="hidden" id="address_json" name="address_json" value="">
                    
                    <label class="check-wrap">
                        <input type="checkbox" id="save_info">
                        <span>Lưu thông tin thanh toán cho lần sau</span>
                    </label>
                </div>

                <!-- 3. Giao hàng & Thanh toán -->
                <h3 class="section-title">3. Vận chuyển & Thanh toán</h3>
                <div class="checkout-block">
                    <label class="form-label" style="margin-bottom: 1rem;">Phương thức giao hàng</label>
                    <div class="radio-card-list" style="margin-bottom: 2rem;">
                        <label class="radio-card">
                            <input type="radio" name="shipping_method" value="standard" data-fee="0" checked onchange="calculateTotal()">
                            <div class="rc-inner">
                                <div class="rc-check"></div>
                                <div class="rc-content">
                                    <div class="rc-title">Giao Hàng Tiêu Chuẩn</div>
                                    <div class="rc-desc">Nhận hàng trong 3-5 ngày làm việc.</div>
                                </div>
                                <div class="rc-price">Miễn phí</div>
                            </div>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="shipping_method" value="express" data-fee="35000" onchange="calculateTotal()">
                            <div class="rc-inner">
                                <div class="rc-check"></div>
                                <div class="rc-content">
                                    <div class="rc-title">Giao Hàng Hỏa Tốc</div>
                                    <div class="rc-desc">Nhận hàng trong vòng 24H (Nội thành).</div>
                                </div>
                                <div class="rc-price">35,000đ</div>
                            </div>
                        </label>
                    </div>

                    <!-- Hidden field for shipping fee -->
                    <input type="hidden" id="shipping_fee" name="shipping_fee" value="0">

                    <label class="form-label" style="margin-bottom: 1rem;">Phương thức thanh toán</label>
                    <div class="radio-card-list">
                        <label class="radio-card">
                            <input type="radio" name="payment_method" value="COD" checked>
                            <div class="rc-inner">
                                <div class="rc-check"></div>
                                <div class="rc-content">
                                    <div class="rc-title">Thanh toán khi nhận hàng (COD)</div>
                                </div>
                            </div>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="payment_method" value="BANK">
                            <div class="rc-inner">
                                <div class="rc-check"></div>
                                <div class="rc-content">
                                    <div class="rc-title">Chuyển khoản Ngân Hàng (VietQR)</div>
                                    <div class="rc-desc">Hệ thống duyệt tự động ngay lập tức.</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <label class="check-wrap" style="margin-bottom: 2rem;">
                    <input type="checkbox" id="terms" required>
                    <span>Tôi đã đọc và đồng ý với các <a href="#" style="color:var(--accent);">Điều khoản & Chính sách</a> của website.</span>
                </label>
            </div>

            <!-- Right Column: Summary -->
            <div>
                <div class="summary-card">
                    <h3 class="section-title" style="margin-top: -0.5rem; font-size: 1.15rem; border-bottom: none; margin-bottom: 1rem;">Đơn Hàng Của Bạn</h3>
                    
                    <div class="summary-items">
                        <?php 
                        $cartSubtotal = 0;
                        foreach ($cart as $id => $item): 
                            $itemTotal = $item['price'] * $item['quantity'];
                            $cartSubtotal += $itemTotal;
                            
                            $imgSrc = $item['image'];
                            if ($imgSrc && !filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                                $imgSrc = ltrim($imgSrc, '/');
                                if (strpos($imgSrc, 'webbanhang/') === 0) {
                                    $imgSrc = substr($imgSrc, 11);
                                }
                                $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                            }
                        ?>
                        <div class="summary-item">
                            <div class="s-item-img">
                                <?php if ($imgSrc): ?>
                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <span class="s-item-qty"><?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="s-item-info">
                                <div class="s-item-name"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                            <div class="s-item-price"><?php echo number_format($itemTotal, 0, '.', ','); ?>đ</div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Discount Box -->
                    <div class="discount-wrap">
                        <input type="text" id="discount_input" class="form-control" placeholder="Mã giảm giá (VD: SALE50K)">
                        <button type="button" class="btn-apply" onclick="applyDiscount()">ÁP DỤNG</button>
                    </div>
                    <div id="discount_msg" class="discount-msg">Đã áp dụng mã giảm giá!</div>

                    <!-- Hidden fields for discount to send to server -->
                    <input type="hidden" id="discount_code" name="discount_code" value="">
                    <input type="hidden" id="discount_amount" name="discount_amount" value="0">

                    <div class="summary-totals">
                        <div class="s-row">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($cartSubtotal, 0, '.', ','); ?>đ</span>
                        </div>
                        <div class="s-row">
                            <span>Phí vận chuyển</span>
                            <span id="txt-shipping">0đ</span>
                        </div>
                        <div class="s-row discount-row" id="row-discount">
                            <span>Giảm giá</span>
                            <span id="txt-discount">-0đ</span>
                        </div>
                        <div class="s-row total">
                            <span class="s-label">Tổng cộng</span>
                            <span id="txt-total"><?php echo number_format($cartSubtotal, 0, '.', ','); ?> VNĐ</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-action" id="btn-submit">ĐẶT HÀNG NGAY</button>
                    <a href="<?php echo BASE_URL; ?>/Product/cart" style="display: block; text-align: center; margin-top: 1.25rem; color: var(--muted); font-size: 0.85rem; text-decoration: none;">← Trở về giỏ hàng</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Scripts for Logic -->
<script>
    const subtotal = <?php echo $cartSubtotal; ?>;
    let currentShippingFee = 0;
    let currentDiscountAmt = 0;
    let currentDiscountCode = '';

    // API Provinces VN (esgoo.net is more stable)
    const apiProvinces = 'https://esgoo.net/api-tinhthanh/1/0.htm';
    const apiDistricts = 'https://esgoo.net/api-tinhthanh/2/';
    const apiWards = 'https://esgoo.net/api-tinhthanh/3/';

    async function loadProvinces() {
        try {
            const res = await fetch(apiProvinces);
            const json = await res.json();
            const data = json.data;
            const provinceSelect = document.getElementById('province');
            data.forEach(p => {
                let opt = document.createElement('option');
                opt.value = p.full_name;
                opt.dataset.id = p.id;
                opt.text = p.full_name;
                provinceSelect.add(opt);
            });
            
            // If we have saved data, trigger load
            loadSavedInfo();
        } catch(e) { console.error('Lỗi load API tỉnh thành', e); }
    }

    document.getElementById('province').addEventListener('change', async function() {
        const pId = this.options[this.selectedIndex].dataset.id;
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        
        districtSelect.innerHTML = '<option value="" disabled selected>Chọn Quận / Huyện</option>';
        wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
        wardSelect.disabled = true;

        if(!pId) return;
        districtSelect.disabled = false;
        
        try {
            const res = await fetch(apiDistricts + pId + '.htm');
            const json = await res.json();
            json.data.forEach(d => {
                let opt = document.createElement('option');
                opt.value = d.full_name;
                opt.dataset.id = d.id;
                opt.text = d.full_name;
                districtSelect.add(opt);
            });
        } catch(e) {}
    });

    document.getElementById('district').addEventListener('change', async function() {
        const dId = this.options[this.selectedIndex].dataset.id;
        const wardSelect = document.getElementById('ward');
        
        wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
        
        if(!dId) return;
        wardSelect.disabled = false;

        try {
            const res = await fetch(apiWards + dId + '.htm');
            const json = await res.json();
            json.data.forEach(w => {
                let opt = document.createElement('option');
                opt.value = w.full_name;
                opt.text = w.full_name;
                wardSelect.add(opt);
            });
        } catch(e) {}
    });

    // Real-time Validation (Phone)
    const phoneInput = document.getElementById('phone');
    const fgPhone = document.getElementById('fg-phone');
    phoneInput.addEventListener('input', function() {
        let val = this.value.replace(/\D/g, '');
        this.value = val; // Only numbers
        if(val.length > 0) {
            if(val.length < 10 || val.length > 11 || !val.startsWith('0')) {
                fgPhone.classList.add('error');
                fgPhone.classList.remove('success');
                fgPhone.setAttribute('data-error', 'SĐT phải từ 10-11 số và bắt đầu bằng số 0');
            } else {
                fgPhone.classList.remove('error');
                fgPhone.classList.add('success');
            }
        } else {
            fgPhone.classList.remove('error', 'success');
        }
    });

    // Calculation logic
    function calculateTotal() {
        // Get shipping fee
        const shipRadios = document.getElementsByName('shipping_method');
        for (let r of shipRadios) {
            if(r.checked) {
                currentShippingFee = parseInt(r.dataset.fee);
                document.getElementById('shipping_fee').value = currentShippingFee;
                document.getElementById('txt-shipping').innerText = currentShippingFee === 0 ? 'Miễn phí' : currentShippingFee.toLocaleString() + 'đ';
            }
        }
        
        let total = subtotal + currentShippingFee - currentDiscountAmt;
        if(total < 0) total = 0;
        
        document.getElementById('txt-total').innerText = total.toLocaleString() + ' VNĐ';
    }

    // Fake Discount Logic
    function applyDiscount() {
        const input = document.getElementById('discount_input').value.trim().toUpperCase();
        const msg = document.getElementById('discount_msg');
        const row = document.getElementById('row-discount');
        const txtDiscount = document.getElementById('txt-discount');

        if(input === 'SALE50K') {
            currentDiscountAmt = 50000;
            currentDiscountCode = 'SALE50K';
            msg.innerText = 'Áp dụng thành công mã giảm 50,000đ!';
            msg.style.color = 'var(--teal)';
            msg.style.display = 'block';
            
            row.style.display = 'flex';
            txtDiscount.innerText = '-50,000đ';
        } else if(input === 'FREESHIP' && currentShippingFee > 0) {
            currentDiscountAmt = currentShippingFee;
            currentDiscountCode = 'FREESHIP';
            msg.innerText = 'Áp dụng thành công mã Freeship!';
            msg.style.color = 'var(--teal)';
            msg.style.display = 'block';
            
            row.style.display = 'flex';
            txtDiscount.innerText = '-' + currentDiscountAmt.toLocaleString() + 'đ';
        } else if(input === '') {
            currentDiscountAmt = 0;
            currentDiscountCode = '';
            msg.style.display = 'none';
            row.style.display = 'none';
        } else {
            currentDiscountAmt = 0;
            currentDiscountCode = '';
            msg.innerText = 'Mã giảm giá không hợp lệ hoặc đã hết hạn!';
            msg.style.color = 'var(--danger)';
            msg.style.display = 'block';
            row.style.display = 'none';
        }

        document.getElementById('discount_code').value = currentDiscountCode;
        document.getElementById('discount_amount').value = currentDiscountAmt;
        calculateTotal();
    }

    // Local Storage logic
    function saveInfo() {
        if(document.getElementById('save_info').checked) {
            const data = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                email: document.getElementById('email').value,
                province: document.getElementById('province').value,
                district: document.getElementById('district').value,
                ward: document.getElementById('ward').value,
                address_detail: document.getElementById('address_detail').value
            };
            localStorage.setItem('checkout_info', JSON.stringify(data));
        } else {
            localStorage.removeItem('checkout_info');
        }
    }

    function loadSavedInfo() {
        let data = null;
        
        // Load from DB if logged in
        <?php if (isset($accountInfo) && !empty($accountInfo)): ?>
            data = {};
            <?php if (!empty($accountInfo->fullname)): ?>data.name = <?php echo json_encode($accountInfo->fullname); ?>;<?php endif; ?>
            <?php if (!empty($accountInfo->phone)): ?>data.phone = <?php echo json_encode($accountInfo->phone); ?>;<?php endif; ?>
            <?php if (!empty($accountInfo->email)): ?>data.email = <?php echo json_encode($accountInfo->email); ?>;<?php endif; ?>
            
            <?php if (!empty($accountInfo->address)): ?>
                try {
                    const dbAddress = JSON.parse(<?php echo json_encode($accountInfo->address); ?>);
                    data.province = dbAddress.province;
                    data.district = dbAddress.district;
                    data.ward = dbAddress.ward;
                    data.address_detail = dbAddress.address_detail;
                } catch(e) {}
            <?php endif; ?>
        <?php endif; ?>

        if (!data) {
            const saved = localStorage.getItem('checkout_info');
            if(saved) data = JSON.parse(saved);
        }

        if(data) {
            document.getElementById('save_info').checked = true;
            if(data.name) document.getElementById('name').value = data.name;
            if(data.phone) { document.getElementById('phone').value = data.phone; document.getElementById('phone').dispatchEvent(new Event('input')); }
            if(data.email) document.getElementById('email').value = data.email;
            if(data.address_detail) document.getElementById('address_detail').value = data.address_detail;
            
            // A bit complex to set province/district/ward dropdowns correctly due to async load
            if(data.province) {
                let pSelect = document.getElementById('province');
                for(let i=0; i<pSelect.options.length; i++) {
                    if(pSelect.options[i].value === data.province) { pSelect.selectedIndex = i; break; }
                }
                pSelect.dispatchEvent(new Event('change'));
                
                if(data.district) {
                    setTimeout(() => {
                        let dSelect = document.getElementById('district');
                        for(let i=0; i<dSelect.options.length; i++) {
                            if(dSelect.options[i].value === data.district) { dSelect.selectedIndex = i; break; }
                        }
                        dSelect.dispatchEvent(new Event('change'));
                        
                        if(data.ward) {
                            setTimeout(() => {
                                let wSelect = document.getElementById('ward');
                                for(let i=0; i<wSelect.options.length; i++) {
                                    if(wSelect.options[i].value === data.ward) { wSelect.selectedIndex = i; break; }
                                }
                            }, 500); // give fetch time
                        }
                    }, 500);
                }
            }
        }
    }

    // Form Submission Interception
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Prevent if phone is invalid
        if(fgPhone.classList.contains('error')) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại Số điện thoại!');
            phoneInput.focus();
            return;
        }

        // Concatenate address
        const detail = document.getElementById('address_detail').value.trim();
        const w = document.getElementById('ward').value;
        const d = document.getElementById('district').value;
        const p = document.getElementById('province').value;
        
        document.getElementById('full_address').value = `${detail}, ${w}, ${d}, ${p}`;
        
        const addressData = {
            province: p,
            district: d,
            ward: w,
            address_detail: detail
        };
        document.getElementById('address_json').value = JSON.stringify(addressData);
        
        saveInfo();
    });

    // Init
    loadProvinces();
</script>

<?php include 'app/views/shares/footer.php'; ?>
