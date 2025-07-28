<template>
    <AppLayout title="Deleted Expenses">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div
                                class="pb-0 mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center pb-sm-3">

                                <h1 class="main-header-text">
                                    View Deleted Expenses
                                </h1>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <input v-model="codeFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_category" :options="categories"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Category"
                                            label="name" track-by="id" max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-3 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_supplier" :options="suppliers.map(supplier => ({
                                            ...supplier,
                                            company: truncateSupplierText(supplier.company),
                                            searchableText: truncateSupplierText(supplier.company ? `${supplier.name} - ${supplier.company}` : supplier.name)
                                        }))" class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Supplier"
                                            label="searchableText" track-by="id" max-height="200">
                                            <template #option="{ option }">
                                                {{ truncateSupplierText(option.company ? `${option.name} -
                                                ${option.company}` : option.name)
                                                }}
                                            </template>
                                        </Multiselect>
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
                                            :clear-on-select="false" :searchable="true" placeholder="Currency"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-2 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-1 mb-xl-0">
                                    </div>
                                    <div v-if="expenses.length > 0"
                                        class="mb-2 col-xl-2 col-xxl-1 ps-0 d-flex justify-content-end align-self-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm h-100 ps-3 pe-2" type="button"
                                                id="dropdownMenuClickableInside" data-bs-toggle="dropdown"
                                                data-bs-auto-close="outside" aria-expanded="false"
                                                style="background-color: #f0f0f0;">
                                                <i class=" bi bi-three-dots fs-2"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableInside">
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <div class="btn-group dropstart">
                                                            <button type="button"
                                                                class="py-0 btn btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                Sort By
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li data-bs-toggle="dropdown">
                                                                    <a class="dropdown-item"
                                                                        @click="setSortingValue(1)">Code</a>
                                                                </li>
                                                                <li data-bs-toggle="dropdown">
                                                                    <a class="dropdown-item"
                                                                        @click="setSortingValue(2)">Date</a>
                                                                </li>
                                                                <li data-bs-toggle="dropdown">
                                                                    <a class="dropdown-item"
                                                                        @click="setSortingValue(3)">Total (Min -
                                                                        Max)</a>
                                                                </li>
                                                                <li data-bs-toggle="dropdown">
                                                                    <a class="dropdown-item"
                                                                        @click="setSortingValue(4)">Total (Max -
                                                                        Min)</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <div class="btn-group dropstart">
                                                            <button type="button"
                                                                class="py-0 btn btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                Table Rows
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li v-for="perPageCount in perPage" :key="perPageCount"
                                                                    data-bs-toggle="dropdown">
                                                                    <a class="dropdown-item"
                                                                        @click="perPageChange(perPageCount)" href="#">
                                                                        {{ perPageCount }}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openExpenseFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="expenses.length > 0">
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
                                <!-- Expense Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && codeFilter == '' && search_category == null && search_supplier == null && from_date == null && to_date == null">
                                    <!-- Icon for Empty State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-receipt fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Deleted Expenses</h2>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your deleted expenses will appear here.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (codeFilter != '' || search_category != null || search_supplier != null || from_date != null || to_date != null)">
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

                                <div class="row" v-if="expenses.length > 0">
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Code</th>
                                                    <th>Category</th>
                                                    <th>Supplier</th>
                                                    <th>Date</th>
                                                    <th>Currency</th>
                                                    <th class="text-end pe-3">Amount</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="expense in expenses" class="cursor-pointer">
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        expense.code }}</td>
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        truncateText(expense.category_name) }}</td>
                                                    <td v-if="expense.supplier_id != null" class="py-2 ps-3"
                                                        @click.prevent="viewExpense(expense.id)">{{
                                                            truncateText(expense.supplier_name) }}</td>
                                                    <td v-else class="py-2 ps-3"
                                                        @click.prevent="viewExpense(expense.id)">
                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        formatDate(expense.date) }}</td>
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        expense.currency_name }}</td>
                                                    <td class="py-2 text-end pe-3"
                                                        @click.prevent="viewExpense(expense.id)">
                                                        {{
                                                            expense.formatted_amount }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="expense in expenses">
                                                    <td class="table-responsive-width">
                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CODE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    expense.code }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CATEGORY</div>
                                                            <div class="col-8 right-side text-end"
                                                                v-if="expense.supplier_id != null">
                                                                <span>{{
                                                                    truncateText(expense.category_name) }}</span>
                                                            </div>
                                                            <div v-else class="col-8 right-side text-end">
                                                                <span> </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                Supplier</div>
                                                            <div class="col-8 right-side text-end"
                                                                v-if="expense.supplier_id != null">
                                                                <span>{{
                                                                    truncateText(expense.supplier_name) }}</span>
                                                            </div>
                                                            <div v-else class="col-8 right-side text-end">
                                                                <span> </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DATE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    formatDate(expense.date) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CURRENCY</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    expense.currency_name }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider"
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                AMOUNT</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{
                                                                    expense.formatted_amount }}</span>
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
                                <div v-if="expenses.length > 0" class="my-3 row ps-1 ps-md-0">

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
        </template>

        <template #modal>
            <!-- Expense Filter Modal -->
            <div class="modal fade" id="expenseFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="expenseFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Expense Filters</h5>
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
                                        <input id="modalSearch" v-model="codeFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCategory"
                                            class="text-gray-600 col-form-label">CATEGORY</label>
                                        <Multiselect id="modalSelectCategory" v-model="search_category"
                                            :options="categories" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select Category" label="name" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectSupplier"
                                            class="text-gray-600 col-form-label">SUPPLIER</label>
                                        <Multiselect id="modalSelectSupplier" v-model="search_supplier" :options="suppliers.map(supplier => ({
                                            ...supplier,
                                            company: truncateSupplierText(supplier.company),
                                            searchableText: truncateSupplierText(supplier.company ? `${supplier.name} - ${supplier.company}` : supplier.name)
                                        }))" class="z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Supplier"
                                            label="searchableText" track-by="id" max-height="200">
                                            <template #option="{ option }">
                                                {{ truncateSupplierText(option.company ? `${option.name} -
                                                ${option.company}` : option.name)
                                                }}
                                            </template>
                                        </Multiselect>
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
                                        <Datepicker id="modalFromDate" v-else="to_date && from_date" v-model="from_date"
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
                                        <button @click="expenseFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyExpenseFilter" class="btn btn-info ms-4 fw-bold">
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
            <div class="modal fade" id="expenseRestoreModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Restore</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to restore this expense?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal" @click="closeRestoreModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-info" style="font-weight: bold"
                                @click.prevent="restoreExpense(selectedExpenseId)">
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

const expenses = ref([]);
const emptyImageVal = ref(0);
const categories = ref([]);
const suppliers = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_edit_category = ref({});
const select_supplier = ref([]);
const edit_select_supplier = ref([]);
const select_edit_unit = ref({});
const emailIndex = ref(1);
const phoneIndex = ref(1);
const selectedExpenseId = ref(null);

const product = ref({});
const expense = ref({});
const edit_expense = ref({});
const supplierData = ref({});

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_category = ref(null);
const search_category_id = ref(null);
const search_supplier = ref(null);
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

const expense_image = ref(null);
const edit_expense_image = ref(null);

const search_select_currency = ref({});
const previousCurrency = ref({});

const sorting_value = ref(0);



const search_expense = ref({});

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

const openExpenseFilterModal = () => {
    loading_bar.value.start();
    $('#expenseFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyExpenseFilter = () => {
    $('#expenseFilterModal').modal('hide');
    reload();
};

const expenseFilterModalClear = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_category.value = null;
    search_supplier.value = null;
    from_date.value = null;
    to_date.value = null;
    // search_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#expenseFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('expenses.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_code: codeFilter.value,
                search_category: search_category_id.value,
                search_supplier: search_supplier_id.value,
                search_date: search_date.value,
                search_from_date: search_expense.value.from_date,
                search_to_date: search_expense.value.to_date,
                currency: search_select_currency.value?.id,
                sorting_value: sorting_value.value,
            },
        })
    ).data;

    expenses.value = tableData.data;
    pagination.value = tableData.meta;

    if (expenses.value.length > 0) {
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

watch(search_supplier, (newValue) => {
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
    search_expense.value.from_date = newValue;
    getSearch();
});

watch(to_date, (newValue) => {
    if (newValue) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_expense.value.to_date = endDate;
        getSearch();
    } else {
        search_expense.value.to_date = null;
    }
});

const getCategories = async () => {
    try {
        const response = (await axios.get(route('expenses.category.multiselect.all'))).data;
        categories.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getSuppliers = async () => {
    try {
        const response = (await axios.get(route('supplier.multiselect.all'))).data;
        suppliers.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const saveExpense = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        if (select_category.value != null) {
            expense.value.expense_category_id = select_category.value.id;
        }

        if (select_supplier.value != null) {
            expense.value.supplier_id = select_supplier.value.id;
        }

        if (select_currency.value != null) {
            expense.value.currency_id = select_currency.value.id;
        }

        if (date.value != null) {
            expense.value.date = date.value;
        }

        const response = await axios.post(route('expense.store'), expense.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Expense added successfully!');

        expense.value = {};
        expense_image.value = null;
        select_category.value = [];
        select_supplier.value = [];

        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;

        $('#expenseModal').modal('hide');
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

const viewExpense = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('expense.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }

};

const editExpense = async (id) => {
    try {
        const response = (await axios.get(route("expense.get", id))).data;
        edit_expense.value = response;

        if (edit_expense.value.expense_category_id) {
            edit_select_category.value.id = edit_expense.value.expense_category_id;
            edit_select_category.value.name = edit_expense.value.category_name;
        }

        if (edit_expense.value.supplier_id) {
            const supplier = (await axios.get(route('supplier.get', edit_expense.value.supplier_id))).data
            edit_select_supplier.value = supplier;
        }

        if (edit_expense.value.currency_id) {
            edit_select_currency.value.id = edit_expense.value.currency_id;
            edit_select_currency.value.code = edit_expense.value.currency_name;
        }

        if (edit_expense.value.date) {
            edit_date.value = new Date(edit_expense.value.date);
        }
        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;

        $('#expenseUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }
};

const loadData = async (id) => {
    try {
        const response = (await axios.get(route("expense.get", id))).data;
        edit_expense.value = response;

        if (edit_expense.value.expense_category_id) {
            edit_select_category.value.id = edit_expense.value.expense_category_id;
            edit_select_category.value.name = edit_expense.value.category_name;
        }

        if (edit_expense.value.supplier_id) {
            const supplier = (await axios.get(route('supplier.get', edit_expense.value.supplier_id))).data
            edit_select_supplier.value = supplier;
        }

        if (edit_expense.value.currency_id) {
            edit_select_currency.value.id = edit_expense.value.currency_id;
            edit_select_currency.value.code = edit_expense.value.currency_name;
        }

        if (edit_expense.value.date) {
            edit_date.value = new Date(edit_expense.value.date);
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const updateExpense = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        if (edit_select_category.value != null) {
            edit_expense.value.expense_category_id = edit_select_category.value.id;
        }

        if (edit_select_supplier.value != null) {
            edit_expense.value.supplier_id = edit_select_supplier.value.id;
        } else {
            edit_expense.value.supplier_id = null;
        }

        if (edit_select_currency.value != null) {
            edit_expense.value.currency_id = edit_select_currency.value.id;
        }

        if (edit_date.value != null) {
            expense.value.date = edit_date.value;
        }

        if (edit_expense_image.value != null) {
            edit_expense.value.image = edit_expense_image.value;
        } else {
            edit_expense.value.image = null;
        }

        const response = await axios.post(route("expense.update", edit_expense.value.id), edit_expense.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Expense updated successfully!');

        edit_expense.value = {};
        edit_expense_image.value = null;

        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;

        $('#expenseUpdateModal').modal('hide');
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

        await axios.get(route("expense.image.remove", id)).data;

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
        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;
        expense.value.image_url = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

function showRestoreModal(expenseId) {

    selectedExpenseId.value = expenseId;
    $("#expenseRestoreModal").modal("show");

}

function closeRestoreModal() {
    $("#expenseRestoreModal").modal("hide");
}

const restoreExpense = async (expenseId) => {
    try {


        await axios.get(route("expenses.restore", expenseId));
        closeRestoreModal();
        successMessage('Expense restored successfully');
        reload();


    } catch (error) {
    }
}

const showSupplierModal = async () => {
    supplierData.value = {};
    $("#supplierModal").modal("show");
};

const saveNewSupplier = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const response = await axios.post(route("supplier.all.store"), supplierData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New supplier registration is successful');
            closeSupplierModal();
            getSuppliers();
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

const closeSupplierModal = async () => {
    $("#supplierModal").modal("hide");
};

function clearFilters() {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_category.value = null;
    search_supplier.value = null;
    from_date.value = null;
    to_date.value = null;
    // search_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
}

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
        business_details.value = response;

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

const truncateSupplierText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 38 ? text.substring(0, 38) + '...' : text;
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
    getCategories();
    getSuppliers();
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
