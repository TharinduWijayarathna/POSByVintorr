<template>
    <AppLayout title="Expenses">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-4 col-md-4 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    View Expenses
                                </h1>
                            </div>

                            <!-- Desktop View -->
                            <div class="col-sm-8 col-md-8 d-flex justify-content-end d-none d-md-flex">
                                <Link :href="route('expenses.deleted.list')" data-toggle="tooltip"
                                    data-placement="bottom" title="Deleted list" class="btn trash-btn ps-3 pe-2">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>
                                <div @click.prevent="exportExpenseReport" class="btn btn-info ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export expense report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up"></i> Export
                                </div>
                                <div @click.prevent=expenseCategory(); class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Expenses category" style="font-weight: bold">
                                    <i class="bi bi-list"></i> Categories
                                </div>
                                <div @click.prevent=newExpense(); class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new expense" style="font-weight: bold">
                                    <i class="bi bi-plus-lg"></i> Add Expense
                                </div>
                            </div>

                            <!-- Mobile View -->
                            <div class="col-12 col-sm-8 col-md-8 d-flex justify-content-end d-md-flex d-lg-none">
                                <Link :href="route('expenses.deleted.list')" data-toggle="tooltip"
                                    data-placement="bottom" title="Deleted list" class="btn trash-btn ps-3 pe-2">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>
                                <div @click.prevent="exportExpenseReport" class="btn btn-info export-btn ps-3 pe-2 ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export expense report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </div>
                                <div @click.prevent="expenseCategory()" class="btn btn-info export-btn ps-3 pe-2 ms-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Expenses category"
                                    style="font-weight: bold">
                                    <i class="bi bi-list fs-2 export-icon ms-1"></i>
                                </div>
                                <div @click.prevent=newExpense(); class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new expense" style="font-weight: bold">
                                    <i class="bi bi-plus-lg fs-2"></i>Add Expense
                                </div>
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

                                        <Datepicker v-else="to_date && from_date" v-model="from_date"
                                            class="mb-2 form-control form-control-sm" :placeholder="placeholderText" />

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
                                    <!-- Icon for Empty Expenses State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-wallet2 fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Expenses State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Expenses</h2>
                                    </div>

                                    <!-- Subtext for Empty Expenses State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your expenses will appear here.</p>
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
                                    <!-- Desktop view -->
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
                                                    <th class="text-end">Amount</th>
                                                    <th></th>
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
                                                            truncateText(expense.supplier_name) }}
                                                    </td>
                                                    <td v-else class="py-2 ps-3"
                                                        @click.prevent="viewExpense(expense.id)">
                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        formatDate(expense.date) }}</td>
                                                    <td class="py-2 ps-3" @click.prevent="viewExpense(expense.id)">{{
                                                        expense.currency_name }}</td>
                                                    <td class="py-2 text-end" @click.prevent="viewExpense(expense.id)">
                                                        {{
                                                            expense.formatted_amount }}</td>
                                                    <td class="py-2 text-end pe-1">
                                                        <a href="javascript:void(0)" class="p-2"
                                                            @click.prevent="expensePrint(expense.id)"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Print voucher">
                                                            <i class="bi bi-printer fs-3"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="p-2" data-toggle="tooltip"
                                                            data-placement="bottom" title="Delete expense"
                                                            @click="deleteExpense(expense.id)">
                                                            <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr v-if="search_select_currency != null && search_select_currency.id != 0"
                                                    class="mb-5 total-row">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">TOTAL</td>
                                                    <td class="py-2 pt-4 text-gray-600 text-end fw-bold">{{
                                                        totalOfAmount
                                                    }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
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
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ truncateText(expense.category_name) }}</span>
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

                                                        <div class="row row-divider "
                                                            @click.prevent="viewExpense(expense.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                AMOUNT</div>
                                                            <div class="col-8 right-side text-end ">
                                                                <span>{{
                                                                    expense.formatted_amount }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                            </div>
                                                            <div class="col-8 right-side text-end ">
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    @click.prevent="expensePrint(expense.id)"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Print voucher">
                                                                    <i class="bi bi-printer fs-3"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="pl-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete expense"
                                                                    @click="deleteExpense(expense.id)">
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
            <!-- Add Expense Modal -->
            <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New Expense</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="saveExpense" enctype="multipart/form-data">

                                <div class="text-gray-600 col-form-label">Category</div>
                                <Multiselect v-model="select_category" :options="categories" class="z__index"
                                    data-toggle="tooltip" data-placement="bottom" title="Select category"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Category" label="name" track-by="id"
                                    max-height="200" />
                                <small v-if="validationErrors.expense_category_id"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.expense_category_id }}</small>

                                <div class="text-gray-600 col-form-label">Supplier <i class="bi bi-plus-lg-fill fs-5"
                                        @click.prevent="showSupplierModal" style="color: rgb(0, 167, 0);"></i></div>
                                <Multiselect v-model="select_supplier" :options="suppliersSearch" class="z__index"
                                    data-toggle="tooltip" data-placement="bottom" title="Select supplier"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Supplier" label="name" track-by="id"
                                    max-height="200" @search-change="getSuppliersSearch" :internal-search="false">
                                    <template #noOptions>
                                        <div>Press a key to select Supplier</div>
                                    </template>
                                    <template #noResult>
                                        <div>No matching suppliers found</div>
                                    </template>
                                </Multiselect>

                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />

                                <div class="text-gray-600 col-form-label">Description</div>
                                <textarea v-model="expense.description" class="form-control"
                                    placeholder="Enter category remark" data-kt-autosize="true"
                                    style="resize: none; font-size: 12px;" rows="2"></textarea>

                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                                    data-toggle="tooltip" data-placement="bottom" title="Select currency"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />

                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="expense.amount" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" />
                                <small v-if="validationErrors.amount"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.amount }}</small>

                                <div class="text-gray-600 col-form-label">Receipt Image</div>
                                <div class="col-lg-12">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="overflow: hidden; position: relative;">
                                            <img v-if="expense.image_url" :src="expense.image_url"
                                                class="mb-2 img-fluid"
                                                style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                            <img v-else src="@/../src/assets/media/stock/food/product_image.webp"
                                                class="mb-2 img-fluid"
                                                style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                        </div>

                                        <label
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            aria-label="Change avatar" data-bs-original-title="Change avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-pencil-square text-dark fs-3"></i>
                                            <input type="file" ref="fileInput" accept="image/jpg, image/png"
                                                id="expense_image" name="avatar" @change="onFileChange">
                                            <input type="hidden" name="avatar_remove">
                                        </label>

                                        <span
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg"></i> </span>

                                        <span v-if="expense.image_url" @click.prevent="selectImageRemove"
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg fs-3"></i> </span>
                                    </div>

                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>

                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Add new expense">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Expense Modal -->
            <div class="modal fade" id="expenseUpdateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Update Expense</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="updateExpense" enctype="multipart/form-data">

                                <div class="text-gray-600 col-form-label">Code</div>
                                <input v-model="edit_expense.code" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" disabled />

                                <div class="text-gray-600 col-form-label">Category</div>
                                <Multiselect v-model="edit_select_category" :options="categories" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Category" label="name" track-by="id"
                                    max-height="200" />
                                <small v-if="validationErrors.expense_category_id"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.expense_category_id }}</small>

                                <div class="text-gray-600 col-form-label">Supplier</div>
                                <Multiselect v-model="edit_select_supplier" :options="suppliersSearch" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Supplier" label="name" track-by="id"
                                    max-height="200" @search-change="getSuppliersSearch" :internal-search="false">
                                    <template #noOptions>
                                        <div>Press a key to select Supplier</div>
                                    </template>
                                    <template #noResult>
                                        <div>No matching suppliers found</div>
                                    </template>
                                </Multiselect>

                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="edit_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />

                                <div class="text-gray-600 col-form-label">Description</div>
                                <textarea v-model="edit_expense.description" class="form-control"
                                    placeholder="Enter category remark" data-kt-autosize="true"
                                    style="resize: none; font-size: 12px;" rows="2"></textarea>

                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="edit_select_currency" :options="currencies" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />

                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="edit_expense.amount" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" />
                                <small v-if="validationErrors.amount"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.amount }}</small>

                                <div class="text-gray-600 col-form-label">Receipt Image</div>

                                <div class="col-lg-12">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="overflow: hidden; position: relative;">
                                            <img v-if="edit_expense.image_url" :src="edit_expense.image_url"
                                                class="mb-2 img-fluid"
                                                style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                            <img v-else src="@/../src/assets/media/stock/food/product_image.webp"
                                                class="mb-2 img-fluid"
                                                style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                        </div>

                                        <label
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            aria-label="Change avatar" data-bs-original-title="Change avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-pencil-square text-dark fs-3"></i>
                                            <input type="file" ref="fileInput" accept="image/jpg, image/png"
                                                id="expense_image" name="avatar" @change="onFileChangeEdit">
                                            <input type="hidden" name="avatar_remove">
                                        </label>

                                        <span
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg"></i> </span>

                                        <span v-if="edit_expense.image_url"
                                            @click.prevent="removeImage(edit_expense.id)"
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg fs-3"></i> </span>
                                    </div>

                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>

                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-light-info" style="font-weight: bold">
                                            UPDATE
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="expenseDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this expense?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete expense"
                                @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Supplier Modal -->
            <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewSupplier" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title" style="color: #071437">Add New Supplier</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            @click="closeSupplierModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Name</lable>
                                        <input v-model="supplierData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Supplier Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Company</lable>
                                        <input v-model="supplierData.company" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Company Name" />
                                        <small v-if="validationErrors.company"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.company }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="supplierData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="supplierData.email" type="email"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                        <small v-if="validationErrors.email"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.email }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 2">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[emailIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="supplierData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 3">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="supplierData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone No.1</lable>
                                        <input v-model="supplierData.contact" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Phone Number1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 2">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[phoneIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="supplierData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 3">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="supplierData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Website</lable>
                                        <input v-model="supplierData.website" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, watch, nextTick, computed } from "vue";
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import { Link, router } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'

import _ from 'lodash';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});
const sorting_value = ref(0);
const expenses = ref([]);
const emptyImageVal = ref(0);
const categories = ref([]);
const suppliers = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_supplier = ref([]);
const edit_select_supplier = ref([]);
const emailIndex = ref(1);
const phoneIndex = ref(1);
const from_date = ref(null);
const to_date = ref(null);
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
const suppliersSearch = ref([]);
const expense_image = ref(null);
const edit_expense_image = ref(null);

const search_select_currency = ref({});


const previousCurrency = ref({});
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

const onFileChange = (e) => {
    expense.value.image = e.target.files[0];
    expense_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            expense.value.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onFileChangeEdit = (e) => {
    edit_expense.value.image = e.target.files[0];
    edit_expense_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_expense.value.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }

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
    search_select_currency.value = null;
    // search_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#expenseFilterModal').modal('hide');
};

const reload = async (perPageCount) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('expenses.all'), {
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

const getSuppliersSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('supplier.multiselect.all.search', { query }));
            suppliersSearch.value = response.data;
        } catch (error) {
            suppliersSearch.value = [];
        }
    } else {
        suppliersSearch.value = [];
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

const deleteExpense = async (id) => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        nextTick(() => {
            loading_bar.value.start();
        });
        try {
            const response = (await axios.get(route("expense.get", id))).data;
            edit_expense.value = response;

            $('#expenseDeleteModal').modal('show');
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

        const response = (await axios.delete(route("expense.delete", edit_expense.value.id))).data;
        successMessage('Expense deleted successfully!');
        $('#expenseDeleteModal').modal('hide');
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

const editStock = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("product.get", id))).data;
        stock.value = response;

        stock_in.value = true;

        $('#stockModal').modal('show');
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

const updateStock = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (stock_in.value == true) {
            stock.value.transaction_type_id = 1;
        }
        if (stock_in.value == "on") {
            stock.value.transaction_type_id = 1;
        } else if (stock_out.value == "on") {
            stock.value.transaction_type_id = 0;
        }

        const response = (await axios.post(route("stock.update", stock.value.id), stock.value)).data;
        successMessage('Stock updated successfully!');

        $('#stockModal').modal('hide');
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
    search_select_currency.value = null;
    // search_date.value = null;
    page.value = 1;
    getCurrency();
    getBusinessDetails();
    nextTick(() => {
        loading_bar.value.finish();
    });
}

function openNewModal() {
    nextTick(() => {
        loading_bar.value.start();
    });
    product.value = {};
    select_category.value = null;
    select_supplier.value = null;
    expense.image_url = null;
    const fileInput = document.getElementById('expense_image');
    selectImageRemove();
    $('#expenseModal').modal('show');
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

const expensePrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('expense.print', id), { responseType: "blob" });
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        previousCurrency.value = search_select_currency.value;
        page.value = 1;
        reload();
    } else {
        search_select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

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

const newExpense = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('expense.create'));
    } else {
        errorMessage('Access denied');
    }

};

const expenseCategory = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('expenses.category'));
    } else {
        errorMessage('Access denied');
    }

};

const totalOfAmount = computed(() => {
    return expenses.value.reduce((subTotal, expense) => {
        const amount = parseFloat(expense.amount.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const exportExpenseReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.expense.export"),
            {
                search_code: codeFilter.value,
                search_category: search_category.value,
                search_supplier: search_supplier.value,
                search_order_from_date: search_expense.value.from_date,
                search_order_to_date: search_expense.value.to_date,
                currency: search_select_currency.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;

        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `ExpenseReport-${formattedDate}.xlsx`;

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();

    } catch (error) {
        convertValidationNotification(error);
    } finally {
        loading_bar.value.finish();
    }
};

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
</style>
