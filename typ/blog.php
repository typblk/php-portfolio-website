<?php
include 'partials/header.php';
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<main>

    <?php
    include 'partials/slider.php';
    ?>

    <section class="section section-sm">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-9 pr-3">
                    <div class="row row-cols-1 g-4">
                        <?php
                        $bloglar = new Tayyip();
                        foreach ($bloglar->getBlog() as $item) :
                        ?>
                            <div class="col">
                                <div class="row">
                                    <div class="col-lg-4 pl-0">
                                        <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>">
                                            <img src="images/<?php echo htmlspecialchars($item->fotograf) ?>" class="img-fluid" alt="<?php echo htmlspecialchars($item->baslik) ?>">
                                        </a>
                                    </div>
                                    <div class="col-lg-8 card-body">
                                        <a href="/bloglar/<?php echo htmlspecialchars($item->blogUrl) ?>">
                                            <h5 class="blog-title"><?php echo htmlspecialchars($item->baslik) ?></h5>
                                        </a>
                                        <p class="card-text"><?php echo htmlspecialchars($item->kisa_aciklama) ?></p>
                                        <div class="card-footer bg-white border-0 pb-3 pt-0">
                                            <span class="small text-gray-600 mr-2"><i class="bi bi-person mr-1"></i>Tayyip Bölük</span>
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
                            </div>
                        <?php endforeach; ?>
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
</main>

<?php
include 'partials/footer.php';
?>