<template>
    <AppLayout title="Product Sales">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">

                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 col-sm-6 d-flex justify-content-start align-items-center">

                                <h1 class="page-title">
                                    Product Sales&nbsp;
                                </h1>
                            </div>

                            <!-- Desktop view -->
                            <div class="col-12 col-sm-6 d-none d-md-flex justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Print product sales report"
                                    class="btn btn-info fw-bold">
                                    <i class="bi bi-printer"></i>
                                    PRINT
                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Export product sales report"
                                    class="btn btn-info ms-4 fw-bold">
                                    <i class="bi bi-box-arrow-up"></i> EXPORT
                                </a>
                            </div>

                            <!-- Mobile view -->
                            <div class="col-12 col-sm-6 d-flex d-md-none justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Print product sales report"
                                    class="btn btn-info export-btn ps-3 pe-2 ms-2">
                                    <i class="bi bi-printer fs-2 export-icon ms-1"></i>

                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Export product sales report"
                                    class="btn btn-info export-btn ps-3 pe-2 ms-2">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <label for="status"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">STATUS</label>
                                        <Multiselect v-model="select_status" :options="status_items"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="name" track-by="id" />
                                    </div>

                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">NAME
                                        </div>

                                        <Multiselect v-model="select_product" :options="products_items"
                                            data-toggle="tooltip" data-placement="bottom" class="z__index"
                                            :showLabels="false" :close-on-select="true" :searchable="true"
                                            placeholder="Select" label="name" track-by="id" max-height="200"
                                            @search-change="getProductSearch" :internal-search="false">
                                            <template #noOptions>
                                                <div>Press a key to select Product</div>
                                            </template>
                                            <template #noResult>
                                                <div>No matching products found</div>
                                            </template>
                                        </Multiselect>
                                    </div>

                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <label for="from_date" class="pt-0 pb-1 text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker v-model="from_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <label for="to_date" class="pt-0 pb-1 text-gray-600 col-form-label">TO
                                            DATE</label>
                                        <Datepicker v-model="to_date" class="mb-2 form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <label for="select_search_customer"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect v-model="select_currency" :options="currencies"
                                            class="mb-2 z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="code" track-by="id" max-height="200" />
                                    </div>
                                    <div class="col-md-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <!-- <div class="mb-2 col-xl-1 col-xxl-1 mb-xl-0"></div> -->

                                    <div
                                        class="mb-2 col-xl-2 col-xxl-1 ms-auto d-flex justify-content-end align-self-end">
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
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openProductSalesFilterModal">
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
                                <!-- Sales Table -->
                                <div class="row">
                                    <!-- Desktop Table -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-6">STATUS</th>
                                                    <th>CODE</th>
                                                    <th>NAME</th>
                                                    <th class="text-end pe-3">QTY</th>
                                                    <th class="text-end pe-3">TOTAL VALUE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="product in products" :key="product.id"
                                                    class="text-gray-600 fs-6">
                                                    <td v-if="!product.product.deleted_at" class="py-2 ps-3">
                                                        <div class="badge badge-light-success done-status">
                                                            AVAILABLE
                                                        </div>
                                                    </td>
                                                    <td v-else class="py-2 ps-4">
                                                        <div class="badge badge-light-danger">
                                                            DELETED
                                                        </div>
                                                    </td>
                                                    <td class="py-2">{{ product.product.code }}</td>
                                                    <td class="py-2">{{ truncateText(product.product.name) }}</td>
                                                    <td class="py-2 text-end pe-3">{{
                                                        numberFormatter(product.total_quantity, 2) }}</td>
                                                    <td class="py-2 text-end pe-3">{{
                                                        numberFormatter(product.total_amount, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="product in products" :key="product.id">
                                                    <td class="table-responsive-width">
                                                        <div class="row " @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase ">
                                                                STATUS</div>
                                                            <div class="col-8 right-side text-end">
                                                                <div v-if="!product.product.deleted_at">
                                                                    <div class="badge badge-light-success done-status">
                                                                        AVAILABLE
                                                                    </div>
                                                                </div>
                                                                <div v-else>
                                                                    <div class="badge badge-light-danger">
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
                                                                <span>{{ product.product.code }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                NAME</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ truncateText(product.product.name) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                QTY</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>
                                                                    {{ numberFormatter(product.total_quantity, 2) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider "
                                                            @click.prevent="editInvoice(bill.id)">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                TOTAL</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ numberFormatter(product.total_amount, 2)
                                                                    }}</span>
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
                                <div v-if="pagination" class="my-3 row ps-1 ps-md-0">

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
            <!-- Product Sales Filter Modal -->
            <div class="modal fade" id="productSalesFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="productSalesFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Product Sales Filters</h5>
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
                                        <label for="modalStatus" class="text-gray-600 col-form-label">STATUS</label>
                                        <Multiselect id="modalStatus" v-model="select_status" :options="status_items"
                                            class="z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" :searchable="true" placeholder="Select"
                                            label="name" track-by="id" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalName" class="text-gray-600 col-form-label">NAME</label>
                                        <Multiselect v-model="select_product" :options="products_items"
                                            data-toggle="tooltip" data-placement="bottom" class="z__index"
                                            :showLabels="false" :close-on-select="true" :searchable="true"
                                            placeholder="Select" label="name" track-by="id" max-height="200"
                                            @search-change="getProductSearch" :internal-search="false">
                                            <template #noOptions>
                                                <div>Press a key to select Product</div>
                                            </template>
                                            <template #noResult>
                                                <div>No matching products found</div>
                                            </template>
                                        </Multiselect>
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalFromDate" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker id="modalFromDate" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :upperLimit="to_date" />
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
                                        <Multiselect id="modalSelectCurrency" v-model="select_currency"
                                            :options="currencies" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="code" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="productSalesFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyProductSalesFilter" class="btn btn-info ms-4 fw-bold">
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
import { usePage } from '@inertiajs/vue3'
import Datepicker from 'vue3-datepicker'
import { Link } from '@inertiajs/vue3';
import { debounce } from "lodash";

const datePickerFormat = 'dd-MM-yyyy';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const from_date = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
const to_date = ref(new Date());
const select_currency = ref({});

const loading_bar = ref(null);

const search_product = ref({
    product_id: null,
    from_date: from_date.value,
    to_date: to_date.value,
});
const products = ref([]);
const currencies = ref([]);
const products_items = ref([]);
const select_product = ref(null);
const select_status = ref(null);
const status_items = ref([
    { name: 'AVAILABLE', id: 1 },
    { name: 'DELETED', id: 2 }
]);

const placeholderText = 'DD/MM/YYYY';

const validationErrors = ref({});
const validationMessage = ref(null);
const previousCurrency = ref({});

onMounted(() => {
    setToDate();
    getCurrencies();
    getBusinessDetails();
    getProducts();
});

const setToDate = () => {
    to_date.value.setHours(23, 59, 59, 999);
}

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

const setPage = async (new_page) => {
    page.value = new_page;
    reload();
};
const getSearch = async () => {
    page.value = 1;
    reload();
};

const perPageChange = async () => {
    page.value = 1;
    reload();
};

const openProductSalesFilterModal = () => {
    loading_bar.value.start();
    $('#productSalesFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyProductSalesFilter = () => {
    $('#productSalesFilterModal').modal('hide');
    reload();
};

const productSalesFilterModalClear = async () => {
    select_product.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    select_currency.value = null;
    search_product.value = {
        product_id: null,
        from_date: from_date.value,
        to_date: to_date.value,
    };
    select_status.value = null;
    page.value = 1;
    getCurrencies();
    getBusinessDetails();
    $('#productSalesFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('report.productSales.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                // "filter[search]": search.value,
                search_product_id: search_product.value.product_id,
                search_product_from_date: search_product.value.from_date,
                search_product_to_date: search_product.value.to_date,
                status: select_status.value ? select_status.value.name : null,
                search_order_currency: select_currency.value.id,
            },
        })
    ).data;

    products.value = tableData.data;
    pagination.value = tableData.meta;

    // if (select_currency.value) {
    //     getTotalValues();
    // }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getProductSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('product.multiselect.all.search', { query }));
            products_items.value = response.data;

        } catch (error) {
            products_items.value = [];
        }
    } else {
        getLimitedProducts();
    }
};

const getProducts = async () => {
    try {
        const productData = (await axios.get(route("limited.products"))).data;
        products_items.value = productData;
    } catch (error) {
        convertValidationNotification(error);
    }
}

const getCurrencies = async () => {
    try {
        const response = (await axios.get(route("currency.list"))).data.data;
        const allCurrenciesOption = { id: 0, code: 'All Currencies' };
        const updatedResponse = [allCurrenciesOption, ...response];

        currencies.value = updatedResponse;

    } catch (error) {
    }
};


const printReport = async () => {
    try {
        await nextTick(() => {
            loading_bar.value.start();
        });
        const response = await axios.post(
            route("report.productSales.print"),
            {
                search_product_from_date: search_product.value.from_date,
                search_product_to_date: search_product.value.to_date,
                search_product_currency: select_currency.value,
                search_product_id: search_product.value.product_id,
                status: select_status.value,
            },
            { responseType: "blob" }
        );
        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
    } catch (error) {
        console.error('Error printing report:', error);
    } finally {
        await nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const exportReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.productSales.export"),
            {
                status: select_status.value,
                search_product_from_date: search_product.value.from_date,
                search_product_to_date: search_product.value.to_date,
                product: select_product.value,
                // product_items: products.value,
                search_product_currency: select_currency.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;


        // file name
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `ProductSalesReport-${formattedDate}.xlsx`;

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();

    } catch (error) {
        console.error(error);
        convertValidationNotification(error);
    } finally {
        loading_bar.value.finish();
    }
};

const clearFilters = async () => {
    select_product.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    select_currency.value = null;
    search_product.value = {
        product_id: null,
        from_date: from_date.value,
        to_date: to_date.value,
    };
    select_status.value = null;
    page.value = 1;
    getCurrencies();
    getBusinessDetails();
};

const numberFormatter = (number) => {
    if (number == undefined || number == null || number == Infinity) {
        return "0.00";
    }
    const parsedNumber = Number(number);
    if (isNaN(parsedNumber)) {
        return "0.00";
    }
    if (typeof parsedNumber !== "number") {
        return "0.00";
    }
    return parsedNumber.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
    });
};

watch(select_product, (newValue) => {
    search_product.value.product_id = newValue ? newValue.id : null;
    getSearch();
});

watch(from_date, (newValue) => {
    search_product.value.from_date = newValue;
    getSearch();
});

watch(to_date, (newValue) => {
    if (to_date.value) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_product.value.to_date = endDate;
        getSearch();
    } else {
        search_product.value.to_date = null;
    }
});

watch(select_status, (newValue) => {
    getSearch();
});

watch(select_currency, (newValue) => {
    if (select_currency.value) {
        previousCurrency.value = select_currency.value;
        page.value = 1;
        reload();
    } else {
        page.value = 1;
        select_currency.value = { id: previousCurrency.value.id, code: previousCurrency.value.code };
    }
});

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data.data;
        if (response.currency_id) {
            select_currency.value.id = response.currency_id;
            select_currency.value.code = response.currency_name;
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

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

</script>

<style lang="css" scoped>
.total-row {
    border-bottom: 3px double #000;
    border-top: 3px #000;
}

.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.page-title {
    margin-right: auto;
    font-size: 28px;
    color: #071437
}

.done-status {
    background-color: #DBFFE4;
    color: green;
}
</style>
