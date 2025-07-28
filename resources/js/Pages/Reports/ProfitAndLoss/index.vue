<template>
    <AppLayout title="Profit and Loss">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 col-sm-6 d-flex justify-content-start align-items-center">
                                <h1 class="page-title">
                                    Profit & Loss&nbsp;
                                </h1>
                            </div>
                            <!-- Desktop view -->
                            <div class="col-12 col-sm-6 d-none d-md-flex justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Print profit & loss report"
                                    class="btn btn-info fw-bold">
                                    <i class="bi bi-printer"></i>
                                    PRINT
                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Export profit & loss report"
                                    class="btn btn-info ms-4 fw-bold">
                                    <i class="bi bi-box-arrow-up"></i> EXPORT
                                </a>
                            </div>

                            <!-- Mobile view -->
                            <div class="col-12 col-sm-6 d-flex d-md-none justify-content-end">
                                <a @click.prevent="printReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Print profit & loss report"
                                    class="btn btn-info fw-bold export-btn ps-3 pe-2 ms-2">
                                    <i class="bi bi-printer fs-2 export-icon ms-1"></i>

                                </a>
                                <a @click.prevent="exportReport" href="javascript:void(0)" data-toggle="tooltip"
                                    data-placement="bottom" title="Export profit & loss report"
                                    class="btn btn-info export-btn ps-3 pe-2 ms-2">
                                    <i class="bi bi-box-arrow-up fs-2 export-icon ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="shadow card">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="select_search_customer"
                                            class="pt-0 pb-1 text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                                            :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                            :searchable="true" placeholder="Select" label="code" track-by="id"
                                            max-height="200" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="from_date" class="pt-0 pb-1 text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker v-model="from_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :upperLimit="to_date" />
                                    </div>
                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-0">
                                        <label for="to_date" class="pt-0 pb-1 text-gray-600 col-form-label">TO
                                            DATE</label>
                                        <Datepicker v-model="to_date" class="form-control form-control-sm"
                                            :placeholder="placeholderText" :lowerLimit="from_date" />
                                    </div>

                                    <div class="mb-2 col-xl-2 col-xxl-2 mb-xl-0 pe-0 align-self-end">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openProfitAndLossFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sales & Income -->
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="p-2 m-2 text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="px-5 left">SALES & INCOME</th>
                                                    <th class="m-2 text-left"> </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr>
                                                    <td class="px-5 py-2 text-left"> Sales On Cash</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{

                                                                numberFormatter(sales_on_cash, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left"> Sales On Credit</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{

                                                                numberFormatter(sales_on_credit, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left"> Manual Transactions</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{

                                                                numberFormatter(positive_manual_transactions, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left fw-bold text-dark"> Total Sales</td>
                                                    <th class="px-5 text-end fw-bold text-dark"><span class="me-2">{{
                                                        select_currency ?
                                                            select_currency.code : "" }}</span>{{

                                                                numberFormatter((sales_on_cash + sales_on_credit +
                                                                    positive_manual_transactions), 2)

                                                            }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Expenses -->
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="p-2 m-2 text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="px-5 left">Expenses</th>
                                                    <th class="m-2 text-left"> </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr>
                                                    <td class="px-5 py-2 text-left">Returns</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{
                                                                numberFormatter(returns, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left">Payroll</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{
                                                                numberFormatter(payroll, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left">Expenses</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{
                                                                numberFormatter(otherExpenses, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left"> Manual Transactions</td>
                                                    <th class="px-5 text-end"><span class="me-2">{{ select_currency ?
                                                        select_currency.code : "" }}</span>{{

                                                                numberFormatter(negative_manual_transactions, 2)
                                                            }}</th>
                                                </tr>
                                                <tr>
                                                    <td class="px-5 py-2 text-left fw-bold text-dark">Total Expenses
                                                    </td>
                                                    <th class="px-5 text-end fw-bold text-dark"><span class="me-2">{{
                                                        select_currency ?
                                                            select_currency.code : "" }}</span>{{
                                                                numberFormatter((returns + otherExpenses + payroll +
                                                                    negative_manual_transactions), 2)
                                                            }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Profit -->
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="p-2 m-2 text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="px-5 left">Profit</th>
                                                    <th class="px-5 text-end fs-4"><span class="me-2">{{ select_currency
                                                        ?
                                                        select_currency.code : "" }}</span>{{
                                                                numberFormatter(profit, 2)
                                                            }}</th>

                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr class="p-2 m-2 text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="px-5 left">Profit %</th>
                                                    <th class="px-5 text-end fs-4">
                                                        {{ numberFormatter(
                                                            profit_percentage, 2)
                                                        }}%
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profit & Loss Filter Modal -->
            <div class="modal fade" id="profitAndLossFIlterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="profitAndLossFIlterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Profit & Loss Filters</h5>
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
                                        <label for="modalSelectCurrency"
                                            class="text-gray-600 col-form-label">CURRENCY</label>
                                        <Multiselect id="modalSelectCurrency" v-model="select_currency"
                                            :options="currencies" class="z__index" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="code" track-by="id" max-height="200" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalFromDate" class="text-gray-600 col-form-label">FROM
                                            DATE</label>
                                        <Datepicker id="modalFromDate" v-model="from_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :upperLimit="to_date" />
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <label for="modalToDate" class="text-gray-600 col-form-label">TO DATE</label>
                                        <Datepicker id="modalToDate" v-model="to_date"
                                            class="form-control form-control-sm" :placeholder="placeholderText"
                                            :lowerLimit="from_date" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="profitAndLossFIlterModalClear"
                                            class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyProfitAndLossFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
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
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, watch, nextTick, computed } from 'vue';
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Loader from '@/Components/Basic/LoadingBar.vue';
import Datepicker from 'vue3-datepicker'

const from_date = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1));
const to_date = ref(new Date());

const loading_bar = ref(null);
const currencies = ref([]);
const select_currency = ref({});
const businessDetails = ref({});
const placeholderText = 'DD/MM/YYYY';

const sales_on_cash = ref(0);
const sales_on_credit = ref(0);
const positive_manual_transactions = ref(0);
const negative_manual_transactions = ref(0);

const payroll = ref(0);
const otherExpenses = ref(0);
const returns = ref(0);

const profit = ref(0);
const profit_percentage = ref(0);

const search_data = ref({
    from_date: from_date.value,
    to_date: to_date.value,
    currency_id: select_currency.value.id,
})

const setToDate = () => {
    to_date.value.setHours(23, 59, 59, 999);
}

const getSearch = async () => {
    if (search_data.value.from_date && search_data.value.currency_id) {
        reload();
    }
};

const openProfitAndLossFilterModal = () => {
    loading_bar.value.start();
    $('#profitAndLossFIlterModal').modal('show');
    loading_bar.value.finish();
};

const applyProfitAndLossFilter = () => {
    $('#profitAndLossFIlterModal').modal('hide');
    reload();
};

const profitAndLossFIlterModalClear = async () => {
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    if (businessDetails.value.currency_id) {
        select_currency.value.id = businessDetails.value.currency_id;
        select_currency.value.code = businessDetails.value.currency_name;
    }
    search_data.value = {
        from_date: from_date.value,
        to_date: to_date.value,
        currency_id: select_currency.value.id,
    };
    $('#profitAndLossFIlterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = await axios.get(route('report.profitAndLoss.all'), {
            params: {
                search_data_from_date: search_data.value.from_date,
                search_data_to_date: search_data.value.to_date,
                search_data_currency: search_data.value.currency_id,
            },
        });

        const tableData = response.data;

        // Convert string values to numbers
        sales_on_cash.value = parseFloat(tableData.sales_on_cash) || 0;
        sales_on_credit.value = Math.abs(parseFloat(tableData.sales_on_credit)) || 0;
        positive_manual_transactions.value = Math.abs(parseFloat(tableData.positive_manual_transactions)) || 0;
        negative_manual_transactions.value = Math.abs(parseFloat(tableData.negative_manual_transactions)) || 0;
        returns.value = Math.abs(parseFloat(tableData.returns)) || 0;
        payroll.value = parseFloat(tableData.payroll) || 0;
        otherExpenses.value = parseFloat(tableData.otherExpenses) || 0;
        profit.value = parseFloat(tableData.profit) || 0;
        profit_percentage.value = parseFloat(tableData.profit_percentage) || 0;

    } catch (error) {
        convertValidationNotification(error);
    } finally {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getCurrencies = async () => {
    try {
        const response = (await axios.get(route("currency.list"))).data;
        currencies.value = response.data;

    } catch (error) {
    }
};

const getBusinessDetails = async () => {
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        businessDetails.value = response.data;

        if (businessDetails.value.currency_id) {
            select_currency.value.id = businessDetails.value.currency_id;
            select_currency.value.code = businessDetails.value.currency_name;
            search_data.value.currency_id = select_currency.value.id;
        }

    } catch (error) {
    }
};

watch(select_currency, (newValue) => {
    search_data.value.currency_id = newValue ? newValue.id : null;
    getSearch();
});

watch(from_date, (newValue) => {
    search_data.value.from_date = newValue;
    getSearch();
});

watch(to_date, (newValue) => {
    if (to_date.value) {
        const endDate = new Date(newValue);
        endDate.setHours(23, 59, 59, 999);
        search_data.value.to_date = endDate;
        getSearch();
    } else {
        search_data.value.to_date = null;
    }
});

const clearFilters = () => {
    from_date.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    to_date.value = new Date();
    if (businessDetails.value.currency_id) {
        select_currency.value.id = businessDetails.value.currency_id;
        select_currency.value.code = businessDetails.value.currency_name;
    }
    search_data.value = {
        from_date: from_date.value,
        to_date: to_date.value,
        currency_id: select_currency.value.id,
    };
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

const printReport = async () => {
    try {
        await nextTick(() => {
            loading_bar.value.start();
        });
        const response = await axios.post(
            route("report.profitAndLoss.print"),
            {
                search_data_from_date: search_data.value.from_date,
                search_data_to_date: search_data.value.to_date,
                search_data_currency: select_currency.value,
                sales_on_cash: sales_on_cash.value,
                sales_on_credit: sales_on_credit.value,
                positive_manual_transactions: positive_manual_transactions.value,
                negative_manual_transactions: negative_manual_transactions.value,
                returns: returns.value,
                payroll: payroll.value,
                otherExpenses: otherExpenses.value,
                profit: profit.value,
                profit_percentage: profit_percentage.value,
            },
            { responseType: "blob" }
        );
        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
    } catch (error) {
        convertValidationNotification(error);
    } finally {
        await nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const exportReport = async () => {
    try {
        loading_bar.value.start();

        const response = await axios.post(
            route("report.profitAndLoss.export"),
            {
                search_data_from_date: search_data.value.from_date,
                search_data_to_date: search_data.value.to_date,
                search_data_currency: select_currency.value,
                sales_on_cash: sales_on_cash.value,
                sales_on_credit: sales_on_credit.value,
                positive_manual_transactions: positive_manual_transactions.value,
                negative_manual_transactions: negative_manual_transactions.value,
                returns: returns.value,
                payroll: payroll.value,
                otherExpenses: otherExpenses.value,
                profit: profit.value,
                profit_percentage: profit_percentage.value,
            }
        );
        const downloadUrl = response.data.path;
        const link = document.createElement("a");
        link.href = downloadUrl;


        // file name
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        const fileName = `ProfitAndLossReport-${formattedDate}.xlsx`;

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();

    } catch (error) {
        convertValidationNotification(error);
    } finally {
        loading_bar.value.finish();
    }
};



onMounted(async () => {
    setToDate();
    await getCurrencies();
    await getBusinessDetails();
    await reload();
});
</script>

<style lang="css" scoped>
.mobile-row {
    background-color: #f3f2f7 !important;
    border-top: 1px solid #c9c9c9 !important;
    border-bottom: 1px solid #c9c9c900 !important;
    padding: 5px;
    font-size: small;
}

.custom-row {
    background-color: #ffffff !important;
    border-top: 1px solid #c9c9c9 !important;
    border-bottom: 1px solid #c9c9c900 !important;
    padding: 3px;
    font-size: small;
}

.page-title {
    margin-right: auto;
    font-size: 28px;
    color: #071437
}
</style>
