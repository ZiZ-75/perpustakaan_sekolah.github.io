<?php
require '../../includes/config.php';
require '../../includes/functions.php';

requireLogin();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../siswa/dashboard.php');
    exit();
}

$borrowedBooks = getAllBorrowedBooks();
?>

<?php include '../../includes/header.php'; ?>

<h2>Dashboard Admin</h2>
<p>Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?></p>

<div class="card mt-4">
    <div class="card-header">
        <h4>Daftar Buku yang Dipinjam</h4>
    </div>
    <div class="card-body">
        <?php if (empty($borrowedBooks)): ?>
            <p>Tidak ada buku yang sedang dipinjam.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrowedBooks as $index => $book): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($book['judul']) ?></td>
                                <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                <td><?= htmlspecialchars($book['nama_peminjam']) ?></td>
                                <td><?= $book['tanggal_pinjam'] ?></td>
                                <td><?= $book['tanggal_kembali'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $book['status'] === 'dipinjam' ? 'warning' : 'success' ?>">
                                        <?= $book['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>