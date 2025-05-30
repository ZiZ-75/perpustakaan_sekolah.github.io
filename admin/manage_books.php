<?php
require '../../includes/config.php';
require '../../includes/functions.php';

requireLogin();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../siswa/dashboard.php');
    exit();
}

// Tambah buku baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_buku'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $isbn = $_POST['isbn'];
    $jumlah_tersedia = $_POST['jumlah_tersedia'];
    
    if (addBook($judul, $pengarang, $tahun_terbit, $isbn, $jumlah_tersedia)) {
        $success = "Buku berhasil ditambahkan";
    } else {
        $error = "Gagal menambahkan buku";
    }
}

$books = getAllBooks();
?>

<?php include '../../includes/header.php'; ?>

<h2>Kelola Buku</h2>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Buku Baru</h4>
            </div>
            <div class="card-body">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_tersedia" class="form-label">Jumlah Tersedia</label>
                        <input type="number" class="form-control" id="jumlah_tersedia" name="jumlah_tersedia" required min="1">
                    </div>
                    <button type="submit" name="tambah_buku" class="btn btn-primary">Tambah Buku</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Buku</h4>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $index => $book): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($book['judul']) ?></td>
                                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                        <td><?= $book['jumlah_tersedia'] ?></td>
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