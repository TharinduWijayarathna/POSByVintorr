<template>
    <div class="row" v-if="emptyImageVal == 1">
        <div class="text-center col-12 mt-15">
            <img src="@/../src/images/EmptyState/invoices.webp" height="200" />
        </div>
        <div class="mt-8 text-center col-12">
            <h1 class="text-gray-700 heading fw-bold fs-2hx">No any Invoices</h1>
        </div>
        <div class="mt-2 text-center col-12 mb-15">
            <p class="text-gray-700 fs-3">Invoices will appear here</p>
        </div>
    </div>

    <div v-else class="page-count-show page-count-show-mobile">
        <!-- Desktop View -->
        <div v-if="invoices.length > 0"
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

        <!-- Mobile View -->
        <div v-if="invoices.length > 0" class="mt-6 col-12 d-none justify-content-end align-self-end">
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
                        <th>INVOICE NO.</th>
                        <th>CASHIER</th>
                        <th>DATE</th>
                        <th class="text-center">CURRENCY</th>
                        <th class="text-end pe-3">TOTAL</th>
                        <th class="text-end">PAID AMOUNT</th>
                        <th class="text-end">DUE AMOUNT</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <!-- Table Data Rows -->
                    <tr v-for="(invoice, index) in invoices" :key="index" class="cursor-pointer">
                        <td class="py-2 text-center" @click.prevent="editInvoice(invoice.id)">
                            <div v-if="invoice.status == 1 && invoice.credit_status == 1"
                                class="badge badge-light-success paid-status">PAID
                            </div>
                            <div v-if="invoice.status == 0" class="badge badge-light-info">
                                PENDING
                            </div>
                            <div v-if="invoice.credit_status == 0 && invoice.customer_paid == 0"
                                class="badge badge-light-secondary none-status">NONE
                            </div>
                            <div v-if="invoice.status == 4" class="badge badge-light-info">
                                RETURN
                            </div>
                            <div v-if="invoice.credit_status == 0 && invoice.customer_paid > 0"
                                class="badge badge-light-warning partial-status">
                                PARTIAL
                            </div>
                        </td>
                        <td class="py-2" @click.prevent="editInvoice(invoice.id)">{{
                            invoice.code }}</td>
                        <td v-if="invoice.customer_id == null" class="py-2" @click.prevent="editInvoice(invoice.id)">
                            Walking
                            Customer</td>
                        <td v-if="invoice.cashier != null" class="py-2" @click.prevent="editInvoice(invoice.id)">{{
                            truncateText(invoice.cashier_name) }}
                        </td>
                        <td v-if="invoice.cashier == null" class="py-2" @click.prevent="editInvoice(invoice.id)"></td>
                        <td class="py-2" @click.prevent="editInvoice(invoice.id)">{{
                            formatDate(invoice.invoice_date) }}</td>
                        <td class="py-2 text-center" @click.prevent="editInvoice(invoice.id)">{{
                            invoice.currency_name }}</td>

                        <td class="py-2 text-end pe-3" @click.prevent="editInvoice(invoice.id)">
                            {{
                                invoice.formatted_total }}
                        </td>
                        <td class="py-2 text-end" @click.prevent="editInvoice(invoice.id)">{{
                            invoice.formatted_customer_paid }}
                        </td>
                        <td class="py-2 text-end" @click.prevent="editInvoice(invoice.id)">{{
                            invoice.formatted_due }}
                        </td>
                        <td class="py-2 text-end pe-1">
                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                title="Print invoice" class="p-2" @click.prevent="historyPrint(invoice.id)">
                                <i class="bi bi-printer fs-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- End of Table Data Rows -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div v-if="invoices.length > 0" class="my-3 row">

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
import { ref, nextTick, defineProps, onBeforeMount } from 'vue';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faFloppyDisk, faTrash, faArrowRightRotate } from '@fortawesome/free-solid-svg-icons'
import moment from "moment";


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
const invoices = ref([]);
const emptyImageVal = ref(0);
const loading_bar = ref(null);


// Function to format date
const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY');
};

// Function to set the current page
const setPage = (newPage) => {
    page.value = newPage;
    getCustomerInvoices();
};

const perPageChange = async () => {
    page.value = 1;
    getCustomerInvoices();
};

const getCustomerInvoices = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('customer.invoice.all', props.customerId), {
            params: {
                page: page.value,
                per_page: pageCount.value,
            },
        })
    ).data;

    invoices.value = tableData.data;
    pagination.value = tableData.meta;

    if (invoices.value.length > 0) {
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

const editInvoice = async (id) => {
    try {
        const response = await axios.get(route('invoice.edit', id));
        if (response.data.type == 1) {
            window.location.href = route('invoice.load', response.data.id);
        }
    } catch (error) {

    }

};

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};


onBeforeMount(() => {
    getCustomerInvoices();
});
</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

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
</style>
