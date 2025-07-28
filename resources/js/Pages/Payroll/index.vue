<template>
    <AppLayout title="Payrolls">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Manage Payrolls
                                </h1>

                            </div>

                            <!-- Desktop View -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-none d-md-flex justify-content-end align-items-center">
                                <Link :href="route('payrolls.deleted.list')" data-toggle="tooltip"
                                    data-placement="bottom" title="Deleted list" class="btn trash-btn me-2 ps-3 pe-2">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>

                                <div @click.prevent="exportPayrollReport" class="btn btn-info me-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Export Payroll Report"
                                    style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up"></i> Export
                                </div>
                                <div @click.prevent=newPayroll(); class="btn btn-info" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new payroll" style="font-weight: bold">
                                    <i class="bi bi-plus-lg"></i> Add Payroll
                                </div>
                            </div>

                            <!-- Mobile view -->
                            <div
                                class="col-12 col-sm-6 col-md-6 d-flex d-md-none justify-content-end align-items-center">
                                <Link :href="route('payrolls.deleted.list')" data-toggle="tooltip"
                                    data-placement="bottom" title="Deleted list" class="btn trash-btn ps-3 pe-2">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>

                                <div @click.prevent="exportPayrollReport"
                                    class="btn btn-info export-btn ps-3 pe-2 ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Export payroll report" style="font-weight: bold">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </div>
                                <div @click.prevent=newPayroll(); class="btn btn-info ms-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new payroll" style="font-weight: bold">
                                    <i class="bi bi-plus-lg"></i> Add Payroll
                                </div>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-3 col-xxl-1 mb-xl-0 pe-xxl-0">
                                        <input v-model="codeFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_employee" :options="employees.map(employee => ({
                                            ...employee,
                                            company: truncateEmployeeText(employee.company),
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
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <Multiselect v-model="search_select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select Currency"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-xl-3 col-xxl-1 align-self-end">
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
                                    v-if="emptyImageVal == 1 && codeFilter == '' && search_employee == null && from_date == null && to_date == null">

                                    <!-- Icon for No Payrolls State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-file-earmark-text fs-1"></i>
                                    </div>

                                    <!-- Heading for No Payrolls State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Payrolls</h2>
                                    </div>

                                    <!-- Subtext for No Payrolls State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your payrolls will appear here once available.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (codeFilter != '' || search_employee != null || from_date != null || to_date != null)">

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
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Code</th>
                                                    <th>Employee</th>
                                                    <th>Date</th>
                                                    <th class="text-center">Currency</th>
                                                    <th class="text-end">Amount</th>
                                                    <th class="text-end pe-3">Actions</th>
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
                                                    <td class="py-2 text-center ps-3"
                                                        @click.prevent="viewPayroll(payroll.id)">{{
                                                            payroll.currency_name }}</td>
                                                    <td class="py-2 text-end" @click.prevent="viewPayroll(payroll.id)">
                                                        {{
                                                            payroll.formatted_amount }}</td>
                                                    <td class="py-2 text-end pe-1">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Print voucher button -->
                                                            <button
                                                                class="border btn btn-sm border-dark texr-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Print voucher"
                                                                @click.prevent="payrollPrint(payroll.id)">
                                                                <i class="p-2 bi bi-printer fs-3 text-dark"></i>
                                                            </button>

                                                            <!-- Delete payroll button -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete payroll"
                                                                @click="deletePayroll(payroll.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr v-if="search_select_currency != null && search_select_currency.id != 0"
                                                    class="mb-5">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="py-2 pt-4 text-center text-gray-700 fw-bold">TOTAL</td>
                                                    <td class="py-2 pt-4 text-gray-700 text-end fw-bold">{{
                                                        totalOfAmount
                                                        }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
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

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <!-- Print Voucher Button -->
                                                                <a href="javascript:void(0);" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Print voucher"
                                                                    @click.prevent="payrollPrint(payroll.id)">
                                                                    <i class="p-2 bi bi-printer fs-5 text-dark"></i>
                                                                </a>

                                                                <!-- Delete Payroll Button -->
                                                                <a href="javascript:void(0);" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Delete payroll"
                                                                    @click="deletePayroll(payroll.id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Total Row -->
                                        <div v-if="search_select_currency != null && search_select_currency.id != 0"
                                            class="py-2 text-end">
                                            <div class="text-gray-700 fw-bold">
                                                <span class="pt-4">TOTAL</span>
                                                <span class="pt-4">{{ totalOfAmount }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="payrolls.length > 0" class="my-3 row align-items-center">
                                    <!-- Entries Dropdown Section -->
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <label class="mb-0 me-2">Show</label>
                                        <select name="kt_ecommerce_payrolls_table_length"
                                            aria-controls="kt_ecommerce_payrolls_table"
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
                                            id="kt_ecommerce_payrolls_table_paginate">
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
        </template>

        <template #modal>
            <!-- Payroll Filter Modal -->
            <div class="modal fade" id="payrollFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="payrollFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Payroll Filters</h5>
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
            <!-- Add Payroll Modal -->
            <div class="modal fade" id="payrollModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New Payroll</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="savePayroll" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Employee <i class="bi bi-plus-lg-fill fs-5"
                                        @click.prevent="showEmployeeModal" style="color: rgb(0, 167, 0);"></i></div>
                                <Multiselect v-model="select_employee" :options="employeesSearch" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Employee" label="name" track-by="id"
                                    max-height="200" @search-change="getEmployeesSearch" :internal-search="false">
                                    <template #noOptions>
                                        <div>Press a key to select Employee</div>
                                    </template>
                                    <template #noResult>
                                        <div>No matching employees found</div>
                                    </template>
                                </Multiselect>

                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />

                                <div class="text-gray-600 col-form-label">Description</div>
                                <textarea v-model="payroll.description" class="form-control"
                                    placeholder="Enter category remark" data-kt-autosize="true"
                                    style="resize: none; font-size: 12px;" rows="2"></textarea>

                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />

                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="payroll.amount" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" />
                                <small v-if="validationErrors.amount"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.amount }}</small>

                                <div class="text-gray-600 col-form-label">Receipt Image</div>
                                <div class="col-lg-12">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="overflow: hidden; position: relative;">
                                            <img v-if="payroll.image_url" :src="payroll.image_url"
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
                                                id="payroll_image" name="avatar" @change="onFileChange">
                                            <input type="hidden" name="avatar_remove">
                                        </label>

                                        <span
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg"></i> </span>

                                        <span v-if="payroll.image_url" @click.prevent="selectImageRemove"
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
                                        <button type="submit" class="btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Payroll Modal -->
            <div class="modal fade" id="payrollUpdateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Update Payroll</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="updatePayroll" enctype="multipart/form-data">

                                <div class="text-gray-600 col-form-label">Code</div>
                                <input v-model="edit_payroll.code" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" disabled />

                                <div class="text-gray-600 col-form-label">Employee</div>
                                <Multiselect v-model="edit_select_employee" :options="employeesSearch" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Employee" label="name" track-by="id"
                                    max-height="200" @search-change="getEmployeesSearch" :internal-search="false">
                                    <template #noOptions>
                                        <div>Press a key to select Employee</div>
                                    </template>
                                    <template #noResult>
                                        <div>No matching employees found</div>
                                    </template>
                                </Multiselect>

                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="edit_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />

                                <div class="text-gray-600 col-form-label">Description</div>
                                <textarea v-model="edit_payroll.description" class="form-control"
                                    placeholder="Enter category remark" data-kt-autosize="true"
                                    style="resize: none; font-size: 12px;" rows="2"></textarea>

                                <div class="text-gray-600 col-form-label">Currency</div>
                                <Multiselect v-model="edit_select_currency" :options="currencies" class="z__index"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    max-height="200" />

                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="edit_payroll.amount" type="cost" class="form-control form-control-sm"
                                    placeholder="Amount" />
                                <small v-if="validationErrors.amount"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.amount }}</small>

                                <div class="text-gray-600 col-form-label">Receipt Image</div>

                                <div class="col-lg-12">
                                    <div class="image-input image-input-outline" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="overflow: hidden; position: relative;">
                                            <img v-if="edit_payroll.image_url" :src="edit_payroll.image_url"
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
                                                id="payroll_image" name="avatar" @change="onFileChangeEdit">
                                            <input type="hidden" name="avatar_remove">
                                        </label>

                                        <span
                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x-lg"></i> </span>

                                        <span v-if="edit_payroll.image_url"
                                            @click.prevent="removeImage(edit_payroll.id)"
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
            <div class="modal fade" id="payrollDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this payroll?
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

            <!-- Add Employee Modal -->
            <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewEmployee" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title" style="color: #071437">Add New Employee</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            @click="closeEmployeeModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Name</lable>
                                        <input v-model="employeeData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Employee Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Company</lable>
                                        <input v-model="employeeData.company" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Company Name" />
                                        <small v-if="validationErrors.company"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.company }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="employeeData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="employeeData.email" type="email"
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
                                                <input v-model="employeeData.email2" type="email"
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
                                                <input v-model="employeeData.email3" type="email"
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
                                        <input v-model="employeeData.contact" type="text"
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
                                                <input v-model="employeeData.contact2" type="text"
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
                                                <input v-model="employeeData.contact3" type="text"
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
                                        <input v-model="employeeData.website" type="text"
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
const payrolls = ref([]);
const emptyImageVal = ref(0);
const employees = ref([]);
const select_employee = ref([]);
const edit_select_employee = ref([]);
const emailIndex = ref(1);
const phoneIndex = ref(1);
const from_date = ref(null);
const to_date = ref(null);
const product = ref({});
const payroll = ref({});
const edit_payroll = ref({});
const employeeData = ref({});
const stock = ref({});
const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_employee = ref(null);
const search_employee_id = ref(null);
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
const employeesSearch = ref([]);
const payroll_image = ref(null);
const edit_payroll_image = ref(null);

const search_select_currency = ref({});
const previousCurrency = ref({});



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
    payroll.value.image = e.target.files[0];
    payroll_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            payroll.value.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onFileChangeEdit = (e) => {
    edit_payroll.value.image = e.target.files[0];
    edit_payroll_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_payroll.value.image_url = e.target.result;
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
    await nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_employee.value = null;
    from_date.value = null;
    to_date.value = null;
    sorting_value.value = 0;
    getCurrency();
    await getBusinessDetails();
    await nextTick(() => {
        loading_bar.value.finish();
    });
    $('#payrollFilterModal').modal('hide');
};

const reload = async (perPageCount) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('payrolls.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_code: codeFilter.value,
                search_employee: search_employee_id.value,
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

const exportPayrollReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.payroll.export"),
            {
                search_from_date: search_order.value.from_date,
                search_to_date: search_order.value.to_date,
                search_currency: search_select_currency.value,
                employee: search_employee.value,
                payrolls: payrolls.value,
                code: codeFilter.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;


        // file name
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `PayrollReport-${formattedDate}.xlsx`;

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

watch(search_employee, (newValue) => {
    if (newValue) {
        search_employee_id.value = newValue.id;
    } else {
        search_employee_id.value = null;
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
    if (newValue) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_order.value.to_date = endDate;
        getSearch();
    } else {
        search_order.value.to_date = null;
    }
});

const getEmployees = async () => {
    try {
        const response = (await axios.get(route('employee.multiselect.all'))).data;
        employees.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployeesSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('employee.multiselect.all.search', { query }));
            employeesSearch.value = response.data;
        } catch (error) {
            employeesSearch.value = [];
        }
    } else {
        employeesSearch.value = [];
    }
};

const savePayroll = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        if (select_employee.value != null) {
            payroll.value.employee_id = select_employee.value.id;
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

        if (edit_payroll.value.employee_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.employee_id))).data
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

        if (edit_payroll.value.employee_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.employee_id))).data
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


        if (edit_select_employee.value != null) {
            edit_payroll.value.employee_id = edit_select_employee.value.id;
        } else {
            edit_payroll.value.employee_id = null;
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

const deletePayroll = async (id) => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        nextTick(() => {
            loading_bar.value.start();
        });
        try {
            const response = (await axios.get(route("payroll.get", id))).data;
            edit_payroll.value = response;

            $('#payrollDeleteModal').modal('show');
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

        const response = (await axios.delete(route("payroll.delete", edit_payroll.value.id))).data;
        successMessage('Payroll deleted successfully!');
        $('#payrollDeleteModal').modal('hide');
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

const clearFilters = async () => {
    await nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    search_employee.value = null;
    from_date.value = null;
    to_date.value = null;
    sorting_value.value = 0;
    getCurrency();
    await getBusinessDetails();
    await nextTick(() => {
        loading_bar.value.finish();
    });
}

function openNewModal() {
    nextTick(() => {
        loading_bar.value.start();
    });
    product.value = {};
    select_employee.value = null;
    payroll.image_url = null;
    const fileInput = document.getElementById('payroll_image');
    selectImageRemove();
    $('#payrollModal').modal('show');
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

watch(search_select_currency, (newValue) => {
    if (search_select_currency.value) {
        previousCurrency.value = search_select_currency.value;
        page.value = 1;
        reload();
    } else {
        search_select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data.data;
        business_details.value = response;

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

const payrollPrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('payroll.print', id), { responseType: "blob" });
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

const newPayroll = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        router.visit(route('payroll.create'));
    } else {
        errorMessage('Access denied');
    }

};

const totalOfAmount = computed(() => {
    return payrolls.value.reduce((subTotal, payroll) => {
        const amount = parseFloat(payroll.amount.replace(/,/g, ''));
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

onMounted(() => {
    getEmployees();
    getCurrency();
    getBusinessDetails();
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
