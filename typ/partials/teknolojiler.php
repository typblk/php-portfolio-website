<section class="section section-sm">
  <div class="container">
    <div class="row text-center mb-5">
      <div class="col">
        <h2 class="display-3 mb-2">Kullandığım Teknolojiler</h2>
      </div>
    </div>
    <div class="row text-center">
      <div class="col d-flex justify-content-center flex-wrap">
      <?php
      $teknolojiler = new Tayyip(); 
      foreach ($teknolojiler->getTeknoloji() as $item) :
      ?>
        <span title="<?php echo htmlspecialchars($item->teknoloji) ?>" class="icon icon-xl icon-dark mr-3 mr-sm-4 py-3">
          <img src="./images/<?php echo htmlspecialchars($item->logo) ?>" width="50" alt="<?php echo htmlspecialchars($item->teknoloji) ?>">
        </span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>