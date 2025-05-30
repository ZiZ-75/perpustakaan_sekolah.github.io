<?php
require 'config.php';

function registerUser($username, $password, $nama_lengkap, $role) {
    global $pdo;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $hashed_password, $nama_lengkap, $role]);
}

function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function getAllBooks() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM books WHERE jumlah_tersedia > 0");
    return $stmt->fetchAll();
}

function getBorrowedBooks($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, b.judul, b.pengarang 
                          FROM peminjaman p 
                          JOIN books b ON p.book_id = b.id 
                          WHERE p.user_id = ? AND p.status = 'dipinjam'");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getAllBorrowedBooks() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, b.judul, b.pengarang, u.nama_lengkap as nama_peminjam
                        FROM peminjaman p 
                        JOIN books b ON p.book_id = b.id 
                        JOIN users u ON p.user_id = u.id
                        WHERE p.status = 'dipinjam'");
    return $stmt->fetchAll();
}

function pinjamBuku($user_id, $book_id) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Kurangi stok buku
        $stmt = $pdo->prepare("UPDATE books SET jumlah_tersedia = jumlah_tersedia - 1 WHERE id = ? AND jumlah_tersedia > 0");
        $stmt->execute([$book_id]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception("Buku tidak tersedia");
        }
        
        // Tambahkan data peminjaman
        $tanggal_pinjam = date('Y-m-d');
        $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));
        
        $stmt = $pdo->prepare("INSERT INTO peminjaman (user_id, book_id, tanggal_pinjam, tanggal_kembali) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $book_id, $tanggal_pinjam, $tanggal_kembali]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function kembalikanBuku($peminjaman_id) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Dapatkan book_id dari peminjaman
        $stmt = $pdo->prepare("SELECT book_id FROM peminjaman WHERE id = ?");
        $stmt->execute([$peminjaman_id]);
        $book_id = $stmt->fetchColumn();
        
        // Update status peminjaman
        $stmt = $pdo->prepare("UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURDATE() WHERE id = ?");
        $stmt->execute([$peminjaman_id]);
        
        // Tambah stok buku
        $stmt = $pdo->prepare("UPDATE books SET jumlah_tersedia = jumlah_tersedia + 1 WHERE id = ?");
        $stmt->execute([$book_id]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function addBook($judul, $pengarang, $tahun_terbit, $isbn, $jumlah_tersedia) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO books (judul, pengarang, tahun_terbit, isbn, jumlah_tersedia) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$judul, $pengarang, $tahun_terbit, $isbn, $jumlah_tersedia]);
}
?>