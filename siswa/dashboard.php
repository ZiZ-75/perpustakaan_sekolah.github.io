<?php
require '../../includes/config.php';
require '../../includes/functions.php';

requireLogin();
if ($_SESSION['role'] !== 'siswa') {
    header('Location: ../admin/dashboard.php');
    exit();
}

// Proses peminjaman buku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pinjam_buku'])) {
    $book_id = $_POST['book_id'];
    
    if (pinjamBuku($_SESSION['user_id'], $book_id)) {
        $success = "Buku berhasil dipinjam";
    } else {
        $error = "Gagal meminjam buku. Buku mungkin tidak tersedia.";
    }
}

// Proses pengembalian buku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kembalikan_buku'])) {
    $peminjaman_id = $_POST['peminjaman_id'];
    
    if (kembalikanBuku($peminjaman_id)) {
        $success = "Buku berhasil dikembalikan";
    } else {
        $error = "Gagal mengembalikan buku";
    }
}

$books = getAllBooks();
$borrowedBooks = getBorrowedBooks($_SESSION['user_id']);
?>

<?php include '../../includes/header.php'; ?>

<h2>Dashboard Siswa</h2>
<p>Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?></p>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Buku yang Dipinjam</h4>
            </div>
            <div class="card-body">
                <?php if (empty($borrowedBooks)): ?>
                    <p>Anda belum meminjam buku.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Pengarang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrowedBooks as $index => $book): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($book['judul']) ?></td>
                                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                        <td><?= $book['tanggal_pinjam'] ?></td>
                                        <td><?= $book['tanggal_kembali'] ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="peminjaman_id" value="<?= $book['id'] ?>">
                                                <button type="submit" name="kembalikan_buku" class="btn btn-sm btn-success">Kembalikan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Buku Tersedia</h4>
            </div>
            <div class="card-body">
                <?php if (empty($books)): ?>
                    <p>Tidak ada buku tersedia.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Tersedia</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $index => $book): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($book['judul']) ?></td>
                                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                        <td><?= $book['jumlah_tersedia'] ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                <button type="submit" name="pinjam_buku" class="btn btn-sm btn-primary">Pinjam</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>