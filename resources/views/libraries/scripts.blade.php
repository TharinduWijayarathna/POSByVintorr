<!-- javascript -->
<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SLIDER -->
<script src="{{ asset('assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
<!-- Parallax -->
<script src="{{ asset('assets/libs/jarallax/jarallax.min.js ') }}"></script>
<!-- Lightbox -->
<script src="{{ asset('assets/libs/tobii/js/tobii.min.js') }}"></script>
<!-- Animation -->
<script src="{{ asset('assets/libs/wow.js/wow.min.js') }}"></script>
<!-- Main Js -->
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins.init.js') }}"></script>
<!--Note: All init (custom) js like tiny slider, counter, countdown, lightbox, gallery, swiper slider etc.-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let accordionButtons = document.querySelectorAll('.accordion-button-custom');

        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                let icon = button.querySelector('i');

                if (button.getAttribute('aria-expanded') === 'true') {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });
    });
</script>

@stack('scripts')
