<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damar Wulan AC - Suku Cadang & Perbaikan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html,
        body {
            height: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f7fe;
        }

        /* Bagian konten utama akan mengambil sisa ruang yang ada */
        main,
        .main-content,
        .auth-wrapper {
            flex: 1 0 auto;
        }

        /* Layout Khusus Auth */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            background-color: var(--bg-light);
        }

        .auth-side-info {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--royal-blue) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        /* Pola dekoratif agar tidak kosong */
        .auth-side-info::before {
            content: "";
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .input-group-text {
            background-color: #fafafa;
            border-right: none;
            color: var(--primary-navy);
            border-radius: 12px 0 0 12px;
        }

        .form-control-with-icon {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        /* Hero Section Styling */
        .hero-section {
            padding: 80px 0;
            background-color: var(--bg-light);
        }

        .hero-title {
            font-size: 3.5rem;
            line-height: 1.1;
            color: var(--primary-navy);
        }

        .hero-title span {
            color: var(--accent-red);
            /* Highlight warna merah logo */
        }

        /* Search Bar Styling */
        .search-container {
            max-width: 600px;
            background: #fff;
            padding: 8px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            border: none !important;
            box-shadow: none !important;
            padding-left: 20px;
        }

        .btn-search {
            background-color: var(--primary-navy);
            color: white;
            border-radius: 15px;
            padding: 10px 30px;
            font-weight: 700;
        }

        /* Filter Sidebar */
        .filter-card {
            border: none;
            border-radius: 25px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        /* Product Card Rebranding */
        .product-card {
            border: none;
            border-radius: 24px;
            background: #fff;
            transition: 0.4s;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(26, 35, 126, 0.1);
        }

        .product-badge {
            background: rgba(211, 47, 47, 0.1);
            color: var(--accent-red);
            font-size: 10px;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 10px;
        }

        .btn-add-cart {
            background-color: var(--royal-blue);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .btn-add-cart:hover {
            background-color: var(--accent-red);
            color: white;
            transform: rotate(90deg);
        }

        footer {
            flex-shrink: 0;
            background-color: #1a1d23;
            /* Warna gelap standar atau Navy logo Tuan */
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>

<body>