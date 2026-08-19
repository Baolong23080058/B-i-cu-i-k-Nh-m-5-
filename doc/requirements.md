# Requirements – Pickleball Coaching System

## 1. Functional Requirements

FR01. Hệ thống cho phép người dùng đăng ký / đăng nhập theo 3 vai trò: Học viên (PLAYER), Huấn luyện viên (COACH), Admin (ADMIN).  
FR02. Học viên có thể tìm kiếm, lọc HLV theo tiêu chí (khu vực, trình độ, đánh giá…).  
FR03. Học viên có thể xem hồ sơ HLV (thông tin, kinh nghiệm, đánh giá, lịch dạy).  
FR04. Học viên có thể đặt lịch / đăng ký buổi học với HLV.  
FR05. Học viên có thể đánh giá HLV sau buổi học.  
FR06. Học viên có thể xem lịch sử học tập của mình.  
FR07. HLV có thể tạo và cập nhật hồ sơ cá nhân.  
FR08. HLV có thể quản lý lịch dạy (thêm, sửa, hủy khung giờ).  
FR09. HLV có thể xem danh sách học viên đã đăng ký.  
FR10. Admin có thể duyệt / từ chối hồ sơ HLV.  
FR11. Admin có thể quản lý người dùng (khóa/mở tài khoản).  
FR12. Admin có thể xem thống kê cơ bản (số HLV, số học viên, số lịch đặt…).  
FR13. Hệ thống hỗ trợ tìm kiếm / lọc dữ liệu.  
FR14. Hệ thống có phân quyền rõ ràng giữa các vai trò.

## 2. Non-functional Requirements

NFR01. Hệ thống là ứng dụng web, chạy trên trình duyệt.  
NFR02. Có giao diện cơ bản, dễ sử dụng, hỗ trợ responsive.  
NFR03. Mật khẩu được mã hóa, có bảo vệ session.  
NFR04. Dữ liệu đầu vào được kiểm tra (validation).  
NFR05. Hệ thống có thể triển khai local và hướng tới triển khai online.  
NFR06. Thời gian phản hồi các thao tác cơ bản ở mức chấp nhận được.

## 3. Business Rules

BR01. Chỉ HLV đã được Admin duyệt mới hiển thị cho Học viên.  
BR02. Không cho đặt trùng khung giờ đã kín.  
BR03. Chỉ Học viên đã hoàn thành buổi học mới được đánh giá HLV.  
BR04. Mỗi tài khoản chỉ thuộc 1 vai trò tại một thời điểm.
