<template>
    <AppLayout title="Deleted Products">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2 justify-content-center">
                    <div class="mt-5 mb-0 col-lg-12 mt-lg-0 custom-component">
                        <div
                            class="pb-0 mt-0 mb-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    Deleted Products
                                </h1>

                            </div>

                            <div class="col-6 d-flex justify-content-end">
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="codeFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="nameFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="minPriceFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Min. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="maxPriceFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Max. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="stockFilter" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Select Stock</option>
                                            <option value="1">No Stock</option>
                                            <option value="2">Re-Order Level Reached</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openProductsFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="products.length > 0">
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
                                <!-- Product Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && codeFilter == '' && nameFilter == '' && minPriceFilter == '' && maxPriceFilter == '' && stockFilter == 0">

                                    <!-- Icon for No Deleted Products State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-trash fs-1"></i>
                                    </div>

                                    <!-- Heading for No Deleted Products State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Deleted Products</h2>
                                    </div>

                                    <!-- Subtext for No Deleted Products State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your deleted products will appear here once
                                            available.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (codeFilter != '' || nameFilter != '' || minPriceFilter != '' || maxPriceFilter != '' || stockFilter != 0)">

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
                                        <p class="text-gray-600 fs-5">Try adjusting your filters to find the products
                                            you're looking for.</p>
                                    </div>
                                </div>

                                <div v-if="products.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="text-center">Image</th>
                                                    <th class="ps-3">Code</th>
                                                    <th>Name</th>
                                                    <th>Unit</th>
                                                    <th class="text-end">Priority</th>
                                                    <th class="text-end">Stock</th>
                                                    <th class="text-end">ROL</th>
                                                    <th class="text-end">Product Cost</th>
                                                    <th class="text-end">Selling Price</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="product in products">
                                                    <td class="py-2 text-center">
                                                        <img v-if="product.image_url" :src="product.image_url"
                                                            class="img-fluid"
                                                            style="width: 50px; height: 50px; object-fit: cover; object-position: left; border-radius: 10px;" />

                                                        <img v-else
                                                            src="@/../src/assets/media/stock/food/product_image.webp"
                                                            class="img-fluid"
                                                            style="width: 50px; height: 50px; border-radius: 10px;" />

                                                    </td>
                                                    <td class="py-2 ps-3">{{ product.code }}</td>
                                                    <td class="py-2">{{ truncateText(product.name) }}</td>
                                                    <td v-if="product.unit != null" class="py-2">{{ product.unit_name }}
                                                    </td>
                                                    <td v-else class="py-2"></td>
                                                    <td class="py-2 text-end">{{ product.order_no }}</td>
                                                    <td v-if="product.product_type == 1" class="py-2 text-end">{{
                                                        product.formatted_stock_qty }}</td>
                                                    <td v-else class="py-2 text-end"></td>
                                                    <td class="py-2 text-end">{{ product.formatted_rol }}</td>
                                                    <!-- <td class="py-2 text-end" :class="{
                                                        'text-danger': (product.formatted_rol > 0 && (product.formatted_rol == product.formatted_stock_qty || product.formatted_rol > product.formatted_stock_qty))
                                                    }">{{ product.formatted_rol }}</td> -->
                                                    <td class="py-2 text-end">{{ product.formatted_product_price }}</td>
                                                    <td class="py-2 text-end">{{ product.formatted_selling_price }}</td>
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Restore product"
                                                                @click="openRestoreModal(product.id)">
                                                                <i class="p-2 bi bi-arrow-repeat fs-3 text-dark"></i>
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
                                        <div v-for="product in products" :key="product.id"
                                            class="p-3 mb-4 border rounded">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Product Image -->
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="product-admin-table-image-outline"
                                                                    v-if="product.image_url">
                                                                    <img :src="product.image_url" class="img-fluid"
                                                                        style="width: 50px; height: 50px; object-fit: cover; object-position: left; border-radius: 10px;" />
                                                                </div>
                                                                <div class="product-admin-table-image-outline" v-else>
                                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                                        class="img-fluid"
                                                                        style="width: 50px; height: 50px; border-radius: 10px;" />
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Product Code -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Code</td>
                                                        <td class="text-end">{{ product.code }}</td>
                                                    </tr>

                                                    <!-- Product Name -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Name</td>
                                                        <td class="text-end">{{ truncateText(product.name) }}</td>
                                                    </tr>

                                                    <!-- Product Unit -->
                                                    <tr v-if="product.unit != null">
                                                        <td class="text-gray-400 text-uppercase">Unit</td>
                                                        <td class="text-end">{{ product.unit_name }}</td>
                                                    </tr>

                                                    <!-- Product Priority -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Priority</td>
                                                        <td class="text-end">{{ product.order_no }}</td>
                                                    </tr>

                                                    <!-- Product Stock -->
                                                    <tr v-if="product.product_type == 1">
                                                        <td class="text-gray-400 text-uppercase">Stock</td>
                                                        <td class="text-end">{{ product.formatted_stock_qty }}</td>
                                                    </tr>

                                                    <!-- Product ROL -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">ROL</td>
                                                        <td class="text-end">{{ product.formatted_rol }}</td>
                                                    </tr>

                                                    <!-- Product Cost -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Product Cost</td>
                                                        <td class="text-end">{{ product.formatted_product_price }}</td>
                                                    </tr>

                                                    <!-- Selling Price -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Selling Price</td>
                                                        <td class="text-end">{{ product.formatted_selling_price }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    data-placement="bottom" title="Restore product"
                                                                    @click="openRestoreModal(product.id)">
                                                                    <i
                                                                        class="p-2 bi bi-arrow-repeat fs-5 text-dark"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="products.length > 0" class="my-3 row align-items-center">
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
                                            id="kt_ecommerce_products_table_paginate">
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
            <!-- Product Filter Modal -->
            <div class="modal fade" id="productsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="productsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Deleted Product Filters</h5>
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
                                        <input id="modalSearchCode" v-model="codeFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search By Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchName" v-model="nameFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search By Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchMinSell" v-model="minPriceFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Min. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchMaxSell" v-model="maxPriceFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Max. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <select id="modalSelectStock"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="stockFilter" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Select Stock</option>
                                            <option value="1">No Stock</option>
                                            <option value="2">Re-Order Level Reached</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="productsFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyProductsFilter" class="btn btn-info ms-4 fw-bold">
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
            <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Restore</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to restore this product?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal"
                                @click="closeRestoreModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                                data-placement="bottom" title="Restore product"
                                @click.prevent="confirmRestore(selectedProductId)">
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

import _ from 'lodash';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const products = ref([]);
const emptyImageVal = ref(0);
const categories = ref([]);
const units = ref([]);
const select_category = ref([]);
const select_edit_category = ref(null);
const select_unit = ref([]);
const select_edit_unit = ref({});

const product = ref({});
const edit_product = ref({});
const selectedProductId = ref(null);

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const nameFilter = ref("");
const minCostFilter = ref("");
const maxCostFilter = ref("");
const minPriceFilter = ref("");
const maxPriceFilter = ref("");
const stockFilter = ref(0);

const validationErrors = ref({});
const validationMessage = ref(null);

const loading_bar = ref(null);

const product_image = ref(null);
const edit_product_image = ref(null);



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

const onFileChange = (e) => {
    product.value.image = e.target.files[0];
    product_image.value = e.target.files[0];
};

const onFileChangeEdit = (e) => {
    edit_product.value.image = e.target.files[0];
    edit_product_image.value = e.target.files[0];

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

const openProductsFilterModal = () => {
    loading_bar.value.start();
    $('#productsFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyProductsFilter = () => {
    $('#productsFilterModal').modal('hide');
    reload();
};

const productsFilterModalClear = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    nameFilter.value = "";
    minCostFilter.value = "";
    maxCostFilter.value = "";
    minPriceFilter.value = "";
    maxPriceFilter.value = "";
    stockFilter.value = 0;
    reload();
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#productsFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('product.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_product_code: codeFilter.value,
                search_product_name: nameFilter.value,
                search_product_cost_min: minCostFilter.value,
                search_product_cost_max: maxCostFilter.value,
                search_product_selling_min: minPriceFilter.value,
                search_product_selling_max: maxPriceFilter.value,
                search_order_stock: stockFilter.value,
            },
        })
    ).data;

    products.value = tableData.data;
    pagination.value = tableData.meta;

    if (products.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getCategories = async () => {
    try {
        const response = (await axios.get(route('categories.all'))).data;
        categories.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

const getUnits = async () => {
    try {
        const response = (await axios.get(route('units.all'))).data;
        units.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

const saveProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {

        if (select_category.value != null) {
            product.value.product_category_id = select_category.value.id;
        }

        if (select_unit.value != null) {
            product.value.unit = select_unit.value.id;
        }

        const response = await axios.post(route('product.store'), product.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data == "this priority number already exists") {
            errorMessage('this priority number already exists');
            nextTick(() => {
                loading_bar.value.finish();
            });
        } else if (response.data == "This product already exists") {
            errorMessage('This product already exists');
            nextTick(() => {
                loading_bar.value.finish();
            });
        } else {
            successMessage('Product added successfully!');

            product.value = {};
            product_image.value = null;
            select_category.value = [];
            select_unit.value = [];

            const fileInput = document.getElementById('product_image');
            fileInput.value = null;

            $('#productModal').modal('hide');
            reload();

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

const editProduct = async (id) => {
    resetValidationErrors();
    try {
        const response = (await axios.get(route("product.get", id))).data;
        edit_product.value = response;

        if (edit_product.value.product_category_id) {
            select_edit_category.value = edit_product.value.product_category;
        } else {
            select_edit_category.value = null;
        }
        select_edit_unit.value = {};
        if (edit_product.value.unit) {
            select_edit_unit.value.id = edit_product.value.unit;
            select_edit_unit.value.title = edit_product.value.unit_name;
        } else {
            select_edit_unit.value = null;
        }

        $('#productUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }
};

const updateProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (select_edit_category.value != null) {
            edit_product.value.product_category_id = select_edit_category.value.id;
        } else {
            edit_product.value.product_category_id = null;
        }

        if (select_edit_unit.value != null) {
            edit_product.value.unit = select_edit_unit.value.id;
        } else {
            edit_product.value.unit = null;
        }

        if (edit_product_image.value != null) {
            edit_product.value.image = edit_product_image.value;
        } else {
            edit_product.value.image = null;
        }

        const response = await axios.post(route("product.update", edit_product.value.id), edit_product.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data == "this priority number already exists") {
            errorMessage('this priority number already exists');
            nextTick(() => {
                loading_bar.value.finish();
            });
        } else {

            successMessage('Product updated successfully!');

            edit_product.value = {};
            edit_product_image.value = null;

            const fileInput = document.getElementById('product_image');
            fileInput.value = null;

            $('#productUpdateModal').modal('hide');
            reload();

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

function openRestoreModal(prodyctId) {

    selectedProductId.value = prodyctId;
    $("#restoreModal").modal("show");

}

function closeRestoreModal() {
    $("#restoreModal").modal("hide");
}

const confirmRestore = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const response = (await axios.get(route("product.restore", id))).data;
        closeRestoreModal();
        successMessage('Product restored successfully!');
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

watch(stockFilter, (newValue) => {
    stockFilter.value = newValue;
    getSearch();
});

function clearFilters() {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    nameFilter.value = "";
    minCostFilter.value = "";
    maxCostFilter.value = "";
    minPriceFilter.value = "";
    maxPriceFilter.value = "";
    stockFilter.value = 0;
    reload();
    nextTick(() => {
        loading_bar.value.finish();
    });
}

function openNewModal() {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    product.value = {};
    select_category.value = null;
    select_unit.value = null;
    const fileInput = document.getElementById('product_image');
    fileInput.value = null;
    $('#productModal').modal('show');
    nextTick(() => {
        loading_bar.value.finish();
    });
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
    reload();
    getCategories();
    getUnits();

    if (page_props.check_rol == 2) {
        stockFilter.value = 2;
        reload();
    }

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

.restore-btn {
    color: green;
}

.restore {
    width: 100px;
    height: 100px;
}
</style>
