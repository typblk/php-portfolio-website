<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:;");

include 'controllers/baglanti.php';
include 'controllers/islem.php';

$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();
?>

<?php
$page = basename($_SERVER['PHP_SELF'], ".php");

$pageTitles = [
    'index' => 'Kişisel Website',
    'hakkimda' => 'Hakkımda',
    'projeler' => 'Projeler',
    'blog' => 'Blog',
    'iletisim' => 'İletişim',
    'cerez-politikasi' => 'Çerez Politikası',
    'gizlilik-politikasi' => 'Gizlilik Politikası',
];

$title = $pageTitles[$page] ?? '';
?>

<!DOCTYPE html>
<html lang="tr">

<head>
<?php echo $item->analytic ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?>">
    <meta name="author" content="Tayyip Bölük">
    <meta name="description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($item->site_keyw) ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars($item->site_url) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="Sebsite">
    <meta property="og:url" content="<?php echo htmlspecialchars($item->site_url) ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="og:image" content="../images/<?php echo htmlspecialchars($item->logo) ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo htmlspecialchars($item->site_url) ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="twitter:image" content="./images/<?php echo htmlspecialchars($item->logo) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="./images/<?php echo htmlspecialchars($item->favicon) ?>">
    <meta name="msapplication-TileColor" content="#183B56">
    <meta name="theme-color" content="#ffffff">

    <!-- Fontawesome -->
    <link type="text/css" href="./vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Swipe CSS -->
    <link type="text/css" href="./css/swipe.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

   

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
                            <a href="/" class="nav-link <?php echo $page == 'index' ? 'active' : ''; ?>">
                                Anasayfa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/hakkimda" class="nav-link <?php echo $page == 'hakkimda' ? 'active' : ''; ?>">
                                Hakkımda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/projeler" class="nav-link <?php echo $page == 'projeler' ? 'active' : ''; ?>">
                                Projeler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/blog" class="nav-link <?php echo $page == 'blog' ? 'active' : ''; ?>">
                                Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/iletisim" class="nav-link <?php echo $page == 'iletisim' ? 'active' : ''; ?>">
                                İletişim
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <a href="<?php echo htmlspecialchars($item->cv) ?>" target="_blank" class="btn btn-outline-soft d-none d-md-inline mr-md-3 animate-up-2">Özgeçmiş <i class="far fa-file ml-2"></i></a>
                    <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>