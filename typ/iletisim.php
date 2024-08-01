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
  $ayarlar = new Tayyip();
  $item = $ayarlar->getAyarlar();
  ?>

  <section class="section section-sm">
    <div class="container">
      <div class="row row-grid align-items-center">
        <div class="col-12 col-lg-5 ml-lg-auto">
          <p class="display-5 font-weight-bold mb-4 mb-lg-5">Benimle İletişime Geç</p>
          <ul class="list-unstyled">
            <li class="py-3"><i class="bi bi-telephone display-4 mr-4 text-tertiary"></i><a class="text-decoration-none font-weight-bold text-dark" href="tel:+<?php echo htmlspecialchars($item->tel) ?>"><?php echo htmlspecialchars($item->tel) ?></a></li>
            <li class="py-3"><i class="bi bi-envelope display-4 mr-4 text-tertiary"></i><a class="text-decoration-none font-weight-bold text-dark" href="mailto:<?php echo htmlspecialchars($item->email) ?>"><?php echo htmlspecialchars($item->email) ?></a></li>
            <li class="py-3"><i class="bi bi-telegram display-4 mr-4 text-tertiary"></i><a class="text-decoration-none font-weight-bold text-dark" href="<?php echo htmlspecialchars($item->tele) ?>"><?php echo htmlspecialchars($item->tele) ?></a></li>
          </ul>
        </div>
        <div class="col-12 col-lg-6">
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
      </div>
    </div>
  </section>

  <div class="py-5">
    <?php
    include 'partials/hizmetler.php';
    ?>
  </div>
</main>

<!-- Toast message -->
<div class="toast" style="position: fixed; top: 20px; right: 20px;" data-delay="5000">
  <div class="toast-header">
    <strong class="mr-auto">Bildirim</strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
  <div class="toast-body" id="toastMessage">
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