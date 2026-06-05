@php
    $link = 'pricing';
    $title = 'Pricing';
    $description = "At Scribble, we believe in providing flexible pricing options that cater to the diverse needs of our users.
We understand that every business is unique, and our goal is to offer cost-effective solutions that align
with your budget and communication requirements. ";
@endphp

@include('home_partials/home_header')

<!-- banner section -->
<section class="banner-top position-relative pb-5">
    <div class="overlay-bg"></div>
    <div class="container pt-5">
        <div class="row align-items-center mb-5 pt-5">
            <div class="col-lg-6 pt-5">
                <h1 class="display-3 text-white fw-bolder">Pricing
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="w3l-servicesblock py-md-5 py-5">
    <div class="container">
        <div class="row">
            <h2 class="text-light-blue mb-3">Pricing that Fits Your <span class="text-orange">Business Needs</span></h2>
            <p>At Scribble, we believe in providing flexible pricing options that cater to the diverse needs of our
                users.
                We understand that every business is unique, and our goal is to offer cost-effective solutions that
                align
                with your budget and communication requirements. With Scribble, you can choose the pricing plan that
                suits you best, ensuring maximum value for your investment.</p>
        </div>
    </div>
    <div class="container-fluid">
        @include('home_partials.plans')
    </div>
    <div class="container ">
        <div class="row text-left">
            <div class="col-md-4 mb-3">
                <div class="bg-light-blue rounded-5 p-3 text-light">
                    <h3 class="text-white">Sender ID Pricing</h3>
                    <p class="text-white">In addition to our pricing plans, we offer Sender ID services to personalize
                        your SMS messages and
                        enhance brand recognition. Our Sender ID charges vary based on your preferences. Please refer to
                        our
                        Sender ID pricing sheet for detailed information on costs, including VAT, Digital Service Tax,
                        and other
                        transaction charges. Rest assured, we provide transparent pricing, ensuring no hidden costs
                        along the
                        way</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="bg-orange rounded-5 p-3 text-light">
                    <h3 class="text-white"> Contact Our Sales Team</h3>

                    <p class="text-white">
                        We understand that choosing the right pricing plan can be crucial for your business. Our
                        dedicated sales
                        team is here to assist you in making an informed decision. Feel free to reach out to us via
                        email at
                        hello@citruslabs.co.ke or give us a call at +254 20 213 1200. We are ready to address any
                        questions you
                        may have and guide you through the process.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="bg-deep-blue rounded-5 p-3 text-light">
                    <h3 class="text-white">Unlock the Power of Bulk SMS Today</h3>
                    <p class="text-white">Join thousands of businesses who have already experienced the benefits of
                        Scribble. Sign up now and
                        start leveraging the power of bulk SMS messaging to engage, inform, and connect with your
                        audience.
                        Choose a pricing plan that suits your needs and take your communication strategy to new heights
                        with
                        Scribble!</p>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ url('/register') }}"><button type="submit" class="btn button-style shadow-lg" name="sub"><i
                        class="fa fa-signing"></i>Register Now</button></a>

        </div>
    </div>
</section>


<!--footer -->
@include('home_partials/home_footer')

</body>

</html>
