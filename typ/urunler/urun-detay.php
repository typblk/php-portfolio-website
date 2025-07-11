<?php
include '../controllers/baglanti.php';
include '../controllers/islem.php';

$ayarlarInstance = new Tayyip();
$site_settings = $ayarlarInstance->getAyarlar();
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<?php
$urunUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
$urunInstance = new Tayyip();
$itemu = $urunInstance->getUrunSlug($urunUrl);

if (!$itemu) {
    header("Location: /");
    exit();
}

$meta_title = htmlspecialchars($itemu->baslik) . " | " . htmlspecialchars($site_settings->site_adi);
$meta_description = !empty($itemu->kisa_aciklama) ? htmlspecialchars($itemu->kisa_aciklama) : htmlspecialchars($site_settings->site_desc);
$meta_keywords = htmlspecialchars($itemu->baslik) . ', ' . htmlspecialchars($site_settings->site_keyw);
// Add category to keywords if available:
// if (!empty($itemu->kategori)) { $meta_keywords .= ', ' . htmlspecialchars($itemu->kategori); }


$canonical_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/urunler/' . htmlspecialchars($itemu->urunUrl);
$og_image_url = !empty($itemu->fotograf) ? rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($itemu->fotograf) : rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($site_settings->logo); // Fallback to site logo
$favicon_url = rtrim(htmlspecialchars($site_settings->site_url), '/') . '/images/' . htmlspecialchars($site_settings->favicon);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php if(isset($site_settings->analytic)) { echo $site_settings->analytic; } ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?php echo $meta_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo $meta_title; ?>">
    <meta name="author" content="Tayyip Bölük">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
    <link rel="canonical" href="<?php echo $canonical_url; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="product">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($itemu->baslik); ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:image" content="<?php echo $og_image_url; ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($site_settings->site_adi); ?>">
    <?php
    // Example product specific OG tags - uncomment and adapt if these fields exist in $itemu
    // if(isset($itemu->price_amount) && isset($itemu->price_currency)){
    //     echo '<meta property="product:price:amount" content="' . htmlspecialchars($itemu->price_amount) . '">';
    //     echo '<meta property="product:price:currency" content="' . htmlspecialchars($itemu->price_currency) . '">';
    // }
    // if(isset($itemu->availability)){ // e.g., 'in stock', 'out of stock', 'preorder'
    //     echo '<meta property="product:availability" content="' . htmlspecialchars($itemu->availability) . '">';
    // }
    ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $canonical_url; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($itemu->baslik); ?>">
    <meta property="twitter:description" content="<?php echo $meta_description; ?>">
    <meta property="twitter:image" content="<?php echo $og_image_url; ?>">
    <?php
    // Example Twitter product card data - uncomment and adapt if these fields exist in $itemu
    // if(isset($itemu->price_amount) && isset($itemu->price_currency)) {
    //    echo '<meta name="twitter:data1" content="' . htmlspecialchars($itemu->price_amount) . ' ' . htmlspecialchars($itemu->price_currency) . '">';
    //    echo '<meta name="twitter:label1" content="Price">';
    // }
    // if(isset($itemu->brand_name)){ // Assuming a brand name field
    //    echo '<meta name="twitter:data2" content="' . htmlspecialchars($itemu->brand_name) . '">';
    //    echo '<meta name="twitter:label2" content="Brand">';
    // }
    ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $favicon_url; ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php if(!empty($itemu->fotograf)): ?>
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
        "@type": "Product",
        "name": "<?php echo htmlspecialchars($itemu->baslik); ?>",
        "description": "<?php echo $meta_description; ?>",
        <?php if(!empty($itemu->fotograf)): ?>
        "image": "<?php echo $og_image_url; ?>",
        <?php endif; ?>
        "url": "<?php echo $canonical_url; ?>",
        // "brand": { // Uncomment and populate if brand info is available
        //    "@type": "Brand",
        //    "name": "Your Brand Name or <?php echo htmlspecialchars($site_settings->site_adi); ?>"
        // },
        // "sku": "<?php echo htmlspecialchars($itemu->id); ?>", // Example SKU, use a real one if available
        // "mpn": "<?php echo htmlspecialchars($itemu->id); ?>", // Example MPN
        // "offers": { // Uncomment and populate if pricing and availability are available
        //    "@type": "Offer",
        //    "url": "<?php echo $canonical_url; ?>",
        //    "priceCurrency": "TRY", // Change as needed
        //    "price": "0.00", // Example price - make this dynamic
        //    "itemCondition": "https://schema.org/NewCondition", // Or UsedCondition, etc.
        //    "availability": "https://schema.org/InStock" // Or OutOfStock, PreOrder etc.
        //    // "seller": {
        //    //   "@type": "Organization", // Or Person
        //    //   "name": "<?php echo htmlspecialchars($site_settings->site_adi); ?>"
        //    // }
        // },
        <?php if(!empty($itemu->link) && filter_var($itemu->link, FILTER_VALIDATE_URL)): ?>
        "potentialAction": { // Link to an external demo or purchase page
            "@type": "ViewAction",
            "target": "<?php echo htmlspecialchars($itemu->link); ?>"
        },
        <?php endif; ?>
        "keywords": "<?php echo $meta_keywords; ?>"
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
    $urunUrl = isset($_GET['slug']) ? $_GET['slug'] : '';
    $urunler = new Tayyip();
    $item = $urunler->getUrunSlug($urunUrl);
    ?>

    <section class="section section-header text-dark">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 mb-5 mb-md-7 mt-5">
                    <h1 class="display-3 font-weight-bolder mb-4">
                        <?php echo htmlspecialchars($item->baslik) ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-white">
                            <li class="breadcrumb-item"><a href="/">Anasayfa</a></li>
                            <li class="breadcrumb-item"><a href="/">Ürünler</a></li>
                            <li class="breadcrumb-item text-tertiary" aria-current="page"><?php echo htmlspecialchars($item->baslik) ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-12 col-lg-5 justify-content-center">
                    <img class="d-none d-md-inline-block" src="../assets/img/illustrations/scene-2.svg" alt="Mobile App Mockup">
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 p-3">
                     <?php
                        // $itemu is the product detail variable from the top of the file.
                        // The $item variable later in the file is for $ayarlar.
                        if (!empty($itemu->fotograf)) {
                            $urun_img_filename = htmlspecialchars($itemu->fotograf);
                            $urun_img_path = "../images/" . $urun_img_filename;
                            $urun_img_webp_path = "../images/" . pathinfo($urun_img_filename, PATHINFO_FILENAME) . '.webp';
                            $urun_file_extension = strtolower(pathinfo($urun_img_filename, PATHINFO_EXTENSION));
                            $urun_mime_type = ($urun_file_extension == 'jpg' || $urun_file_extension == 'jpeg') ? 'image/jpeg' : (($urun_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                        ?>
                            <picture>
                                <source srcset="<?php echo $urun_img_webp_path; ?>" type="image/webp">
                                <source srcset="<?php echo $urun_img_path; ?>" type="<?php echo $urun_mime_type; ?>">
                                <img src="<?php echo $urun_img_path; ?>" alt="<?php echo htmlspecialchars($itemu->baslik); ?>" class="img-fluid rounded shadow">
                            </picture>
                        <?php } else { ?>
                            <img src="../images/default-product.jpg" alt="<?php echo htmlspecialchars($itemu->baslik); ?> için varsayılan görsel" class="img-fluid rounded shadow">
                        <?php } ?>
                </div>
                <div class="col-lg-6 p-3">
                    <p><?php echo htmlspecialchars($itemu->kisa_aciklama); // Use $itemu for consistency ?></p>
                    <div class="py-3"><a href="/urunler/<?php echo htmlspecialchars($itemu->urunUrl); ?>" title="<?php echo htmlspecialchars($itemu->baslik); ?>" class="btn btn-dark mt-3 animate-up-2">
                            Daha Fazla
                            <span class="icon icon-xs ml-2">
                                <i class="fas fa-external-link-alt"></i>
                            </span>
                        </a>
                        <a href="<?php echo htmlspecialchars($item->link) ?>" target="_blank" title="<?php echo htmlspecialchars($item->baslik) ?>" class="btn btn-tertiary text-white mt-3 ml-2 animate-up-2">
                            Demo
                            <span class="icon icon-xs ml-2">
                                <i class="far fa-eye"></i>
                            </span>
                        </a>
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