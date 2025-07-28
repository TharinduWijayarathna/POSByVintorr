<template>
    <div class="row">
        <!-- <div class="col-none col-md-2"></div> -->
        <div class="col-12 col-lg-8 ms-md-6">
            <div class="pb-5 mx-0 mt-1 d-flex row">
                <div class="px-0 col-md-6 px-md-3">
                    <div class="alert-header h-100">
                        <span class="fs-3x">⚠️</span><br />
                        <b class="fs-6">
                            Resetting your account will delete all of your invoices, quotations, products,
                            receipts, customers, suppliers, transactions, purchaser orders, and inventory logs.
                        </b>
                        <p style="font-weight: bold; text-align: center;" class="fs-3">
                            This action cannot be reversed.
                        </p>
                        <div class="reset">
                            <button class="btn btn-danger" @click.prevent="sendTokenEmail">Reset Account</button>
                        </div>
                    </div>

                    <!-- <br> -->
                    <!-- <div class="mb-3 reset">
                        <button class="btn btn-primary" @click.prevent="sendTokenEmail">Reset Account</button>
                    </div> -->
                </div>
                <div class="mt-5 col-md-6 mt-md-0 px-md-3" v-if="showEnterToken">
                    <div class="row alert-confirm-header h-100 align-content-between ">
                        <!-- <div class="w-100"> -->
                        <div class="col-12">
                            <b class="fs-6">Reset token sent successfully! Check your email.</b>
                        </div>
                        <!-- <br/> -->
                        <div class="col-12 align-items-end">
                            <form @submit.prevent="resetConfirm" enctype="multipart/form-data">
                                <label for="resetToken" class="form-label">Enter your Reset Token</label>
                                <div class="input-group ">
                                    <input type="text" class="rounded form-control red-border token-input"
                                        v-model="resetToken" id="resetToken">&nbsp;

                                </div>
                                <div class="mt-5 center-align"> <button class="rounded btn btn-danger"
                                        @click.prevent="confirmResetModal" type="submit">Confirm Reset</button>
                                </div>
                            </form>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-none col-md-2"></div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div class="modal fade" id="resetConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reset this account?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-darkr" style="font-weight: bold" data-toggle="tooltip"
                        data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-light-danger" style="font-weight: bold" data-toggle="tooltip"
                        data-placement="bottom" title="Reset account" @click.prevent="resetConfirm">
                        <i class="bi bi-arrow-clockwise"></i>
                        RESET
                    </button>
                </div>
            </div>
        </div>
    </div>
    <Loader ref="loading_bar" />
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3';

const validationErrors = ref({});
const validationMessage = ref(null);
const loading_bar = ref(null);
const resetToken = ref('');
const showEnterToken = ref(false);

const sendTokenEmail = async () => {
    try {

        nextTick(() => {
            loading_bar.value.start();
        });
        const response = await axios.get(route('configuration.send_token_email.send'));
        showEnterToken.value = true;
        resetToken.value = '';
        nextTick(() => {
            loading_bar.value.finish();
        });
        successMessage("The Reset Token Email Send Successfully")

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        errorMessage('Unable to send token email.');
    }
};

const resetConfirm = async () => {
    $('#resetConfirmModal').modal('hide');
    try {
        nextTick(() => {
            loading_bar.value.start();
        });

        if (!(resetToken.value)) {
            nextTick(() => {
                loading_bar.value.finish();
            });

            return errorMessage("Please enter token value");
        }
        const response = await axios.post(route('configuration.reset.account', resetToken.value));
        showEnterToken.value = false;
        resetToken.value = '';
        nextTick(() => {
            loading_bar.value.finish();
        });
        successMessage("Account Successfully Reset")
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
};


const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data.error)) return;
    let message = error.response.data.error;
    errorMessage(message);
};

const confirmResetModal = async (id) => {
    $('#resetConfirmModal').modal('show');
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

onMounted(() => {
});
</script>

<style lang="scss" scoped>
.alert-confirm-header {
    line-height: 1.5;
    background-color: #F3F2F7;
    padding: 20px;
    padding-top: 20px;
    padding-bottom: 22px;
    border-radius: 10px;
    text-align: center;
    box-sizing: border-box;
    overflow: visible;
    word-wrap: break-word;
}

.alert-header {
    line-height: 1.5;
    color: rgba(190, 22, 22, 0.808);
    background-color: #ffeeee;
    // border: 1px solid #ff0000;
    padding: 20px;
    padding-top: 10px;
    padding-bottom: 22px;
    border-radius: 10px;
    text-align: center;
    box-sizing: border-box;
    overflow: visible;
    word-wrap: break-word;
}

.alert-header b,
.alert-header p {
    margin: 10px 0;
    padding: 0;
    line-height: 1.5;
}
</style>
