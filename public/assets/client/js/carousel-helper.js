document.addEventListener('DOMContentLoaded', function () {
    // Hàm khởi tạo Carousel dùng chung
    function setupCarousel(carouselId, prevBtnId, nextBtnId, scrollStep) {
        const carousel = document.getElementById(carouselId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);

        if (!carousel || !prevBtn || !nextBtn) return;

        let currentPosition = 0;

        function updateButtons() {
            // Tính toán độ rộng tối đa có thể cuộn
            // scrollWidth: tổng độ rộng nội dung, offsetWidth: độ rộng khung nhìn thấy
            const containerWidth = carousel.parentElement.offsetWidth;
            const contentWidth = carousel.scrollWidth;
            const maxScroll = Math.max(0, contentWidth - containerWidth);

            prevBtn.disabled = currentPosition <= 0;
            // Cho phép sai số nhỏ (1px) khi tính toán
            nextBtn.disabled = currentPosition >= maxScroll - 1; 
        }

        prevBtn.addEventListener('click', function () {
            currentPosition = Math.max(0, currentPosition - scrollStep);
            carousel.style.transform = `translateX(-${currentPosition}px)`;
            updateButtons();
        });

        nextBtn.addEventListener('click', function () {
            const containerWidth = carousel.parentElement.offsetWidth;
            const contentWidth = carousel.scrollWidth;
            const maxScroll = Math.max(0, contentWidth - containerWidth);

            currentPosition = Math.min(maxScroll, currentPosition + scrollStep);
            carousel.style.transform = `translateX(-${currentPosition}px)`;
            updateButtons();
        });

        // Cập nhật khi resize màn hình (reset về 0 để tránh lỗi giao diện)
        window.addEventListener('resize', function() {
            currentPosition = 0;
            carousel.style.transform = `translateX(0px)`;
            updateButtons();
        });

        // Chạy lần đầu
        updateButtons();
    }

    // --- KÍCH HOẠT CÁC CAROUSEL ---

    // 1. Carousel Danh Mục (Giữ nguyên logic cũ của bạn)
    setupCarousel('categoryCarousel', 'categoryPrev', 'categoryNext', 320);

    // 2. Carousel Sản Phẩm Liên Quan (Mới thêm vào)
    // Giả sử mỗi thẻ sản phẩm khoảng 280px + 20px gap = 300px
    setupCarousel('relatedProductCarousel', 'relatedPrev', 'relatedNext', 300); 
});