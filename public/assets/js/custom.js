$(document).ready(function () {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   //toggle nav button on small devices
   $('nav .navbar-toggler').click(() => {
      $('nav .navbar-toggler').toggleClass('change')
   });
   // Timer Code Started
   //draw date
   function makeTimer() {
      $('#draw-date-timer').removeClass('d-none');
      var endTime = $('#draw-date').val();
      var now = new Date();
      now = Date.parse(now) / 1000;
      var timeLeft = endTime - now;

      var days = Math.floor(timeLeft / 86400);
      var hours = Math.floor((timeLeft - days * 86400) / 3600);
      var minutes = Math.floor((timeLeft - days * 86400 - hours * 3600) / 60);
      var seconds = Math.floor(
         timeLeft - days * 86400 - hours * 3600 - minutes * 60
      );

      if (hours < "10") {
         hours = "0" + hours;
      }
      if (minutes < "10") {
         minutes = "0" + minutes;
      }
      if (seconds < "10") {
         seconds = "0" + seconds;
      }
      if (!timeLeft || timeLeft < 1) {
         $('#draw-date-timer').addClass('d-none');

      }
      else {
         $('#draw-date-timer').removeClass('d-none');
      }

      $("#days").html(days + "");
      $("#hours").html(hours + "");
      $("#minutes").html(minutes + "");
      $("#seconds").html(seconds + "");
   }

   setInterval(function () {
      makeTimer();
   }, 1000);

   // next game starts
   function next_game() {
      var endTime = $('#next-game').val();
      var now = new Date();
      now = Date.parse(now) / 1000;

      var timeLeft = endTime - now;

      var days = Math.floor(timeLeft / 86400);
      var hours = Math.floor((timeLeft - days * 86400) / 3600);
      var minutes = Math.floor((timeLeft - days * 86400 - hours * 3600) / 60);
      var seconds = Math.floor(
         timeLeft - days * 86400 - hours * 3600 - minutes * 60
      );

      if (hours < "10") {
         hours = "0" + hours;
      }
      if (minutes < "10") {
         minutes = "0" + minutes;
      }
      if (seconds < "10") {
         seconds = "0" + seconds;
      }
      if (!timeLeft || timeLeft < 1) {
         $('#game-timer').addClass('d-none');
         $('.play-btn').removeClass('d-none');
      }
      $("#days-game").html(days + "");
      $("#hours-game").html(hours + "");
      $("#minutes-game").html(minutes + "");
      $("#seconds-game").html(seconds + "");
   }

   setInterval(function () {
      next_game();
   }, 1000);



   // Generate Random 3 character code.
   function numbers() {
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      for (var i = 0; i < 3; i++)
         text += possible.charAt(Math.floor(Math.random() * possible.length));
      return text;
   }

   // QUICK PICK Button.
   function quiqfix() {
      for (var i = 0; i < 4; i++) {
         var random = Math.floor(Math.random() * $(".numbers-area ul li").length);
         console.log(random);
         $(".number-card[card-number='" + random + "']").trigger("click");
      }
   }

   // Initial Check for Code created or not and retirve.
   function fill() {
      var number1 = JSON.parse(localStorage.getItem("number-1"));
      var number2 = JSON.parse(localStorage.getItem("number-2"));
      var number3 = JSON.parse(localStorage.getItem("number-3"));
      var number4 = JSON.parse(localStorage.getItem("number-4"));
      if (number1 !== null) {
         $(".number-1").text(number1[1]);
         $(".number-1").addClass("selected");
         $(".number-card[card-number='" + number1[0] + "']").addClass("open");
         $(".number-card[card-number='" + number1[0] + "']")
            .find(".lucky-number")
            .text(number1[1]);
         $("#step1").addClass('d-none');
         $("#step2").removeClass('d-none');
      }
      if (number2 !== null) {
         $(".number-2").text(number2[1]);
         $(".number-2").addClass("selected");
         $(".number-card[card-number='" + number2[0] + "']").addClass("open");
         $(".number-card[card-number='" + number2[0] + "']")
            .find(".lucky-number")
            .text(number2[1]);
         $("#step1").addClass('d-none');
         $("#step2").removeClass('d-none');
      }
      if (number3 !== null) {
         $(".number-3").text(number3[1]);
         $(".number-3").addClass("selected");
         $(".number-card[card-number='" + number3[0] + "']").addClass("open");
         $(".number-card[card-number='" + number3[0] + "']")
            .find(".lucky-number")
            .text(number3[1]);
         $("#step1").addClass('d-none');
         $("#step2").removeClass('d-none');
      }
      if (number4 !== null) {
         $(".number-4").text(number4[1]);
         $(".number-4").addClass("selected");
         $(".number-card[card-number='" + number4[0] + "']").addClass("open");
         $(".number-card[card-number='" + number4[0] + "']")
            .find(".lucky-number")
            .text(number4[1]);
         $("#hiddenCode").attr("value", number1[1] + number2[1] + number3[1] + number4[1]);
         $("#enterBtn").removeClass('d-none');
         $("#step1").addClass('d-none');
         $("#step2").removeClass('d-none');
         $(".lucky-number-container").removeClass("not-active");
      }
      // if(localStorage.getItem("enter") == "true"){
      // 	saveCodes(number1, number2, number3, number4);
      // }
   }

   function FirstCode(number1, number2, number3, number4) { }

   //save code per line // sometime entry can be array or code lines
   function saveCodes(number1, number2, number3, number4) {
      if (localStorage.getItem("codes") !== null) {
         var codes = JSON.parse(localStorage.getItem("codes"));
         if (Array.isArray(number1)) {
            var code = number1[1] + '-' + [number2[1] + '-' + number3[1]] + '-' + number4[1];
         }
         else {
            var code = number1 + '-' + number2 + '-' + number3 + '-' + number4;
         }
         codes.push(code);
         localStorage.setItem("codes", JSON.stringify(codes));
      } else {
         var codes = [];
         if (Array.isArray(number1)) {
            var code = number1[1] + '-' + number2[1] + '-' + number3[1] + '-' + number4[1];
         }
         else {
            var code = number1 + '-' + number2 + '-' + number3 + '-' + number4;
         }
         codes.push(code);
         localStorage.setItem("codes", JSON.stringify(codes));
      }
   }

   // Generate Final CODE using Random 3 character code and store in Local Storage.
   function generateNumbers(selector) {
      if (!$(selector).hasClass("open")) {
         var luckynumber = numbers();
         var luckynumberId = $(selector).find(".card-number").text();
         var luckynumberArray = [luckynumberId, luckynumber];
         $(selector).find(".lucky-number").text(luckynumber);
         $(selector).addClass("open");
         var numItems = $(".numbers-area .number-card.open").length;
         switch (numItems) {
            case 1:
               localStorage.setItem("number-1", JSON.stringify(luckynumberArray));
               fill();
               break;
            case 2:
               localStorage.setItem("number-2", JSON.stringify(luckynumberArray));
               fill();
               break;
            case 3:
               localStorage.setItem("number-3", JSON.stringify(luckynumberArray));
               fill();
               break;
            case 4:
               localStorage.setItem("number-4", JSON.stringify(luckynumberArray));
               fill();
               break;
            default:
               alert("You already Selected 4 numbers filled");
         }
      }
   }

   // Number Box Click events.
   $(".numbers-area .number-card").click(function () {
      var numItems = $(".numbers-area .number-card.open").length;
      var selector = $(this);
      switch (numItems) {
         case 0:
            generateNumbers(selector);
            break;
         case 1:
            generateNumbers(selector);
            break;
         case 2:
            generateNumbers(selector);
            break;
         case 3:
            generateNumbers(selector);
            break;
         default:
            Swal.fire({
               title: "You Already Created Lucky Code",
               icon: "info",

            });
      }
   });

   // Play Button Step 2 Navigate.
   $("#playBtn").click(function () {
      $("#step1").addClass('d-none');
      $("#step2").removeClass('d-none');
   });

   // Quiq Pick Button
   $("#quiqfixBtn").click(function () {
      quiqfix();
   });
   $("#quickPickFinal").click(function () {
      localStorage.removeItem("number-1");
      localStorage.removeItem("number-2");
      localStorage.removeItem("number-3");
      localStorage.removeItem("number-4");
      $(".numbers-area .number-card.open").removeClass("open");
      for (var i = 1; i < 5; i++) {
         for (var i = 1; i < 5; i++) {
            var luckynumber = numbers();
            var luckynumberId = Math.floor(
               Math.random() * $(".numbers-area ul li").length
            );
            var luckynumberArray = [luckynumberId, luckynumber];
            localStorage.setItem("number-" + i, JSON.stringify(luckynumberArray));
            $(".number-" + i).text(luckynumber);
         }
      }
      var number1 = JSON.parse(localStorage.getItem("number-1"));
      var number2 = JSON.parse(localStorage.getItem("number-2"));
      var number3 = JSON.parse(localStorage.getItem("number-3"));
      var number4 = JSON.parse(localStorage.getItem("number-4"));
      saveCodes(number1, number2, number3, number4);
      update_numbers();
      codeDisplayInner();
   });

   // Enter Button Step 3 Navigate.
   $("#enterBtn").click(function () {
      localStorage.setItem("enter", true);
      fill();
      $("#step2").addClass('d-none');
      $("#step3").removeClass('d-none');

      var number1 = JSON.parse(localStorage.getItem("number-1"));
      var number2 = JSON.parse(localStorage.getItem("number-2"));
      var number3 = JSON.parse(localStorage.getItem("number-3"));
      var number4 = JSON.parse(localStorage.getItem("number-4"));
      //don't save if null
      if (number1 !== null && number2 !== null && number3 !== null && number4 !== null) {
         saveCodes(number1, number2, number3, number4);
         update_numbers();
         codeDisplay();
         codeDisplayInner();
      }
   });

   if (localStorage.getItem("enter") == "true") {
      fill();
      $("#step1").addClass('d-none');
      $("#step2").addClass('d-none');
      $("#step3").removeClass('d-none');
   } else {
      fill();
   }


   update_numbers();
   codeDisplay();
   codeDisplayInner();




   function codeDisplay() {
      let lcStr = JSON.parse(localStorage.getItem("codes"));
      if (lcStr !== null) {
         $(".lucky-number-section .number").remove();
         for (var i = 0; i < lcStr.length; i++) {
            $(".lucky-number-section_inner").append(
               '<div class="number code-' + i + '"></div>'
            );
            //split to get each code
            var per_line = lcStr[i].split('-')
            for (var j = 0; j < 4; j++) {
               $(".lucky-number-section_inner .code-" + i).append(
                  '<span class="selected">' +
                  per_line[j] +
                  '</span> <i class="dash"> - </i> '
               );
            }
         }
      }
   }

   function codeDisplayInner() {
      let lcStr = JSON.parse(localStorage.getItem("codes"));
      if (lcStr !== null) {
         $(".codes-container .codes-wrapper .codes-inner").empty();
         for (var i = 0; i < lcStr.length; i++) {

            $(".codes-container .codes-wrapper .codes-inner").append(
               `<div class="row row-` + i + `"><div class=" col-9 number-inner  code-` + i + `"></div>
          <div class=" col-3 removediv-` + i + `"></div></div>`);
            //split to get each code
            var per_line = lcStr[i].split('-');
            for (var j = 0; j < 4; j++) {
               //add times  at end
               if (j == 3) {
                  $(".codes-container .codes-wrapper .codes-inner .code-" + i).append(
                     '<span class="selected">' +
                     per_line[j] +
                     '</span> <i class="dash"> - </i> ');
                  $(".removediv-" + i).append(`<button type="button" title='Click to remove the code'
              class="remove-code ml-3 selected btn-danger border-0" 
              id=removebtn-`+ i + `>
                <span class= "selected" >&times;</span></button>`
                  )
               }
               else {
                  $(".codes-container .codes-wrapper .codes-inner .code-" + i).append(
                     '<span class="selected">' +
                     per_line[j] +
                     '</span> <i class="dash"> - </i> ');
               }


            }
         }
      }
      else {
         $("#step1").removeClass('d-none');
         $("#step2").addClass('d-none');
         $("#step3").addClass('d-none');
      }
   }

   // if code generated than we filled from Local Storage.
   // fill();  

   $("#playAgain").click(function () {
      localStorage.removeItem("number-1");
      localStorage.removeItem("number-2");
      localStorage.removeItem("number-3");
      localStorage.removeItem("number-4");
      $(".numbers-area .number-card").removeClass("open");
      $(".lucky-number-container .number span").removeClass("selected");
      $("#step1").addClass('d-none');
      $("#step2").removeClass('d-none');
      $("#step3").addClass('d-none');
      //  location.reload();
   });

   //click to remove id code
   $(document).on('click', '.remove-code', function () {
      let code_id = this.id;
      let code_line = code_id.split('-')[1];
      remove_code(code_line);
      update_numbers();
      codeDisplay();
      codeDisplayInner();
   });

   //to remove code
   function remove_code(code_line) {
      let codes = JSON.parse(localStorage.getItem("codes"));
      if (codes !== null) {
         //remove code from storage
         codes.splice(code_line, 1);
         $('.row-' + code_line).remove();
         if (codes.length == 0) {
            localStorage.removeItem("codes");
            $("#playAgain").click()

         }
         else {
            //save again to codes
            localStorage.setItem("codes", JSON.stringify(codes));
         }
      }
      update_numbers();
      codeDisplay();
      codeDisplayInner();
      localStorage.removeItem("number-1");
      localStorage.removeItem("number-2");
      localStorage.removeItem("number-3");
      localStorage.removeItem("number-4");

   }

   //server side save current number
   function update_numbers() {
      let lcStr = JSON.parse(localStorage.getItem("codes"));
      var code_lines = [];
      if (lcStr !== null) {
         for (var i = 0; i < lcStr.length; i++) {
            code_lines.push(lcStr[i]);
         }
      }
      else {
         code_lines = '';
      }

      var numbers = new FormData();
      numbers.append('current_picked', code_lines);
      $.ajax({
         url: '/user/picked/numbers/update',// Server side script to process
         type: 'POST',
         data: numbers,
         processData: false,//Bypass jquery's form data processing
         contentType: false
      }).then((data) => {
      })
   }


});













