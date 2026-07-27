<?php
session_start();
require 'config/koneksi.php';
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}
include 'includes/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    body {
        background: #f4f7fe;
        /* Warna dasar yang lebih lembut */
    }

    /* Background Aksen */
    .bg-decoration {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 300px;
        background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
        z-index: -1;
        border-radius: 0 0 50% 50% / 0 0 20% 20%;
    }

    .card-security {
        border-radius: 25px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-control-custom {
        border-radius: 12px;
        padding: 15px 20px;
        border: 2px solid #eee;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #1a237e;
        box-shadow: 0 0 15px rgba(26, 35, 126, 0.1);
        background: #fff;
    }

    /* Strength Meter */
    .strength-meter {
        height: 6px;
        background: #eee;
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
        display: none;
    }

    .strength-bar {
        height: 100%;
        width: 0;
        transition: all 0.5s ease;
    }

    .btn-update {
        border-radius: 12px;
        padding: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .btn-update:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(26, 35, 126, 0.2);
    }
</style>

<div class="bg-decoration"></div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-security animate__animated animate__fadeInUp">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/6195/6195699.png" width="80" class="animate__animated animate__pulse animate__infinite">
                        </div>
                        <h3 class="fw-800 text-dark">Keamanan Akun</h3>
                        <p class="text-muted">Halo <b><?= $_SESSION['nama']; ?></b>, perbarui sandi Anda agar tetap aman.</p>
                    </div>

                    <form action="aksi_password_pembeli.php" method="POST" id="formUpdate">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">PASSWORD BARU</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px; border: 2px solid #eee;">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" name="pass_baru" id="pass_baru" class="form-control form-control-custom border-start-0" placeholder="Ketik password baru..." required>
                            </div>
                            <div class="strength-meter" id="meterBox">
                                <div class="strength-bar" id="meterBar"></div>
                            </div>
                            <small id="strengthText" class="text-muted mt-1 d-block" style="font-size: 11px;"></small>
                        </div>

                        <div class="mb-5">
                            <label class="form-label small fw-bold text-muted">KONFIRMASI PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px; border: 2px solid #eee;">
                                    <i class="fas fa-shield-alt text-muted"></i>
                                </span>
                                <input type="password" name="konfirmasi" id="konfirmasi" class="form-control form-control-custom border-start-0" placeholder="Ulangi password..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 btn-update shadow">
                            SIMPAN PERUBAHAN <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none small text-muted">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const passInput = document.getElementById('pass_baru');
    const meterBox = document.getElementById('meterBox');
    const meterBar = document.getElementById('meterBar');
    const strengthText = document.getElementById('strengthText');

    // Fitur Strength Meter yang "Hidup"
    passInput.addEventListener('input', function() {
        const val = passInput.value;
        meterBox.style.display = val.length > 0 ? 'block' : 'none';

        let strength = 0;
        if (val.length >= 6) strength += 40;
        if (val.match(/[A-Z]/)) strength += 20;
        if (val.match(/[0-9]/)) strength += 20;
        if (val.match(/[^A-Za-z0-9]/)) strength += 20;

        meterBar.style.width = strength + '%';

        if (strength < 40) {
            meterBar.style.backgroundColor = '#ff4d4d';
            strengthText.innerText = 'Sangat Lemah';
        } else if (strength < 80) {
            meterBar.style.backgroundColor = '#ffd11a';
            strengthText.innerText = 'Cukup Kuat';
        } else {
            meterBar.style.backgroundColor = '#2ecc71';
            strengthText.innerText = 'Sangat Kuat!';
        }
    });

    document.getElementById('formUpdate').onsubmit = function(e) {
        const p1 = passInput.value;
        const p2 = document.getElementById('konfirmasi').value;

        if (p1.length < 6) {
            e.preventDefault();
            Swal.fire('Terlalu Pendek', 'Password minimal 6 karakter ya Tuan.', 'warning');
            return;
        }

        if (p1 !== p2) {
            e.preventDefault();
            Swal.fire('Ops!', 'Konfirmasi password tidak cocok.', 'error');
        }
    };
</script>