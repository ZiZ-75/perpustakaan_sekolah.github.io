<?php
require 'includes/config.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: siswa/dashboard.php');
        }
        exit();
    } else {
        $error = "Username atau password salah";
    }
}

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    redirectBasedOnRole();
}
?>

<?php include 'includes/header.php'; ?>

<div class="form-container">
    <h2 class="text-center mb-4">Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    
    <div class="mt-3 text-center">
        Belum punya akun? <a href="register.php">Daftar disini</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>