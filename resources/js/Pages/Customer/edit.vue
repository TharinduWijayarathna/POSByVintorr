<template>
    <AppLayout title="Customer Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Customer&nbsp;
                                </h1>
                                <!-- <a style="margin-right: 100%;"
                                    href="https://www.youtube.com/watch?v=X8QwQrXa2Go&list=PL5kz-kd2_LQu2PxJtC3OMeT1r3AFAqh-O&index=9"
                                    target="_blank"><i class="bi bi-question-circle fs-2" style="color:#BFC9CA;"></i></a> -->
                            </div>
                        </div>
                        <div class="shadow card">
                            <div class="px-0 py-3 text-sm card-body px-md-8">
                                <div class="px-5 pt-5 pb-5 card-body px-md-8">
                                    <div class="row">

                                        <!-- Desktop View Header -->
                                        <div class="d-none col-md-8 d-md-block">
                                            <div class="mb-2 d-flex align-items-center">
                                                <a href="#"
                                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{
                                                        truncateText(selectedCustomerData.name) }}</a>
                                                <!-- <label v-if="selectedCustomerData.deleted_at == null"><i
                                                                class="bi bi-check-circle fs-1 fw-bold ms-2"
                                                                style="color: rgb(25, 198, 25);;"></i></label> -->
                                            </div>
                                        </div>

                                        <div class="d-none col-md-4 d-md-block text-end">
                                            <a href="#"
                                                v-if="selectedCustomerData.email3 || selectedCustomerData.email2 || selectedCustomerData.email"
                                                @click.prevent="showCustomerEmailModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Email to customer"
                                                class="btn btn-sm btn-warning me-3 px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Mail</a>
                                            <span v-else data-toggle="tooltip" data-placement="bottom"
                                                title="First add a Email">
                                                <a href="#" class="btn btn-sm btn-warning me-3 px-7 disabled">Mail</a>
                                            </span>

                                            <a href="#" @click.prevent="showCustomerModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Edit customer"
                                                class="btn btn-sm btn-primary me-3 px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Edit</a>
                                        </div>

                                        <!-- Mobile View Header -->
                                        <div class="mb-4 col-12 d-block d-md-none d-md-block text-end pe-0">
                                            <a href="#"
                                                v-if="selectedCustomerData.email3 || selectedCustomerData.email2 || selectedCustomerData.email"
                                                @click.prevent="showCustomerEmailModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Email to customer"
                                                class="btn btn-sm btn-warning me-3 px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Mail</a>
                                            <span v-else data-toggle="tooltip" data-placement="bottom"
                                                title="First add a Email">
                                                <a class="btn btn-sm btn-warning me-3 px-7 disabled">Mail</a>
                                            </span>

                                            <a href="#" @click.prevent="showCustomerModal" data-toggle="tooltip"
                                                data-placement="bottom" title="Edit customer"
                                                class="btn btn-sm btn-primary me-3 px-7" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_offer_a_deal">Edit</a>
                                        </div>

                                        <div class="mb-4 col-12 d-block d-md-none d-md-block">
                                            <div class="mb-2 align-items-center">
                                                <a href="#"
                                                    class="text-gray-900 text-wrap text-hover-primary fs-2 fw-bold me-1">{{
                                                        truncateText(selectedCustomerData.name) }}</a>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-geo-alt text-primary fs-2 me-4"></i>
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="text-gray-500">Address</div>
                                                            <div v-if="selectedCustomerData.address"
                                                                class="mt-1 text-gray-700">
                                                                {{ selectedCustomerData.address }}
                                                            </div>
                                                            <div v-else class="mt-1 text-gray-700">No Address</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 ">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-globe2 text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="text-gray-500">Website</div>
                                                            <div v-if="selectedCustomerData.website"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                :title="selectedCustomerData.website"
                                                                class="mt-1 text-gray-700 two-line-truncate">
                                                                <a class="text-gray-700"
                                                                    :href="selectedCustomerData.website"
                                                                    target="_blank">{{
                                                                        selectedCustomerData.website }}</a>
                                                            </div>
                                                            <div v-else class="mt-1 text-gray-700">No Website</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-telephone text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8" v-if="selectedCustomerData.contact">
                                                            <div class="text-gray-500">Contact</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.contact">
                                                                {{ selectedCustomerData.contact }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.contact2">
                                                                {{ selectedCustomerData.contact2 }}
                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.contact3">
                                                                {{ selectedCustomerData.contact3 }}
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
                                        <div class="col-md-5">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1"><i
                                                                class="bi bi-envelope text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8" v-if="selectedCustomerData.email">
                                                            <div class="text-gray-500">Email</div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.email">
                                                                {{ selectedCustomerData.email }}

                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.email2">
                                                                {{ selectedCustomerData.email2 }}

                                                            </div>
                                                            <div class="mt-2 text-gray-700"
                                                                v-if="selectedCustomerData.email3">
                                                                {{ selectedCustomerData.email3 }}
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
                                        <div class="col-md-2">

                                        </div>

                                        <div class="col-md-5">
                                            <div class="mb-4 fs-6 pe-2">
                                                <div class="mb-2 text-gray-500 me-5">
                                                    <div class="row">
                                                        <div class="mt-2 col-1">
                                                            <i class="bi bi-wallet2 text-primary fs-2 me-4"></i>
                                                        </div>
                                                        <div class="col-8">
                                                            <div v-if="dueData.length > 0" class="text-gray-500">Has
                                                                due of</div>
                                                            <div v-else class="text-gray-500">No any due</div>
                                                            <div class="mt-1 text-gray-700">
                                                                <div class="mt-1 text-gray-700" id="customer_due">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 shadow card">
                            <div class="px-0 text-sm py-md-3 card-body px-md-8">

                                <!-- mobile view -->
                                <div class="d-block d-md-none row">
                                    <div class="col-12 pe-0">
                                        <div class="row nav-stretch nav-line-tabs fs-5 fw-bold ps-5">
                                            <div class="col-4 nav-item"> <a
                                                    class="nav-link text-active-primary ms-0 me-0" data-toggle="tooltip"
                                                    data-placement="bottom" title="Show customer invoices"
                                                    :class="[showInvoices == 1 ? 'active' : '']" href="#invoice-data"
                                                    @click="showInvoiceTab">
                                                    Invoices </a></div>
                                            <div class="text-center col-4 nav-item"><a
                                                    class="nav-link text-active-primary ms-0 me-0" data-toggle="tooltip"
                                                    data-placement="bottom" title="Show customer bills"
                                                    :class="[showBills == 1 ? 'active' : '']" href="#pos-bill-data"
                                                    @click="showBillTab">
                                                    POS Bills </a></div>
                                            <div class="col-4 nav-item text-end"><a
                                                    class="nav-link text-active-primary ms-0 me-0" data-toggle="tooltip"
                                                    data-placement="bottom" title="Show customer quotation"
                                                    :class="[showQuotations == 1 ? 'active' : '']"
                                                    href="#quotation-data" @click="showQuotationTab">
                                                    Quotations </a></div>
                                        </div>
                                    </div>

                                </div>

                                <!-- desktop view -->
                                <div class="card-header d-none d-sm-flex d-md-flex card-header-width">

                                    <ul
                                        class="border-transparent nav nav-stretch nav-line-tabs nav-line-tabs-2x fs-5 fw-bold">

                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-10"
                                                data-toggle="tooltip" data-placement="bottom"
                                                title="Show customer invoices"
                                                :class="[showInvoices == 1 ? 'active' : '']" href="#invoice-data"
                                                @click="showInvoiceTab">
                                                Invoices </a>
                                        </li>

                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-10 "
                                                data-toggle="tooltip" data-placement="bottom"
                                                title="Show customer bills" :class="[showBills == 1 ? 'active' : '']"
                                                href="#pos-bill-data" @click="showBillTab">
                                                POS Bills </a>
                                        </li>

                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-10 "
                                                data-toggle="tooltip" data-placement="bottom"
                                                title="Show customer quotation"
                                                :class="[showQuotations == 1 ? 'active' : '']" href="#quotation-data"
                                                @click="showQuotationTab">
                                                Quotations </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="px-5 py-5 card-body px-md-8">

                                    <div class="row" :class="[showInvoices == 1 ? 'd-block' : 'd-none']"
                                        id="invoice-data">
                                        <InvoiceData :customerId="page_props.customer_id" />
                                    </div>
                                    <div class="row" :class="[showBills == 1 ? 'd-block' : 'd-none']"
                                        id="pos-bill-data">
                                        <BillData :customerId="page_props.customer_id" />
                                    </div>
                                    <div class="row" :class="[showQuotations == 1 ? 'd-block' : 'd-none']"
                                        id="quotation-data">
                                        <QuotationData :customerId="page_props.customer_id" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #modal>
            <!-- Edit Customer Modal -->
            <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">

                                <div class="mb-6 col-8">
                                    <h5 class="modal-title" style="color: #071437">Edit
                                        Customer
                                    </h5>
                                </div>
                                <div class="mb-6 col-4 text-end">
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"
                                        @click="closeCustomerModal"></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Customer</lable>
                                    <input v-model="customerData.name" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Customer Name" />
                                    <small v-if="validationErrors.name"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.name }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Address</lable>
                                    <input v-model="customerData.address" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                    <small v-if="validationErrors.address"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.address }}</small>
                                </div>

                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Email 1</lable>
                                    <input v-model="customerData.email" type="email"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                    <small v-if="validationErrors.email"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.email }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Email 2</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="customerData.email2" type="email"
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
                                            <input v-model="customerData.email3" type="email"
                                                class="mt-1 form-control form-control-sm" placeholder="Enter Email 3" />
                                            <small v-if="validationErrors.email3"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email3 }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Phone No.1</lable>
                                    <input v-model="customerData.contact" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Phone Number1" />
                                    <small v-if="validationErrors.contact"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.contact }}</small>
                                </div>
                                <div class="mb-4 col-12">
                                    <lable class="text-gray-600">Phone No.2</lable>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="customerData.contact2" type="text"
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
                                            <input v-model="customerData.contact3" type="text"
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
                                    <input v-model="customerData.website" type="text"
                                        class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                    <small v-if="validationErrors.website"
                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                        {{ validationErrors.website }}</small>
                                </div>
                                <!-- <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Open Outstanding</lable>
                                        <input v-model="customerData.customer_outstanding" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Open Outstanding" />
                                    </div> -->
                            </div>
                            <div class="row">
                                <div class="mt-4 col-12 text-end">
                                    <button v-if="selectedCustomerData.id" type="button" class="btn btn-light-info"
                                        data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                        style="font-weight: bold"
                                        @click.prevent="updateCustomer(selectedCustomerData.id)">
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
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeDeleteModal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this customer?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-dark" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-dismiss="modal"
                                @click="closeDeleteModal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete customer"
                                @click.prevent="deleteCustomer(selectedCustomerId)">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mail Modal -->
            <div class="modal fade" id="send_mail" tabindex="-1" data-bs-backdrop="static"
                aria-labelledby="addNewCardTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="pb-4 pl-4 pr-4 modal-content">
                        <div class="pb-5 modal-body px-sm-5 mx-50">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h1 class="modal-title" style="color: #071437">Outstanding report</h1>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form id="addNewCardValidation" class="row gy-1 gx-2 mt-75"
                                @submit.prevent="sendOutstandingEmail()">
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Send To</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email" class="form-control emailinput" id="to"
                                            v-model="customer_email.to" placeholder="Enter Email Address" />
                                    </div>
                                    <div v-if="validationErrors.to" class="invalid-feedback-msg">
                                        validation error
                                    </div>
                                </div>
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Send CC</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email" id="cc" class="form-control emailinput"
                                            v-model="customer_email.cc" placeholder="Enter Email Addresses" />
                                    </div>
                                    <div id='output'></div>
                                </div>
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Subject</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" v-model="customer_email.subject"
                                            placeholder="Enter Email Subject" />
                                    </div>
                                </div>
                                <div class="mt-4 col-12">
                                    <div class="mb-2">
                                        <label class="form-label line-height-3" for="modalAddCardNumber">
                                            Message
                                        </label>
                                        <QuillEditor v-model="customer_email.message" :toolbar="toolbarOptions"
                                            id="email-message" @keyup="setInvoiceNote" :options="quillOptions"
                                            placeholder="Note">
                                        </QuillEditor>
                                    </div>
                                </div>
                                <div class="mt-4 text-right col-12">
                                    <button type="button" class="mr-2 btn btn-light-darkr fw-bold" data-toggle="tooltip"
                                        data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                        CANCEL
                                    </button>
                                    <button type="submit" class="mt-1 btn btn-info fw-bold" data-toggle="tooltip"
                                        data-placement="bottom" title="Send customer outstanding report email">
                                        <i class="bi bi-send"></i>
                                        SEND
                                    </button>
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
import { ref, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import moment from 'moment';
import { usePage } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill';

import InvoiceData from '@/Pages/Customer/Components/Invoice/All.vue';
import QuotationData from '@/Pages/Customer/Components/Quotation/All.vue';
import BillData from '@/Pages/Customer/Components/PosBill/All.vue';


const { props } = usePage();
const page_props = props;
const pagination = ref({});
const invoices = ref([]);
const dueData = ref([]);
const customers = ref([]);
const selectedCustomerData = ref({});
const emailIndex = ref(1);
const phoneIndex = ref(1);
const showInvoices = ref(1);
const showBills = ref(0);
const showQuotations = ref(0);

// Filter variables
const nameFilter = ref("");
const contactFilter = ref("");

const validationErrors = ref({});
const validationMessage = ref(null);

const customerData = ref({});
const selectedCustomerId = ref(null);

const loading_bar = ref(null);
const customer_email = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY HH:mm:ss');
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



// Clear filters Method
function clearFilters() {
    nameFilter.value = "";
    contactFilter.value = "";
    getCustomers();
}

function showInvoiceTab() {
    showInvoices.value = 1;
    showBills.value = 0;
    showQuotations.value = 0;
}

function showQuotationTab() {
    showQuotations.value = 1;
    showInvoices.value = 0;
    showBills.value = 0;
}

function showBillTab() {
    showBills.value = 1;
    showInvoices.value = 0;
    showQuotations.value = 0;
}

// Show Add/Edit Customer Modal
const showCustomerModal = async () => {
    resetValidationErrors();
    if (selectedCustomerData.value) {
        getCustomerData();
        customerData.value = selectedCustomerData.value;
        $("#customerModal").modal("show");
    }
};

// Show Add/Edit Customer Email Modal
const showCustomerEmailModal = async () => {
    resetValidationErrors();
    getCustomerData();
    $("#send_mail").modal("show");
};

const getCustomerData = async () => {
    const customer = (await axios.get(route('customer.get', page_props.customer_id))).data;
    selectedCustomerData.value = customer;

    setCustomerEmailDetails(selectedCustomerData);
};

const setCustomerEmailDetails = async (selectedCustomerData) => {
    try {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric', };
        const formattedDate = today.toLocaleDateString('en-US', options).replace(',', '').split(' ').join('-');

        const configuration_details = (await axios.get(route("configuration.get"))).data.data;
        customer_email.value.to = selectedCustomerData.value.email;
        customer_email.value.cc = configuration_details.email;
        customer_email.value.subject = 'outstanding mail up to ' + formattedDate;

        customer_email.value.message =
            "<p>Hi " + selectedCustomerData.value.name +
            ",</p><p>I hope you’re doing well! Please see the outstanding payment report below " +
            ".</p><p>Don’t hesitate to reach out if you have any questions.</p><p>Kind regards!</p>";

        const editorContainer = document.querySelector('#email-message .ql-editor');
        editorContainer.innerHTML = customer_email.value.message;
    } catch (error) {
        errorMessage('failed to set customer data');
    }
};

const sendOutstandingEmail = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();
        const editorContainer = document.querySelector('#email-message .ql-editor');
        customer_email.value.message = editorContainer.innerHTML;
        const response = await axios.post(route("customer.outstanding_mail.send", selectedCustomerData.value.id), customer_email.value);
        successMessage('Email send successfully');
        closeCustomerEmailModal();
        getCustomerData();
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

const closeCustomerEmailModal = async () => {
    resetValidationErrors();
    getCustomerData();
    $("#send_mail").modal("hide");
};

// Show Delete Confirmation Modal
function showDeleteModal(customerId) {
    selectedCustomerId.value = customerId;
    $("#deleteModal").modal("show");
}

// Delete Customer (Delete Confirmation Modal)
function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

// Close Add/Edit, Customer (Close Modal)
const closeCustomerModal = async () => {
    $("#customerModal").modal("hide");
    // $("#deleteModal").modal("hide");
};

const clearContact3 = async () => {
    customerData.value.contact3 = "";
};
const clearContact2 = async () => {
    customerData.value.contact2 = "";
};
const clearEmail2 = async () => {
    customerData.value.email2 = "";
};
const clearEmail3 = async () => {
    customerData.value.email3 = "";
};

const updateCustomer = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();
        await axios.post(route('customer.update', id), customerData.value);
        successMessage('Customer successfully updated');
        closeCustomerModal();
        getCustomerData();
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
        const tableData = (await axios.get(route('customer.invoice.all', page_props.customer_id))).data;
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
        const tableData = (await axios.get(route('customer.due', page_props.customer_id))).data;
        dueData.value = tableData;

        const customerDueSpan = document.getElementById('customer_due');
        let formattedAmounts = '';

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
        return text.length > 50 ? text.substring(0, 50) + '...' : text;
    }
    return '';
};

const toolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline'], // toggled buttons
];

onMounted(() => {
    getCustomers();
    getCustomerData();
    getCustomerInvoices();
    getDueAmounts();
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.cancel-btn {
    background-color: rgba(128, 128, 128, 0.192);
    color: black;
}

.nav-item {
    height: 55px;
}

.table-inside {
    height: 60px;
}

.card-header-width {
    min-height: 60px;
}
</style>
