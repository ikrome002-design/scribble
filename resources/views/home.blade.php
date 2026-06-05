<!--
Author: Rufus
Author mail: rufusngash@gmail.com
-->
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Scribble - Bulk SMS Application - Home : Scribble.ke</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="On Scriible.ke take your global SMS messaging to the next level with an industry-leading platform. Reach customers faster at scale with our intelligent routing. 7 Billion Devices Reached. 24/7 Support." />
    <meta property="og:image" content="assets/images/sms-1.jpg" />
    <!-- google-fonts -->
    <link href="//fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- //google-fonts -->
    <!-- //fonts-awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- //fonts-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!----- //Favicon ----->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">



    <!-- Template CSS Style link -->
    <link rel="stylesheet" href='/assets/home_pic/css/style-starter.css' />
    <link rel="stylesheet" href='/assets/home_pic/css/all.css' />
    <link rel="stylesheet" href='/assets/home_pic/css/intlTelInput.css' />
    <link rel="stylesheet" href='/assets/home_pic/css/columns.css' />

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

    <!-- header include -->
    @include('home_partials/home_header')

    <!-- banner section -->
    <section id="home" class="w3l-banner py-5">

        <div class="container pt-5 pb-md-4">
            <div class="row align-items-center">
                <div class="col-md-6 banner-left pt-md-0 pt-5">
                    <h3 class="mb-sm-4 mb-3 title" style="margin-left: 4%;">Connect with<br> your Customers <span
                            class="type-js"><span class="text-js">SMS</span></span></h3>
                    <div class="mt-md-5 mt-4 mb-lg-0 mb-4">
                        <a href="{{ url('/register') }}"><button type="submit" class="btn button-style"
                                name="sub"><i class="fa fa-signing"></i>Get Started</button></a>

                    </div>
                </div>
                <div class="col-md-6 banner-right mt-md-0 mt-4">
                    <img class="img-fluid" src="assets/home_pic/images/b1.png" alt=" ">
                </div>
            </div>
        </div>
    </section>
    <!-- Calendly inline widget begin -->
    <div class="calendly-inline-widget" data-url="https://calendly.com/francisobiri"
        style="min-width:320px;height:630px;"></div>
    <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
    <!-- Calendly inline widget end -->
    <!-- //banner section -->
    <!-- banner bottom section -->
    <div class="w3l-index-block4 pb-5 jumbotron">
        <div class="features-bg pb-lg-5 pt-lg-4 py-4">
            <div class="container">
                <!--div class="title-main text-center mx-auto mb-md-4">
                    <h3 class="title-big">Our Special Courses</h3>
                    <p class="sub-title mt-2">Cum doctus civibus efficiantur in imperdiet deterruisset. Cras efficitur,
                        metus
                        gravida suscipit cursus, dui diam pre lorem id
                        lectus.</p>
                </div-->
                <div class="row">
                    <div class="col-lg-4 col-md-6 features15-col-text">
                        <a href="#" class="d-flex feature-unit align-items-center">
                            <div class="col-4">
                                <div class="features15-info">
                                    <img class="img-fluid" src="assets/home_pic/images/clock.jpg" alt=" ">

                                </div>
                            </div>
                            <div class="col-8">
                                <div class="features15-para">
                                    <!--h6>$100</h6-->
                                    <h4>Worldwide delivery under 10seconds</h4>
                                    <p>Our pro-active monitoring guarantees fast delivery of high volume, time-critical
                                        messages delivered reliably on time</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 features15-col-text">
                        <a href="courses.html" class="d-flex feature-unit align-items-center active">
                            <div class="col-4">
                                <div class="features15-info">
                                    <img class="img-fluid" src="assets/home_pic/images/stats1.png" alt=" ">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="features15-para">
                                    <!--Free</h6-->
                                    <h4>Direct Mobile Operator Connections</h4>
                                    <p>Benefit from 1093+ direct carrier conneections and 2499 operator networks
                                        worldwide to deliver your SMS</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 features15-col-text">
                        <a href="courses.html" class="d-flex feature-unit align-items-center">
                            <div class="col-4">
                                <div class="features15-info">
                                    <img class="img-fluid" src="assets/home_pic/images/c2.png" alt=" ">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="features15-para">
                                    <!--h6>$100</h6-->
                                    <h4>Secure and reliable, 24/7/365 Support</h4>
                                    <p>With high uptime and a redundant setup, you can rely on our source and ISO 27001
                                        certified platform with 24/7/365 support from our Network Operating Center</p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                <!--row-->
            </div>
        </div>
    </div>
    <!-- //banner bottom section -->
    <!-- middle section -->
    <section class="w3l-servicesblock py-md-5 py-4" id="about">
        <div class="container pb-2">
            <div class="row align-items-center">
                <div class="col-lg-6 left-wthree-img pr-lg-4">
                    <img src="assets/home_pic/images/content-image-1.png" alt="" class="img-fluid">
                </div>
                <div class="col-lg-6 about-right-faq align-self mb-lg-0 mb-5 pl-xl-5">
                    <h3>About Us</h3>
                    <!--h3 class="title-big mb-3">Use your account from anywhere in the world and communicate with customers all over the world. </h3-->
                    <h4 style='color: #6f42c1;'>Use your account from anywhere in the world and communicate with
                        customers all over the world.</h4>
                    <p>Detailed overview, and clear insight into your worldwide traffic, allowing you to trace, and
                        optimize flow. </p>

                    <div class="row mt-lg-5 mt-4 mb-2">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center left-insp-art">
                                <img src="assets/images/book.png" alt="" class="img-fluid mr-3">
                                <h6>Enhance your Skills</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-sm-0 mt-4">
                            <div class="d-flex align-items-center left-insp-art">
                                <img src="assets/images/book2.png" alt="" class="img-fluid mr-3">
                                <h6>Start Online Learning</h6>
                            </div>
                        </div>
                    </div>
                    <!--read more-->

                </div>
            </div>
        </div>
    </section>
    <!-- //middle section -->
    <!-- //solutions section -->
    <section class="w3l-servicesblock py-md-5 py-4 well" style="background-color:#f5f5f5;" id="solutions">
        <div class="container pb-2">
            <div class="row align-items-center">
                <div class="col-lg-4 left-wthree-img pr-lg-4">
                    <img src="assets/home_pic/images/solutions-1.jpg" alt="" class="img-fluid img-circle">
                </div>
                <div class="col-lg-8 about-right-faq align-self mb-lg-0 mb-5 pl-xl-5">
                    <h3>Solutions</h3>
                    <!--h3 class="title-big mb-3">Use your account from anywhere in the world and communicate with customers all over the world. </h3-->
                    <h4 style='color: #6f42c1;'></h4>


                    <div class="row mt-lg-5 mt-4 mb-2">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center left-insp-art">
                                <img src="assets/images/book.png" alt="" class="img-fluid">
                                <h6>Customer Support</h6>
                            </div>
                            <h5>Efficient, Customer Centric Customer Service</h5>
                            <p>Integrated Customer Service software makes service easier for customers and employees</p>
                            <img src="assets/home_pic/images/customer-support-1.jpg" class="img-fluid"
                                style="height: 300px; width: 300px;">
                        </div>
                        <div class="col-sm-6 mt-sm-0 mt-4">
                            <div class="d-flex align-items-center left-insp-art">
                                <img src="assets/home_pic/images/book2.png" alt="" class="img-fluid">
                                <h6>Marketing & Sales</h6>
                            </div>
                            <h5>Improved Marketing and Sales by:</h5>
                            <div class="col-md-4">
                                <img src="assets/home_pic/images/Marketing -Sales-1-modified.jpeg"
                                    style="height:300px; width: 200px;">
                            </div>

                        </div>
                        <p>Use Scribble To Get Ready For Hyper-Personalisation In Customer Service</p>
                    </div <!--read more-->

                </div>
            </div>
        </div>
    </section>
    <!-- //solutions section -->
    <!-- testimonials -->
    <section class="w3l-companies-hny-6 position-relative">
        <div class="cusrtomer-layout py-5" style="background-color:#6f42c1;">
            <div class="container py-md-4 py-3">
                <div class="title-heading-w3 text-center mx-auto">
                    <h3 class="title-big" style="color: #fff;">How much data are you missing?</h3>
                </div>
                <div id="owl-demo1" class="owl-carousel owl-theme mt-5">
                    <div class="item">
                        <div class="testimonial-content">
                            <div class="testimonial">
                                <div class="testi-des">
                                    <div class="test-img"><img src="assets/home_pic/images/Capture-modified.png"
                                            class="img-fluid" alt="/">
                                    </div>
                                </div>

                                <p style="color: #fff;">According to IDC's State of Data Science and analytic report,
                                    data is becoming increasingly critical to the success in the digital economy. This
                                    is particularly true for the retail & ecommerce industry, where attribution data is
                                    one of the most vital components of maximising customer journeys. This data allows
                                    businesses to attribute revenue generated to paid marketing efforts by opening data
                                    driving insights across various customer touchpoints, including paid search,
                                    display, email, social media, organic search, and referrals. <br> Tom Faas</p>



                            </div>
                        </div>
                    </div>


                </div>
                <!--owl-->
            </div>
        </div>
    </section>
    <!--//testimonials-->
    <!-- teams 32 block -->
    <section class="w3l-teams-32-main py-5" id="pricing">
        <div class="teams-32 py-md-4">
            <div class="container">
                <div class="text-center">
                    <div class="container">
                        <div class="row pt-4">
                            <div class="col-md-8 m-auto">
                                <h2 style='color: #6f42c1;'>Scribble Pricing</h2>
                                <div class="container table-responsive py-5">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">PAY AS YOU GO</th>
                                                <th scope="col">PREMIUM</th>
                                                <th scope="col">STANDARD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="jumbotron">
                                                <th scope="row"></th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td><strong>Best Plan</strong></td>
                                                <td></td>
                                            </tr>
                                            <tr class="jumbotron">
                                                <th scope="row"></th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Free No Monthly Subscription</th>
                                                <td>Monthly Subscription at KES 8,000
                                                    <hr>+ Added Onboarding Charge
                                                </td>
                                                <td>Monthly Subscription at KES 4,000/=</td>
                                            </tr>
                                            <tr class="jumbotron">
                                                <th scope="row"></th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    Suitable for:
                                                    <ul>
                                                        <li>
                                                            <p>One-Time users</p>
                                                        </li>
                                                        <li>
                                                            <p>An Individual</p>
                                                        </li>
                                                        <li>
                                                            <p>Sole Proprietor</p>
                                                        </li>
                                                    </ul>
                                                </th>
                                                <td>Best for business that initiate market champaigns</td>
                                                <td>Save unlimited contact numbers</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Real-time SMS</th>
                                                <td>Link to MPESA Till number functionality
                                                    <hr>Branded SMS
                                                    <hr><strong>Thank you for coming to my business</strong> SMS to
                                                    customer
                                                </td>
                                                <td><strong>Add Contacts To Group's</strong> feature
                                                    <hr>Send later feature
                                                    <hr>Customer Service Support
                                                </td>
                                            </tr>
                                            <tr class="jumbotron">
                                                <th scope="row"></th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--container-->
                            </div>
                            <!--col-md-8 card-->
                        </div>
                        <!--row pt4-->
                    </div>
                    <!--container-->
                </div>
                <!--text-center-->
            </div>
            <!--container-->
        </div>
        <!--temas32-->
    </section>
    <!-- //teams 32 block -->

    <!--footer -->
    @include('home_partials/home_footer')



    <!--modal-contact -->
    <div class='modal fade' id='myModal_contact'>
        <div class='modal-dialog'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1">Contact Us <img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" "></h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <label>
                                <h5>Name</h5>
                            </label>
                            <input type="text" class="form-control col-md-7" name="name"
                                placeholder="Your name" style='' required>
                        </div>
                        <div class="form group">
                            <label>
                                <h5>Phone</h5>
                            </label><br>
                            <input id="phone2" class="form-control" name="phone" type="tel" required>
                        </div>
                        <div class="form-group">
                            <label>
                                <h5>Email</h5>
                            </label><br>
                            <input type="email" class="form-control col-md-7" name="email"
                                placeholder="example@...." style='' required>
                        </div>
                        <div class="form-group">
                            <label>
                                <h5>Message</h5>
                            </label><br>
                            <textarea class="form-control" name="comments" placeholder="Message" rows="5"></textarea>
                        </div>
                        <center>
                            <button type="submit" name="comment" class="btn button-style btn-lg">Submit</button>
                        </center>
                    </form>
                </div>
                <!--modal-body-->

            </div>
        </div>
    </div>
    <!--modal-contact-->
    <!--modal-contact -->
    <!-- The Modal -->
    <div class='modal fade' id='myModal'>
        <div class='modal-dialog'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' class="text-success" style="color: #4BB543 ">Success <img
                            class="img-fluid" src="assets/images/logo.jpg" alt=" "></h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <h5>Successfully Sent Your details</h5>
                </div>
                <!--modal-body-->

            </div>
        </div>
    </div>
    <!--modal-->

    <!--modal -->
    <div class='modal fade' id='myModal_error'>
        <div class='modal-dialog'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1">Error <img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" "></h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <h5>Something went wrong please try Again</h5>

                </div>

            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal -->
    <!--modal-error -->
    <div class='modal fade' id='myModal_phone'>
        <div class='modal-dialog'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1">Error <img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" "></h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <h5>Phone number already exists please try another</h5>

                </div>

            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal-error -->
    <!--modal-error -->
    <div class='modal fade' id='myModal_mail'>
        <div class='modal-dialog'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1">Error <img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" "></h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <h5>Email already exists please try another</h5>

                </div>

            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal-privacy -->
    <div class='modal fade' id='myModal_privacy'>
        <div class='modal-dialog  modal-lg'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1"><img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" ">Privacy Statement </h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Scribble.ke Privacy Statement.pdf&embedded=true"
                        style="width:500px; height:500px;" frameborder="0">Privacy Statment</iframe>

                </div>

            </div>
        </div>
    </div>
    <!--modal-privacy-->
    <!--modal-cookie -->
    <div class='modal fade' id='myModal_cookie'>
        <div class='modal-dialog  modal-lg'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1"><img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" ">Cookie Policy </h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Cookie Policy - Scribble.pdf&embedded=true"
                        style="width:500px; height:500px;" frameborder="0">Cookie Policy</iframe>

                </div>

            </div>
        </div>
    </div>
    <!--modal-cookie-->
    <!--modal-terms -->
    <div class='modal fade' id='myModal_terms'>
        <div class='modal-dialog  modal-lg'>
            <div class='modal-content'>

                <!-- Modal Header -->
                <div class='modal-header'>
                    <h4 class='modal-title' style="color: #6f42c1"><img class="img-fluid"
                            src="assets/images/logo.jpg" alt=" ">Terms & Conditons </h4>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                </div>

                <!-- Modal body -->
                <div class='modal-body'>
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Terms of Procurement - Scribble.ke.pdf&embedded=true"
                        style="width:500px; height:500px;" frameborder="0">Terms & condition</iframe>

                </div>

            </div>
        </div>
    </div>
    <!--modal-cookie-->

    <!-- Js scripts -->
    <!-- move top -->
    <button onclick="topFunction()" id="movetop" title="Go to top">
        <span class="fa fa-level-up-alt" aria-hidden="true"></span>
    </button>
    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="/assets/home_pic/js/jquery-3.3.1.min.js"></script>
    <script src="/assets/home_pic/js/theme-change.js"></script>
    <script src="/assets/home_pic/js/jquery.magnific-popup.min.js"></script>
    <script src="/assets/home_pic/js/owl.carousel.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("movetop").style.display = "block";
            } else {
                document.getElementById("movetop").style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>

    <script>
        function autoType(elementClass, typingSpeed) {
            var thhis = $(elementClass);
            thhis.css({
                "position": "relative",
                "display": "inline-block"
            });
            thhis.prepend('<div class="cursor" style="right: initial; left:0;"></div>');
            thhis = thhis.find(".text-js");
            var text = thhis.text().trim().split('');
            var amntOfChars = text.length;
            var newString = "";
            thhis.text("|");
            setTimeout(function() {
                thhis.css("opacity", 1);
                thhis.prev().removeAttr("style");
                thhis.text("");
                for (var i = 0; i < amntOfChars; i++) {
                    (function(i, char) {
                        setTimeout(function() {
                            newString += char;
                            thhis.text(newString);
                        }, i * typingSpeed);
                    })(i + 1, text[i]);
                }
            }, 1500);
        }

        $(document).ready(function() {
            // Now to start autoTyping just call the autoType function with the 
            // class of outer div
            // The second paramter is the speed between each letter is typed.   
            autoType(".type-js", 200);
        });
    </script>
    <!-- //theme switch js (light and dark)-->

    <!-- magnific popup -->

    <script>
        $(document).ready(function() {
            $('.popup-with-zoom-anim').magnificPopup({
                type: 'inline',

                fixedContentPos: false,
                fixedBgPos: true,

                overflowY: 'auto',

                closeBtnInside: true,
                preloader: false,

                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in'
            });

            $('.popup-with-move-anim').magnificPopup({
                type: 'inline',

                fixedContentPos: false,
                fixedBgPos: true,

                overflowY: 'auto',

                closeBtnInside: true,
                preloader: false,

                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-slide-bottom'
            });
        });
    </script>
    <!-- //magnific popup -->

    <!-- MENU-JS -->
    <script>
        $(window).on("scroll", function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 80) {
                $("#site-header").addClass("nav-fixed");
            } else {
                $("#site-header").removeClass("nav-fixed");
            }
        });

        //Main navigation Active Class Add Remove
        $(".navbar-toggler").on("click", function() {
            $("header").toggleClass("active");
        });
        $(document).on("ready", function() {
            if ($(window).width() > 991) {
                $("header").removeClass("active");
            }
            $(window).on("resize", function() {
                if ($(window).width() > 991) {
                    $("header").removeClass("active");
                }
            });
        });
    </script>
    <!-- //MENU-JS -->


    <script>
        $(document).ready(function() {
            $("#owl-demo1").owlCarousel({
                loop: true,
                margin: 20,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 1,
                        nav: false
                    },
                    1000: {
                        items: 1,
                        nav: true,
                        loop: true
                    }
                }
            })
        })
    </script>
    <!-- //for testimonials carousel -->

    <!-- disable body scroll which navbar is in active -->
    <script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            })
        });
    </script>
    <!-- //disable body scroll which navbar is in active -->


    <!-- //bootstrap-->
    <!-- //Js scripts -->
    {{-- <script src="build/js/intlTelInput.js"></script>
    <script>
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            // hiddenInput: "full_number",
            // initialCountry: "auto",
            // localizedCountries: { 'de': 'Deutschland' },
            // nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            // separateDialCode: true,
            utilsScript: "build/js/utils.js",
        });
    </script> --}}
    {{-- <script>
        var input = document.querySelector("#phone2");
        window.intlTelInput(input, {
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            // hiddenInput: "full_number",
            // initialCountry: "auto",
            // localizedCountries: { 'de': 'Deutschland' },
            // nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            // separateDialCode: true,
            utilsScript: "build/js/utils.js",
        });
    </script> --}}
</body>

</html>
