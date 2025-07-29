<template>
    <AppLayout title="Credit Bills">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <h1 class="main-header-text">
                                Credit&nbsp;Bills&nbsp;
                            </h1>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">

                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Code" @keyup="debouncedGetSearch" />
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
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-2 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filter">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0"></div>
                                    <div v-if="bills.length > 0"
                                        class="mb-2 col-xl-2 col-xxl-1 ps-0 d-flex justify-content-end align-self-end">
                                        <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                            <label><select name="kt_ecommerce_products_table_length"
                                                    aria-controls="kt_ecommerce_products_table"
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
                                                @click.prevent="openBillsFilterModal">
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
                                    v-if="emptyImageVal == 1 && orderType == 0 && search == null && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-credit-card fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No any credit bills</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">Credit Bills will appear here</p>
                                    </div>
                                </div>
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (orderType != 0 || search != null || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null)">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12 mb-15">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No result found</h1>
                                    </div>
                                </div>
                                <div v-if="bills.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th></th>
                                                    <th class="ps-3">BILL NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-end">TOTAL</th>
                                                    <th class="text-end">PAID AMOUNT</th>
                                                    <th class="text-end pe-2">DUE AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="(bill, index) in bills" :key="index"
                                                    onmouseover="this.style.backgroundColor='#F2F4F4'; this.style.cursor='pointer';"
                                                    onmouseout="this.style.backgroundColor='#ffffff';">
                                                    <td class="py-1 text-center">
                                                        <a v-if="bill.status == 1 || bill.status == 2"
                                                            href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="bottom" title="Print credit bill"
                                                            @click.prevent="printCreditBill(bill.id)"
                                                            class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                class="bi bi-printer fs-3"></i></a>
                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="editCreditBill(bill.id)">{{
                                                        bill.code }}</td>
                                                    <td v-if="bill.customer_id == 0 || bill.customer_id == null"
                                                        class="py-2" @click.prevent="editCreditBill(bill.id)">Walking
                                                        Customer</td>
                                                    <td v-else @click.prevent="editCreditBill(bill.id)">
                                                        {{ truncateText(bill.customer_name) }}</td>
                                                    <td class="py-2" @click.prevent="editCreditBill(bill.id)">
                                                        {{ truncateText(bill.cashier_name) }}</td>
                                                    <td class="py-2" @click.prevent="editCreditBill(bill.id)">{{
                                                        formatDate(bill.date) }}</td>
                                                    <td class="py-2 text-center"
                                                        @click.prevent="editCreditBill(bill.id)">{{
                                                            bill.currency_name }}</td>
                                                    <td class="py-2 text-end" @click.prevent="editCreditBill(bill.id)">
                                                        {{
                                                            bill.formatted_total }}</td>
                                                    <td class="py-2 text-end" @click.prevent="editCreditBill(bill.id)">
                                                        {{
                                                            bill.formatted_customer_paid }}</td>
                                                    <td class="py-2 text-end pe-2"
                                                        @click.prevent="editCreditBill(bill.id)">
                                                        {{ (bill.total -
                                                            bill.customer_paid).toLocaleString('en-US', {
                                                                minimumFractionDigits:
                                                                    2
                                                            }) }}</td>
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
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfPaidTotal
                                                    }}</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end pe-2 fw-bold">{{
                                                        totalOfDueTotal
                                                    }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="(bill, index) in bills" :key="index">
                                                    <td class="table-responsive-width"
                                                        @click.prevent="editReturn(bill.id)">

                                                        <div class="row">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase ">
                                                            </div>
                                                            <div class="col-8 right-side text-end">
                                                                <span><a v-if="bill.status == 1 || bill.status == 2"
                                                                        href="javascript:void(0)" data-toggle="tooltip"
                                                                        data-placement="bottom"
                                                                        title="Print credit bill"
                                                                        @click.prevent="printCreditBill(bill.id)"
                                                                        class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                            class="bi bi-printer fs-3"></i></a></span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider"
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase ">
                                                                BILL NO.</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ bill.code }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row row-divider "
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CUSTOMER</div>
                                                            <div v-if="bill.customer_id == null"
                                                                class="col-8 right-side text-end">
                                                                <span>Walking Customer</span>
                                                            </div>
                                                            <div v-else class="col-8 right-side text-end">
                                                                <span class="py-2">{{ truncateText(bill.customer_name)
                                                                    }} </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                USER</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>
                                                                    {{ truncateText(bill.cashier_name) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DATE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ formatDate(bill.date) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CURRENCY</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ bill.currency_name }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider"
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                TOTAL</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_minus_total }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                PAID</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    bill.formatted_customer_paid }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider"
                                                            @click.prevent="editCreditBill(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DUE</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ (bill.total -
                                                                    bill.customer_paid).toLocaleString('en-US', {
                                                                        minimumFractionDigits:
                                                                            2
                                                                    }) }}</span>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="hr-no-margin"> -->
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div v-if="bills.length > 0" class="my-3 row ps-1 ps-md-0">

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
            <!-- Bills Filter Modal -->
            <div class="modal fade" id="billsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="billsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Credit Bills Filters</h5>
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
                                        <label for="modalOrderStatus" class="text-gray-600 col-form-label">CODE</label>
                                        <input id="modalOrderStatus" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCustomer"
                                            class="text-gray-600 col-form-label">CUSTOMER</label>
                                        <Multiselect id="modalSelectCustomer" v-model="select_search_customer"
                                            :options="truncatedCustomers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCashier" class="text-gray-600 col-form-label">CREATED
                                            BY</label>
                                        <Multiselect id="modalSelectCashier" v-model="select_search_cashier"
                                            :options="cashiers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" @search-change="getCashier"
                                            :searchable="true" placeholder="Created By" label="name" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalFromDate" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker id="modalFromDate"
                                            v-if="to_date && !from_date || to_date && from_date" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :upperLimit="to_date" />
                                        <Datepicker id="modalFromDate" v-else v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText" />
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
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCurrency"
                                            class="text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect id="modalSelectCurrency" v-model="search_select_currency"
                                            :options="currencies" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="code" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="billsFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyBillsFilter" class="btn btn-info ms-4 fw-bold">
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
import { onMounted, ref, watch, computed, nextTick, onBeforeMount } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Multiselect from 'vue-multiselect';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'

import _ from 'lodash';

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
const orderType = ref(0);

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

const openBillsFilterModal = () => {
    loading_bar.value.start();
    $('#billsFilterModal').modal('show');
    loading_bar.value.finish();
};
const applyBillsFilter = () => {
    $('#billsFilterModal').modal('hide');
    reload();
};
const billsFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderType.value = 0;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    $('#billsFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('credit.order.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_customer: search_order.value.customer_id,
                search_order_cashier: search_order.value.cashier_id,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_type: search_order.value.orderType,
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

const getCreditOrders = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        // const tableData = (await axios.get(route('credit.order.all'))).data;
        // bills.value = tableData.data;
        // pagination.value = tableData.meta;
        const tableData = await axios.get(route('credit.order.all')).then((response) => {
            bills.value = response.data.data;
            pagination.value = response.data.meta;
        });

        if (bills.value.length > 0) {
            emptyImageVal.value = 0;
        } else {
            emptyImageVal.value = 1;
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
};

const editCreditBill = async (id) => {
    try {

        window.location.href = route("credit.edit", id);

    } catch (error) {
        errorMessage(error);
    }
};

const printCreditBill = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('payment.print', id), { responseType: "blob" });
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

watch(orderType, (newValue) => {
    search_order.value.orderType = newValue;
    getSearch();
});

const totalOfTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.total.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfPaidTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.customer_paid.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfDueTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.total.replace(/,/g, '')) - parseFloat(bill.customer_paid.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

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

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderType.value = 0;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
}

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
