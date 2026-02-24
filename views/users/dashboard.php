<?php
session_start();
require_once(__DIR__ . '/../../src/controllers/AuthController.php');
require_once(__DIR__ . '/../../src/controllers/UserController.php');

$auth = new AuthController();
if(!$auth->isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

$userCtrl = new UserController();
$users = $userCtrl->getAllUsers();

// capture flash message
$flash = null;
if(isset($_SESSION['msg'])){
    $flash = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        :root {
            --primary-color: #C8D9E6; /* soft blue */
            --secondary-color: #F5EFEB; /* warm cream */
            --surface-color: #FFFFFF; /* white */
            --gray-color: #6B7280;
            --gray-light: #9CA3AF;
            --gray-lighter: #D1D5DB;
            --gray-bg: #F7F7F8;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
            --dark: #111827;
            --light: #ffffff;
        }
        
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--surface-color) 100%);
            background-size: 200% 200%;
            animation: gradient 20s ease infinite;
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--dark);
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .navbar-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 8px 24px rgba(17,24,39,0.06);
            padding: 1rem 0;
            position: relative;
            overflow: hidden;
            color: var(--dark);
        }
        
        .navbar-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark) !important;
            letter-spacing: 1.5px;
            text-shadow: 0 1px 6px rgba(0,0,0,0.04);
            position: relative;
            z-index: 1;
        }
        
        .user-info {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border: 2px solid rgba(255,255,255,0.65);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            animation: float 3s ease-in-out infinite;
            color: var(--dark);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .container {
            position: relative;
            z-index: 1;
        }
        
        .dashboard-header {
            margin-bottom: 3rem;
            text-align: center;
            animation: slideInDown 0.6s ease;
        }
        
        .dashboard-header h2 {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .dashboard-header p {
            font-size: 1.2rem;
            color: rgba(17,24,39,0.8);
            font-weight: 500;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 102, 255, 0.12);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
            border: 1px solid rgba(6, 182, 212, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.6s ease forwards;
            animation-delay: calc(var(--index, 0) * 0.1s);
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--grad-color, #667eea), transparent);
            border-radius: 50%;
            opacity: 0;
            transition: all 0.3s ease;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
            50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.6); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .stat-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 15px 40px rgba(31, 38, 135, 0.3);
            border-color: rgba(255,255,255,0.5);
            animation: glow 1.5s ease-in-out infinite;
        }
        
        .stat-card h3 {
            background: linear-gradient(135deg, #0066FF, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 900;
            font-size: 2.5rem;
            margin: 1rem 0 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .stat-card p {
            color: var(--gray-color);
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .btn-custom {
            border-radius: 12px;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            font-size: 0.95rem;
        }
        
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.3);
            transition: left 0.3s ease;
            z-index: -1;
        }
        
        .btn-custom:hover::before {
            left: 100%;
        }
        
        .btn-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.3);
        }
        
        .btn-custom:active {
            transform: translateY(-2px);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--dark);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .btn-primary-custom::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary-custom:hover {
            color: var(--dark);
            background: linear-gradient(135deg, var(--secondary-color), var(--surface-color));
        }

        .btn-primary-custom:hover::after {
            left: 100%;
        }
        
        .search-box {
            border-radius: 12px;
            border: 1px solid var(--gray-lighter);
            padding: 0.85rem 1rem;
            width: 100%;
            background: var(--surface-color);
            transition: all 0.2s ease;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-color);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--surface-color);
            box-shadow: 0 6px 18px rgba(0,0,0,0.04);
            transform: scale(1.01);
        }

        .search-box::placeholder {
            color: var(--gray-lighter);
        }
        
        .table-custom {
            background: var(--surface-color);
            backdrop-filter: blur(6px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(17,24,39,0.04);
            border: 1px solid rgba(0,0,0,0.04);
            animation: slideInUp 0.8s ease;
        }

        .table-custom thead {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: var(--dark);
        }
        
        .table-custom thead th {
            font-weight: 700;
            padding: 1.5rem 1rem;
            letter-spacing: 0.5px;
        }
        
        .table-custom tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .table-custom tbody tr:hover {
            background: linear-gradient(90deg, rgba(0, 102, 255, 0.05), rgba(6, 182, 212, 0.05));
            transform: scale(1.01);
            box-shadow: inset 0 0 10px rgba(0, 102, 255, 0.1);
            padding-left: 10px;
        }
        
        .table-custom tbody tr:hover td {
            color: #0066FF;
        }
        
        .table-custom tbody tr:hover td:first-child {
            font-weight: 800;
            text-shadow: 0 0 10px rgba(0, 102, 255, 0.25);
        }
        
        .user-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(17,24,39,0.06);
            border: 2px solid rgba(255,255,255,0.7);
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(8px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid#f0f0f0;
            padding-bottom: 1.5rem;
        }
        
        .modal-header h4 {
            margin: 0;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
        }
        
        .modal-close-btn {
            background: none;
            border: none;
            font-size: 32px;
            cursor: pointer;
            color: var(--gray-lighter);
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .modal-close-btn:hover {
            color: #0066FF;
            background: rgba(0, 102, 255, 0.1);
            transform: rotate(90deg) scale(1.2);
        }
        
        .field {
            margin-bottom: 1.5rem;
        }
        
        .field label {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
            display: block;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }
        
        .field input[type="text"],
        .field input[type="email"] {
            border-radius: 12px;
            border: 2px solid var(--gray-lighter);
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--gray-bg);
            font-family: inherit;
            color: var(--gray-color);
        }
        
        .field input[type="text"]:focus,
        .field input[type="email"]:focus {
            outline: none;
            border-color: #0066FF;
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.12);
            transform: translateY(-2px);
            color: var(--dark);
        }
        
        .field input[type="text"]::placeholder,
        .field input[type="email"]::placeholder {
            color: #d1d5db;
        }
        
        .field input[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--dark);
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 1.1rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 18px rgba(17,24,39,0.06);
            position: relative;
            overflow: hidden;
            font-family: inherit;
            letter-spacing: 0.5px;
        }
        
        .field input[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .field input[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 102, 255, 0.4);
        }
        
        .field input[type="submit"]:hover::before {
            left: 100%;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                backdrop-filter: blur(5px);
            }
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .action-bar {
            margin-bottom: 2rem;
            animation: slideInUp 0.7s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-header h2 {
                font-size: 2.2rem;
            }
            
            .btn-custom {
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
            }
            
            .stat-card {
                padding: 1.5rem 1rem;
            }
            
            .table-custom {
                font-size: 0.9rem;
            }
            
            .user-cell {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Utility Classes */
        .d-flex { display: flex; }
        .gap-2 { gap: 0.5rem; }
        .justify-content-lg-end { justify-content: flex-end; }
        .flex-wrap { flex-wrap: wrap; }
        .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        .min-width-150 { min-width: 150px; }
        
        @media (max-width: 992px) {
            .justify-content-lg-end { justify-content: center; }
        }
        
        /* Focus & Accessibility */
        .btn-custom:focus,
        .search-box:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style></head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-custom">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">
            <i class="bi bi-rocket-takeoff" style="margin-right: 10px; font-size: 1.5rem;"></i><span style="letter-spacing: 2px;">CRUD PRO</span>
        </a>
        <div class="user-info" style="position: relative;">
            <div class="avatar" style="animation: float 3s ease-in-out infinite; --index: 1; cursor: pointer;" onclick="toggleUserMenu()" title="Click for options"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 1rem;"><?= $_SESSION['user_name'] ?></div>
                <small style="opacity: 0.8;"><?= $_SESSION['user_email'] ?></small>
            </div>
            <a href="../auth/logout.php" style="margin-left: 20px; color: rgba(255,255,255,0.7); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;" onmouseover="this.style.color='white'; this.style.textShadow='0 0 10px rgba(255,255,255,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.textShadow='none'" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid px-4" style="padding-top: 3rem; padding-bottom: 3rem;">
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-5">
        <h2 style="color: white; margin-bottom: 0.5rem;">
            <span style="color: white; font-weight: 900; font-size: 3.5rem; text-shadow: 0 3px 20px rgba(0, 102, 255, 0.3);">
                User Management
            </span>
        </h2>
        <!-- <p style="color: rgba(255,255,255,0.95); font-size: 1.2rem; font-weight: 500; letter-spacing: 0.3px;">Organize and manage all your users with ease</p> -->
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4" style="--index: 0;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-people" style="font-size: 2.5rem;"></i></div>
                <h3 style="font-size: 2.8rem; margin: 1rem 0 0.5rem; background: linear-gradient(135deg, #0066FF, #06B6D4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= $users->num_rows ?></h3>
                <p style="color: var(--gray-color); font-weight: 600; font-size: 1rem;">Total Users</p>
                <div style="font-size: 0.9rem; color: var(--gray-light); margin-top: 0.75rem;">Updated just now</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" style="--index: 1;">
            <div class="stat-card" onclick="openModal('add')" style="cursor: pointer;">
                <div class="stat-icon"><i class="bi bi-person-plus" style="font-size: 2.5rem;"></i></div>
                <h3 style="font-size: 2.5rem; margin: 1rem 0 0.5rem; background: linear-gradient(135deg, #22D3EE, #06B6D4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Quick</h3>
                <p style="color: var(--gray-color); font-weight: 600; font-size: 1rem;">Add New User</p>
                <div style="font-size: 0.9rem; color: var(--gray-light); margin-top: 0.75rem;">Click to open</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" style="--index: 2;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-pencil-square" style="font-size: 2.5rem;"></i></div>
                <h3 style="font-size: 2.5rem; margin: 1rem 0 0.5rem; background: linear-gradient(135deg, #06B6D4, #22D3EE); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Edit</h3>
                <p style="color: var(--gray-color); font-weight: 600; font-size: 1rem;">User Information</p>
                <div style="font-size: 0.9rem; color: var(--gray-light); margin-top: 0.75rem;">Manage details</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" style="--index: 3;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-shield-lock" style="font-size: 2.5rem;"></i></div>
                <h3 style="font-size: 2.5rem; margin: 1rem 0 0.5rem; background: linear-gradient(135deg, #0066FF, #06B6D4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Secure</h3>
                <p style="color: var(--gray-color); font-weight: 600; font-size: 1rem;">Password Hashing</p>
                <div style="font-size: 0.9rem; color: var(--gray-light); margin-top: 0.75rem;">BCrypt enabled</div>
            </div>
        </div>
    </div>
    
    <!-- Action Bar with Search -->
    <div class="action-bar">
        <div class="row">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div style="position: relative;">
                    <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search by name or email...">
                    <div style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--gray-light); pointer-events: none; font-size: 1.3rem;">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex gap-2 justify-content-lg-end" style="gap: 1rem !important; flex-wrap: wrap;">
                    <div style="position: relative; right: 40%; display:flex; gap:0.75rem; align-items:center;">
                        <button type="button" class="btn-custom btn-primary-custom" onclick="openModal('add')" style="min-width: 120px; padding: 0.45rem 0.9rem; font-size: 0.9rem;">
                            <i class="bi bi-plus-circle"></i> Add
                        </button>
                        <button type="button" class="btn-custom" onclick="massDel()" style="min-width: 120px; padding: 0.45rem 0.9rem; font-size: 0.9rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.18);">
                            <i class="bi bi-trash3"></i> Multi-Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="table-custom mt-4 mb-5">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 5%;">#</th>
                    <th scope="col" style="width: 30%;">User Profile</th>
                    <th scope="col" style="width: 35%;">Email Address</th>
                    <th scope="col" style="width: 30%;">Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
            <?php foreach($users as $index => $data){ ?>
                <tr class="user-row" data-name="<?= strtolower($data['name']) ?>" data-email="<?= strtolower($data['email']) ?>" style="position: relative;">
                    <td style="vertical-align: middle; font-weight: 800; color: #0066FF; font-size: 1.05rem;"><?= $index + 1; ?></td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar" style="width: 45px; height: 45px; font-size: 1.2rem; box-shadow: 0 4px 18px rgba(0, 102, 255, 0.3); color: white;"><?= strtoupper(substr($data['name'], 0, 1)) ?></div>
                            <div>
                                <strong style="display: block; font-size: 1.05rem; color: var(--dark); font-weight: 700;"><?= $data['name']; ?></strong>
                                <small style="color: var(--gray-light); font-weight: 500;">ID: <?= $data['id'] ?></small>
                            </div>
                        </div>
                    </td>
                    <td style="vertical-align: middle; color: var(--gray-color); font-weight: 500; font-size: 0.95rem;"><?= $data['email']; ?></td>
                    <td style="vertical-align: middle;">
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button type="button" class="btn-custom btn-primary-custom" onclick="openModal('edit', <?= htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') ?>)" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn-custom" onclick='delUser(<?=json_encode($data);?>)' style="background: linear-gradient(135deg, #ef4444, #dc26268f); color: white; padding: 0.5rem 1rem; font-size: 0.9rem; box-shadow: 0 2px 10px rgba(239, 68, 68, 0.25);">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                            <input type="checkbox" class="form-check-input select_user" data-id="<?=$data['id']?>" style="width: 20px; height: 20px; cursor: pointer; margin-top: 0.25rem;" title="Select for bulk operations"/>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    
    <!-- Footer Info -->
    <!-- <div style="text-align: center; color: rgba(255,255,255,0.75); margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.12);">
        <p style="font-size: 0.95rem; font-weight: 500; letter-spacing: 0.3px;">Total: <strong style="color: rgba(255,255,255,0.98); font-weight: 700;"><?= $users->num_rows ?></strong> users | Last updated: <strong style="color: rgba(255,255,255,0.98); font-weight: 700;">just now</strong></p>
    </div> -->
</div>

<!-- Reusable Modal -->
<div id="userModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="modalTitle">Add User</h4>
            <button class="modal-close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm" method="POST">
            <div class="field padding-bottom--24">
                <label for="modalName">Name</label>
                <input type="text" id="modalName" name="name" class="form-control" required>
            </div>
            <div class="field padding-bottom--24">
                <label for="modalEmail">Email</label>
                <input type="email" id="modalEmail" name="email" class="form-control" required>
            </div>
            <div class="field padding-bottom--24">
                <input type="hidden" id="modalUserId" name="user_id" value="">
                <input type="hidden" id="modalMode" name="mode" value="add">
                <input type="submit" name="submit" value="Save User" class="btn btn-primary-custom btn-custom btn-block" style="background: var(--primary-color); width: 100%; padding: 0.75rem;">
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<script>
function openModal(mode, userData = null) {
    const modal = document.getElementById('userModal');
    const title = document.getElementById('modalTitle');
    const nameInput = document.getElementById('modalName');
    const emailInput = document.getElementById('modalEmail');
    const userIdInput = document.getElementById('modalUserId');
    const modeInput = document.getElementById('modalMode');
    
    if(mode === 'add') {
        title.textContent = 'Add User';
        nameInput.value = '';
        emailInput.value = '';
        userIdInput.value = '';
        modeInput.value = 'add';
    } else if(mode === 'edit' && userData) {
        title.textContent = 'Edit User';
        nameInput.value = userData.name;
        emailInput.value = userData.email;
        userIdInput.value = userData.id;
        modeInput.value = 'edit';
    }
    
    modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('userModal');
    modal.classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('userModal').addEventListener('click', function(e) {
        if(e.target === this) {
            closeModal();
        }
    });

    // Handle form submission  
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const mode = document.getElementById('modalMode').value;
        const name = document.getElementById('modalName').value;
        const email = document.getElementById('modalEmail').value;
        const userId = document.getElementById('modalUserId').value;
        
        // Send request via fetch
        fetch('process-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'mode=' + mode + '&name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&user_id=' + userId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                swal({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    button: true
                }).then(() => {
                    location.reload();
                });
            } else {
                swal({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    button: true
                });
            }
        });
    });
});

function delUser(row){
swal({
    title: 'Are you sure?',
    text: 'Are you sure you want to delete ' + row.name + '?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
}).then((willDelete) => {
    if (willDelete) {
        location.href = 'delete.php?id=' + row.id;
    }
});
}

var selected = []

function select(id){
  let sel_ids = selected.includes(id)
  if(sel_ids){
    let index = selected.findIndex(s => id)
    selected.splice(index, 1) 
  } else {
    let s = selected.push(id)
    console.log(s)
  }
}

function massDel(){
  if(selected.length == 0){
    alert('No user selected')
  } else {
    swal({
      title: 'Are you sure?',
      text: 'Are you sure you want to delete selected users?',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        let ids = selected.join(',')
        location.href = 'delete.php?many=1&id=' + ids
      }
    });
  }
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        
        if(name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Update selected checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select_user').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const id = parseInt(this.getAttribute('data-id'));
            if(this.checked && !selected.includes(id)) {
                selected.push(id);
            } else if(!this.checked && selected.includes(id)) {
                selected = selected.filter(s => s !== id);
            }
        });
    });
});
</script>

<?php if(!empty($flash)): ?>
<script>
  swal({
    title: <?= json_encode($flash['success'] ? 'Success' : 'Error') ?>,
    text: <?= json_encode($flash['message']) ?>,
    icon: <?= json_encode($flash['success'] ? 'success' : 'error') ?>,
    timer: 4000,
    buttons: false
  });
</script>
<?php endif; ?>

</body>
</html>
