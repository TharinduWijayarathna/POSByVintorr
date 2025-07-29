<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="{{ asset('assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/animate.css/animate.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('assets/css/style-green.min.css') }}" id="color-opt" class="theme-opt" rel="stylesheet"
    type="text/css">
<link href="{{ asset('assets/css/bootstrap-green.min.css') }}" id="bootstrap-style" class="theme-opt" rel="stylesheet"
    type="text/css">




<style>
    .bg-linear-gradient-primary-POSByVintorr {

        background: -webkit-gradient(linear,
                left bottom,
                left top,
                from(rgba(0, 146, 202, 0)),
            );
        background: linear-gradient(to top,
                rgba(0, 146, 202, 0) 0,
                rgba(0, 146, 202, 0.3) 100%);

    }

    .demo {
        margin-left: 80px;
        margin-right: 80px;
    }

    .glow {
        font-size: 24px;
        text-align: center;
        text-shadow: 0 0 5px #000000, 0 0 10px #000000, 0 0 15px #000000;
    }

    a.read-more-custom {
        color: #74c2e1;
    }

    a.read-more-custom:hover {
        color: #005b9a;
    }

    .indicator {
        float: right;
    }

    .accordion-button-custom .question-text {
        font-size: 14px;
        margin-left: 20px;
    }

    .icon-right {
        position: absolute;
        right: 10px;
        padding: 15px;
    }

    @media (max-width: 576px) {
        .mobile-accordion-header {
            background-color: #f8f9fa;
        }
    }
    @media (max-width: 992px) {
        .mobile-accordion-header {
            background-color: #f8f9fa;
            margin-left: -15px;
        }
        .mobile-question-text{
            font-size: 12px;
        }
    }

    .faq-topic{
        background-color: #f8f9fa;
    }

    .slider-scroll {
        position: absolute;
        z-index: -100;
        visibility: hidden;
        top: 660px;
    }

    .services-scroll {
        position: absolute;
        z-index: -100;
        visibility: hidden;
        top: 1400px;
    }

    .faqs-scroll {
        position: absolute;
        z-index: -100;
        visibility: hidden;
        top: 3550px;
    }

    .contact-scroll {
        position: absolute;
        z-index: -100;
        visibility: hidden;
        top: 4305px;
    }

    .carousel-item-content {
        position: relative;
    }

    .carousel-item-content p {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.7);
        padding: 10px;
    }


    @media (max-width: 768px) {

        .demo {
            margin-left: 60px;
            margin-right: 60px;
        }

        .accordion-button-custom .question-text {
            font-size: 12px;
            /* margin-left: 20px; */
            text-align: left;
        }

        .slider-scroll {
            position: absolute;
            z-index: -100;
            visibility: hidden;
            top: 900px;
        }

        .services-scroll {
            position: absolute;
            z-index: -100;
            visibility: hidden;
            top: 1200px;
        }

        .faqs-scroll {
            position: absolute;
            z-index: -100;
            visibility: hidden;
            top: 4740px;
        }

        .contact-scroll {
            position: absolute;
            z-index: -100;
            visibility: hidden;
            top: 5720px;
        }

    }
</style>

@stack('styles')
