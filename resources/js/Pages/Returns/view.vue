<template>
    <AppLayout title="Returns">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-md-6 d-flex justify-content-start align-items-center">
                                <h1 class="main-header-text">
                                    View Returns
                                </h1>

                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
                                <a :href="route('return.deleted.list')" data-toggle="tooltip" data-placement="bottom"
                                    title="Deleted list" class="btn trash-btn me-2 ps-3 pe-2">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </a>
                                <div @click.prevent="newReturn" class="btn btn-info" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new return" style="font-weight: bold;">
                                    <i class="bi bi-plus-lg"></i> Add Return
                                </div>
                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Search by Bill No." @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="select_search_customer" :options="truncatedCustomers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="select_search_cashier" :options="cashiers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCashier" :searchable="true"
                                            placeholder="Created By" label="name" track-by="id" max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <Datepicker v-if="to_date && !from_date || to_date && from_date"
                                            v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />

                                        <Datepicker v-else v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>

                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Currency"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-2 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0"></div>

                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openAllReturnsFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="bills.length > 0">
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
                                <!-- Bills Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && search == null && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null">
                                    <!-- Icon for Empty Returns State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-arrow-repeat fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Returns State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Returns</h2>
                                    </div>

                                    <!-- Subtext for Empty Returns State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Customer returns will appear here.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (search != null || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null)">
                                    <!-- Icon for Empty Search Results -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Search Results -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Results Found</h2>
                                    </div>

                                    <!-- Subtext for Empty Search Results -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Try adjusting your search criteria.</p>
                                    </div>
                                </div>

                                <div v-if="bills.length > 0" class="row">

                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">BILL NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th>CURRENCY</th>
                                                    <th class="text-end">TOTAL</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index"
                                                    onmouseover="this.style.backgroundColor='#F2F4F4'; this.style.cursor='pointer';"
                                                    onmouseout="this.style.backgroundColor='#ffffff';">

                                                    <td class="py-2 ps-3" @click.prevent="editReturn(bill.id)">{{
                                                        bill.code
                                                        }}
                                                    </td>
                                                    <td v-if="bill.customer_id == null" class="py-2"
                                                        @click.prevent="editReturn(bill.id)">Walking Customer
                                                    </td>
                                                    <td v-else class="py-2" @click.prevent="editReturn(bill.id)">
                                                        {{ truncateText(bill.customer_name) }}</td>
                                                    <td class="py-2" @click.prevent="editReturn(bill.id)">
                                                        {{ truncateText(bill.cashier_name) }}</td>
                                                    <td class="py-2" @click.prevent="editReturn(bill.id)">
                                                        {{ formatDate(bill.date) }}</td>
                                                    <td class="py-2" @click.prevent="editReturn(bill.id)">
                                                        {{ bill.currency_name }}</td>
                                                    <td class="py-2 text-end" @click.prevent="editReturn(bill.id)">
                                                        {{ bill.formatted_minus_total }}</td>
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Print return button -->
                                                            <button v-if="bill.status == 4"
                                                                class="border btn btn-sm border-dark text-primary d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Print return"
                                                                @click.prevent="returnPrint(bill.id)">
                                                                <i class="p-2 bi bi-printer fs-3 text-dark"></i>
                                                            </button>

                                                            <!-- Delete return button -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete return"
                                                                @click.prevent="deleteReturn(bill.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </button>

                                                            <!-- Information button with a tooltip -->
                                                            <button v-if="bill.remark"
                                                                class="border btn btn-sm border-info text-info d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" :title="bill.remark">
                                                                <i class="p-2 bi bi-info-circle fs-5 text-info"></i>
                                                            </button>
                                                        </div>
                                                    </td>


                                                </tr>
                                                <tr v-if="search_select_currency != null && search_select_currency.id != 0"
                                                    class="mb-5 total-row">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">TOTAL</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfTotal
                                                        }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="bill in bills" :key="bill.id" style="margin-bottom: 20px;">
                                            <!-- Start a new table for each bill -->
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Bill No. -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Bill No.</td>
                                                        <td class="text-end">{{ bill.code }}</td>
                                                    </tr>

                                                    <!-- Customer -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Customer</td>
                                                        <td class="text-end">
                                                            <span v-if="bill.customer_id == null">Walking
                                                                Customer</span>
                                                            <span v-else>{{ truncateText(bill.customer_name) }}</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Created By -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Created By</td>
                                                        <td class="text-end">{{ truncateText(bill.cashier_name) }}</td>
                                                    </tr>

                                                    <!-- Date -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Date</td>
                                                        <td class="text-end">{{ formatDate(bill.date) }}</td>
                                                    </tr>

                                                    <!-- Currency -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Currency</td>
                                                        <td class="text-end">{{ bill.currency_name }}</td>
                                                    </tr>

                                                    <!-- Total -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Total</td>
                                                        <td class="text-end">{{ bill.formatted_minus_total }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <!-- Print Button -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Print return"
                                                                    v-if="bill.status == 4"
                                                                    @click.prevent="returnPrint(bill.id)">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                                </a>

                                                                <!-- Delete Button -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Delete return"
                                                                    @click.prevent="deleteReturn(bill.id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>

                                                                <!-- Info Button -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-placement="bottom" :title="bill.remark"
                                                                    v-if="bill.remark">
                                                                    <i class="p-2 bi bi-info-circle fs-5 text-info"></i>
                                                                </a>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Total Row if Currency Filter is Applied -->
                                        <div v-if="search_select_currency != null && search_select_currency.id != 0"
                                            class="mb-5 total-row">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <tr>
                                                        <td class="pt-4 text-gray-600 text-end fw-bold" colspan="6">
                                                            TOTAL</td>
                                                        <td class="pt-4 text-gray-600 text-end fw-bold">{{ totalOfTotal
                                                            }}</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="bills.length > 0" class="my-3 row align-items-center">
                                    <!-- Entries Dropdown Section -->
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <label class="mb-0 me-2">Show</label>
                                        <select name="kt_ecommerce_products_table_length"
                                            aria-controls="kt_ecommerce_products_table"
                                            class="w-auto mx-1 form-select form-select-sm form-select-solid"
                                            v-model="pageCount" @change="perPageChange">
                                            <option v-for="perPageCount in perPage" :key="perPageCount"
                                                :value="perPageCount">
                                                {{ perPageCount }}
                                            </option>
                                        </select>
                                        <label class="mb-0 ms-2">entries</label>
                                    </div>

                                    <!-- Pagination Info and Controls -->
                                    <div class="col-sm-6 d-flex justify-content-end align-items-center">
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)" class="page-link">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                                <template v-for="(page, index) in pagination.last_page">
                                                    <template
                                                        v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                                        <li class="paginate_button page-item" :key="index"
                                                            :class="pagination.current_page == page ? 'active' : ''">
                                                            <a href="javascript:void(0)" @click="setPage(page)"
                                                                class="page-link">{{ page }}</a>
                                                        </li>
                                                    </template>
                                                </template>
                                                <li class="paginate_button page-item next"
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page + 1)" class="page-link">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
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
        </template>

        <template #modal>
            <!-- All Returns Filter Modal -->
            <div class="modal fade" id="allReturnsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="allReturnsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                All Returns Filters</h5>
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
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Serach by Bill No."
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectCustomer" v-model="select_search_customer"
                                            :options="truncatedCustomers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectCashier" v-model="select_search_cashier"
                                            :options="cashiers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" @search-change="getCashier"
                                            :searchable="true" placeholder="Created By" label="name" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Datepicker id="modalFromDate"
                                            v-if="to_date && !from_date || to_date && from_date" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :upperLimit="to_date" />
                                        <Datepicker id="modalFromDate" v-else v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Datepicker id="modalToDate" v-model="to_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :lowerLimit="from_date" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectCurrency" v-model="search_select_currency"
                                            :options="currencies" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select Currency" label="code" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="allReturnsFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyAllReturnsFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="returnDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this return?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete return"
                                @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
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
import { Link, router, usePage } from "@inertiajs/vue3";
import moment from 'moment';
import Datepicker from 'vue3-datepicker'

import _ from 'lodash';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const bills = ref([]);
const emptyImageVal = ref(0);

const search = ref(null);
const search_order = ref({});
const from_date = ref(null);
const to_date = ref(null);
const select_search_customer = ref(null);
const select_search_cashier = ref(null);
const customers = ref([]);
const cashiers = ref([]);
const returnId = ref(null);

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';

const currencies = ref([]);
const search_select_currency = ref({});


const previousCurrency = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
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

const getCashier = async () => {
    try {
        const tableData = (await axios.get(route('user.multiselect.all'))).data;
        cashiers.value = tableData;
    } catch (error) {

    }
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

const openAllReturnsFilterModal = () => {
    loading_bar.value.start();
    $('#allReturnsFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyAllReturnsFilter = () => {
    $('#allReturnsFilterModal').modal('hide');
    reload();
};

const allReturnsFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    $('#allReturnsFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('return.bill.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_customer: search_order.value.customer_id,
                search_order_cashier: search_order.value.cashier_id,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                currency: search_select_currency.value?.id,
            },
        })
    ).data;

    bills.value = tableData.data;
    pagination.value = tableData.meta;

    if (bills.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const returnPrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('return.print', id), { responseType: "blob" });
        const url = window.URL.createObjectURL(print.data);
        window.open(url, "_blank");
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }
};

const editReturn = async (id) => {
    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        try {
            window.location.href = route("return.edit", id);
        } catch (error) {
            errorMessage(error);
        }
    } else {
        errorMessage('Access denied');
    }
};

const deleteReturn = async (id) => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        try {
            nextTick(() => {
                loading_bar.value.start();
            });

            returnId.value = id;

            $('#returnDeleteModal').modal('show');
            nextTick(() => {
                loading_bar.value.finish();
            });
        } catch (error) {
            nextTick(() => {
                loading_bar.value.finish();
            });
            convertValidationNotification(error);
        }
    } else {
        errorMessage('Access denied');
    }

}

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        await axios.delete(route("return.delete", returnId.value));
        successMessage('Return deleted successfully');

        $('#returnDeleteModal').modal('hide');
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

watch(select_search_customer, (newValue) => {
    if (newValue) {
        search_order.value.customer_id = newValue.id;
    } else {
        search_order.value.customer_id = null;
    }
    getSearch();
});

watch(select_search_cashier, (newValue) => {
    if (newValue) {
        search_order.value.cashier_id = newValue.id;
    } else {
        search_order.value.cashier_id = null;
    }
    getSearch();
});


watch(from_date, (newValue) => {
    search_order.value.from_date = newValue;
    getSearch();
})


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

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
}

const errorPrint = () => {
    errorMessage('The order payment is not done!');
};

const successMessage = (message) => {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

const errorMessage = (message) => {
    Swal.fire({
        icon: 'error',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

const totalOfTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.total.replace(/,/g, ''));
        return subTotal + Math.abs(amount);
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const truncateShortText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 24 ? text.substring(0, 24) + '...' : text;
    }
    return '';
};

const truncatedCustomers = computed(() => {
    return customers.value.map(customer => ({
        ...customer,
        truncatedName: truncateShortText(customer.name),
    }));
});

const getCurrency = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("currency.list"))).data.data;
        const allCurrenciesOption = { id: 0, code: 'All Currencies' };
        const updatedResponse = [allCurrenciesOption, ...response];

        currencies.value = updatedResponse;


        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const newReturn = () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('return.index'));
    } else {
        errorMessage('Access denied');
    }

}

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data.data;
        if (response.currency_id) {
            search_select_currency.value.id = response.currency_id;
            search_select_currency.value.code = response.currency_name;
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        previousCurrency.value = search_select_currency.value;
        page.value = 1;
        reload();

    } else {
        search_select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

onMounted(() => {
    getCurrency();
    getBusinessDetails();
    getCustomer();
    getCashier();
});

</script>

<style lang="scss" scoped></style>
