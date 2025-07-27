<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url('pages/beranda.php'); ?>">Rusyad Online Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('pages/produk.php'); ?>">Produk</a>
                </li>
                <?php if (is_logged_in()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('pages/keranjang.php'); ?>">Keranjang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('pages/transaksi.php'); ?>">Transaksi</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (is_logged_in()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo base_url('processes/auth_process.php?action=logout'); ?>">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('pages/login.php'); ?>">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('pages/register.php'); ?>">Register</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>