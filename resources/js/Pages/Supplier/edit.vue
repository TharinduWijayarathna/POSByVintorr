<template>
    <AppLayout title="Supplier Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="col-12 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Supplier&nbsp;
                                </h1>
                            </div>
                        </div>

                        <div class="shadow card">
                            <div class="px-0 py-3 text-sm card-body px-md-8">
                                <div class="px-5 pt-5 pb-5 card-body px-md-8">
                                    <div class="row">
                                        <div class="col-8 col-md-10">
                                            <div class="mb-2 d-flex align-items-center">
                                                <a href="#"
                                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{
                                                        truncateText(selectedSupplierData.name) }}</a>
                                            </div>
                                        </div>

                                        <div class="pb-4 col-4 col-md-2 text-end pe-0">
                                            <a href="#" @click.prevent="showSupplierModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Edit supplier"
                                                class="btn btn-sm btn-primary me-3 px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Edit</a>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-geo-alt text-primary fs-2 me-4"></i>
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="text-gray-500">Address</div>
                                                            <div v-if="selectedSupplierData.address"
                                                                class="mt-1 text-gray-700">
                                                                {{ selectedSupplierData.address }}
                                                            </div>
                                                            <div v-else class="mt-1 text-gray-700">No Address</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 ">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-building text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="text-gray-500">Company</div>
                                                            <div v-if="selectedSupplierData.company"
                                                                class="mt-1 text-gray-700">{{
                                                                    selectedSupplierData.company }}</div>
                                                            <div v-else class="mt-1 text-gray-700">No Company</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 ">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-globe2 text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="text-gray-500">Website</div>
                                                            <div v-if="selectedSupplierData.website"
                                                                class="mt-1 text-gray-700 two-line-truncate">
                                                                <a class="text-gray-700" data-toggle="tooltip"
                                                                    data-placement="bottom"
                                                                    :title="selectedSupplierData.website"
                                                                    :href="selectedSupplierData.website"
                                                                    target="_blank">{{
                                                                        selectedSupplierData.website }}</a>
                                                            </div>
                                                            <div v-else class="mt-1 text-gray-700">No Website</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-telephone text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8" v-if="selectedSupplierData.contact">
                                                            <div class="text-gray-500">Contact</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.contact">
                                                                {{ selectedSupplierData.contact }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.contact2">
                                                                {{ selectedSupplierData.contact2 }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.contact3">
                                                                {{ selectedSupplierData.contact3 }}
                                                            </div>
                                                        </div>
                                                        <div class="col-8" v-else>
                                                            <div class="text-gray-500">Contact</div>
                                                            <div class="mt-1 text-gray-700">No Contact Number</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500  me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-envelope text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8" v-if="selectedSupplierData.email">
                                                            <div class="text-gray-500">Email</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.email">
                                                                {{ selectedSupplierData.email }}

                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.email2">
                                                                {{ selectedSupplierData.email2 }}

                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedSupplierData.email3">
                                                                {{ selectedSupplierData.email3 }}
                                                            </div>
                                                        </div>
                                                        <div class="col-8" v-else>
                                                            <div class="text-gray-500">Email</div>
                                                            <div class="mt-1 text-gray-700">No Email</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 shadow card">
                            <div class="px-0 py-3 text-sm card-body px-md-8">
                                <div class="px-5 card-header px-md-8">
                                    <ul
                                        class="border-transparent nav nav-stretch nav-line-tabs nav-line-tabs-2x fs-5 fw-bold">
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-10"
                                                data-toggle="tooltip" data-placement="bottom" title="Expenses"
                                                :class="[showExpenses == 1 ? 'active' : '']" href="#expense-data"
                                                @click="showExpenseTab">
                                                Expenses </a>
                                        </li>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-10"
                                                data-toggle="tooltip" data-placement="bottom" title="Purchase Order"
                                                :class="[showPO == 1 ? 'active' : '']" href="#PO-data"
                                                @click="showPOTab">
                                                PO </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-5 py-5 card-body px-md-8">

                                    <div class="row" :class="[showExpenses == 1 ? 'd-block' : 'd-none']"
                                        id="expense-data">
                                        <ExpenseData :supplierId="page_props.supplier_id"
                                            :currentUserStatus="page_props.status" />
                                    </div>

                                    <div class="row" :class="[showPO == 1 ? 'd-block' : 'd-none']" id="POS-data">
                                        <POData :supplierId="page_props.supplier_id"
                                            :currentUserStatus="page_props.status" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #modal>
            <!-- Edit Supplier Modal -->
            <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-6 col-8">
                                    <h5 class="modal-title" style="color: #071437">Edit
                                        Supplier
                                    </h5>
                                </div>
                                <div class="mb-6 col-4 text-end">
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"
                                        @click="closeSupplierModal"></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Supplier</lable>
                                    <input v-model="supplierData.name" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Customer Name" />
                                    <small v-if="validationErrors.name"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.name }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Company</lable>
                                    <input v-model="supplierData.company" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Customer Name" />
                                    <small v-if="validationErrors.company"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.company }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Postal Address</lable>
                                    <input v-model="supplierData.address" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Postal Address" />
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
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Email 2</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="supplierData.email2" type="email"
                                                class="mt-1 form-control form-control-sm" placeholder="Enter Email 2" />
                                            <small v-if="validationErrors.email2"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email2 }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Email 3</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="supplierData.email3" type="email"
                                                class="mt-1 form-control form-control-sm" placeholder="Enter Email 3" />
                                            <small v-if="validationErrors.email3"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email3 }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Phone No.1</lable>
                                    <input v-model="supplierData.contact" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Phone Number1" />
                                    <small v-if="validationErrors.contact"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.contact }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Phone No.2</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="supplierData.contact2" type="text"
                                                class="mt-1 form-control form-control-sm"
                                                placeholder="Enter Phone Number2" />
                                            <small v-if="validationErrors.contact2"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.contact2 }}</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                            <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                data-placement="bottom" title="Add another phone number"
                                                @click="phoneIndex = 3">+
                                                Another
                                                Phone No.</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Phone No.3</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="supplierData.contact3" type="text"
                                                class="mt-1 form-control form-control-sm"
                                                placeholder="Enter Phone Number3" />
                                            <small v-if="validationErrors.contact3"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.contact3 }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Website</lable>
                                    <input v-model="supplierData.website" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                    <small v-if="validationErrors.website"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.website }}</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-4 col-12 text-end">
                                    <button v-if="selectedSupplierData.id" type="button" class="btn btn-light-info"
                                        data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                        style="font-weight: bold"
                                        @click.prevent="updateSupplier(selectedSupplierData.id)">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
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
                                @click.prevent="deleteSupplier(selectedCustomerId)">
                                <i class="bi bi-trash"></i>
                                DELETE
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
import { ref, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import moment from 'moment';
import { usePage } from '@inertiajs/vue3'
import ExpenseData from '@/Pages/Supplier/Components/Expense/All.vue';
import POData from '@/Pages/Supplier/Components/PurchaseOrder/All.vue';

const { props } = usePage();
const page_props = props;
const pagination = ref({});

const invoices = ref([]);
const dueData = ref([]);

const customers = ref([]);
const selectedSupplierData = ref({});
const phoneIndex = ref(1);

const showExpenses = ref(1);
const showPO = ref(0);

const validationErrors = ref({});
const validationMessage = ref(null);

const supplierData = ref({});
const selectedCustomerId = ref(null);

const loading_bar = ref(null);



const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
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

function showExpenseTab() {
    showExpenses.value = 1;
    showPO.value = 0;
}

function showPOTab() {
    showPO.value = 1;
    showExpenses.value = 0;
}

const showSupplierModal = async () => {

    resetValidationErrors();
    if (selectedSupplierData.value) {
        getSupplierData();
        supplierData.value = selectedSupplierData.value;
        $("#supplierModal").modal("show");
    }

};

const getSupplierData = async () => {
    const supplier = (await axios.get(route('supplier.get', page_props.supplier_id))).data
    selectedSupplierData.value = supplier;
};

function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

const closeSupplierModal = async () => {
    $("#supplierModal").modal("hide");
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
        getSupplierData();

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

const getCustomers = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('customer.all'))).data;

        customers.value = tableData.data;
        pagination.value = tableData.meta;
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

const deleteSupplier = async (customerId) => {
    try {


        await axios.delete(route("customer.delete", customerId));
        closeDeleteModal();
        successMessage('Customer deleted successfully');
        getCustomers();


    } catch (error) {
    }
}

const getCustomerInvoices = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('customer.invoice.all', page_props.supplier_id))).data;
        invoices.value = tableData.data;
        pagination.value = tableData.meta;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getDueAmounts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('customer.due', page_props.supplier_id))).data;
        dueData.value = tableData;

        const customerDueSpan = document.getElementById('customer_due');
        let formattedAmounts = 'Has due of ';

        dueData.value.forEach(entry => {
            if (entry.currency_name !== null) {
                const formattedAmount = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(entry.due_amount.toFixed(2));
                formattedAmounts += `${entry.currency_name} ${formattedAmount}, `;
            }
        });

        // Remove trailing comma and space
        formattedAmounts = formattedAmounts.slice(0, -2);

        customerDueSpan.textContent = formattedAmounts;
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
        return text.length > 50 ? text.substring(0, 50) + '...' : text;
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
    getCustomers();
    getSupplierData();
    getCustomerInvoices();
    getDueAmounts();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}
</style>
