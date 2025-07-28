<template>
    <div class="row" v-if="emptyImageVal == 1">
        <div class="text-center col-12 mt-15">
            <img src="@/../src/images/EmptyState/purchase-orders.webp" height="200" />
        </div>
        <div class="mt-8 text-center col-12">
            <h1 class="text-gray-700 heading fw-bold fs-2hx">No any purchase order</h1>
        </div>
        <div class="mt-2 text-center col-12 mb-15">
            <p class="text-gray-700 fs-3">Purchase order will appear here</p>
        </div>
    </div>
    <div v-else class="page-count-show page-count-supplier-mobile">
        <div v-if="purchase_order.length > 0" class="pb-6 col-12 justify-content-end align-self-end d-none d-md-flex">
            <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                <label><select name="kt_ecommerce_products_table_length" aria-controls="kt_ecommerce_products_table"
                        class="form-select form-select-sm form-select-solid" :value="2" v-model="pageCount"
                        @change="perPageChange">
                        <option v-for="perPageCount in perPage" :key="perPageCount" :value="perPageCount"
                            v-text="perPageCount" />
                    </select></label>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5 mt-md-5">
                <thead>
                    <tr class="text-white text-start fw-bold fs-7 text-uppercase" style="background-color: black;">
                        <th class="ps-3">PO NO.</th>
                        <th>CREATED BY</th>
                        <th>DATE</th>
                        <th class="text-center">Currency</th>
                        <th class="text-end pe-3">TOTAL</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <tr v-for="purchaseOrder in purchase_order" class="cursor-pointer">
                        <td class="py-2 ps-3" @click.prevent="viewPurchaseOrder(purchaseOrder.id)">{{
                            purchaseOrder.code }}</td>
                        <td class="py-2 ps-3" @click.prevent="viewPurchaseOrder(purchaseOrder.id)">{{
                            purchaseOrder.cashier_name }}</td>
                        <td class="py-2 ps-3" @click.prevent="viewPurchaseOrder(purchaseOrder.id)">{{
                            formatDate(purchaseOrder.date) }}</td>
                        <td class="py-2 text-center ps-3" @click.prevent="viewPurchaseOrder(purchaseOrder.id)">{{
                            purchaseOrder.currency_name }}</td>
                        <td class="py-2 text-end pe-3" @click.prevent="viewPurchaseOrder(purchaseOrder.id)">{{
                            purchaseOrder.formatted_total }}</td>
                        <td class="py-2 text-end pe-1">
                            <a href="javascript:void(0)" class="p-2"
                                @click.prevent="PurchaseOrderPrint(purchaseOrder.id)" data-toggle="tooltip"
                                data-placement="bottom" title="Print voucher">
                                <i class="bi bi-printer fs-3"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div v-if="purchase_order.length > 0" class="my-3 row">

        <div class="col-sm-6">
            <div for="purchase_uom" class="text-gray-600 col-form-label">
                Showing {{ pagination.from }} to
                {{ pagination.to }} of
                {{ pagination.total }} entries
            </div>
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            <div class="dataTables_paginate paging_simple_numbers" id="kt_ecommerce_sales_table_paginate">
                <ul class="pagination">
                    <li class="paginate_button page-item previous"
                        :class="pagination.current_page == 1 ? 'disabled' : ''" id="kt_ecommerce_sales_table_previous">
                        <a href="javascript:void(0)" @click="setPage(pagination.current_page - 1)"
                            aria-controls="kt_ecommerce_sales_table" data-dt-idx="0" tabindex="0" class="page-link"><i
                                class="previous"></i></a>
                    </li>
                    <template v-for="(page, index) in pagination.last_page">
                        <template
                            v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                            <li class="paginate_button page-item" :key="index"
                                :class="pagination.current_page == page ? 'active' : ''"><a href="javascript:void(0)"
                                    @click="setPage(page)" aria-controls="kt_ecommerce_sales_table" data-dt-idx="1"
                                    tabindex="0" class="page-link">{{ page }}</a></li>
                        </template>
                    </template>
                    <li class="paginate_button page-item next"
                        :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                        id="kt_ecommerce_sales_table_next"><a href="javascript:void(0)"
                            @click="setPage(pagination.current_page + 1)" aria-controls="kt_ecommerce_sales_table"
                            data-dt-idx="6" tabindex="0" class="page-link"><i class="next"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <Loader ref="loading_bar" />
</template>
<script setup>
import { ref, onMounted, nextTick, defineProps } from 'vue';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faFloppyDisk, faTrash, faArrowRightRotate } from '@fortawesome/free-solid-svg-icons'
import moment from "moment";


library.add(faTrash);
library.add(faFloppyDisk);
library.add(faArrowRightRotate);

const props = defineProps({
    supplierId: Number,
    currentUserStatus: Number
});

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const purchase_order = ref([]);
const emptyImageVal = ref(0);

const loading_bar = ref(null);

const page_props = props;


// Function to format date
const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

const setPage = (newPage) => {
    page.value = newPage;
    getSupplierPO();
};

const perPageChange = async () => {
    page.value = 1;
    getSupplierPO();
};

const getSupplierPO = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('supplier.purchaseOrder.all', props.supplierId), {
            params: {
                page: page.value,
                per_page: pageCount.value,
            },
        })
    ).data;

    purchase_order.value = tableData.data;
    pagination.value = tableData.meta;

    if (purchase_order.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const viewPurchaseOrder = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('purchaseOrder.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }

};

const PurchaseOrderPrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('purchaseOrder.print', id), { responseType: "blob" });
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

onMounted(() => {
    getSupplierPO();
});
</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}
</style>
