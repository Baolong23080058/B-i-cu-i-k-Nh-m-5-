## 1. Tổng quan kết quả

| Hạng mục | Số lượng |
|----------|----------|
| Tổng test case | 20 |
| Pass lần đầu | 18 |
| Fail lần đầu | 2 |
| Đã sửa & Re-test Pass | 2 |
| **Pass cuối** | **20 / 20 (100%)** |

---

## 2. Kết quả chi tiết lần chạy đầu

| Mã TC | Kịch bản | Kết quả | Ghi chú |
|-------|----------|---------|---------|
| TC01 | Login Admin | PASSED | Vào admin/index.php |
| TC02 | Login Coach | PASSED | Vào coach/index.php |
| TC03 | Login Player | PASSED | Vào student/index.php |
| TC04 | Sai mật khẩu | PASSED | Báo lỗi, không vào hệ thống |
| TC05 | Email không tồn tại | PASSED | Báo lỗi |
| TC06 | Player vào Admin | PASSED | Bị chặn / redirect |
| TC07 | Chưa login vào Student | PASSED | Redirect login.php |
| TC08 | Chưa login vào book.php | PASSED | Redirect login.php |
| TC09 | Xem ca trống | PASSED | Hiện đúng AVAILABLE |
| TC10 | Đặt lịch thành công | PASSED | Có record bookings + status BOOKED |
| TC11 | Đặt lại ca đã BOOKED | **FAILED** | Lần đầu: vẫn có thể tạo booking trùng nếu thiếu kiểm tra status khi POST |
| TC12 | Xem lịch đã đặt | PASSED | Đúng dữ liệu theo player_id |
| TC13 | Hai học viên đặt cùng ca | **FAILED** | Lần đầu: rủi ro double booking nếu không dùng transaction |
| TC14 | Trang Admin | PASSED | Hiển thị OK |
| TC15 | Trang Coach | PASSED | Hiển thị OK |
| TC16 | Logout | PASSED | Session bị hủy |
| TC17 | Menu → Đặt lịch | PASSED | |
| TC18 | Menu → Lịch của tôi | PASSED | |
| TC19 | Import SQL | PASSED | |
| TC20 | Có lịch mẫu | PASSED | |

---

## 3. Chi tiết lỗi Fail và cách sửa

### Fail 1 – TC11: Đặt lại ca đã BOOKED

**Mô tả lỗi (lần đầu):**  
Khi ca đã `BOOKED`, nếu request POST vẫn gửi `schedule_id` cũ, hệ thống có thể insert thêm booking nếu chỉ kiểm tra sơ.

**Nguyên nhân:**  
Chưa bắt buộc điều kiện `status = 'AVAILABLE'` ngay trong câu `UPDATE` / thiếu kiểm tra trước khi insert.

**Cách sửa (file `source/student/book.php`):**
- Chỉ cho đặt khi `SELECT ... WHERE id = ? AND status = 'AVAILABLE'`
- `UPDATE schedules SET status = 'BOOKED' WHERE id = ? AND status = 'AVAILABLE'`
- Dùng `mysqli_stmt_affected_rows` — nếu = 0 thì rollback

**Re-test TC11:** PASSED — ca đã BOOKED không đặt lại được.

---

### Fail 2 – TC13: Hai học viên đặt cùng một ca

**Mô tả lỗi (lần đầu):**  
Hai request gần như cùng lúc có thể cùng thấy `AVAILABLE` và cùng insert.

**Nguyên nhân:**  
Thiếu transaction bao quanh kiểm tra + insert + update.

**Cách sửa (file `source/student/book.php`):**
```php
mysqli_begin_transaction($conn);
// check AVAILABLE → INSERT bookings → UPDATE schedules ... AND status = 'AVAILABLE'
// nếu affected_rows = 0 → throw → rollback
mysqli_commit($conn);
