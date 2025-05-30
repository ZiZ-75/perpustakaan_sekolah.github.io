<?php
require 'includes/config.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = 'siswa'; // Default role siswa
    
    if (registerUser($username, $password, $nama_lengkap, $role)) {
        header('Location: login.php?registered=1');
        exit();
    } else {
        $error = "Gagal mendaftar. Username mungkin sudah digunakan.";
    }
}

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    redirectBasedOnRole();
}
?>

<?php include 'includes/header.php'; ?>

<div class="form-container">
    <h2 class="text-center mb-4">Register</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>
    
    <div class="mt-3 text-center">
        Sudah punya akun? <a href="login.php">Login disini</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>