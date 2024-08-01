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
            <div id="projeler">
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
                    foreach ($projeler->getProje() as $item) :
                    ?>
                        <a href="/proje/<?php echo htmlspecialchars($item->projeUrl) ?>" data-filter="<?php echo htmlspecialchars($item->kategori) ?>" class="col-lg-4 col-md-6 p-3 all">
                            <div class="portfolio_box">
                                <div class="single_portfolio">
                                    <img class="img-fluid w-100" src="images/<?php echo htmlspecialchars($item->fotograf) ?>" alt="<?php echo htmlspecialchars($item->proje) ?>">
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
</main>

<?php
include 'partials/footer.php';
?>