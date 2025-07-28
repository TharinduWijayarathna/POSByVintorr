<template>
    <AppLayout title="Transactions">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Your Transactions
                                </h1>
                            </div>

                            <!-- Desktop View -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-none d-md-flex justify-content-end align-items-center">
                                <a href="javascript:void(0)" class="btn btn-info me-2" style="font-weight: bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Export transaction report"
                                    @click.prevent="exportTransactionReport">
                                    <i class="bi bi-box-arrow-up"></i> Export
                                </a>
                                <a href="javascript:void(0)" class="btn btn-info" style="font-weight: bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Add new transaction"
                                    @click.prevent="openAddTransaction">
                                    <i class="bi bi-plus-lg"></i> Add Transaction
                                </a>
                            </div>

                            <!-- Mobile View -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-flex d-md-none justify-content-end align-items-center">
                                <a href="javascript:void(0)" class="btn btn-info export-btn ps-3 pe-2 me-2"
                                    style="font-weight: bold" data-toggle="tooltip" data-placement="bottom"
                                    title="Add new transaction" @click.prevent="exportTransactionReport">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </a>

                                <a href="javascript:void(0)" class="btn btn-info" style="font-weight: bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Add new transaction"
                                    @click.prevent="openAddTransaction">
                                    <i class="bi bi-plus-lg"></i> Add Transaction
                                </a>
                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-0">

                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0" v-model="typeValue"
                                            data-control="select2" data-placeholder="Select an option"
                                            @keyup="getSearch">
                                            <option value="0" disabled selected>Type</option>
                                            <option value="1">POS</option>
                                            <option value="2">Invoice</option>
                                            <option value="3">Expense</option>
                                            <option value="4">Return</option>
                                            <option value="5">Manual</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">

                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0"
                                            v-model="transactionType" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Transaction Type</option>
                                            <option value="1">In</option>
                                            <option value="2">Out</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-0">

                                        <Datepicker v-if="to_date && !from_date || to_date && from_date"
                                            v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />

                                        <Datepicker v-else v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-0">

                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-0">
                                        <input v-model="ref_code" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Ref Code" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-0">
                                        <input v-model="client" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Customer / Supplier" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-0">
                                        <Multiselect v-model="search_select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="code" track-by="id" />
                                    </div>
                                    <div class="col-md-1 align-self-end">
                                        <button @click="clearFilters" data-toggle="tooltip" data-placement="bottom"
                                            title="Clear filters" class="p-5 mb-2 square-clear-button">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-1 mb-xl-0"></div>


                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openTransactionFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="transactions.length > 0"
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
                                </div>

                                <!-- Transactions Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && typeValue == 0 && transactionType == 0 && from_date == null && to_date == null && ref_code == '' && client == ''">
                                    <!-- Icon for Empty Transactions State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-credit-card fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Transactions State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Transactions</h2>
                                    </div>

                                    <!-- Subtext for Empty Transactions State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your transactions will appear here.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (typeValue != 0 || transactionType != 0 || from_date != null || to_date != null || ref_code != '' || client != '')">
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

                                <div v-if="transactions.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">

                                                    <th class="text-center status-col">Type</th>
                                                    <th>Date</th>
                                                    <th>Reference Code</th>
                                                    <th>Customer / Supplier</th>
                                                    <th>Description</th>
                                                    <th class="text-end pe-3">Amount</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr class="cursor-pointer" v-for="(transaction, index) in transactions"
                                                    :key="index"
                                                    @click.prevent="transaction.type == 5 ? editTransaction(transaction.id) : null">

                                                    <td class="py-2 text-center ps-1 status-col">
                                                        <!-- Transaction Type Badges -->
                                                        <div v-if="transaction.type == 1" class="badge badge-success">
                                                            Bill</div>
                                                        <div v-if="transaction.type == 2" class="badge badge-primary">
                                                            Invoice</div>
                                                        <div v-if="transaction.type == 3" class="badge badge-danger">
                                                            Expense</div>
                                                        <div v-if="transaction.type == 4" class="badge badge-warning">
                                                            Return</div>
                                                        <div v-if="transaction.type == 5" class="badge badge-secondary">
                                                            Manual</div>
                                                    </td>
                                                    <td class="py-2">{{ formatDate(transaction.date) }}</td>
                                                    <td class="py-2">{{ transaction.reference_code }}</td>

                                                    <td v-if="transaction.client != null" class="py-2">{{
                                                        truncateText(transaction.client) }}</td>
                                                    <td v-else class="py-2">Walking Customer</td>

                                                    <td v-if="transaction.type == 5" class="py-2">{{ transaction.remark
                                                        }}</td>
                                                    <td v-else class="py-2">{{ truncateText(transaction.description) }}
                                                    </td>

                                                    <td class="py-2 text-end pe-3">
                                                        <span v-if="transaction.sign == 0">-</span>
                                                        <span v-if="transaction.sign == 1">+</span>&nbsp;{{
                                                            transaction.currency_name }}&nbsp;{{
                                                            transaction.formatted_amount }}
                                                    </td>

                                                    <!-- Actions Column -->
                                                    <td class="py-2 text-end">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Edit Button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Edit" @click="editTransaction(transaction.id)">
                                                                <i
                                                                    class="p-2 bi bi-pencil-square text-dark-square fs-5 text-dark"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>


                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="(transaction, index) in transactions" :key="index"
                                            style="margin-bottom: 20px;">
                                            <!-- Start a new table for each transaction -->
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Transaction Type -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Type</td>
                                                        <td class="text-end">
                                                            <div v-if="transaction.type == 1"
                                                                class="badge badge-success">Bill</div>
                                                            <div v-if="transaction.type == 2"
                                                                class="badge badge-primary">Invoice</div>
                                                            <div v-if="transaction.type == 3"
                                                                class="badge badge-danger">Expense</div>
                                                            <div v-if="transaction.type == 4"
                                                                class="badge badge-warning">Return</div>
                                                            <div v-if="transaction.type == 5"
                                                                class="badge badge-secondary">Manual</div>
                                                        </td>
                                                    </tr>

                                                    <!-- Date -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Date</td>
                                                        <td class="text-end">{{ formatDate(transaction.date) }}</td>
                                                    </tr>

                                                    <!-- Reference Code -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Reference Code</td>
                                                        <td class="text-end">{{ transaction.reference_code }}</td>
                                                    </tr>

                                                    <!-- Customer / Supplier -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Customer / Supplier
                                                        </td>
                                                        <td class="text-end">
                                                            <span v-if="transaction.client != null">{{
                                                                truncateText(transaction.client) }}</span>
                                                            <span v-else>Walking Customer</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Description -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Description</td>
                                                        <td class="text-end">
                                                            <span v-if="transaction.type == 5">{{ transaction.remark
                                                                }}</span>
                                                            <span v-else>{{ truncateText(transaction.description)
                                                                }}</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Amount -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Amount</td>
                                                        <td class="text-end">
                                                            <span v-if="transaction.sign == 0">-</span>
                                                            <span v-if="transaction.sign == 1">+</span>&nbsp;{{
                                                                transaction.currency_name }}&nbsp;{{
                                                                transaction.formatted_amount }}
                                                        </td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <a class="p-2 text-primary" href="javascript:void(0)"
                                                                data-toggle="tooltip" title="Edit"
                                                                @click="editTransaction(transaction.id)">
                                                                <i class="bi bi-pencil-square text-dark fs-4"></i>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="transactions.length > 0" class="my-3 row align-items-center">
                                    <!-- Entries Dropdown -->
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
                                        <!-- Pagination Info -->
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>

                                        <!-- Pagination Controls -->
                                        <div class="dataTables_paginate paging_simple_numbers">
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
            <!-- Transaction Filter Modal -->
            <div class="modal fade" id="transactionFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="transactionFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Transaction Filters</h5>
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
                                        <select id="modalType"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="typeValue" data-control="select2" data-placeholder="Select Type"
                                            @keyup="getSearch">
                                            <option value="0" disabled selected>Type</option>
                                            <option value="1">POS</option>
                                            <option value="2">Invoice</option>
                                            <option value="3">Expense</option>
                                            <option value="4">Return</option>
                                            <option value="5">Manual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">

                                        <select id="modalTransactionType"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="transactionType" data-control="select2"
                                            data-placeholder="Select Transaction Type" @keyup="getSearch">
                                            <option value="0" disabled selected>Transaction Type</option>
                                            <option value="1">In</option>
                                            <option value="2">Out</option>
                                        </select>
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
                                        <input id="modalSearchRefCode" v-model="ref_code" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Ref Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">

                                        <input id="modalSearchCS" v-model="client" type="text"
                                            class="form-control form-control-sm"
                                            placeholder="Serach by Customer/Supplier" @keyup="debouncedGetSearch" />
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
                                        <button @click="transactionFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyTransactionFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Transaction Modal -->
            <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #071437">ADD NEW TRANSACTION</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <form @submit.prevent="saveTransaction" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-5 offset-1">
                                        <a href="javascript:void(0)"
                                            class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                            data-toggle="tooltip" data-placement="bottom" title="Income"
                                            :class="{ 'active': tabIndex === 1 }" @click="tabIndex = 1"
                                            data-kt-button="true">
                                            <span class="fs-7 fw-bold d-block">Income</span>
                                        </a>
                                    </div>
                                    <div class="col-5">
                                        <a href="javascript:void(0)"
                                            class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                            data-toggle="tooltip" data-placement="bottom" title="Expense"
                                            :class="{ 'active': tabIndex === 2 }" @click="tabIndex = 2"
                                            data-kt-button="true">
                                            <span class="fs-7 fw-bold d-block">Expense</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="transaction_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                                    data-toggle="tooltip" data-placement="bottom" title="Select currency"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />
                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="transaction.amount" type="number" class="form-control form-control-sm"
                                    placeholder="Enter amount" />
                                <div class="mt-2 text-gray-600 col-form-label">Remark</div>
                                <textarea v-model="transaction.remark" class="form-control" placeholder="Enter remark"
                                    data-kt-autosize="true" style="resize: none; font-size: 12px;" rows="2"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="px-10 mt-2 btn btn-info me-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new transaction" style="font-weight: bold">
                                    ADD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Transaction Modal -->
            <div class="modal fade" id="editTransactionModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #071437">UPDATE TRANSACTION</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="updateTransaction" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-5 offset-1">
                                        <a href="javascript:void(0)"
                                            class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                            :class="{ 'active': edit_transaction.sign === 1 }" data-kt-button="false">
                                            <span class="fs-7 fw-bold d-block">Income</span>
                                        </a>
                                    </div>
                                    <div class="col-5">
                                        <a href="javascript:void(0)"
                                            class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                            :class="{ 'active': edit_transaction.sign === 0 }" data-kt-button="false">
                                            <span class="fs-7 fw-bold d-block">Expense</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="transaction_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="edit_select_currency" :options="currencies" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />
                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="edit_transaction.amount" type="text"
                                    class="form-control form-control-sm" placeholder="Enter amount" />
                                <div class="mt-2 text-gray-600 col-form-label">Remark</div>
                                <textarea v-model="edit_transaction.remark" class="form-control"
                                    placeholder="Enter remark" data-kt-autosize="true"
                                    style="resize: none; font-size: 12px;" rows="2"></textarea>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="px-10 mt-2 btn btn-light-danger me-2"
                                    style="font-weight: bold" @click.prevent="deleteTransaction">
                                    DELETE
                                </button>
                                <button type="submit" class="px-10 mt-2 btn btn-light-info me-2"
                                    style="font-weight: bold">
                                    UPDATE
                                </button>
                            </div>
                        </form>
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

import _ from 'lodash';

const datePickerFormat = 'dd-MM-yyyy';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const transactions = ref([]);
const emptyImageVal = ref(0);

const search = ref(null);
const from_date = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
const to_date = ref(new Date());
const search_transaction = ref({
    from_date: from_date.value,
    to_date: to_date.value,
});
const select_search_customer = ref(null);
const select_search_cashier = ref(null);
const customers = ref([]);
const cashiers = ref([]);
const typeValue = ref(0);
const transactionType = ref(0);
const ref_code = ref('');
const client = ref('');
const business_details = ref({});
const currencies = ref([]);
const select_currency = ref({});
const edit_select_currency = ref({});
const search_select_currency = ref({});

const transaction_date = ref(new Date());
const transaction = ref({});
const edit_transaction = ref({});
const tabIndex = ref(1);

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';



const validationErrors = ref({});
const validationMessage = ref(null);
const previousCurrency = ref({});

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

const formatDate = (value) => {
    // return moment(String(value)).format('DD/MM/YYYY HH:mm:ss');
    return moment(String(value)).format('DD/MM/YYYY');
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

const openTransactionFilterModal = () => {
    loading_bar.value.start();
    $('#transactionFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyTransactionFilter = () => {
    $('#transactionFilterModal').modal('hide');
    reload();
};

const transactionFilterModalClear = async () => {
    typeValue.value = 0;
    transactionType.value = 0;
    emptyImageVal.value = 0;
    ref_code.value = "";
    client.value = "";
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    search_transaction.value.from_date = from_date.value;
    search_transaction.value.to_date = to_date.value;
    search_select_currency.value.id = business_details.value.currency_id;
    search_select_currency.value.code = business_details.value.currency_name;
    page.value = 1;
    getCurrency();
    $('#transactionFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('transaction.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_type: search_transaction.value.typeValue,
                search_transaction_type: search_transaction.value.transactionType,
                search_from_date: search_transaction.value.from_date,
                search_to_date: search_transaction.value.to_date,
                search_ref_code: ref_code.value,
                search_client: client.value,
                currency: search_select_currency.value?.id,
            },
        })
    ).data;

    transactions.value = tableData.data;
    pagination.value = tableData.meta;

    if (transactions.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const exportTransactionReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(route("report.transaction.export"),
            {
                search_type: search_transaction.value.typeValue,
                search_transaction_type: search_transaction.value.transactionType,
                search_from_date: search_transaction.value.from_date,
                search_to_date: search_transaction.value.to_date,
                search_currency: search_select_currency.value,
                client: client.value,
                code: ref_code.value,
                transactions: transactions.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;


        // file name
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `TransactionReport-${formattedDate}.xlsx`;

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

const openAddTransaction = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        tabIndex.value = 1;
        transaction_date.value = new Date();
        $('#transactionModal').modal('show');
    } else {
        errorMessage('Access denied');
    }

}

const saveTransaction = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        if (tabIndex.value == 1) {
            transaction.value.sign = 1;
        } else if (tabIndex.value == 2) {
            transaction.value.sign = 0;
        }

        if (transaction_date.value) {
            transaction.value.date = transaction_date.value;
        }

        if (select_currency.value != null) {
            transaction.value.currency_id = select_currency.value.id;
        }

        await axios.post(route('transaction.store'), transaction.value);
        successMessage('Transaction created successfully');
        $('#transactionModal').modal('hide');
        reload();
        transaction.value = {};


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

const editTransaction = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        if (page_props.auth.user.user_role_id != 3) {
            const transactionData = await axios.get(route("transaction.edit", id));
            edit_transaction.value = transactionData.data;

            if (edit_transaction.value.sign == 1) {
                tabIndex.value = 1;
            } else if (edit_transaction.value.sign == 0) {
                tabIndex.value = 2;
            }

            if (edit_transaction.value.date) {
                const dateObj = new Date(edit_transaction.value.date);
                transaction_date.value = dateObj;
            }

            if (edit_transaction.value.currency_id) {
                edit_select_currency.value.id = edit_transaction.value.currency_id;
                edit_select_currency.value.code = edit_transaction.value.currency_name;
            }

            $('#editTransactionModal').modal('show');
        } else {
            errorMessage('Access denied');
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
}

const updateTransaction = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        if (tabIndex.value == 1) {
            edit_transaction.value.sign = 1;
        } else if (tabIndex.value == 2) {
            edit_transaction.value.sign = 0;
        }

        if (transaction_date.value) {
            edit_transaction.value.date = transaction_date.value;
        }

        if (edit_select_currency.value != null) {
            edit_transaction.value.currency_id = edit_select_currency.value.id;
        }

        await axios.post(route('transaction.update', edit_transaction.value.id), edit_transaction.value);
        successMessage('Transaction updated successfully');
        $('#editTransactionModal').modal('hide');
        reload();
        edit_transaction.value = {};


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

const deleteTransaction = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.delete(route("transaction.delete", edit_transaction.value.id));
        successMessage('Transaction deleted successfully');
        $('#editTransactionModal').modal('hide');
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
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            select_currency.value.id = business_details.value.currency_id;
            select_currency.value.code = business_details.value.currency_name;
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

watch(from_date, (newValue) => {
    search_transaction.value.from_date = newValue;
    getSearch();
});

watch([from_date], async () => {
    if (from_date.value) {
        const picked_date = from_date.value;
        const dateObject = new Date(picked_date);
        const formattedDate = dateObject.toLocaleDateString("en-GB");
    }
});

watch(to_date, (newValue) => {
    if (to_date.value) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_transaction.value.to_date = endDate;
        getSearch();
    } else {
        search_transaction.value.to_date = null;
    }
});

watch(typeValue, (newValue) => {
    search_transaction.value.typeValue = newValue;
    getSearch();
});

watch(transactionType, (newValue) => {
    search_transaction.value.transactionType = newValue;
    getSearch();
});

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        previousCurrency.value = search_select_currency.value;
        page.value = 1;
        reload();
    } else {
        search_select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

function clearFilters() {
    typeValue.value = 0;
    transactionType.value = 0;
    emptyImageVal.value = 0;
    ref_code.value = "";
    client.value = "";
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    search_transaction.value.from_date = from_date.value;
    search_transaction.value.to_date = to_date.value;
    search_select_currency.value.id = business_details.value.currency_id;
    search_select_currency.value.code = business_details.value.currency_name;
    page.value = 1;
    getCurrency();
    //getBusinessDetails();
}

const errorPrint = () => {
    errorMessage('The order payment is not done!');
}

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
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

onMounted(() => {
    getCurrency();
    getBusinessDetails();
});

</script>

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.manual-status {
    background-color: #E8DAEF;
    color: purple;
}

.return-status {
    background-color: #ffe0db;
    color: rgba(255, 51, 0, 0.822);
}

.bill {
    background-color: #DBFFE4;
    color: green;
}

.i-icon-col {
    width: 20px;
}

.status-col {
    width: 60px;
}

@media (max-width: 382px) {
    .header-section {
        width: 100%;
        text-align: center;
    }
}
</style>
