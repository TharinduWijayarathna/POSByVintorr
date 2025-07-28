<template>
    <div class="row" v-if="bills.length == 0">
        <div class="text-center col-12 mt-15">
            <img src="@/../src/images/EmptyState/bills.webp" height="200" />
        </div>
        <div class="mt-8 text-center col-12">
            <h1 class="text-gray-700 heading fw-bold fs-2hx">No any bills</h1>
        </div>
        <div class="mt-2 text-center col-12 mb-15">
            <p class="text-gray-700 fs-3">POS Bills will appear here</p>
        </div>
    </div>
    <div v-else class="page-count-show page-count-show-mobile">
        <!-- Desktop view -->
        <div v-if="bills.length > 0"
            class="pb-6 col-12 pb-sm-0 pb-md-6 d-none d-sm-flex justify-content-end align-self-end">
            <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                <label><select name="kt_ecommerce_products_table_length" aria-controls="kt_ecommerce_products_table"
                        class="form-select form-select-sm form-select-solid" :value="2" v-model="pageCount"
                        @change="perPageChange">
                        <option v-for="perPageCount in perPage" :key="perPageCount" :value="perPageCount"
                            v-text="perPageCount" />
                    </select></label>
            </div>
        </div>

        <!-- Mobile view -->
        <div v-if="bills.length > 0" class="mt-6 col-12 d-none justify-content-end align-self-end">
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
            <table class="table align-middle table-row-dashed fs-6 gy-5 mt-15 mt-sm-12 mt-md-5">
                <thead>
                    <tr class="text-white text-start fw-bold fs-7 text-uppercase" style="background-color: black;">
                        <th class="text-center ps-3">STATUS</th>
                        <th>BILL NO.</th>
                        <th>CREATED BY</th>
                        <th>DATE</th>
                        <th class="text-center">CURRENCY</th>
                        <th class="text-end">SUB TOTAL</th>
                        <th class="text-end">DISCOUNT</th>
                        <th class="text-end pe-3">TOTAL</th>
                        <th class="text-end pe-3">DUE AMOUNT</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <!-- Table Data Rows -->
                    <tr v-for="(bill, index) in bills" :key="index" class="cursor-pointer">
                        <td class="py-2 text-center"
                            @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">
                            <div v-if="bill.status == 1">
                                <div v-if="bill.credit_status == 0" class="badge badge-light-warning credit-status">
                                    CREDIT
                                </div>
                                <div v-else class="badge badge-light-success done-status">DONE</div>
                            </div>
                            <div v-if="bill.status == 0" class="badge badge-light-info">PENDING
                            </div>
                            <div v-if="bill.status == 2" class="badge badge-light-danger">HOLD
                            </div>
                            <div v-if="bill.status == 4" class="badge badge-light-info">RETURN
                            </div>
                        </td>
                        <td class="py-2" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            bill.code }}</td>

                        <td v-if="bill.cashier != null" class="py-2"
                            @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                                truncateText(bill.cashier_name) }}
                        </td>
                        <td v-if="bill.cashier == null" class="py-2"
                            @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)"></td>

                        <td class="py-2" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            formatDate(bill.date) }}</td>
                        <td class="py-2 text-center"
                            @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                                bill.currency_name }}</td>

                        <td class="py-2 text-end" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            bill.formatted_sub_total }}
                        </td>
                        <td class="py-2 text-end" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            bill.formatted_discount }}
                        </td>
                        <td class="py-2 text-end" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            bill.formatted_total }}
                        </td>
                        <td class="py-2 text-end" @click.prevent="viewBill(bill.status, bill.credit_status, bill.id)">{{
                            bill.formatted_due }}
                        </td>
                        <td class="py-2 text-end pe-1">
                            <a v-if="bill.status == 1" href="javascript:void(0)" class="p-2"
                                @click.prevent="historyPrint(bill.id)">
                                <i class="bi bi-printer fs-3" data-toggle="tooltip" data-placement="bottom"
                                    title="Print bill"></i>
                            </a>
                            <a v-else href="javascript:void(0)" class="p-2" @click.prevent="errorPrint">
                                <i class="bi bi-printer fs-3 text-danger" data-toggle="tooltip" data-placement="bottom"
                                    title="Print unavailable"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- End of Table Data Rows -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div v-if="bills.length > 0" class="my-3 row">

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
import Swal from "sweetalert2";

import { router } from "@inertiajs/vue3";

library.add(faTrash);
library.add(faFloppyDisk);
library.add(faArrowRightRotate);

const props = defineProps({
    customerId: Number,
});

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});
const bills = ref([]);
const loading_bar = ref(null);


// Function to format date
const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

// Function to set the current page
const setPage = (newPage) => {
    page.value = newPage;
    getCustomerPosBills();
};

const perPageChange = async () => {
    page.value = 1;
    getCustomerPosBills();
};

const getCustomerPosBills = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('customer.bill.all', props.customerId), {
            params: {
                page: page.value,
                per_page: pageCount.value,
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

const errorPrint = () => {
    errorMessage('The order payment is not done!');
}

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

const viewBill = async (billStatus, creditStatus, billId) => {
    if (billStatus == 1 && creditStatus == 0) {
        router.visit(route("credit.edit", billId));
    } else if (billStatus == 1 && creditStatus == 1) {
        router.visit(route('receipt.index'));
    } else if (billStatus == 2) {
        await axios.get(route('cart.order.reactive', billId)).then((response) => {
            if (response) {
                if (response.data.type == 0) {
                    window.location.href = route("cart.index");
                } else {
                    window.location.href = route("invoice.index");
                }
            }
        });
    } else if (billStatus == 4) {
        router.visit(route('return.edit', billId));
    }
}

onMounted(() => {
    getCustomerPosBills();
});
</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.done-status {
    background-color: #DBFFE4;
    color: green;
}

.credit-status {
    background-color: #ffe0db;
    color: rgba(255, 51, 0, 0.822);
}
</style>
