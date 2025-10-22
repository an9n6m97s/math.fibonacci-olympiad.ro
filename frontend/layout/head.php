<head>
    <!-- ----------------------- -->
    <!-- BROWSER & VIEWPORT      -->
    <!-- ----------------------- -->
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <!-- Titlu pagină -->
    <?php setPageTitle(); ?>

    <!-- ----------------------- -->
    <!-- META DE BAZĂ            -->
    <!-- ----------------------- -->
    <meta name="description" content="<?= getPageDescription(); ?>">
    <meta name="keywords" content="robot-sumo competition, line follower robotics, robot football tournament, maze-solving robot, robot combat event, humanoid robot challenge, robotics contest Romania, student robotics teams, STEM robotics showdown, autonomous robot battle, high-school robotics competition, university robotics challenge, live robot demos, engineering robotics event, robotics qualification event">
    <meta name="author" content="Relativity Robotics Team">
    <meta name="robots" content="index, follow"> <!-- ex: index, follow -->

    <!-- ----------------------- -->
    <!-- SOCIAL MEDIA            -->
    <!-- ----------------------- -->
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= $settings['competition_name'] ?? 'Fibonacci Romania'; ?>">
    <meta property="og:locale" content="ro_RO">
    <meta property="og:title" content="<?= getPageTitle(); ?>">
    <meta property="og:description" content="<?= getPageDescription(); ?>">
    <meta property="og:url" content="<?= getCanonicalUrl(); ?>">
    <meta property="og:image" content="https://fibonacci-olympiad.ro/assets/images/logo/logo.webp">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:creator" content="Relativity Robotics Team">
    <meta name="twitter:title" content="<?= getPageTitle(); ?>">
    <meta name="twitter:description" content="<?= getPageDescription(); ?>">
    <meta name="twitter:image" content="https://fibonacci-olympiad.ro/assets/images/logo/logo.webp">

    <!-- Development team meta tags -->
    <meta name="author" content="EssenByte Solutions">
    <meta name="designer" content="EssenByte Web Development Team">
    <meta name="copyright" content="&copy; <?= date('Y') ?> EssenByte Solutions">
    <meta name="publisher" content="EssenByte Solutions">

    <!-- Optional extended tags for credibility and SEO -->
    <meta name="developer" content="EssenByte Solutions - Full-Stack Web & Robotics Development">
    <meta name="developer-url" content="https://essenbyte.com">
    <meta name="creator" content="Lucian - EssenByte Team Lead">

    <!-- ----------------------- -->
    <!-- FAVICONS & PWA          -->
    <!-- ----------------------- -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/logo/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/logo/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/logo/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#761fe3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- ----------------------- -->
    <!-- PERFORMANCE HINTS       -->
    <!-- ----------------------- -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">

    <!-- ----------------------- -->
    <!-- STRUCTURED DATA         -->
    <!-- ----------------------- -->

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Event",
            "name": "<?php echo getPageTitle(); ?>",
            "description": "<?php echo getPageDescription(); ?>",
            "startDate": "2025-02-27T10:00:00+02:00",
            "endDate": "2025-03-01T22:00:00+02:00",
            "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
            "duration": "P4D",
            "eventStatus": "<?php echo checkEventStatus(); ?>",
            "eventAttendanceMode": "https://schema.org/MixedEventAttendanceMode",
            "inLanguage": "en-US",
            "isAccessibleForFree": "true",
            "typicalAgeRange": "14-70",
            "image": "/assets/images/logo/favicon.ico",
            "sponsor": [{
                    "@type": "Organization",
                    "name": "Eximprod Grup",
                    "url": "https://www.epg.ro/"
                },
                {
                    "@type": "Organization",
                    "name": "Motionplast",
                    "url": "https://motionplast.ro/"
                },
                {
                    "@type": "Organization",
                    "name": "Raiffeisen Bank",
                    "url": "https://www.raiffeisen.ro/"
                },
                {
                    "@type": "Organization",
                    "name": "TEHNOMET S.A.",
                    "url": "https://www.tehnometbz.ro/"
                },
                {
                    "@type": "Organization",
                    "name": "RotMac Eco",
                    "url": ""
                },
                {
                    "@type": "Organization",
                    "name": "WMC Guard",
                    "url": "https://wmcguard.ro/"
                }
            ],
            "eventSchedule": {
                "@type": "Schedule",
                "startDate": "2025-02-27",
                "endDate": "2025-03-01",
                "repeatFrequency": "P1Y",
                "byDay": [
                    "https://schema.org/Thursday",
                    "https://schema.org/Friday",
                    "https://schema.org/Saturday",
                    "https://schema.org/Sunday"
                ],
                "startTime": "10:00:00",
                "endTime": "22:00:00",
                "scheduleTimezone": "Europe/Bucharest"
            },
            "location": {
                "@type": "Place",
                "name": "Liceul Teoretic de Informatică 'Alexandru Marghiloman'",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Strada Ivănețu, Nr. 7",
                    "addressLocality": "Buzău",
                    "addressRegion": "Buzău",
                    "postalCode": "120114",
                    "addressCountry": "RO"
                }
            },
            "organizer": [{
                    "@type": "Organization",
                    "name": "Relativity Robotics",
                    "url": "https://fibonacci-olympiad.ro"
                },
                {
                    "@type": "Organization",
                    "name": "Liceul Teoretic de Informatica \"Alexandru Marghiloman\"",
                    "url": "https://www.licmarghilomanbz.ro"
                }
            ],
            "contributor": [{
                    "@type": "Organization",
                    "name": "Robochallenge",
                    "url": "https://robochallenge.ro/"
                },
                {
                    "@type": "Organization",
                    "name": "UNSTPB",
                    "url": "https://essenbyte.com"
                },
                {
                    "@type": "Organization",
                    "name": "🛡️EssenByte Solutions",
                    "url": "https://essenbyte.com"
                }
            ],
            "offers": {
                "@type": "AggregateOffer",
                "priceCurrency": "EUR",
                "highPrice": "0",
                "lowPrice": "0",
                "availability": "https://schema.org/InStock",
                "offerCount": "10000",
                "validFrom": "2025-09-01T00:00:00+02:00",
                "url": "https://fibonacci-olympiad.ro/participants",
                "offers": [{
                        "@type": "Offer",
                        "url": "https://fibonacci-olympiad.ro/register"
                    },
                    {
                        "@type": "Offer",
                        "url": "https://fibonacci-olympiad.ro/participants"
                    }
                ]
            },
            "performer": [{
                    "@type": "PerformingGroup",
                    "name": "Relativity Robotics"
                },
                {
                    "@type": "PerformingGroup",
                    "name": "Liceul Teoretic de Informatica \"Alexandru Marghiloman\""
                }
            ]
        }
    </script>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "https://fibonacci-olympiad.ro/"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Competition Regulations",
                    "item": "https://fibonacci-olympiad.ro/regulation"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "Accreditation Details",
                    "item": "https://fibonacci-olympiad.ro/accreditations"
                },
                {
                    "@type": "ListItem",
                    "position": 4,
                    "name": "Partners and Sponsors",
                    "item": "https://fibonacci-olympiad.ro/partners"
                },
                {
                    "@type": "ListItem",
                    "position": 5,
                    "name": "Participants List",
                    "item": "https://fibonacci-olympiad.ro/participants"
                },
                {
                    "@type": "ListItem",
                    "position": 6,
                    "name": "Contact Us",
                    "item": "https://fibonacci-olympiad.ro/contact"
                },
                {
                    "@type": "ListItem",
                    "position": 7,
                    "name": "Register for the Competition",
                    "item": "https://fibonacci-olympiad.ro/register"
                },
                {
                    "@type": "ListItem",
                    "position": 8,
                    "name": "Login to Your Account",
                    "item": "https://fibonacci-olympiad.ro/ucp/login"
                }
            ]
        }
    </script>

    <!-- Mod tematic (0 = light, 1 = dark) -->
    <meta name="theme-style-mode" content="1">

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/analytics.php'; ?>
</head>