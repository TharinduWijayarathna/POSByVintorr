<template>
    <AppLayout title="Employee Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="col-12 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Employee
                                </h1>
                            </div>
                        </div>

                        <div class="shadow card">
                            <div class="px-0 py-3 text-sm card-body px-md-8">
                                <div class="px-5 pt-5 pb-5 card-body px-md-8">
                                    <div class="row">
                                        <div class="col-md-10 col-8">
                                            <div class="mb-2 d-flex align-items-center">
                                                <a href="#"
                                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{
                                                        truncateText(selectedEmployeeData.name) }}</a>
                                            </div>
                                        </div>
                                        <div class="pb-4 col-md-2 col-4 text-end">
                                            <a href="#" @click.prevent="showEmployeeModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Edit employee"
                                                class="btn btn-sm btn-primary px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Edit</a>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-geo-alt text-primary fs-2 me-4"></i></div>
                                                        <div class="col-8">
                                                            <div class="text-gray-500">Address</div>
                                                            <div v-if="selectedEmployeeData.address"
                                                                class="mt-1 text-gray-700">
                                                                {{ selectedEmployeeData.address }}
                                                            </div>
                                                            <div v-else class="mt-1 text-gray-700">No Address</div>
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
                                                        <div class="col-8" v-if="selectedEmployeeData.contact">
                                                            <div class="text-gray-500">Contact</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.contact">
                                                                {{ selectedEmployeeData.contact }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.contact2">
                                                                {{ selectedEmployeeData.contact2 }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.contact3">
                                                                {{ selectedEmployeeData.contact3 }}
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
                                                <div class="mb-2 text-gray-500 me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-envelope text-primary fs-2 me-4"></i></div>
                                                        <div class="col-8" v-if="selectedEmployeeData.email">
                                                            <div class="text-gray-500">Email</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.email">
                                                                {{ selectedEmployeeData.email }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.email2">
                                                                {{ selectedEmployeeData.email2 }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedEmployeeData.email3">
                                                                {{ selectedEmployeeData.email3 }}
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
                                        <div class="col-md-3"></div>
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
                                                data-toggle="tooltip" data-placement="bottom" title="Payrolls"
                                                :class="[showPayrolls == 1 ? 'active' : '']" href="#payroll-data"
                                                @click="showPayrollTab">
                                                Payrolls </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-5 py-5 card-body px-md-8">

                                    <div class="row" :class="[showPayrolls == 1 ? 'd-block' : 'd-none']"
                                        id="payroll-data">
                                        <PayrollData :employeeId="page_props.employee_id"
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
            <!-- Edit Employee Modal -->
            <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-6 col-8">
                                    <h5 class="modal-title" style="color: #071437">Edit
                                        Employee
                                    </h5>
                                </div>
                                <div class="mb-6 col-4 text-end">
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                        @click="closeEmployeeModal"></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Employee</label>
                                    <input v-model="employeeData.name" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Employee Name" />
                                    <small v-if="validationErrors.name"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.name }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Postal Address</label>
                                    <input v-model="employeeData.address" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Postal Address" />
                                    <small v-if="validationErrors.address"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.address }}</small>
                                </div>

                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Email 1</label>
                                    <input v-model="employeeData.email" type="email"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                    <small v-if="validationErrors.email"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.email }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Email 2</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="employeeData.email2" type="email"
                                                class="mt-1 form-control form-control-sm" placeholder="Enter Email 2" />
                                            <small v-if="validationErrors.email2"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email2 }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Email 3</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="employeeData.email3" type="email"
                                                class="mt-1 form-control form-control-sm" placeholder="Enter Email 3" />
                                            <small v-if="validationErrors.email3"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email3 }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Phone No.1</label>
                                    <input v-model="employeeData.contact" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Phone Number1" />
                                    <small v-if="validationErrors.contact"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.contact }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Phone No.2</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="employeeData.contact2" type="text"
                                                class="mt-1 form-control form-control-sm"
                                                placeholder="Enter Phone Number2" />
                                            <small v-if="validationErrors.contact2"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.contact2 }}</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                            <a href="javascript:void(0)" class="text-primary" @click="phoneIndex = 3">+
                                                Another
                                                Phone No.</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 col-12">
                                    <label class="text-gray-600">Phone No.3</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="employeeData.contact3" type="text"
                                                class="mt-1 form-control form-control-sm"
                                                placeholder="Enter Phone Number3" />
                                            <small v-if="validationErrors.contact3"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.contact3 }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-4 col-12 text-end">
                                    <button v-if="selectedEmployeeData.id" type="button" class="btn btn-light-info"
                                        style="font-weight: bold"
                                        @click.prevent="updateEmployee(selectedEmployeeData.id)">
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
                            Are you sure you want to delete this customer?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-dismiss="modal" @click="closeDeleteModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                @click.prevent="deleteCustomer(selectedCustomerId)">
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
import PayrollData from '@/Pages/Employee/Components/Payroll/All.vue';

const { props } = usePage();
const page_props = props;
const pagination = ref({});

const invoices = ref([]);
const dueData = ref([]);

const customers = ref([]);
const selectedEmployeeData = ref({});
const phoneIndex = ref(1);

const showPayrolls = ref(1);

const validationErrors = ref({});
const validationMessage = ref(null);

const employeeData = ref({});
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

function showPayrollTab() {
    showPayrolls.value = 1;
}

const showEmployeeModal = async () => {

    resetValidationErrors();
    if (selectedEmployeeData.value) {
        getEmployeeData();
        employeeData.value = selectedEmployeeData.value;
        $("#employeeModal").modal("show");
    }

};

const getEmployeeData = async () => {
    const employee = (await axios.get(route('employee.get', page_props.employee_id))).data
    selectedEmployeeData.value = employee;
};

function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

const closeEmployeeModal = async () => {
    $("#employeeModal").modal("hide");
};

const updateEmployee = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        await axios.post(route('employee.update', id), employeeData.value);
        successMessage('Employee successfully updated');
        closeEmployeeModal();
        getEmployeeData();

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

const deleteCustomer = async (customerId) => {
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
        const tableData = (await axios.get(route('customer.invoice.all', page_props.employee_id))).data;
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
        const tableData = (await axios.get(route('customer.due', page_props.employee_id))).data;
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
    getEmployeeData();
    getCustomerInvoices();
    getDueAmounts();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}
</style>
