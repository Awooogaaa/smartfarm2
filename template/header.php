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
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fc 100%);
            color: #2d3748;
            padding-top: 1.5rem;
            flex-shrink: 0;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #e2e8f0;
        }

        .sidebar .nav-link {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            margin: 0.35rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: #0ea5e9;
            background: linear-gradient(135deg, #e0f2fe 0%, #e0f2fe 100%);
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        .sidebar .nav-link.active {
            color: #0ea5e9;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: linear-gradient(180deg, #0ea5e9 0%, #06b6d4 100%);
            border-radius: 0 4px 4px 0;
        }

        .sidebar .sidebar-header {
            padding: 0 1.5rem 1.5rem 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
        }

        .sidebar .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #0ea5e9 0%, #06b6d4 100%);
            border-radius: 2px;
        }

        .sidebar .sidebar-header h3 {
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .sidebar .sidebar-header h3 i {
            font-size: 1.6rem;
            vertical-align: middle;
            margin-right: 8px;
        }

        .sidebar .sidebar-header small {
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 2.5rem;
            overflow-x: auto;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            background: linear-gradient(180deg, transparent 0%, #f8f9fc 100%);
        }

        .sidebar-footer small {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Tombol sidebar toggle untuk mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1050;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
        }

        .sidebar-toggle:focus {
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3);
        }

        .sidebar-toggle i {
            font-size: 1.5rem;
            color: white;
        }

        .sidebar-close {
            display: none;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 280px;
                position: fixed;
                top: 0;
                left: -280px;
                z-index: 1040;
                height: 100%;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                padding-top: 60px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                padding: 1.5rem;
                padding-top: 80px;
                width: 100%;
            }
            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .sidebar-close {
                display: block;
                position: absolute;
                top: 15px;
                right: 15px;
                font-size: 1.8rem;
                color: #64748b;
                background: #f1f5f9;
                border: none;
                padding: 0.5rem;
                line-height: 1;
                width: 40px;
                height: 40px;
                border-radius: 10px;
                transition: all 0.3s ease;
            }
            .sidebar-close:hover {
                background: #e2e8f0;
                color: #0ea5e9;
                transform: rotate(90deg);
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <button class="btn sidebar-toggle" id="sidebarToggleBtn">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="bi bi-tree-fill"></i>SmartFarm</h3>
            <small>Manajemen Pertanian</small>
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
        <div class="sidebar-footer">
            <small>&copy; <?php echo date('Y'); ?> SmartFarm. All rights reserved.</small>
        </div>
        
        <button class="sidebar-close" id="sidebarCloseBtn">&times;</button>
    </div>

    <div class="main-content">
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
        </div>
        
    <script>
        // Fungsi untuk toggle sidebar di mobile
        document.getElementById('sidebarToggleBtn')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('sidebarOverlay').classList.add('active');
        });

        document.getElementById('sidebarCloseBtn')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });

        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            this.classList.remove('active');
        });
    </script>