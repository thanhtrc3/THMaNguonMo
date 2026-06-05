<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d1117;
            --surface: #161b22;
            --surface2: #21262d;
            --border: #30363d;
            --border2: #21262d;
            --accent: #58a6ff;
            --accent-dim: #388bfd;
            --text: #c9d1d9;
            --muted: #8b949e;
            --mono: 'Inter', sans-serif;
            --sans: 'Outfit', sans-serif;
            --danger: #f85149;
            --teal: #2ea043;
        }
        body {
            background-color: var(--bg) !important;
            color: var(--text) !important;
            font-family: var(--sans) !important;
        }
        .navbar {
            background-color: var(--surface) !important;
            border-bottom: 1px solid var(--border);
            font-family: var(--mono);
            padding: 0.75rem 1.5rem;
        }
        .navbar-brand {
            color: var(--accent) !important;
            font-weight: 700;
            letter-spacing: -0.05em;
        }
        .nav-link {
            color: var(--muted) !important;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }
        .nav-link:hover {
            color: var(--accent) !important;
        }
        .navbar-toggler {
            border-color: var(--border) !important;
        }
        .navbar-toggler-icon {
            filter: invert(1);
        }
        
        /* Dropdown customization */
        .dropdown-menu {
            background-color: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 4px;
            margin-top: 0.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .dropdown-item {
            color: var(--muted) !important;
            font-family: var(--mono);
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 0.62rem 1.25rem;
            transition: background 0.2s, color 0.2s;
        }
        .dropdown-item:hover {
            background-color: rgba(37, 99, 235, 0.08) !important;
            color: var(--accent) !important;
        }
        .dropdown-divider {
            border-top: 1px solid var(--border) !important;
        }
        
        footer {
            background-color: var(--surface) !important;
            border-top: 1px solid var(--border);
            color: var(--muted) !important;
            font-family: var(--mono);
            font-size: 0.75rem;
        }
        footer a {
            color: var(--muted) !important;
            transition: color 0.2s;
        }
        footer a:hover {
            color: var(--accent) !important;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--surface) !important; border-bottom: 1px solid var(--border);">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/Product/">// CYBER STORE</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/Product/list">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/Product/cart">
                        Giỏ hàng 
                        <span class="badge badge-pill" style="background-color: var(--accent); color: #ffffff; font-weight: 700; margin-left: 2px; font-size: 0.65rem; padding: 0.2rem 0.4rem;">
                             <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>
                        </span>
                    </a>
                </li>
            </ul>
            
            <!-- Global Search Form -->
            <form class="form-inline my-2 my-lg-0 mr-lg-3 position-relative" action="<?php echo BASE_URL; ?>/Product/list" method="GET">
                <input class="form-control mr-sm-2" type="search" name="q" placeholder="Tìm sản phẩm..." aria-label="Search" style="background: var(--surface2); border: 1px solid var(--border); border-radius: 3px; color: var(--text); font-size: 0.75rem; padding-left: 2.2rem; width: 220px; height: 36px; outline: none;" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.85rem; pointer-events: none;">⌕</span>
            </form>
            
            <!-- Admin Dropdown Menu -->
            <ul class="navbar-nav">
                <?php if (SessionHelper::isAdmin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 1px solid var(--border); padding: 0.4rem 0.9rem; border-radius: 3px; background: rgba(37, 99, 235, 0.05); color: var(--accent) !important; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em;">
                        QUẢN TRỊ
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/Product/manage">Quản lý Sản phẩm</a>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/Category/">Quản lý Danh mục</a>
                    </div>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                <?php
                if (SessionHelper::isLoggedIn()) {
                    echo "<a class='nav-link' href='#' style='color: #fff !important; font-weight: 600;'>👋 " . htmlspecialchars($_SESSION['username']) . "</a>";
                } else {
                    echo "<a class='nav-link' href='" . BASE_URL . "/account/login'>Đăng nhập</a>";
                }
                ?>
                </li>
                
                <?php if (SessionHelper::isLoggedIn()): ?>
                <li class="nav-item">
                    <a class='nav-link' href='<?php echo BASE_URL; ?>/account/logout' style="color: var(--danger) !important;">Đăng xuất</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class='nav-link' href='<?php echo BASE_URL; ?>/account/register' style="border: 1px solid var(--accent); padding: 0.3rem 0.8rem; border-radius: 4px; color: var(--accent) !important; margin-left: 10px;">Đăng ký</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
