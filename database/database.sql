-- ===========================
-- Tabel Admin
-- ===========================

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ===========================
-- Tabel Kategori
-- ===========================

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

-- ===========================
-- Tabel Produk
-- ===========================

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    nama_produk VARCHAR(150) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    stok INT DEFAULT 0,
    gambar VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_kategori
    FOREIGN KEY (kategori_id)
    REFERENCES kategori(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

INSERT INTO kategori (nama_kategori) VALUES
('Beras'),
('Minyak'),
('Kopi'),
('Gula'),
('Mie Instan'),
('Tisu'),
('Minuman'),
('Lainnya');

INSERT INTO admin (nama, username, password)
VALUES (
'Leli',
'beras',
'tokoberas'
);