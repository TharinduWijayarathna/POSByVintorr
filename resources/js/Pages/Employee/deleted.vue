<template>
    <AppLayout title="Deleted Employees">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div
                                class="pb-0 mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center pb-sm-3">

                                <h1 class="main-header-text">
                                    View Deleted Employees
                                </h1>
                            </div>

                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-md-2 mb-md-0">

                                        <input v-model="nameFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Name" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <input v-model="contactFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Phone" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <input v-model="emailFilter" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Email" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="col-md-2 align-self-end">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                    </div>

                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openEmployeeFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="employees.length > 0">
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
                                <!-- Employee Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && nameFilter == '' && emailFilter == '' && contactFilter == ''">
                                    <!-- Icon for Empty State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-person-x fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Deleted Employees</h2>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your deleted employees will appear here</p>
                                    </div>
                                </div>

                                <div class="row"
                                    v-if="emptyImageVal == 1 && (nameFilter != '' || emailFilter != '' || contactFilter != '')">
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

                                <div v-if="employees.length > 0" class="row">
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Employee</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!-- Table Data Rows -->
                                                <tr v-for="employee in employees" class="cursor-pointer">
                                                    <td class="py-2 ps-3">{{
                                                        truncateText(employee.name) }}</td>
                                                    <td class="py-2">{{
                                                        truncateText(employee.contact) }}</td>
                                                    <td class="py-2">{{
                                                        truncateText(employee.email) }}</td>
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Restore Employee"
                                                                @click="showRestoreModal(employee.id)">
                                                                <i class="p-2 bi bi-arrow-repeat fs-5 text-dark"></i>
                                                            </button>
                                                        </div>

                                                    </td>
                                                </tr>

                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="employee in employees" :key="'employee-table-' + employee.id"
                                            style="margin-bottom: 20px;">
                                            <!-- Start a new table for each employee -->
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Employee Name -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Name</td>
                                                        <td class="text-end">{{ truncateText(employee.name) }}</td>
                                                    </tr>

                                                    <!-- Employee Phone Number -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Phone</td>
                                                        <td class="text-end">{{ truncateText(employee.contact) }}</td>
                                                    </tr>

                                                    <!-- Employee Email -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Email</td>
                                                        <td class="text-end">{{ truncateText(employee.email) }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase"></td>
                                                        <td class="text-end">
                                                            <a href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Restore employee"
                                                                @click="showRestoreModal(employee.id)">
                                                                <i
                                                                    class="bi bi-arrow-repeat fs-3 restore-btn text-dark"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="employees.length > 0" class="my-3 row align-items-center ps-1 ps-md-0">
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
                                                <!-- Previous Button -->
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_previous">
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
        </template>
        <template #modal>
            <!-- Employee Filter Modal -->
            <div class="modal fade" id="employeeFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="employeeFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Deleted Employees Filters</h5>
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
                                        <input id="modalSearchName" v-model="nameFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Filter by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchPhoneNo" v-model="contactFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Filter by Number"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchEmail" v-model="emailFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Filter by Email"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="employeeFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyEmployeeFilter" class="btn btn-info ms-4 fw-bold">
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
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                @click="closeRestoreModal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to restore this employee?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-dark" style="font-weight: bold"
                                data-dismiss="modal" @click="closeRestoreModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-info" style="font-weight: bold"
                                @click.prevent="restoreEmployee(selectedEmployeeId)">
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
import { ref, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'

import _ from 'lodash';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const employees = ref([]);
const emptyImageVal = ref(0);

// Filter variables
const nameFilter = ref("");
const emailFilter = ref("");
const contactFilter = ref("");

const validationErrors = ref({});
const validationMessage = ref(null);
const selectedEmployeeId = ref(null);

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

// Clear filters Method
function clearFilters() {
    emptyImageVal.value = 0;
    nameFilter.value = "";
    emailFilter.value = "";
    contactFilter.value = "";
    getEmployees();
}

const openEmployeeFilterModal = () => {
    loading_bar.value.start();
    $('#employeeFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyEmployeeFilter = () => {
    $('#employeeFilterModal').modal('hide');
    reload();
};

const employeeFilterModalClear = async () => {
    emptyImageVal.value = 0;
    nameFilter.value = "";
    emailFilter.value = "";
    contactFilter.value = "";
    getEmployees();
    $('#employeeFilterModal').modal('hide');
};

// Show Restore Confirmation Modal
function showRestoreModal(employeeId) {
    selectedEmployeeId.value = employeeId;
    $("#restoreModal").modal("show");
}

// Restore Employee (Restore Confirmation Modal)
function closeRestoreModal() {
    $("#restoreModal").modal("hide");
}

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

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('employee.deleted.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_employee_name: nameFilter.value,
                search_employee_email: emailFilter.value,
                search_employee_contact: contactFilter.value,
            },
        })
    ).data;

    employees.value = tableData.data;
    pagination.value = tableData.meta;

    if (employees.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getEmployees = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('employee.deleted.all'))).data;

        employees.value = tableData.data;
        pagination.value = tableData.meta;

        if (employees.value.length > 0) {
            emptyImageVal.value = 0;
        } else {
            emptyImageVal.value = 1;
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

const restoreEmployee = async (employeeId) => {
    try {
        await axios.get(route("employee.restore", employeeId));
        closeRestoreModal();
        successMessage('Employee restored successfully');
        reload();
    } catch (error) {
    }
}

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

onMounted(() => {
    getEmployees();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.restore-btn {
    color: green;
}
</style>
