<template>
    <AppLayout title="Outstanding">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div class="pb-0 col-12 col-sm-6 d-flex justify-content-start align-items-center pb-sm-3">
                                <h1 class="page-title">
                                    Outstanding&nbsp;
                                </h1>
                            </div>
                            <!-- Desktop view -->
                            <div class="col-12 col-sm-6 d-none d-md-flex justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)" class="btn btn-info fw-bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Print outstanding report">
                                    <i class="bi bi-printer"></i>
                                    PRINT
                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Export outstanding report"
                                    class="btn btn-info ms-4 fw-bold">
                                    <i class="bi bi-box-arrow-up"></i> EXPORT
                                </a>
                            </div>

                            <!-- Mobile view -->
                            <div class="col-12 col-sm-6 d-flex d-md-none justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)"
                                    class="btn btn-info export-btn ps-3 pe-2 ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Print outstanding report">
                                    <i class="bi bi-printer fs-2 export-icon ms-1"></i>

                                </a>
                                <div @click.prevent="exportReport" class="btn btn-info export-btn ps-3 pe-2 ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export outstanding report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </div>

                            </div>

                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="search" class="pt-0 pb-1 text-gray-600 col-form-label">CODE</label>
                                        <input id="search" v-model="search" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="select_search_customer"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect v-model="select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Currency"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="select_search_customer"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">CUSTOMER</label>
                                        <Multiselect id="select_search_customer" v-model="select_search_customer"
                                            :options="customers" class="mb-2 z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="name" track-by="id" max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="from_date" class="pt-0 pb-1 text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="to_date" class="pt-0 pb-1 text-gray-600 col-form-label">TO
                                            DATE</label>
                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>

                                    <div class="col-md-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <!-- <div class="mb-2 col-xl-1 col-xxl-1 mb-xl-0"></div> -->

                                    <div class="mb-2 col-xl-3 col-xxl-1 ps-0 d-flex justify-content-end align-self-end">
                                        <div class="dataTables_length" id="kt_ecommerce_orders_table_length">
                                            <label><select name="kt_ecommerce_orders_table_length"
                                                    aria-controls="kt_ecommerce_orders_table"
                                                    class="form-select form-select-sm form-select-solid" :value="2"
                                                    v-model="pageCount" @change="perPageChange">
                                                    <option v-for="perPageCount in perPage" :key="perPageCount"
                                                        :value="perPageCount" v-text="perPageCount" />
                                                </select></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openOutstandingFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="orders.length > 0">
                                            <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                                <label>
                                                    <select name="kt_ecommerce_products_table_length"
                                                        aria-controls="kt_ecommerce_products_table"
                                                        class="form-select form-select-sm form-select-solid" :value="2"
                                                        v-model="pageCount" @change="perPageChange">
                                                        <option v-for="perPageCount in perPage" :key="perPageCount"
                                                            :value="perPageCount" v-text="perPageCount" />
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Desktop View -->
                                <div class="row">
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3 table-align-max">CODE</th>
                                                    <th class="table-align-max">DATE</th>
                                                    <th class="text-end table-align-min">PAID AMOUNT</th>
                                                    <th class="text-end table-align-max">0-30 DAYS</th>
                                                    <th class="text-end table-align-max">31-60 DAYS</th>
                                                    <th class="text-end table-align-max">61-90 DAYS</th>
                                                    <th class="text-end table-align-max">OVER 90 DAYS</th>
                                                    <th class="text-end table-align-max">AGE(DAYS)</th>
                                                    <th class="text-center table-align-max">CURRENCY</th>
                                                    <th class="text-end pe-10 table-align-max">TOTAL</th>
                                                    <th class="text-end pe-3 table-align-max">DUE AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="order in orders" :key="order.id" class="cursor-pointer">
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 ps-3 table-align-max">{{ order.code }}</td>
                                                    <td @click="handleOrderClick(order)" class="py-2 table-align-max">{{
                                                        formatDate(order.date) }}</td>

                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-min">{{
                                                            numberFormatter(order.customer_paid) }}</td>

                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-max">{{ order.age_column ===
                                                            'age_0_30' ?
                                                            order.formatted_due : numberFormatter(0) }}
                                                    </td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-max">{{ order.age_column ===
                                                            'age_31_60' ?
                                                            order.formatted_due : numberFormatter(0) }}
                                                    </td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-max">{{ order.age_column ===
                                                            'age_61_90' ?
                                                            order.formatted_due : numberFormatter(0) }}
                                                    </td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-max">{{ order.age_column ===
                                                            'over_90' ?
                                                            order.formatted_due : numberFormatter(0) }}
                                                    </td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end table-align-max">{{ order.age }}</td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-center table-align-max">{{ order.currency_name
                                                        }}
                                                    </td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end pe-10 table-align-max">{{
                                                            numberFormatter(order.total) }}</td>
                                                    <td @click="handleOrderClick(order)"
                                                        class="py-2 text-end pe-3 table-align-max">{{
                                                            numberFormatter(order.total - order.customer_paid) }}</td>
                                                </tr>
                                                <tr v-if="select_currency && total_total != 0 && select_currency.id != 0"
                                                    class="total-row-line">
                                                    <td class="py-2 ps-3 table-align-max"> </td>
                                                    <td class="py-2 text-gray-600 table-align-max fw-bold fs-6">TOTAL
                                                    </td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end table-align-min fw-bold fs-6">
                                                        {{ numberFormatter(paid_total) }}</td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(age_0_30_total) }}
                                                    </td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(age_31_60_total) }}
                                                    </td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(age_61_90_total) }}
                                                    </td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(over_90_total) }}
                                                    </td>
                                                    <td class="py-2 text-end table-align-max"> </td>
                                                    <td class="py-2 text-center table-align-max"> </td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end pe-10 table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(total_total) }}</td>
                                                    <td
                                                        class="py-2 text-gray-600 text-end pe-3 table-align-max fw-bold fs-6">
                                                        {{ numberFormatter(due_amount_total) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!--  Mobile View -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="order in orders" :key="order.id">
                                                    <td class="table-responsive-width" @click="handleOrderClick(order)">
                                                        <div class="row">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase ">
                                                                CODE</div>
                                                            <div class="col-7 col-sm-8 right-side text-end">
                                                                <div>
                                                                    {{ order.code }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                DATE</div>
                                                            <div class="col-7 col-sm-8 right-side text-end">
                                                                <span>{{ formatDate(order.date) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                PAID</div>
                                                            <div class="col-7 col-sm-8 right-side text-end">
                                                                <span>{{
                                                                    numberFormatter(order.customer_paid) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                0-30 DAYS</div>
                                                            <div class="col-7 col-sm-8 right-side text-end"> {{
                                                                order.age_column
                                                                    ===
                                                                    'age_0_30' ?
                                                                    order.formatted_due : numberFormatter(0) }}
                                                            </div>
                                                        </div>


                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                31-60 DAYS</div>
                                                            <div class="col-7 col-sm-8 right-side text-end">
                                                                <span>{{ order.age_column ===
                                                                    'age_31_60' ?
                                                                    order.formatted_due : numberFormatter(0) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                61-90 DAYS</div>
                                                            <div class="col-7 col-sm-8 right-side text-end">
                                                                <span>{{ order.age_column ===
                                                                    'age_61_90' ?
                                                                    order.formatted_due : numberFormatter(0) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                > 90 DAYS </div>
                                                            <div class="col-7 col-sm-8 right-side text-end ">
                                                                <span>{{ order.age_column === 'over_90' ?
                                                                    order.formatted_due : numberFormatter(0) }} </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                AGE(DAYS)</div>
                                                            <div class="col-7 col-sm-8 right-side text-end ">
                                                                <span>{{ order.age }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                CURRENCY</div>
                                                            <div class="col-7 col-sm-8 right-side text-end ">
                                                                <span>{{ order.currency_name
                                                                    }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                TOTAL</div>
                                                            <div class="col-7 col-sm-8 right-side text-end ">
                                                                <span>{{
                                                                    numberFormatter(order.total) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div
                                                                class="text-gray-400 col-5 col-sm-4 left-side text-uppercase">
                                                                DUE</div>
                                                            <div class="col-7 col-sm-8 right-side text-end ">
                                                                <span>{{
                                                                    numberFormatter(order.total - order.customer_paid)
                                                                }}</span>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="hr-no-margin "> -->
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div v-if="pagination" class="my-3 row ps-1 ps-md-0">

                                    <div class="col-sm-6">
                                        <div for="purchase_uom" class="text-gray-600 col-form-label">
                                            Showing {{ pagination.from }} to
                                            {{ pagination.to }} of
                                            {{ pagination.total }} entries
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_previous"><a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)"
                                                        aria-controls="kt_ecommerce_sales_table" data-dt-idx="0"
                                                        tabindex="0" class="page-link"><i class="previous"></i></a></li>
                                                <template v-for="(page, index) in pagination.last_page">
                                                    <template
                                                        v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                                        <li class="paginate_button page-item" :key="index"
                                                            :class="pagination.current_page == page ? 'active' : ''"><a
                                                                href="javascript:void(0)" @click="setPage(page)"
                                                                aria-controls="kt_ecommerce_sales_table" data-dt-idx="1"
                                                                tabindex="0" class="page-link">{{ page }}</a></li>
                                                    </template>
                                                </template>
                                                <li class="paginate_button page-item next"
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_next"><a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page + 1)"
                                                        aria-controls="kt_ecommerce_sales_table" data-dt-idx="6"
                                                        tabindex="0" class="page-link"><i class="next"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Outstanding Filter Modal -->
            <div class="modal fade" id="outstandingFIlterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="outstandingFIlterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Outstanding Filters</h5>
                            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">
                                    <font-awesome-icon icon="fa-solid fa-xmark" />
                                </span>
                            </button>
                        </div>
                        <div class="pt-0 pb-3 mt-0 modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="items-center text-muted">
                                        <label for="modalSearch" class="text-gray-600 col-form-label">CODE</label>
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCurrency"
                                            class="text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect id="modalSelectCurrency" v-model="select_currency"
                                            :options="currencies" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="code" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCustomer"
                                            class="text-gray-600 col-form-label">CUSTOMER</label>
                                        <Multiselect id="modalSelectCustomer" v-model="select_search_customer"
                                            :options="customers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="name" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalFromDate" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker id="modalFromDate" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :upperLimit="to_date" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalToDate" class="text-gray-600 col-form-label">TO DATE</label>
                                        <Datepicker id="modalToDate" v-model="to_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :lowerLimit="from_date" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="outstandingFIlterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyOutstandingFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
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
import { debounce } from "lodash";
import _ from 'lodash';

const datePickerFormat = 'dd-MM-yyyy';
const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const from_date = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
const to_date = ref(new Date());

const loading_bar = ref(null);

const search_order = ref({
    customer_id: null,
    from_date: from_date.value,
    to_date: to_date.value,
    currency_id: null,
});
const orders = ref([]);
const products_items = ref([]);
const currencies = ref([]);
const select_order = ref(null);
const select_currency = ref({});
const customers = ref([]);
const search = ref(null);
const select_search_customer = ref(null);

const paid_total = ref(0);
const age_0_30_total = ref(0);
const age_31_60_total = ref(0);
const age_61_90_total = ref(0);
const over_90_total = ref(0);
const total_total = ref(0);
const due_amount_total = ref(0);

const placeholderText = 'DD/MM/YYYY';

const validationErrors = ref({});
const validationMessage = ref(null);
const previousCurrency = ref({});

onMounted(() => {
    setToDate();
    getCustomer();
    getCurrencies();
    getBusinessDetails();
});

const setToDate = () => {
    to_date.value.setHours(23, 59, 59, 999);
}

const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    const { message } = error.response.data;

    errorMessage(message);
};

const setPage = async (new_page) => {
    page.value = new_page;
    reload();
};
const getSearch = async () => {
    page.value = 1;
    reload();
};

const debouncedGetSearch = _.debounce(getSearch, 1000);

const perPageChange = async () => {
    page.value = 1;
    reload();
};

const openOutstandingFilterModal = () => {
    loading_bar.value.start();
    $('#outstandingFIlterModal').modal('show');
    loading_bar.value.finish();
};

const applyOutstandingFilter = () => {
    $('#outstandingFIlterModal').modal('hide');
    reload();
};

const outstandingFIlterModalClear = async () => {
    search.value = null;
    select_search_customer.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    select_currency.value = null;
    search_order.value = {
        customer_id: null,
        from_date: from_date.value,
        to_date: to_date.value,
        currency_id: null,
    };
    page.value = 1;
    $('#outstandingFIlterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('report.outstanding.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_customer: search_order.value.customer_id,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_currency: select_currency.value.id,
            },
        })
    ).data;

    orders.value = tableData.data;
    pagination.value = tableData.meta;

    if (select_currency.value) {
        getTotalValues();
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getTotalValues = () => {

    paid_total.value = 0;
    age_0_30_total.value = 0;
    age_31_60_total.value = 0;
    age_61_90_total.value = 0;
    over_90_total.value = 0;
    total_total.value = 0;
    due_amount_total.value = 0;
    let due_amount = 0;

    orders.value.forEach((order) => {
        paid_total.value += parseFloat(order.customer_paid) || 0;

        due_amount = parseFloat(order.total) - parseFloat(order.customer_paid);

        age_0_30_total.value += order.age_column === 'age_0_30' ? due_amount : 0;
        age_31_60_total.value += order.age_column === 'age_31_60' ? due_amount : 0;
        age_61_90_total.value += order.age_column === 'age_61_90' ? due_amount : 0;
        over_90_total.value += order.age_column === 'over_90' ? due_amount : 0;

        total_total.value += parseFloat(order.total) || 0;

        due_amount_total.value += due_amount;
    });
}

const handleOrderClick = async (order) => {
    try {
        if (order.type == 0) {
            editCreditBill(order.id);
        } else if (order.type == 1) {
            editInvoice(order.id);
        }
    } catch (error) {

    }
};

const editCreditBill = async (id) => {
    try {

        window.location.href = route("credit.edit", id);

    } catch (error) {
        errorMessage(error);
    }
};

const editInvoice = async (id) => {

    try {
        const response = await axios.get(route('invoice.edit', id));
        if (response.data.type == 1) {
            window.location.href = route('invoice.load', response.data.id);
        }
    } catch (error) {
        errorMessage(error);
    }

};

const getCustomer = async () => {
    try {
        const tableData = (await axios.get(route('customer.multiselect.all'))).data;
        customers.value = tableData;

        // Adding a walking customer
        const newCustomer = { id: 0, name: "Walking Customer" };
        customers.value.push(newCustomer);
    } catch (error) {

    }
};

const getCurrencies = async () => {
    try {
        const response = (await axios.get(route("currency.list"))).data.data;
        const allCurrenciesOption = { id: 0, code: 'All Currencies' };
        const updatedResponse = [allCurrenciesOption, ...response];

        currencies.value = updatedResponse;

    } catch (error) {
    }
};


const printReport = async () => {
    try {
        await nextTick(() => {
            loading_bar.value.start();
        });
        const response = await axios.post(
            route("report.outstanding.print"),
            {
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_currency: select_currency.value,
                orders: orders.value,
                customer: select_search_customer.value,
                code: search.value,
            },
            { responseType: "blob" }
        );
        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
    } catch (error) {
        // console.error('Error printing report:', error);
        convertValidationNotification(error);
    } finally {
        await nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const exportReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.outstanding.export"),
            {
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_currency: select_currency.value,
                customer: select_search_customer.value,
                orders: orders.value,
                code: search.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;


        // file name
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `OutstandingReport-${formattedDate}.xlsx`;

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();

    } catch (error) {
        // console.error(error);
        convertValidationNotification(error);
    } finally {
        loading_bar.value.finish();
    }
};

const clearFilters = async () => {
    search.value = null;
    select_search_customer.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    select_currency.value = null;
    search_order.value = {
        customer_id: null,
        from_date: from_date.value,
        to_date: to_date.value,
        currency_id: null,
    };
    page.value = 1;
    getBusinessDetails();
};

watch(select_search_customer, (newValue) => {
    if (newValue) {
        search_order.value.customer_id = newValue.id;
    } else {
        search_order.value.customer_id = null;
    }
    getSearch();
});

watch(from_date, (newValue) => {
    search_order.value.from_date = newValue;
    getSearch();
});

watch(to_date, (newValue) => {
    if (to_date.value) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_order.value.to_date = endDate;
        getSearch();
    } else {
        search_order.value.to_date = null;
    }
});

watch(select_currency, (newValue) => {
    if (select_currency.value) {
        previousCurrency.value = select_currency.value;
        page.value = 1;
        reload();
    } else {
        page.value = 1;
        select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

const numberFormatter = (number) => {
    if (number == undefined || number == null || number == Infinity) {
        return "0.00";
    }
    const parsedNumber = Number(number);
    if (isNaN(parsedNumber)) {
        return "0.00";
    }
    if (typeof parsedNumber !== "number") {
        return "0.00";
    }
    return parsedNumber.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
    });
};

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data.data;
        if (response.currency_id) {
            select_currency.value.id = response.currency_id;
            select_currency.value.code = response.currency_name;
        }
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

</script>

<style lang="css" scoped>
.table-responsive .total-row-line {
    border-top: 2px solid #eceef0 !important;
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.page-title {
    margin-right: auto;
    font-size: 28px;
    color: #071437
}

.done-status {
    background-color: #DBFFE4;
    color: green;
}

.table-align-max {
    width: 10%;
}

.table-align-min {
    width: 5%;
}
</style>
