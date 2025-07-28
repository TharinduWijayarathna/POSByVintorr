<template>

    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-11">
            <div class="row d-flex justify-content-center">
                <div class="col-9 col-md-5">
                    <div class="">
                        <div class="text-gray-600 col-form-label ps-md-9 report-label">Monthly Customer Outstanding
                            Report
                            Generate Date</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mt-4 mt-sm-0">
                        <Multiselect v-model="select_date" :options="generateDates(31)" class="text-center z__index"
                            :showLabels="false" :close-on-select="true" :clear-on-select="false" :searchable="true"
                            placeholder="Select Date" label="name" track-by="id" max-height="200" />
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-9 col-md-5">
                    <div class="">
                        <div class="text-gray-600 col-form-label ps-md-9 report-label">Monthly Business Summary Report
                            Generate Date</div>
                    </div>
                </div>
                <div class="col-3 col-md-3">
                    <div class="mt-4 mt-sm-0">
                        <Multiselect v-model="select_monthly_business_summary_date" :options="generateDates(31)"
                            class="text-center z__index" :showLabels="false" :close-on-select="true"
                            :clear-on-select="false" :searchable="true" placeholder="Select Date" label="name"
                            track-by="id" max-height="200" />
                    </div>
                </div>
            </div>
            <div class="mb-3 row d-flex justify-content-center">
                <div class="col-9 col-md-5">
                </div>
                <div class="col-3 col-md-3">
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-info w-30 fw-bold" data-toggle="tooltip"
                            @click="updateDates()" data-placement="bottom" title="Save changes">
                            SAVE
                        </button>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-none col-md-4">
                </div>
                <div class="mt-4 col-4 d-flex flex-end">
                    <div class="text-end ">
                        <button type="button" class="btn btn-info w-30 fw-bold a" data-toggle="tooltip"
                            @click="sendCustomerOutstanding()" data-placement="bottom" title="Save changes">
                            SEND C-O-R
                        </button>
                    </div>
                    <div class="text-end align-item-right justify-content-right">
                        <button type="button" class="btn btn-info w-30 fw-bold ms-2" data-toggle="tooltip"
                            @click="sendMonthlyBusinessSummary()" data-placement="bottom" title="Save changes">
                            SEND M-B-R
                        </button>
                    </div>
                </div>
                <div class="col-none col-md-3">
                </div>
            </div> -->
        </div>
    </div>

    <Loader ref="loading_bar" />
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import Multiselect from 'vue-multiselect';
import { usePage } from '@inertiajs/vue3';

const validationErrors = ref({});
const validationMessage = ref(null);
const select_date = ref(null);
const select_monthly_business_summary_date = ref(null);
const loading_bar = ref(null);

const generateDates = (count) => {
    return Array.from({ length: count + 1 }, (_, index) => {
        if (index === 0) {
            return { id: 0, name: 'NO' };
        } else {
            return { id: index, name: index.toString() };
        }
    });
};

const updateDates = () => {
    try {

        saveMonthlyCustomOutstandingDate();
        saveMonthlyBusinessSummaryDate();
        successMessage('Dates Updated successfully!');


    } catch (error) {
        convertValidationNotification(error);
    }
}

const saveMonthlyCustomOutstandingDate = async () => {
    try {
        const response = await axios.post(route('configuration.monthly_outstanding_report.store', select_date.value.id));
        getMonthlyCustomOutstandingEmailDate();
    } catch (error) {
        convertValidationNotification(error);
    }
};

const saveMonthlyBusinessSummaryDate = async () => {
    try {
        const response = await axios.post(route('configuration.monthly_business_summary_report.store', select_monthly_business_summary_date.value.id));
        getMonthlyBusinessSummaryEmailDate();
    } catch (error) {
        convertValidationNotification(error);
    }
};

const getMonthlyCustomOutstandingEmailDate = async () => {
    try {

        const response = await axios.get(route('configuration.monthly_outstanding_report.get'));
        const selected_date = response.data.value;
        if (response.data) {
            if (selected_date == 0) {
                select_date.value = { id: 0, name: 'NO' };
            } else {
                select_date.value = { id: selected_date, name: selected_date };
            }
        }


    } catch (error) {
        convertValidationNotification(error);
    }
};

const getMonthlyBusinessSummaryEmailDate = async () => {
    try {

        const response = await axios.get(route('configuration.monthly_business_summary_report.get'));
        const selected_date = response.data.value;
        if (response.data) {
            if (selected_date == 0) {
                select_monthly_business_summary_date.value = { id: selected_date, name: 'NO' };
            } else {
                select_monthly_business_summary_date.value = { id: selected_date, name: selected_date };
            }
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    let { message } = error.response.data;
    errorMessage(message);
};

const sendCustomerOutstanding = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = await axios.get(route('configuration.monthly_outstanding_report.send'));
        nextTick(() => {
            loading_bar.value.finish();
        });
        successMessage('Email send successfully');
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
};

const sendMonthlyBusinessSummary = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = await axios.get(route('configuration.monthly_business_summary_report.send'));
        nextTick(() => {
            loading_bar.value.finish();
        });
        successMessage('Email send successfully');
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
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
    getMonthlyCustomOutstandingEmailDate();
    getMonthlyBusinessSummaryEmailDate();
});
</script>

<style lang="scss" scoped>
.form-check-input {
    width: 15px;
    height: 15px;
}

.report-label {
    text-align: left;
}

.multiselect-container {
    max-width: 200px;
}
</style>
