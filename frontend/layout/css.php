<!-- Style css -->

<?php
if (preg_match('#^/ucp(?:/[^/]+)*/?$#', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH))) : ?>
    <script src="/assets/ucp/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/ucp/js/config.js"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="/assets/ucp/vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/ucp/icons/line.css">
    <link href="/assets/ucp/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="/assets/ucp/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="/assets/ucp/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="/assets/ucp/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
    <link href="/assets/ucp/vendors/leaflet/leaflet.css" rel="stylesheet">
    <link href="/assets/ucp/vendors/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
    <link href="/assets/ucp/vendors/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet">
    <link href="/assets/ucp/vendors/dropzone/dropzone.css" rel="stylesheet" />
    <link href="/assets/ucp/vendors/flatpickr/flatpickr.min.css" rel="stylesheet" />
    <link href="/assets/ucp/vendors/choices/choices.min.css" rel="stylesheet" />

<?php else : ?>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/animate.min.css">
    <link rel="stylesheet" href="/assets/css/keyframe-animation.css">
    <link rel="stylesheet" href="/assets/lib/font-awesome-pro/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/css/logistic-icons.min.css">
    <link rel="stylesheet" href="/assets/css/odometer.min.css">
    <link rel="stylesheet" href="/assets/css/nice-select.css">
    <link rel="stylesheet" href="/assets/css/swiper.min.css">
    <link rel="stylesheet" href="/assets/css/venobox.min.css">
    <link rel="stylesheet" href="/assets/css/slider.css">
    <link rel="stylesheet" href="/assets/css/common-style.css">
    <link rel="stylesheet" href="/assets/css/main.css">



<?php endif; ?>
<link href="/assets/css/general.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- CSS -->
<!-- JS -->
<script src="/vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script src="/assets/js/vendor/jquary-3.6.0.min.js"></script>