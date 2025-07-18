<?php
include '../controllers/baglanti.php';
include '../controllers/islem.php';

$ayarlarInstance = new Tayyip(); // Renamed to avoid conflict
$site_settings = $ayarlarInstance->getAyarlar(); // Global site settings
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<?php
$projeUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
$projelerInstance = new Tayyip(); // Renamed to avoid conflict
$itemp = $projelerInstance->getProjeSlug($projeUrl); // Current project details

// Fallback for meta description if project's short description is empty
$meta_description = !empty($itemp->kisa_aciklama) ? htmlspecialchars($itemp->kisa_aciklama) : htmlspecialchars($site_settings->site_desc);
// Keywords could be project category, technologies, or site keywords
$meta_keywords = !empty($itemp->teknoloji) ? htmlspecialchars($itemp->teknoloji) : htmlspecialchars($site_settings->site_keyw);
if (!empty($itemp->kategori)) {
    $meta_keywords .= ', ' . htmlspecialchars($itemp->kategori);
}

$canonical_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/proje/' . htmlspecialchars($itemp->projeUrl);
$og_image_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($itemp->fotograf);
$favicon_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($site_settings->favicon);

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php echo $site_settings->analytic; // Include analytics code ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($itemp->proje); ?> | <?php echo htmlspecialchars($site_settings->site_adi); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo htmlspecialchars($itemp->proje); ?> | <?php echo htmlspecialchars($site_settings->site_adi); ?>">
    <meta name="author" content="Tayyip Bölük">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
    <link rel="canonical" href="<?php echo $canonical_url; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="object"> {/* Could be "article" or a more specific type if applicable */}
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($itemp->proje); ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:image" content="<?php echo $og_image_url; ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($site_settings->site_adi); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $canonical_url; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($itemp->proje); ?>">
    <meta property="twitter:description" content="<?php echo $meta_description; ?>">
    <meta property="twitter:image" content="<?php echo $og_image_url; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $favicon_url; ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php if(!empty($itemp->fotograf)): ?>
    <link rel="preload" href="<?php echo $og_image_url; ?>" as="image" fetchpriority="high">
    <?php endif; ?>

    <!-- Fontawesome -->
    <link type="text/css" href="../vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Swipe CSS -->
    <link type="text/css" href="../css/swipe.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CreativeWork", // Or a more specific type like SoftwareApplication, Product, etc.
        "name": "<?php echo htmlspecialchars($itemp->proje); ?>",
        "description": "<?php echo $meta_description; ?>",
        <?php if(!empty($itemp->fotograf)): ?>
        "image": "<?php echo $og_image_url; ?>",
        <?php endif; ?>
        "url": "<?php echo $canonical_url; ?>",
        "author": {
            "@type": "Person",
            "name": "Tayyip Bölük" // Assuming author is Tayyip Bölük
        },
        // "datePublished": "YYYY-MM-DD", // Add if you have a publication date for projects
        <?php if(!empty($itemp->kategori)): ?>
        "genre": "<?php echo htmlspecialchars($itemp->kategori); ?>",
        <?php endif; ?>
        <?php if(!empty($itemp->teknoloji)): ?>
        "keywords": "<?php echo htmlspecialchars($itemp->teknoloji); ?>", // Using teknoloji as keywords
        <?php endif; ?>
        <?php if(!empty($itemp->link) && filter_var($itemp->link, FILTER_VALIDATE_URL)): ?>
        "potentialAction": {
            "@type": "ViewAction",
            "target": "<?php echo htmlspecialchars($itemp->link); ?>"
        }
        <?php endif; ?>
    }
    </script>

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
                            <a href="/projeler" class="nav-link active">
                                Projeler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/blog" class="nav-link">
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
    $projeUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
    $projeler = new Tayyip();
    $item = $projeler->getProjeSlug($projeUrl);
    ?>

    <section class="section section-header text-dark">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 mb-5 mb-md-7 mt-5">
                    <h1 class="display-3 font-weight-bolder mb-4">
                        <?php echo htmlspecialchars($item->proje) ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-white">
                            <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                            <li class="breadcrumb-item"><a href="projeler">Projeler</a></li>
                            <li class="breadcrumb-item text-tertiary" aria-current="page"><?php echo htmlspecialchars($item->proje) ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-12 col-lg-5 justify-content-center">
                    <img class="d-none d-md-inline-block" src="../assets/img/illustrations/scene-3.svg" alt="Mobile App Mockup">
                </div>
            </div>
        </div>
    </section>

    <section class="section section-sm">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 p-3">
                    <?php
                        $proj_main_image_filename = htmlspecialchars($item->fotograf); // $item is $itemp from top of file
                        $proj_main_image_path = "../images/" . $proj_main_image_filename;
                        $proj_main_image_webp_path = "../images/" . pathinfo($proj_main_image_filename, PATHINFO_FILENAME) . '.webp';
                        $proj_main_file_extension = strtolower(pathinfo($proj_main_image_filename, PATHINFO_EXTENSION));
                        $proj_main_mime_type = ($proj_main_file_extension == 'jpg' || $proj_main_file_extension == 'jpeg') ? 'image/jpeg' : (($proj_main_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                    ?>
                    <picture>
                        <source srcset="<?php echo $proj_main_image_webp_path; ?>" type="image/webp">
                        <source srcset="<?php echo $proj_main_image_path; ?>" type="<?php echo $proj_main_mime_type; ?>">
                        <img src="<?php echo $proj_main_image_path; ?>" alt="<?php echo htmlspecialchars($item->proje); ?>" class="img-fluid">
                    </picture>
                </div>
                <div class="col-lg-8 p-3 align-items-center">
                    <p><?php echo htmlspecialchars($item->kisa_aciklama) ?></p>
                    <p><?php echo htmlspecialchars($item->teknoloji) ?></p>
                    <div class="mt-4">
                        <?php if (!empty($item->link)): ?>
                        <a href="<?php echo htmlspecialchars($item->link); ?>" target="_blank" title="<?php echo htmlspecialchars($item->proje); ?>" class="btn btn-tertiary text-white mt-3 ml-2 animate-up-2">
                            İncele
                            <span class="icon icon-xs ml-2">
                                <i class="far fa-eye"></i>
                            </span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="py-4">
                <p><?php echo htmlspecialchars($item->aciklama) ?>.</p>
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