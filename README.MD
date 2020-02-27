# Smart resource

# Requirement

- Laravel 6.x
- Guzzle
- spatie/data-transfer-object

# Ideas

- 2 chế độ local/remote để hỗ trợ người làm frontend lấy data trực tiếp từ server chính để dev/test giao diện đang làm.
- Tiện ích cache hỗ trợ cache một số kết quả hay dùng, dễ cấu hình, có command để refresh các dữ liệu đến hạn để người dùng ko phải chờ các xử lý hỗ trợ cache.

# Todo

-[] Sử dụng websocket hoặc giao thức kết nối nhanh hơn khi sử dụng chế độ remote
-[] Sử dụng Cache Store riêng
