<template>
    <AppLayout title="Deleted Invoices">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2 justify-content-center">
                    <div class="mt-5 col-lg-12 mt-lg-0 custom-component">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Deleted&nbsp;Invoices&nbsp;
                                </h1>
                                <!-- <a style="margin-right: 100%;"
                                    href="https://www.youtube.com/watch?v=NETDg3u5JBQ&list=PL5kz-kd2_LQu2PxJtC3OMeT1r3AFAqh-O&index=5"
                                    target="_blank"><i class="bi bi-question-circle fs-2" style="color:#BFC9CA;"></i></a> -->
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <!-- <Link :href="route('invoice.deleted.list')" class="btn btn-light-danger me-2 ps-3 pe-2">
                                <i class="bi bi-list-task fs-2"></i>
                                </Link>
                                <Link :href="route('invoice.index')" class="btn btn-info"
                                    style="font-weight: bold">
                                <i class="bi bi-plus-lg"></i> NEW INVOICE
                                </Link> -->
                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <!-- <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">STATUS</div>
                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="orderStatus" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Paid</option>
                                            <option value="2">Partial</option>
                                            <option value="3">None</option>
                                        </select>
                                    </div> -->
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">BILL NO.
                                        </div>
                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Bill No." @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <label for="search"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">PARAMETERS</label>
                                        <input id="header_fields" v-model="header_fields" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Parameters"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">CUSTOMER
                                        </div>
                                        <Multiselect v-model="select_search_customer" :options="truncatedCustomers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">CREATED
                                            BY</div>
                                        <Multiselect v-model="select_search_cashier" :options="cashiers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCashier" :searchable="true"
                                            placeholder="Created By" label="name" track-by="id" max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">FROM DATE
                                        </div>
                                        <!-- <input v-model="from_date" type="date" class="form-control form-control-sm"
                                            placeholder="Search" @keyup="getSearch" /> -->
                                        <Datepicker v-if="to_date && !from_date || to_date && from_date"
                                            v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :format="'dd/MM/yyyy'"
                                            :upperLimit="to_date" />
                                        <Datepicker v-else v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">TO DATE
                                        </div>
                                        <!-- <input v-model="to_date" type="date" class="form-control form-control-sm"
                                            placeholder="Search" @keyup="getSearch" /> -->
                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">CURRENCY
                                        </div>
                                        <Multiselect v-model="search_select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-8 col-xxl-2 align-self-end d-flex justify-content-between">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>

                                        <div class="mb-2 dropdown">
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
                                                                        @click="setSortingValue(1)">Bill No</a>
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
                                <div class="row"
                                    v-if="bills.length < 1 && orderStatus == 0 && search == '' && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null && search_select_currency == null">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-trash fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No any deleted Invoices</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">Your deleted invoices will appear here</p>
                                    </div>
                                </div>
                                <div class="row"
                                    v-if="bills.length == 0 && (orderStatus != 0 || search != '' || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null || search_select_currency != null)">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12 mb-15">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No result found</h1>
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
                                                    <th class="text-center pe-1 i-icon-col"></th>
                                                    <th class="text-center status-col">STATUS</th>
                                                    <th>BILL NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-end">SUB TOTAL</th>
                                                    <th class="text-end">DISCOUNT</th>
                                                    <th class="text-end pe-3">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index">
                                                    <!-- <td class="py-1 text-center">
                                                        <a v-if="bill.status == 1" href="javascript:void(0)"
                                                            @click.prevent="historyPrint(bill.id)"
                                                            class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                class="bi bi-printer"></i></a>
                                                        <a v-else-if="bill.status == 4" href="javascript:void(0)"
                                                            @click.prevent="returnPrint(bill.id)"
                                                            class="btn btn-icon btn-outline btn-outline-primary btn-active-light-primary btn-sm"><i
                                                                class="bi bi-printer"></i></a>
                                                        <a v-else href="javascript:void(0)" @click.prevent="errorPrint"
                                                            class="btn btn-icon btn-outline btn-outline-danger btn-active-light-danger btn-sm"><i
                                                                class="bi bi-printer"></i></a>
                                                    </td> -->
                                                    <td class="py-2 text-center pe-0 i-icon-col">
                                                        <i v-if="bill.ref_no"
                                                            class="pl-3 cursor-pointer bi bi-info-circle"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            :title=bill.ref_no></i>
                                                    </td>
                                                    <td class="py-2 text-center cursor-pointer status-col"
                                                        @click.prevent="editInvoice(bill.id)">
                                                        <!-- <div v-if="bill.status == 1 && bill.credit_status == 1"
                                                            class="badge badge-success paid-status">PAID
                                                        </div>
                                                        <div v-if="bill.credit_status == 0 && bill.customer_paid == 0"
                                                            class="badge none-status">
                                                            NONE
                                                        </div>
                                                        <div v-if="bill.status == 4" class="badge badge-light-info">
                                                            RETURN
                                                        </div>
                                                        <div v-if="bill.credit_status == 0 && bill.customer_paid > 0"
                                                            class="badge badge-secondary partial-status">
                                                            PARTIAL
                                                        </div> -->
                                                        <div class="badge none-status">
                                                            DELETED
                                                        </div>
                                                    </td>
                                                    <td class="py-2 cursor-pointer"
                                                        @click.prevent="editInvoice(bill.id)">{{
                                                            bill.code }}</td>
                                                    <td v-if="bill.customer_id == null" class="py-2 cursor-pointer"
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
                                                </tr>
                                                <!-- <tr class="mb-5">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="py-2 pt-4 text-gray-700 text-end fw-bold">TOTAL</td>
                                                    <td class="py-2 pt-4 text-gray-700 text-end fw-bold">{{ totalOfSubTotal
                                                                                                            }}</td>
                                                    <td class="py-2 pt-4 text-gray-700 text-end fw-bold">{{ totalOfDiscount
                                                                                                            }}</td>
                                                    <td class="py-2 pt-4 text-gray-700 text-end fw-bold pe-3">{{
                                                                                                            totalOfTotal }}</td>
                                                </tr> -->

                                                <!-- End of Table Data Rows -->
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
                                                                <!-- <div
                                                                    v-if="bill.credit_status == 0 && bill.customer_paid > 0">
                                                                    <div
                                                                        class="text-center badge badge-secondary partial-status">
                                                                        PARTIAL
                                                                    </div>
                                                                </div>
                                                                <div v-if="bill.status == 4">
                                                                    <div class="text-center badge badge-light-info">
                                                                        RETURN
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    v-if="bill.credit_status == 0 && bill.customer_paid == 0">
                                                                    <div class="text-center badge none-status">
                                                                        NONE
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    v-if="bill.credit_status == 1 && bill.credit_status == 1">
                                                                    <div class="text-center badge paid-status">
                                                                        PAID
                                                                    </div>
                                                                </div> -->
                                                                <div>
                                                                    <div class="text-center badge none-status">
                                                                        DELETED
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
                                                                SUB TOTAL</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_sub_total }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                DISCOUNT</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{ bill.formatted_discount }}</span>
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

                                                        <div class="row row-divider"
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                            </div>
                                                            <div class="col-8 right-side text-end ">

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
                                <div class="my-3 row ps-1 ps-md-0" v-if="bills.length > 0">

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
                                <!-- <div class="col-12">
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
                                </div> -->
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this invoice?
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
const select_search_customer = ref(null);
const select_search_cashier = ref(null);
const customers = ref([]);
const cashiers = ref([]);
const orderStatus = ref(0);

const invoice = ref({});

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';

const currencies = ref([]);
const search_select_currency = ref({});
const previousCurrency = ref({});

const sorting_value = ref(0);



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
    search.value = "";
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
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
        await axios.get(route('invoice.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_order_customer: search_order.value.customer_id,
                search_order_cashier: search_order.value.cashier_id,
                search_order_from_date: search_order.value.from_date,
                search_order_to_date: search_order.value.to_date,
                search_order_status: search_order.value.orderStatus,
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
    search.value = "";
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    orderStatus.value = 0;
    page.value = 1;
    header_fields.value = null;
    getCurrency();
    getBusinessDetails();
}

const errorPrint = () => {
    errorMessage('The order payment is not done!');
}

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

const setSortingValue = async (value) => {
    sorting_value.value = value;
    getSearch();
};

onMounted(() => {
    getCurrency();
    getBusinessDetails();
    getCustomer();
    getCashier();
    clearFilters();
});

</script>

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
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
    background-color: rgba(63, 63, 63, 0.100);
    color: rgba(63, 63, 63, 0.600);
}

.i-icon-col {
    width: 30px;
}

.status-col {
    width: 80px;
}
</style>
