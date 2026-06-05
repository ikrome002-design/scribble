@php
    $title = 'Solutions';
    $description = "With our cutting-edge technology and intuitive interface, we make sending personalized SMS campaigns
                    a breeze. Whether you're a small business owner, a marketer, or an enterprise, our platform is
                    designed
                    to meet your specific messaging needs";
    $link = 'solutions';
@endphp

@include('home_partials/home_header')

<!-- banner section -->
<section class="banner-top position-relative pb-5">
    <div class="overlay-bg"></div>
    <div class="container pt-5">
        <div class="row align-items-center mb-5 pt-5">
            <div class="col-lg-6 pt-5">
                <h1 class="display-3 text-white fw-bolder">Solutions
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="w3l-servicesblock py-md-5 py-5" id="solutions">
    <div class="container">
        <div class="row">
            <div
                class="col-lg-5 image-cover-no-repeat mh-50vh"style="background-image:url(/assets/img/sms-marketing.jpg)">

            </div>
            <div class="col-lg-7  pl-lg-5 py-5 ">
                <h2 class="text-light-blue fw-bolder">Our Solutions</h2>
                <p> Welcome to Scribble - Your Ultimate Bulk SMS Solution!</p>
                <p>At Scribble, we understand the power of effective communication in driving business success. That's
                    why
                    we have developed a comprehensive suite of solutions designed to meet your bulk SMS needs. Whether
                    you're a small business, a non-profit organization, or a large enterprise, Scribble has the tools
                    and
                    features to help you connect with your audience and achieve your communication goals.</p>
                <div class="bg-light-blue rounded-5 p-3 text-light">
                    <ol class="list-style-lower-decimal">
                        <li><b>Bulk SMS Messaging:</b>
                            With our user-friendly platform, you can effortlessly send personalized SMS messages to
                            a large
                            audience with just a few clicks. Reach your customers, clients, or members instantly and
                            engage
                            them with tailored messages that resonate.</li>
                        <li><b>Real-Time Reporting and Analytics:</b>
                            Monitor the performance of your SMS campaigns in real-time. Track delivery rates, open
                            rates,
                            click-through rates, and other key metrics to gain valuable insights into the
                            effectiveness of your
                            messaging strategies. Make data-driven decisions and optimize your campaigns for maximum
                            impact.</li>
                        <li><b>Transactional SMS:</b>
                            Send automated SMS notifications, alerts, and transactional messages to your customers.
                            Keep
                            them informed about order confirmations, payment receipts, delivery updates, and more.
                            Enhance customer satisfaction and streamline your communication processes with ease.
                        </li>
                        <li><b>SMS Surveys and Feedback:</b>
                            Gather valuable feedback from your audience using SMS surveys. Create customized
                            surveys,
                            collect responses, and gain actionable insights to improve your products, services, or
                            overall
                            customer experience. Build stronger relationships by showing your customers that their
                            opinions
                            matter.</li>
                        <li><b>Two-Way SMS Communication:</b>
                            Enable seamless two-way communication with your audience. Allow recipients to respond to
                            your SMS messages, creating an interactive and engaging experience. Enhance customer
                            support, conduct polls, and facilitate instant communication with ease.
                        <li><b>Scribble PRO - Advanced Functionality:</b>
                            Take your bulk SMS campaigns to the next level with Scribble PRO. This premium feature
                            allows
                            you to track all transactions made through your M-Pesa Paybill accounts in real-time.
                            Gain
                            valuable data to monitor sales performance, track individual staff members' performance,
                            andimprove the payment verification process. Unlock the power of data-driven insights
                            and drive
                            better business outcomes.</li>
                        <li><b>API Integration:</b>
                            Integrate Scribble's powerful SMS features into your existing applications, websites, or
                            systems.
                            Seamlessly connect with our API, enabling automated messaging and enhancing your overall
                            communication workflow.</li>
                    </ol>
                </div>
                <p>At Scribble, we are committed to providing you with a reliable, scalable, and cost-effective bulk SMS
                    solution. Our intuitive platform, advanced features, including Scribble PRO, and exceptional
                    customer
                    support make us the trusted choice for businesses of all sizes.</p>
                <p> Take control of your communication strategy and unlock the true potential of SMS marketing with
                    Scribble. Sign up today and experience the power of effective messaging at your fingertips!
                </p>
                <p> Ready to get started? Create an account now and revolutionize your communication efforts with
                    Scribble!</p>
                <div class="mt-4">
                    <a href="{{ url('/register') }}"><button type="submit" class="btn button-style shadow-lg"
                            name="sub"><i class="fa fa-signing"></i>Register Now</button></a>

                </div>
            </div>
        </div>
    </div>
</section>


<!--footer -->
@include('home_partials/home_footer')

</body>

</html>
