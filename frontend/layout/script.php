<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/settings.php'; ?>
<!-- main JS -->
<?php if (preg_match('#^/ucp(?:/[^/]+)*/?$#', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH))) : ?>
    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="/assets/ucp/vendors/popper/popper.min.js"></script>
    <script src="/assets/ucp/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="/assets/ucp/vendors/anchorjs/anchor.min.js"></script>
    <script src="/assets/ucp/vendors/is/is.min.js"></script>
    <script src="/assets/ucp/vendors/fontawesome/all.min.js"></script>
    <script src="/assets/ucp/vendors/lodash/lodash.min.js"></script>
    <script src="/assets/ucp/vendors/list.js/list.min.js"></script>
    <script src="/assets/ucp/vendors/feather-icons/feather.min.js"></script>
    <script src="/assets/ucp/vendors/dayjs/dayjs.min.js"></script>
    <script src="/assets/ucp/vendors/leaflet/leaflet.js"></script>
    <script src="/assets/ucp/vendors/leaflet.markercluster/leaflet.markercluster.js"></script>
    <script src="/assets/ucp/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js"></script>
    <script src="/assets/ucp/js/phoenix.js"></script>
    <script src="/assets/ucp/vendors/echarts/echarts.min.js"></script>
    <script src="/assets/ucp/js/dashboards/ecommerce-dashboard.js"></script>
    <script src="/assets/ucp/vendors/dropzone/dropzone-min.js"></script>
    <script src="/assets/ucp/vendors/flatpickr/flatpickr.min.js"></script>
    <script src="/assets/ucp/vendors/choices/choices.min.js"></script>

<?php else : ?>


    <script src="/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <script src="/assets/js/vendor/popper.min.js"></script>
    <script src="/assets/lib/gsap/gsap.min.js"></script>
    <script src="/assets/lib/gsap/ScrollTrigger.min.js"></script>
    <script src="/assets/lib/gsap/split-type.min.js"></script>
    <script src="/assets/js/vendor/lenis.min.js"></script>
    <script src="/assets/js/vendor/odometer.min.js"></script>
    <script src="/assets/js/vendor/jquery.nice-select.min.js"></script>
    <script src="/assets/js/vendor/waypoints.min.js"></script>
    <script src="/assets/js/vendor/venobox.min.js"></script>
    <script src="/assets/js/vendor/swiper.min.js"></script>
    <script src="/assets/js/vendor/wow.min.js"></script>
    <script src="/assets/js/mailchimp.js"></script>
    <script src="/assets/js/quote-form.js"></script>
    <script src="/assets/js/main.js"></script>

<?php endif; ?>


<script src="/functions.js?v=<?= time() ?>"></script>
<script src="/server.js?v=<?= time() ?>"></script>
<script src="/sw.js"></script>

<!-- <script src="https://essenbyte.com/backend/api/public/security.js?code=server_change&start=15.09.2025&end=25.09.2025"></script> -->