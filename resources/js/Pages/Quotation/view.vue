<template>
    <AppLayout title="Quotations">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Quotations
                                </h1>

                            </div>
                            <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                                <a :href="route('quotation.deleted.list')" data-toggle="tooltip" data-placement="bottom"
                                    title="Deleted list" class="btn trash-btn me-2 ps-3 pe-2">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </a>
                                <div @click.prevent="newQuotation()" class="btn btn-info" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new quotation" style="font-weight: bold;">
                                    <i class="bi bi-plus-lg"></i> Add Quotation
                                </div>
                            </div>
                        </div>

                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">

                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="quotationStatus" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="2">Quotation</option>
                                            <option value="1">Invoice</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="search" type="text" class="mb-2 form-control form-control-sm"
                                            placeholder="Search by Quotation No." @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">

                                        <input id="header_fields" v-model="header_fields" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Parameters"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">

                                        <Multiselect v-model="select_search_customer" :options="truncatedCustomers"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <!-- <div class="mb-2 col-xl-2 col-xxl-1 mb-xl-0 pe-xxl-0">
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

                                    <!-- <div class="col-md-1 align-self-end">

                                    </div> -->
                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openQuotationFilterModal">
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
                                    v-if="emptyImageVal == 1 && quotationStatus == 0 && search == null && select_search_customer == null && select_search_cashier == null && from_date == null && to_date == null">

                                    <!-- Icon for Empty Quotations State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-file-earmark-text fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Quotations State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Quotations</h2>
                                    </div>

                                    <!-- Subtext for Empty Quotations State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Quotations will appear here.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (quotationStatus != 0 || search != null || select_search_customer != null || select_search_cashier != null || from_date != null || to_date != null)">

                                    <!-- Icon for No Search Results -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>

                                    <!-- Heading for No Search Results -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Result Found</h2>
                                    </div>

                                    <!-- Optional Subtext for No Search Results -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Try adjusting your search or filter criteria.</p>
                                    </div>
                                </div>

                                <div v-if="bills.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="pe-0 ps-0 text-end i-icon-col"></th>
                                                    <th class="text-center ps-0 status-col">STATUS</th>
                                                    <th class="ps-20">QUOTATION NO.</th>
                                                    <th>CUSTOMER</th>
                                                    <th>CREATED BY</th>
                                                    <th>DATE</th>
                                                    <th class="text-center">CURRENCY</th>
                                                    <th class="text-end">TOTAL</th>
                                                    <th class="text-end pe-3">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="(bill, index) in bills" :key="index" class="cursor-pointer">
                                                    <td class="py-2 pe-2 text-end i-icon-col">
                                                        <i v-if="bill.ref_no"
                                                            class="pl-2 cursor-pointer bi bi-info-circle"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            :title=bill.ref_no></i>
                                                    </td>
                                                    <td class="py-2 text-center cursor-pointer ps-0 status-col"
                                                        @click.prevent="editQuotation(bill.id)">
                                                        <div v-if="bill.convert_status == 1">
                                                            <div v-if="bill.convert_status == 1"
                                                                class="badge badge-light-success invoice-status ">
                                                                INVOICE
                                                            </div>
                                                        </div>
                                                        <div v-if="bill.convert_status == 0">
                                                            <div class="badge badge-secondary quotation-status">
                                                                QUOTATION
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        class="py-2 cursor-pointer ps-20">{{
                                                            bill.code }}</td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        v-if="bill.customer_id == 0 || bill.customer_id == null"
                                                        class="py-2 cursor-pointer">
                                                        Walking
                                                        Customer</td>
                                                    <td @click.prevent="editQuotation(bill.id)" v-else
                                                        class="py-2 cursor-pointer">{{ truncateText(bill.customer_name)
                                                        }}</td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        v-if="bill.cashier != null" class="py-2 cursor-pointer">
                                                        {{ truncateText(bill.cashier_name) }}
                                                    </td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        v-if="bill.cashier == null" class="py-2 cursor-pointer"></td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        class="py-2 cursor-pointer">{{
                                                            formatDate(bill.quotation_date) }}</td>
                                                    <td class="py-2 text-center"
                                                        @click.prevent="editQuotation(bill.id)">
                                                        {{ bill.currency_name }}
                                                    </td>
                                                    <td @click.prevent="editQuotation(bill.id)"
                                                        class="py-2 cursor-pointer text-end">{{ bill.formatted_total }}
                                                    </td>
                                                    <td class="py-2 text-end pe-1">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Copy shareable link button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Copy shareable link"
                                                                @click.prevent="createQuotationLink(bill.id)">
                                                                <i class="p-2 bi bi-link fs-5 text-dark"></i>
                                                            </button>

                                                            <!-- Print quotation button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Print quotation"
                                                                @click.prevent="historyPrint(bill.id)">
                                                                <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                            </button>

                                                            <!-- Delete quotation button -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete quotation"
                                                                @click.prevent="deleteInvoice(bill.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
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
                                                    <td></td>
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
                                                    <!-- Status -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Status</td>
                                                        <td class="text-end">
                                                            <span v-if="bill.convert_status == 1"
                                                                class="badge badge-light-success">INVOICE</span>
                                                            <span v-else class="badge badge-secondary">QUOTATION</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Quotation No. -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Quotation No.</td>
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
                                                        <td class="text-end">{{ truncateText(bill.cashier_name) }}</td>
                                                    </tr>

                                                    <!-- Date -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Date</td>
                                                        <td class="text-end">{{ formatDate(bill.quotation_date) }}</td>
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

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">

                                                                <!-- Shareable Link Icon -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    title="Copy shareable link"
                                                                    @click.prevent="createQuotationLink(bill.id)">
                                                                    <i class="p-2 bi bi-link fs-5 text-dark"></i>
                                                                </a>

                                                                <!-- Print Icon -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    title="Print quotation"
                                                                    @click.prevent="historyPrint(bill.id)">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                                </a>

                                                                <!-- Delete Icon -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    title="Delete quotation"
                                                                    @click.prevent="deleteInvoice(bill.id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Total Row if Currency Filter is Applied -->
                                        <div v-if="search_select_currency != null && search_select_currency.id != 0"
                                            class="mb-5">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-gray-600 text-end fw-bold" colspan="6">TOTAL
                                                        </td>
                                                        <td class="text-gray-600 text-end fw-bold">{{ totalOfTotal }}
                                                        </td>
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
            <!-- Quotation Filter Modal -->
            <div class="modal fade" id="quotationFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="quotationFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Quotation Filters</h5>
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
                                            v-model="quotationStatus" data-control="select2"
                                            data-placeholder="Select Status" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="2">Quotation</option>
                                            <option value="1">Invoice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Quotation No."
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">

                                        <input id="modalSearchHeaderFields" v-model="header_fields" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Parameters"
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
                                        <Datepicker id="modalFromDate" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :format="'dd/MM/yyyy'" :upper-limit="to_date" />
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
                                        <button @click="quotationFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyQuotationFilter" class="btn btn-info ms-4 fw-bold">
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
                            Are you sure you want to delete this quotation?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete quotation"
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
import moment from 'moment';
import Datepicker from 'vue3-datepicker'
import { Link, router, usePage } from "@inertiajs/vue3";

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
const select_convert_status = ref(null);
const customers = ref([]);
const cashiers = ref([]);
const quotationStatus = ref(0);
const sorting_value = ref(0);
const convert_status = ref([
    { id: 0, name: 'Quotation' },
    { id: 1, name: 'Invoice' },
]);

const quotation = ref({});

const loading_bar = ref(null);

const placeholderText = 'DD/MM/YYYY';

const currencies = ref([]);
const search_select_currency = ref({});
const previousCurrency = ref({});


const debouncedSearch = ref('');
const debouncedRefNoSearch = ref('');

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
    debouncedSearch.value = search.value;
    debouncedRefNoSearch.value = header_fields.value;
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

const openQuotationFilterModal = () => {
    loading_bar.value.start();
    $('#quotationFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyQuotationFilter = () => {
    $('#quotationFilterModal').modal('hide');
    reload();
};

const quotationFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    quotationStatus.value = 0;
    sorting_value.value = 0;
    select_convert_status.value = '';
    page.value = 1;
    header_fields.value = '';
    debouncedSearch.value = '';
    debouncedRefNoSearch.value = '';
    search_order.value = {};
    getCurrency();
    getBusinessDetails();
    $('#quotationFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('quotation.bill.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": debouncedSearch.value,
                search_order_customer: search_order.value.customer_id,
                search_order_cashier: search_order.value.cashier_id,
                select_convert_status: quotationStatus.value,
                sorting_value: sorting_value.value,
                search_from_date: search_order.value.from_date,
                search_to_date: search_order.value.to_date,
                currency: search_select_currency.value?.id,
                header_fields: debouncedRefNoSearch.value,
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
        const print = await axios.get(route('quotation.print', id), { responseType: "blob" });
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

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        nextTick(() => {
            loading_bar.value.start();
        });
        try {
            const response = (await axios.get(route("quotation.delete.confirm", id))).data;
            quotation.value = response;

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

        const response = (await axios.delete(route("quotation.delete", quotation.value.id))).data;
        successMessage('Quotation deleted successfully!');

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

const editQuotation = async (id) => {

    try {
        const response = await axios.get(route('quotation.edit', id));
        if (response.data.id) {
            window.location.href = route('quotation.load', response.data.id);
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

// watch(to_date, (newValue) => {
//     search_order.value.to_date = newValue;
//     getSearch();
// });

watch(quotationStatus, (newValue) => {
    quotationStatus.value = newValue;
    getSearch();
});

watch(from_date, (newValue) => {
    search_order.value.from_date = newValue;
    getSearch();
});

watch(to_date, (newValue) => {
    if (newValue) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_order.value.to_date = endDate;
        getSearch();
    } else {
        to_date.value = null;
    }
});

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

const clearFilters = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    select_search_cashier.value = null;
    from_date.value = null;
    to_date.value = null;
    quotationStatus.value = 0;
    sorting_value.value = 0;
    select_convert_status.value = '';
    page.value = 1;
    header_fields.value = '';
    debouncedSearch.value = '';
    debouncedRefNoSearch.value = '';
    search_order.value = {};
    getCurrency();
    getBusinessDetails();
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

const createQuotationLink = async (quotationId) => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        nextTick(() => {
            loading_bar.value.start();
        });
        try {
            const response = await axios.get(route('quotation.link.get', quotationId));
            await navigator.clipboard.writeText(response.data);
            successMessage('link copied successfully')
            nextTick(() => {
                loading_bar.value.finish();
            });
        } catch (error) {
            nextTick(() => {
                loading_bar.value.finish();
            });
            errorMessage(error);
        }
    } else {
        errorMessage('Access denied');
    }

};

const newQuotation = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('quotation.index'));
    } else {
        errorMessage('Access denied');
    }

};

const totalOfTotal = computed(() => {
    return bills.value.reduce((subTotal, bill) => {
        const amount = parseFloat(bill.total.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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

.invoice-status {
    background-color: #DBFFE4;
    color: green
}

.ref-no {
    width: 10px
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.i-icon-col {
    width: 30px;
}

.status-col {
    width: 85px;
}
</style>
