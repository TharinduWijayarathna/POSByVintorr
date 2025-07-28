<template>
    <AppLayout title="Deleted Purchase Orders">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Deleted Purchase Orders
                                </h1>

                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Purchase Order No." @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input id="header_fields" v-model="header_fields" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Parameters"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="select_search_supplier" :options="truncatedSuppliers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getSupplier" :searchable="true"
                                            placeholder="Select Supplier" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <!-- <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="text-gray-600 col-form-label">CREATED BY</div>
                                        <Multiselect v-model="select_search_cashier" :options="cashiers"
                                            class="z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCashier" :searchable="true"
                                            placeholder="Created By" label="name" track-by="id" max-height="200" />
                                    </div> -->
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
                                    <div class="mb-2 col-xl-3 col-xxl-1 mb-xl-0 pe-xxl-0">
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
                                    <!-- <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0"></div> -->

                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openPurchaseOrderFilterModal">
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
                                                        v-model="pageCount" @change="perPageChangeMobile">
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
                                    v-if="bills.length < 1 && search == '' && select_search_supplier == null && select_search_cashier == null && from_date == null && to_date == null">

                                    <!-- Icon for No Deleted Purchase Orders State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-archive fs-1"></i>
                                    </div>

                                    <!-- Heading for No Deleted Purchase Orders State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Deleted Purchase Orders</h2>
                                    </div>

                                    <!-- Subtext for No Deleted Purchase Orders State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your deleted purchase orders will appear here.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="bills.length == 0 && (search != '' || select_search_supplier != null || select_search_cashier != null || from_date != null || to_date != null)">

                                    <!-- Icon for No Search Results -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>

                                    <!-- Heading for No Search Results -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Result Found</h2>
                                    </div>

                                    <!-- Subtext for No Search Results -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Try adjusting your filters or search criteria to
                                            find results.</p>
                                    </div>
                                </div>

                                <div class="row" v-if="bills.length > 0">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <!-- <th></th> -->
                                                    <!-- <th class="text-center ps-3">STATUS</th> -->
                                                    <th class="pe-0 text-end i-icon-col"></th>
                                                    <th class="ps-3">PURCHASE ORDER NO.</th>
                                                    <th>SUPPLIER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-end pe-3">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index">
                                                    <td class="py-2 pe-0 text-end i-icon-col">
                                                        <i v-if="bill.ref_no"
                                                            class="pl-3 cursor-pointer bi bi-info-circle"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            :title=bill.ref_no></i>
                                                    </td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        class="py-2 cursor-pointer ps-3">{{
                                                            bill.code }}</td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        v-if="bill.supplier_id == null" class="py-2 cursor-pointer">
                                                        Walking
                                                        Supplier</td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)" v-else
                                                        class="py-2 cursor-pointer">{{ truncateText(bill.supplier_name)
                                                        }}</td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        v-if="bill.cashier != null" class="py-2 cursor-pointer">{{
                                                            truncateText(bill.cashier_name) }}
                                                    </td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        v-if="bill.cashier == null" class="py-2 cursor-pointer"></td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        class="py-2 cursor-pointer">{{
                                                            formatDate(bill.date) }}
                                                    </td>
                                                    <td class="py-2 text-center"
                                                        @click.prevent="editPurchaseOrder(bill.id)">
                                                        {{ bill.currency_name }}
                                                    </td>
                                                    <td @click.prevent="editPurchaseOrder(bill.id)"
                                                        class="py-2 cursor-pointer text-end pe-3">{{
                                                            bill.formatted_total }}
                                                    </td>
                                                </tr>

                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="bill in bills" :key="bill.id" class="p-3 mb-4 border rounded">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- PO No. -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Purchase Order No.</td>
                                                        <td class="text-end">{{ bill.code }}</td>
                                                    </tr>

                                                    <!-- Supplier -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Supplier</td>
                                                        <td class="text-end">
                                                            <span v-if="bill.supplier_id == null">Walking
                                                                Supplier</span>
                                                            <span v-else>{{ truncateText(bill.supplier_name) }}</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Created By -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Created By</td>
                                                        <td class="text-end">
                                                            <span v-if="bill.cashier != null">{{
                                                                truncateText(bill.cashier_name) }}</span>
                                                        </td>
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
                                                        <td class="text-end">{{ bill.formatted_total }}</td>
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
                                        <select name="kt_ecommerce_orders_table_length"
                                            aria-controls="kt_ecommerce_orders_table"
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
                                            id="kt_ecommerce_orders_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <!-- Previous Button -->
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)" class="page-link">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>

                                                <!-- Page Numbers -->
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

                                                <!-- Next Button -->
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
            <!-- Purchase Order Filter Modal -->
            <div class="modal fade" id="purchaseOrderFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="purchaseOrderFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Deleted PO Filters</h5>
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
                                            class="form-control form-control-sm"
                                            placeholder="Search by Purchase Order No." @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="header_fields" v-model="header_fields" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Parameters"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectSupplier" v-model="select_search_supplier"
                                            :options="truncatedSuppliers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Supplier" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <!-- <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCashier" class="text-gray-600 col-form-label">CREATED BY</label>
                                        <Multiselect id="modalSelectCashier" v-model="select_search_cashier" :options="cashiers"
                                            class="z__index" :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCashier" :searchable="true" placeholder="Created By" label="name"
                                            track-by="id" max-height="200" />
                                    </div>
                                </div> -->
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
                                            placeholder="Select Currency" label="code" track-by="id" max-height="200"
                                            @change="getCurrencySearch" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="purchaseOrderFilterModalClear"
                                            class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyPurchaseOrderFilter" class="btn btn-info ms-4 fw-bold">
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
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this purchase order?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
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
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'
import { Link } from "@inertiajs/vue3";

import _ from 'lodash';

const datePickerFormat = 'dd-MM-yyyy';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const bills = ref([]);

const header_fields = ref(null);
const search = ref(null);
const search_order = ref({});
const from_date = ref(null);
const to_date = ref(null);
const select_search_supplier = ref(null);
const select_search_cashier = ref(null);
const suppliers = ref([]);
const cashiers = ref([]);
const orderStatus = ref(0);

const purchaseOrder = ref({});

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';

const currencies = ref([]);
const search_select_currency = ref({});

const sorting_value = ref(0);


const previousCurrency = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

const getSupplier = async () => {
    try {
        const tableData = (await axios.get(route('supplier.multiselect.all'))).data;
        suppliers.value = tableData;

        // Adding a walking supplier
        const newSupplier = { id: 0, name: "Walking Supplier" };
        suppliers.value.push(newSupplier);
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

const perPageChange = async (perPageCount) => {
    page.value = 1;
    pageCount.value = perPageCount;
    reload();
};

const perPageChangeMobile = async () => {
    page.value = 1;
    reload();
};

const openPurchaseOrderFilterModal = () => {
    loading_bar.value.start();
    $('#purchaseOrderFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyPurchaseOrderFilter = () => {
    $('#purchaseOrderFilterModal').modal('hide');
    reload();
};

const purchaseOrderFilterModalClear = async () => {
    search.value = "";
    select_search_supplier.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
    page.value = 1;
    header_fields.value = null;
    getCurrency();
    getBusinessDetails();
    $('#purchaseOrderFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('purchaseOrder.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_supplier: search_order.value.supplier_id,
                search_order_cashier: search_order.value.cashier_id,
                search_from_date: search_order.value.from_date,
                search_to_date: search_order.value.to_date,
                currency: search_select_currency.value?.id,
                sorting_value: sorting_value.value,
                header_fields: header_fields.value,
            },
        })
    ).data;

    bills.value = tableData.data;
    pagination.value = tableData.meta;
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const historyPrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('purchaseOrder.print', id), { responseType: "blob" });
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

const deleteInvoice = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("purchaseOrder.delete.confirm", id))).data;
        purchaseOrder.value = response;

        $('#deleteModal').modal('show');
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

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const response = (await axios.delete(route("purchaseOrder.delete", purchaseOrder.value.id))).data;
        successMessage('PurchaseOrder deleted successfully!');

        $('#deleteModal').modal('hide');
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        previousCurrency.value = search_select_currency.value;
        page.value = 1;
        reload();
    } else {
        search_select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

const editPurchaseOrder = async (id) => {

    try {
        const response = await axios.get(route('purchaseOrder.edit', id));
        if (response.data.status == 1) {
            window.location.href = route('purchaseOrder.load', response.data.id);
        }
    } catch (error) {
        errorMessage(error);
    }

};

watch(select_search_supplier, (newValue) => {
    if (newValue) {
        search_order.value.supplier_id = newValue.id;
    } else {
        search_order.value.supplier_id = null;
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

watch(orderStatus, (newValue) => {
    search_order.value.orderStatus = newValue;
    getSearch();
});

watch(from_date, (newValue) => {
    search_order.value.from_date = newValue;
    getSearch();
});

function clearFilters() {
    search.value = "";
    select_search_supplier.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
    page.value = 1;
    header_fields.value = null;
    getCurrency();
    getBusinessDetails();
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

const truncatedSuppliers = computed(() => {
    return suppliers.value.map(supplier => ({
        ...supplier,
        truncatedName: truncateShortText(supplier.name),
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        page.value = 1;
        reload();
    } else {
        page.value = 1;
        reload();
    }
});

const setSortingValue = async (value) => {
    sorting_value.value = value;
    getSearch();
};

onMounted(() => {
    clearFilters();
    getCurrency();
    getBusinessDetails();
    getSupplier();
    getCashier();

});

</script>

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.i-icon-col {
    width: 30px;
}
</style>
