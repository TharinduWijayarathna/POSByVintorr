<template>
    <AppLayout title="Return Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-0 col-lg-12 mt-lg-0">
                        <!-- Desktop View -->
                        <div
                            class="mt-5 d-none d-sm-flex justify-content-start align-items-center col-form-label mt-lg-0">
                            <h1 class="return-main-header">
                                Return&nbsp;<span class="text-gray-600 fs-1">-&nbsp;{{ returnData.code }}</span>
                            </h1>
                            <a v-if="selectCustomer == null" href="#" data-toggle="tooltip" data-placement="bottom"
                                title="Add customer"
                                class="py-2 btn btn-sm fs-4 fw-bold text-sm-center text-lg-start customer-btn"
                                data-bs-toggle="modal" data-bs-target="#customerModal" @click="showCustomerModal">&plus;
                                CUSTOMER</a>
                            <a v-if="selectCustomer != null" href="#" @click.prevent="removeCustomer"
                                data-toggle="tooltip" data-placement="bottom" title="Remove customer"
                                class="py-2 btn btn-sm fs-4 fw-bold text-sm-center text-lg-start customer-btn">✖
                                {{ truncateCustomerText(selectCustomerName) }}</a>
                        </div>

                        <!-- Mobile View -->
                        <div class="mt-5 row d-flex d-sm-none col-form-label">
                            <div class="col-12">
                                <h1 class="return-main-header">
                                    Return&nbsp;<span class="text-gray-600 fs-1 ">-&nbsp;{{ returnData.code }}</span>
                                </h1>
                            </div>
                            <div class="text-right col-12">
                                <a v-if="selectCustomer == null" href="#" data-toggle="tooltip" data-placement="bottom"
                                    title="Add customer"
                                    class="py-2 btn btn-sm fs-4 fw-bold text-sm-center text-lg-start customer-btn"
                                    data-bs-toggle="modal" data-bs-target="#customerModal"
                                    @click="showCustomerModal">&plus;
                                    CUSTOMER</a>
                                <a v-if="selectCustomer != null" href="#" @click.prevent="removeCustomer"
                                    data-toggle="tooltip" data-placement="bottom" title="Remove customer"
                                    class="py-2 btn btn-sm fs-4 fw-bold text-sm-center text-lg-start customer-btn">✖
                                    {{ truncateCustomerText(selectCustomerName) }}</a>
                            </div>
                        </div>

                        <div class="shadow card">
                            <div class="p-4 text-sm card-body p-md-9">
                                <div class="row">
                                    <form id="addNewCardValidation" class="mt-0 row w-100 pe-0 pe-lg-3"
                                        @submit.prevent="addProduct">
                                        <div class="col-lg-3 pe-0">
                                            <div class="text-gray-600 col-form-label">Product</div>
                                            <div class="input-group input-group-merge">
                                                <Multiselect v-model="select_product" :options="productsSearch.map(product => ({
                                                    ...product, name: truncateText(product.name),
                                                    searchableText: `${product.code} : ${truncateText(product.name)}`
                                                }))" data-toggle="tooltip" data-placement="bottom"
                                                    title="Select product" class="z__index"
                                                    :class="{ 'error-border': changeProductBorder === 1 }"
                                                    :showLabels="false" :close-on-select="true" :searchable="true"
                                                    placeholder="Select Product" label="searchableText" track-by="id"
                                                    max-height="200" @search-change="getProductSearch"
                                                    :internal-search="false">
                                                    <template #noOptions>
                                                        <div>Press a key to select Product</div>
                                                    </template>
                                                    <template #noResult>
                                                        <div>No matching products found</div>
                                                    </template>
                                                </Multiselect>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 pe-0 pe-lg-3">
                                            <div class="text-gray-600 col-form-label">Price</div>
                                            <input type="text" class="form-control form-control-solid form-control-sm"
                                                data-toggle="tooltip" data-placement="bottom" title="Enter price"
                                                name="search" v-model="selected_product.formatted_selling_price"
                                                placeholder="0.00" />
                                        </div>
                                        <div class="col-lg-3 pe-0 pe-lg-3">
                                            <div class="text-gray-600 col-form-label">Qty</div>
                                            <input type="text" class="form-control form-control-solid form-control-sm"
                                                data-toggle="tooltip" data-placement="bottom" title="Enter quantity"
                                                name="search" v-model="selected_product.quantity" placeholder="0" />
                                        </div>
                                        <div
                                            class="mt-3 text-right col-lg-2 mt-lg-0 d-md-block d-lg-flex align-self-end align-item-right pe-0">
                                            <button type="submit" class="btn btn-info btn-sm fw-bold"
                                                data-toggle="tooltip" data-placement="bottom" title="Add item"><i
                                                    class="bi bi-plus-lg fw-bold"></i>
                                                ADD</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="row table-responsive gx-0">
                                    <div class="table">
                                        <table
                                            class="table mt-5 align-middle table-hover table-striped fs-6 gy-3 mt-md-10"
                                            id="kt_ecommerce_sales_table">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">CODE</th>
                                                    <th>NAME</th>
                                                    <th class="text-end">RETURNING QTY</th>
                                                    <th class="text-end">PRICE</th>
                                                    <th class="text-end">LINE TOTAL</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">

                                                <tr v-for="(returnItem, index) in returnProducts">
                                                    <td class="py-2 ps-3">
                                                        <span>{{ returnItem.product_code }}</span>
                                                    </td>
                                                    <td class="py-2">
                                                        <span>{{ truncateText(returnItem.product_name) }}</span>
                                                    </td>
                                                    <td class="py-2 text-end">
                                                        <span>{{ formatQuantity(returnItem.return_quantity) }}</span>
                                                    </td>
                                                    <td class="py-2 text-end">
                                                        <span>{{ returnItem.formatted_unit_price }}</span>
                                                    </td>
                                                    <td class="py-2 text-end">
                                                        <span>{{ returnItem.formatted_total }}</span>
                                                    </td>
                                                    <td class="py-2 text-end pe-3">
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="bottom" title="Delete item"
                                                            @click.prevent="deleteItem(returnItem.id)" class="p-2">
                                                            <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="ps-3">
                                                        <h3>Item Lines : {{ returnProducts.length }}</h3>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-gray-700 text-end fw-bold">
                                                        <div class="mt-1">Currency</div><br />
                                                        <div class="mt-3">Total :</div>
                                                    </td>
                                                    <td class="text-gray-700 text-end fw-bold multiselect-menu ">
                                                        <div class="d-flex justify-content-end">
                                                            <Multiselect v-model="select_currency" :options="currencies"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Select currency"
                                                                class="z__index custom-multiselect" :showLabels="false"
                                                                :close-on-select="true" :clear-on-select="false"
                                                                :searchable="true" placeholder="Select Currency"
                                                                label="code" track-by="id" max-height="200" />
                                                        </div>
                                                        <br />{{ viewTotal }}
                                                    </td>
                                                    <td></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-5 mb-2 row">
                                    <div class="col-lg-10">
                                        <textarea v-model="remark" class="form-control" placeholder="Remark"
                                            data-kt-autosize="true" rows="1.5" style="resize: none;">
        </textarea>
                                        <small v-if="validationErrors.remark"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.remark }}
                                        </small>
                                    </div>
                                    <div class="mt-3 col-lg-2 mt-lg-0" data-toggle="tooltip" data-placement="bottom"
                                        title="Confirm return">
                                        <a href="javascript:void(0)" @click.prevent="returnDone"
                                            class="px-0 btn btn-lg w-100 btn-info" data-toggle="modal"
                                            style="font-weight: bold" data-target="#newCustomerModal">
                                            <i class="bi bi-arrow-return-left"></i>
                                            RETURN
                                        </a>
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

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #071437">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close"
                        id="add-customer-modal-close-button"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data">
                        <div class="mb-8 row">
                            <div class="mt-2 col-12 col-sm-4">
                                <span class="text-gray-600">PHONE NUMBER</span>
                            </div>
                            <div class="col-10 col-md-6">
                                <input type="text" class="form-control form-control-sm" v-model="customer.contact"
                                    placeholder="Phone Number" required />
                            </div>
                            <div class="col-2 col-md-2">
                                <div class="py-0 row h-100">
                                    <div class="col-12 justify-content-end text-end">
                                        <a class="square-customer-button h-100" @click.prevent="searchLoyaltyCustomer"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Search customer using phone number">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="loyalty_customer.name != null">
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">NAME</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm"
                                        v-model="loyalty_customer.name" placeholder="Name" disabled />
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">EMAIL ADDRESS</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" class="form-control form-control-sm"
                                        v-model="loyalty_customer.email" placeholder="Email Address" disabled />
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">ADDRESS</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm"
                                        v-model="loyalty_customer.address" placeholder="Address" disabled />
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12 justify-content-end text-end">
                                    <a class="square-customer-button" @click.prevent="saveCustomer(loyalty_customer)"
                                        data-toggle="tooltip" data-placement="bottom" title="Add customer">
                                        <i class=" bi bi-chevron-double-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div v-if="loyalty_customer.name == null">
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">NAME</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm" v-model="customer.name"
                                        placeholder="Name" required />
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">EMAIL ADDRESS</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" class="form-control form-control-sm" v-model="customer.email"
                                        placeholder="Email Address" />
                                </div>
                            </div>
                            <div class="mt-2 row">
                                <div class="mt-2 col-md-4">
                                    <span class="text-gray-600">ADDRESS</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-sm" v-model="customer.address"
                                        placeholder="Address" />
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12 justify-content-end text-end">
                                    <a type="button" class="square-clear-button" @click.prevent="saveNewCustomer"
                                        data-toggle="tooltip" data-placement="bottom" title="Add customer">
                                        <i class=" bi bi-chevron-double-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, nextTick, watch } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import Multiselect from 'vue-multiselect';

const orderId = ref(null);

const customer = ref({});
const loyalty_customer = ref({});
const selectCustomer = ref(null);
const selectCustomerName = ref('');

const product = ref({});
const product_id = ref(null);
const items = ref({});
const items_price = ref(null);

const select_product = ref(null);
const products = ref([]);
const selected_product = ref({});

const product_search_query = ref('');
const productsOptionsList = ref([]);
const productsSearch = ref([]);
const selectedId = ref(null);

const total = ref(0);
const viewTotal = ref(0.00);

const returnProducts = ref([]);
const returnData = ref({});

const business_details = ref({});
const currencies = ref([]);
const select_currency = ref({});
const select_currency_id = ref(null);

const remark = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

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

const addProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {



        if (selected_product.value.formatted_selling_price != null) {
            const formattedPrice = selected_product.value.formatted_selling_price.replace(/,/g, '');
            if (/^\d+(\.\d+)?$/.test(formattedPrice)) {
                selected_product.value.unit_price = parseFloat(formattedPrice);
            } else {
                selected_product.value.unit_price = selected_product.value.formatted_selling_price;
            }
        }
        if (selected_product.value.formatted_selling_price == null) {
            selected_product.value.unit_price = null;
        }
        selected_product.value.product_id = product_id.value;
        selected_product.value.customer_id = selectCustomer.value;

        const response = (await axios.post(route("return.item.store"), selected_product.value)).data;

        select_product.value = null;
        if (select_product.value == null) {
            product_id.value = null;
        }
        selected_product.value = {};
        getReturnProducts();
        calculateTotals();

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

const getReturnProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const returnProductData = (await axios.get(route('return.get.products'))).data;
        returnProducts.value = returnProductData;
        nextTick(() => {
            loading_bar.value.finish();
        });
        return returnProductData.length;
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const formatQuantity = (quantity) => {
    return Number(quantity).toFixed(0);
};

const deleteItem = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.delete(route('return.delete.product', id)).then((response) => {
            getReturnProducts();
            calculateTotals();
        });
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const searchProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const optionsDropdown = document.getElementById('productsOptionsDropDown');

    if (optionsDropdown) {
        optionsDropdown.style.display = 'block';
    }

    if (product_search_query.value.trim() !== "") {
        try {
            const search_result = (
                await axios.post(route('product.search'), {
                    params: {
                        search: product_search_query.value,
                    },
                })
            ).data.data;
            productsOptionsList.value = search_result.map((element) => {
                return {
                    value: element.id,
                    text: element.name,
                };
            });
        } catch (error) {
            console.error(error);
        }
    } else {
        productsOptionsList.value = [];
    }

    nextTick(() => {
        loading_bar.value.finish();
    });

    return productsOptionsList;
};

const setProduct = async (productId) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (productId != 0) {
            const productData = (await axios.get(route('product.get.details', productId))).data;
            product.value = productData;
            product_id.value = productData.id;
            items_price.value = productData.selling;
            product_search_query.value = productData.name
            toggleDropDown();
        } else {
            product.value = {};
            items_price.value = null;
        }
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const searchLoyaltyCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const tableData = (
            await axios.get(
                route('customer.number.get', customer.value.contact)
            )
        ).data;
        loyalty_customer.value = tableData;
        loyalty_customer.value.name = truncateText(loyalty_customer.value.name);

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const saveNewCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const customerData = (await axios.post(route('customer.store'), customer.value)).data;
        if (customerData == "exists") {
            errorMessage('This customer already exists');
        } else {
            saveCustomer(customerData);
        }

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
        convertValidationError(error);
    }
};

const saveCustomer = async (customer) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (customer) {
            const customer_all_details = (await axios.post(route('return.customer'), customer)).data;
            selectCustomer.value = customer_all_details.customer_id;
            selectCustomerName.value = customer_all_details.customer_name;
            $('#customerModal').modal('hide');
            $('#add-customer-modal-close-button').click();
            successMessage('Customer selected successfully');
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const removeCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.get(route('return.customer.remove', orderId.value));
        selectCustomer.value = null;
        successMessage('Successfully removed the customer');
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const calculateTotals = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const calTotal = (await axios.get(route('return.get.total'))).data;
        const formattedTotal = Number(calTotal).toFixed(2);
        const formattedViewTotal = Number(calTotal).toLocaleString('en-US', { minimumFractionDigits: 2 });

        total.value = formattedTotal;
        viewTotal.value = formattedViewTotal;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const getReturnOrder = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const order = (await axios.get(route('return.order'))).data;
        returnData.value = order;
        orderId.value = order.id;
        selectCustomer.value = order.customer_id;
        selectCustomerName.value = order.customer_name;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const returnDone = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        const order_status = ref(null);

        if (remark.value == null && selectCustomer.value == null) {
            errorMessage('Please enter remark or select customer');
            order_status.value = 0;
        } else if (selectCustomer.value !== null && remark.value === null) {
            order_status.value = 1;
        } else if (remark.value.length <= 0 && selectCustomer.value == null) {
            errorMessage('Please enter remark or select customer');
            order_status.value = 0;
        } else {
            order_status.value = 1;
        }

        if (order_status.value == 1) {
            const length = await getReturnProducts();
            if (length <= 0) {
                errorMessage('Please select return item');
            } else {

                if (select_currency.value) {
                    select_currency_id.value = select_currency.value.id;
                }

                const data = {
                    order_id: orderId.value,
                    order_total: total.value,
                    remark: remark.value,
                    currency_id: select_currency_id.value,
                };
                try {
                    resetValidationErrors();
                    await axios.post(route('return.done'), data);

                    nextTick(() => {
                        loading_bar.value.start();
                    });
                    const print = await axios.get(route('return.print', orderId.value), { responseType: "blob" });
                    const url = window.URL.createObjectURL(print.data);
                    window.open(url, "_blank");

                    successMessage('Return is successful');
                    window.location.href = route("return.index");
                    nextTick(() => {
                        loading_bar.value.finish();
                    });
                } catch (error) {
                    nextTick(() => {
                        loading_bar.value.finish();
                    });
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage(error.response.data.message);
                    } else {
                        errorMessage('An error occurred while processing your request.');
                    }
                }
            }
        }
        nextTick(() => {
            loading_bar.value.finish();
        });

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
    }
};

const toggleDropDown = () => {
    const optionsDropdown = document.getElementById('productsOptionsDropDown');

    if (optionsDropdown) {
        optionsDropdown.style.display = optionsDropdown.style.display === 'none' ? 'block' : 'none';
    }
};

const getProductSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('product.multiselect.all.search', { query }));
            productsSearch.value = response.data;

        } catch (error) {
            productsSearch.value = [];
        }
    } else {
        getLimitedProducts();
    }
};

const getLimitedProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const productData = (await axios.get(route("limited.products"))).data;
        productsSearch.value = productData;
        selectedId.value = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
};

const showCustomerModal = async () => {
    customer.value.contact = "";
    customer.value.name = "";
    customer.value.email = "";
    customer.value.address = "";
    loyalty_customer.value = {};
    loyalty_customer.value.name = "";
    loyalty_customer.value.email = "";
    loyalty_customer.value.address = "";
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

const getCurrency = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("currency.list"))).data;
        currencies.value = response.data;

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getBusinessDetails = async () => {
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            select_currency.value.id = business_details.value.currency_id;
            select_currency.value.code = business_details.value.currency_name;
        }

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getAllProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const productData = (await axios.get(route('cart.products.all'))).data;
        products.value = productData;
        selectedId.value = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const getProductDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (select_product.value != null) {
            product_id.value = select_product.value.id;
        }


        const tableData = (
            await axios.get(
                route('product.get', product_id.value)
            )
        ).data;
        selected_product.value = tableData;

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

const truncateCustomerText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 50 ? text.substring(0, 50) + '...' : text;
    }
    return '';
};

watch(() => select_product.value, () => {
    product_id.value = null;
    // if (select_product.value.id != null) {
    //     getProductDetails();
    // }
    getProductDetails();
});

onMounted(() => {
    getLimitedProducts();
    getReturnOrder();
    getReturnProducts();
    calculateTotals();
    getAllProducts();
    getCurrency();
    getBusinessDetails();
});

</script>

<style lang="scss" scoped>
.options-dropdown {
    background: #fff;
    position: absolute;
    top: 32px;
    z-index: 10;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.option {
    padding: 8px;
    cursor: pointer;
}

.mt-2px {
    margin-top: 2px;
}

#productsOptionsDropDown {
    top: 37px;
}

.multiselect-menu {
    width: 200px;
}

.multiselect-add-new-button {
    width: 100%;
    border-radius: 0%;
}

.custom-multiselect {
    text-align: center;
    width: 80px;
}
</style>
