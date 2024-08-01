<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../authenticate/login.php");
    exit();
}
?>

<?php
$timeoutDuration = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeoutDuration) {
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
?>

<?php
include '../controllers/baglanti.php';
include '../controllers/islem.php';

include "admin-partials/_header.php";

$urunler = new Tayyip();
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Dashboard /</span> Ürün
            </h4>
            <a href="urun-ekle.php" class="btn btn-primary">Ürün Ekle</a>
        </div>
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fotoğraf</th>
                            <th>Başlık</th>
                            <th>Aksiyon</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php foreach ($urunler->getUrun() as $item) : ?>
                            <tr>
                                <td><img src="../images/<?php echo $item->fotograf ?>" width="50" alt="" class="img-fluid"></td>
                                <td><span class="fw-medium"><?php echo $item->baslik ?></span></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="urun-duzenle.php?id=<?php echo $item->id ?>"><i class="bx bx-edit-alt me-1"></i> Düzenle</a>
                                            <button class="dropdown-item Ssil" data-id="<?php echo $item->id ?>" data-add="<?php echo $item->baslik ?>" data-bs-toggle="modal" data-bs-target="#modalTsil"><i class="bx bx-trash me-1"></i> Sil</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<div class="modal fade" id="modalTsil" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <span><i class="bx bx-error-circle display-1 mb-3 text-warning"></i></span>
            <h4 class="modal-title "><span id="silAd"></span> silinsin mi?</h4>
            <p class="mb-3">Bu veriyi bir kez sildikten sonra geri getiremezsiniz! Silmek istediğinize emin misiniz?</p>
                <form action="urun-sil.php" method="post" style="display:inline;">
                    <input type="hidden" id="silInput" name="urunId" value="">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="deleteUrun" class="btn btn-primary">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "admin-partials/_footer.php" ?>