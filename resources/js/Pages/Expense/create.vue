<template>
    <AppLayout title="Create Expenses">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div
                                class="pb-0 mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center pb-sm-3">

                                <h1 class="main-header-text">
                                    Create New Expense
                                </h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="py-0 mb-5 col-12 col-md-6 mb-md-0 mb-xl-0">

<div class="shadow card h-100">
    <div class="p-4 py-0 text-sm card-body p-md-7 py-md-3">

        <form @submit.prevent="saveExpense" enctype="multipart/form-data">
            <div class="mt-3 row">
                <div class="col-md-12">
                    <div class="text-gray-600 col-form-label">Receipt Image</div>
                </div>
                <div class="pt-2 col-md-12 d-flex justify-content-center">

                    <!-- Desktop View -->
                    <div class="p-2 image-input image-chooser-border d-none d-md-flex"
                        :class="{ 'drag-over': isDragOver }"
                        @dragover.prevent="handleDragOver"
                        @drop.prevent="handleFileDrop"
                        @dragleave.prevent="handleDragLeave">
                        <div class="image-input-wrapper w-400px h-400px">
                            <template v-if="expense.isPdf == 1">
                                <iframe :src="expense.image_url" width="100%"
                                    height="500px"></iframe>
                            </template>
                            <template v-else>
                                <img v-if="expense.image_url && edit_expense.isPdf == 0"
                                    :src="expense.image_url"
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
                            <i class="bi bi-pencil-square text-dark-fill fs-3 edit-btn"></i>

                        </label>
                        <input type="file" hidden ref="fileInput"
                            accept="image/jpg, image/png, application/pdf"
                            id="expense_image" name="avatar" @change="onFileChange">
                        <input type="hidden" name="avatar_remove">
                        <span v-if="expense.image_url"
                            @click.prevent="selectImageRemove"
                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body d-none"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                            aria-label="Change avatar"
                            data-bs-original-title="Change avatar"
                            data-kt-initialized="1">
                            <i class="bi bi-x-lg delete-btn"></i> </span>
                    </div>

                    <!-- Mobile view -->
                    <div class="p-2 image-input image-chooser-border-mobile d-block d-md-none"
                        :class="{ 'drag-over': isDragOver }"
                        @dragover.prevent="handleDragOver"
                        @drop.prevent="handleFileDrop"
                        @dragleave.prevent="handleDragLeave">
                        <div class="image-input-wrapper-mobile">
                            <template v-if="expense.isPdf == 1">
                                <iframe :src="expense.image_url" width="100%"
                                    height="500px"></iframe>
                            </template>
                            <template v-else>
                                <img v-if="expense.image_url && edit_expense.isPdf == 0"
                                    :src="expense.image_url"
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
                            <i class="bi bi-pencil-square text-dark-fill fs-3 edit-btn"></i>

                        </label>
                        <input type="file" hidden ref="fileInput"
                            accept="image/jpg, image/png, application/pdf"
                            id="expense_image" name="avatar" @change="onFileChange">
                        <input type="hidden" name="avatar_remove">
                        <span v-if="expense.image_url"
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
                            <div class="py-0 col-12 col-md-6">
                                <div class="shadow card h-100">
                                    <div class="p-4 py-1 text-sm card-body p-md-7 py-md-7">
                                        <form @submit.prevent="saveExpense" enctype="multipart/form-data">
                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">
                                                        Category
                                                        <i class="m-auto cursor-pointer bi bi-plus-lg-fill fs-5 plus-btn"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new category"
                                                            @click.prevent="showExpenseCategoryModal"></i>
                                                    </div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <Multiselect v-model="select_category" :options="categories"
                                                        class="z__index" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Category" label="name" track-by="id"
                                                        max-height="200" />
                                                    <small v-if="validationErrors.expense_category_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.expense_category_id }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">
                                                        Supplier
                                                        <i class="cursor-pointer bi bi-plus-lg-fill fs-5 plus-btn-supplier"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new supplier"
                                                            @click.prevent="showSupplierModal"></i>
                                                    </div>
                                                </div>
                                                <div class="pt-2 col-md-9 d-flex multiselect-wrapper">
                                                    <Multiselect v-model="select_supplier" :options="suppliersSearch.map(supplier => ({
                                                        ...supplier,
                                                        company: truncateSupplierText(supplier.company),
                                                        searchableText: supplier.company ? `${supplier.name} - ${truncateSupplierText(supplier.company)}` : supplier.name
                                                    }))" class="z__index" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        :internal-search="false" placeholder="Select Supplier"
                                                        label="searchableText" track-by="id" max-height="200"
                                                        @search-change="getSuppliersSearch">
                                                        <template #noOptions>
                                                            <div>Press a key to select Supplier</div>
                                                        </template>
                                                        <template #noResult>
                                                            <div>No matching suppliers found</div>
                                                        </template>
                                                        <template #option="{ option }">
                                                            {{ option.company ? `${option.name} - ${option.company}` :
                                                                option.name
                                                            }}
                                                        </template>
                                                    </Multiselect>
                                                    <small v-if="validationErrors.supplier_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.supplier_id }}</small>
                                                    <button v-if="removeState == false" type="button"
                                                        class="supplier-remove-btn mr-9" data-bs-toggle="dropdown"
                                                        data-kt-menu-placement="bottom-end"
                                                        @click.prevent="removeSupplier">
                                                        <i class="bi bi-x-lg fs-3 text-danger supplier-remove-icon"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Date</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input type="date" v-model="date"
                                                        class="form-control form-control-sm" />
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
                                                    <input v-model="expense.amount" type="number"
                                                        class="form-control form-control-sm" placeholder="Amount" />
                                                    <small v-if="validationErrors.amount"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.amount }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Remark</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input v-model="expense.remark" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Enter expense remark" />
                                                    <small v-if="validationErrors.remark"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.remark }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Note</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <textarea v-model="expense.description" class="form-control"
                                                        placeholder="Expense Note" data-kt-autosize="true"
                                                        style="resize: none; font-size: 12px;" rows="4"></textarea>
                                                    <small v-if="validationErrors.description"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.description }}</small>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="mt-6 mb-6 col-12 text-end align-items-end mb-md-0 mt-md-15">
                                                    <button type="submit" class="btn btn-info ms-2"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Add expense" style="font-weight: bold">
                                                        CREATE
                                                    </button>
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
            <div class="modal fade" id="expenseDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this expense?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete"
                                @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Supplier Modal -->
            <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewSupplier" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title" style="color: #071437">Add New Supplier</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            data-toggle="tooltip" data-placement="bottom" title="Close"
                                            @click="closeSupplierModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Name</lable>
                                        <input v-model="supplierData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Supplier Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Company</lable>
                                        <input v-model="supplierData.company" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Company Name" />
                                        <small v-if="validationErrors.company"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.company }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="supplierData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
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
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add new email address"
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
                                                <input v-model="supplierData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier email address"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another email address"
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
                                                <input v-model="supplierData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier email address"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone No.1</lable>
                                        <input v-model="supplierData.contact" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Phone Number1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another phone number"
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
                                                <input v-model="supplierData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier phone number"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
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
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="supplierData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear supplier phone number"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Website</lable>
                                        <input v-model="supplierData.website" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" data-toggle="tooltip"
                                            data-placement="bottom" title="Add new supplier" style="font-weight: bold">
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
                                        data-toggle="tooltip" data-placement="bottom" title="Close"
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
                                        <button type="submit" class="mt-2 btn btn-info" data-toggle="tooltip"
                                            data-placement="bottom" title="Add new category" style="font-weight: bold">
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
const supplierData = ref({});

const categories = ref([]);
const suppliers = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_edit_category = ref({});
const select_supplier = ref([]);
const edit_select_supplier = ref([]);
const select_edit_unit = ref({});
const suppliersSearch = ref([]);

const product = ref({});
const expense = ref({});
const edit_expense = ref({});

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_category = ref(null);
const search_category_id = ref(null);
const search_supplier = ref(null);
const search_supplier_id = ref(null);
const search_date = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const loading_bar = ref(null);

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

const expense_image = ref(null);
const edit_expense_image = ref(null);
const categoryData = ref({});
const removeState = ref(false);



const isDragOver = ref(false);

const onFileChange = (e) => {

    const fileInputs = document.getElementById('expense_image');
    if (fileInputs.files[0]) {
        const file = fileInputs.files[0];
        if (file.size < 5 * 1024 * 1024) {
            expense.value.image = e.target.files[0];
            expense_image.value = e.target.files[0];

            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    expense.value.image_url = e.target.result;
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
        expense.value.image = file;
        expense_image.value = file;
        saveExpense();
        const reader = new FileReader();
        reader.onload = (event) => {
            expense.value.image_url = event.target.result;
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
    edit_expense.value.image = e.target.files[0];
    edit_expense_image.value = e.target.files[0];

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_expense.value.image_url = e.target.result;
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

watch(search_supplier, (newValue) => {
    if (newValue) {
        search_supplier_id.value = newValue.id;
    } else {
        search_supplier_id.value = null;
    }
});

watch(search_date, (newValue) => {
    search_date.value = newValue;
});

const removeSupplier = async () => {
    try {
        select_supplier.value = [];
        removeState.value = true;
        successMessage('Supplier removed successfully!');

    } catch (error) {
        convertValidationError(error);
    }
};

const getCategories = async () => {
    try {
        const response = (await axios.get(route('expenses.category.multiselect.all'))).data;
        categories.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const truncateSupplierText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const getSuppliers = async () => {
    try {
        const response = (await axios.get(route('supplier.multiselect.all'))).data;
        suppliers.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getSuppliersSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('supplier.multiselect.all.search', { query }));
            suppliersSearch.value = response.data;
            removeState.value = false;
        } catch (error) {
            suppliersSearch.value = [];
        }
    } else {
        suppliersSearch.value = [];
    }
};

const saveExpense = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();


        const fileInputs = document.getElementById('expense_image');
        if (fileInputs.files[0]) {
            const file = fileInputs.files[0];
            if (file.size > 5 * 1024 * 1024) {
                errorMessage('File size should be less than 5MB');

                const fileInput = document.getElementById('expense_image');
                fileInput.value = null;

                nextTick(() => {
                    loading_bar.value.finish();
                });

                return;
            }
        }

        if (select_category.value != null) {
            expense.value.expense_category_id = select_category.value.id;
        }

        if (select_supplier.value != null) {
            expense.value.supplier_id = select_supplier.value.id;
        }

        if (select_currency.value != null) {
            expense.value.currency_id = select_currency.value.id;
        }

        if (date.value != null) {
            expense.value.date = date.value;
        }

        const response = await axios.post(route('expense.store'), expense.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Expense added successfully!');

        expense.value = {};
        expense_image.value = null;
        select_category.value = [];
        select_supplier.value = [];

        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;

        viewExpense(response.data);

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

const viewExpense = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        window.location.href = route('expense.load', id);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getExpense = async () => {
    try {
        const response = (await axios.get(route("expense.get", page_props.expense_id))).data;
        edit_expense.value = response;

        if (edit_expense.value.expense_category_id) {
            edit_select_category.value.id = edit_expense.value.expense_category_id;
            edit_select_category.value.name = edit_expense.value.category_name;
        }

        if (edit_expense.value.supplier_id) {
            const supplier = (await axios.get(route('supplier.get', edit_expense.value.supplier_id))).data
            edit_select_supplier.value = supplier;
        }

        if (edit_expense.value.currency_id) {
            edit_select_currency.value.id = edit_expense.value.currency_id;
            edit_select_currency.value.code = edit_expense.value.currency_name;
        }

        if (edit_expense.value.date) {
            edit_date.value = new Date(edit_expense.value.date);
        }
        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;

        const isPdf = edit_expense.value.image_url.toLowerCase().endsWith('.pdf');
        edit_expense.value.isPdf = isPdf ? 1 : 0;

    } catch (error) {
        convertValidationNotification(error);
    }
};

const loadData = async (id) => {
    try {
        const response = (await axios.get(route("expense.get", id))).data;
        edit_expense.value = response;

        if (edit_expense.value.expense_category_id) {
            edit_select_category.value.id = edit_expense.value.expense_category_id;
            edit_select_category.value.name = edit_expense.value.category_name;
        }

        if (edit_expense.value.supplier_id) {
            const supplier = (await axios.get(route('supplier.get', edit_expense.value.supplier_id))).data
            edit_select_supplier.value = supplier;
        }

        if (edit_expense.value.currency_id) {
            edit_select_currency.value.id = edit_expense.value.currency_id;
            edit_select_currency.value.code = edit_expense.value.currency_name;
        }

        if (edit_expense.value.date) {
            edit_date.value = new Date(edit_expense.value.date);
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
        if (edit_expense.value.image_url) {
            const link = document.createElement('a');
            link.href = edit_expense.value.image_url;
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
        const fileInput = document.getElementById('expense_image');
        fileInput.value = null;
        expense.value.image_url = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }

};

const deleteExpense = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("expense.get", id))).data;
        edit_expense.value = response;

        $('#expenseDeleteModal').modal('show');
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


        const response = (await axios.delete(route("expense.delete", page_props.expense_id))).data;
        successMessage('Expense deleted successfully!');

        $('#expenseDeleteModal').modal('hide');
        window.location.href = route('expenses.index');

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

const showSupplierModal = async () => {

    resetValidationErrors();
    supplierData.value = {};
    $("#supplierModal").modal("show");

};

const saveNewSupplier = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const response = await axios.post(route("supplier.all.store"), supplierData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New supplier registration is successful');
            closeSupplierModal();
            getSuppliers();
            suppliersSearch.value.push(response.data);
            select_supplier.value = [{
                ...response.data,
                company: truncateSupplierText(response.data.company),
                searchableText: response.data.company ? `${response.data.name} - ${truncateSupplierText(response.data.company)}` : response.data.name
            }];
            select_supplier.value = select_supplier.value[0];
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

const closeSupplierModal = async () => {
    $("#supplierModal").modal("hide");
};

const showExpenseCategoryModal = async () => {

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


        const response = (await axios.post(route("expenses.category.store"), categoryData.value)).data;
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
        const response = (await axios.get(route("configuration.get"))).data.data;
        business_details.value = response;

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

    if (select_category.value.id == null) {
        errorMessage('Please select an expense category');
    } else if (expense.value.amount == null) {
        errorMessage('Please enter expense amount');
    } else {
        const fileInput = document.getElementById('expense_image');
        fileInput.click();
        fileInput.addEventListener('change', saveExpense);
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
    getSuppliers();
    getCurrency();
    getBusinessDetails();
    getExpense();
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

.plus-btn-supplier {
    color: #1464a4;
    margin-left: 6px;
}

.edit-btn {
    color: #1464a4;
}

.delete-btn {
    color: red;
}

@media (max-width: 768px) {
    .image-chooser-border {
        border: none;
    }
}
</style>
