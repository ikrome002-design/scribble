@php
    $title = 'FAQs';
    $description = "How long is billing period ?', 'One billing period goes for 30 days.";
@endphp
@include('home_partials.home_header')
<style>
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    li {
        margin-bottom: 0.5rem;
        margin-top: 0.5rem;
    }

    .terms li {
        list-style-type: disc;
    }

    .terms .list-style-none {
        list-style-type: none;
    }


    ol.list-style-lower-alpha li {
        list-style-type: lower-alpha !important;
    }

    .terms ul,
    .terms ol {
        padding-left: 2rem
    }

    .accordion {
        transition: all 1s ease-out;
    }

    .accordion-item {
        border: none;
        border-bottom: 1px solid #fb9c2a;

    }

    .accordion-header {
        color: #2b3d8e;
    }

    .accordion-button:focus {
        outline: none;
        box-shadow: none;
    }

    .accordion-button:not(.collapsed) {

        background-color: #2b3d8e;
        color: white;
        border: none;
    }

    .accordion-body {
        background-color: rgb(43, 61, 142, 0.1);
    }
</style>


@php
    $faqs = [
        [
            'What is Scribble?',
            "Scribble is a Bulk SMS platform that allows businesses to send personalized SMS messages to their
audience. It enables you to connect and engage with customers, clients, or members through effective
communication.",
        ],
        [
            'Can my device access Scribble?',
            "Yes, Scribble is accessible from various devices. You can access the Scribble platform from your desktop
computer, laptop, smartphone, or tablet. Simply visit our website at www.scribble.ke and sign in to your
account to start utilizing Scribble's features and sending SMS messages.",
        ],
        [
            'How does Scribble work?',
            "Scribble provides an intuitive and user-friendly platform where you can compose and send SMS messages
to a large audience. Simply create your message, select your recipients, and hit send. Scribble takes care
of the rest, ensuring reliable and timely delivery of your messages.",
        ],
        [
            'What can I use Scribble for?',
            "Scribble is versatile and can be used for various purposes, including promotional campaigns, customer
notifications, transactional messages, surveys, and two-way communication with your audience.",
        ],
        [
            'How much does Scribble cost?',
            "Scribble offers flexible pricing plans to accommodate different needs. We have Pay As You Go options
where you purchase SMS credits as needed, as well as Standard and Premium plans with monthly
subscriptions. Refer to our pricing section for detailed information.",
        ],
        [
            'What is Scribble PRO?',
            "Scribble PRO is an advanced feature available with our Premium Plan. It allows you to track transactions
made through your M-Pesa Paybill accounts in real-time, providing valuable insights to monitor sales
performance, track individual staff members' performance, and improve the payment verification
process.",
        ],
        [
            'How can I track the performance of my SMS campaigns?',
            "Scribble provides real-time reporting and analytics, allowing you to monitor the delivery rates, open
rates, click-through rates, and other key metrics of your SMS campaigns. This data helps you evaluate the
effectiveness of your messaging strategies and make informed decisions.8. Can I integrate Scribble with my existing systems?
Yes, Scribble offers API integration, enabling you to seamlessly integrate our powerful SMS features into
your existing applications, websites, or systems. This allows for automated messaging and streamlines
your communication workflow.",
        ],
        [
            'How secure is Scribble?',
            "At Scribble, we prioritize the security and privacy of your data. We employ industry-standard security
measures to protect your information and ensure secure transmission of messages.",
        ],
        [
            'Is there a limit to the number of recipients I can send messages to?',
            "Scribble supports both small and large-scale messaging needs. There are no limits on the number of
recipients you can send messages to, making it suitable for businesses of all sizes.",
        ],
        [
            'Can I personalize my SMS messages?',
            "Absolutely! Scribble allows you to personalize your SMS messages by including recipient names or other
relevant information, making your communications more engaging and tailored to each recipient.",
        ],
        [
            'What support is available if I have questions or issues?',
            "Our dedicated support team is available to assist you with any questions or issues you may have. You can
reach us through email, phone, or our customer support portal. We are committed to providing you with
timely and reliable support.",
        ],
        [
            'How do I get started with Scribble?',
            "Getting started with Scribble is easy. Simply sign up for an account, choose the pricing plan that suits your
needs, and you'll be ready to start sending SMS messages and connecting with your audience.
If you have any additional questions, please don't hesitate to reach out to our sales or support team.We're here to help!",
        ],
    ];
@endphp
<section class="terms">
    <div class="container py-5">
        <div class="row pt-5 mt-5">
            <div class="col-md-8 m-auto">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach ($faqs as $k => $f)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading{{ $k }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse{{ $k }}" aria-expanded="false"
                                    aria-controls="flush-collapse{{ $k }}">
                                    {{ $f[0] }}
                                </button>
                            </h2>
                            <div id="flush-collapse{{ $k }}" class="accordion-collapse collapse"
                                aria-labelledby="flush-heading{{ $k }}"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {{ $f[1] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

</section>



<!--footer -->
@include('home_partials/home_footer')


</body>

</html>
