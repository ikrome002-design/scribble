@php
    $title = 'Contact Us';
    $description = "We understand that choosing the right pricing plan can be crucial for your business. Our
                        dedicated sales
                        team is here to assist you in making an informed decision.";
    $link = 'contact';
@endphp

@include('home_partials/home_header')

<!-- banner section -->
<section class="banner-top position-relative pb-5">
    <div class="overlay-bg"></div>
    <div class="container pt-5">
        <div class="row align-items-center mb-5 pt-5">
            <div class="col-lg-6 pt-5">
                <h1 class="display-3 text-white fw-bolder">Contact Us
                </h1>
            </div>
        </div>
    </div>
</section>
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="wrapper px-md-4">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <div class="w-100 text-center">
                                <div
                                    class="bg-light-blue fa-xl text-white mb-3 icon-rounded d-flex align-items-center justify-content-center">

                                    <span class="fa fa-map-marker"></span>

                                </div>
                                <p>Parliament Road</p>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="w-100 text-center">
                                <div
                                    class="bg-light-blue fa-xl text-white mb-3 icon-rounded d-flex align-items-center justify-content-center">
                                    <span class="fa fa-phone"></span>
                                </div>
                                <div class="text">
                                    <p><a href="tel:+254712400000">+254 (071) 2400 000</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="w-100 text-center">
                                <div
                                    class="bg-light-blue fa-xl text-white mb-3 icon-rounded d-flex align-items-center justify-content-center">
                                    <span class="fa fa-paper-plane"></span>
                                </div>
                                <div class="text">
                                    <p> <a href="mailto:{{ env('SUPPORT_EMAIL') }}">{{ env('SUPPORT_EMAIL') }}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="w-100 text-center">
                                <div
                                    class="bg-light-blue fa-xl text-white mb-3 icon-rounded d-flex align-items-center justify-content-center">
                                    <span class="fa fa-globe"></span>
                                </div>
                                <div class="text">
                                    <p><a href="//scribble.ke">scribble.ke</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-lg-7 bg-white">
                            <div class="contact-wrap w-100 p-md-5 p-4">
                                <h3 class="mb-4">Message Us</h3>
                                <form id="contact-form">
                                    <div class="row">
                                        <div class="form-group mb-3 col-md-6">
                                            <label>
                                                <h5> Full Name</h5>
                                            </label>
                                            <input type="text" class="form-control " name="name"
                                                placeholder="Your name" required>
                                        </div>
                                        <div class="form group mb-3 col-md-6">
                                            <label>
                                                <h5>Phone</h5>
                                            </label><br>
                                            <input class="form-control" name="phone" type="tel"
                                                placeholder="You  phone number" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>
                                                <h5>Email</h5>
                                            </label><br>
                                            <input type="email" class="form-control" name="email"
                                                placeholder="example@...." s' required>
                                        </div>
                                        <div class="form-group mb-3 ">
                                            <label>
                                                <h5>Message</h5>
                                            </label><br>
                                            <textarea class="form-control" name="message" placeholder="Message" rows="5"></textarea>
                                        </div>
                                        <div class="">
                                            <button type="button" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"
                                                data-action='submit' data-callback='submitContactForm'
                                                class="btn submit-form-btn button-style btn-lg g-recaptcha">Submit</button>
                                        </div>
                                    </div>
                                </form>
                                <div role="alert" id="form-alert" class="alert position-fixed  bottom-0 end-0 "
                                    style="z-index: 11">

                                    <div class="alert-danger  form-error  py-2 px-3" role="alert">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-hide="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        <div id="form-error">

                                        </div>
                                    </div>
                                    <div class="alert-success  form-success py-2 px-3" role="alert">
                                        <div class="text-end">
                                            <button type="button" class="btn-close" data-bs-hide="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        <div id="form-success">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 order-md-first d-flex align-items-stretch">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.80823030326!2d36.820591199999996!3d-1.289289!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d99d15e713%3A0x4d288644422a55eb!2sParliament%20Rd%2C%20Nairobi!5e0!3m2!1sen!2ske!4v1685356403350!5m2!1sen!2ske"
                                width="600" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!--footer -->
@include('home_partials/home_footer')

</body>

</html>
