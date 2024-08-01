<?php 
$page = basename($_SERVER['PHP_SELF'], ".php");

$sayfa = $page;
$slayt = new Tayyip();
$item = $slayt->getSlaytSayfa($sayfa);
?>


<section class="section section-header text-dark">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-7 mb-5 mb-md-7 mt-5">
        <h1 class="display-3 font-weight-bolder mb-4">
        <?php echo htmlspecialchars($item->baslik) ?>
        </h1>
        <div class="d-flex align-items-center mb-4">
          <hr>
          <h5 class="display-4s font-weight-bold">Yazılım Geliştirici</h5>
        </div>
        <p class="lead mb-3 mb-lg-5"><?php echo htmlspecialchars($item->aciklama) ?></p>
      </div>
      <div class="col-12 col-lg-5 justify-content-center">
        <img class="d-none d-md-inline-block" src="./images/<?php echo htmlspecialchars($item->fotograf) ?>" alt="<?php echo htmlspecialchars($item->baslik) ?>">
      </div>
    </div>
  </div>
</section>