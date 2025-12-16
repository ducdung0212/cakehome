<footer class="footer-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5><i class="bi bi-shop"></i> CakeHome</h5>
                <p class="text-light">
                    Mang đến những chiếc bánh ngọt cao cấp, được làm thủ công với tình yêu và đam mê.
                    Trải nghiệm hương vị tuyệt vời từ những nguyên liệu chọn lọc.
                </p>
                <div class="social-icons mt-3">
                    <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" title="Youtube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Sản Phẩm</h5>
                <ul>
                    <li><a href="/products?category=cake">Bánh Kem</a></li>
                    <li><a href="/products?category=cookies">Bánh Cookies</a></li>
                    <li><a href="/products?category=macaron">Bánh Macaron</a></li>
                    <li><a href="/products?category=bread">Bánh Mì Ngọt</a></li>
                    <li><a href="/products?category=gift">Quà Tặng</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Hỗ Trợ</h5>
                <ul>
                    <li><a href="/about">Về Chúng Tôi</a></li>
                    <li><a href="/contact">Liên Hệ</a></li>
                    <li><a href="/shipping-policy">Chính Sách Giao Hàng</a></li>
                    <li><a href="/return-policy">Chính Sách Đổi Trả</a></li>
                    <li><a href="/faq">Câu Hỏi Thường Gặp</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12 mb-4">
                <h5>Liên Hệ</h5>
                @php
                    $siteAddress =
                        $siteSettings['site_address'] ?? '62 Đường số 15, khu Trung Sơn, Bình Hưng, Bình Chánh, TP.HCM';
                    $sitePhone = $siteSettings['site_phone'] ?? '0975108384';
                    $siteEmail = $siteSettings['site_email'] ?? 'info@cakehome.vn';
                    $workHours = $siteSettings['site_working_hours'] ?? '8:00 - 22:00 (Thứ 2 - Chủ Nhật)';
                @endphp
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt text-secondary"></i>
                        {{ $siteAddress }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone text-secondary"></i>
                        {{ $sitePhone }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope text-secondary"></i>
                        {{ $siteEmail }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock text-secondary"></i>
                        {{ $workHours }}
                    </li>
                </ul>

            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.1);">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; 2025 CakeHome. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="/terms" class="me-3">Điều khoản sử dụng</a>
                <a href="/privacy" class="me-3">Chính sách bảo mật</a>
                <a href="/faq">FAQ</a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="mb-0 text-white small">
                    <i class="bi bi-shield-check"></i> Thanh toán an toàn và bảo mật
                </p>

                <div class="mt-2">
                    <img src="https://developers.momo.vn/v3/assets/images/MOMO-Logo-App-6262c3743a290ef02396a24ea2b66c35.png"
                        alt="Momo" style="height: 30px;">
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary-custom d-flex justify-content-center align-items-center"
    style="position: fixed; bottom: 30px; right: 30px; display: none; border-radius: 50%; width: 50px; height: 50px; z-index: 1000;">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    // Back to top button
    window.addEventListener('scroll', function() {
        const backToTopBtn = document.getElementById('backToTop');
        if (window.scrollY > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });

    document.getElementById('backToTop').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
