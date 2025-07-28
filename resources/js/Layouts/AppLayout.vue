<template>

    <Head :title="title ? title : 'SparkPOS'">
        <link rel="icon" type="image/png" href="/logo/logo_favicon.webp">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- <title>SparkPOS</title> -->

        <meta name="keywords"
            content="POS System, SparkPOS, Retail Management, Sales Software, Inventory Management, Business Solutions">
        <meta name="description"
            content="SparkPOS POS System offers comprehensive solutions for efficient retail management. Manage your sales, inventory, and customer relationships with ease.">

        <meta name="og:image" property="og:image" content="{{ asset('logo/logo_favicon.webp') }}">
        <meta name="og:image:secure_url" property="og:image:secure_url" content="{{ asset('logo/logo_favicon.webp') }}">
        <meta name="og:image:width" property="og:image:width" content="500">
        <meta name="og:image:height" property="og:image:height" content="200">
        <meta name="og:image:alt" property="og:image:alt" content="SparkPOS POS System | Efficient Retail Management">
        <meta name="og:site_name" property="og:site_name" content="SparkPOS POS System">
        <meta name="og:type" property="og:type" content="website">
        <meta name="og:title" property="og:title" content="SparkPOS POS System | Efficient Retail Management">
        <meta name="og:url" property="og:url" content="{{ Request::url() }}">
        <meta name="og:description" property="og:description"
            content="SparkPOS POS System offers comprehensive solutions for efficient retail management. Manage your sales, inventory, and customer relationships with ease.">

        <meta name="twitter:image:src" content="{{ asset('logo/logo_favicon.webp') }}">
        <meta name="twitter:site" content="@YourTwitterHandle">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="SparkPOS POS System | Efficient Retail Management">
        <meta name="twitter:description"
            content="SparkPOS POS System offers comprehensive solutions for efficient retail management. Manage your sales, inventory, and customer relationships with ease.">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" href="/logo/logo_favicon.webp">
        <link rel="shortcut icon" type="image/x-icon" href="/logo/logo_favicon.webp">
    </Head>


    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="flex-row page d-flex flex-column-fluid">
            <SideBar />
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <NavBar />
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        <div id="kt_content_container" class="container-fluid">
                            <slot name="header" />
                            <slot name="content" />

                            <a href="#" @click="topFunction" id="back-to-top" class="back-to-top d-flex align-items-center justify-content-center d-md-none"
                                v-if="showButton"><i class="text-white bi bi-arrow-up-short fs-3x fw-bold"></i></a>

                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
            <slot name="modal" />
            <slot name="loader" />
        </div>
        <!--end::Page-->
    </div>
</template>

<script setup>
import { onMounted, ref, defineProps, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';
import NavBar from '@/Components/Main/NavBar.vue';
import SideBar from '@/Components/Main/SideBar.vue';
import { usePage } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';

const isSidebarExpanded = ref(false);
const showButton = ref(false);

const props = defineProps({
    title: String,
});

const toggleSidebar = () => {
    isSidebarExpanded.value = true;
};

const closeSidebar = () => {
    isSidebarExpanded.value = false;
};

const scrollFunction = () => {
    showButton.value = window.scrollY > 200;
};

onMounted(() => {
    window.addEventListener('scroll', scrollFunction);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', scrollFunction);
});

const topFunction = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<style lang="scss">
body {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    height: 100vh;
}

.table-responsive {
    overflow: auto;
}

.page-top {
    margin-top: 53px;
}

// scroll button
.back-to-top {
    position: fixed;
    z-index: 99;
    bottom: 25px;
    right: 25px;
    -webkit-transition: all 0.5s ease;
    transition: all 0.5s ease;
    height: 36px;
    width: 36px;
    line-height: 33px;
    border-radius: 6px;
    background: #00bf77;
    color: #fff !important;
}

.v3dp__datepicker {
    --elem-hover-bg-color: #00bf77 !important;
    --elem-selected-bg-color: #00bf77 !important;
}
</style>
