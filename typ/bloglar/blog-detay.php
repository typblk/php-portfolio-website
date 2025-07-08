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
$blogUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
$bloglarInstance = new Tayyip(); // Renamed to avoid conflict
$itemb = $bloglarInstance->getBlogSlug($blogUrl); // Current blog post details

// Fallback for meta description if blog's short description is empty
$meta_description = !empty($itemb->kisa_aciklama) ? htmlspecialchars($itemb->kisa_aciklama) : htmlspecialchars($site_settings->site_desc);
$canonical_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/bloglar/' . htmlspecialchars($itemb->blogUrl);
$og_image_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($itemb->fotograf);
$favicon_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($site_settings->favicon);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php echo $site_settings->analytic; // Include analytics code ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($itemb->baslik); ?> | <?php echo htmlspecialchars($site_settings->site_adi); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo htmlspecialchars($itemb->baslik); ?> | <?php echo htmlspecialchars($site_settings->site_adi); ?>">
    <meta name="author" content="Tayyip Bölük">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($itemb->etiket); // Use blog tags as keywords, fallback to site keywords if needed or combine them ?>" />
    <link rel="canonical" href="<?php echo $canonical_url; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($itemb->baslik); ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:image" content="<?php echo $og_image_url; ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($site_settings->site_adi); ?>">


    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $canonical_url; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($itemb->baslik); ?>">
    <meta property="twitter:description" content="<?php echo $meta_description; ?>">
    <meta property="twitter:image" content="<?php echo $og_image_url; ?>"> <?php // Twitter often uses og:image if twitter:image is not specified, but explicit is better ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $favicon_url; ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php if(!empty($itemb->fotograf)): ?>
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
        "@type": "BlogPosting", // Or Article
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo $canonical_url; ?>"
        },
        "headline": "<?php echo htmlspecialchars($itemb->baslik); ?>",
        <?php if(!empty($itemb->fotograf)): ?>
        "image": "<?php echo $og_image_url; ?>",
        <?php endif; ?>
        "datePublished": "<?php echo !empty($itemb->tarih) ? date('Y-m-d\TH:i:sP', strtotime($itemb->tarih)) : ''; ?>",
        "dateModified": "<?php echo !empty($itemb->tarih) ? date('Y-m-d\TH:i:sP', strtotime($itemb->tarih)) : ''; ?>", // Assuming tarih is also last modified date
        "author": {
            "@type": "Person",
            "name": "Tayyip Bölük" // Assuming author is always Tayyip Bölük
        },
        "publisher": {
            "@type": "Organization", // Or Person, if the publisher is the same as the author
            "name": "<?php echo htmlspecialchars($site_settings->site_adi); ?>",
            <?php if(!empty($site_settings->logo)): ?>
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo rtrim(htmlspecialchars($site_settings->site_url), '/'); ?>/images/<?php echo htmlspecialchars($site_settings->logo); ?>"
            }
            <?php endif; ?>
        },
        "description": "<?php echo $meta_description; ?>"
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
                        <?php
                            $blog_main_image_filename = htmlspecialchars($item->fotograf); // $item is $itemb from the top of the file for blog details
                            $blog_main_image_path = "../images/" . $blog_main_image_filename;
                            $blog_main_image_webp_path = "../images/" . pathinfo($blog_main_image_filename, PATHINFO_FILENAME) . '.webp';
                            $blog_main_file_extension = strtolower(pathinfo($blog_main_image_filename, PATHINFO_EXTENSION));
                            $blog_main_mime_type = ($blog_main_file_extension == 'jpg' || $blog_main_file_extension == 'jpeg') ? 'image/jpeg' : (($blog_main_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                        ?>
                        <picture>
                            <source srcset="<?php echo $blog_main_image_webp_path; ?>" type="image/webp">
                            <source srcset="<?php echo $blog_main_image_path; ?>" type="<?php echo $blog_main_mime_type; ?>">
                            <img src="<?php echo $blog_main_image_path; ?>" class="img-fluid py-3" alt="<?php echo htmlspecialchars($item->baslik); ?>">
                        </picture>
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
                        foreach ($bloglar->getBlogA() as $item) : // Here $item refers to the loop variable for recent blogs
                        ?>
                            <li>
                                <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>" title="<?php echo htmlspecialchars($item->baslik) ?>" class="text-decoration-none row">
                                    <span class="col-4 p-2">
                                        <?php
                                            $recent_blog_img_filename = htmlspecialchars($item->fotograf);
                                            $recent_blog_img_path = "../images/" . $recent_blog_img_filename; // Corrected path relative to typ/bloglar/
                                            $recent_blog_img_webp_path = "../images/" . pathinfo($recent_blog_img_filename, PATHINFO_FILENAME) . '.webp';
                                            $recent_blog_file_extension = strtolower(pathinfo($recent_blog_img_filename, PATHINFO_EXTENSION));
                                            $recent_blog_mime_type = ($recent_blog_file_extension == 'jpg' || $recent_blog_file_extension == 'jpeg') ? 'image/jpeg' : (($recent_blog_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                                        ?>
                                        <picture>
                                            <source srcset="<?php echo $recent_blog_img_webp_path; ?>" type="image/webp">
                                            <source srcset="<?php echo $recent_blog_img_path; ?>" type="<?php echo $recent_blog_mime_type; ?>">
                                            <img src="<?php echo $recent_blog_img_path; ?>" alt="<?php echo htmlspecialchars($item->baslik); ?>" class="img-fluid">
                                        </picture>
                                    </span>
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