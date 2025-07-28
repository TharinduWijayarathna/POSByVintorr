<template>
    <AppLayout title="Hold Orders">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                            <h1 class="main-header-text">
                                Hold Orders
                            </h1>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <div class="mb-3 row d-none d-lg-flex">
                                    <div class="mb-2 col-md-2 mb-md-0">

                                        <select class="form-select form-select-sm" v-model="orderStatus"
                                            data-control="select2" data-placeholder="Select an option"
                                            @keyup="getSearch">
                                            <option value="0" disabled selected>Select Status</option>
                                            <option value="2">Hold</option>
                                            <option value="5">Enquire</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">

                                        <input v-model="search" type="text" class="form-control form-control-sm"
                                            placeholder="Serach by Bill No." @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">

                                        <Multiselect v-model="select_search_customer" :options="truncatedCustomers"
                                            class="z__index" :showLabels="false" :close-on-select="true"
                                            :clear-on-select="false" @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <div class="col-md-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3"></div>

                                </div>
                                <div class="px-0 text-sm py-3filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4 ms-1">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openHoldFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="orders.length > 0">
                                            <div class="dataTables_length me-1" id="kt_ecommerce_products_table_length">
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

                                <div class="row"
                                    v-if="emptyImageVal == 1 && orderStatus == 0 && search == null && select_search_customer == null">
                                    <!-- Icon for Empty Hold Carts State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-cart-x fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty Hold Carts State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Hold Carts</h2>
                                    </div>

                                    <!-- Subtext for Empty Hold Carts State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Hold carts will appear here.</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (orderStatus != 0 || search != null || select_search_customer != null)">
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

                                <div class="row" v-if="orders.length > 0">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3 "
                                            id="kt_ecommerce_sales_table">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="text-center">STATUS</th>
                                                    <th class="ps-3">BILL NO.</th>
                                                    <th>CREATED BY</th>
                                                    <th>CUSTOMER</th>
                                                    <th class="text-end">SUB TOTAL</th>
                                                    <th class="text-end">DISCOUNT</th>
                                                    <th class="text-end">TOTAL</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="order in orders"
                                                    onmouseover="this.style.backgroundColor='#F2F4F4'; this.style.cursor='pointer';"
                                                    onmouseout="this.style.backgroundColor='#ffffff';">
                                                    <td class="py-2 text-center" @click.prevent="reActive(order.id)">
                                                        <div v-if="order.status == 2" class="badge badge-light-danger">
                                                            HOLD
                                                        </div>
                                                        <div v-if="order.status == 5" class="badge badge-light-info">
                                                            ENQUIRE
                                                        </div>
                                                    </td>
                                                    <td class="py-2 ps-3" @click.prevent="reActive(order.id)">
                                                        <span>{{ order.code }}</span>
                                                    </td>
                                                    <td v-if="order.cashier != null" class="py-2"
                                                        @click.prevent="reActive(order.id)">
                                                        <span>
                                                            {{ truncateText(order.cashier_name) }}
                                                        </span>
                                                    </td>
                                                    <td v-if="order.cashier == null" class="py-2"
                                                        @click.prevent="reActive(order.id)"></td>
                                                    <td class="py-2" @click.prevent="reActive(order.id)">
                                                        <span v-if="order.customer_id == null">Walking Customer</span>
                                                        <span v-else>{{ truncateText(order.customer_name) }}</span>
                                                    </td>
                                                    <td class="py-2 text-end" @click.prevent="reActive(order.id)">
                                                        <span>{{ order.formatted_sub_total }}</span>
                                                    </td>
                                                    <td class="py-2 text-end" @click.prevent="reActive(order.id)">
                                                        <span>{{ order.formatted_discount }}</span>
                                                    </td>
                                                    <td class="py-2 text-end" @click.prevent="reActive(order.id)">
                                                        <span>{{ order.formatted_total }}</span>
                                                    </td>
                                                    <td class="py-2 text-end pe-1">
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;"
                                                                href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete order"
                                                                @click="showDeleteModal(order.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-3 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="order in orders" :key="order.id" style="margin-bottom: 20px;">
                                            <!-- Start a new table for each order -->
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Status -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Status</td>
                                                        <td class="text-end">
                                                            <div v-if="order.status == 2"
                                                                class="badge badge-light-danger">HOLD</div>
                                                            <div v-if="order.status == 5"
                                                                class="badge badge-light-info">ENQUIRE</div>
                                                        </td>
                                                    </tr>

                                                    <!-- Bill No. -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Bill No.</td>
                                                        <td class="text-end">{{ order.code }}</td>
                                                    </tr>

                                                    <!-- Created By -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Created By</td>
                                                        <td class="text-end">
                                                            <span v-if="order.cashier != null">{{
                                                                truncateText(order.cashier_name) }}</span>
                                                            <span v-else></span>
                                                        </td>
                                                    </tr>

                                                    <!-- Customer -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Customer</td>
                                                        <td class="text-end">
                                                            <span v-if="order.customer_id == null">Walking
                                                                Customer</span>
                                                            <span v-else>{{ truncateText(order.customer_name) }}</span>
                                                        </td>
                                                    </tr>

                                                    <!-- Sub Total -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Sub Total</td>
                                                        <td class="text-end">{{ order.formatted_sub_total }}</td>
                                                    </tr>

                                                    <!-- Discount -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Discount</td>
                                                        <td class="text-end">{{ order.formatted_discount }}</td>
                                                    </tr>

                                                    <!-- Total -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Total</td>
                                                        <td class="text-end">{{ order.formatted_total }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <!-- <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <a class="btn text-primary"
                                    href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Delete order" @click="showDeleteModal(order.id)">
                                <i class="p-2 bi bi-trash-fill fs-4 text-danger"></i>
                            </a>
                        </div>
                    </td> -->

                                                        <td class="text-end">
                                                            <a class="p-2 text-primary" href="javascript:void(0)"
                                                                data-toggle="tooltip" title="Edit"
                                                                @click="showDeleteModal(order.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-4 text-danger"></i>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <div v-if="orders.length > 0" class="my-3 row align-items-center ps-1 ps-md-0">
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

                                    <!-- Pagination Info and Controls -->
                                    <div class="col-sm-6 d-flex justify-content-end align-items-center">
                                        <!-- Pagination Info -->
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>

                                        <!-- Pagination Controls -->
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_previous">
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
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_next">
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
            <!-- Hold Filter Modal -->
            <div class="modal fade" id="holdFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="holdFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Hold Filters</h5>
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

                                        <select id="modalOrderStatus"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="orderStatus" data-control="select2"
                                            data-placeholder="Select Status" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="2">Hold</option>
                                            <option value="5">Enquire</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Serach by Bill No."
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <Multiselect id="modalSelectCustomer" v-model="select_search_customer"
                                            :options="truncatedCustomers" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false"
                                            @search-change="getCustomer" :searchable="true"
                                            placeholder="Select Customer" label="truncatedName" track-by="id"
                                            max-height="200" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="holdFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyHoldFilter" class="btn btn-info ms-4 fw-bold">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close" @click="closeDeleteModal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-darkr" style="font-weight: bold" data-dismiss="modal"
                        data-toggle="tooltip" data-placement="bottom" title="Cancel" @click="closeDeleteModal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-light-danger" style="font-weight: bold" data-toggle="tooltip"
                        data-placement="bottom" title="Delete order"
                        @click.prevent="deleteHoldedOrder(selectedOrderId)">
                        <i class="bi bi-trash"></i>
                        DELETE
                    </button>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, watch, nextTick, computed } from 'vue';
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { router, usePage } from '@inertiajs/vue3'

import _ from 'lodash';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const orders = ref([]);
const emptyImageVal = ref(0);

const search = ref(null);
const search_order = ref({});
const select_search_customer = ref(null);
const orderStatus = ref(0);
const customers = ref([]);

const selectedOrderId = ref(null);

const loading_bar = ref(null);



const getCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('customer.multiselect.all'))).data;
        customers.value = tableData;

        // Adding a walking customer
        const newCustomer = { id: 0, name: "Walking Customer" };
        customers.value.push(newCustomer);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

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

const openHoldFilterModal = () => {
    loading_bar.value.start();
    $('#holdFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyHoldFilter = () => {
    $('#holdFilterModal').modal('hide');
    reload();
};

const holdFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    orderStatus.value = 0;
    reload();
    $('#holdFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (
            await axios.get(route('cart.hold.all'), {
                params: {
                    page: page.value,
                    per_page: pageCount.value,
                    "filter[search]": search.value,
                    search_order_customer_id: search_order.value.customer_id,
                    search_order_status: search_order.value.orderStatus,
                },
            })
        ).data;

        orders.value = tableData.data;
        pagination.value = tableData.meta;

        if (orders.value.length > 0) {
            emptyImageVal.value = 0;
        } else {
            emptyImageVal.value = 1;
        }
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const getHoldCartOrders = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('cart.hold.all'))).data;

        orders.value = tableData.data;
        pagination.value = tableData.meta;

        if (orders.value.length > 0) {
            emptyImageVal.value = 0;
        } else {
            emptyImageVal.value = 1;
        }
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const reActive = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        await axios.get(route('cart.order.reactive', id)).then((response) => {
            if (response) {
                if (response.data.type == 0) {
                    router.visit(route("cart", 'sidebar'));
                } else {
                    router.visit(route('invoice.index'));
                }
            }

        });

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        errorMessage(error.response.data.message);
    }
};

const deleteHoldedOrder = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        await axios.get(route('order.delete', id));
        getHoldCartOrders();
        closeDeleteModal();
        successMessage('Deletion of hold order successful');

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

// Show Delete Confirmation Modal
function showDeleteModal(orderId) {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 2 || page_props.auth.user.user_role_id == 4) {
        selectedOrderId.value = orderId;
        $("#deleteModal").modal("show");
    } else {
        errorMessage('Access denied');
    }

}

// Delete Order (Delete Confirmation Modal)
function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

watch(orderStatus, (newValue) => {
    search_order.value.orderStatus = newValue;
    getSearch();
});

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    select_search_customer.value = null;
    orderStatus.value = 0;
    reload();
}

watch(select_search_customer, (newValue) => {
    if (newValue) {
        search_order.value.customer_id = newValue.id;
    } else {
        search_order.value.customer_id = null;
    }
    getSearch();
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

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const truncateShortText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 24 ? text.substring(0, 24) + '...' : text;
    }
    return '';
};

const truncatedCustomers = computed(() => {
    return customers.value.map(customer => ({
        ...customer,
        truncatedName: truncateShortText(customer.name),
    }));
});

onMounted(() => {
    getHoldCartOrders();
    getCustomer();
});

</script>

<style lang="scss" scoped></style>
