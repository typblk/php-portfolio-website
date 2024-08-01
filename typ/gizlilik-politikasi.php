<?php
include 'partials/header.php';
?>

<?php
$logger = new Tayyip();
$logger->logTraffic(basename($_SERVER['PHP_SELF']));
?>

<main>

    <?php
    $politika = new Tayyip();
    $item = $politika->getGizlilik();
    ?>
    <section class="section section-lg">
        <div class="container">
            <div class="row">
                <div class="row row-grid align-items-center mb-5 mb-lg-7">
                    <div class="col-12">
                        <h2 class="mb-4">Gizlilik Politikası</h2>
                        <p><?php echo htmlspecialchars($item->gizlilik) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
include 'partials/footer.php';
?>