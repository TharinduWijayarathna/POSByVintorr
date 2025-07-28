<template>
    <AppLayout title="Invoices">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2 justify-content-center">
                    <div class="mt-5 col-lg-12 mt-lg-0 custom-component">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Invoices
                                </h1>

                            </div>
                            <!-- Desktop view -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-none d-md-flex justify-content-end align-items-center">
                                <a v-if="props.auth.user.user_role_id != 2" :href="route('invoice.deleted.list')"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list"
                                    class="btn trash-btn ps-3 pe-2">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </a>
                                <div @click.prevent="exportInvoiceReport" class="btn btn-info ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export invoice report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up"></i> Export
                                </div>
                                <div @click.prevent="newInvoice" class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new invoice" style="font-weight: bold;">
                                    <i class="bi bi-plus-lg"></i> Add Invoice
                                </div>
                            </div>

                            <!-- Mobile view -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-flex d-md-none justify-content-end align-items-center">
                                <a v-if="props.auth.user.user_role_id != 2" :href="route('invoice.deleted.list')"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list"
                                    class="btn trash-btn ps-3 pe-2">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </a>
                                <div @click.prevent="exportInvoiceReport" class="btn btn-info export-btn ps-3 pe-2 ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export invoice report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </div>
                                <div @click.prevent="newInvoice" class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new invoice" style="font-weight: bold;">
                                    <i class="bi bi-plus-lg"></i> Add Invoice
                                </div>
                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <select id="orderStatus"
                                            class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="orderStatus" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Paid</option>
                                            <option value="2">Partial</option>
                                            <option value="3">None</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <input id="search" v-model="search" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="INV No."
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input id="header_fields" v-model="header_fields" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Parameters"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect id="select_search_customer" v-model="select_search_customer"
                                            :options="truncatedCustomers" class="mb-2 z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect id="select_search_cashier" v-model="select_search_cashier"
                                            :options="cashiers" class="mb-2 z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" @search-change="getCashier"
                                            :searchable="true" placeholder="Created By" label="name" track-by="id"
                                            max-height="200" />
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
                                        <Multiselect id="search_select_currency" v-model="search_select_currency"
                                            :options="currencies" class="mb-2 z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select Currency" label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-6 col-xxl-1 align-self-end d-flex justify-content-between">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filter">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>

                                        <div class="mb-2 dropdown" v-if="bills.length > 0">
                                            <button class="btn btn-sm h-100 ps-3 pe-2" type="button"
                                                id="dropdownMenuClickableInside" data-bs-toggle="dropdown"
                                                data-bs-auto-close="outside" aria-expanded="false"
                                                style="background-color: #f0f0f0;">
                                                <i class="bi bi-three-dots fs-2"></i>
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
                                                                        @click="setSortingValue(1)">Invoice No</a>
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
                                                @click.prevent="openInvoiceFilterModal">
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
                                <!-- No Invoices State -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && orderStatus == 0 && search == null && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null">

                                    <!-- Icon for No Invoices State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-file-earmark-text fs-1"></i>
                                    </div>

                                    <!-- Heading for No Invoices State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Invoices</h2>
                                    </div>

                                    <!-- Subtext for No Invoices State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Invoices will appear here once available.</p>
                                    </div>
                                </div>

                                <!-- No Search Results State -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (orderStatus != 0 || search != null || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null)">

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
                                        <p class="text-gray-600 fs-5">Try adjusting your filters to find the invoices
                                            you're looking for.</p>
                                    </div>
                                </div>

                                <div v-if="bills.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="text-center ps-0 status-col">STATUS</th>
                                                    <th>INVOICE NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-end pe-3">TOTAL</th>
                                                    <th class="text-end">PAID AMOUNT</th>
                                                    <th class="text-end">DUE AMOUNT</th>
                                                    <th class="text-end pe-3">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index" class="cursor-pointer">
                                                    <td class="py-2 text-center ps-0 status-col"
                                                        @click.prevent="editInvoice(bill.id)">
                                                        <div
                                                            v-if="bill.customer_paid > 0 && (bill.total - bill.customer_paid) > 0">
                                                            <div
                                                                class="text-center badge badge-secondary partial-status">
                                                                PARTIAL
                                                            </div>
                                                        </div>
                                                        <div v-if="bill.customer_paid == 0">
                                                            <div class="text-center badge none-status">
                                                                NONE
                                                            </div>
                                                        </div>
                                                        <div
                                                            v-if="bill.customer_paid > 0 && (bill.total - bill.customer_paid) <= 0">
                                                            <div class="text-center badge paid-status">
                                                                PAID
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-2" @click.prevent="editInvoice(bill.id)">{{
                                                        bill.code }}</td>
                                                    <td v-if="bill.customer_id == 0 || bill.customer_id == null"
                                                        class="py-2" @click.prevent="editInvoice(bill.id)">Walking
                                                        Customer</td>
                                                    <td v-else class="py-2" @click.prevent="editInvoice(bill.id)">{{
                                                        truncateText(bill.customer_name) }}</td>
                                                    <td v-if="bill.cashier != null" class="py-2"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            truncateText(bill.cashier_name) }}
                                                    </td>
                                                    <td v-if="bill.cashier == null" class="py-2"
                                                        @click.prevent="editInvoice(bill.id)"></td>
                                                    <td class="py-2" @click.prevent="editInvoice(bill.id)">{{
                                                        formatDate(bill.date) }}</td>
                                                    <td class="py-2 text-center" @click.prevent="editInvoice(bill.id)">
                                                        {{
                                                            bill.currency_name }}</td>

                                                    <td class="py-2 text-end pe-3"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            bill.formatted_total }}
                                                    </td>
                                                    <td class="py-2 text-end" @click.prevent="editInvoice(bill.id)">{{
                                                        bill.formatted_customer_paid }}
                                                    </td>
                                                    <td class="py-2 text-end" @click.prevent="editInvoice(bill.id)">{{
                                                        bill.formatted_due }}
                                                    </td>
                                                    <td class="py-2 text-end pe-1">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Info button for ref_no (only if ref_no exists) -->
                                                            <button v-if="bill.ref_no"
                                                                class="border btn btn-sm border-info text-info d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" :title="bill.ref_no">
                                                                <i class="bi bi-info-circle fs-5"></i>
                                                            </button>

                                                            <!-- Copy shareable link button -->
                                                            <button
                                                                class="border btn btn-sm border-primary text-primary d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Copy shareable link"
                                                                @click.prevent="createInvoiceLink(bill.id)">
                                                                <i class="bi bi-link fs-5"></i>
                                                            </button>

                                                            <!-- Print invoice button -->
                                                            <button
                                                                class="border btn btn-sm border-primary text-primary d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Print invoice"
                                                                @click.prevent="historyPrint(bill.id)">
                                                                <i class="bi bi-printer fs-3"></i>
                                                            </button>

                                                            <!-- Delete invoice button (only visible if user_role_id is not 2) -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete invoice"
                                                                @click.prevent="deleteInvoice(bill.id)">
                                                                <i class="bi bi-trash-fill fs-5"></i>
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
                                                    <td></td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">TOTAL</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfTotal
                                                    }}</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfPaid
                                                    }}</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold pe-3">{{
                                                        totalOfDue }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="(bill, index) in bills" :key="index">
                                                    <td class="table-responsive-width">
                                                        <div class="row " @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase ">
                                                                STATUS</div>
                                                            <div class="col-8 right-side text-end">
                                                                <div
                                                                    v-if="bill.customer_paid > 0 && (bill.total - bill.customer_paid) > 0">
                                                                    <div
                                                                        class="text-center badge badge-secondary partial-status">
                                                                        PARTIAL
                                                                    </div>
                                                                </div>
                                                                <div v-if="bill.customer_paid == 0">
                                                                    <div class="text-center badge none-status">
                                                                        NONE
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    v-if="bill.customer_paid > 0 && (bill.total - bill.customer_paid) <= 0">
                                                                    <div class="text-center badge paid-status">
                                                                        PAID
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CODE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    bill.code }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CUSTOMER</div>
                                                            <div class="col-8 right-side text-end"
                                                                v-if="bill.customer_id == 0 || bill.customer_id == null">
                                                                <span>Walking
                                                                    Customer</span>
                                                            </div>
                                                            <div v-else class="col-8 right-side text-end">
                                                                <span>{{
                                                                    truncateText(bill.customer_name) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                USER</div>
                                                            <div class="col-8 right-side text-end"
                                                                v-if="bill.cashier != null">
                                                                <span>
                                                                    {{ truncateText(bill.cashier_name) }}
                                                                </span>
                                                            </div>
                                                            <div class="col-8 right-side text-end"
                                                                v-if="bill.cashier == null">
                                                                <span>

                                                                </span>
                                                            </div>
                                                        </div>


                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DATE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    formatDate(bill.date) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                CURRENCY</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{
                                                                    bill.currency_name }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                TOTAL</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_total }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                PAID</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_customer_paid }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DUE</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_due }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                            </div>
                                                            <div class="col-8 right-side text-end ">
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Copy shareable link"
                                                                    @click.prevent="createInvoiceLink(bill.id)">
                                                                    <i class="bi bi-link"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="pl-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Print invoice"
                                                                    @click.prevent="historyPrint(bill.id)">
                                                                    <i class="bi bi-printer fs-3"></i>
                                                                </a>
                                                                <a v-if="page_props.auth.user.user_role_id != 2"
                                                                    href="javascript:void(0)" class="pl-4"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete invoice"
                                                                    @click.prevent="deleteInvoice(bill.id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
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
            <!-- Invoice Filter Modal -->
            <div class="modal fade" id="invoiceFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="invoiceFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Invoice Filters</h5>
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
                                        <label for="modalOrderStatus"
                                            class="text-gray-600 col-form-label">STATUS</label>
                                        <select id="modalOrderStatus"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="orderStatus" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Paid</option>
                                            <option value="2">Partial</option>
                                            <option value="3">None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSearch" class="text-gray-600 col-form-label">INV NO.</label>
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="INV No."
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSearchHeaderFields"
                                            class="text-gray-600 col-form-label">PARAMETERS</label>
                                        <input id="modalSearchHeaderFields" v-model="header_fields" type="text"
                                            class="form-control form-control-sm" placeholder="Parameters"
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
                                <!-- <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalSelectCashier" class="text-gray-600 col-form-label">CREATED
                                            BY</label>
                                        <Multiselect id="modalSelectCashier" v-model="select_search_cashier"
                                            :options="cashiers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" @search-change="getCashier"
                                            :searchable="true" placeholder="Created By" label="name" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div> -->
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalFromDate" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker id="modalFromDate" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :format="'dd/MM/yyyy'" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalToDate" class="text-gray-600 col-form-label">TO DATE</label>
                                        <Datepicker id="modalToDate" v-model="to_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText" />
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
                                        <button @click="invoiceFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyInvoiceFilter" class="btn btn-info ms-4 fw-bold">
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this invoice?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete invoice"
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
import { Link, router } from "@inertiajs/vue3";

import _ from 'lodash';

const { props } = usePage();
const page_props = props;

const datePickerFormat = 'dd-MM-yyyy';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const bills = ref([]);
const emptyImageVal = ref(0);

const header_fields = ref(null);
const search = ref(null);
const search_order = ref({});
const from_date = ref(null);
const to_date = ref(null);
const select_search_customer = ref(null);
const select_search_cashier = ref(null);
const customers = ref([]);
const cashiers = ref([]);
const orderStatus = ref(0);
const sorting_value = ref(0);

const invoice = ref({});

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';



const currencies = ref([]);
const search_select_currency = ref({});
const previousCurrency = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};
const invoiceLink = ref({});
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

const setSortingValue = async (value) => {
    sorting_value.value = value;
    getSearch();
};

const perPageChange = async (perPageCount) => {
    page.value = 1;
    pageCount.value = perPageCount;
    reload();
};

const perPageChangeMobile = async () => {
    page.value = 1;
    reload();
};

const openInvoiceFilterModal = () => {
    loading_bar.value.start();
    $('#invoiceFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyInvoiceFilter = () => {
    $('#invoiceFilterModal').modal('hide');
    reload();
};

const invoiceFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
    sorting_value.value = 0;
    page.value = 1;
    header_fields.value = null;
    getCurrency();
    getBusinessDetails();
    $('#invoiceFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('invoice.bill.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_customer: search_order.value.customer_id,
                search_order_cashier: search_order.value.cashier_id,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_status: search_order.value.orderStatus,
                sorting_value: sorting_value.value,
                currency: search_select_currency.value?.id,
                header_fields: header_fields.value,
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

const historyPrint = async (id) => {
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

const deleteInvoice = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        try {
            const response = (await axios.get(route("invoice.delete.confirm", id))).data;
            invoice.value = response;

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
    } else {
        nextTick(() => {
            loading_bar.value.finish();
        });
        errorMessage('Access denied');
    }

}

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const response = (await axios.delete(route("invoice.delete", invoice.value.id))).data;
        successMessage('Invoice deleted successfully!');

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

watch([from_date], async () => {
    if (from_date.value) {
        const picked_date = from_date.value;
        const dateObject = new Date(picked_date);
        const formattedDate = dateObject.toLocaleDateString("en-GB");
    }
});

watch(to_date, (newValue) => {
    if (newValue) {
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

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
    sorting_value.value = 0;
    page.value = 1;
    header_fields.value = null;
    getCurrency();
    getBusinessDetails();
}

const createInvoiceLink = async (invoiceId) => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        nextTick(() => {
            loading_bar.value.start();
        });
        try {
            const response = await axios.get(route('invoice.link.get', invoiceId));
            await navigator.clipboard.writeText(response.data);
            successMessage('link copied successfully')
            invoiceLink.value = response;
            nextTick(() => {
                loading_bar.value.finish();
            });
        } catch (error) {
            nextTick(() => {
                loading_bar.value.finish();
            });
        }
    } else {
        errorMessage('Access denied');
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

const totalOfSubTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.sub_total.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfDiscount = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.discount.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.total.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfPaid = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.customer_paid.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfDue = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.formatted_due.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
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

const newInvoice = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('invoice.index'));
    } else {
        errorMessage('Access denied');
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

const exportInvoiceReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.invoice.export"),
            {
                invoice_no: search.value,
                search_order_customer: select_search_customer.value,
                search_order_cashier: select_search_cashier.value,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_status: search_order.value.orderStatus,
                currency: search_select_currency.value,
                header_fields: header_fields.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;

        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `InvoiceReport-${formattedDate}.xlsx`;

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();

    } catch (error) {
        convertValidationNotification(error);
    } finally {
        loading_bar.value.finish();
    }
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

<style lang="scss" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.paid-status {
    background-color: #DBFFE4;
    color: green;
}

.none-status {
    background-color: #ffe0db;
    color: rgba(255, 51, 0, 0.822);
}

.partial-status {
    background-color: rgba(255, 187, 0, 0.2);
    color: rgba(235, 168, 0, 1);
}

.ref-no {
    width: 10px;
}

.i-icon-col {
    width: 30px;
}

.status-col {
    width: 80px;
}
</style>
