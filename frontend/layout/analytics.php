<script async src="https://www.googletagmanager.com/gtag/js?id=G-EHJRCHJWSF"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EHJRCHJWSF');
</script>

<script type="application/json">
    {
        "analytics_provider": "Google",
        "ga4": {
            "measurement_id": "G-EHJRCHJWSF",
            "enabled": true,
            "anonymize_ip": true,
            "send_page_view": true,
            "debug_mode": false,
            "cookie_flags": "SameSite=None;Secure"
        },
        "custom_events": [{
            "name": "page_view",
            "trigger": "auto",
            "parameters": {
                "page_title": "<?= getPageTitle(); ?>",
                "page_location": "<?= getBaseUrl(); ?>",
                "page_path": "<?= getPagePath(); ?>"
            }
        }]
    }
</script>