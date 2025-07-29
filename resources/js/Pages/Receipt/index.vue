<template>
    <AppLayout title="Bills">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <h1 class="main-header-text">
                                Bills
                            </h1>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0" v-model="orderStatus"
                                            data-control="select2" data-placeholder="Select an option"
                                            @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Done</option>
                                            <option value="2">Hold</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Bill No." @keyup="debouncedGetSearch" />
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
                                            :placeholder="placeholderText" :format="'dd/MM/yyyy'"
                                            :upperLimit="to_date" />
                                        <Datepicker v-else v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
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
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0"
                                            v-model="paymentMethod" data-control="select2"
                                            data-placeholder="Select Payment Method" @change="getSearch">
                                            <option value="" selected>Payment Method</option>
                                            <option value="0">Cash</option>
                                            <option value="1">Card</option>
                                        </select>
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
                                    v-if="emptyImageVal == 1 && orderStatus == 0 && search == null && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null">
                                    <!-- Icon for Empty Bills State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-file-earmark-post fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Bills State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Bills</h2>
                                    </div>

                                    <!-- Subtext for Empty Bills State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">POS Bills will appear here.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (orderStatus != 0 || search != null || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null)">
                                    <!-- Icon for Empty Search Results State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Search Results State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Result Found</h2>
                                    </div>

                                    <!-- Subtext for Empty Search Results State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Try adjusting your filters or search criteria.</p>
                                    </div>
                                </div>

                                <div v-if="bills.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3 ">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">

                                                    <th class="text-center">STATUS</th>
                                                    <th>BILL NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-center">PAYMENT</th>
                                                    <th class="text-end">SUB TOTAL</th>
                                                    <th class="text-end">DISCOUNT</th>
                                                    <th class="text-end pe-3">TOTAL</th>
                                                    <th class="text-end pe-3">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index">

                                                    <td class="py-2 text-center cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">
                                                        <div v-if="bill.status == 1"
                                                            class="badge badge-success done-status">
                                                            DONE
                                                        </div>
                                                        <div v-if="bill.status == 0" class="badge badge-light-info">
                                                            PENDING
                                                        </div>
                                                        <div v-if="bill.status == 2" class="badge badge-light-danger">
                                                            HOLD
                                                        </div>
                                                        <div v-if="bill.status == 4" class="badge badge-light-info">
                                                            RETURN
                                                        </div>
                                                    </td>
                                                    <td class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            bill.code }}</td>
                                                    <td v-if="bill.customer_id == 0 || bill.customer_id == null"
                                                        class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">Walking Customer</td>
                                                    <td v-else class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            truncateText(bill.customer_name) }}
                                                    </td>
                                                    <td v-if="bill.cashier != null" class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            truncateText(bill.cashier_name) }}
                                                    </td>
                                                    <td v-if="bill.cashier == null" class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)"></td>
                                                    <td class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            formatDate(bill.date) }}</td>
                                                    <td class="py-2 text-center cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{ bill.currency_name }}
                                                    </td>
                                                    <td class="py-2 text-center cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">
                                                        <span v-if="bill.payment_type === 0"
                                                            class="badge badge-light-primary">
                                                            <i class="bi bi-cash-coin me-1"></i>CASH
                                                        </span>
                                                        <span v-else-if="bill.payment_type === 1"
                                                            class="badge badge-light-info">
                                                            <i class="bi bi-credit-card me-1"></i>CARD
                                                        </span>
                                                        <span v-else class="badge badge-light-secondary">
                                                            N/A
                                                        </span>
                                                    </td>
                                                    <td class="py-2 cursor-pointer text-end"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            bill.formatted_sub_total }}
                                                    </td>
                                                    <td class="py-2 cursor-pointer text-end"
                                                        @click.prevent="editInvoice(bill.id)">{{ bill.formatted_discount
                                                        }}
                                                    </td>
                                                    <td class="py-2 cursor-pointer text-end pe-3"
                                                        @click.prevent="editInvoice(bill.id)">{{ bill.formatted_total }}
                                                    </td>
                                                    <td class="py-1 text-end pe-3">
                                                        <a v-if="bill.status == 1" href="javascript:void(0)"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Print bill" @click.prevent="historyPrint(bill.id)"
                                                            class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                class="bi bi-printer fs-3"></i></a>
                                                        <a v-else-if="bill.status == 4" href="javascript:void(0)"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Print return" @click.prevent="returnPrint(bill.id)"
                                                            class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                class="bi bi-printer fs-3"></i></a>
                                                        <a v-else href="javascript:void(0)" @click.prevent="errorPrint"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Print Unavailable"
                                                            class="btn btn-icon btn-outline btn-outline-dark btn-active-light-dark btn-sm"><i
                                                                class="bi bi-printer fs-3"></i></a>
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
                                                        totalOfSubTotal
                                                    }}</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfDiscount
                                                    }}</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end pe-2 fw-bold">{{
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
                                                    <!-- Status -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Status</td>
                                                        <td class="text-end">
                                                            <div v-if="bill.status == 1"
                                                                class="badge badge-success done-status">DONE</div>
                                                            <div v-if="bill.status == 0" class="badge badge-light-info">
                                                                PENDING</div>
                                                            <div v-if="bill.status == 2"
                                                                class="badge badge-light-danger">HOLD</div>
                                                            <div v-if="bill.status == 4" class="badge badge-light-info">
                                                                RETURN</div>
                                                        </td>
                                                    </tr>

                                                    <!-- Bill No. -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Bill No.</td>
                                                        <td class="text-end">{{ bill.code }}</td>
                                                    </tr>

                                                    <!-- Customer -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Customer</td>
                                                        <td class="text-end">
                                                            <span
                                                                v-if="bill.customer_id == 0 || bill.customer_id == null">Walking
                                                                Customer</span>
                                                            <span v-else>{{ truncateText(bill.customer_name) }}</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Created By -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Created By</td>
                                                        <td class="text-end">{{ bill.cashier_name ?
                                                            truncateText(bill.cashier_name) : '' }}</td>
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

                                                    <!-- Payment Method -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Payment</td>
                                                        <td class="text-end">
                                                            <span v-if="bill.payment_type === 0"
                                                                class="badge badge-light-primary">
                                                                <i class="bi bi-cash-coin me-1"></i>CASH
                                                            </span>
                                                            <span v-else-if="bill.payment_type === 1"
                                                                class="badge badge-light-info">
                                                                <i class="bi bi-credit-card me-1"></i>CARD
                                                            </span>
                                                            <span v-else class="badge badge-light-secondary">
                                                                N/A
                                                            </span>
                                                        </td>
                                                    </tr>

                                                    <!-- Sub Total -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Sub Total</td>
                                                        <td class="text-end">{{ bill.formatted_sub_total }}</td>
                                                    </tr>

                                                    <!-- Discount -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Discount</td>
                                                        <td class="text-end">{{ bill.formatted_discount }}</td>
                                                    </tr>

                                                    <!-- Total -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Total</td>
                                                        <td class="text-end">{{ bill.formatted_total }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <!-- Print Button -->
                                                                <a v-if="bill.status == 1" href="javascript:void(0)"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Print bill"
                                                                    @click.prevent="historyPrint(bill.id)">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                                </a>
                                                                <a v-else-if="bill.status == 4"
                                                                    href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Print return"
                                                                    @click.prevent="returnPrint(bill.id)">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                                </a>
                                                                <a v-else href="javascript:void(0)"
                                                                    @click.prevent="errorPrint" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Print Unavailable">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
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
                                                        <td class="pt-4 text-gray-600 text-end fw-bold">{{
                                                            totalOfSubTotal }}</td>
                                                        <td class="pt-4 text-gray-600 text-end fw-bold">{{
                                                            totalOfDiscount }}</td>
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
                                <div v-if="bills.length > 0" class="my-3 row align-items-center ps-1 ps-md-0">

                                    <!-- Entries Per Page Dropdown -->
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <label class="mb-0 me-2">Show</label>
                                        <select name="kt_ecommerce_bills_table_length"
                                            aria-controls="kt_ecommerce_bills_table"
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
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_bills_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_bills_table_previous">
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
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                                    id="kt_ecommerce_bills_table_next">
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
            <!-- Bills Filter Modal -->
            <div class="modal fade" id="billsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="billsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Bills Filters</h5>
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
                                        <select id="modalOrderStatus"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="orderStatus" data-control="select2"
                                            data-placeholder="Select Status" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Done</option>
                                            <option value="2">Hold</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Bill No."
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
                                            :format="'dd/MM/yyyy'" :upperLimit="to_date" />
                                        <Datepicker id="modalFromDate" v-else v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :format="'dd/MM/yyyy'" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Datepicker id="modalToDate" v-model="to_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText" />
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
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <select id="modalPaymentMethod" class="form-select form-select-sm ps-2 pe-0"
                                            v-model="paymentMethod" data-control="select2"
                                            data-placeholder="Select Payment Method" @change="getSearch">
                                            <option value="" selected>Payment Method</option>
                                            <option value="0">Cash</option>
                                            <option value="1">Card</option>
                                        </select>
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
import { onMounted, ref, watch, nextTick, computed } from 'vue';
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import moment from 'moment';
import Datepicker from 'vue3-datepicker'
import _ from 'lodash';

const datePickerFormat = 'dd-MM-yyyy';

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
const orderStatus = ref(0);
const sorting_value = ref(0);

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';

const currencies = ref([]);
const search_select_currency = ref({});
const previousCurrency = ref({});
const paymentMethod = ref('');

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
    from_date.value = null;
    to_date.value = null;
    search_order.value.to_date = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    orderStatus.value = 0;
    sorting_value.value = 0;
    paymentMethod.value = '';
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
        await axios.get(route('cart.bill.all'), {
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
                payment_method: paymentMethod.value,
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

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    from_date.value = null;
    to_date.value = null;
    search_order.value.to_date = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    orderStatus.value = 0;
    sorting_value.value = 0;
    paymentMethod.value = '';
    page.value = 1;
    getCurrency();
    getBusinessDetails();
}

const errorPrint = () => {
    errorMessage('The order payment is not done!');
}

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
        // Ensure bill.sub_total is a string or default to empty string
        const amount = parseFloat((bill.sub_total || 0).toString().replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfDiscount = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        // Ensure bill.discount is a string or default to empty string
        const amount = parseFloat((bill.discount || 0).toString().replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        // Ensure bill.total is a string or default to empty string
        const amount = parseFloat((bill.total || 0).toString().replace(/,/g, ''));
        return subTotal + amount;
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
        page.value = 1;
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

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.done-status {
    background-color: #DBFFE4;
    color: green;
}
</style>
