<?php

$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();
?>

<footer class="footer py-5 pt-lg-6">


    <div class="sticky-right">
        <a href="#" class="icon icon-secondary icon-md btn btn-icon-only btn-white border border-soft shadow-soft animate-up-3">
            <span class="fas fa-chevron-up"></span>
        </a>
    </div>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-4">
                <p><?php echo htmlspecialchars($item->alt_aciklama) ?></p>
                <ul class="social-buttons mb-5 mb-lg-0">
                    <li>
                        <a target="_blank" title="instagram" href="<?php echo htmlspecialchars($item->insta) ?>" aria-label="instagram social link" class="icon icon-md icon-instagram mr-3 <?php echo strlen($item->insta) > 0 ? '' : 'd-none'; ?>">
                            <span class="fab fa-instagram"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="linkedin" href="<?php echo htmlspecialchars($item->linke) ?>" class="icon icon-md icon-facebook mr-3 <?php echo strlen($item->linke) > 0 ? '' : 'd-none'; ?>" aria-label="linkedin social link">
                            <span class="fab fa-linkedin"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="github" href="<?php echo htmlspecialchars($item->git) ?>" aria-label="github social link" class="icon icon-md icon-github mr-3 <?php echo strlen($item->git) > 0 ? '' : 'd-none'; ?>">
                            <span class="fab fa-github"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="x" href="<?php echo htmlspecialchars($item->x) ?>" class="icon icon-md icon-dark mr-3 <?php echo strlen($item->x) > 0 ? '' : 'd-none'; ?>" aria-label="twitter social link">
                            <span class="bi bi-twitter-x"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="facebook" href="<?php echo htmlspecialchars($item->face) ?>" class="icon icon-md icon-facebook mr-3 <?php echo strlen($item->face) > 0 ? '' : 'd-none'; ?>" aria-label="facebook social link">
                            <span class="fab fa-facebook"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="youtube" href="<?php echo htmlspecialchars($item->you) ?>" class="icon icon-md icon-youtube mr-3 <?php echo strlen($item->you) > 0 ? '' : 'd-none'; ?>" aria-label="youtube social link">
                            <span class="fab fa-youtube"></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" title="telegram" href="<?php echo htmlspecialchars($item->tele) ?>" class="icon icon-md icon-twitter mr-3 <?php echo strlen($item->tele) > 0 ? '' : 'd-none'; ?>" aria-label="telegram social link">
                            <span class="fab fa-telegram"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-6 col-md-2 mb-5 mb-lg-0">
                <span class="h5">Tayyip Bölük</span>
                <ul class="footer-links mt-2">
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/projeler">Projeler</a></li>
                    <li><a href="/hakkimda">Hakkımda</a></li>
                    <li><a href="/iletisim">İletişim</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-4 mb-5 mb-lg-0">
                <span class="h5">Hizmetlerim</span>
                <ul class="footer-links mt-2">
                    <?php
                    $hizmetler = new Tayyip();
                    foreach ($hizmetler->getHizmet() as $item) :
                    ?>
                        <li><a href="/hizmet/<?php echo htmlspecialchars($item->hizmetUrl) ?>"><?php echo htmlspecialchars($item->hizmet) ?></a></li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>
        <div class="row pt-2 pt-lg-5">
            <div class="d-md-flex justify-content-between mb-md-0">
                <div class="d-flex text-center justify-content-center align-items-center" role="contentinfo">
                    <p class="text-tertiary font-weight-bold">Tayyip Bölük</p>
                    <p class="font-weight-normal font-small ml-2">© Tüm Hakları Saklıdır. <span class="current-year">2023</span></p>
                </div>
                <div class="text-center small">
                    <a href="gizlilik-politikasi" class="text-secondary pr-4">Gizlilik Politikası</a>
                    <a href="cerez-politikasi" class="text-secondary">Çerez Politikası</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Core -->
<script src="./vendor/popper.js/dist/umd/popper.min.js"></script>
<script src="./vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./vendor/headroom.js/dist/headroom.min.js"></script>

<!-- Vendor JS -->
<script src="./vendor/onscreen/dist/on-screen.umd.min.js"></script>
<script src="./vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<script src="vendor/isotope/imagesload.pkgd.min.js"></script>

<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Swipe JS -->
<script src="./assets/js/swipe.js"></script>

</body>

</html>