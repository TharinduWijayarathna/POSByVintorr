@extends('layouts.app')
@section('content')
    <h3 class="slider-scroll" id="slider">Links</h3>
    <h3 class="services-scroll" id="services">Links</h3>
    <h3 class="faqs-scroll" id="faqs">Links</h3>
    <h3 class="contact-scroll" id="contact">Links</h3>

    <!-- Start -->
    <section class="pt-5 pb-5 pb-sm-0 d-flex align-items-center bg-linear-gradient-primary-SparkPos">
        <div class="container">
            <div class="row mt-5 align-items-center">
                <div class="col-md-6">
                    <div class="title-heading me-lg-4 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                        <h1 class="heading fw-bold mb-3">Point of <span class="text-primary">sale,</span> <br> point
                            of <span class="text-primary">success!</span></h1>
                        <p class="para-desc text-success">Welcome to SparkPos - Your Path to Retail Success! </p>
                        <p class="para-desc text-dark">Our Point of
                            Sale
                            solution is designed to empower small-scale retail shops, making it easy and affordable to
                            manage your business effectively. Picture your success with our POS system!</p>


                        <a href="https://demo.SparkPos.lk" class="btn btn-primary"> Experience our demo! </a>
                        <p class="mt-4 text-muted mb-0">Looking for help? <a href="#contact" class="text-primary">Get in
                                touch
                                with us.</a></p>
                    </div>
                </div>
                <!--end col-->

                <div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
                    <div class="position-relative ms-lg-5">
                        <div class="bg-half-260 overflow-hidden rounded-md shadow-md jarallax" data-jarallax
                            data-speed="0.5" style="background: url('assets/images/start/1.webp');">
                            <div class="py-lg-5 py-md-0 py-5"></div>
                        </div>

                        <div class="modern-saas-absolute-left wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                            <div class="card">
                                <div
                                    class="features feature-primary d-flex justify-content-between align-items-center rounded shadow p-3 d-none d-lg-block">
                                    <div class="d-flex align-items-center">
                                        <div class="icon bg-soft-success text-center rounded-pill">
                                            <i class="uil uil-usd-circle fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Sales</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0">Rs.<span class="counter-value"
                                                    data-target="876121">876121</span></p>
                                        </div>
                                    </div>

                                    <span class="text-success ms-4"><i class="uil uil-arrow-growth"></i> 100%</span>
                                </div>
                                <div
                                    class="features feature-primary d-flex justify-content-between align-items-center rounded shadow py-1 px-3 d-block d-lg-none">
                                    <div class="d-flex align-items-center">
                                        <div class="icon bg-soft-success text-center rounded-pill">
                                            <i class="uil uil-usd-circle fs-5 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Sales</h6>
                                            <p class="fs-6 text-dark fw-bold mb-0">Rs.<span class="counter-value"
                                                    data-target="876121">876121</span></p>
                                        </div>
                                    </div>

                                    <span class="text-success ms-4"><i class="uil uil-arrow-growth"></i> 100%</span>
                                </div>
                            </div>
                        </div>

                        <div class="modern-saas-absolute-right wow animate__animated animate__fadeInUp d-none d-lg-block"
                            data-wow-delay=".5s">
                            <div class="card rounded shadow">
                                <div class="p-3">
                                    <h5>Manage Your Business</h5>

                                    <div class="progress-box mt-2">
                                        <h6 class="title fw-normal text-muted">with SparkPos!</h6>
                                        <div class="progress">
                                            <div class="progress-bar position-relative bg-primary" style="width:100%;">
                                                <div class="progress-value d-block text-muted h6 mt-1"
                                                    style="padding-right: 15px;">100%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end process box-->
                                </div>
                            </div>
                        </div>
                        <div class="modern-saas-absolute-right wow animate__animated animate__fadeInUp d-block d-lg-none"
                            data-wow-delay=".5s">
                            <div class="card rounded shadow">
                                <div class="py-1 px-3">
                                    <h6>Manage Your Business</h6>

                                    <div class="progress-box mt-2">
                                        <h6 class="title fw-normal text-muted">with SparkPos!</h6>
                                        <div class="progress">
                                            <div class="progress-bar position-relative bg-primary" style="width:100%;">
                                                <div class="progress-value d-block text-muted h6 mt-1"
                                                    style="padding-right: 15px;">100%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end process box-->
                                </div>
                            </div>
                        </div>

                        <div class="position-absolute top-0 start-0 translate-middle z-index-m-1">
                            <img src="assets/images/shapes/dots.svg" class="avatar avatar-xl-large" alt="">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>

    <!-- Slider -->
    <section class="container pb-5 mb-md-5 mt-md-5 mt-sm-0 d-none d-md-block">
        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel"
            style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/0.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><b><i>Swipe to explore how &nbsp;</i></b><img
                            src="assets/images/logos/logo.webp" height="30" class="logo-light-mode suitability"
                            alt=""><b><i>
                                &nbsp;can seamlessly integrate with your business.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/1.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a computer
                                store.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/2.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a cosmetics
                                store.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/3.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a bakery.</i></b>
                    </p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/4.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a pastry
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/5.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a florist
                                boutique.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/6.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a baby
                                store.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/7.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a gift/ornaments
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/8.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a sports
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/9.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a ice-cream
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/10.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a stationary
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/11.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a pet shop.</i></b>
                    </p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/12.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a bag shop.</i></b>
                    </p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/13.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a furniture
                                shop.</i></b></p>
                </div>
                <div class="carousel-item" data-bs-interval="3500">

                    <div class="carousel-item-content">

                        <img src="assets/images/slider/14.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a farm shop.</i></b>
                    </p>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="prev">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#005b9a"
                    d="M8.29 11.29a1 1 0 0 0-.21.33a1 1 0 0 0 0 .76a1 1 0 0 0 .21.33l3 3a1 1 0 0 0 1.42-1.42L11.41 13H15a1 1 0 0 0 0-2h-3.59l1.3-1.29a1 1 0 0 0 0-1.42a1 1 0 0 0-1.42 0ZM2 12A10 10 0 1 0 12 2A10 10 0 0 0 2 12Zm18 0a8 8 0 1 1-8-8a8 8 0 0 1 8 8Z" />
            </svg> --}}
                <i data-feather="chevron-left" class="fea icon-lg icons align-middle"
                    style="color:#ffffff; border:0px solid #ffffff; border-radius:10px; background:#005b9a;"></i>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="next">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#005b9a"
                    d="M15.71 12.71a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76a1 1 0 0 0-.21-.33l-3-3a1 1 0 0 0-1.42 1.42l1.3 1.29H9a1 1 0 0 0 0 2h3.59l-1.3 1.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0ZM22 12a10 10 0 1 0-10 10a10 10 0 0 0 10-10ZM4 12a8 8 0 1 1 8 8a8 8 0 0 1-8-8Z" />
            </svg> --}}
                <i data-feather="chevron-right" class="fea icon-lg icons align-middle"
                    style="color:#ffffff; border:0px solid #ffffff; border-radius:10px; background:#005b9a;"></i>
            </button>
        </div>
    </section>
    <section class="container pb-2 pb-lg-5 mb-md-5 mt-md-5 mt-sm-0 d-block d-md-none">
        <div id="carouselExampleIntervalMobile" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/0.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><b><i>Swipe to explore how &nbsp;</i></b><img
                            src="assets/images/logos/logo.webp" height="30" class="logo-light-mode suitability"
                            alt=""><b><i>
                                &nbsp;can seamlessly integrate with your business.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/1.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a computer
                                store.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/2.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a cosmetics
                                store.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/3.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a bakery.</i></b>
                    </p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/4.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a pastry
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/5.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a florist
                                boutique.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/6.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a baby
                                store.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/7.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a gift/ornaments
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/8.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a sports
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/9.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a ice-cream
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/10.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a stationary
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/11.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a pet shop.</i></b>
                    </p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/12.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a bag shop.</i></b>
                    </p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/13.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a furniture
                                shop.</i></b></p>
                </div>
                <div class="carousel-item p-2" data-bs-interval="3500">

                    <div class="carousel-item-content"
                        style="border: 2px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(93, 93, 93, 0.5);">

                        <img src="assets/images/slider/14.webp" class="d-block w-100" alt="...">
                    </div>
                    <br>
                    <p style="text-align: center;"><img src="assets/images/logos/logo.webp" height="30"
                            class="logo-light-mode suitability" alt=""> <b><i>is suitable for a farm shop.</i></b>
                    </p>
                </div>
            </div>
            <button class="carousel-control-prev pt-0" type="button" data-bs-target="#carouselExampleIntervalMobile"
                data-bs-slide="prev"
                style="width: 16%; display: flex; align-items: flex-start; justify-content: end; margin-top: 75px">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#005b9a"
                    d="M8.29 11.29a1 1 0 0 0-.21.33a1 1 0 0 0 0 .76a1 1 0 0 0 .21.33l3 3a1 1 0 0 0 1.42-1.42L11.41 13H15a1 1 0 0 0 0-2h-3.59l1.3-1.29a1 1 0 0 0 0-1.42a1 1 0 0 0-1.42 0ZM2 12A10 10 0 1 0 12 2A10 10 0 0 0 2 12Zm18 0a8 8 0 1 1-8-8a8 8 0 0 1 8 8Z" />
            </svg> --}}
                <i data-feather="chevron-left" class="fea icon-lg icons"
                    style="color:#ffffff; border:0px solid #ffffff; border-radius:10px; background:#005b9a;"></i>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIntervalMobile"
                data-bs-slide="next"
                style="width: 16%; display: flex; align-items: flex-start; justify-content: start; margin-top: 75px">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#005b9a"
                    d="M15.71 12.71a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76a1 1 0 0 0-.21-.33l-3-3a1 1 0 0 0-1.42 1.42l1.3 1.29H9a1 1 0 0 0 0 2h3.59l-1.3 1.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0ZM22 12a10 10 0 1 0-10 10a10 10 0 0 0 10-10ZM4 12a8 8 0 1 1 8 8a8 8 0 0 1-8-8Z" />
            </svg> --}}
                <i data-feather="chevron-right" class="fea icon-lg icons align-middle"
                    style="color:#ffffff; border:0px solid #ffffff; border-radius:10px; background:#005b9a;"></i>
            </button>
        </div>
    </section>

    <!-- Services -->
    <section class="container pb-3 mb-md-5 mt-md-5 mt-sm-0">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-lg-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-4">Empowering Your Retail <span class="fw-bold text-primary"> Business.</span>
                    </h4>
                    <p class="text-muted para-desc mb-0 mx-auto">Transform your retail experience with seamless cart
                        management, hassle-free returns, and flexible payments.</p>

                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden " style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                        <img src="assets/images/services/01.webp" class="img-fluid" alt="">
                            <h5 class="card-title" style="font-weight: bold">Effortless Point of Sale
                            </h5>
                            <p class="text-muted">Add items to your cart smoothly with our intuitive interface and easily manage multiple carts, ensuring seamless service for every customer.</p>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden" style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold">Record Payments & Expenses</h5>
                            <p class="text-muted">Record customer payments and track all of your business expenses. Check all the transactions in one place for simplified financial management.</p>
                            <img src="assets/images/services/02.webp" class="img-fluid " alt="">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden" style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                        <img src="assets/images/services/03.webp" class="img-fluid" alt="">
                            <h5 class="card-title" style="font-weight: bold">Effortless Invoicing</h5>
                            <p class="text-muted">Create invoices with our intuitive interface or create quotations and convert them into invoices with just a few clicks, making invoicing a breeze.</p>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden" style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold">Your Own Public Website</h5>
                            <p class="text-muted">Showcase your products with your personalized public website, reaching more customers and enhancing your online presence effortlessly.</p>
                            <img src="assets/images/services/04.webp" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden" style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                        <img src="assets/images/services/05.webp" class="img-fluid" alt="">
                            <h5 class="card-title" style="font-weight: bold">Insightful Dashboard </h5>
                            <p class="text-muted">Gain valuable insights into your business with a comprehensive dashboard displaying daily and monthly charts, expense breakdowns, etc.</p>

                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-6 col-12 mt-4 pt-2">
                    <div class="card shadow border-5 overflow-hidden" style="border: 2px solid #005b9a; border-radius: 50px;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold">Customizable Invoices</h5>
                            <p class="text-muted">Personalize your invoices with your logo, theme color, and additional details in the footer and header, leaving a lasting impression on your clients.</p>
                            <img src="assets/images/services/06.webp" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="https://www.youtube.com/channel/UCctSlp79gKBv4b-eaBpmcMQ" class="btn btn-outline-primary"
                        target="_blank"> Explore the Features <i class="bi bi-arrow-up-right"></i> </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs -->
    <section class="container pb-5 mb-md-5 mt-md-5 mt-sm-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2 wow animate__animated animate__fadeInUp"
                    data-wow-delay=".1s">
                    <h4 class="title mb-4">Frequently Asked Questions</h4>
                    <p class="text-muted para-desc mb-0 mx-auto"> Got questions about <span
                            class="text-primary fw-bold">SparkPos? </span>Here are some common queries our customers often
                        have. If you don't find your answer here, don't hesitate to contact us!</p>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row align-items-center">
            <div class="col-md-6 mt-2">
                <div class="bg-half-260 overflow-hidden rounded-md shadow-md jarallax"
                    style="background: url('assets/images/faq/1.webp');">
                </div>
            </div>
            <!--end col-->

            <div class="col-md-6 mt-4 pt-2">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item rounded shadow wow animate__animated animate__fadeInUp"
                        data-wow-delay=".3s">

                        <div class="row ps-2 py-1 pt-lg-2 pb-lg-2 faq-topic" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <div class="col-10 col-md-11">
                                <span class="question-text">Is SparkPos suitable for my specific retail
                                    business?</span>
                            </div>
                            <div class="col-2 col-md-1 d-flex align-items-center justify-content-end justify-content-lg-center">
                                <i class="fas fa-chevron-up me-2" id="faq_1" style="font-size: 20px;"></i>
                            </div>
                        </div>
                        <div id="collapseOne" class="accordion-collapse border-0 collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                Absolutely! SparkPos is designed for various small-scale retail shops, including boutiques,
                                cafes, bakeries, and more. Its flexibility and customizable features make it adaptable to
                                your unique business needs.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded shadow mt-3 wow animate__animated animate__fadeInUp"
                        data-wow-delay=".5s">
                        <div class="row ps-2 py-1 pt-lg-2 pb-lg-2 faq-topic" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <div class="col-10 col-md-11">
                                <span class="question-text">How easy is it to train my staff to use SparkPos?</span>
                            </div>
                            <div class="col-2 col-md-1 d-flex align-items-center justify-content-end justify-content-lg-center">
                                <i class="fas fa-chevron-down me-2" id="faq_2" style="font-size: 20px;"></i>
                            </div>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse border-0 collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                SparkPos is known for its user-friendly interface. Your staff can quickly learn to use the
                                system with minimal training. We also provide training resources and support to ensure a
                                smooth transition.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded shadow mt-3 wow animate__animated animate__fadeInUp"
                        data-wow-delay=".7s">
                        <div class="row ps-2 py-1 pt-lg-2 pb-lg-2 faq-topic" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <div class="col-10 col-md-11">
                                <span class="question-text">Can I try SparkPos before committing to a subscription?</span>
                            </div>
                            <div class="col-2 col-md-1 d-flex align-items-center justify-content-end justify-content-lg-center">
                                <i class="fas fa-chevron-down me-2" id="faq_3" style="font-size: 20px;"></i>
                            </div>
                        </div>
                        <div id="collapseThree" class="accordion-collapse border-0 collapse"
                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                Of course! We offer a free trial so you can experience SparkPos firsthand. You can test its
                                features and see how it fits your business requirements before making any commitment.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded shadow mt-3 wow animate__animated animate__fadeInUp"
                        data-wow-delay=".9s">
                        <div class="row ps-2 py-1 pt-lg-2 pb-lg-2 faq-topic" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <div class="col-10 col-md-11">
                                <span class="question-text">Can I customize the invoices with my branding?</span>
                            </div>
                            <div class="col-2 col-md-1 d-flex align-items-center justify-content-end justify-content-lg-center">
                                <i class="fas fa-chevron-down me-2" id="faq_4" style="font-size: 20px;"></i>
                            </div>
                        </div>
                        <div id="collapseFour" class="accordion-collapse border-0 collapse "
                            aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                Yes, you can! SparkPos offers the flexibility to personalize your invoices with your unique
                                branding elements, such as your logo and business information. You can create professional,
                                customized invoices that reflect your brand's identity.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded shadow mt-3 wow animate__animated animate__fadeInUp"
                        data-wow-delay="1.1s">
                        <div class="row ps-2 py-1 pt-lg-2 pb-lg-2 faq-topic" data-bs-toggle="collapse"
                            data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <div class="col-10 col-md-11">
                                <span class="question-text">What if I need assistance or encounter issues while using
                                    SparkPos?</span>
                            </div>
                            <div class="col-2 col-md-1 d-flex align-items-center justify-content-end justify-content-lg-center">
                                <i class="fas fa-chevron-down me-2" id="faq_5" style="font-size: 20px;"></i>
                            </div>
                        </div>
                        <div id="collapseFive" class="accordion-collapse border-0 collapse" aria-labelledby="headingFive"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                We're here to support you! Our dedicated customer support team is available to help you with
                                any questions or issues you may face. You can reach out to us via phone, email or using our
                                social media platforms.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </section>

    <!-- Contact -->
    <section class="container pb-5 mb-md-5 mt-md-5 mt-sm-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2 wow animate__animated animate__fadeInUp"
                    data-wow-delay=".1s">
                    <h4 class="title mb-4">Connect With Us</h4>
                    <p class="text-muted para-desc mb-0 mx-auto"> Ready to take your retail business to the next level with
                        <span class="text-primary fw-bold">SparkPos? </span>Contact us to learn more, request a demo, or get
                        started. We're here to support your journey to success.
                    </p>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
        <div class="row  pt-3">
            <div class="col-md-4">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-phone rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Phone</h5>
                        <p class="text-muted">For immediate assistance, please contact our helpline.</p>
                        <a href="tel:+94772299862" class="text-primary">077 229 9862</a>
                    </div>
                </div>
            </div>
            <!--end col-->

            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-envelope rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Email</h5>
                        <p class="text-muted">Connect with our team via email for prompt and thorough support.</p>
                        <a href="mailto:sparkpos@emergentspark.com" class="text-primary">sparkpos@emergentspark.com</a>
                    </div>
                </div>
            </div>
            <!--end col-->

            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-globe rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Social Media</h5>
                        <p class="text-muted">Engage and connect with us across our social channels.</p>
                        <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">

                            <li class="list-inline-item mb-0"><a
                                    href="https://www.facebook.com/profile.php?id=61552381082922" target="_blank"
                                    class="rounded"><i class="uil uil-facebook-f align-middle" title="facebook"></i></a>
                            </li>
                            <li class="list-inline-item mb-0"><a href="https://www.linkedin.com/company/SparkPos.lk"
                                    target="_blank" class="rounded"><i class="uil uil-linkedin align-middle"
                                        title="linkedin"></i></a></li>
                            <li class="list-inline-item mb-0"><a href="https://wa.me/+94772299862" target="_blank"
                                    class="rounded"><i class="uil uil-whatsapp align-middle" title="whatsapp"></i></a>
                            </li>
                            <li class="list-inline-item mb-0"><a href="https://www.youtube.com/@emergentsparkSoftware" target="_blank" class="rounded"><i
                                        class="uil uil-youtube align-middle" title="youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </section>

    <!-- End -->
@endsection
@push('scripts')
    <script>
        $('#faq_1').click(function() {
            $('#faq_1').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('#faq_2').click(function() {
            $('#faq_2').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('#faq_3').click(function() {
            $('#faq_3').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('#faq_4').click(function() {
            $('#faq_4').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('#faq_5').click(function() {
            $('#faq_5').toggleClass('fa-chevron-down fa-chevron-up');
        });
    </script>
@endpush
