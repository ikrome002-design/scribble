@php
    $title = 'About Us';
    $description = "With our cutting-edge technology and intuitive interface, we make sending personalized SMS campaigns
                    a breeze. Whether you're a small business owner, a marketer, or an enterprise, our platform is
                    designed
                    to meet your specific messaging needs";
    $link = 'about';
@endphp

@include('home_partials/home_header')

<!-- banner section -->
<section class="banner-top position-relative pb-5">
    <div class="overlay-bg"></div>
    <div class="container pt-5">
        <div class="row align-items-center mb-5 pt-5">
            <div class="col-lg-6 pt-5">
                <h1 class="display-3 text-white fw-bolder">About Us
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 my-auto">
                <img src="/assets/img/sms-marketing-office.jpg" alt="sms marketing office" class="img-fluid">
            </div>
            <div class="col-lg-6">
                <h2 class="text-light-blue fw-bolder">Who Are <span class="text-orange">We</span></h2>
                <p>Welcome to Scribble, your premier Bulk SMS solution provider!</p>
                <p> At Scribble, we understand the power of effective communication and the impact it can have on
                    businesses. We are dedicated to empowering organizations of all sizes with the ability to connect
                    and
                    engage with their audience through our reliable and user-friendly Bulk SMS platform.</p>
                <p>With our cutting-edge technology and intuitive interface, we make sending personalized SMS campaigns
                    a breeze. Whether you're a small business owner, a marketer, or an enterprise, our platform is
                    designed
                    to meet your specific messaging needs.</p>
                <p>Our team of experienced professionals is committed to delivering exceptional service and support. We
                    are passionate about helping you achieve your goals by leveraging the potential of SMS marketing.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <h2 class="text-light-blue fw-bold mb-3 ">Why choose <strong class="text-orange">Scribble</strong></h2>
        </div>
        <div class="row align-items-center">
            <div class="col-sm-6 col-lg-4 mb-2-9 mb-sm-0">
                <div class="pr-md-3">
                    <div class="text-center text-sm-right mb-4">
                        <div>
                            <i class="fa-solid fa-message display-2 text-orange"></i>
                        </div>
                        <h3 class="sub-info text-orange">Easy-to-use platform</h3>
                        <p>Our user-friendly interface allows you to create, manage, and track your
                            SMS campaigns effortlessly.</p>
                    </div>
                    <div class="text-center text-sm-right mb-4">
                        <div class="display-2 text-deep-blue">
                            <i class="fa fa-users "></i>
                        </div>
                        <h4 class="sub-info text-deep-blue">Personalized messaging</h4>
                        <p>Engage your audience with tailored SMS messages, ensuring a higher
                            response rate and customer satisfaction.</p>
                    </div>
                    <div class="text-center text-sm-right mb-4">
                        <div class="display-2 text-light-blue">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h4 class="sub-info text-light-blue">Reliable delivery</h4>
                        <p>With our robust infrastructure, you can trust that your messages will be
                            delivered promptly and reliably, reaching your recipients without delay</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="why-choose-center-image">
                    <img src="/assets/home_pic/images/sms-icon.svg" alt="sms icon">
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="pl-md-3">
                    <div class="text-center text-sm-left mb-4">
                        <div class="display-2 text-primary ">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="sub-info text-primary">Scalability</h3>
                        <p>Whether you need to send a few hundred messages or millions, our platform can
                            handle your messaging volume with ease.</p>
                    </div>

                    <div class="text-center text-sm-left">
                        <div class="display-2 text-sky-blue">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <h4 class="sub-info text-sky-blue">Competitive pricing</h4>
                        <p>We offer flexible pricing plans that fit your budget, ensuring that you get
                            the best value for your investment.</p>
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-light-blue">
        <p>Join thousands of satisfied customers who have chosen Scribble as their go-to Bulk SMS provider. Start
            unlocking the potential of SMS marketing and take your communication strategy to the next level.</p>
        <p>Get started with Scribble today and experience the power of effective messaging!</p>
        <div class="my-4 text-center">
            <a href="{{ url('/register') }}"><button type="submit" class="btn button-style" name="sub"><i
                        class="fa fa-signing"></i>Create Account Now</button></a>

        </div>
    </div>

</section>


<!--footer -->
@include('home_partials/home_footer')

</body>

</html>
