<section class="section section-sm">
  <div class="container">
    <div class="row justify-content-center">
      <?php
      $hizmetler = new Tayyip();
      foreach ($hizmetler->getHizmet() as $item) :
      ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-2">
          <div class=" border-0 bg-white p-1">
            <div class="row">
              <div class="col-3 pl-3 pr-0">
                <p class="display-3 font-weight-bold text-light d-block mr-4 service-number">01</p>
              </div>
              <div class="col-9 p-0 pl-2 pl-lg-4 border-left">
                <div class="">
                  <a href="/hizmet/<?php echo htmlspecialchars($item->hizmetUrl) ?>" title="<?php echo htmlspecialchars($item->hizmet) ?>" class=" mb-4">
                    <img src="./images/<?php echo htmlspecialchars($item->fotograf) ?>" width="70" alt="<?php echo htmlspecialchars($item->hizmet) ?>">
                  </a>
                  <a href="/hizmet/<?php echo htmlspecialchars($item->hizmetUrl) ?>">
                    <h2 class="h3 text-dark m-2"><?php echo htmlspecialchars($item->hizmet) ?></h2>
                  </a>
                </div>
                <div class="pt-3">
                  <p>
                    <?php echo htmlspecialchars($item->kisa_aciklama) ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>