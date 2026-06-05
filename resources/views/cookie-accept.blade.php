   <div id="cookie-container"
       style="width:100vw;height:100vh;position:fixed;bottom:0;background-color:rgba(111, 66, 193, 0.8);z-index:100000000000;display:none">
       <div
           style="padding:1rem;color:white;width:100vw;position:absolute;bottom:0;background-color:rgba(111, 66, 193, 1)">
           <div>
               <h4 style="color:white">Cookie Consent</h4>
           </div>
           <div>
               <div>
                   <p style="color:white;">This website uses cookies or similar technologies, to enhance your browsing
                       experience and
                       provide
                       personalized recommendations. By continuing to use our website, you agree to our
                       <a style="color:#fb9c2a;text-decoration:underline" href="/privacy-policy">Privacy Policy</a>
                   </p>
                   <div style="text-align:right;padding:1rem">
                       <button id="accept-cookie"
                           style="background-color:#fb9c2a;padding:0.25rem .5rem; border:3px solid#fb9c2a;border-radius:1rem; ">Accept</button>
                   </div>
               </div>
           </div>
       </div>
   </div>
   <script>
       if (localStorage.getItem('accept-cookie') == null) {
           $('#cookie-container').css('display', 'block')
           $(document).on('click', '#accept-cookie', function() {
               localStorage.setItem('accept-cookie', true)
               $('#cookie-container').remove()
           })
       }
   </script>
