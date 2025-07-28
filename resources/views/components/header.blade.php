       <!-- Navbar Start -->
       <header id="topnav" class="defaultscroll sticky">
           <div class="container">
               <!-- Logo container-->
               <a class="logo" href="#">
                   <img src="assets/images/logos/logo.webp" height="30" class="logo-light-mode" alt="">
                   <img src="assets/images/logos/logo.webp" height="30" class="logo-dark-mode" alt="">
               </a>
               <!-- Logo End -->

               <!-- End Logo container-->
               <div class="menu-extras">
                   <div class="menu-item">
                       <!-- Mobile menu toggle-->
                       <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                           <div class="lines">
                               <span></span>
                               <span></span>
                               <span></span>
                           </div>
                       </a>
                       <!-- End mobile menu toggle-->
                   </div>
               </div>

               <div id="navigation">
                   <!-- Navigation Menu-->
                   <ul class="navigation-menu buy-button list-inline mb-0">
                       <li class="list-inline-item ps-1 mb-0"><a href="/" class="sub-menu-item">Home</a></li>
                       <li class="list-inline-item ps-1 mb-0" onclick="toggleMenu()"><a href="#slider"
                               class="sub-menu-item">Suitability</a>
                       </li>
                       <li class="list-inline-item ps-1 mb-0" onclick="toggleMenu()"><a href="#services" class="sub-menu-item">Services</a>
                       </li>
                       <li class="list-inline-item ps-1 mb-0" onclick="toggleMenu()"><a href="#faqs" class="sub-menu-item">FAQs</a></li>
                       <li class="list-inline-item ps-1 mb-0" onclick="toggleMenu()"><a href="#contact" class="sub-menu-item">Connect</a></li>
                       <li class="list-inline-item ps-1 mb-0 d-block d-lg-none"><a href="{{ route('get-started') }}"
                               class="sub-menu-item">Register</a></li>
                       <a href="{{ route('get-started') }}"
                           class="btn btn-primary d-none d-lg-block d-lg-flex align-items-center my-3">Register</a>


                   </ul><!--end navigation menu-->
               </div><!--end navigation-->
           </div><!--end container-->
       </header><!--end header-->
       <!-- Navbar End -->
