-- Tạo cơ sở dữ liệu CongtyDB với utf8mb4
CREATE DATABASE CongtyDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE CongtyDB;

-- Tạo bảng SanPham
CREATE TABLE SanPham (
    MaSP INT PRIMARY KEY,
    TenSP VARCHAR(25),
    SoLuong INT,
    GiaTien INT
);

-- Thêm 3 bản ghi sản phẩm
INSERT INTO SanPham (MaSP, TenSP, SoLuong, GiaTien) VALUES
(1, 'Bút bi Thiên Long', 100, 5000),
(2, 'Vở ô ly 200 trang', 50, 12000),
(3, 'Thước kẻ 30cm', 80, 8000);
