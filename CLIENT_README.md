# 🍰 CakeHome - Website Bán Bánh Ngọt Cao Cấp

Website thương mại điện tử bán bánh ngọt cao cấp được xây dựng với Laravel & Bootstrap 5.

## ✨ Tính Năng

### 🎨 Giao Diện Client (Đã hoàn thiện)
- ✅ **Trang chủ** (`/`) - Hero banner, danh mục, sản phẩm nổi bật
- ✅ **Danh sách sản phẩm** (`/products`) - Lọc, sắp xếp, phân trang
- ✅ **Chi tiết sản phẩm** (`/products/{id}`) - Ảnh, thông tin, đánh giá
- ✅ **Giỏ hàng** (`/cart`) - Quản lý giỏ hàng, tính tổng
- ✅ **Danh sách yêu thích** (`/wishlist`) - Sản phẩm yêu thích, chia sẻ
- ✅ **Thanh toán** (`/checkout`) - Form đặt hàng, phương thức thanh toán
- ✅ **Đăng nhập/Đăng ký** (`/login`) - Auth with Google/Facebook
- ✅ **Liên hệ** (`/contact`) - Form liên hệ, bản đồ
- ✅ **Về chúng tôi** (`/about`) - Giới thiệu công ty

### 🎨 Components
- ✅ Header với top bar, navigation, search modal
- ✅ Footer đầy đủ với social links, newsletter
- ✅ Breadcrumb navigation
- ✅ Product cards với hover effects
- ✅ Back to top button

## 📂 Cấu Trúc Thư Mục

```
resources/views/client/
├── layouts/
│   └── master.blade.php          # Layout chính
├── partials/
│   ├── header.blade.php          # Header + Navigation
│   └── footer.blade.php          # Footer
└── pages/
    ├── home.blade.php            # Trang chủ
    ├── products.blade.php        # Danh sách sản phẩm
    ├── product-detail.blade.php  # Chi tiết sản phẩm
    ├── cart.blade.php            # Giỏ hàng
    ├── wishlist.blade.php        # Danh sách yêu thích
    ├── checkout.blade.php        # Thanh toán
    ├── auth.blade.php            # Đăng nhập/Đăng ký
    ├── contact.blade.php         # Liên hệ
    └── about.blade.php           # Về chúng tôi
```

## 🚀 Cài Đặt

1. **Clone repository**
```bash
git clone https://github.com/ducdung0212/cakehome.git
cd cakehome
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database trong `.env`**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cakehome
DB_USERNAME=root
DB_PASSWORD=
```

5. **Chạy migration & seeder**
```bash
php artisan migrate --seed
```

6. **Khởi động server**
```bash
php artisan serve
```

Truy cập: http://127.0.0.1:8000

## 🎨 Công Nghệ Sử Dụng

- **Backend:** Laravel 11
- **Frontend:** Bootstrap 5, HTML5, CSS3, JavaScript
- **Icons:** Bootstrap Icons
- **Fonts:** Google Fonts (Playfair Display, Poppins)
- **Images:** Unsplash (demo)

## 📱 Responsive Design

Website được thiết kế hoàn toàn responsive, tương thích với:
- 💻 Desktop (>= 1200px)
- 💻 Laptop (992px - 1199px)
- 📱 Tablet (768px - 991px)
- 📱 Mobile (< 768px)

## 🎨 Màu Sắc

```css
--primary-color: #8B4513;    /* Nâu socola */
--secondary-color: #D2691E;  /* Nâu cam */
--dark-bg: #1a1a1a;          /* Nền tối */
--light-bg: #f8f5f2;         /* Nền sáng */
--text-dark: #333;
--text-light: #666;
```

## 🔗 Routes

| Method | URI | Name | View |
|--------|-----|------|------|
| GET | / | home | client.pages.home |
| GET | /products | products | client.pages.products |
| GET | /products/{id} | product.detail | client.pages.product-detail |
| GET | /cart | cart | client.pages.cart |
| GET | /wishlist | wishlist | client.pages.wishlist |
| GET | /checkout | checkout | client.pages.checkout |
| GET | /login | login | client.pages.auth |
| GET | /contact | contact | client.pages.contact |
| GET | /about | about | client.pages.about |

## 📝 TODO (Backend)

- [ ] Tích hợp database với các trang
- [ ] API endpoints cho cart, checkout
- [ ] Payment gateway integration (VNPay, MoMo)
- [ ] User authentication & authorization
- [ ] Admin dashboard
- [ ] Order management system
- [ ] Email notifications
- [ ] Search functionality
- [ ] Product filters & sorting

## 👨‍💻 Tác Giả

**ducdung0212**
- GitHub: [@ducdung0212](https://github.com/ducdung0212)

## 📄 License

MIT License

---

Made with ❤️ by ducdung0212
