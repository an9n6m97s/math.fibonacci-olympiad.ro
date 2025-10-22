<div class="container mt-5">
    <!-- Page header -->
    <div class="row gy-3 px-4 px-lg-6 pt-6 justify-content-between">
        <div class="col-auto">
            <h2 class="mb-0 text-body-emphasis">Our Sponsors</h2>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="px-4 px-lg-6 pb-6">

        <h1 class="text-center text-uppercase mt-5 text-dark">Our Sponsors</h1>
        <div class="row justify-content-center g-3">


            <?php
            $sponsorImages = glob($_SERVER['DOCUMENT_ROOT'] . '/assets/images/sponsors/*.{jpg,png,gif,webp}', GLOB_BRACE);
            foreach ($sponsorImages as $image) {
                $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image);
                if ($imagePath != '/assets/images/sponsors/liceul-teoretic-de-informatica-alexandru-marghiloman.webp') {
                    echo '<div class="col-md-4">';
                    echo "<div class=\"card mt-3 p-3 partenersBox\" style=\"background-image: url($imagePath);\"></div>";
                    echo '</div>';
                }
            }
            ?>


        </div>

        <h1 class="text-center text-uppercase mt-5 text-dark">Organizers</h1>
        <div class="row justify-content-center g-3">


            <?php
            $sponsorImages = glob($_SERVER['DOCUMENT_ROOT'] . '/assets/images/organizer/*.{jpg,png,gif,webp}', GLOB_BRACE);
            foreach ($sponsorImages as $image) {

                $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image);
                echo '<div class="col-md-4">';
                echo "<div class=\"card mt-3 p-3 partenersBox\" style=\"background-image: url($imagePath);\"></div>";
                echo '</div>';
            }
            ?>


        </div>

        <h1 class="text-center text-uppercase mt-5 text-dark">Partners</h1>
        <div class="row justify-content-center g-3">


            <?php
            $sponsorImages = glob($_SERVER['DOCUMENT_ROOT'] . '/assets/images/parteners/*.{jpg,png,gif,webp}', GLOB_BRACE);
            foreach ($sponsorImages as $image) {

                $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image);

                echo '<div class="col-md-4">';
                if ($imagePath == '/assets/images/parteners/essenbyte.webp') {
                    echo "";
                    echo "<a href=\"https://essenbyte.com\"><div class=\"card mt-3 p-3 partenersBox\" style=\"background-image: url($imagePath); background-color: white;\"></div></a>";
                } else {
                    echo "<div class=\"card mt-3 p-3 partenersBox\" style=\"background-image: url($imagePath);\"></div>";
                }

                echo '</div>';
            }
            ?>

        </div>

    </div>
</div>