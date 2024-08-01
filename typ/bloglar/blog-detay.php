<?php
include '../controllers/baglanti.php';
include '../controllers/islem.php';

$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<?php
$blogUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
$bloglar = new Tayyip();
$itemb = $bloglar->getBlogSlug($blogUrl);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($item->site_adi) ?> | <?php echo htmlspecialchars($itemb->baslik) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo htmlspecialchars($itemb->baslik) ?>">
    <meta name="author" content="Tayyip Bölük">
    <meta name="description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($item->site_keyw) ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars($item->site_url) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="Sebsite">
    <meta property="og:url" content="<?php echo htmlspecialchars($item->site_url) ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo htmlspecialchars($itemb->baslik) ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="og:image" content="../images/<?php echo htmlspecialchars($item->logo) ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo htmlspecialchars($item->site_url) ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo htmlspecialchars($itemb->baslik) ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="twitter:image" content="./images/<?php echo htmlspecialchars($item->logo) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="./images/<?php echo htmlspecialchars($item->favicon) ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Fontawesome -->
    <link type="text/css" href="../vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Swipe CSS -->
    <link type="text/css" href="../css/swipe.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

    <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->

</head>

<body>
    <header class="header-global" id="home">
        <nav id="navbar-main" aria-label="Primary navigation" class="navbar navbar-main navbar-expand-lg navbar-theme-primary headroom navbar-light navbar-theme-secondary">
            <div class="container position-relative">
                <a class="navbar-brand text-lowercase mr-lg-4 mt-3" href="/">
                    <p class="navbar-brand-dark text-light font-weight-bold">tayyip-bölük<i class="bi bi-circle-fill small text-tertiary ml-1"></i></p>
                    <p class="navbar-brand-light text-dark font-weight-bold">tayyip-bölük<i class="bi bi-circle-fill small text-tertiary ml-1"></i></p>
                </a>
                <div class="navbar-collapse collapse mr-auto" id="navbar_global">
                    <div class="navbar-collapse-header">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="/">
                                    <p class="text-dark font-weigth-bold">tayyip-bölük<i class="bi bi-circle-fill small text-tertiary ml-2"></i></p>
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <a href="#navbar_global" class="fas fa-times" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" title="close" aria-label="Toggle navigation"></a>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
                        <li class="nav-item">
                            <a href="/" class="nav-link">
                                Anasayfa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/hakkimda" class="nav-link">
                                Hakkımda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/projeler" class="nav-link">
                                Projeler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/blog" class="nav-link active">
                                Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/iletisim" class="nav-link">
                                İletişim
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <a href="<?php echo htmlspecialchars($item->cv) ?>" target="_blank" class="btn btn-outline-soft d-none d-md-inline mr-md-3 animate-up-2">Öz Geçmiş <i class="far fa-file ml-2"></i></a>
                    <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <?php
    $blogUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
    $bloglar = new Tayyip();
    $item = $bloglar->getBlogSlug($blogUrl);
    ?>

    <section class="section section-header text-dark">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 mb-5 mb-md-7 mt-5">
                    <h1 class="display-3 font-weight-bolder">
                        <?php echo htmlspecialchars($item->baslik) ?>
                    </h1>
                    <nav aria-label="breadcrumb ">
                        <ol class="breadcrumb bg-white pl-0">
                            <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                            <li class="breadcrumb-item"><a href="blog">Blog</a></li>
                            <li class="breadcrumb-item text-tertiary" aria-current="page"><?php echo htmlspecialchars($item->baslik) ?></li>
                        </ol>
                    </nav>
                    <p><?php echo htmlspecialchars($item->kisa_aciklama) ?></p>
                </div>
                <div class="col-12 col-lg-5 justify-content-center">
                    <img class="d-none d-md-inline-block" src="../assets/img/illustrations/scene.svg" alt="Mobile App Mockup">
                </div>
            </div>
        </div>
    </section>

    <section class="section section-sm">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-9 pr-3">
                    <div class="">
                        <img src="../images/<?php echo htmlspecialchars($item->fotograf) ?>" class="img-fluid py-3" alt="<?php echo htmlspecialchars($item->baslik) ?>">
                        <div class="bg-white border-0 pb-3">
                            <span class="small text-gray-600 mr-2"><i class="bi bi-person mr-1"></i>Tayyip Bölük</span>
                            <span class="small text-gray-600 mr-2">
                                <i class="bi bi-calendar4-week mr-1"></i>
                                <?php
                                $date = new DateTime($item->tarih);
                                $months = [
                                    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan', 5 => 'Mayıs', 6 => 'Haziran',
                                    7 => 'Temmuz', 8 => 'Ağustos', 9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                                ];
                                $day = $date->format('j');
                                $monthNumber = $date->format('n');
                                $monthName = $months[$monthNumber];
                                echo $day . ' ' . $monthName;
                                ?>
                            </span>
                            <span class="small text-gray-600"><i class="bi bi-bookmark mr-1"></i><?php echo htmlspecialchars($item->etiket) ?></span>
                        </div>
                        <h5 class=""><?php echo htmlspecialchars($item->baslik) ?></h5>
                        <div class="card-text"><?php echo htmlspecialchars($item->blog) ?></div>
                        <div class="py-3">
                            <span class="border p-2"><?php echo htmlspecialchars($item->etiket) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h4 class="border-left border-tertiary border-5 pl-2">Yeni Yazılar</h4>
                    <ul class="list-unstyled pt-3 pb-5">
                        <?php
                        $bloglar = new Tayyip();
                        foreach ($bloglar->getBlogA() as $item) :
                        ?>
                            <li>
                                <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>" title="<?php echo htmlspecialchars($item->baslik) ?>" class="text-decoration-none row">
                                    <span class="col-4 p-2"><img src="images/<?php echo htmlspecialchars($item->fotograf) ?>" alt="<?php echo htmlspecialchars($item->baslik) ?>" class="img-fluid"></span>
                                    <span class="col-8 p-2 align-item-center">
                                        <h5><?php echo htmlspecialchars($item->baslik) ?></h5>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h4 class="border-left border-tertiary border-5 pl-2">Etiketler</h4>
                    <div class="pt-3">
                        <?php
                        $bloglar = new Tayyip();
                        foreach ($bloglar->getBlog() as $item) :
                        ?>
                            <span class="border p-2"><?php echo htmlspecialchars($item->etiket) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php

    $ayarlar = new Tayyip();
    $item = $ayarlar->getAyarlar();
    ?>

    <footer class="footer py-5 pt-lg-6">

        <div class="sticky-right">
            <a href="#" class="icon icon-secondary icon-md btn btn-icon-only btn-white border border-soft shadow-soft animate-up-3">
                <span class="fas fa-chevron-up"></span>
            </a>
        </div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <p><?php echo htmlspecialchars($item->alt_aciklama) ?></p>
                    <ul class="social-buttons mb-5 mb-lg-0">
                        <li>
                            <a target="_blank" title="instagram" href="<?php echo htmlspecialchars($item->insta) ?>" aria-label="instagram social link" class="icon icon-md icon-instagram mr-3 <?php echo strlen($item->insta) > 0 ? '' : 'd-none'; ?>">
                                <span class="fab fa-instagram"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="linkedin" href="<?php echo htmlspecialchars($item->linke) ?>" class="icon icon-md icon-facebook mr-3 <?php echo strlen($item->linke) > 0 ? '' : 'd-none'; ?>" aria-label="linkedin social link">
                                <span class="fab fa-linkedin"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="github" href="<?php echo htmlspecialchars($item->git) ?>" aria-label="github social link" class="icon icon-md icon-github mr-3 <?php echo strlen($item->git) > 0 ? '' : 'd-none'; ?>">
                                <span class="fab fa-github"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="x" href="<?php echo htmlspecialchars($item->x) ?>" class="icon icon-md icon-dark mr-3 <?php echo strlen($item->x) > 0 ? '' : 'd-none'; ?>" aria-label="twitter social link">
                                <span class="bi bi-twitter-x"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="facebook" href="<?php echo htmlspecialchars($item->face) ?>" class="icon icon-md icon-facebook mr-3 <?php echo strlen($item->face) > 0 ? '' : 'd-none'; ?>" aria-label="facebook social link">
                                <span class="fab fa-facebook"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="youtube" href="<?php echo htmlspecialchars($item->you) ?>" class="icon icon-md icon-youtube mr-3 <?php echo strlen($item->you) > 0 ? '' : 'd-none'; ?>" aria-label="youtube social link">
                                <span class="fab fa-youtube"></span>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" title="telegram" href="<?php echo htmlspecialchars($item->tele) ?>" class="icon icon-md icon-twitter mr-3 <?php echo strlen($item->tele) > 0 ? '' : 'd-none'; ?>" aria-label="telegram social link">
                                <span class="fab fa-telegram"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 col-md-2 mb-5 mb-lg-0">
                    <span class="h5">Tayyip Bölük</span>
                    <ul class="footer-links mt-2">
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/projeler">Projeler</a></li>
                        <li><a href="/hakkimda">Hakkımda</a></li>
                        <li><a href="/iletisim">İletişim</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 mb-5 mb-lg-0">
                    <span class="h5">Hizmetlerim</span>
                    <ul class="footer-links mt-2">
                        <?php
                        $hizmetler = new Tayyip();
                        foreach ($hizmetler->getHizmet() as $item) :
                        ?>
                            <li><a href="/hizmet/<?php echo htmlspecialchars($item->hizmetUrl) ?>"><?php echo htmlspecialchars($item->hizmet) ?></a></li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
            <div class="row pt-2 pt-lg-5">
                <div class="d-md-flex justify-content-between mb-md-0">
                    <div class="d-flex text-center justify-content-center align-items-center" role="contentinfo">
                        <p class="text-tertiary font-weight-bold">Tayyip Bölük</p>
                        <p class="font-weight-normal font-small ml-2">© Tüm Hakları Saklıdır. <span class="current-year">2023</span></p>
                    </div>
                    <div class="text-center small">
                        <a href="gizlilik-politikasi" class="text-secondary pr-4">Gizlilik Politikası</a>
                        <a href="cerez-politikasi" class="text-secondary">Çerez Politikası</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Core -->
    <script src="../vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendor/headroom.js/dist/headroom.min.js"></script>

    <!-- Vendor JS -->
    <script src="../vendor/onscreen/dist/on-screen.umd.min.js"></script>
    <script src="../vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <script src="../vendor/isotope/imagesload.pkgd.min.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Swipe JS -->
    <script src="../assets/js/swipe.js"></script>

</body>

</html>