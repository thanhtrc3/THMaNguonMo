<?php /** @var ProductModel[] $products */ ?>
<?php /** @var CategoryModel[] $categories */ ?>
<?php include 'app/views/shares/header.php'; ?>

<?php
// Tính toán số lượng sản phẩm cho mỗi danh mục
$categoryCounts = [];
if (isset($products) && is_array($products)) {
    foreach ($products as $p) {
        $catName = $p->getCategory() ?? 'Khác';
        $categoryCounts[$catName] = ($categoryCounts[$catName] ?? 0) + 1;
    }
}

// Lọc Sản Phẩm Nổi Bật (Sắp xếp theo giá giảm dần, lấy tối đa 4 sản phẩm)
$featuredProducts = [];
if (isset($products) && is_array($products)) {
    $featuredProducts = $products;
    usort($featuredProducts, function($a, $b) {
        return $b->getPrice() <=> $a->getPrice();
    });
    $featuredProducts = array_slice($featuredProducts, 0, 4);
}

// Lọc Sản Phẩm Mới Nhất (Đảo ngược mảng sản phẩm gốc để lấy các sản phẩm thêm sau, lấy tối đa 4 sản phẩm)
$newArrivals = [];
if (isset($products) && is_array($products)) {
    $newArrivals = array_slice(array_reverse($products), 0, 4);
}

// Hàm lấy icon đại diện cho danh mục
function getCategoryIcon($catName) {
    $catNameLower = mb_strtolower($catName, 'UTF-8');
    if (strpos($catNameLower, 'linh kiện') !== false || strpos($catNameLower, 'phần cứng') !== false) {
        return '⚙️';
    } elseif (strpos($catNameLower, 'màn hình') !== false) {
        return '🖥️';
    } elseif (strpos($catNameLower, 'chuột') !== false || strpos($catNameLower, 'mouse') !== false) {
        return '🖱️';
    } elseif (strpos($catNameLower, 'bàn phím') !== false || strpos($catNameLower, 'keyboard') !== false) {
        return '⌨️';
    } elseif (strpos($catNameLower, 'tai nghe') !== false || strpos($catNameLower, 'âm thanh') !== false) {
        return '🎧';
    } elseif (strpos($catNameLower, 'laptop') !== false || strpos($catNameLower, 'máy tính') !== false) {
        return '💻';
    } elseif (strpos($catNameLower, 'điện thoại') !== false || strpos($catNameLower, 'mobile') !== false) {
        return '📱';
    } elseif (strpos($catNameLower, 'phụ kiện') !== false || strpos($catNameLower, 'dây') !== false) {
        return '🔌';
    }
    return '❖';
}
?>

<style>
    /* Grid nền Cyberpunk */
    body::before { 
        content: ''; 
        position: fixed; 
        inset: 0; 
        background-image: linear-gradient(rgba(232,255,71,0.02) 1px, transparent 1px), 
                          linear-gradient(90deg, rgba(232,255,71,0.02) 1px, transparent 1px); 
        background-size: 40px 40px; 
        pointer-events: none; 
        z-index: 0; 
    }
    
    .home-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        padding-bottom: 3rem;
    }
    
    @keyframes fadeUp { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    
    /* ══ HERO BANNER / SLIDER ══ */
    .hero-slider {
        position: relative;
        background: #080808;
        border: 1px solid var(--border);
        border-radius: 4px;
        height: 420px;
        overflow: hidden;
        margin-bottom: 3rem;
        box-shadow: 0 15px 40px rgba(0,0,0,0.8);
        animation: fadeUp 0.5s ease both;
    }
    
    .slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.8s ease, visibility 0.8s ease;
        display: flex;
        align-items: center;
        padding: 3rem;
        background-size: cover;
        background-position: center;
    }
    
    .slide.active {
        opacity: 1;
        visibility: visible;
    }
    
    .slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(8,8,8,0.95) 40%, rgba(8,8,8,0.4) 100%);
        z-index: 1;
    }
    
    .slide-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }
    
    .slide-tag {
        display: inline-block;
        font-family: var(--mono);
        font-size: 0.65rem;
        color: var(--accent);
        border: 1px solid var(--accent);
        padding: 0.25rem 0.75rem;
        border-radius: 2px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 1.25rem;
        background: rgba(232, 255, 71, 0.05);
    }
    
    .slide-title {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        text-transform: uppercase;
    }
    .slide-title span {
        color: var(--accent);
    }
    
    .slide-desc {
        color: var(--muted);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .slide-btns {
        display: flex;
        gap: 1rem;
    }
    
    .btn-cyber-primary {
        font-family: var(--mono);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: #0d0d0d;
        background: var(--accent);
        border: 1px solid var(--accent);
        padding: 0.75rem 1.75rem;
        border-radius: 3px;
        text-transform: uppercase;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
    }
    .btn-cyber-primary:hover {
        background: var(--accent-dim);
        border-color: var(--accent-dim);
        color: #0d0d0d;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .btn-cyber-secondary {
        font-family: var(--mono);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: var(--text);
        background: transparent;
        border: 1px solid var(--border);
        padding: 0.75rem 1.75rem;
        border-radius: 3px;
        text-transform: uppercase;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }
    .btn-cyber-secondary:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(232, 255, 71, 0.02);
        text-decoration: none;
    }
    
    .slider-dots {
        position: absolute;
        bottom: 1.5rem;
        left: 3rem;
        display: flex;
        gap: 0.5rem;
        z-index: 3;
    }
    
    .dot {
        width: 8px;
        height: 8px;
        background: #2a2a2a;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s, transform 0.3s;
    }
    .dot.active {
        background: var(--accent);
        transform: scale(1.2);
    }
    
    /* ══ MARKETING TRUST BANNERS ══ */
    .trust-banner-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
        margin-bottom: 3.5rem;
        animation: fadeUp 0.5s 0.1s ease both;
    }
    
    .trust-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 3px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: border-color 0.3s, background 0.3s;
    }
    .trust-card:hover {
        border-color: rgba(232, 255, 71, 0.3);
        background: rgba(20,20,20,0.8);
    }
    
    .trust-icon {
        font-size: 1.75rem;
        color: var(--accent);
        line-height: 1;
    }
    
    .trust-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.15rem;
        letter-spacing: 0.02em;
    }
    
    .trust-desc {
        color: var(--muted);
        font-size: 0.72rem;
        margin-bottom: 0;
        line-height: 1.4;
    }
    
    /* ══ CATEGORY SECTIONS ══ */
    .section-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.75rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: -0.02em;
        margin-bottom: 0;
    }
    .section-title span {
        color: var(--accent);
    }
    
    .section-link {
        font-family: var(--mono);
        font-size: 0.7rem;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-decoration: none;
        transition: color 0.2s, opacity 0.2s;
    }
    .section-link:hover {
        color: var(--accent-dim);
        text-decoration: none;
        opacity: 0.8;
    }
    
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 3.5rem;
        animation: fadeUp 0.5s 0.15s ease both;
    }
    
    .category-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 3px;
        padding: 1.5rem 1rem;
        text-align: center;
        text-decoration: none;
        transition: transform 0.2s, border-color 0.2s, background 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .category-card:hover {
        transform: translateY(-3px);
        border-color: var(--accent);
        background: rgba(232, 255, 71, 0.02);
        text-decoration: none;
    }
    
    .category-card-icon {
        font-size: 2.2rem;
        margin-bottom: 0.75rem;
        filter: drop-shadow(0 0 10px rgba(232, 255, 71, 0.1));
    }
    
    .category-card-name {
        color: var(--text);
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    
    .category-card-count {
        font-family: var(--mono);
        font-size: 0.68rem;
        color: var(--muted);
    }
    
    /* ══ PRODUCT CARDS GRID ══ */
    .home-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3.5rem;
        animation: fadeUp 0.5s 0.2s ease both;
    }
    
    .home-product-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: background 0.2s, border-color 0.2s;
        position: relative;
    }
    
    .home-product-card:hover {
        border-color: rgba(232, 255, 71, 0.4);
        background: rgba(20,20,20,1);
    }
    
    .home-product-card::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: 0; 
        right: 0; 
        height: 2px; 
        background: var(--accent); 
        transform: scaleX(0); 
        transform-origin: left; 
        transition: transform 0.25s cubic-bezier(0.16,1,0.3,1); 
        z-index: 2; 
    }
    .home-product-card:hover::before { 
        transform: scaleX(1); 
    }
    
    .badge-card {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 3;
        font-family: var(--mono);
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 0.25rem 0.5rem;
        border-radius: 2px;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .badge-featured {
        background: rgba(232, 255, 71, 0.15);
        color: var(--accent);
        border-color: var(--accent);
        box-shadow: 0 0 10px rgba(232, 255, 71, 0.2);
    }
    .badge-new {
        background: rgba(71, 232, 208, 0.15);
        color: var(--teal);
        border-color: var(--teal);
        box-shadow: 0 0 10px rgba(71, 232, 208, 0.2);
    }
    
    .card-img-container {
        position: relative;
        height: 180px;
        background: #080808;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .card-img-inner {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: transform 0.4s, opacity 0.4s;
    }
    
    .home-product-card:hover .card-img-inner {
        transform: scale(1.04);
        opacity: 1;
    }
    
    .card-no-img-text {
        font-family: var(--mono);
        font-size: 0.65rem;
        color: var(--muted);
        letter-spacing: 0.1em;
    }
    
    .card-body-inner {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .card-cat-tag {
        font-family: var(--mono);
        font-size: 0.6rem;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.35rem;
    }
    
    .card-title-link {
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.35;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.5rem;
    }
    
    .card-title-link a {
        color: var(--text);
        text-decoration: none;
        transition: color 0.15s;
    }
    .card-title-link a::after {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 1;
    }
    
    .card-title-link a:hover {
        color: var(--accent);
    }
    
    .card-desc-text {
        color: var(--muted);
        font-size: 0.75rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.2rem;
    }
    
    .card-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.85rem;
        border-top: 1px solid var(--border2);
        margin-top: auto;
    }
    
    .card-price-val {
        font-family: var(--mono);
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--teal);
    }
    
    .btn-add-to-cart {
        font-family: var(--mono);
        font-size: 0.65rem;
        letter-spacing: 0.05em;
        color: var(--accent);
        background: rgba(232, 255, 71, 0.06);
        border: 1px solid rgba(232, 255, 71, 0.18);
        border-radius: 3px;
        padding: 0.35rem 0.75rem;
        text-decoration: none;
        transition: all 0.2s;
        text-transform: uppercase;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }
    .btn-add-to-cart:hover {
        background: var(--accent);
        color: #0d0d0d;
        border-color: var(--accent);
        text-decoration: none;
    }
    
    /* ══ CTA SECTION ══ */
    .cta-banner {
        background: linear-gradient(135deg, #101010 0%, #050505 100%);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    
    .cta-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(232, 255, 71, 0.03) 0%, transparent 70%);
        pointer-events: none;
    }
    
    .cta-title {
        font-size: 1.8rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    .cta-title span {
        color: var(--accent);
    }
    
    .cta-desc {
        color: var(--muted);
        font-size: 0.85rem;
        max-width: 550px;
        margin: 0 auto 1.75rem auto;
        line-height: 1.6;
    }
</style>

<div class="home-container">
    
    <!-- ══ SLIDER HERO ══ -->
    <div class="hero-slider">
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1470&auto=format&fit=crop');">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <span class="slide-tag">Thế hệ mới // 2026</span>
                <h2 class="slide-title">Hệ Thống Máy Chủ <span>Cyber Core</span></h2>
                <p class="slide-desc">Được trang bị những cấu hình tối tân nhất, dòng máy trạm Cyber Core sinh ra để thách thức mọi tựa game AAA và phần mềm xử lý đồ họa nặng nề nhất.</p>
                <div class="slide-btns">
                    <a href="<?php echo BASE_URL; ?>/Product/list" class="btn-cyber-primary">Cửa Hàng Ngay</a>
                    <a href="<?php echo BASE_URL; ?>/Product/list?category=Linh+ki%E1%BB%87n+m%C3%A1y+t%C3%ADnh" class="btn-cyber-secondary">Linh Kiện</a>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1527690781703-a2e6bd766d0c?q=80&w=1470&auto=format&fit=crop');">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <span class="slide-tag">Trải nghiệm thị giác</span>
                <h2 class="slide-title">Màn Hình Gaming <span>Neon Beam</span></h2>
                <p class="slide-desc">Khám phá độ trễ cực thấp cùng tần số quét kinh ngạc lên đến 360Hz. Từng khung hình chuyển động mịn màng giúp bạn làm chủ mọi trận chiến khốc liệt.</p>
                <div class="slide-btns">
                    <a href="<?php echo BASE_URL; ?>/Product/list" class="btn-cyber-primary">Mua Ngay</a>
                    <a href="<?php echo BASE_URL; ?>/Product/list?category=M%C3%A0n+h%C3%ACnh+m%C3%A1y+t%C3%ADnh" class="btn-cyber-secondary">Màn Hình</a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=1530&auto=format&fit=crop');">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <span class="slide-tag">Tập trung độ chính xác</span>
                <h2 class="slide-title">Phụ Kiện Gaming <span>Matrix Gear</span></h2>
                <p class="slide-desc">Thiết bị chuột, bàn phím cơ quang học tối tân giúp tối ưu hóa từng mili-giây phản hồi. Bền bỉ, công thái học, đậm chất tương lai.</p>
                <div class="slide-btns">
                    <a href="<?php echo BASE_URL; ?>/Product/list" class="btn-cyber-primary">Xem Phụ Kiện</a>
                </div>
            </div>
        </div>
        
        <!-- Các nút chuyển slide -->
        <div class="slider-dots">
            <span class="dot active" onclick="setSlide(0)"></span>
            <span class="dot" onclick="setSlide(1)"></span>
            <span class="dot" onclick="setSlide(2)"></span>
        </div>
    </div>

    <!-- ══ CATEGORY QUICK LINKS ══ -->
    <div class="section-header">
        <h2 class="section-title">Danh Mục <span>Sản Phẩm</span></h2>
        <a href="<?php echo BASE_URL; ?>/Product/list" class="section-link">Tất cả sản phẩm →</a>
    </div>

    <div class="category-grid">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <?php 
                $count = $categoryCounts[$cat->getName()] ?? 0;
                ?>
                <a href="<?php echo BASE_URL; ?>/Product/list?category=<?php echo urlencode($cat->getName()); ?>" class="category-card">
                    <span class="category-card-icon"><?php echo getCategoryIcon($cat->getName()); ?></span>
                    <span class="category-card-name"><?php echo htmlspecialchars($cat->getName(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="category-card-count"><?php echo $count; ?> sản phẩm</span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; color: var(--muted); font-size: 0.8rem; padding: 2rem 0;">
                // Không tìm thấy danh mục nào trong hệ thống
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ FEATURED PRODUCTS ══ -->
    <div class="section-header">
        <h2 class="section-title">Sản Phẩm <span>Nổi Bật</span></h2>
        <a href="<?php echo BASE_URL; ?>/Product/list" class="section-link">Tất cả sản phẩm →</a>
    </div>

    <div class="home-products-grid">
        <?php if (!empty($featuredProducts)): ?>
            <?php foreach ($featuredProducts as $p): ?>
                <div class="home-product-card">
                    <span class="badge-card badge-featured">Nổi Bật</span>
                    
                    <div class="card-img-container">
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
                            <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>" class="card-img-inner">
                        <?php else: ?>
                            <span class="card-no-img-text">CHƯA CÓ ẢNH</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body-inner">
                        <span class="card-cat-tag">// <?php echo $p->getCategory() ? htmlspecialchars($p->getCategory(), ENT_QUOTES, 'UTF-8') : 'Khác'; ?></span>
                        <h3 class="card-title-link">
                            <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $p->getID(); ?>">
                                <?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>
                        <p class="card-desc-text"><?php echo htmlspecialchars($p->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
                        
                        <div class="card-bottom-row">
                            <span class="card-price-val"><?php echo number_format($p->getPrice(), 0, '.', ','); ?> VNĐ</span>
                            <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $p->getID(); ?>" class="btn-add-to-cart">+ Giỏ hàng</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; color: var(--muted); font-size: 0.8rem; padding: 4rem 0;">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">[ _ ]</div>
                <div>Chưa có sản phẩm nổi bật nào được cập nhật</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ NEW ARRIVALS ══ -->
    <div class="section-header">
        <h2 class="section-title">Sản Phẩm <span>Mới Nhất</span></h2>
        <a href="<?php echo BASE_URL; ?>/Product/list" class="section-link">Tất cả sản phẩm →</a>
    </div>

    <div class="home-products-grid">
        <?php if (!empty($newArrivals)): ?>
            <?php foreach ($newArrivals as $p): ?>
                <div class="home-product-card">
                    <span class="badge-card badge-new">Mới Nhất</span>
                    
                    <div class="card-img-container">
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
                            <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>" class="card-img-inner">
                        <?php else: ?>
                            <span class="card-no-img-text">CHƯA CÓ ẢNH</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body-inner">
                        <span class="card-cat-tag">// <?php echo $p->getCategory() ? htmlspecialchars($p->getCategory(), ENT_QUOTES, 'UTF-8') : 'Khác'; ?></span>
                        <h3 class="card-title-link">
                            <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $p->getID(); ?>">
                                <?php echo htmlspecialchars($p->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>
                        <p class="card-desc-text"><?php echo htmlspecialchars($p->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
                        
                        <div class="card-bottom-row">
                            <span class="card-price-val"><?php echo number_format($p->getPrice(), 0, '.', ','); ?> VNĐ</span>
                            <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $p->getID(); ?>" class="btn-add-to-cart">+ Giỏ hàng</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; color: var(--muted); font-size: 0.8rem; padding: 4rem 0;">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">[ _ ]</div>
                <div>Chưa có sản phẩm mới nào được cập nhật</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ CTA SECTION ══ -->
    <div class="cta-banner">
        <div class="cta-glow"></div>
        <h2 class="cta-title">Sẵn Sàng Nâng Cấp <span>Cyber Gear</span> Của Bạn?</h2>
        <p class="cta-desc">Đừng chần chừ! Hãy khám phá hàng trăm linh kiện, màn hình và phụ kiện máy tính chính hãng độc quyền với chính sách hậu mãi vàng chỉ có tại Cyber Store.</p>
        <a href="<?php echo BASE_URL; ?>/Product/list" class="btn-cyber-primary" style="padding: 0.9rem 2.2rem; font-size: 0.8rem;">Ghé thăm Cửa hàng ngay</a>
    </div>

</div>

<script>
    // JS cho Slider tự động chạy
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval = setInterval(nextSlide, 5000); // 5 giây tự động đổi slide

    function nextSlide() {
        goToSlide((currentSlide + 1) % slides.length);
    }

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');
        currentSlide = n;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function setSlide(n) {
        goToSlide(n);
        // Reset interval khi người dùng nhấn thủ công
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
