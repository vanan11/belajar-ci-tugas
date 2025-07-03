<?= $this->extend('layout') ?> // **PERBAIKAN:** Mengarah langsung ke 'layout' karena file ada di app/Views/layout.php
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<?php if (session()->getFlashData('failed')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<!-- Tombol Tambah Diskon -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Diskon
</button>

<table class="table datatable mt-3">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($diskon as $i => $item): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $item['tanggal'] ?></td>
                <td>Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
                <td>
                    <!-- Tombol Ubah yang memicu modal edit -->
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?= $item['id'] ?>">
                        Ubah
                    </button>
                    <!-- Tombol Hapus (menggunakan form untuk method DELETE) -->
                    <form action="<?= base_url('diskon/delete/' . $item['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin hapus diskon ini?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal (berada di dalam loop karena datanya spesifik untuk setiap baris) -->
            <div class="modal fade" id="editModal-<?= $item['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel-<?= $item['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="<?= base_url('diskon/update/' . $item['id']) ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT"> <!-- Penting untuk metode PUT di CodeIgniter -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel-<?= $item['id'] ?>">Edit Diskon</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-2">
                                    <label for="tanggal-<?= $item['id'] ?>">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal-<?= $item['id'] ?>" name="tanggal" value="<?= $item['tanggal'] ?>" readonly>
                                    <small class="form-text text-muted">Tanggal diskon tidak bisa diubah.</small>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="nominal-<?= $item['id'] ?>">Nominal</label>
                                    <input type="number" class="form-control" id="nominal-<?= $item['id'] ?>" name="nominal" value="<?= $item['nominal'] ?>" required>
                                    <?php if (session('validation') && session('validation')->hasError('nominal') && session('validation_id') == $item['id']): // Tampilkan error jika ada dan sesuai ID ?>
                                        <div class="invalid-feedback d-block">
                                            <?= session('validation')->getError('nominal') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Edit Modal -->

        <?php endforeach ?>
    </tbody>
</table>

<!-- Add Modal (berada di luar loop karena hanya satu) -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('diskon/store') ?>" method="post">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (session('validation_add')): // Tampilkan error validasi khusus untuk Add Modal ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php foreach (session('validation_add')->getErrors() as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mb-2">
                        <label for="addTanggal">Tanggal</label>
                        <input type="date" class="form-control <?= (session('validation_add') && session('validation_add')->hasError('tanggal')) ? 'is-invalid' : '' ?>" id="addTanggal" name="tanggal" value="<?= old('tanggal') ?>" required>
                        <?php if (session('validation_add') && session('validation_add')->hasError('tanggal')): ?>
                            <div class="invalid-feedback d-block">
                                <?= session('validation_add')->getError('tanggal') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="addNominal">Nominal</label>
                        <input type="number" class="form-control <?= (session('validation_add') && session('validation_add')->hasError('nominal')) ? 'is-invalid' : '' ?>" id="addNominal" name="nominal" value="<?= old('nominal') ?>" required>
                        <?php if (session('validation_add') && session('validation_add')->hasError('nominal')): ?>
                            <div class="invalid-feedback d-block">
                                <?= session('validation_add')->getError('nominal') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Add Modal -->

<!-- Script untuk menampilkan modal jika ada error validasi setelah redirect -->
<?= $this->section('script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tampilkan Add Modal jika ada error validasi dari form Tambah
        <?php if (session('validation_add')): ?>
            var addDiskonModal = new bootstrap.Modal(document.getElementById('addModal'));
            addDiskonModal.show();
        <?php endif; ?>

        // Tampilkan Edit Modal jika ada error validasi dari form Edit tertentu
        <?php if (session('validation') && session('validation_id')): ?>
            var editModalId = 'editModal-<?= session('validation_id') ?>';
            var editDiskonModal = new bootstrap.Modal(document.getElementById(editModalId));
            editDiskonModal.show();
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
