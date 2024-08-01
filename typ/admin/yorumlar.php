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

$yorumlar = new Tayyip();
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Dashboard /</span> Yorumlar
            </h4>
            <a href="yorum-ekle.php" class="btn btn-primary">Yorum Ekle</a>
        </div>
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fotoğraf</th>
                            <th>Yorum Yapan</th>
                            <th>Aksiyon</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php foreach ($yorumlar->getYorum() as $item) : ?>
                            <tr>
                                <td><img src="../images/<?php echo $item->fotograf ?>" width="50" alt="" class="img-fluid"></td>
                                <td><span class="fw-medium"><?php echo $item->yorum_yapan ?></span></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="yorum-duzenle.php?id=<?php echo $item->id ?>"><i class="bx bx-edit-alt me-1"></i> Düzenle</a>
                                            <button class="dropdown-item Ssil" data-id="<?php echo $item->id ?>" data-add="<?php echo $item->yorum_yapan ?>" data-bs-toggle="modal" data-bs-target="#modalYsil"><i class="bx bx-trash me-1"></i> Sil</button>
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

<div class="modal fade" id="modalYsil" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <span><i class="bx bx-error-circle display-1 mb-3 text-warning"></i></span>
            <h4 class="modal-title "><span id="silAd"></span> silinsin mi?</h4>
            <p class="mb-3">Bu veriyi bir kez sildikten sonra geri getiremezsiniz! Silmek istediğinize emin misiniz?</p>
                <form action="yorum-sil.php" method="post" style="display:inline;">
                    <input type="hidden" id="silInput" name="yorumId" value="">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="deleteYorum" class="btn btn-primary">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "admin-partials/_footer.php" ?>