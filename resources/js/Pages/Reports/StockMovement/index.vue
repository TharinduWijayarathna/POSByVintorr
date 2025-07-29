<template>
    <AppLayout title="Stock Movement">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 d-flex justify-content-start align-items-center">
                                <h1 class="page-title">
                                    Stock Movement&nbsp;
                                </h1>
                            </div>
                            <!-- <div class="col-6 d-flex justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)"
                                    class="btn btn-info fw-bold">
                                    <i class="bi bi-printer"></i>
                                    PRINT
                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)"
                                    class="btn btn-info ms-4 fw-bold">
                                    <i class="bi bi-box-arrow-in-right"></i> EXPORT
                                </a>
                            </div> -->
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">SELECT
                                            PRODUCT
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

                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-0" v-if="select_product">
                                        <label for="from_date" class="pt-0 pb-1 text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker v-model="from_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />
                                    </div>
                                    <div class="mb-2 col-xl-3 col-xxl-2 mb-xl-0 pe-0" v-if="select_product">
                                        <label for="to_date" class="pt-0 pb-1 text-gray-600 col-form-label">TO
                                            DATE</label>
                                        <Datepicker v-model="to_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>

                                    <div class="col-md-1 align-self-end" v-if="select_product">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-xl-1 col-xxl-3 mb-xl-0"></div>

                                    <div class="col-xl-1 col-xxl-2 ps-0 d-flex justify-content-end align-self-end"
                                        v-if="select_product">
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
                                                @click.prevent="openProductsFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button v-if="select_product" class="p-5 square-clear-button"
                                                @click="clearFilters" data-toggle="tooltip" data-placement="bottom"
                                                title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="select_product">
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

                                <div class="row" v-if="!select_product">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-arrow-left-right fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">Select a Product</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">To View the Stock Movement</p>
                                    </div>
                                </div>

                                <div class="row" v-if="select_product && StockLogs?.length == 0">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12 mb-15">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No result found</h1>
                                    </div>
                                </div>

                                <!-- Sales Table -->
                                <div class="row" v-if="select_product && StockLogs?.length > 0">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="text-center pe-1 i-icon-col"></th>
                                                    <th class="ps-3 col-2">DATE</th>
                                                    <!-- <th>PRODUCT</th> -->
                                                    <th class="col-4">REASON</th>
                                                    <th class="col-3">REFERENCE</th>
                                                    <th class="text-end pe-3 col-2">AFFECTED QTY</th>
                                                    <th class="text-end pe-3 col-1">BALANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="stock in StockLogs"
                                                    class="text-gray-600 cursor-pointer fs-6">
                                                    <td class="py-2 text-center pe-0 i-icon-col">
                                                        <i v-if="stock.remarks" class="pl-3 bi bi-info-circle"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            :title=stock.remarks></i>
                                                    </td>
                                                    <td class="py-2 ps-3">{{ formatDate(stock.date) }}</td>
                                                    <!-- <td class="py-2">{{ truncateText(stock.product.name) }} </td> -->
                                                    <td class="py-2">{{ stock.reason }} </td>
                                                    <td class="py-2">{{ stock.reference }} </td>
                                                    <td class="py-2 text-end pe-3">
                                                        <span v-if="stock.quantity">
                                                            <span v-if="stock.transaction_type_id == 1">
                                                                +{{ numberFormatter(stock.quantity) }}
                                                            </span>
                                                            <span v-else>
                                                                -{{ numberFormatter(stock.quantity) }}
                                                            </span>
                                                        </span>
                                                    </td>
                                                    <td class="py-2 text-end pe-3">{{ numberFormatter(stock.balance) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="fs-6" width="100%">
                                            <tbody>
                                                <tr class="odd" v-for="stock in StockLogs">
                                                    <td class="table-responsive-width">
                                                        <div class="row ">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase ">
                                                                DATE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <div>
                                                                    {{ formatDate(stock.date) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row row-divider ">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                REASON</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ stock.reason }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                REF</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ stock.reference }} </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                AFF QTY</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span v-if="stock.transaction_type_id == 1">
                                                                    +{{ numberFormatter(stock.quantity) }}
                                                                </span>
                                                                <span v-else>
                                                                    -{{ numberFormatter(stock.quantity) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row row-divider ">
                                                            <div class="text-gray-400 col-4 left-side text-uppercase">
                                                                BALANCE</div>
                                                            <div class="col-8 right-side text-end">
                                                                <span>{{ numberFormatter(stock.balance) }}</span>
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
                                <div v-if="pagination && StockLogs?.length > 0" class="my-3 row ps-1 ps-md-0">

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
            <!-- Stock Movement Filter Modal -->
            <div class="modal fade" id="productsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="productsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Stock Movement Filters</h5>
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
                                        <label for="modalSearchCode" class="text-gray-600 col-form-label">SELECT
                                            PRODUCT</label>
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
                                <div class="pt-3 col-12" v-if="!select_product"></div>
                                <div class="col-12" v-if="select_product">
                                    <div class="items-center text-muted">
                                        <label for="modalSearchCode" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker v-model="from_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />
                                    </div>
                                </div>
                                <div class="col-12" v-if="select_product">
                                    <div class="items-center text-muted">
                                        <label for="modalSearchCode" class="text-gray-600 col-form-label">TO
                                            DATE</label>
                                        <Datepicker v-model="to_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>
                                </div>
                                <div class="col-6" v-if="select_product">
                                    <div class="py-4 mt-2">
                                        <button @click="productsFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6" v-if="select_product">
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
        </template>
        <template #loader>
            <Loader ref="loading_bar" />
        </template>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, watch, nextTick } from 'vue';
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import Datepicker from 'vue3-datepicker'

import moment from 'moment';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY HH:mm:ss');
};

const from_date = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
const to_date = ref(new Date());
const loading_bar = ref(null);

const search_product = ref({
    from_date: from_date.value,
    to_date: to_date.value,
});

const StockLogs = ref([]);
const products_items = ref([]);
const select_product = ref(null);

const placeholderText = 'DD/MM/YYYY';
const validationErrors = ref({});
const validationMessage = ref(null);

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

const reload = async () => {

    try {
        nextTick(() => {
            loading_bar.value.start();
        });
        if (search_product.value.product_id) {
            const tableData = (
                await axios.get(route('report.stockMovement.all'), {
                    params: {
                        page: page.value,
                        per_page: pageCount.value,
                        search_product_id: search_product.value.product_id,
                        search_product_from_date: search_product.value.from_date,
                        search_product_to_date: search_product.value.to_date,
                    },
                })
            ).data;

            StockLogs.value = tableData.data;
            pagination.value = tableData.meta;
        } else {
            StockLogs.value = null
            pagination.value = null;
        }
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
    select_product.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    search_product.value = {
        from_date: from_date.value,
        to_date: to_date.value,
    };
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#productsFilterModal').modal('hide');
};

const getProductSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('stockable.products.multiselect', { query }));
            products_items.value = response.data;

        } catch (error) {
            products_items.value = [];
        }
    } else {
        getProducts();
    }
};

const getProducts = async () => {
    try {
        const response = (await axios.get(route("stockable.products.list"))).data;
        products_items.value = response.data;
    } catch (error) {
        convertValidationNotification(error);
    }
}

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const clearFilters = async () => {
    select_product.value = null;
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    search_product.value = {
        from_date: from_date.value,
        to_date: to_date.value,
    };
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

const setToDate = () => {
    to_date.value.setHours(23, 59, 59, 999);
}

onMounted(() => {
    setToDate();
    getProducts();
    reload();
});

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
</style>
