<template>
    <AppLayout title="Customers">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Manage Your Customers
                                </h1>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end">

                                <Link :href="route('customer.deleted.list')" class="btn trash-btn me-2 ps-3 pe-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>
                                <button data-toggle="tooltip" data-placement="bottom" title="Import supplier"
                                    @mouseover="hovered = true" @mouseleave="hovered = false" @click="openImportModal"
                                    class="btn export-btn me-2 ps-4 pe-1"
                                    style="font-weight: bold;  border: 1px solid transparent;">
                                    <i class="bi bi-upload fs-2 export-icon"></i>
                                </button>
                                <button class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new customer"
                                    @click="showCustomerModal(selectedCustomerData = {}, emailIndex = 1, phoneIndex = 1)">
                                    <i class="bi bi-plus-lg"></i> Add Customer
                                </button>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-md-3 mb-md-0">
                                        <input v-model="nameFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search By Name" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-3 mb-md-0">
                                        <input v-model="contactFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search By Phone" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="col-md-2 align-self-end">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                </div>

                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openCustomerFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="customers.length > 0">
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

                                <!-- When table is empty -->
                                <div class="row" v-if="emptyImageVal == 1 && nameFilter == '' && contactFilter == ''">
                                    <!-- Icon for Empty State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-person-circle fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Customers Found</h2>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Once added, your customers will appear here.</p>
                                    </div>
                                </div>

                                <div class="row" v-if="emptyImageVal == 1 && (nameFilter != '' || contactFilter != '')">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No Results Found</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12">
                                        <p class="text-gray-600 fs-5">Try adjusting your search filters.</p>
                                    </div>
                                </div>


                                <div v-if="customers.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Customer</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th class="text-end">Outstanding Balance</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="customer in customers" :key="customer.id"
                                                    class="cursor-pointer">
                                                    <!-- Customer Name -->
                                                    <td class="py-2 ps-3" @click.prevent="viewCustomer(customer.id)">
                                                        {{ truncateText(customer.name) }}
                                                    </td>

                                                    <!-- Contact Number -->
                                                    <td class="py-2" @click.prevent="viewCustomer(customer.id)">
                                                        {{ truncateText(customer.contact) }}
                                                    </td>

                                                    <!-- Email Address -->
                                                    <td class="py-2" @click.prevent="viewCustomer(customer.id)">
                                                        {{ truncateText(customer.email) }}
                                                    </td>

                                                    <!-- Outstanding Balance -->
                                                    <td class="py-2 text-end pe-2"
                                                        @click.prevent="viewCustomer(customer.id)">
                                                        {{ customer.customer_due }}
                                                    </td>

                                                    <!-- Action Buttons -->
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Delete Customer"
                                                                @click="showDeleteModal(customer.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
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
                                        <div v-for="customer in customers" :key="'customer-table-' + customer.id"
                                            style="margin-bottom: 20px;">
                                            <!-- Start a new table for each customer -->
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Customer Name -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Name</td>
                                                        <td class="text-end">{{ truncateText(customer.name) }}</td>
                                                    </tr>

                                                    <!-- Customer Contact -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Phone</td>
                                                        <td class="text-end">{{ truncateText(customer.contact) }}</td>
                                                    </tr>

                                                    <!-- Customer Email -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Email</td>
                                                        <td class="text-end">{{ truncateText(customer.email) }}</td>
                                                    </tr>

                                                    <!-- Outstanding Amount -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Outstanding</td>
                                                        <td class="text-end">{{ customer.customer_due }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <a href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete customer"
                                                                @click="showDeleteModal(customer.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>

                                <!-- Pagination -->
                                <div v-if="customers.length > 0" class="my-3 row align-items-center ps-1 ps-md-0">
                                    <!-- Entries Per Page Dropdown -->
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

                                    <!-- Pagination Info -->

                                    <div class="col-sm-6 d-flex justify-content-end align-items-center">
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>

                                        <!-- Pagination Controls -->
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''">
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
            <!-- Customer Filter Modal -->
            <div class="modal fade" id="customerFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="customerFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Customer Filters</h5>
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
                                        <input id="modalSearch" v-model="nameFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Filter by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <div class="items-center text-muted">
                                            <input id="modalSearchContact" v-model="contactFilter" type="text"
                                                class="form-control form-control-sm" placeholder="Filter by Phone"
                                                @keyup="debouncedGetSearch" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="customerFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyCustomerFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Customer Modal -->
            <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewCustomer" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-6">
                                        <h5 v-if="selectedCustomerData.id" class="modal-title" style="color: #071437">
                                            Update
                                            Customer
                                        </h5>
                                        <h5 v-else class="modal-title" style="color: #071437">Add New Customer</h5>
                                    </div>
                                    <div class="mb-6 col-6 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            data-toggle="tooltip" data-placement="bottom" title="Close"
                                            @click="closeCustomerModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Customer Name</lable>
                                        <input v-model="customerData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Customer Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="customerData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="customerData.email" type="email"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                        <small v-if="validationErrors.email"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.email }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another email address"
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
                                                <input v-model="customerData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                                <small v-if="validationErrors.email2"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.email2 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer email address"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another email address"
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
                                                <input v-model="customerData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                                <small v-if="validationErrors.email3"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.email3 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer email address"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone 1</lable>
                                        <input v-model="customerData.contact" type="number"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Phone 1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another phone number"
                                                    @click="phoneIndex = 2">+
                                                    Another
                                                    Phone</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone 2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[phoneIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="customerData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone 2" />
                                                <small v-if="validationErrors.contact2"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.contact2 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer phone number"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another  phone number"
                                                    @click="phoneIndex = 3">+
                                                    Another
                                                    Phone</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone 3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="customerData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone 3" />
                                                <small v-if="validationErrors.contact3"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.contact3 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer phone number"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Website</lable>
                                        <input v-model="customerData.website" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                        <small v-if="validationErrors.website"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.website }}</small>
                                    </div>
                                    <!-- <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Open Outstanding</lable>
                                        <input v-model="customerData.customer_outstanding" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Open Outstanding" />
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Add new customer">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Customer Deletion</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeDeleteModal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this customer?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-dismiss="modal"
                                @click="closeDeleteModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete customer"
                                @click.prevent="deleteCustomer(selectedCustomerId)">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Modal -->
            <div class="modal fade" id="import_modal" tabindex="-1" data-bs-backdrop="static"
                aria-labelledby="addNewCardTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="border-0 shadow-lg modal-content rounded-3">
                        <!-- Modal Header -->
                        <div class="pb-0 border-0 modal-header">
                            <h3 class="modal-title ms-2">Import Data</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-5 py-4 modal-body">
                            <div class="row gx-5">
                                <!-- Step 1: Download Template -->
                                <div class="p-4 text-center col-md-5 d-flex flex-column align-items-center">
                                    <div class="mb-3 text-white d-flex align-items-center justify-content-center bg-primary rounded-circle"
                                        style="width: 40px; height: 40px; font-weight: bold;">
                                        1
                                    </div>
                                    <h5 class="mb-3 fw-bold">Download Template</h5>
                                    <p class="mb-4 text-muted">Download the template file and fill in your data.</p>
                                    <button class="btn btn-primary" @click="downloadSampleExcelFile"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Download the Sample Excel file">
                                        Download Template
                                    </button>
                                </div>

                                <!-- Vertical Divider for Larger Screens -->
                                <div class="col-md-1 d-none d-md-flex align-items-center">
                                    <div class="mx-auto border-end h-100"
                                        style="border-color: #dee2e6 !important; height: 80%;"></div>
                                </div>

                                <!-- Step 2: Upload File -->
                                <div class="p-4 text-center col-md-5 d-flex flex-column align-items-center">
                                    <div class="mb-3 text-white d-flex align-items-center justify-content-center bg-primary rounded-circle"
                                        style="width: 40px; height: 40px; font-weight: bold;">
                                        2
                                    </div>
                                    <h5 class="mb-3 fw-bold">Upload Your File</h5>
                                    <p class="mb-4 text-muted">Upload your completed file to import the data.</p>

                                    <!-- Upload Form -->
                                    <div v-if="uploading === 0">
                                        <form role="form" @submit.prevent="importCustomersFile"
                                            enctype="multipart/form-data" class="w-100">
                                            <input type="file" ref="fileInput" class="mb-2 form-control"
                                                @input="customer_file = $event.target.files[0]" />
                                            <div v-if="validationErrors.customer_file" class="mt-1 text-danger small">
                                                {{ validationErrors.customer_file }}
                                            </div>
                                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Import Excel file">
                                                Import Data
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Loading Indicator -->
                                    <div v-else class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-primary">Uploading...</p>
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import { Link } from "@inertiajs/vue3";

import _ from 'lodash';

const { props } = usePage();

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const customers = ref([]);
const emptyImageVal = ref(0);
const selectedCustomerData = ref({});

const emailIndex = ref(1);
const phoneIndex = ref(1);

// Filter variables
const nameFilter = ref("");
const contactFilter = ref("");

const uploading = ref(0);
const customer_file = ref(null);
const fileInput = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const customerData = ref({});
const selectedCustomerId = ref(null);

const loading_bar = ref(null);



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

// Clear filters Method
function clearFilters() {
    emptyImageVal.value = 0;
    nameFilter.value = "";
    contactFilter.value = "";
    getCustomers();
}

// Show Add/Edit Customer Modal
const showCustomerModal = async (selectedCustomerData) => {
    resetValidationErrors();
    if (selectedCustomerData) {
        customerData.value.name = selectedCustomerData.name;
        customerData.value.company = selectedCustomerData.company;
        customerData.value.address = selectedCustomerData.address;
        customerData.value.email = selectedCustomerData.email;
        customerData.value.email2 = selectedCustomerData.email2;
        customerData.value.email3 = selectedCustomerData.email3;
        customerData.value.contact = selectedCustomerData.contact;
        customerData.value.contact2 = selectedCustomerData.contact2;
        customerData.value.contact3 = selectedCustomerData.contact3;
        customerData.value.website = selectedCustomerData.website;
        customerData.value.customer_credit = selectedCustomerData.customer_credit;
        customerData.value.customer_outstanding = selectedCustomerData.customer_outstanding;
        $("#customerModal").modal("show");
    } else {
        customerData.value = {};
        $("#customerModal").modal("show");
    }
};

// Show Delete Confirmation Modal
function showDeleteModal(customerId) {
    selectedCustomerId.value = customerId;
    $("#deleteModal").modal("show");
}

// Delete Customer (Delete Confirmation Modal)
function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

// Close Add/Edit, Customer (Close Modal)
const closeCustomerModal = async () => {
    $("#customerModal").modal("hide");
    // $("#deleteModal").modal("hide");
};

const clearContact3 = async () => {
    customerData.value.contact3 = "";
};
const clearContact2 = async () => {
    customerData.value.contact2 = "";
};
const clearEmail2 = async () => {
    customerData.value.email2 = "";
};
const clearEmail3 = async () => {
    customerData.value.email3 = "";
};

const saveNewCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {
        const response = await axios.post(route("customer.all.store"), customerData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New customer registration is successful');
            closeCustomerModal();
            getCustomers();
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

const setPage = (new_page) => {
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

const openCustomerFilterModal = () => {
    loading_bar.value.start();
    $('#customerFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyCustomerFilter = () => {
    $('#customerFilterModal').modal('hide');
    reload();
};

const customerFilterModalClear = async () => {
    emptyImageVal.value = 0;
    nameFilter.value = "";
    contactFilter.value = "";
    getCustomers();
    $('#customerFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('customer.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_customer_name: nameFilter.value,
                search_customer_contact: contactFilter.value,
            },
        })
    ).data;

    customers.value = tableData.data;
    pagination.value = tableData.meta;

    if (customers.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getCustomers = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('customer.all'))).data;

        customers.value = tableData.data;
        pagination.value = tableData.meta;

        if (customers.value.length > 0) {
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
        convertValidationError(error);
    }
};

const deleteCustomer = async (customerId) => {
    try {
        await axios.delete(route("customer.delete", customerId));
        closeDeleteModal();
        successMessage('Customer deleted successfully');
        getCustomers();
    } catch (error) {
    }
}

const viewCustomer = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('customer.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const openImportModal = () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    if (fileInput.value) {
        fileInput.value.value = ''; // Reset the file input
    }
    customer_file.value = null;
    $('#import_modal').modal('show');
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const downloadSampleExcelFile = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = await axios.get(route('customer.download_sample_excel'), { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;

        const date = new Date();
        const file_name = `customers-${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}T${date.getHours().toString().padStart(2, '0')}-${date.getMinutes().toString().padStart(2, '0')}-${date.getSeconds().toString().padStart(2, '0')}.xlsx`;

        link.setAttribute('download', file_name);
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
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

const importCustomersFile = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        resetValidationErrors();
        uploading.value = 1;
        const formData = new FormData();
        if (!customer_file.value) {
            errorMessage('Please upload an Excel file (XLSX)');
            uploading.value = 0;
            customer_file.value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }

        // Check file type
        if (customer_file.value.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            errorMessage('File type is not valid. Please upload an Excel file (XLSX)');
            uploading.value = 0;
            customer_file.value = null;
            document.querySelector('input[type="file"]').value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }

        formData.append('customer_file', customer_file.value);

        const response = (await axios.post(route('customer.import'), formData)).data;
        customer_file.value = null;

        if (response.message) {
            successMessage(response.message);
            uploading.value = 0;
            $('#import_modal').modal('hide');
            reload();
        }

        if (response.errors && response.errors.length > 0) {
            let errorMessages = response.errors.toString().replace(/,/g, '<br>');
            if (response.message) {
                errorMessages = `${response.message}<br>${errorMessages}`;
            }
            uploading.value = 0;
            errorMessage(`${errorMessages}`);
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

        uploading.value = 0;
        customer_file.value = null;
        convertValidationError(error);
    }
};

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
        html: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 8000,
        timerProgressBar: true,
    });
};

onMounted(() => {
    getCustomers();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

@media (max-width: 767.98px) {
    .download-btn {
        margin-top: 10px !important;
    }
}

@media (min-width: 768px) {
    .download-btn {
        margin-top: 44px !important;
    }
}

.modal-dialog-width {
    max-width: 600px;
}

.modal-body-padding {
    padding: 14px;
}

.download-section {
    background-color: #F3F2F7;
    position: relative;
    border-radius: 10px;
}

.download-caption {
    font-size: 12px;
    font-weight: 600;
    color: #a4a4a7;
}

.main-modal-section {
    column-gap: 14px;
}

.download-btn-header {
    position: absolute;
    bottom: 16px;
    left: 0;
    right: 0;
    margin: auto;
}

.add-btn-section {
    background-color: #F3F2F7;
    border-radius: 10px;
}
</style>
