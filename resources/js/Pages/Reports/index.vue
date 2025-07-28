<template>
    <AppLayout title="Reports">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <!-- Title Section -->
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="page-title">Reports</h1>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="pt-0 mt-0 row g-4 g-xl-6">
                        <!-- Outstanding Balance -->
                        <div class="mb-4 col-sm-6 col-xl-4">
                            <Link :href="route('report.outstanding.index')"
                                class="p-5 text-center card sub-section-card box-shadow h-100">
                            <div class="mb-4 icon-wrapper">
                                <i class="bi bi-cash-coin fs-4x text-dark"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark">Outstanding Balance</div>
                            </Link>
                        </div>

                        <!-- Profit & Loss -->
                        <div class="mb-4 col-sm-6 col-xl-4">
                            <Link :href="route('report.profitAndLoss.index')"
                                class="p-5 text-center card sub-section-card box-shadow h-100">
                            <div class="mb-4 icon-wrapper">
                                <i class="bi bi-bar-chart-fill fs-4x text-dark"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark">Profit & Loss Report</div>
                            </Link>
                        </div>

                        <!-- Product Sales -->
                        <div class="mb-4 col-sm-6 col-xl-4">
                            <Link :href="route('report.productSales.index')"
                                class="p-5 text-center card sub-section-card box-shadow h-100">
                            <div class="mb-4 icon-wrapper">
                                <i class="bi bi-basket fs-4x text-dark"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark">Sales Performance</div>
                            </Link>
                        </div>

                        <!-- Stock Movement -->
                        <div class="mb-4 col-sm-6 col-xl-4">
                            <Link :href="route('report.stockMovement.index')"
                                class="p-5 text-center card sub-section-card box-shadow h-100">
                            <div class="mb-4 icon-wrapper">
                                <i class="bi bi-archive fs-4x text-dark"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark">Stock Movement</div>
                            </Link>
                        </div>

                        <!-- Tax Report -->
                        <div class="mb-4 col-sm-6 col-xl-4">
                            <Link :href="route('report.tax.index')"
                                class="p-5 text-center card sub-section-card box-shadow h-100">
                            <div class="mb-4 icon-wrapper">
                                <i class="bi bi-file-earmark-text fs-4x text-dark"></i>
                            </div>
                            <div class="fs-3 fw-bold text-dark">Tax Compliance</div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>



        </template>
        <template #loader>
            <Loader ref="loading_bar" />
        </template>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, watch, nextTick, computed } from 'vue';
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import moment from 'moment';
import { usePage } from '@inertiajs/vue3'
import Datepicker from 'vue3-datepicker'
import { Link } from '@inertiajs/vue3';

const datePickerFormat = 'dd-MM-yyyy';

</script>

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.page-title {
    margin-right: auto;
    font-size: 28px;
    color: #071437
}

.sub-section-card {
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.right-arrow {
    font-weight: 100px;
    font-size: 25px;
}

.favicon-image {
    /* width: 65px;
    height: 65px; */
    font-size: 3rem;
}
</style>
