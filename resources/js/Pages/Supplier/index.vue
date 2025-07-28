<template>
    <AppLayout title="Suppliers">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Manage Your Suppliers
                                </h1>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                                <Link :href="route('supplier.deleted.list')" class="btn trash-btn me-2 ps-3 pe-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list">
                                <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </Link>
                                <button data-toggle="tooltip" data-placement="bottom" title="Import supplier"
                                    @mouseover="hovered = true" @mouseleave="hovered = false" @click="openImportModal"
                                    class="btn export-btn me-2 ps-4 pe-1"
                                    style="font-weight: bold;  border: 1px solid transparent;">
                                    <i class="bi bi-box-arrow-in-left fs-2 export-icon"></i>
                                </button>
                                <button class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new supplier"
                                    @click="showSupplierModal(selectedSupplierData = {}, emailIndex = 1, phoneIndex = 1)">
                                    <i class="bi bi-plus-lg"></i> Add Supplier
                                </button>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">
                                        </div>
                                        <input v-model="nameFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Name" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">
                                        </div>
                                        <input v-model="companyFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Company" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">
                                        </div>
                                        <input v-model="contactFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Phone" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="col-md-2 align-self-end" data-toggle="tooltip" data-placement="bottom"
                                        title="Clear filters">
                                        <button @click="clearFilters" class="p-5 square-clear-button">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                    </div>

                                </div>

                                <!-- Filters Mobile -->
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openSupplierFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="suppliers.length > 0">
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
                                <!-- Supplier Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && nameFilter == '' && companyFilter == '' && contactFilter == ''">
                                    <!-- Icon for Empty State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-truck fs-1"></i>
                                        <!-- You can replace the icon with any appropriate one -->
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No Suppliers Found</h1>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">Your suppliers will appear here once added.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (nameFilter != '' || companyFilter != '' || contactFilter != '')">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                        <!-- Search icon for no results -->
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No Results Found</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12">
                                        <p class="text-gray-700 fs-3">Try adjusting your search filters.</p>
                                    </div>
                                </div>

                                <div v-if="suppliers.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Supplier</th>
                                                    <th>Company</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="supplier in suppliers" class="cursor-pointer">
                                                    <td class="py-2 ps-3" @click.prevent="viewSupplier(supplier.id)">
                                                        {{ truncateText(supplier.name) }}

                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="viewSupplier(supplier.id)">
                                                        {{ truncateText(supplier.company) }}
                                                    </td>
                                                    <td class="py-2" @click.prevent="viewSupplier(supplier.id)">
                                                        {{ truncateText(supplier.contact) }}
                                                    </td>
                                                    <td class="py-2" @click.prevent="viewSupplier(supplier.id)">
                                                        {{ truncateText(supplier.email) }}
                                                    </td>

                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <a href="javascript:void(0)"
                                                                class="border btn btn-sm border-danger text-dark d-flex align-items-center justify-content-center"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Delete supplier"
                                                                style="width: 36px; height: 36px;"
                                                                @click="showDeleteModal(supplier.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="table table-bordered table-striped fs-6">
                                            <tbody>
                                                <tr v-for="supplier in suppliers" :key="supplier.id"
                                                    class="border-bottom">
                                                    <!-- Supplier Name -->
                                                    <td class="text-gray-400 text-uppercase">Supplier</td>
                                                    <td class="text-end">{{ truncateText(supplier.name) }}</td>
                                                </tr>
                                                <tr v-for="supplier in suppliers" :key="'company-' + supplier.id"
                                                    class="border-bottom">
                                                    <!-- Supplier Company -->
                                                    <td class="text-gray-400 text-uppercase">Company</td>
                                                    <td class="text-end">{{ truncateText(supplier.company) }}</td>
                                                </tr>
                                                <tr v-for="supplier in suppliers" :key="'contact-' + supplier.id"
                                                    class="border-bottom">
                                                    <!-- Supplier Contact -->
                                                    <td class="text-gray-400 text-uppercase">Phone</td>
                                                    <td class="text-end">{{ truncateText(supplier.contact) }}</td>
                                                </tr>
                                                <tr v-for="supplier in suppliers" :key="'email-' + supplier.id"
                                                    class="border-bottom">
                                                    <!-- Supplier Email -->
                                                    <td class="text-gray-400 text-uppercase">Email</td>
                                                    <td class="text-end">{{ truncateText(supplier.email) }}</td>
                                                </tr>
                                                <tr v-for="supplier in suppliers" :key="'actions-' + supplier.id"
                                                    class="border-bottom">
                                                    <!-- Actions -->
                                                    <td class="text-gray-400 text-uppercase">Actions</td>
                                                    <td class="text-end">
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="bottom" title="Delete supplier"
                                                            @click="showDeleteModal(supplier.id)">
                                                            <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="suppliers.length > 0" class="my-3 row align-items-center">
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
            <!-- Supplier Filter Modal -->
            <div class="modal fade" id="supplierFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="supplierFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Supplier Filters</h5>
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
                                        <input id="modalSearchName" v-model="nameFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchCompany" v-model="companyFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Company"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchContact" v-model="contactFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Phone"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="supplierFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applySupplierFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add/Edit Supplier Modal -->
            <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewSupplier" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 v-if="selectedSupplierData.id" class="modal-title" style="color: #071437">
                                            Update
                                            Supplier
                                        </h5>
                                        <h5 v-else class="modal-title" style="color: #071437">Add New Supplier</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            data-toggle="tooltip" data-placement="bottom" title="Close"
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
                                                <input v-model="supplierData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier email address"
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
                                                <input v-model="supplierData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier email address"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone 1</lable>
                                        <input v-model="supplierData.contact" type="number"
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
                                                <input v-model="supplierData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier phone number"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another phone number"
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
                                                <input v-model="supplierData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier phone number"
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
                                        <button v-if="selectedSupplierData.id" type="button" class="btn btn-light-info"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                            style="font-weight: bold"
                                            @click.prevent="updateSupplier(selectedSupplierData.id)">
                                            UPDATE
                                        </button>
                                        <button v-else type="submit" class="btn btn-info" data-toggle="tooltip"
                                            data-placement="bottom" title="Add new supplier" style="font-weight: bold">
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
                            <h5 class="modal-title">Confirm Supplier Deletion</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeDeleteModal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this supplier?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-dismiss="modal"
                                @click="closeDeleteModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete supplier"
                                @click.prevent="deleteSupplier(selectedSupplierId)">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import modal -->
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
                                        title="Download sample Excel file">
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
                                    <div v-if="uploading == 0">
                                        <form role="form" @submit.prevent="importSuppliersFile"
                                            enctype="multipart/form-data" class="w-100">
                                            <input type="file" ref="fileInput" class="mb-2 form-control"
                                                @input="supplier_file = $event.target.files[0]" />
                                            <div v-if="validationErrors.supplier_file" class="mt-1 text-danger small">
                                                {{ validationErrors.supplier_file }}
                                            </div>
                                            <button type="submit" class="mt-3 btn btn-primary w-100"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Import Excel file">
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

            <!-- End Import Modal -->


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

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const suppliers = ref([]);
const emptyImageVal = ref(0);
const selectedSupplierData = ref({});

const emailIndex = ref(1);
const phoneIndex = ref(1);

// Filter variables
const nameFilter = ref("");
const companyFilter = ref("");
const contactFilter = ref("");

const validationErrors = ref({});
const validationMessage = ref(null);

const supplierData = ref({});
const selectedSupplierId = ref(null);

const uploading = ref(0);
const supplier_file = ref(null);
const fileInput = ref(null);

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
    companyFilter.value = "";
    contactFilter.value = "";
    getSuppliers();
}

const openSupplierFilterModal = () => {
    loading_bar.value.start();
    $('#supplierFilterModal').modal('show');
    loading_bar.value.finish();
};

const applySupplierFilter = () => {
    $('#supplierFilterModal').modal('hide');
    reload();
};

const supplierFilterModalClear = async () => {
    emptyImageVal.value = 0;
    nameFilter.value = "";
    companyFilter.value = "";
    contactFilter.value = "";
    getSuppliers();
    $('#supplierFilterModal').modal('hide');
};

// Show Add/Edit Customer Modal
const showSupplierModal = async (selectedSupplierData) => {

    resetValidationErrors();
    if (selectedSupplierData) {
        supplierData.value.name = selectedSupplierData.name;
        supplierData.value.company = selectedSupplierData.company;
        supplierData.value.address = selectedSupplierData.address;
        supplierData.value.email = selectedSupplierData.email;
        supplierData.value.email2 = selectedSupplierData.email2;
        supplierData.value.email3 = selectedSupplierData.email3;
        supplierData.value.contact = selectedSupplierData.contact;
        supplierData.value.contact2 = selectedSupplierData.contact2;
        supplierData.value.contact3 = selectedSupplierData.contact3;
        supplierData.value.website = selectedSupplierData.website;
        supplierData.value.customer_credit = selectedSupplierData.customer_credit;
        supplierData.value.customer_outstanding = selectedSupplierData.customer_outstanding;
        $("#supplierModal").modal("show");
    } else {
        supplierData.value = {};
        $("#supplierModal").modal("show");
    }

};

// Show Delete Confirmation Modal
function showDeleteModal(supplierId) {

    selectedSupplierId.value = supplierId;
    $("#deleteModal").modal("show");

}

// Delete Customer (Delete Confirmation Modal)
function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

// Close Add/Edit, Customer (Close Modal)
const closeSupplierModal = async () => {
    $("#supplierModal").modal("hide");
};

const clearContact3 = async () => {
    supplierData.value.contact3 = "";
};
const clearContact2 = async () => {
    supplierData.value.contact2 = "";
};
const clearEmail2 = async () => {
    supplierData.value.email2 = "";
};
const clearEmail3 = async () => {
    supplierData.value.email3 = "";
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
        // convertValidationNotification(error);
        convertValidationError(error);
    }
};

const updateSupplier = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        await axios.post(route('supplier.update', id), supplierData.value);
        successMessage('Supplier successfully updated');
        closeSupplierModal();
        getSuppliers();

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

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('supplier.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_supplier_name: nameFilter.value,
                search_supplier_company: companyFilter.value,
                search_supplier_contact: contactFilter.value,
            },
        })
    ).data;

    suppliers.value = tableData.data;
    pagination.value = tableData.meta;

    if (suppliers.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getSuppliers = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('supplier.all'))).data;

        suppliers.value = tableData.data;
        pagination.value = tableData.meta;

        if (suppliers.value.length > 0) {
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

const deleteSupplier = async (supplierId) => {
    try {


        await axios.delete(route("supplier.delete", supplierId));
        closeDeleteModal();
        successMessage('Supplier deleted successfully');
        getSuppliers();


    } catch (error) {
    }
}

const viewSupplier = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('supplier.load', id);
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
    supplier_file.value = null;
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
        const response = await axios.get(route('supplier.download_sample_excel'), { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;

        const date = new Date();
        const file_name = `suppliers-${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}T${date.getHours().toString().padStart(2, '0')}-${date.getMinutes().toString().padStart(2, '0')}-${date.getSeconds().toString().padStart(2, '0')}.xlsx`;

        link.setAttribute('download', file_name); // Set the download attribute to the generated file name
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

const importSuppliersFile = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        uploading.value = 1;
        const formData = new FormData();

        if (!supplier_file.value) {
            errorMessage('Please select an excel file');
            uploading.value = 0;
            supplier_file.value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }

        // Check file type
        if (supplier_file.value.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            errorMessage('File type is not valid. Please upload an Excel file (XLSX)');
            uploading.value = 0;
            supplier_file.value = null;
            document.querySelector('input[type="file"]').value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }


        formData.append('supplier_file', supplier_file.value);

        const response = (await axios.post(route('supplier.import'), formData)).data;
        supplier_file.value = null;

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

            errorMessage(`${errorMessages}`);
            uploading.value = 0;
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

        uploading.value = 0;
        supplier_file.value = null;
        convertValidationError(error);
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
        html: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 8000,
        timerProgressBar: true,
    });
};

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

onMounted(() => {
    getSuppliers();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
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
