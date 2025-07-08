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
    <link rel="canonical" href="<?php echo rtrim(htmlspecialchars($item->site_url), '/') . $_SERVER['REQUEST_URI']; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo rtrim(htmlspecialchars($item->site_url), '/') . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="og:image" content="<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/images/<?php echo htmlspecialchars($item->logo); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($item->site_adi); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo rtrim(htmlspecialchars($item->site_url), '/') . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($item->site_adi) ?> | <?php echo $title; ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($item->site_desc) ?>">
    <meta property="twitter:image" content="<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/images/<?php echo htmlspecialchars($item->logo); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/images/<?php echo htmlspecialchars($item->favicon); ?>">
    <meta name="msapplication-TileColor" content="#183B56">
    <meta name="theme-color" content="#ffffff">

    <!-- Preconnect to font CDNs -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <?php
    // Preload LCP Image for Homepage Hero
    if ($page == 'index') {
        // We need to fetch the hero image URL here.
        // This requires an instance of Tayyip and fetching slide data for 'index'.
        // This logic is usually in index.php, so we might need to pass it or duplicate it.
        // For simplicity, let's assume $hero_image_filename is available.
        // In a real scenario, ensure $item_hero_image_filename is correctly populated.
        // $slayt_instance_for_lcp = new Tayyip(); // Avoid re-declaring if already available
        // $hero_slide_data = $slayt_instance_for_lcp->getSlaytSayfa('index');
        // if ($hero_slide_data && !empty($hero_slide_data->fotograf)) {
        //    $hero_image_filename = htmlspecialchars($hero_slide_data->fotograf);
        //    echo '<link rel="preload" as="image" href="' . rtrim(htmlspecialchars($item->site_url), '/') . '/images/' . $hero_image_filename . '" fetchpriority="high">' . "\n";
        // }
        // Due to potential complexity of fetching specific LCP image here without full context of index.php variables,
        // this specific preload might be better placed directly in index.php before including the header,
        // or the LCP image filename needs to be passed to this header scope.
        // For now, I'll comment out the actual preload for homepage LCP from header,
        // suggesting it be handled within index.php or by passing data.
        // echo "<!-- LCP Preload for homepage hero image would be here if data was available -->\n";
    }
    ?>

    <!-- Critical CSS (Placeholder - to be populated manually/with tools) -->
    <style type="text/css">
        /* Common critical styles: basic layout, typography, header */
        body { font-family: sans-serif; margin: 0; background-color: #fff; color: #333; }
        .container { width: 90%; margin-left: auto; margin-right: auto; max-width: 1200px; }
        header.header-global { padding: 1rem 0; }
        nav.navbar-main { display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand p { margin: 0; font-weight: bold; }
        /* Add other critical styles here based on inspection */
    </style>

    <!-- Fontawesome (Async) -->
    <link rel="preload" href="./vendor/@fortawesome/fontawesome-free/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="./vendor/@fortawesome/fontawesome-free/css/all.min.css"></noscript>

    <!-- Bootstrap Icons (Async from CDN) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css"></noscript>

    <!-- Swipe CSS (Async) -->
    <link rel="preload" href="./css/swipe.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="./css/swipe.css"></noscript>

    <!-- Main Style CSS (Async) -->
    <link rel="preload" href="./css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="./css/style.css"></noscript>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/",
        "name": "<?php echo htmlspecialchars($item->site_adi); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/arama?q={search_term_string}", // Assuming a search page like /arama
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person", // Change to Organization if it's a company
        "name": "Tayyip Bölük", // Author/Owner Name
        "url": "<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/",
        <?php if(!empty($item->logo)): ?>
        "image": "<?php echo rtrim(htmlspecialchars($item->site_url), '/'); ?>/images/<?php echo htmlspecialchars($item->logo); ?>",
        <?php endif; ?>
        <?php
            $socialLinks = [];
            if (!empty($item->insta)) $socialLinks[] = htmlspecialchars($item->insta);
            if (!empty($item->linke)) $socialLinks[] = htmlspecialchars($item->linke);
            if (!empty($item->git)) $socialLinks[] = htmlspecialchars($item->git);
            if (!empty($item->x)) $socialLinks[] = htmlspecialchars($item->x);
            if (!empty($item->face)) $socialLinks[] = htmlspecialchars($item->face);
            if (!empty($item->you)) $socialLinks[] = htmlspecialchars($item->you);
            if (!empty($item->tele)) $socialLinks[] = htmlspecialchars($item->tele);

            if (!empty($socialLinks)):
        ?>
        "sameAs": <?php echo json_encode($socialLinks); ?>,
        <?php endif; ?>
        "jobTitle": "Yazılım Geliştirici" // Example, make this dynamic if available
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
                    <?php if (!empty($item->cv)): ?>
                        <button type="button" class="btn btn-outline-soft d-none d-md-inline mr-md-3 animate-up-2" data-bs-toggle="modal" data-bs-target="#cvModal">
                            Özgeçmiş <i class="far fa-file ml-2"></i>
                        </button>
                    <?php else: ?>
                        {/* Optionally, display a disabled button or hide it if no CV is set */}
                        <button type="button" class="btn btn-outline-soft d-none d-md-inline mr-md-3 animate-up-2" disabled>
                            Özgeçmiş <i class="far fa-file ml-2"></i>
                        </button>
                    <?php endif; ?>
                    <button class="navbar-toggler ml-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation"> {/* Assuming BS5 for collapse too */}
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>