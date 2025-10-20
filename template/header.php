<?php
// Lokasi file: /template/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Pastikan koneksi.php dipanggil dengan path yang benar relatif dari header.php
include_once(__DIR__ . "/../koneksi.php"); 

// Dapatkan path skrip saat ini untuk menandai link aktif
$current_page = basename($_SERVER['SCRIPT_NAME']);
$current_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 1.5rem;
            flex-shrink: 0; /* Mencegah sidebar menyusut */
        }

        .sidebar .nav-link {
            color: #adb5bd;
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.25rem;
            margin: 0.25rem 1rem;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px; /* Lebar ikon agar teks rata */
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: #0d6efd;
            font-weight: bold;
        }

        .sidebar .sidebar-header {
            padding: 0 1.5rem 1.5rem 1.5rem;
            border-bottom: 1px solid #495057;
            margin-bottom: 1rem;
            text-align: center;
        }

        .sidebar .sidebar-header h3 {
            margin-bottom: 0;
            color: #2AF598; 
        }
        
        .main-content {
            flex-grow: 1;
            padding: 2.5rem;
            overflow-x: auto; /* Ubah ke auto jika tabel lebar */
        }

        /* Tombol sidebar toggle untuk mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1050; /* Pastikan di atas sidebar */
            background-color: rgba(0,0,0,0.5); /* Semi transparan */
            border: none;
        }
        .sidebar-toggle:focus {
            box-shadow: none; /* Hilangkan shadow saat focus */
        }

        .sidebar-close {
             display: none; /* Sembunyikan by default */
        }


        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 280px; /* Lebar tetap saat muncul */
                position: fixed; /* Ubah ke fixed */
                top: 0;
                left: -280px; /* Sembunyikan di luar layar kiri */
                z-index: 1040;
                height: 100%; /* Tinggi penuh */
                transition: left 0.3s ease-in-out;
                padding-top: 60px; /* Ruang untuk tombol close */
            }
            .sidebar.active {
                left: 0; /* Tampilkan sidebar */
            }
            .main-content {
                padding: 1.5rem;
                padding-top: 70px; /* Beri ruang untuk tombol toggle */
                width: 100%; /* Pastikan lebar penuh */
            }
            .sidebar-toggle {
                display: block; /* Tampilkan tombol toggle */
            }
            .sidebar-close {
                display: block; /* Tampilkan tombol close */
                position: absolute;
                top: 15px;
                right: 15px;
                font-size: 1.5rem;
                color: white;
                background: none;
                border: none;
                padding: 0.5rem;
                line-height: 1;
            }
        }
    </style>
</head>

<body>
    <button class="btn btn-dark btn-lg sidebar-toggle" id="sidebarToggleBtn">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="bi bi-tree-fill"></i> SmartFarm</h3>
            <small class="text-muted">Manajemen Pertanian</small>
        </div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_dir == 'produk') ? 'active' : ''; ?>" href="../produk/index.php">
                    <i class="bi bi-box-seam-fill"></i>
                    Manajemen Produk
                </a>
            </li>
            <li class="nav-item">
                 <a class="nav-link <?php echo ($current_dir == 'gudang') ? 'active' : ''; ?>" href="../gudang/index.php">
                    <i class="bi bi-house-door-fill"></i>
                    Manajemen Gudang
                </a>
            </li>
        </ul>
        <div class="text-center p-3 text-muted" style="border-top: 1px solid #495057;">
            <small>&copy; <?php echo date('Y'); ?> SmartFarm</small>
        </div>
        
        <button class="sidebar-close" id="sidebarCloseBtn">&times;</button>
    </div>

    <div class="main-content">
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
        </div>