    </div>
    <!-- Footer section -->
    <footer class="text-center text-lg-start mt-5 py-4" style="background-color: var(--surface); border-top: 1px solid var(--border);">
        <div class="container">
            <div class="row">
                <!-- Cột thông tin liên hệ -->
                <div class="col-lg-6 col-md-12 mb-4 text-left">
                    <h5 class="text-uppercase" style="color: var(--text);">// Cyber Store</h5>
                    <p class="text-muted">
                        Hệ thống cửa hàng công nghệ số cao cấp, mang lại trải nghiệm mua sắm mượt mà với phong cách hiện đại.
                    </p>
                </div>
                <!-- Cột liên kết nhanh -->
                <div class="col-lg-3 col-md-6 mb-4 text-left">
                    <h5 class="text-uppercase" style="color: var(--text);">Liên kết nhanh</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="<?php echo BASE_URL; ?>/Product/list" class="text-muted">Cửa hàng</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/Product/cart" class="text-muted">Giỏ hàng</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/Product/manage" class="text-muted">Trang Quản Trị</a></li>
                    </ul>
                </div>
                <!-- Cột mạng xã hội -->
                <div class="col-lg-3 col-md-6 mb-4 text-left">
                    <h5 class="text-uppercase" style="color: var(--text);">Kết nối với chúng tôi</h5>
                    <div class="mt-2">
                        <a href="#" class="text-muted mr-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted mr-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted mr-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dòng bản quyền -->
        <div class="text-center p-3 mt-3" style="background-color: var(--surface2); border-top: 1px solid var(--border);">
            © 2026 Cyber Store. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- AJAX Add to Cart Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const addToCartLinks = document.querySelectorAll('a[href*="/addToCart/"]');
        addToCartLinks.forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                
                // Hiệu ứng nút đang thêm
                const originalText = link.innerHTML;
                const originalBg = link.style.backgroundColor;
                const originalColor = link.style.color;
                const originalBorder = link.style.borderColor;
                
                link.innerHTML = '...';
                link.style.opacity = '0.7';
                link.style.pointerEvents = 'none';

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Cập nhật số lượng trên badge Header
                        const badge = document.querySelector('.nav-link .badge');
                        if (badge) {
                            badge.textContent = data.totalItems;
                            badge.style.transition = 'transform 0.2s';
                            badge.style.transform = 'scale(1.5)';
                            setTimeout(() => badge.style.transform = 'scale(1)', 200);
                        }
                        
                        // Hiệu ứng thành công trên nút
                        link.innerHTML = '✓ Đã thêm';
                        link.style.backgroundColor = 'var(--teal, #47e8d0)';
                        link.style.borderColor = 'var(--teal, #47e8d0)';
                        link.style.color = '#000';
                        link.style.opacity = '1';
                        
                        setTimeout(() => {
                            link.innerHTML = originalText;
                            link.style.backgroundColor = originalBg;
                            link.style.borderColor = originalBorder;
                            link.style.color = originalColor;
                            link.style.pointerEvents = 'auto';
                        }, 1200);
                    }
                } catch (err) {
                    console.error('Lỗi khi thêm vào giỏ hàng:', err);
                    window.location.href = url; // Fallback
                }
            });
        });
    });
    </script>
</body>
</html>
