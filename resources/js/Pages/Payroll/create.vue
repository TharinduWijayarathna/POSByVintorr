<template>
    <AppLayout title="Create Payrolls">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Create&nbsp;Payroll&nbsp;
                                </h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="py-0 col-12 col-md-6">
                                <div class="shadow card h-100">
                                    <div class="p-4 py-1 text-sm p-md-7 py-md-7 card-body">
                                        <form @submit.prevent="savePayroll" enctype="multipart/form-data">
                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">
                                                        Employee
                                                        <i class="cursor-pointer bi bi-plus-lg-fill fs-5 plus-btn-employee"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new employee"
                                                            @click.prevent="showEmployeeModal"></i>
                                                    </div>
                                                </div>
                                                <div class="pt-2 col-md-9 d-flex multiselect-wrapper">
                                                    <Multiselect v-model="select_employee" :options="employeesSearch"
                                                        class="z__index" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        :internal-search="false" placeholder="Select Employee"
                                                        label="name" track-by="id" max-height="200"
                                                        @search-change="getEmployeesSearch">
                                                        <template #noOptions>
                                                            <div>Press a key to select Employee</div>
                                                        </template>
                                                        <template #noResult>
                                                            <div>No matching employees found</div>
                                                        </template>
                                                    </Multiselect>
                                                    <button v-if="removeState == false" type="button"
                                                        class="supplier-remove-btn mr-9" data-bs-toggle="dropdown"
                                                        data-kt-menu-placement="bottom-end"
                                                        @click.prevent="removeEmployee">
                                                        <i class="bi bi-x-lg fs-3 text-danger supplier-remove-icon"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <small v-if="validationErrors.employee_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.employee_id }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Date</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input type="date" v-model="date"
                                                        class="form-control form-control-sm" :format="'dd/MM/yyyy'" />
                                                    <small v-if="validationErrors.date"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.date }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Currency</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <Multiselect v-model="select_currency" :options="currencies"
                                                        class="z__index" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Currency" label="code" track-by="id"
                                                        max-height="200" />
                                                    <small v-if="validationErrors.currency_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.currency_id }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Amount</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input v-model="payroll.amount" type="number"
                                                        class="form-control form-control-sm" placeholder="Amount" />
                                                    <small v-if="validationErrors.amount"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.amount }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Description</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <textarea v-model="payroll.description" class="form-control"
                                                        placeholder="Payroll Description" data-kt-autosize="true"
                                                        style="resize: none; font-size: 12px;" rows="4"></textarea>
                                                    <small v-if="validationErrors.description"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.description }}</small>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div
                                                    class="mt-6 mb-6 col-12 text-end align-items-end mb-md-0 mt-md-20 ">
                                                    <button type="submit" class="btn btn-info ms-2"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Add payroll" style="font-weight: bold">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="py-0 mt-4 col-12 col-md-6 mt-md-0">

                                <div class="shadow card h-100">
                                    <div class="p-4 py-0 text-sm p-md-7 py-md-3 card-body">

                                        <form @submit.prevent="savePayroll" enctype="multipart/form-data">
                                            <div class="mt-3 row">
                                                <div class="col-md-12">
                                                    <div class="text-gray-600 col-form-label">Payment Slip</div>
                                                </div>
                                                <div class="pt-2 col-md-12 d-flex justify-content-center">
                                                    <!-- Desktop View -->
                                                    <div class="p-2 image-input image-chooser-border d-none d-md-flex"
                                                        :class="{ 'drag-over': isDragOver }"
                                                        @dragover.prevent="handleDragOver"
                                                        @drop.prevent="handleFileDrop"
                                                        @dragleave.prevent="handleDragLeave">
                                                        <div class="image-input-wrapper w-400px h-400px">
                                                            <template v-if="payroll.isPdf == 1">
                                                                <iframe :src="payroll.image_url" width="100%"
                                                                    height="500px"></iframe>
                                                            </template>
                                                            <template v-else>
                                                                <img v-if="payroll.image_url && edit_payroll.isPdf == 0"
                                                                    :src="payroll.image_url"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution" />
                                                                <img v-else
                                                                    src="@/../src/assets/media/receipt/expense-receipt-placeholder.png"
                                                                    @click="openImageFile"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution" />
                                                            </template>
                                                        </div>

                                                        <label @click="openImageFile"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-40px h-40px bg-body d-none"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1">
                                                            <i
                                                                class="bi bi-pencil-square text-dark-fill fs-3 edit-btn"></i>

                                                        </label>
                                                        <input type="file" hidden ref="fileInput"
                                                            accept="image/jpg, image/png, application/pdf"
                                                            id="payroll_image" name="avatar" @change="onFileChange">
                                                        <input type="hidden" name="avatar_remove">
                                                        <span v-if="payroll.image_url"
                                                            @click.prevent="selectImageRemove"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body d-none"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1">
                                                            <i class="bi bi-x-lg delete-btn"></i> </span>
                                                    </div>

                                                    <!-- Mobile View -->
                                                    <div class="p-2 image-input image-chooser-border-mobile d-block d-md-none"
                                                        :class="{ 'drag-over': isDragOver }"
                                                        @dragover.prevent="handleDragOver"
                                                        @drop.prevent="handleFileDrop"
                                                        @dragleave.prevent="handleDragLeave">
                                                        <div class="image-input-wrapper-mobile">
                                                            <template v-if="payroll.isPdf == 1">
                                                                <iframe :src="payroll.image_url" width="100%"
                                                                    height="500px"></iframe>
                                                            </template>
                                                            <template v-else>
                                                                <img v-if="payroll.image_url && edit_payroll.isPdf == 0"
                                                                    :src="payroll.image_url"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution-mobile" />
                                                                <img v-else
                                                                    src="@/../src/assets/media/receipt/expense-receipt-placeholder.png"
                                                                    @click="openImageFile" class="mb-2 img-fluid" />
                                                            </template>
                                                        </div>

                                                        <label @click="openImageFile"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-40px h-40px bg-body d-none"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1">
                                                            <i
                                                                class="bi bi-pencil-square text-dark-fill fs-3 edit-btn"></i>

                                                        </label>
                                                        <input type="file" hidden ref="fileInput"
                                                            accept="image/jpg, image/png, application/pdf"
                                                            id="payroll_image" name="avatar" @change="onFileChange">
                                                        <input type="hidden" name="avatar_remove">
                                                        <span v-if="payroll.image_url"
                                                            @click.prevent="selectImageRemove"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body d-none"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1">
                                                            <i class="bi bi-x-lg delete-btn"></i> </span>
                                                    </div>
                                                </div>
                                                <div class="pt-4 text-center text-gray-400 col-12">
                                                    File should be less than 5MB
                                                </div>
                                            </div>

                                            <div class="mt-10 row">
                                                <div class="mt-4 col text-end">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #modal>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="payrollDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this payroll?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Employee Modal -->
            <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewEmployee" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title" style="color: #071437">Add New Employee</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            @click="closeEmployeeModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Name</lable>
                                        <input v-model="employeeData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Employee Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="employeeData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="employeeData.email" type="email"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                        <small v-if="validationErrors.email"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.email }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 2">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[emailIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="employeeData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 3">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="employeeData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone No.1</lable>
                                        <input v-model="employeeData.contact" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Phone Number1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 2">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[phoneIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="employeeData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 3">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="employeeData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Category Modal -->
            <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New Category</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                        @click="closeCategoryModal"></button>
                                </div>
                            </div>
                            <form @submit.prevent="saveNewCategory" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Name</div>
                                <input v-model="categoryData.name" type="text" class="form-control form-control-sm"
                                    placeholder="Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="mt-2 btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </form>
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
import { ref, onMounted, watch, nextTick } from "vue";
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import { Link } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'


const { props } = usePage();
const page_props = props;

const emailIndex = ref(1);
const phoneIndex = ref(1);
const employeeData = ref({});

const categories = ref([]);
const employees = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_edit_category = ref({});
const select_employee = ref([]);
const edit_select_employee = ref([]);
const select_edit_unit = ref({});
const employeesSearch = ref([]);

const product = ref({});
const payroll = ref({});
const edit_payroll = ref({});

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_category = ref(null);
const search_category_id = ref(null);
const search_employee = ref(null);
const search_employee_id = ref(null);
const search_date = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const loading_bar = ref(null);

const isDragOver = ref(false);

const today = new Date();
const date = ref(formatDate(today));
function formatDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}

const edit_date = ref(new Date());
const placeholderText = 'DD/MM/YYYY';
const business_details = ref({});
const currencies = ref([]);
const select_currency = ref({});
const edit_select_currency = ref({});

const payroll_image = ref(null);
const edit_payroll_image = ref(null);
const categoryData = ref({});
const removeState = ref(false);



const onFileChange = (e) => {

    const fileInputs = document.getElementById('payroll_image');
    if (fileInputs.files[0]) {
        const file = fileInputs.files[0];
        if (file.size < 5 * 1024 * 1024) {
            payroll.value.image = e.target.files[0];
            payroll_image.value = e.target.files[0];

            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    payroll.value.image_url = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
};

const handleDragOver = (e) => {
    e.preventDefault();
    isDragOver.value = true;
};

const handleFileDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.size < 5 * 1024 * 1024) {
        payroll.value.image = file;
        payroll_image.value = file;
        savePayroll();
        const reader = new FileReader();
        reader.onload = (event) => {
            payroll.value.image_url = event.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        errorMessage('File size should be less than 5MB');
    }
    isDragOver.value = false;
};

const handleDragLeave = (e) => {
    e.preventDefault();
    isDragOver.value = false;
};

const onFileChangeEdit = (e) => {
    edit_payroll.value.image = e.target.files[0];
    edit_payroll_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_payroll.value.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    }

};

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

watch(search_category, (newValue) => {
    if (newValue) {
        search_category_id.value = newValue.id;
    } else {
        search_category_id.value = null;
    }
});

watch(search_employee, (newValue) => {
    if (newValue) {
        search_employee_id.value = newValue.id;
    } else {
        search_employee_id.value = null;
    }
});

watch(search_date, (newValue) => {
    search_date.value = newValue;
});

const removeEmployee = async () => {
    try {
        select_employee.value = [];
        removeState.value = true;
        successMessage('Employee removed successfully!');

    } catch (error) {
        convertValidationError(error);
    }
};

const getCategories = async () => {
    try {
        const response = (await axios.get(route('payrolls.category.multiselect.all'))).data;
        categories.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployees = async () => {
    try {
        const response = (await axios.get(route('employee.multiselect.all'))).data;
        employees.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployeesSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('employee.multiselect.all.search', { query }));
            employeesSearch.value = response.data;
            removeState.value = false;
        } catch (error) {
            employeesSearch.value = [];
        }
    } else {
        employeesSearch.value = [];
    }
};

const savePayroll = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();


        const fileInputs = document.getElementById('payroll_image');
        if (fileInputs.files[0]) {
            const file = fileInputs.files[0];
            if (file.size > 5 * 1024 * 1024) {
                errorMessage('File size should be less than 5MB');

                const fileInput = document.getElementById('payroll_image');
                fileInput.value = null;

                nextTick(() => {
                    loading_bar.value.finish();
                });

                return;
            }
        }

        if (select_employee.value != null) {
            payroll.value.employee_id = select_employee.value.id;
        }

        if (select_currency.value != null) {
            payroll.value.currency_id = select_currency.value.id;
        }

        if (date.value != null) {
            payroll.value.date = date.value;
        }

        // set the type of the payroll as salary payment
        payroll.value.type = 1;

        const response = await axios.post(route('payroll.store'), payroll.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Payroll added successfully!');

        payroll.value = {};
        payroll_image.value = null;
        select_category.value = [];
        select_employee.value = [];

        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        viewPayroll(response.data);

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
        if (error.response && error.response.data && error.response.data.message) {
            // errorMessage(error.response.data.message);
        } else {
            errorMessage('An error occurred while processing your request.');
        }
    }
};

const viewPayroll = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('payroll.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getPayroll = async () => {
    try {
        const response = (await axios.get(route("payroll.get", page_props.payroll_id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.employee_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.employee_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }

        if (edit_payroll.value.date) {
            edit_date.value = new Date(edit_payroll.value.date);
        }
        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        const isPdf = edit_payroll.value.image_url.toLowerCase().endsWith('.pdf');
        edit_payroll.value.isPdf = isPdf ? 1 : 0;

    } catch (error) {
        convertValidationNotification(error);
    }
};

const loadData = async (id) => {
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.employee_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.employee_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }

        if (edit_payroll.value.date) {
            edit_date.value = new Date(edit_payroll.value.date);
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const downloadReceipt = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        // $refs.downloadLink.click();
        if (edit_payroll.value.image_url) {
            const link = document.createElement('a');
            link.href = edit_payroll.value.image_url;
            link.download = 'image'; // Set the desired filename for the downloaded image
            link.click();
        }
    } catch (error) {

    }
};

const selectImageRemove = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;
        payroll.value.image_url = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }

};

const deletePayroll = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;

        $('#payrollDeleteModal').modal('show');
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const response = (await axios.delete(route("payroll.delete", page_props.payroll_id))).data;
        successMessage('Payroll deleted successfully!');

        $('#payrollDeleteModal').modal('hide');
        window.location.href = route('payrolls.index');

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

const showEmployeeModal = async () => {

    resetValidationErrors();
    employeeData.value = {};
    $("#employeeModal").modal("show");

};

const saveNewEmployee = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {



        // add the type of the employee as 1
        employeeData.value.type = 1;

        const response = await axios.post(route("employee.all.store"), employeeData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New employee registration is successful');
            closeEmployeeModal();
            getEmployees();
            select_employee.value = response.data;
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

const closeEmployeeModal = async () => {
    $("#employeeModal").modal("hide");
};

const showPayrollCategoryModal = async () => {

    categoryData.value.name = "";
    categoryData.value.remarks = "";
    $("#categoryModal").modal("show");

};

const closeCategoryModal = async () => {
    $("#categoryModal").modal("hide");
    categoryData.value.stock_quantity = "";
};

const saveNewCategory = async () => {
    resetValidationErrors();
    try {


        const response = (await axios.post(route("payrolls.category.store"), categoryData.value)).data;
        if (response === "This category already exists") {
            errorMessage('This category already exists');
        } else {
            successMessage('New category registration is successful');
            closeCategoryModal();
            getCategories();
            select_category.value = response;
        }


    } catch (error) {
        convertValidationError(error);
    }
};

const editStock = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("product.get", id))).data;
        stock.value = response;

        stock_in.value = true;

        $('#stockModal').modal('show');
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
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            select_currency.value.id = business_details.value.currency_id;
            select_currency.value.code = business_details.value.currency_name;
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

const openImageFile = async () => {

    if (select_employee.value.id == null) {
        errorMessage('Please select an employee');
    } else if (payroll.value.amount == null) {
        errorMessage('Please enter payroll amount');
    } else {
        const fileInput = document.getElementById('payroll_image');
        fileInput.click();
        fileInput.addEventListener('change', savePayroll);
    }



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
    getCategories();
    getEmployees();
    getCurrency();
    getBusinessDetails();
    getPayroll();
    removeState.value = true;
});

watch(() => {
    if (stock_in.value == true) {
        stock_out.value = false;
    } else if (stock_out.value == true) {
        stock_in.value = false;
    }
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.download-button:active {
    background-color: #DBFFE4;
    color: green;
    font-weight: bold
}

.plus-btn {
    color: #1464a4;
}

.plus-btn-employee {
    color: #1464a4;
    margin-left: 6px;
}

.edit-btn {
    color: #1464a4;
}

.delete-btn {
    color: red;
}
</style>
