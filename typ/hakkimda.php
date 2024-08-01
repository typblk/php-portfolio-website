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

    <?php
    $hakkimda = new Tayyip();
    $item = $hakkimda->getHakkimizda();

    $hakkimizda = $item->hakkimizda;
    $lines = explode(PHP_EOL, $hakkimizda);
    $first_part = array_slice($lines, 0, 4);
    $second_part = array_slice($lines, 4);
    $first_part_text = htmlspecialchars(implode(PHP_EOL, $first_part));
    $second_part_text = htmlspecialchars(implode(PHP_EOL, $second_part));
    ?>

    <section class="section section-sm">
        <div class="container">
            <div class="row">
                <div class="row row-grid align-items-center mb-5 mb-lg-7">
                    <div class="col-12 col-lg-6 ml-lg-auto">
                        <img src="./images/<?php echo htmlspecialchars($item->fotograf) ?>" class="w-100" alt="<?php echo htmlspecialchars($item->baslik) ?>">
                    </div>
                    <div class="col-12 col-lg-5">
                        <h2 class="mb-4"><?php echo htmlspecialchars($item->baslik) ?></h2>
                        <p><?php echo nl2br($first_part_text); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><?php echo nl2br($second_part_text); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php
    include 'partials/teknolojiler.php';
    ?>

    <div class="pt-5">
        <?php
        include 'partials/hizmetler.php';
        ?>
    </div>

</main>

<?php
include 'partials/footer.php';
?>