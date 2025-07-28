<template>
    <AppLayout title="Credit Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                Credit Bill Management
                            </h1>
                        </div>

                        <div class="shadow card mb-9">
                            <div class="p-4 text-sm card-body pt-md-2 pb-md-2 p-md-8">

                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <!-- <th class="ps-3">CUSTOMER TYPE</th> -->
                                                    <th class="ps-3">CUSTOMER NAME</th>
                                                    <th class="ps-3">NO</th>
                                                    <th class="text-end">ORDER AMOUNT</th>
                                                    <th class="text-end pe-3">PAID AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr>
                                                    <!-- <td class="py-2 ps-3">{{ order.customer_type }}</td> -->
                                                    <td class="py-2 ps-3">{{ truncateText(order.customer_name) }}</td>
                                                    <td class="py-2 ps-3">{{ order.code }}</td>
                                                    <td class="py-2 text-end">{{ order.formatted_total }}</td>
                                                    <td class="py-2 text-end pe-3">{{ order.formatted_customer_paid }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="shadow card">
                            <div class="p-4 text-sm card-body pt-md-2 pb-md-3 p-md-8">

                                <div class="row">
                                    <div class="mt-3 col-12 col-md-6">
                                        <span class="text-gray-600 pe-5 fs-4">PAYMENTS</span>
                                        <button v-if="order.total - order.customer_paid > 0" data-toggle="tooltip"
                                            data-placement="bottom" title="Add payment" class="py-2 btn btn-info btn-sm"
                                            style="font-weight: bold" @click="paymentModal">
                                            <i class="bi bi-wallet2"></i> ADD PAYMENT
                                        </button>
                                    </div>
                                    <div class="mt-3 col-12 col-md-6 text-end">
                                        <span class="ps-2 pe-2 fs-4 text-danger">DUE PAYMENT : {{ (order.total -
                                            order.customer_paid).toLocaleString('en-US', {
                                                minimumFractionDigits:
                                                    2
                                            }) }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">BILL NO.</th>
                                                    <th>DATE</th>
                                                    <th>REMARK</th>
                                                    <th class="text-end pe-3">AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="(bill, index) in bills" :key="index"
                                                    onmouseover="this.style.backgroundColor='#F2F4F4'; this.style.cursor='pointer';"
                                                    onmouseout="this.style.backgroundColor='#ffffff';">
                                                    <td class="py-2 ps-3">{{ bill.code }}</td>
                                                    <td class="py-2">{{ bill.date }}</td>
                                                    <td class="py-2">{{ bill.remark }}</td>
                                                    <td class="py-2 text-end pe-3">{{
                                                        numberFormatter(bill.accepted_amount) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-gray-700 text-end fs-4">Total:</td>
                                                    <td class="text-gray-700 text-end fs-4 pe-3">{{
                                                        order.formatted_customer_paid }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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

    <!-- Add Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #071437">PAYMENT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                </div>
                <form @submit.prevent="savePayment" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="text-gray-600 col-form-label">Date</div>
                        <Datepicker v-model="payment_date" class="form-control form-control-sm"
                            :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                        <div class="text-gray-600 col-form-label">Amount</div>
                        <input v-model="payment.balance" type="text" class="form-control form-control-sm"
                            placeholder="Enter paid amount" />
                        <div class="mt-2 text-gray-600 col-form-label">Remark</div>
                        <textarea v-model="payment.remark" class="form-control" placeholder="Enter payment remark"
                            data-kt-autosize="true" style="resize: none; font-size: 12px;" rows="2"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="mt-2 btn btn-info me-2" style="font-weight: bold"
                            data-toggle="tooltip" data-placement="bottom" title="Add payment">
                            ADD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, nextTick } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { usePage } from '@inertiajs/vue3'
import Loader from '@/Components/Basic/LoadingBar.vue';
import Datepicker from 'vue3-datepicker'

const { props } = usePage();
const order = props.order;
const page_props = props;

const bills = ref([]);

const payment = ref({});
const placeholderText = 'DD/MM/YYYY';
const payment_date = ref(new Date());

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

const getBills = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route('credit.bill.all', order.id))).data;
        bills.value = tableData;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const savePayment = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (payment_date.value) {
            payment.value.date = payment_date.value;
        }
        const response = (await axios.post(route("payment.credit.bill", order.id), payment.value)).data;
        successMessage('Payment successfully!');

        $('#paymentModal').modal('hide');
        // window.location.reload();
        window.location.href = route("credit.edit", order.id);
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

const paymentModal = () => {
    if (page_props.auth.user.user_role_id != 3) {
        payment.value = {};
        $('#paymentModal').modal('show');
    } else {
        errorMessage('Access denied');
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

const numberFormatter = (number) => {
    if (number == undefined || number == null || number == Infinity) {
        return "0.00";
    }
    const parsedNumber = Number(number);
    if (isNaN(parsedNumber)) {
        return "0.00";
    }
    if (typeof parsedNumber !== "number") {
        return "0.00";
    }
    return parsedNumber.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
    });
};

onMounted(() => {
    getBills();
});

</script>

<style lang="scss" scoped></style>
