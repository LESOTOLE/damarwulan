<footer class="bg-dark text-white py-4 mt-auto">
    <div class="container text-center">
        <small>&copy; 2026 AutoParts Store. Seluruh Hak Cipta Dilindungi.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk menampilkan SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['alert_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $_SESSION['alert_success']; ?>',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['alert_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['alert_error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $_SESSION['alert_error']; ?>',
                confirmButtonColor: '#0d6efd'
            });
            <?php unset($_SESSION['alert_error']); ?>
        <?php endif; ?>
    });
</script>
</body>

</html>