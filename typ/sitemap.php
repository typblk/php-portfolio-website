<?php
header('Content-Type: application/xml; charset=utf-8');
include 'controllers/baglanti.php'; // Adjust path if necessary
include 'controllers/islem.php';   // Adjust path if necessary

$db = new Db();
$pdo = $db->connect();

$ayarlarInstance = new Tayyip();
$ayarlar = $ayarlarInstance->getAyarlar();
$siteUrl = htmlspecialchars($ayarlar->site_url);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Helper function to add a URL to the sitemap
function addUrl($loc, $lastmod = null, $changefreq = 'daily', $priority = '0.7') {
    echo "  <url>\n";
    echo "    <loc>" . $loc . "</loc>\n";
    if ($lastmod) {
        echo "    <lastmod>" . $lastmod . "</lastmod>\n";
    }
    echo "    <changefreq>" . $changefreq . "</changefreq>\n";
    echo "    <priority>" . $priority . "</priority>\n";
    echo "  </url>\n";
}

// Static Pages
$staticPages = [
    'index.php' => ['priority' => '1.0', 'changefreq' => 'daily'],
    'hakkimda.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'projeler.php' => ['priority' => '0.9', 'changefreq' => 'weekly'],
    'blog.php' => ['priority' => '0.9', 'changefreq' => 'weekly'],
    'iletisim.php' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'gizlilik-politikasi.php' => ['priority' => '0.3', 'changefreq' => 'yearly'],
    'cerez-politikasi.php' => ['priority' => '0.3', 'changefreq' => 'yearly']
];

$today = date('Y-m-d');

foreach ($staticPages as $page => $meta) {
    // Remove .php for cleaner URLs if server rewrites are handled
    $pageName = str_replace('.php', '', $page);
    if ($pageName === 'index') {
        addUrl($siteUrl . '/', $today, $meta['changefreq'], $meta['priority']);
    } else {
        addUrl($siteUrl . '/' . $pageName, $today, $meta['changefreq'], $meta['priority']);
    }
}

// Dynamic Content: Bloglar
$bloglar = $ayarlarInstance->getBlog();
if ($bloglar) {
    foreach ($bloglar as $blog) {
        $blog_url = $siteUrl . '/bloglar/' . htmlspecialchars($blog->blogUrl);
        $last_mod_date = !empty($blog->tarih) ? date('Y-m-d', strtotime($blog->tarih)) : $today;
        addUrl($blog_url, $last_mod_date, 'weekly', '0.8');
    }
}

// Dynamic Content: Projeler
$projeler = $ayarlarInstance->getProje();
if ($projeler) {
    foreach ($projeler as $proje) {
        $proje_url = $siteUrl . '/proje/' . htmlspecialchars($proje->projeUrl);
        // Assuming no specific modification date for projects, use today
        addUrl($proje_url, $today, 'monthly', '0.7');
    }
}

// Dynamic Content: Hizmetler
$hizmetler = $ayarlarInstance->getHizmet();
if ($hizmetler) {
    foreach ($hizmetler as $hizmet) {
        $hizmet_url = $siteUrl . '/hizmet/' . htmlspecialchars($hizmet->hizmetUrl);
        // Assuming no specific modification date for hizmetler, use today
        addUrl($hizmet_url, $today, 'monthly', '0.6');
    }
}

// Dynamic Content: Urunler
$urunler = $ayarlarInstance->getUrun();
if ($urunler) {
    foreach ($urunler as $urun) {
        $urun_url = $siteUrl . '/urunler/' . htmlspecialchars($urun->urunUrl);
        // Assuming no specific modification date for urunler, use today
        addUrl($urun_url, $today, 'monthly', '0.6');
    }
}

echo '</urlset>' . "\n";
?>
