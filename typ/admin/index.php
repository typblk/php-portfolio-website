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

include "admin-partials/_header.php" ?>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-8 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Hoşgeldin! 🎉</h5>
                <p class="mb-4 motivasyon">
                </p>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#basicModal">Özgeçmiş </button>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img src="./assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="row">
          <div class=" col-6 col-lg-6 col-md-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img src="./assets/img/icons/unicons/customer-support.png" alt="chart success" class="rounded" />
                  </div>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                      <a class="dropdown-item" href="projeler.php">Projeler</a>
                      <a class="dropdown-item" href="proje-ekle.php">Proje Ekle</a>
                    </div>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Projeler</span>
                <?php
                $projeler = new Tayyip();
                $projeSayisi = $projeler->getProjeSayisi();
                ?>
                <h3 class="card-title mb-2"><?php echo htmlspecialchars($projeSayisi) ?></h3>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-6 col-md-12  mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img src="./assets/img/icons/unicons/flash.png" alt="Credit Card" class="rounded" />
                  </div>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                      <a class="dropdown-item" href="bloglar.php">Bloglar</a>
                      <a class="dropdown-item" href="blog-yaz.php">Blog Yaz</a>
                    </div>
                  </div>
                </div>
                <span>Bloglar</span>
                <?php
                $bloglar = new Tayyip();
                $blogSayisi = $bloglar->getBlogSayisi();
                ?>
                <h3 class="card-title text-nowrap mb-1"><?php echo htmlspecialchars($blogSayisi) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="col-12 col-lg-8 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Site Trafiği</h5>
              <?php
                $trafik = new Tayyip();
                $tarfikSayisi = $trafik->getTrafikSayisi();
                ?>
              <small class="text-muted">Toplam <?php echo htmlspecialchars($tarfikSayisi) ?> ziyaret</small>
            </div>
          </div>
          <div class="card-body px-0">
            <div class="tab-content p-0">
              <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                <div id="incomeChart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Total Revenue -->


      <div class="col-12 col-lg-4">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between pb-3">
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Blog Yazıları</h5>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                <a class="dropdown-item" href="bloglar.php">Blog</a>
                <a class="dropdown-item" href="blog-yaz.php">Blog Yaz</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <ul class="p-0 m-0">
              <?php
              $bloglar = new Tayyip();
              foreach ($bloglar->getBlogA() as $item) :
              ?>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../images/<?php echo htmlspecialchars($item->fotograf) ?>" width="50" alt="<?php echo htmlspecialchars($item->baslik) ?>">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0"><?php echo htmlspecialchars($item->baslik) ?></h6>
                      <small class="text-muted text-truncate"><?php echo htmlspecialchars($item->kisa_aciklama) ?></small>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- / Content -->

<?php
$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();

if (isset($_POST["submit"])) {
  $cv = $_POST["cv"];

  // Hataları ve başarı mesajını göster
  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo '
            <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Hata</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . htmlspecialchars($error) . '
                </div>
            </div>';
    }
  } else {
    if ($ayarlar->editcv($cv)) {
      echo '
            <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Başarılı</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Ayarlar başarıyla güncellendi.
                </div>
            </div>';
      echo '<script>window.location.href = "gizlilik.php";</script>';
      exit();
    } else {
      echo '
            <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Hata</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Ayarlar güncellenirken bir hata oluştu.
                </div>
            </div>';
    }
  }
}
?>


<!--modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-modal="true" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Özgeçmiş</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <form action="" method="post">
            <div class="col mb-3">
              <label for="cv" class="form-label">Özgeçmiş linki</label>
              <input type="text" id="cv" name="cv" class="form-control" value="<?php echo htmlspecialchars($item->cv) ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include "admin-partials/_footer.php" ?>