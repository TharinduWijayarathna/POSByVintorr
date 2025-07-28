<template>
    <div class="row px-md-7 px-xl-10">
        <div class="col-12">
            <form @submit.prevent="updateControllers" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6 d-none d-md-block">
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="dailyTotalSales">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Daily Total Sales Chart</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyTotalSales">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Yearly Total Sales Chart</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 d-none d-md-block">
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="dailyNOB">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Daily Number of Bills Chart</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyNOB">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Yearly Number of Bills Chart</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 d-none d-md-block">
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyPerformance">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Yearly Performance Chart</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="topSellingProducts">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Top Selling Products</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 d-none d-md-block">
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="rolReachProducts">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">ROL Reached Products</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="mt-4 form-check form-switch ms-5 check-box-item">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="expense">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-gray-600 col-form-label">Expense Chart</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-block d-md-none">
                    <div class="col-12">
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="dailyTotalSales">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Daily Total Sales Chart</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="dailyNOB">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Daily Number of Bills Chart</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyTotalSales">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Yearly Total Sales Chart</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyNOB">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Yearly Number of Bills Chart</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="yearlyPerformance">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Yearly Performance Chart</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="rolReachProducts">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">ROL Reached Products</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="topSellingProducts">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Top Selling Products</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-2 d-flex align-items-center">
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" @click="activeDeactivate"
                                        v-model="expense">
                                </div>
                            </div>
                            <div class="col-10 d-flex align-items-center">
                                <label class="text-gray-600 col-form-label ms-2">Expense Chart</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mt-4 mb-3 col-12 text-end">
                        <button type="submit" class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                            data-placement="bottom" title="Save changes">
                            SAVE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Loader ref="loading_bar" />
</template>


<script setup>
import { ref, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';

const dailyTotalSales = ref(false);
const dailyNOB = ref(false);
const yearlyTotalSales = ref(false);
const yearlyNOB = ref(false);
const yearlyPerformance = ref(false);
const rolReachProducts = ref(false);
const topSellingProducts = ref(false);
const expense = ref(false);

const dashboardItems = {
    dailyTotalSales,
    dailyNOB,
    yearlyTotalSales,
    yearlyNOB,
    yearlyPerformance,
    rolReachProducts,
    topSellingProducts,
    expense
};

const loading_bar = ref(null);
const validationErrors = ref({});
const validationMessage = ref(null);

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

const getDashboardController = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        const response = await axios.get(route('dashboard.controller.get'));
        const data = response.data;

        Object.keys(dashboardItems).forEach((key, index) => {
            dashboardItems[key].value = Boolean(data[index].status);
        });

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

const updateControllers = async () => {
    try {
        const dashboardItems = {
            daily_total_sales_chart: dailyTotalSales.value,
            daily_nob_chart: dailyNOB.value,
            yearly_total_sales_chart: yearlyTotalSales.value,
            yearly_nob_chart: yearlyNOB.value,
            yearly_performance_chart: yearlyPerformance.value,
            rol_reached_products: rolReachProducts.value,
            top_selling_products: topSellingProducts.value,
            expense_chart: expense.value
        };
        const response = await axios.post(route('dashboard.controller.update'), dashboardItems);
        successMessage('dashboard controller update successfully');
        getDashboardController();
    } catch (error) {
        errorMessage(error);
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

onMounted(() => {
    getDashboardController();
});

</script>
