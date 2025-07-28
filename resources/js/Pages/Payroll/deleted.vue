<template>
    <AppLayout title="Deleted Payrolls">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Deleted Payrolls
                                </h1>

                            </div>

                            <div class="col-6 d-flex justify-content-end">
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="codeFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_employee" :options="employees.map(employee => ({
                                            ...employee,
                                            searchableText: employee.company ? `${employee.name} - ${truncateEmployeeText(employee.company)}` : employee.name
                                        }))" class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Employee"
                                            label="searchableText" track-by="id" max-height="200">
                                            <template #option="{ option }">
                                                {{ option.company ? `${option.name} - ${option.company}` : option.name
                                                }}
                                            </template>
                                        </Multiselect>
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Datepicker v-if="to_date && !from_date || to_date && from_date"
                                            v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />

                                        <Datepicker v-else v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" />

                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-4 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-1 mb-xl-0">
                                    </div>
                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openPayrollFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="payrolls.length > 0">
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
                                <!-- Payroll Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && codeFilter == '' && search_category == null && search_employee == null && from_date == null && to_date == null">

                                    <!-- Icon for No Deleted Payrolls State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-file-earmark-x fs-1"></i>
                                    </div>

                                    <!-- Heading for No Deleted Payrolls State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Deleted Payrolls</h2>
                                    </div>

                                    <!-- Subtext for No Deleted Payrolls State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your deleted payrolls will appear here once
                                            available.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (codeFilter != '' || search_category != null || search_employee != null || from_date != null || to_date != null)">

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
                                        <p class="text-gray-600 fs-5">Try adjusting your filters to find the payrolls
                                            you're looking for.</p>
                                    </div>
                                </div>

                                <div class="row" v-if="payrolls.length > 0">
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Code</th>
                                                    <th>Employee</th>
                                                    <th>Date</th>
                                                    <th>Currency</th>
                                                    <th class="text-end pe-3">Amount</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="payroll in payrolls" class="cursor-pointer">
                                                    <td class="py-2 ps-3" @click.prevent="viewPayroll(payroll.id)">{{
                                                        payroll.code }}</td>
                                                    <td v-if="payroll.supplier_id != null" class="py-2 ps-3"
                                                        @click.prevent="viewPayroll(payroll.id)">{{
                                                            truncateText(payroll.supplier_name) }}</td>
                                                    <td v-else class="py-2 ps-3"
                                                        @click.prevent="viewPayroll(payroll.id)">
                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="viewPayroll(payroll.id)">{{
                                                        formatDate(payroll.date) }}</td>
                                                    <td class="py-2 ps-3" @click.prevent="viewPayroll(payroll.id)">{{
                                                        payroll.currency_name }}</td>
                                                    <td class="py-2 text-end pe-3"
                                                        @click.prevent="viewPayroll(payroll.id)">
                                                        {{ payroll.formatted_amount }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="payroll in payrolls" :key="payroll.id"
                                            class="p-3 mb-4 border rounded">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Payroll Code -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Code</td>
                                                        <td class="text-end">{{ payroll.code }}</td>
                                                    </tr>

                                                    <!-- Employee Name -->
                                                    <tr v-if="payroll.supplier_id != null">
                                                        <td class="text-gray-400 text-uppercase">Employee</td>
                                                        <td class="text-end">{{ truncateText(payroll.supplier_name) }}
                                                        </td>
                                                    </tr>
                                                    <tr v-else>
                                                        <td class="text-gray-400 text-uppercase">Employee</td>
                                                        <td class="text-end">N/A</td>
                                                    </tr>

                                                    <!-- Date -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Date</td>
                                                        <td class="text-end">{{ formatDate(payroll.date) }}</td>
                                                    </tr>

                                                    <!-- Currency -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Currency</td>
                                                        <td class="text-end">{{ payroll.currency_name }}</td>
                                                    </tr>

                                                    <!-- Amount -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Amount</td>
                                                        <td class="text-end">{{ payroll.formatted_amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="payrolls.length > 0" class="my-3 row align-items-center">

                                    <!-- Entries Dropdown Section -->
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <label class="mb-0 me-2">Show</label>
                                        <select name="kt_payrolls_table_length" aria-controls="kt_payrolls_table"
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
                                            id="kt_payrolls_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <!-- Previous Button -->
                                                <li class="paginate_button page-item previous"
                                                    :class="{ 'disabled': pagination.current_page == 1 }">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)" class="page-link">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>

                                                <!-- Page Number Buttons -->
                                                <template v-for="(page, index) in pagination.last_page" :key="index">
                                                    <template
                                                        v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                                        <li class="paginate_button page-item"
                                                            :class="{ 'active': pagination.current_page == page }">
                                                            <a href="javascript:void(0)" @click="setPage(page)"
                                                                class="page-link">{{ page }}</a>
                                                        </li>
                                                    </template>
                                                </template>

                                                <!-- Next Button -->
                                                <li class="paginate_button page-item next"
                                                    :class="{ 'disabled': pagination.current_page == pagination.last_page }">
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
            <!-- Payroll Filter Modal -->
            <div class="modal fade" id="payrollFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="payrollFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Deleted Payroll Filters</h5>
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
                                        <input id="modalSearch" v-model="codeFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectEmployee" v-model="search_employee" :options="employees.map(employee => ({
                                            ...employee,
                                            company: truncateEmployeeText(employee.company),
                                            searchableText: employee.company ? `${employee.name} - ${truncateEmployeeText(employee.company)}` : employee.name
                                        }))" class="z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Employee"
                                            label="searchableText" track-by="id" max-height="200">
                                            <template #option="{ option }">
                                                {{ option.company ? `${option.name} - ${option.company}` : option.name
                                                }}
                                            </template>
                                        </Multiselect>
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
                                        <button @click="payrollFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyPayrollFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Restore Confirmation Modal -->
            <div class="modal fade" id="payrollRestoreModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Restore</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to restore this payroll?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal" @click="closeRestoreModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-info" style="font-weight: bold"
                                @click.prevent="restorePayroll(selectedPayrollId)">
                                <i class="bi bi-arrow-repeat"></i>
                                RESTORE
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, watch, nextTick } from "vue";
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import { Link } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'

import _ from 'lodash';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const payrolls = ref([]);
const emptyImageVal = ref(0);
const categories = ref([]);
const employees = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_edit_category = ref({});
const select_employee = ref([]);
const edit_select_employee = ref([]);
const select_edit_unit = ref({});
const emailIndex = ref(1);
const phoneIndex = ref(1);
const selectedPayrollId = ref(null);

const product = ref({});
const payroll = ref({});
const edit_payroll = ref({});
const employeeData = ref({});

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_category = ref(null);
const search_category_id = ref(null);
const search_employee = ref(null);
const search_supplier_id = ref(null);
const search_date = ref(null);
const from_date = ref(null);
const to_date = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const loading_bar = ref(null);

const date = ref(new Date());
const edit_date = ref(new Date());
const placeholderText = 'DD/MM/YYYY';
const business_details = ref({});
const currencies = ref([]);
const select_currency = ref({});
const edit_select_currency = ref({});

const payroll_image = ref(null);
const edit_payroll_image = ref(null);

const search_select_currency = ref({});

const sorting_value = ref(0);


const search_order = ref({});
const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

const setPage = (new_page) => {
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

const convertValidationError = (err) => {
    resetValidationErrors(err);
    if (!(err.response && err.response.data)) return;
    const { message, errors } = err.response.data;
    validationMessage.value = message;
    if (errors) {
        for (const error in errors) {
            validationErrors.value[error] = errors[error][0];
        }
    }
};

const openPayrollFilterModal = () => {
    loading_bar.value.start();
    $('#payrollFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyPayrollFilter = () => {
    $('#payrollFilterModal').modal('hide');
    reload();
};

const payrollFilterModalClear = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_category.value = null;
    search_employee.value = null;
    from_date.value = null;
    to_date.value = null;
    getCurrency();
    await getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#payrollFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('payrolls.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_code: codeFilter.value,
                search_category: search_category_id.value,
                search_employee: search_supplier_id.value,
                search_date: search_date.value,
                search_from_date: search_order.value.from_date,
                search_to_date: search_order.value.to_date,
                currency: search_select_currency.value?.id,
                sorting_value: sorting_value.value,
            },
        })
    ).data;

    payrolls.value = tableData.data;
    pagination.value = tableData.meta;

    if (payrolls.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

watch(search_category, (newValue) => {
    if (newValue) {
        search_category_id.value = newValue.id;
    } else {
        search_category_id.value = null;
    }
    getSearch();
});

watch(search_employee, (newValue) => {
    if (newValue) {
        search_supplier_id.value = newValue.id;
    } else {
        search_supplier_id.value = null;
    }
    getSearch();
});

watch(search_date, (newValue) => {
    search_date.value = newValue;
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

const getCategories = async () => {
    try {
        const response = (await axios.get(route('payrolls.category.multiselect.all'))).data;
        categories.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployees = async () => {
    try {
        const response = (await axios.get(route('employee.multiselect.all'))).data;
        employees.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const savePayroll = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        if (select_category.value != null) {
            payroll.value.payroll_category_id = select_category.value.id;
        }

        if (select_employee.value != null) {
            payroll.value.supplier_id = select_employee.value.id;
        }

        if (select_currency.value != null) {
            payroll.value.currency_id = select_currency.value.id;
        }

        if (date.value != null) {
            payroll.value.date = date.value;
        }

        const response = await axios.post(route('payroll.store'), payroll.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Payroll added successfully!');

        payroll.value = {};
        payroll_image.value = null;
        select_category.value = [];
        select_employee.value = [];

        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        $('#payrollModal').modal('hide');
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
    }
};

const viewPayroll = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('payroll.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }

};

const editPayroll = async (id) => {
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.supplier_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.supplier_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }

        if (edit_payroll.value.date) {
            edit_date.value = new Date(edit_payroll.value.date);
        }
        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        $('#payrollUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }
};

const loadData = async (id) => {
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.supplier_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.supplier_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }

        if (edit_payroll.value.date) {
            edit_date.value = new Date(edit_payroll.value.date);
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const updatePayroll = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        if (edit_select_category.value != null) {
            edit_payroll.value.payroll_category_id = edit_select_category.value.id;
        }

        if (edit_select_employee.value != null) {
            edit_payroll.value.supplier_id = edit_select_employee.value.id;
        } else {
            edit_payroll.value.supplier_id = null;
        }

        if (edit_select_currency.value != null) {
            edit_payroll.value.currency_id = edit_select_currency.value.id;
        }

        if (edit_date.value != null) {
            payroll.value.date = edit_date.value;
        }

        if (edit_payroll_image.value != null) {
            edit_payroll.value.image = edit_payroll_image.value;
        } else {
            edit_payroll.value.image = null;
        }

        const response = await axios.post(route("payroll.update", edit_payroll.value.id), edit_payroll.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Payroll updated successfully!');

        edit_payroll.value = {};
        edit_payroll_image.value = null;

        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        $('#payrollUpdateModal').modal('hide');
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
    }
};

const removeImage = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        await axios.get(route("payroll.image.remove", id)).data;

        loadData(id);

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

const selectImageRemove = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;
        payroll.value.image_url = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

function showRestoreModal(payrollId) {

    selectedPayrollId.value = payrollId;
    $("#payrollRestoreModal").modal("show");

}

function closeRestoreModal() {
    $("#payrollRestoreModal").modal("hide");
}

const restorePayroll = async (payrollId) => {
    try {


        await axios.get(route("payrolls.restore", payrollId));
        closeRestoreModal();
        successMessage('Payroll restored successfully');
        reload();


    } catch (error) {
    }
}

const showEmployeeModal = async () => {
    employeeData.value = {};
    $("#employeeModal").modal("show");
};

const saveNewEmployee = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const response = await axios.post(route("employee.all.store"), employeeData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New employee registration is successful');
            closeEmployeeModal();
            getEmployees();
        }


        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
    }
};

const closeEmployeeModal = async () => {
    $("#employeeModal").modal("hide");
};

const clearFilters = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_category.value = null;
    search_employee.value = null;
    from_date.value = null;
    to_date.value = null;
    getCurrency();
    await getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
}

const getCurrency = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("currency.list"))).data;
        currencies.value = response.data;

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
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            search_select_currency.value.id = business_details.value.currency_id;
            search_select_currency.value.code = business_details.value.currency_name;
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

const setSortingValue = async (value) => {
    sorting_value.value = value;
    getSearch();
};

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const truncateEmployeeText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 25 ? text.substring(0, 25) + '...' : text;
    }
    return '';
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        page.value = 1;
        reload();
    } else {
        page.value = 1;
        search_select_currency.value = {};
        getCurrency();
        getBusinessDetails();
    }
});

onMounted(() => {
    // reload();
    getCurrency();
    getBusinessDetails();
    getCategories();
    getEmployees();
});

watch(() => {
    if (stock_in.value == true) {
        stock_out.value = false;
    } else if (stock_out.value == true) {
        stock_in.value = false;
    }
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.restore-btn {
    color: green;
}
</style>
