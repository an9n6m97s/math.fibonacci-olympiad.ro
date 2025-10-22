<div class="map-wrapper">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d225.68918610503897!2d26.84427709478381!3d45.15022860948328!2m3!1f0!2f39.40618408720114!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x40b15fcd09a43519%3A0xe24f4d4a4da9a4fe!2sLiceul%20Teoretic%20Alexandru%20Marghiloman!5e1!3m2!1sro!2sro!4v1741377752673!5m2!1sro!2sro" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
</div><!-- /#google-map -->

<section class="contact-section padding">
    <div class="map-pattern"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7 sm-padding">
                <div class="contact-form-wrap row-gap">
                    <div class="section-heading mb-40">
                        <h3 class="sub-heading is-border border-anim">Send Us Mail<span class="sh-underline"><img class="sh-truck" src="/assets/images/general/truck.svg" alt="truck"></span></h3>
                        <h2 class="text-anim" data-effect="fade-in-right" data-split="char" data-delay="0.3" data-duration="1">Feel Free To <span class="hl">Write</span></h2>
                        <p class="text-anim" data-effect="fade-in-bottom" data-ease="power4.out">
                            For questions about registration, rules, schedule, partnerships, or media, contact the Fibonacci Romania team.<br>
                            We coordinate all inquiries centrally and typically respond within 24–48 hours.
                        </p>
                    </div>
                    <div class="contact-form">
                        <form method="post" id="contact-form" class="form-horizontal">
                            <div class="contact-form-group">
                                <div class="form-field">
                                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" required>
                                </div>
                                <div class="form-field">
                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last Name" required>
                                </div>
                                <div class="form-field">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-field">
                                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" required>
                                </div>
                                <div class="form-field">
                                    <select id="type" name="type" class="form-control custom-select-box" required>
                                        <option value="">Select option</option>
                                        <option>Information</option>
                                        <option>Technical support</option>
                                        <option>Volunteer</option>
                                        <option>Accommodation</option>
                                        <option>Transportation</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="form-field message">
                                    <textarea id="message" name="message" cols="30" rows="4" class="form-control" placeholder="Message" required></textarea>
                                </div>
                                <div class="form-field submit-btn">
                                    <button id="submit" class="default-btn" type="submit">Send Message</button>
                                </div>
                            </div>
                            <div id="form-messages" class="ajax-form-msg alert" role="alert"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 sm-padding">
                <div class="section-heading mb-40">
                    <h3 class="sub-heading is-border border-anim">Need Any Help?<span class="sh-underline"><img class="sh-truck" src="/assets/images/general/truck.svg" alt="truck"></span></h3>
                    <h2 class="text-anim" data-effect="fade-in-right" data-split="char" data-delay="0.3" data-duration="1">Get In Touch <br><span class="hl">With Us!</span></h2>
                    <p class="text-anim" data-effect="fade-in-bottom" data-ease="power4.out">Logistics involves the efficient management and <br>coordination of the movement of goods.</p>
                </div>
                <ul class="contact-info-list">
                    <li class="wow fade-in-bottom" data-wow-delay="100ms">
                        <i class="fa-light fa-phone-volume"></i>
                        <h3>Have any question? <span><a href="https://wa.me/<?= str_replace(' ', '', $settings['contact_phone']) ?>"><?= $settings['contact_phone'] ?></a></span></h3>
                    </li>
                    <li class="wow fade-in-bottom" data-wow-delay="300ms">
                        <i class="fa-light fa-envelope-open-text"></i>
                        <h3>Write us email <span><a href="mailto:<span><?= $settings['contact_email'] ?>"><span><?= $settings['contact_email'] ?></a></span></h3>
                    </li>
                    <li class="wow fade-in-bottom" data-wow-delay="500ms">
                        <i class="fa-light fa-location-dot"></i>
                        <h3>Headquoters <span>Liceul Teoretic de Informatica "Alexandru Marghiloman" <br> Str. Ivănețu nr. 7, Cod 120114, Buzău</span></h3>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--/.contact-section-->