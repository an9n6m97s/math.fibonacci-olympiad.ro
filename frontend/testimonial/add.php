<!-- Page Title -->
<section class="page-title">
    <div class="page-title-icon" style="background-image:url(/assets/images/icons/page-title_icon-1.webp)"></div>
    <div class="page-title-icon-two" style="background-image:url(/assets/images/icons/page-title_icon-2.webp)"></div>
    <div class="page-title-shadow" style="background-image:url(/assets/images/background/page-title-1.webp)"></div>
    <div class="page-title-shadow_two" style="background-image:url(/assets/images/background/page-title-2.webp)"></div>
    <div class="auto-container">
        <h2>Clients testimonials</h2>
        <ul class="bread-crumb clearfix">
            <li><a href="/">Home</a></li>
            <li>Testimonial</li>
        </ul>
        <h2 class="h3">Have you participated in a past edition?<br>
            Share your experience and inspire future competitors!<br>
            Click below to add a review:</h2>
        <a href="/testimonial/add">
            <div class="testimonial-block_one-rating">
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa fa-star fa-2xl"></span>
                <span class="fa-regular fa-star fa-2xl"></span>
            </div>
        </a>

    </div>
</section>
<!-- End Page Title -->

<!-- Testimonial Four -->
<section class="team-detail_form">
    <div class="auto-container">
        <div class="row clearfix">
            <!-- Column -->

            <div class="column col-lg-12 col-md-12 col-sm-12">
                <div class="default-form contact-form">


                    <form method="post" action="submit_testimonial.php" id="testimonial-form" enctype="multipart/form-data">
                        <div class="row clearfix">

                            <!-- Author -->
                            <div class="form-group col-lg-6">
                                <input type="text" name="author" placeholder="Author Name" required>
                            </div>

                            <!-- Role -->
                            <div class="form-group col-lg-6">
                                <input type="text" name="role" placeholder="Role / Position ( e.g., Leader, Member, C.E.O., Manager)" required>
                            </div>

                            <!-- Team -->
                            <div class="form-group col-lg-6">
                                <input type="text" name="team" placeholder="Team / Company">
                            </div>

                            <!-- Photo -->
                            <div class="form-group col-lg-6">
                                <label for="photo-upload" class="upload-btn">
                                    <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linejoin="round" stroke-linecap="round"
                                            d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625
                M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" />
                                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                            d="M17 15V18M17 21V18M17 18H14M17 18H20" />
                                    </svg>
                                    ADD PROFILE PICTURE
                                </label>
                                <input type="file" name="photo" id="photo-upload" accept="image/*" hidden>
                                <div id="preview-container">
                                    <img id="photo-preview" src="" alt="Image Preview" style="display:none; max-width: 100%; margin-top: 0.5rem; border-radius: 0.25rem;" />
                                </div>
                            </div>

                            <!-- Star Rating -->
                            <div class="form-group col-lg-12">
                                <h2 class="sec-title_heading h5">Your Rating:</h2>
                                <div class="star-rating" id="star-rating">
                                    <span data-value="1" class="fa fa-star-o"></span>
                                    <span data-value="2" class="fa fa-star-o"></span>
                                    <span data-value="3" class="fa fa-star-o"></span>
                                    <span data-value="4" class="fa fa-star-o"></span>
                                    <span data-value="5" class="fa fa-star-o"></span>
                                </div>
                            </div>

                            <!-- Review -->
                            <div class="form-group col-lg-12">
                                <textarea name="review" placeholder="Your testimonial..." required></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="form-group col-lg-12">
                                <button type="submit" class="template-btn btn-style-one">
                                    <span class="btn-wrap">
                                        <span class="text-one">Submit Testimonial</span>
                                        <span class="text-two">Submit Testimonial</span>
                                    </span>
                                </button>
                            </div>

                        </div>
                    </form>


                </div>
            </div>

        </div>
    </div>
</section>
<!-- End Testimonial Four -->