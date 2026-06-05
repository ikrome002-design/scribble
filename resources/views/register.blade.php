<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scribble - Bulk SMS Application - Home : Scribble.ke</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="assets/home_pic/css/form.css" />
    <link href="//fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />
    <!-- //google-fonts -->
    <!-- //fonts-awesome -->

    <!-- //fonts-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href='/assets/home_pic/css/style-starter.css' />
    <link rel="stylesheet" href='/assets/home_pic/css/intlTelInput.css' />
    <link rel="stylesheet" href='/assets/home_pic/css/columns.css' />
    <link rel="stylesheet" href='/assets/form_steps/step.css' />
    <!-- google-fonts -->
    <link href="//fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />
    <!-- //google-fonts -->

    <!-- //fonts-awesome -->
    <link rel="stylesheet" href='/assets/home_pic/css/all.css' />
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
    <style>
        @media screen and (max-width: 768px) {
            .steps {
                display: none !important;
            }
        }

        .listy-style li {
            list-style-type: decimal !important;
        }
    </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <!--header-->
    @include('home_partials/home_header')
    <!--//header-->
    <div class="mt-5 pt-5 container">
        <div>
            @include('notification.notifybs5')
        </div>
    </div>
    <!-- section -->
    @yield('body')

    <!-- footer -->
    @include('home_partials/home_footer')
    <!-- //footer -->
    <!--modal-contact -->
    <div class="modal fade" id="myModal_contact">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        Contact Us
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <label>
                                <h5>Name</h5>
                            </label>
                            <input type="text" class="form-control col-md-7" name="name" placeholder="Your name"
                                style="" required />
                        </div>
                        <div class="form group">
                            <label>
                                <h5>Phone</h5>
                            </label><br />
                            <input id="phone2" class="form-control" name="phone" type="tel" required />
                        </div>
                        <div class="form-group">
                            <label>
                                <h5>Email</h5>
                            </label><br />
                            <input type="email" class="form-control col-md-7" name="email"
                                placeholder="example@...." style="" required />
                        </div>
                        <div class="form-group">
                            <label>
                                <h5>Message</h5>
                            </label><br />
                            <textarea class="form-control" name="comments" placeholder="Message" rows="5"></textarea>
                        </div>
                        <center>
                            <button type="submit" name="comment" class="btn button-style btn-lg">
                                Submit
                            </button>
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
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" class="text-success" style="color: #4bb543">
                        Success
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h5>Successfully Sent Your details</h5>
                </div>
                <!--modal-body-->
            </div>
        </div>
    </div>
    <!--modal-->

    <!--modal -->
    <div class="modal fade" id="myModal_error">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        Error
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h5>Something went wrong please try Again</h5>
                </div>
            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal -->
    <!--modal-error -->
    <div class="modal fade" id="myModal_phone">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        Error
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h5>Phone number already exists please try another</h5>
                </div>
            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal-error -->
    <!--modal-error -->
    <div class="modal fade" id="myModal_mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        Error
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h5>Email already exists please try another</h5>
                </div>
            </div>
        </div>
    </div>
    <!--modal-error-->
    <!--modal-privacy -->
    <div class="modal fade" id="myModal_privacy">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />Privacy Statement
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Scribble.ke Privacy Statement.pdf&embedded=true"
                        style="width: 500px; height: 500px" frameborder="0">Privacy Statment</iframe>
                </div>
            </div>
        </div>
    </div>
    <!--modal-privacy-->
    <!--modal-cookie -->
    <div class="modal fade" id="myModal_cookie">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />Cookie Policy
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Cookie Policy - Scribble.pdf&embedded=true"
                        style="width: 500px; height: 500px" frameborder="0">Cookie Policy</iframe>
                </div>
            </div>
        </div>
    </div>
    <!--modal-cookie-->
    <!--modal-terms -->
    <div class="modal fade" id="myModal_terms">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #6f42c1">
                        <img class="img-fluid" src="assets/images/logo.jpg" alt=" " />Terms & Conditons
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <iframe
                        src="https://docs.google.com/gview?url=https://api.ementoringafrica.or.ke/scribble/docs//Terms of Procurement - Scribble.ke.pdf&embedded=true"
                        style="width: 500px; height: 500px" frameborder="0">Terms & condition</iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    </script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="/assets/home_pic/js/main.js"></script>
    <script src="/assets/form_steps/jquery.steps.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    @yield('scripts')



    <button onclick="topFunction()" id="movetop" title="Go to top">
        <span class="fa fa-level-up-alt" aria-hidden="true"></span>
    </button>
    <script>
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (
                document.body.scrollTop > 20 ||
                document.documentElement.scrollTop > 20
            ) {
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
    <!-- //move top -->

    <!-- common jquery plugin -->
    <!-- <script src="assets/js/jquery-3.3.1.min.js"></script> -->
    <!-- //common jquery plugin -->

    <!-- theme switch js (light and dark)-->
    <script src="/assets/js/theme-change.js"></script>
    <!-- <script>
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
    </script> -->
    <!-- //theme switch js (light and dark)-->

    <!-- magnific popup -->
    <!-- <script src="assets/js/jquery.magnific-popup.min.js"></script> -->
    <!-- <script>
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
    </script> -->
    <!-- //magnific popup -->

    <!-- MENU-JS -->
    <!-- <script>
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
    </script> -->
    <!-- //MENU-JS -->

    <!-- for testimonials carousel -->
    <!-- <script src="assets/js/owl.carousel.js"></script>
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
    </script> -->
    <!-- //for testimonials carousel -->

    <!-- disable body scroll which navbar is in active -->
    <script>
        $(function() {
            $(".navbar-toggler").click(function() {
                $("body").toggleClass("noscroll");
            });
        });
    </script>
    <!-- //disable body scroll which navbar is in active -->



    <!-- <script>
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
    </script> -->
    <!-- <script>
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
    </script> -->
</body>

</html>
