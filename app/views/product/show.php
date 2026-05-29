<?php /** @var ProductModel $product */ ?>
<?php include 'app/views/shares/header.php'; ?>

<style>
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
        background-size: 30px 30px;
        pointer-events: none;
        z-index: 0;
    }

    .wrapper {
        width: 100%;
        max-width: 1000px;
        margin: 3rem auto;
        position: relative;
        z-index: 1;
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .card {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .card-header {
        background: var(--surface2) !important;
        border-bottom: 1px solid var(--border) !important;
        padding: 1.5rem 2.5rem !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title-main {
        font-family: var(--mono);
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        color: var(--muted);
        margin: 0;
        text-transform: uppercase;
    }

    .sys-status {
        font-family: var(--mono);
        font-size: 0.65rem;
        color: var(--teal);
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }
    .sys-status::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: var(--teal);
        border-radius: 50%;
    }

    .card-body {
        padding: 3rem 2.5rem !important;
    }

    .product-img-wrap {
        width: 100%;
        height: 400px;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: opacity 0.2s ease, transform 0.3s ease;
    }

    .no-img-text {
        font-family: var(--mono);
        font-size: 0.8rem;
        color: var(--muted);
        letter-spacing: 0.1em;
    }

    .gallery-grid {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-top: 0.8rem;
    }

    .gallery-thumb-item {
        width: 64px;
        height: 64px;
        border-radius: 4px;
        border: 1px solid var(--border);
        cursor: pointer;
        overflow: hidden;
        background: var(--surface2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        position: relative;
    }

    .gallery-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .gallery-thumb-item:hover {
        border-color: var(--accent);
    }

    .gallery-thumb-item:hover img {
        opacity: 0.9;
    }

    .gallery-thumb-item.active {
        border-color: var(--accent) !important;
        border-width: 2px;
    }

    .gallery-thumb-item.active img {
        opacity: 1;
    }

    .info-title {
        font-family: var(--sans);
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 1.2rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .info-desc {
        font-size: 0.95rem;
        color: var(--muted);
        line-height: 1.7;
        margin-bottom: 2rem;
        padding-left: 1.25rem;
        border-left: 3px solid var(--accent);
    }

    .price-box {
        background: var(--surface2);
        border: 1px solid var(--border);
        padding: 1.25rem 1.75rem;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 2rem;
    }

    .price-lbl {
        font-family: var(--mono);
        font-size: 0.7rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .info-price {
        font-family: var(--sans);
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent);
        margin: 0;
        line-height: 1;
    }

    .info-cat {
        margin-bottom: 2.5rem;
        font-family: var(--mono);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-cat strong {
        color: var(--muted);
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .badge-cat {
        background: rgba(37, 99, 235, 0.08) !important;
        border: 1px solid rgba(37, 99, 235, 0.2) !important;
        color: var(--accent) !important;
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem !important;
        border-radius: 4px !important;
        font-weight: 600;
    }

    .actions-wrap {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-add-cart {
        background: var(--accent) !important;
        color: #ffffff !important;
        border: none !important;
        font-family: var(--sans);
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.15rem 2.25rem !important;
        border-radius: 4px !important;
        transition: all 0.2s !important;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-add-cart:hover {
        background: var(--accent-dim) !important;
        transform: translateY(-2px);
        text-decoration: none !important;
        color: #ffffff !important;
    }

    .btn-back {
        background: transparent !important;
        color: var(--text) !important;
        border: 1px solid var(--border) !important;
        font-family: var(--sans);
        font-size: 0.9rem;
        font-weight: 600;
        padding: 1.15rem 1.75rem !important;
        border-radius: 4px !important;
        transition: all 0.2s !important;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--surface2) !important;
        border-color: var(--border2) !important;
        text-decoration: none !important;
    }
</style>

<div class="wrapper">
    <div class="card shadow-lg">
        <div class="card-header">
            <h2 class="card-title-main"><?php echo (isset($product) && $product) ? htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') : 'Chi tiết sản phẩm'; ?></h2>
            <div class="sys-status">Đang hoạt động</div>
        </div>
        <div class="card-body">
            <?php if (isset($product) && $product): ?>
                <div class="row">
                    <!-- Column Image & Gallery -->
                    <div class="col-md-5 mb-5 mb-md-0">
                        <div class="product-img-wrap">
                            <?php 
                                $imgSrc = $product->getImage();
                                if ($imgSrc):
                                    if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                                        $imgSrc = ltrim($imgSrc, '/');
                                        if (strpos($imgSrc, 'webbanhang/') === 0) {
                                            $imgSrc = substr($imgSrc, 11);
                                        }
                                        $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                                    }
                            ?>
                                <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" class="product-img" alt="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php else: ?>
                                <span class="no-img-text">Chưa có ảnh</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($product->sub_images) || $product->getImage()): ?>
                            <div class="product-gallery-wrap" style="margin-top: 1.2rem;">
                                <div class="gallery-title" style="font-family: var(--mono); font-size: 0.65rem; color: var(--muted); letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.6rem;"></div>
                                <div class="gallery-grid">
                                    <?php 
                                        $mainThumbSrc = $product->getImage();
                                        if ($mainThumbSrc):
                                            if (!filter_var($mainThumbSrc, FILTER_VALIDATE_URL)) {
                                                $mainThumbSrc = ltrim($mainThumbSrc, '/');
                                                if (strpos($mainThumbSrc, 'webbanhang/') === 0) {
                                                    $mainThumbSrc = substr($mainThumbSrc, 11);
                                                }
                                                $mainThumbSrc = BASE_URL . '/' . ltrim($mainThumbSrc, '/');
                                            }
                                    ?>
                                        <div class="gallery-thumb-item active" onclick="switchMainImage('<?php echo htmlspecialchars($mainThumbSrc, ENT_QUOTES, 'UTF-8'); ?>', this)">
                                            <img src="<?php echo htmlspecialchars($mainThumbSrc, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <?php 
                                    if (!empty($product->sub_images)):
                                        foreach ($product->sub_images as $subImg): 
                                            $subImgSrc = $subImg['image_path'];
                                            if ($subImgSrc):
                                                if (!filter_var($subImgSrc, FILTER_VALIDATE_URL)) {
                                                    $subImgSrc = ltrim($subImgSrc, '/');
                                                    if (strpos($subImgSrc, 'webbanhang/') === 0) {
                                                        $subImgSrc = substr($subImgSrc, 11);
                                                    }
                                                    $subImgSrc = BASE_URL . '/' . ltrim($subImgSrc, '/');
                                                }
                                    ?>
                                            <div class="gallery-thumb-item" onclick="switchMainImage('<?php echo htmlspecialchars($subImgSrc, ENT_QUOTES, 'UTF-8'); ?>', this)">
                                                <img src="<?php echo htmlspecialchars($subImgSrc, ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                    <?php endif; endforeach; endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Column Details -->
                    <div class="col-md-7">
                        <h1 class="info-title"><?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?></h1>
                        
                        <div class="info-desc">
                            <?php 
                                $desc = $product->getDescription();
                                echo !empty($desc) ? nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) : '<span style="color:var(--muted); font-style:italic;">// Không có mô tả chi tiết cho sản phẩm này.</span>'; 
                            ?>
                        </div>
                        
                        <div>
                            <div class="price-box">
                                <div class="price-lbl">Giá</div>
                                <div class="info-price">
                                    <?php echo number_format($product->getPrice(), 0, '.', ','); ?> VNĐ
                                </div>
                            </div>
                        </div>

                        <div class="info-cat">
                            <strong>Danh mục:</strong>
                            <span class="badge-cat">
                                <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                            </span>
                        </div>

                        <div class="actions-wrap">
                            <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $product->getID(); ?>" class="btn-add-cart">
                                + Thêm vào giỏ hàng
                            </a>
                            <a href="<?php echo BASE_URL; ?>/Product/" class="btn-back">
                                Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 2rem; border-radius: 4px; font-family: var(--sans);">
                    <h4 style="font-size: 1.1rem; margin: 0; font-weight: 600;">✕ Lỗi hệ thống: Không tìm thấy thông tin sản phẩm</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function switchMainImage(src, element) {
        const mainImg = document.querySelector('.product-img');
        if (mainImg) {
            mainImg.style.opacity = '0.2';
            mainImg.style.transform = 'scale(0.97)';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 180);
        }
        
        const thumbs = document.querySelectorAll('.gallery-thumb-item');
        thumbs.forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
