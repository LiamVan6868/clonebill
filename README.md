# Bill Vn - Source Code

Đây là mã nguồn của website Bill Vn.

## Yêu cầu

- PHP 7.4 trở lên
- MySQL / MariaDB
- Composer

## Cài đặt Local (Máy cá nhân)

1.  **Clone repository** hoặc giải nén source code.
2.  **Cài đặt dependencies**:
    ```bash
    composer install
    ```
3.  **Cấu hình Database**:
    - Tạo database tên `billviet` (hoặc tên khác tùy bạn).
    - Import file SQL (nếu có) vào database.
    - Mặc định cấu hình kết nối ở `function/connect/config.ini.php` là:
        - Host: `localhost`
        - User: `root`
        - Pass: (rỗng)
        - DB: `billviet`
    - Nếu cấu hình của bạn khác, hãy sửa trực tiếp file đó hoặc set biến môi trường.

4.  **Chạy server**:
    Bạn có thể dùng PHP built-in server:
    ```bash
    php -S localhost:8080 router.php
    ```
    Truy cập `http://localhost:8080`.

## Deploy lên Railway

1.  **Tạo Project mới trên Railway**:
    - Chọn "Deploy from GitHub repo".

2.  **Cấu hình biến môi trường (Environment Variables)**:
    Vào tab *Variables* của service và thêm các biến sau:

    - `DB_HOST`: Host của database (Railway MySQL hoặc database ngoài).
    - `DB_USER`: Username database.
    - `DB_PASS`: Password database.
    - `DB_NAME`: Tên database.
    - `DB_PORT`: Port database (thường là 3306).

    *Lưu ý: Nếu bạn deploy MySQL trên cùng project Railway, hãy copy các thông tin connection từ service MySQL sang.*

3.  **Deploy**:
    Railway sẽ tự động nhận diện `composer.json` và cài đặt dependencies.

4.  **Domain**:
    Vào tab *Settings* -> *Networking* để tạo Public Domain (ví dụ: `bill-vn-demo.up.railway.app`).

## Cấu trúc thư mục

- `public_html/`: Thư mục gốc.
- `function/`: Các hàm xử lý core.
- `client/`: Giao diện người dùng.
- `admin/`: Trang quản trị.
- `router.php`: Routing cho local dev.
