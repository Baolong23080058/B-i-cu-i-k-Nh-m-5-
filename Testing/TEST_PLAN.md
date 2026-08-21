## 1. Mục tiêu kiểm thử
- Xác nhận các chức năng cốt lõi hoạt động đúng trên bản web hiện tại
- Kiểm tra phân quyền 3 vai trò: ADMIN, COACH, PLAYER
- Kiểm tra quy trình đặt lịch học (booking flow)
- Phát hiện lỗi, sửa lỗi và re-test đến khi Pass

## 2. Phạm vi kiểm thử (In Scope)
- Đăng nhập / Đăng xuất / Phân quyền theo role
- Trang Admin, trang HLV (Coach), trang Học viên (Student)
- Xem danh sách ca dạy trống
- Đặt lịch học
- Xem lịch đã đặt
- Chặn đặt trùng ca (status BOOKED)
- Bảo vệ trang khi chưa đăng nhập

## 3. Ngoài phạm vi (Out of Scope)
- Thanh toán online thật
- API riêng / mobile app
- Matchmaking / ghép trận
- Upload chứng chỉ phức tạp

## 4. Môi trường kiểm thử
| Hạng mục | Chi tiết |
|----------|----------|
| OS | Windows |
| Server | XAMPP (Apache + MySQL) |
| Backend | PHP + mysqli |
| Database | pickleball_db |
| Browser | Chrome |
| URL | http://localhost/pickleball/ |

## 5. Tài khoản kiểm thử
| Role | Email | Password |
|------|-------|----------|
| ADMIN | admin@pickleball.vn | password |
| COACH | huan.coach@gmail.com | password |
| PLAYER | long.player@gmail.com | password |

## 6. Danh sách Test Case

| Mã TC | Phân hệ | Kịch bản | Dữ liệu đầu vào | Kết quả mong đợi | Mức độ |
|-------|---------|----------|-----------------|------------------|--------|
| TC01 | Authentication | Đăng nhập Admin thành công | email: admin@pickleball.vn / password | Chuyển tới source/admin/index.php, hiện trang Admin | High |
| TC02 | Authentication | Đăng nhập Coach thành công | email: huan.coach@gmail.com / password | Chuyển tới source/coach/index.php | High |
| TC03 | Authentication | Đăng nhập Học viên thành công | email: long.player@gmail.com / password | Chuyển tới source/student/index.php | High |
| TC04 | Authentication | Đăng nhập sai mật khẩu | email đúng + password sai | Ở lại login, báo lỗi đăng nhập | High |
| TC05 | Authentication | Đăng nhập email không tồn tại | email không có trong DB | Báo lỗi, không vào hệ thống | High |
| TC06 | Authorization | Học viên truy cập trang Admin khi đã login PLAYER | Vào URL /admin/index.php với session PLAYER | Bị chặn / chuyển về login hoặc không cho vào Admin | Critical |
| TC07 | Authorization | Chưa login truy cập trang Student | Mở /student/index.php khi chưa đăng nhập | Redirect về login.php | Critical |
| TC08 | Authorization | Chưa login truy cập trang đặt lịch | Mở /student/book.php khi chưa đăng nhập | Redirect về login.php | Critical |
| TC09 | Student – Booking | Xem danh sách ca dạy trống | Login PLAYER → book.php | Hiện các ca status = AVAILABLE (HLV, sân, giờ, giá) | High |
| TC10 | Student – Booking | Đặt lịch thành công | Login PLAYER → chọn 1 ca AVAILABLE → Đặt lịch | Thông báo thành công; thêm dòng bảng bookings; schedules.status đổi BOOKED | Critical |
| TC11 | Student – Booking | Không đặt được ca đã BOOKED | Thử đặt lại cùng schedule_id đã đặt | Báo không còn trống / không tạo booking mới | Critical |
| TC12 | Student – Booking | Xem lịch đã đặt | Login PLAYER → my-bookings.php | Hiện đúng các booking của học viên đó (HLV, giờ, trạng thái) | High |
| TC13 | Student – Booking | Học viên A đặt xong, học viên B không đặt trùng ca | PLAYER1 đặt ca X thành công; PLAYER2 đặt cùng ca X | PLAYER2 thất bại, ca vẫn BOOKED 1 lần | Critical |
| TC14 | Admin | Admin xem trang quản trị | Login ADMIN → admin/index.php | Hiện dashboard Admin (thống kê / duyệt HLV / user) | High |
| TC15 | Coach | HLV xem trang của mình | Login COACH → coach/index.php | Hiện trang HLV (hồ sơ / lịch dạy) | High |
| TC16 | Authentication | Đăng xuất | Bấm Đăng xuất từ trang bất kỳ | Hủy session, về login.php; vào lại trang bảo vệ bị chặn | High |
| TC17 | Student – UI | Từ trang chủ Học viên vào Đặt lịch | Login PLAYER → index.php → nút Đặt lịch học | Mở được book.php | Medium |
| TC18 | Student – UI | Từ trang chủ Học viên vào Lịch của tôi | Login PLAYER → index.php → nút Lịch của tôi | Mở được my-bookings.php | Medium |
| TC19 | Data | Import database | Import database/pickleball.sql vào MySQL | Tạo đủ bảng users, coach_profiles, schedules, bookings… | High |
| TC20 | Data | Có dữ liệu lịch mẫu AVAILABLE | Kiểm tra bảng schedules | Có ít nhất 1 dòng status = AVAILABLE để test đặt lịch | Medium |

---

## 7. Tiêu chí đạt
- Tất cả TC mức Critical và High phải Pass sau re-test
- Có ít nhất 1 TC Fail trong lần chạy đầu, đã sửa và Pass lại
- Không còn lỗi chặn luồng đăng nhập / đặt lịch chính
