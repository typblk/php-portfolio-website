 <!-- Footer -->
 <footer class="content-footer footer bg-footer-theme">
   <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
     <div class="mb-2 mb-md-0">
       ©
       <script>
         document.write(new Date().getFullYear());
       </script>
       <a href="https://tayyipboluk.com" target="_blank" class="footer-link fw-bolder">Tayyip Bölük</a>
     </div>
   </div>
 </footer>
 <!-- / Footer -->

 <div class="content-backdrop fade"></div>
 </div>
 <!-- Content wrapper -->
 </div>
 <!-- / Layout page -->
 </div>

 <!-- Overlay -->
 <div class="layout-overlay layout-menu-toggle"></div>
 </div>
 <!-- / Layout wrapper -->

 <!-- Core JS -->
 <!-- build:js assets/vendor/js/core.js -->
 <script src="./assets/vendor/libs/jquery/jquery.js"></script>
 <script src="./assets/vendor/libs/popper/popper.js"></script>
 <script src="./assets/vendor/js/bootstrap.js"></script>
 <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

 <script src="./assets/vendor/js/menu.js"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>
 <script src="./assets/vendor/ckeditor.js"></script>

 <!-- Main JS -->
 <script src="./assets/js/main.js"></script>

 <!-- Page JS -->
 <script src="./assets/js/dashboards-analytics.js"></script>

 <!-- Place this tag in your head or just before your close body tag. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>

 <script>
   ClassicEditor
     .create(document.querySelector('#blog'))
     .then(editor => {
       console.log(editor);
     })
     .catch(error => {
       console.error(error);
     });
 </script>

 <script>
   (function() {
     var timeoutDuration = 900000;
     var timeout;

     function resetTimer() {
       clearTimeout(timeout);
       timeout = setTimeout(logout, timeoutDuration);
     }

     function logout() {
       window.location.href = '/cikis.php'; // Çıkış işlemini gerçekleştirecek sayfa
     }

     // Kullanıcı etkinliğini dinleyin
     window.onload = resetTimer;
     document.onmousemove = resetTimer;
     document.onkeypress = resetTimer;
     document.onclick = resetTimer;
   })();
 </script>


 <script>
   $(document).ready(function() {
     $('.Ssil').on('click', function() {
       var dataId = $(this).data('id');
       var dataAdd = $(this).data('add');
       console.log(dataId)

       $('#silInput').val(dataId);
       $('#silAd').text(dataAdd);
     });
   });
 </script>
 </body>

 </html>