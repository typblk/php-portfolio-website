<?php
include 'partials/header.php';
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<main>
    <?php
    $page = basename($_SERVER['PHP_SELF'], ".php");

    $sayfa = $page;
    $slayt = new Tayyip();
    $item = $slayt->getSlaytSayfa($sayfa);
    ?>
    <?php
    $ayarlar = new Tayyip();
    $itema = $ayarlar->getAyarlar();
    ?>
    <!-- Hero -->
    <section class="section section-header text-dark">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 mb-5 mb-md-7">
                    <h1 class="display-3 font-weight-bolder mb-4">
                        <?php echo htmlspecialchars($item->baslik) ?> 👋
                    </h1>
                    <div class="d-flex align-items-center mb-4">
                        <hr>
                        <h5 class="display-4s font-weight-bold">Yazılım Geliştirici</h5>
                    </div>
                    <p class="lead mb-3 mb-lg-5"><?php echo htmlspecialchars($item->aciklama) ?></p>
                    <div>
                        <a href="projeler" class="btn btn-tertiary text-white animate-up-2">
                            Projeler
                            <span class="icon icon-xs ml-2">
                                <i class="far fa-file-code"></i>
                            </span>
                        </a>
                        <a href="https://wa.me/9<?php echo htmlspecialchars($itema->tel) ?>?text=Merhaba%20Hizmet%20almak%20istiyorum." class="btn btn-lighten animate-up-2 ml-2">
                            İletişime Geç
                            <span class="icon icon-xs ml-2">
                                <i class="far fa-paper-plane"></i>
                            </span>
                        </a>
                    </div>
                    <div class="d-flex mt-5">
                        <p class="small mt-2 mr-2">Beni Takip Et!</p>
                        <a target="_blank" title="instagram" href="<?php echo htmlspecialchars($itema->insta) ?>" class="social-link <?php echo empty($itema->insta) ? 'd-none' : ''; ?>"><i class="fab fa-instagram"></i></a>
                        <a target="_blank" title="linkedin" href="<?php echo htmlspecialchars($itema->linke) ?>" class="social-link <?php echo empty($itema->linke) ? 'd-none' : ''; ?>"><i class="fab fa-linkedin-in"></i></a>
                        <a target="_blank" title="github" href="<?php echo htmlspecialchars($itema->git) ?>" class="social-link <?php echo empty($itema->git) ? 'd-none' : ''; ?>"><i class="fab fa-github"></i></a>
                        <a target="_blank" title="twitter-x" href="<?php echo htmlspecialchars($itema->x) ?>" class="social-link <?php echo empty($itema->x) ? 'd-none' : ''; ?>"><i class="bi bi-twitter-x"></i></a>
                        <a target="_blank" title="facebook" href="<?php echo htmlspecialchars($itema->face) ?>" class="social-link <?php echo empty($itema->face) ? 'd-none' : ''; ?>"><i class="fab fa-facebook-f"></i></a>
                        <a target="_blank" title="youtube" href="<?php echo htmlspecialchars($itema->you) ?>" class="social-link <?php echo empty($itema->you) ? 'd-none' : ''; ?>"><i class="fab fa-youtube"></i></a>
                        <a target="_blank" title="telegram" href="<?php echo htmlspecialchars($itema->tele) ?>" class="social-link <?php echo empty($itema->tele) ? 'd-none' : ''; ?>"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>
                <div class="col-12 col-lg-6 justify-content-center">
                    <?php
                        $hero_image_filename = htmlspecialchars($item->fotograf);
                        $hero_image_path = "images/" . $hero_image_filename;
                        $hero_image_webp_path = "images/" . pathinfo($hero_image_filename, PATHINFO_FILENAME) . '.webp';
                        $file_extension = strtolower(pathinfo($hero_image_filename, PATHINFO_EXTENSION));
                        $mime_type = ($file_extension == 'jpg' || $file_extension == 'jpeg') ? 'image/jpeg' : (($file_extension == 'png') ? 'image/png' : 'image/octet-stream'); // Basic mime type
                    ?>
                    <picture>
                        <source srcset="<?php echo $hero_image_webp_path; ?>" type="image/webp">
                        <source srcset="<?php echo $hero_image_path; ?>" type="<?php echo $mime_type; ?>">
                        <img class="d-none d-md-inline-block" src="<?php echo $hero_image_path; ?>" alt="Tayyip Bölük - <?php echo htmlspecialchars($item->baslik); ?>">
                    </picture>
                </div>
            </div>
        </div>
    </section>

    <?php
    $hakkimda = new Tayyip();
    $item = $hakkimda->getHakkimizda();
    ?>
    <section class="section section-lg py-0">
        <div class="container">
            <div class="row">
                <div class="row row-grid align-items-center mb-5 mb-lg-7">
                    <div class="col-12 col-lg-6 ml-lg-auto">
                        <?php
                            $hakkimda_image_filename = htmlspecialchars($item->fotograf);
                            $hakkimda_image_path = "images/" . $hakkimda_image_filename;
                            $hakkimda_image_webp_path = "images/" . pathinfo($hakkimda_image_filename, PATHINFO_FILENAME) . '.webp';
                            $hakkimda_file_extension = strtolower(pathinfo($hakkimda_image_filename, PATHINFO_EXTENSION));
                            $hakkimda_mime_type = ($hakkimda_file_extension == 'jpg' || $hakkimda_file_extension == 'jpeg') ? 'image/jpeg' : (($hakkimda_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                        ?>
                        <picture>
                            <source srcset="<?php echo $hakkimda_image_webp_path; ?>" type="image/webp">
                            <source srcset="<?php echo $hakkimda_image_path; ?>" type="<?php echo $hakkimda_mime_type; ?>">
                            <img src="<?php echo $hakkimda_image_path; ?>" class="w-100" alt="<?php echo htmlspecialchars($item->baslik); ?>">
                        </picture>
                    </div>
                    <div class="col-12 col-lg-5">
                        <h2 class="mb-4"><?php echo htmlspecialchars($item->baslik) ?></h2>
                        <p class="hakkimda"><?php echo htmlspecialchars($item->hakkimizda) ?></p>
                        <a href="hakkimda" class="btn btn-dark mt-3 animate-up-2">
                            Daha Fazla
                            <span class="icon icon-xs ml-2">
                                <i class="fas fa-external-link-alt"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include 'partials/hizmetler.php';
    ?>

    <section class="section section-sm">
        <div class="container">
            <?php
            $urunler = new Tayyip();
            foreach ($urunler->getUrun() as $item) :
            ?>
                <div class="row row-grid align-items-center mb-5 mb-lg-7">
                    <div class="col-12 col-lg-3 ml-lg-auto">
                        <?php
                            $urun_image_filename = htmlspecialchars($item->fotograf);
                            $urun_image_path = "images/" . $urun_image_filename;
                            $urun_image_webp_path = "images/" . pathinfo($urun_image_filename, PATHINFO_FILENAME) . '.webp';
                            $urun_file_extension = strtolower(pathinfo($urun_image_filename, PATHINFO_EXTENSION));
                            $urun_mime_type = ($urun_file_extension == 'jpg' || $urun_file_extension == 'jpeg') ? 'image/jpeg' : (($urun_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                        ?>
                        <picture>
                            <source srcset="<?php echo $urun_image_webp_path; ?>" type="image/webp">
                            <source srcset="<?php echo $urun_image_path; ?>" type="<?php echo $urun_mime_type; ?>">
                            <img src="<?php echo $urun_image_path; ?>" class="w-100" alt="<?php echo htmlspecialchars($item->baslik); ?>">
                        </picture>
                    </div>
                    <div class="col-12 col-lg-9">
                        <h2 class="mb-4"><?php echo htmlspecialchars($item->baslik) ?></h2>
                        <p><?php echo htmlspecialchars($item->kisa_aciklama) ?></p>
                        <a href="/urunler/<?php echo htmlspecialchars($item->urunUrl) ?>" title="<?php echo htmlspecialchars($item->baslik) ?>" class="btn btn-dark mt-3 animate-up-2">
                            Daha Fazla
                            <span class="icon icon-xs ml-2">
                                <i class="fas fa-external-link-alt"></i>
                            </span>
                        </a>
                        <a href="<?php echo htmlspecialchars($item->link) ?>" target="_blank" title="<?php echo htmlspecialchars($item->baslik) ?>" class="btn btn-tertiary text-white mt-3 ml-2 animate-up-2">
                            İncele
                            <span class="icon icon-xs ml-2">
                                <i class="far fa-eye"></i>
                            </span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>


            <div id="projeler">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 text-center mb-5 mb-lg-6">
                        <h2 class="display-3 mb-4">Projelerim</h2>
                        <p class="lead">Yaptığım projelerle ilgili detaylı bilgiye bu bölümden ulaşabilirsiniz <br class="d-none d-lg-inline-block"> have to say about our mobile app and our support team.</p>
                    </div>
                </div>
                <div class="filters portfolio-filter mb-4">
                    <button class="btn btn-outline-dark filter active" data-filter="*">Hepsi</button>
                    <?php
                    $projeler = new Tayyip();
                    $kategoriArray = [];

                    foreach ($projeler->getProjeA() as $item) {
                        if (!in_array($item->kategori, $kategoriArray)) {
                            $kategoriArray[] = $item->kategori;
                        }
                    }

                    foreach ($kategoriArray as $kategori) {
                    ?>
                        <button class="btn btn-outline-dark filter" data-filter=".<?php echo htmlspecialchars($kategori); ?>"><?php echo htmlspecialchars($kategori); ?></button>
                    <?php
                    }
                    ?>

                </div>

                <div class="filters-content">
                    <div class="row portfolio-grid justify-content-center">
                        <?php
                        $projeler = new Tayyip();
                        foreach ($projeler->getProjeA() as $item) :
                        ?>
                            <a href="/proje/<?php echo htmlspecialchars($item->projeUrl) ?>" data-filter="<?php echo htmlspecialchars($item->kategori) ?>" class="col-lg-4 col-md-6 p-3 all">
                                <div class="portfolio_box">
                                    <div class="single_portfolio">
                                        <?php
                                            $proj_thumb_filename = htmlspecialchars($item->fotograf);
                                            $proj_thumb_path = "images/" . $proj_thumb_filename;
                                            $proj_thumb_webp_path = "images/" . pathinfo($proj_thumb_filename, PATHINFO_FILENAME) . '.webp';
                                            $proj_thumb_file_extension = strtolower(pathinfo($proj_thumb_filename, PATHINFO_EXTENSION));
                                            $proj_thumb_mime_type = ($proj_thumb_file_extension == 'jpg' || $proj_thumb_file_extension == 'jpeg') ? 'image/jpeg' : (($proj_thumb_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                                        ?>
                                        <picture>
                                            <source srcset="<?php echo $proj_thumb_webp_path; ?>" type="image/webp">
                                            <source srcset="<?php echo $proj_thumb_path; ?>" type="<?php echo $proj_thumb_mime_type; ?>">
                                            <img class="img-fluid w-100 rounded" src="<?php echo $proj_thumb_path; ?>" alt="<?php echo htmlspecialchars($item->proje); ?>">
                                        </picture>
                                        <div class="overlay"></div>
                                    </div>
                                    <div class="short_info">
                                        <h4><?php echo htmlspecialchars($item->proje) ?></h4>
                                        <p><?php echo htmlspecialchars($item->teknoloji) ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include 'partials/teknolojiler.php';
    ?>

    <section class="section section-sm">
        <div class="container">
            <?php
            $yorumlar = new Tayyip();
            $yorumlarListesi = $yorumlar->getYorum();

            if (!empty($yorumlarListesi)) :
            ?>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 text-center mb-5 mb-lg-6">
                        <h2 class="display-3 mb-4">Sizden Gelen Yorumlar</h2>
                    </div>
                </div>
                <div class="row mt-lg-4">
                    <?php foreach ($yorumlarListesi as $item) : ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card border-light">
                                <div class="card-body text-center py-5">
                                    <?php
                                        $avatar_filename = htmlspecialchars($item->fotograf);
                                        $avatar_path = "images/" . $avatar_filename;
                                        $avatar_webp_path = "images/" . pathinfo($avatar_filename, PATHINFO_FILENAME) . '.webp';
                                        $avatar_file_extension = strtolower(pathinfo($avatar_filename, PATHINFO_EXTENSION));
                                        $avatar_mime_type = ($avatar_file_extension == 'jpg' || $avatar_file_extension == 'jpeg') ? 'image/jpeg' : (($avatar_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                                    ?>
                                    <picture>
                                        <source srcset="<?php echo $avatar_webp_path; ?>" type="image/webp">
                                        <source srcset="<?php echo $avatar_path; ?>" type="<?php echo $avatar_mime_type; ?>">
                                        <img class="image-sm img-fluid mx-auto mb-3" src="<?php echo $avatar_path; ?>" alt="<?php echo htmlspecialchars($item->yorum_yapan); ?>" width="80" height="80"> {/* Assuming 80x80px for image-sm avatars */}
                                    </picture>
                                    <p class="px-2 my-4"><?php echo htmlspecialchars($item->yorum) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else :
            ?>

            <?php endif; ?>
        </div>
    </section>


    <section class="section section-sm">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center mb-4 mb-lg-5">
                    <h2 class="display-3 mb-4">Blog Yazılarım</h2>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php
                    $bloglar = new Tayyip();
                    foreach ($bloglar->getBlogA() as $item) :
                    ?>
                        <div class="col">
                            <div class="card h-100">
                                <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>">
                                    <?php
                                        $blog_thumb_filename = htmlspecialchars($item->fotograf);
                                        $blog_thumb_path = "images/" . $blog_thumb_filename;
                                        $blog_thumb_webp_path = "images/" . pathinfo($blog_thumb_filename, PATHINFO_FILENAME) . '.webp';
                                        $blog_thumb_file_extension = strtolower(pathinfo($blog_thumb_filename, PATHINFO_EXTENSION));
                                        $blog_thumb_mime_type = ($blog_thumb_file_extension == 'jpg' || $blog_thumb_file_extension == 'jpeg') ? 'image/jpeg' : (($blog_thumb_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                                    ?>
                                    <picture>
                                        <source srcset="<?php echo $blog_thumb_webp_path; ?>" type="image/webp">
                                        <source srcset="<?php echo $blog_thumb_path; ?>" type="<?php echo $blog_thumb_mime_type; ?>">
                                        <img src="<?php echo $blog_thumb_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item->baslik); ?>">
                                    </picture>
                                </a>
                                <div class="card-body">
                                    <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>">
                                        <h5 class="blog-title stretched-link"><?php echo htmlspecialchars($item->baslik) ?></h5>
                                    </a>
                                    <p class="card-text"><?php echo htmlspecialchars($item->kisa_aciklama) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 pb-3 pt-0">
                                    <span class="small text-gray-600 mr-2"><i class="bi bi-person mr-1"></i>Tayyip</span>
                                    <span class="small text-gray-600 mr-2">
                                        <i class="bi bi-calendar4-week mr-1"></i>
                                        <?php
                                        $date = new DateTime($item->tarih);
                                        $months = [
                                            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan', 5 => 'Mayıs', 6 => 'Haziran',
                                            7 => 'Temmuz', 8 => 'Ağustos', 9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                                        ];
                                        $day = $date->format('j'); // Gün numarası (0 öncüsüz)
                                        $monthNumber = $date->format('n'); // Ay numarası (0 öncüsüz)
                                        $monthName = $months[$monthNumber]; // Ay ismi
                                        echo $day . ' ' . $monthName;
                                        ?>
                                    </span>
                                    <span class="small text-gray-600"><i class="bi bi-bookmark mr-1"></i><?php echo htmlspecialchars($item->etiket) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    $ayarlar = new Tayyip();
    $item = $ayarlar->getAyarlar();
    ?>
    <section class="section section-sm">
        <div class="container">
            <div class="row row-grid align-items-center">
                <div class="col-12 col-lg-6">
                    <p class="display-5 font-weight-bold mb-4 mb-lg-5">Benimle İletişime Geç</p>

                    <form id="contactForm" method="post" class="row g-3 needs-validation" novalidate>
                        <div class="col-12">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" id="name" name="name" value="" required>
                            <div class="invalid-feedback">
                                Ad soyad alanını doldurmak zorunludur.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="" required>
                            <div class="invalid-feedback">
                                E-posta alanını doldurmak zorunludur.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="mesaj" class="form-label">Mesaj</label>
                            <textarea class="form-control" id="mesaj" name="message" placeholder="Mesajınızı Yazınız" required></textarea>
                            <div class="invalid-feedback">
                                Mesaj alanını doldurmak zorunludur.
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-dark" name="sendEmail" type="submit">Gönder <i class="far fa-paper-plane ml-2"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-lg-5 ml-lg-auto">
                    <?php
                        $iletisim_image_filename = htmlspecialchars($item->fotograf); // Assuming $item here is from $ayarlar->getAyarlar()
                        $iletisim_image_path = "images/" . $iletisim_image_filename;
                        $iletisim_image_webp_path = "images/" . pathinfo($iletisim_image_filename, PATHINFO_FILENAME) . '.webp';
                        $iletisim_file_extension = strtolower(pathinfo($iletisim_image_filename, PATHINFO_EXTENSION));
                        $iletisim_mime_type = ($iletisim_file_extension == 'jpg' || $iletisim_file_extension == 'jpeg') ? 'image/jpeg' : (($iletisim_file_extension == 'png') ? 'image/png' : 'image/octet-stream');
                    ?>
                    <picture>
                        <source srcset="<?php echo $iletisim_image_webp_path; ?>" type="image/webp">
                        <source srcset="<?php echo $iletisim_image_path; ?>" type="<?php echo $iletisim_mime_type; ?>">
                        <img class="d-none d-lg-inline-block" src="<?php echo $iletisim_image_path; ?>" alt="İletişim - Tayyip Bölük">
                    </picture>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-sm">
        <div class="text-center">
            <div class="row">
                <div class="col instagram">

                </div>
                <div class="col instagram">

                </div>
                <div class="col instagram">

                </div>
                <div class="col instagram">

                </div>
                <div class="col instagram">

                </div>
                <div class="col instagram">

                </div>
            </div>
        </div>
    </section>
</main>

<!-- Toast message -->
<div class="toast" style="position: fixed; top: 20px; right: 20px;" data-delay="5000">
    <div class="toast-header">
        <strong class="mr-auto">Bildirim</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body text-white" id="toastMessage">
    </div>
</div>

<?php
include 'partials/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('contactForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'email.php', true);
            xhr.onload = function() {
                var toastElement = document.querySelector('.toast');
                var toastBody = document.getElementById('toastMessage');

                if (xhr.status >= 200 && xhr.status < 400) {
                    toastBody.innerText = 'Mesajınız başarıyla gönderildi.';
                    toastElement.classList.remove('bg-danger');
                    toastElement.classList.add('bg-success');
                } else {
                    toastBody.innerText = 'Mesaj gönderilirken bir hata oluştu.';
                    toastElement.classList.remove('bg-success');
                    toastElement.classList.add('bg-danger');
                }

                var toast = new bootstrap.Toast(toastElement);
                toast.show();
            };
            xhr.onerror = function() {
                var toastElement = document.querySelector('.toast');
                var toastBody = document.getElementById('toastMessage');

                toastBody.innerText = 'Mesaj gönderilirken bir hata oluştu.';
                toastElement.classList.remove('bg-success');
                toastElement.classList.add('bg-danger');

                var toast = new bootstrap.Toast(toastElement);
                toast.show();
            };
            xhr.send(formData);
        });
    });
</script>