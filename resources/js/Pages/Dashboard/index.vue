<template>
    <AppLayout title="Dashboard">
        <template #content>
            <div v-if="props.auth.user.user_role_id != 3">

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                        <h1 class="main-header-text">
                            Dashboard
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="dashboard-bot">
                                <img src="../../../src/images/Dashboard/dashboard-balance-card.png" class="bot-image"
                                    height="120" />
                            </div>
                            <div class="mx-6 mt-6 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fs-1 fw-bold me-2 lh-1 ls-n2">{{
                                            transactionBalance.code
                                        }}

                                            {{
                                                Number(transactionBalance.amount).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }}</span>
                                    </div>
                                    <span class="pt-1 text-gray-500 fw-semibold fs-6">Balance</span>
                                </div>
                            </div>
                            <div class="pt-0 mx-6 mb-5 d-flex align-items-end">
                                <div class="flex-column w-100">
                                    <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                        <div class="pt-1 col-4 text-end">
                                            <Multiselect v-model="select_currency" :options="currencies"
                                                class="z__index custom-currency-multiselect" :showLabels="false"
                                                :close-on-select="true" :clear-on-select="false" :searchable="true"
                                                placeholder="Select" label="code" track-by="id" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div v-if="monthly_target > 0" class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fs-1 fw-bold me-2 lh-1 ls-n2">{{ base_currency_code
                                            }}
                                            {{
                                                Number(monthly_target).toLocaleString('en-US', { minimumFractionDigits: 2 })
                                            }}
                                        </span>
                                    </div>
                                    <span class="pt-1 text-gray-500 fw-semibold fs-6">Target of This Month</span>
                                </div>
                                <i v-if="$page.props.auth.user.user_role_id == 1" data-toggle="tooltip"
                                    data-placement="bottom" title="Change monthly target"
                                    class="mt-1 cursor-pointer bi bi-three-dots-vertical fs-4"
                                    @click="monthlyModal"></i>
                            </div>

                            <div class="pt-0 mx-6 mb-6 d-flex align-items-end">
                                <div class="d-flex align-items-center flex-column w-100">
                                    <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                        <span v-if="monthly_target - monthly_sales_amount > 0"
                                            class="text-gray-900 fw-bolder fs-6">{{
                                                Number(monthly_target - monthly_sales_amount).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }} to Goal</span>
                                        <span v-else></span>
                                        <span class="text-gray-500 fw-bold fs-6">{{
                                            monthly_target_percentage % 1 !== 0
                                                ? monthly_target_percentage.toFixed(2)
                                                : Math.floor(monthly_target_percentage)
                                        }}%</span>
                                    </div>

                                    <div class="mx-3 rounded h-8px w-100 bg-light-danger">
                                        <div v-if="monthly_target_percentage < 100" class="rounded bg-success h-8px"
                                            style="background-color:#DC7633 !important;" role="progressbar"
                                            :style="{ width: monthly_target_percentage + '%' }" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                        <div v-if="monthly_target_percentage == 100" class="rounded bg-success h-8px"
                                            style="background-color:#28B463 !important;" role="progressbar"
                                            :style="{ width: monthly_target_percentage + '%' }" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div v-else class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="pt-3 m-4 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                </div>
                                <i v-if="$page.props.auth.user.user_role_id == 1" data-toggle="tooltip"
                                    data-placement="bottom" title="Add monthly target"
                                    class="mt-1 text-right cursor-pointer bi bi-three-dots-vertical fs-4"
                                    @click="monthlyModal"></i>
                            </div>
                            <div class="px-8 py-1 row">
                                <div class="text-center col-12">
                                    <span class="pt-1 text-gray-500 fw-semibold fs-2">Setup your monthly target</span>
                                </div>
                            </div>
                            <div class="p-4 row d-flex align-items-center h-100">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fs-1 fw-bold me-2 lh-1 ls-n2">{{
                                            totalTaxAmount.code
                                        }}

                                            {{
                                                Number(totalTaxAmount.amount).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }}</span>
                                    </div>
                                    <span class="pt-1 text-gray-500 fw-semibold fs-6">
                                        Tax collected this month
                                    </span>
                                </div>
                            </div>
                            <div class="pt-0 mx-6 mb-5 d-flex align-items-end">
                                <div class="flex-column w-100">
                                    <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                        <div class="pt-1 col-4 text-end">
                                            <Multiselect v-model="select_currency" :options="currencies"
                                                class="z__index custom-currency-multiselect" :showLabels="false"
                                                :close-on-select="true" :clear-on-select="false" :searchable="true"
                                                placeholder="Select" label="code" track-by="id" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div v-if="yearly_target > 0" class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fs-1 fw-bold me-2 lh-1 ls-n2">{{ base_currency_code
                                            }}
                                            {{
                                                Number(yearly_target).toLocaleString('en-US', { minimumFractionDigits: 2 })
                                            }}</span>
                                    </div>
                                    <span class="pt-1 text-gray-500 fw-semibold fs-6">Target of This Year</span>
                                </div>
                                <i v-if="$page.props.auth.user.user_role_id == 1" data-toggle="tooltip"
                                    data-placement="bottom" title="Change yearly target"
                                    class="mt-1 text-right cursor-pointer bi bi-three-dots-vertical fs-4"
                                    @click="yearlyModal"></i>
                            </div>

                            <div class="pt-0 mx-6 mb-6 d-flex align-items-end">
                                <div class="d-flex align-items-center flex-column w-100">
                                    <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                        <span v-if="yearly_target - yearly_sales_amount > 0"
                                            class="text-gray-900 fw-bolder fs-6">{{
                                                Number(yearly_target - yearly_sales_amount).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }} to Goal</span>
                                        <span v-else></span>
                                        <span class="text-gray-500 fw-bold fs-6">{{
                                            yearly_target_percentage % 1 !== 0
                                                ? yearly_target_percentage.toFixed(2)
                                                : Math.floor(yearly_target_percentage)
                                        }}%</span>
                                    </div>

                                    <div class="mx-3 rounded h-8px w-100 bg-light-danger">
                                        <div v-if="yearly_target_percentage < 100" class="rounded bg-success h-8px"
                                            role="progressbar" style="background-color:#DC7633 !important;"
                                            :style="{ width: yearly_target_percentage + '%' }" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                        <div v-if="yearly_target_percentage == 100" class="rounded bg-success h-8px"
                                            role="progressbar" style="background-color:#28B463 !important;"
                                            :style="{ width: yearly_target_percentage + '%' }" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="mt-3 card card-flush mb-xl-5 h-150px">

                            <div class="pt-3 m-4 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                </div>
                                <i v-if="$page.props.auth.user.user_role_id == 1" data-toggle="tooltip"
                                    data-placement="bottom" title="Add yearly target"
                                    class="mt-1 text-right cursor-pointer bi bi-three-dots-vertical fs-4"
                                    @click="yearlyModal"></i>
                            </div>

                            <div class="px-8 py-1 row">
                                <div class="text-center col-12">
                                    <span class="pt-1 text-gray-500 fw-semibold fs-2">Setup your yearly target</span>
                                </div>
                            </div>
                            <div class="p-4 row d-flex align-items-center h-100">
                            </div>
                        </div> -->
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div v-if="total_outstanding > 0" class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 d-flex justify-content-between">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fs-2 fw-bold me-2 lh-1 ls-n2">{{ base_currency_code
                                            }}
                                            {{
                                                Number(total_outstanding).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }}</span>
                                    </div>
                                    <span class="pt-1 text-gray-500 fw-semibold fs-7">Outstanding</span>
                                </div>
                            </div>

                            <div class="pt-0 mx-6 mb-6 d-flex align-items-end">
                                <div class="w-100">
                                    <div class="mt-auto d-flex justify-content-between w-100 fs-7">
                                        <span v-if="total_bill_outstanding > 0" class="text-gray-900 fw-bolder fs-6">
                                            {{ base_currency_code }} {{ formatLargeNumber(total_bill_outstanding) }}
                                        </span>
                                        <span v-else class="fw-bolder">
                                            {{ base_currency_code }} 0.00
                                        </span>

                                        <span v-if="total_outstanding - total_bill_outstanding > 0"
                                            class="text-gray-900 fw-bolder">
                                            {{ base_currency_code }} {{ formatLargeNumber(total_outstanding -
                                                total_bill_outstanding) }}
                                        </span>
                                        <span v-else class="fw-bolder">
                                            {{ base_currency_code }} 0.00
                                        </span>
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between w-100">
                                        <span class="text-gray-500 fw-bold fs-6">Bill</span>
                                        <span class="text-gray-500 fw-bold fs-6">Invoice</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-else class="mt-3 card card-flush mb-xl-5 h-150px">

                            <div class="p-8 text-center row d-flex align-items-center h-100">
                                <div class="col-12">
                                    <span class="pt-1 text-center text-gray-500 fw-semibold fs-2">No any
                                        outstanding</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- start daily charts -->
                <div class="row">
                    <div class="px-8 mt-5 col-12 px-md-11">
                        <h1 class="text-gray-700" v-if="dashboardItems.dailyNOB || dashboardItems.dailyTotalSales">Daily
                        </h1>
                    </div>
                </div>

                <div class=""
                    v-if="!dashboardItems.dailyNOB && dashboardItems.dailyTotalSales || dashboardItems.dailyNOB && !dashboardItems.dailyTotalSales">
                    <div class="row" v-if="!dashboardItems.dailyNOB && dashboardItems.dailyTotalSales">
                        <div class="mt-1 col-md-12">
                            <div class="card">
                                <div class="px-5 px-md-8">
                                    <div class="mt-2 row">
                                        <div class="col-6">
                                            <h5 class="mt-5 mb-1 h3">Total Sales</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="text-gray-400" /> -->
                                <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                    <apexchart type="area" height="250" :options="chartOptionsSale"
                                        :series="seriesSale">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="dashboardItems.dailyNOB && !dashboardItems.dailyTotalSales">
                        <!-- aa {{ dashboardItems.dailyNOB }} -->
                        <div class="mt-1 col-md-12">
                            <div class="card">
                                <div class="px-5 px-md-8">
                                    <div class="mt-2 row">
                                        <div class="col-6">
                                            <h5 class="mt-5 mb-1 h3">Number of Bills</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="text-gray-400" /> -->
                                <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                    <apexchart type="area" height="250" :options="chartOptionsInvoice"
                                        :series="seriesInvoice">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else-if="dashboardItems.dailyNOB && dashboardItems.dailyTotalSales">
                    <div class="row">
                        <div class="mt-1 mb-8 col-md-6 mb-md-0">
                            <div class="card">
                                <div class="px-5 px-md-8">
                                    <div class="mt-2 row">
                                        <div class="col-6">
                                            <h5 class="mt-5 mb-1 h3">Total Sales</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="text-gray-400" /> -->
                                <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                    <apexchart type="area" height="250" :options="chartOptionsSale"
                                        :series="seriesSale">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                        <!-- aa {{ dashboardItems.dailyNOB }} -->
                        <div class="mt-1 col-md-6">
                            <div class="card">
                                <div class="px-5 px-md-8">
                                    <div class="mt-2 row">
                                        <div class="col-6">
                                            <h5 class="mt-5 mb-1 h3">Number of Bills</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="text-gray-400" /> -->
                                <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                    <apexchart type="area" height="250" :options="chartOptionsInvoice"
                                        :series="seriesInvoice">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- daily charts ends-->


                <!-- start yearly charts -->
                <div class="row"
                    v-if="$page.props.auth.user.user_role_id == 1 && dashboardItems.yearlyTotalSales || dashboardItems.yearlyNOB || dashboardItems.yearlyPerformance">
                    <div class="px-8 mt-10 col-12 px-md-11">
                        <h1 class="text-gray-700">Yearly</h1>
                    </div>
                </div>
                <div class="row" v-if="$page.props.auth.user.user_role_id == 1 && dashboardItems.yearlyPerformance">
                    <div class="mb-8 col-12">
                        <div class="card">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="mb-4 col-12 col-md-4 d-flex flex-column flex-md-row mb-md-0">
                                        <h5 class="mt-5 mb-1 h3">Performance</h5>
                                        <div class="flex-row justify-between d-flex flex-md-row">
                                            <select v-model="selectedYear"
                                                class="mt-3 form-control form-control-sm custom-year-multiselect ms-md-4 me-md-3">
                                                <option v-for="year in years" :key="year" :value="year">{{ year }}
                                                </option>
                                            </select>
                                            <Multiselect v-model="select_currency" :options="currencies"
                                                class="mt-3 z__index custom-currency-multiselect ms-md-4"
                                                :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                                :searchable="true" placeholder="Select" label="code" track-by="id" />
                                        </div>
                                    </div>
                                    <div
                                        class="mt-3 col-12 col-md-8 d-flex flex-column flex-md-row align-items-center justify-content-end mt-md-0">
                                        <div class="mb-2 d-flex align-items-center mb-md-0 w-100 w-md-auto">
                                            <i class="bi bi-arrow-up-left-circle-fill h3 me-1 tick-color1 fs-3"></i>
                                            <h5 class="text-gray-700 h3 text-start text-md-end w-100 w-md-auto">{{
                                                totalOfSales }}</h5>
                                        </div>
                                        <div class="mb-2 d-flex align-items-center mb-md-0 ms-md-3 w-100 w-md-auto">
                                            <i class="bi bi-arrow-up-circle-fill h3 me-1 tick-color2 fs-3"></i>
                                            <h5 class="text-gray-700 h3 text-start text-md-end w-100 w-md-auto">{{
                                                totalOfIncomes }}</h5>
                                        </div>
                                        <div class="d-flex align-items-center ms-md-3 w-100 w-md-auto">
                                            <i class="bi bi-arrow-down-circle-fill h3 me-1 tick-color3 fs-3"></i>
                                            <h5 class="text-gray-700 h3 text-start text-md-end w-100 w-md-auto">{{
                                                totalOfExpenses }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-2 d-none d-md-block card-body ps-0 pe-2 px-md-8">
                                <apexchart type="area" height="250" :options="chartOptionMonthlyPerformance"
                                    :series="seriesMonthlyPerformance">
                                </apexchart>
                            </div>
                            <div class="text-center d-block d-md-none card-body">
                                <button class="py-2 btn btn-primary btn-sm rounded-pill"
                                    @click="openPerformanceChartModal">View Chart</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"
                    v-if="$page.props.auth.user.user_role_id == 1 && !dashboardItems.yearlyNOB && dashboardItems.yearlyTotalSales || dashboardItems.yearlyNOB && !dashboardItems.yearlyTotalSales">
                    <div class="col-md-12" v-if="!dashboardItems.yearlyNOB && dashboardItems.yearlyTotalSales">
                        <div class="card">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="col-6">
                                        <h5 class="mt-5 mb-1 h3">Total Sales</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="text-gray-400" /> -->
                            <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                <apexchart type="area" height="250" :options="chartOptionMonthlySales"
                                    :series="seriesMonthlySales">
                                </apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="dashboardItems.yearlyNOB && !dashboardItems.yearlyTotalSales">
                        <div class="card">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="col-6">
                                        <h5 class="mt-5 mb-1 h3">Number of Bills</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="text-gray-400" /> -->
                            <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                <apexchart type="area" height="250" :options="chartOptionMonthlyInvoices"
                                    :series="seriesMonthlyInvoices">
                                </apexchart>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="dashboardItems.yearlyNOB && dashboardItems.yearlyTotalSales">
                    <div class="mb-8 col-md-6 mb-md-0">
                        <div class="card">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="col-6">
                                        <h5 class="mt-5 mb-1 h3">Total Sales</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="text-gray-400" /> -->
                            <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                <apexchart type="area" height="250" :options="chartOptionMonthlySales"
                                    :series="seriesMonthlySales">
                                </apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="col-6">
                                        <h5 class="mt-5 mb-1 h3">Number of Bills</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="text-gray-400" /> -->
                            <div class="pb-2 card-body ps-0 pe-2 px-md-8">
                                <apexchart type="area" height="250" :options="chartOptionMonthlyInvoices"
                                    :series="seriesMonthlyInvoices">
                                </apexchart>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- yearly chart ends -->


                <!-- bottom section starts -->
                <div class="mt-8 row"
                    v-if="dashboardItems.expense || dashboardItems.topSellingProducts || dashboardItems.rolReachProducts">
                    <div class="mb-8 col-md-6 mb-md-0" v-if="dashboardItems.expense">
                        <div class="card h-100">
                            <div class="px-5 px-md-8">
                                <div class="mt-2 row">
                                    <div class="col-7">
                                        <h5 class="mt-6 mb-4 h3">Expenses</h5>
                                    </div>
                                    <div class="pt-3 col-5 text-end d-flex justify-content-end">
                                        <Multiselect v-model="select_month" :options="months"
                                            class="z__index custom-month-multiselect me-3" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="name" track-by="id" />
                                        <Multiselect v-model="select_currency" :options="currencies"
                                            class="z__index custom-currency-multiselect" :showLabels="false"
                                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                                            placeholder="Select" label="code" track-by="id" />
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="text-gray-400" /> -->
                            <div class="pb-5 card-body">
                                <apexchart v-if="allZero == false" type="donut" height="346"
                                    :options="chartOptionsExpenses" :series="seriesExpenses">
                                </apexchart>
                                <div v-else class="hover-scroll-overlay-y pe-6 me-n6" style="height: 349px">
                                    <div class="py-3 d-flex align-items-center justify-content-center">
                                        <div class="mt-8 text-center col-12">
                                            <i class="text-gray-400 bi bi-wallet2 fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="py-3 d-flex align-items-center justify-content-center">
                                        <span class="pt-1 text-gray-500 fw-semibold fs-6">No any expenses in this
                                            month.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 col-md-6 mb-md-0"
                        v-if="dashboardItems.topSellingProducts && !dashboardItems.rolReachProducts">
                        <div class="card">
                            <div class="px-5 card-body px-md-8">
                                <h5 class="mb-4 h3">Top Selling Products</h5>
                                <div v-if="featured_products.length > 1"
                                    class="cursor-pointer hover-scroll-overlay-y pe-6 me-n6 top-selling-product-card"
                                    @click.prevent="viewProductSalesReport()">
                                    <div v-for="featured_product in featured_products" class="py-3">
                                        <div class="mb-1 d-flex flex-stack">
                                            <div class="d-flex align-items-center ps-1">
                                                <div class="dashboard-product-image-outline"
                                                    v-if="!featured_product.image_url">
                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                        class="dashboard-product-image" alt="">
                                                </div>
                                                <div class="dashboard-product-image-outline"
                                                    v-if="featured_product.image_url">
                                                    <img :src="featured_product.image_url"
                                                        class="dashboard-product-image">
                                                </div>
                                                <a href="#" class="text-gray-800 text-hover-primary fw-bold ms-3">{{
                                                    featured_product.product.name }}</a>
                                            </div>

                                            <div class="m-0">
                                                <span class="badge badge-light-primary">{{
                                                    Number(featured_product.total_quantity).toLocaleString('en-US', {
                                                        minimumFractionDigits: 0
                                                    }) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="hover-scroll-overlay-y pe-6 me-n6" style="height: 365px">
                                    <div class="py-3 d-flex align-items-center">
                                        <div class="text-center col-12 mt-15">
                                            <i class="text-gray-400 bi bi-graph-up fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="py-3 text-center d-flex align-items-center">

                                        <span class="pt-1 text-gray-500 fw-semibold fs-6">Most sold products of this
                                            month
                                            will appear here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 col-md-6 mb-md-0"
                        v-if="!dashboardItems.topSellingProducts && dashboardItems.rolReachProducts">
                        <div class="card">
                            <div class="px-5 cursor-pointer card-body px-md-8" @click.prevent="getAllRolProducts">
                                <h5 class="mb-4 h3">ROL Reached Products</h5>
                                <div v-if="reOrderProducts.length > 0" class="hover-scroll-overlay-y pe-6 me-n6"
                                    style="height: 365px">
                                    <!-- style="height: 415px" -->

                                    <div v-for="reOrderProduct in reOrderProducts" class="py-3">
                                        <div class="mb-1 d-flex flex-stack">
                                            <div class="d-flex align-items-center ps-1">
                                                <div class="dashboard-product-image-outline"
                                                    v-if="!reOrderProduct.image_url">
                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                        class="dashboard-product-image" alt="">
                                                </div>
                                                <div class="dashboard-product-image-outline"
                                                    v-if="reOrderProduct.image_url">
                                                    <img :src="reOrderProduct.image_url"
                                                        class="dashboard-product-image">
                                                </div>
                                                <a href="#" class="text-gray-800 text-hover-primary fw-bold ms-3">{{
                                                    reOrderProduct.name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="hover-scroll-overlay-y pe-6 me-n6" style="height: 365px">
                                    <div class="py-3 d-flex align-items-center">
                                        <div class="text-center col-12 mt-15">
                                            <i class="text-gray-400 bi bi-exclamation-triangle fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="py-3 text-center d-flex align-items-center">
                                        <span class="pt-1 text-gray-500 fw-semibold fs-6">Reorder-level reached products
                                            will appear here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-8 col-md-3 mb-md-0"
                        v-if="dashboardItems.topSellingProducts && dashboardItems.rolReachProducts">
                        <div class="card">
                            <div class="px-5 card-body px-md-8">
                                <h5 class="mb-4 h3">Top Selling Products</h5>
                                <div v-if="featured_products.length > 1"
                                    class="cursor-pointer hover-scroll-overlay-y pe-6 me-n6 top-selling-product-card"
                                    @click.prevent="viewProductSalesReport()">
                                    <div v-for="featured_product in featured_products" class="py-3">
                                        <div class="mb-1 d-flex flex-stack">
                                            <div class="d-flex align-items-center ps-1">
                                                <div class="dashboard-product-image-outline"
                                                    v-if="!featured_product.image_url">
                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                        class="dashboard-product-image" alt="">
                                                </div>

                                                <div class="dashboard-product-image-outline"
                                                    v-if="featured_product.image_url">
                                                    <img :src="featured_product.image_url"
                                                        class="dashboard-product-image">
                                                </div>
                                                <a href="#" class="text-gray-800 text-hover-primary fw-bold ms-3">{{
                                                    featured_product.product.name }}</a>
                                            </div>

                                            <div class="m-0">
                                                <span class="badge badge-light-primary">{{
                                                    Number(featured_product.total_quantity).toLocaleString('en-US', {
                                                        minimumFractionDigits: 0
                                                    }) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="hover-scroll-overlay-y pe-6 me-n6" style="height: 365px">
                                    <div class="py-3 d-flex align-items-center">
                                        <div class="text-center col-12 mt-15">
                                            <i class="text-gray-400 bi bi-graph-up fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="py-3 text-center d-flex align-items-center">

                                        <span class="pt-1 text-gray-500 fw-semibold fs-6">Most sold products of this
                                            month
                                            will appear here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 col-md-3 mb-md-0"
                        v-if="dashboardItems.topSellingProducts && dashboardItems.rolReachProducts">
                        <div class="card">
                            <div class="px-5 cursor-pointer card-body px-md-8" @click.prevent="getAllRolProducts">
                                <h5 class="mb-4 h3">ROL Reached Products</h5>
                                <div v-if="reOrderProducts.length > 0" class="hover-scroll-overlay-y pe-6 me-n6"
                                    style="height: 365px">
                                    <!-- style="height: 415px" -->

                                    <div v-for="reOrderProduct in reOrderProducts" class="py-3">
                                        <div class="mb-1 d-flex flex-stack">
                                            <div class="d-flex align-items-center ps-1">
                                                <div class="dashboard-product-image-outline"
                                                    v-if="!reOrderProduct.image_url">
                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                        class="dashboard-product-image" alt="">
                                                </div>
                                                <div class="dashboard-product-image-outline"
                                                    v-if="reOrderProduct.image_url">
                                                    <img :src="reOrderProduct.image_url"
                                                        class="dashboard-product-image">
                                                </div>
                                                <a href="#" class="text-gray-800 text-hover-primary fw-bold ms-3">{{
                                                    reOrderProduct.name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="hover-scroll-overlay-y pe-6 me-n6" style="height: 365px">
                                    <div class="py-3 d-flex align-items-center">
                                        <div class="text-center col-12 mt-15">
                                            <i class="text-gray-400 bi bi-exclamation-triangle fs-1"></i>
                                        </div>
                                    </div>
                                    <div class="py-3 text-center d-flex align-items-center">
                                        <span class="pt-1 text-gray-500 fw-semibold fs-6">Reorder-level reached products
                                            will appear here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Weekly Target Modal -->
                <div class="modal fade modal-sm" id="weeklyModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Weekly Target</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-12">
                                        <input v-model="targets.weekly_target" type="number" min="1"
                                            data-toggle="tooltip" data-placement="bottom" title="Enter amount"
                                            class="form-control form-control-sm" placeholder="Enter weekly target" />
                                    </div>
                                    <div class="col-12 col-md-6 offset-0 offset-md-6">
                                        <button type="button" class="btn btn-info w-100" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                            @click="saveTargets">
                                            SAVE
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Monthly Target Modal -->
                <div class="modal fade modal-sm" id="monthlyModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Monthly Target</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-12">
                                        <input v-model="targets.monthly_target" type="number" min="1"
                                            data-toggle="tooltip" data-placement="bottom" title="Enter amount"
                                            class="form-control form-control-sm" placeholder="Enter monthly target" />
                                    </div>
                                    <div class="col-12 col-md-6 offset-0 offset-md-6">
                                        <button type="button" class="btn btn-info w-100" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                            @click="saveTargets">
                                            SAVE
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Yearly Target Modal -->
                <div class="modal fade modal-sm" id="yearlyModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Yearly Target</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-12">
                                        <input v-model="targets.yearly_target" type="number" min="1"
                                            data-toggle="tooltip" data-placement="bottom" title="Enter amount"
                                            class="form-control form-control-sm" placeholder="Enter yearly target" />
                                    </div>
                                    <div class="col-12 col-md-6 offset-0 offset-md-6">
                                        <button type="button" class="btn btn-info w-100" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                            @click="saveTargets">
                                            SAVE
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart Modal -->
                <div class="modal fade" id="performanceChartModal" tabindex="-1"
                    aria-labelledby="performanceChartModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="px-0 modal-body">
                                <div class="px-5 mb-4 col-12 col-md-4 d-flex flex-column flex-md-row mb-md-0">

                                    <div class="flex-row justify-between d-flex flex-md-row">
                                        <h5 class="mb-1 h2">Performance Chart</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <div class="px-5 mb-4 col-12 col-md-4 d-flex flex-column flex-md-row mb-md-0">
                                    <div class="flex-row justify-between d-flex flex-md-row">
                                        <select v-model="selectedYear"
                                            class="mt-3 form-control form-control-sm custom-year-multiselect ms-md-4 me-md-3">
                                            <option v-for="year in years" :key="year" :value="year">{{ year }}
                                            </option>
                                        </select>
                                        <Multiselect v-model="select_currency" :options="currencies"
                                            class="mt-3 z__index custom-currency-multiselect ms-md-4"
                                            :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                            :searchable="true" placeholder="Select" label="code" track-by="id" />
                                    </div>
                                </div>
                                <div class="col-12 pe-2">
                                    <apexchart type="area" height="225" :options="chartOptionMonthlyPerformance"
                                        :series="seriesMonthlyPerformance">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div v-else>
                <div class="row">


                    <div class="col-12 col-md-3"></div>

                    <div class="col-12 col-md-3">
                        <div class="mt-3 text-center card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 text-center d-flex justify-content-center align-item-center">
                                <div class="text-center card-title d-flex flex-column">
                                    <span class="text-gray-900 fw-bold main-number-format fw-semibold">{{
                                        transactionBalance.code }}

                                        {{ Number(transactionBalance.amount).toLocaleString('en-US', {
                                            minimumFractionDigits: 2
                                        })
                                        }}
                                    </span>

                                    <span class="pt-1 text-gray-500 fw-semibold fs-5">Balance</span>
                                </div>
                            </div>
                            <div class="pt-0 mb-5 justify-content-center align-item-center">
                                <div class="">
                                    <div class="mt-auto mb-2 d-flex justify-content-center align-item-center">
                                        <div class="pt-1 text-end align-item-center">
                                            <Multiselect v-model="select_currency" :options="currencies"
                                                class="z__index custom-currency-multiselect" :showLabels="false"
                                                :close-on-select="true" :clear-on-select="false" :searchable="true"
                                                placeholder="Select" label="code" track-by="id" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div v-if="total_outstanding > 0" class="mt-3 card card-flush mb-xl-5 h-150px">
                            <div class="mx-6 mt-6 d-flex justify-content-center align-item-center ">
                                <div class="card-title d-flex flex-column">
                                    <div class="text-center card-title d-flex flex-column">
                                        <span class="text-gray-900 fw-bold main-number-format fw-semibold">{{
                                            base_currency_code
                                        }}
                                            {{
                                                Number(total_outstanding).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2
                                                })
                                            }}
                                        </span>

                                        <span class="pt-1 text-gray-500 fw-semibold fs-5">Outstanding</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-0 mx-6 mb-6 d-flex align-items-end">
                                <div class="w-100">
                                    <div class="mt-auto d-flex justify-content-between w-100 fs-7">
                                        <span v-if="total_bill_outstanding > 0"
                                            class="text-gray-900 fw-bolder secondary-number-format">
                                            {{ base_currency_code }} {{ formatLargeNumber(total_bill_outstanding) }}
                                        </span>
                                        <span v-else class="fw-bolder secondary-number-format">
                                            {{ base_currency_code }} 0.00
                                        </span>

                                        <span v-if="total_outstanding - total_bill_outstanding > 0"
                                            class="text-gray-900 fw-bolder secondary-number-format">
                                            {{ base_currency_code }} {{ formatLargeNumber(total_outstanding -
                                                total_bill_outstanding) }}
                                        </span>
                                        <span v-else class="fw-bolder secondary-number-format">
                                            {{ base_currency_code }} 0.00
                                        </span>
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between w-100">
                                        <span class="text-gray-500 fw-bold fs-6">Bill</span>
                                        <span class="text-gray-500 fw-bold fs-6">Invoice</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-else class="mt-3 card card-flush mb-xl-5 h-150px">

                            <div class="p-8 text-center row d-flex align-items-center h-100">
                                <div class="col-12">
                                    <span class="pt-1 text-center text-gray-500 fw-semibold fs-5">No any
                                        outstanding</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3"></div>
                </div>

                <div class="row full-height">
                    <div class="col-12">
                        <h1 class="text-center fs-3x fw-bolder">Welcome to SparkPos</h1>
                    </div>
                    <div class="text-center col-12">
                        <span class="text-gray-600 fs-2">You can use this account to audit {{ businessDetails.name
                            }}</span>
                    </div>
                    <div class="mt-8 text-center col-12 d-block d-md-none">
                        <img src="../../../src/images/Audit/audit-dashboard.png" height="250" />
                    </div>
                    <div class="mt-8 text-center col-12 d-none d-md-block">
                        <img src="../../../src/images/Audit/audit-dashboard.png" height="400" />
                    </div>
                    <div class="mt-8 text-center col-12">
                        <a href="https://SparkPos.lk" class="fs-3" target="_blank">Learn more about SparkPos</a>
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
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, defineProps, onMounted, computed, watch, nextTick, reactive } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faHouse, faAngleRight } from '@fortawesome/free-solid-svg-icons';
import Swal from "sweetalert2";
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import { usePage, router } from '@inertiajs/vue3'
import Loader from '@/Components/Basic/LoadingBar.vue';

const selectedYear = ref(new Date().getFullYear());
const startYear = new Date().getFullYear() - 10;
const endYear = new Date().getFullYear();
const years = computed(() => {
    const yearsArray = [];
    for (let year = endYear; year >= startYear; year--) {
        yearsArray.push(year);
    }
    return yearsArray;
});

const { props } = usePage();
const props_data = props;

defineProps(['weekly_target', 'monthly_target', 'yearly_target', 'weekly_sales_amount', 'monthly_sales_amount', 'yearly_sales_amount', 'weekly_target_percentage', 'monthly_target_percentage', 'yearly_target_percentage', 'total_outstanding', 'total_bill_outstanding', 'total_invoice_outstanding', 'monthly_sales', 'monthly_invoices', 'today_sales', 'today_invoice', 'hours', 'total_system_sales', 'total_system_invoices', 'today_sales_amount', 'month_sales_amount', 'today_invoice_count', 'month_invoice_count', 'distinct_month_names', 'distinct_month_names_invoices', 'year_month', 'twelve_months', 'month_dates', 'featured_products', 'base_currency_code', 'currency_wise_outstanding']);

library.add(faHouse);
library.add(faAngleRight);

const targets = ref({});

const year_month = ref([]);
const month_dates = ref([]);

const reOrderProducts = ref([]);
const businessDetails = ref({});
const currencies = ref([]);
const select_currency = ref({});
const currency_value = ref();
const sales = ref([]);
const incomes = ref([]);
const expenses = ref([]);
const expensesDetails = ref([]);
const expenseLabelDetails = ref([]);
const allZero = ref(false);
const transactionBalance = ref({});
const totalTaxAmount = ref({});
const seriesSale = props_data.today_sales;
const select_month = ref(null);
const months = [
    { id: 1, name: 'January' },
    { id: 2, name: 'February' },
    { id: 3, name: 'March' },
    { id: 4, name: 'April' },
    { id: 5, name: 'May' },
    { id: 6, name: 'June' },
    { id: 7, name: 'July' },
    { id: 8, name: 'August' },
    { id: 9, name: 'September' },
    { id: 10, name: 'October' },
    { id: 11, name: 'November' },
    { id: 12, name: 'December' }
];

const dashboardItems = reactive({
    dailyTotalSales: false,
    dailyNOB: false,
    yearlyTotalSales: false,
    yearlyNOB: false,
    yearlyPerformance: false,
    rolReachProducts: false,
    topSellingProducts: false,
    expense: false
});
const loading_bar = ref(null);

const chartOptionsSale = ref({
    chart: {
        height: 350,
        type: 'area',
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
        type: 'time',
        labels: {
            format: 'HH:mm',
            rotate: -45,
            style: {
                fontSize: '12px'
            }
        },
        categories: month_dates.value,
        tooltip: {
            enabled: false
        }
    },
    tooltip: {
        x: { format: 'dd-MM-yy HH:mm' },
    },
    yaxis: {
        labels: {
            formatter: (val) => (val / 1).toFixed(2).replace(",", ".").toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    },
    responsive: [
        {
            breakpoint: 1460,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        formatter: function (value) {
                            return value.length > 5 ? value.split(' ').join('<br>') : value;
                        },
                        style: {
                            fontSize: '10px'
                        }
                    }
                }
            }
        },
        {
            breakpoint: 480,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        formatter: function (value) {
                            return value.length > 5 ? value.split(' ').join('<br>') : value;
                        },
                        style: {
                            fontSize: '8px'
                        }
                    }
                }
            }
        }
    ]
});

const seriesInvoice = props_data.today_invoice;
const chartOptionsInvoice = ref({
    chart: {
        height: 350,
        type: 'area',
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
        type: 'time',
        labels: {
            format: 'HH:mm',
            rotate: -45,
            style: {
                fontSize: '12px'
            }
        },
        categories: month_dates.value,
        tooltip: {
            enabled: false
        }
    },
    tooltip: { x: { format: 'dd-MM-yy HH:mm' } },
    yaxis: {
        labels: {
            formatter: (val) => val.toFixed(0)
        }
    },
    responsive: [
        {
            breakpoint: 1460,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        formatter: function (value) {
                            return value.length > 5 ? value.split(' ').join('<br>') : value;
                        },
                        style: {
                            fontSize: '10px'
                        }
                    }
                }
            }
        },
        {
            breakpoint: 480,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        formatter: function (value) {
                            return value.length > 5 ? value.split(' ').join('<br>') : value;
                        },
                        style: {
                            fontSize: '8px'
                        }
                    }
                }
            }
        }
    ]
});

const seriesMonthlyPerformance = [
    {
        name: "Sales",
        data: sales
    },
    {
        name: "Incomes",
        data: incomes
    },
    {
        name: "Expenses",
        data: expenses
    },
];
const chartOptionMonthlyPerformance = ref({
    chart: {
        height: 350,
        type: 'area',
        zoom: { enabled: false }
    },
    colors: ['#008ffb', '#00e396', '#ff4560'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
        categories: props_data.twelve_months,
        labels: {
            formatter: function (value) {
                if (value && value.length > 5) {
                    return value.split(' ').join('<br>');
                }
                return value || '';
            }
        },
        tooltip: {
            enabled: false
        }
    },
    yaxis: {
        labels: {
            formatter: (val) => (val / 1).toFixed(2).replace(",", ".").toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    },
    responsive: [
        {
            breakpoint: 1200,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        style: {
                            fontSize: '10px'
                        }
                    }
                }
            }
        },
        {
            breakpoint: 480,
            options: {
                xaxis: {
                    labels: {
                        rotate: 0,
                        style: {
                            fontSize: '8px'
                        }
                    }
                }
            }
        }
    ]
});

const chartOptionMonthlySales = ref({
    chart: {
        height: 350,
        type: 'area',
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
        categories: props_data.year_month,
        tooltip: {
            enabled: false
        }
    },
    yaxis: {
        labels: {
            formatter: (val) => (val / 1).toFixed(2).replace(",", ".").toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    }
});
const seriesMonthlySales = props_data.monthly_sales;

const chartOptionMonthlyInvoices = ref({
    chart: {
        height: 350,
        type: 'area',
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
        categories: props_data.year_month,
        tooltip: {
            enabled: false
        }
    },
    yaxis: {
        labels: {
            // formatter: (val) => (val / 1).toFixed(2).replace(",", ".").toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            formatter: (val) => val.toFixed(0)
        }
    }
});
const seriesMonthlyInvoices = props_data.monthly_invoices;

// const seriesExpenses = props_data.monthly_expense_percentage.expenses;

const seriesExpenses = expensesDetails;
const chartOptionsExpenses = ref({
    chart: {
        height: 300,
        type: 'donut',
        zoom: { enabled: false }
    },
    // labels: props_data.monthly_expense_percentage.categories,
    labels: expenseLabelDetails,
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    tooltip: {

    },
    yaxis: {
        labels: {
            formatter: (val) => (val / 1).toFixed(2).replace(",", ".").toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    }
});

watch(expenseLabelDetails, (newLabels) => {
    chartOptionsExpenses.value = {
        ...chartOptionsExpenses.value,
        labels: newLabels
    };
});

function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

const getReOrderItems = async () => {
    try {
        const response = (await axios.get(route('products.rol'))).data;
        reOrderProducts.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getAllRolProducts = async () => {
    router.visit(route('products.rol.all', 2));
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
        businessDetails.value = response;

        if (businessDetails.value.data.currency_id) {
            select_currency.value.id = businessDetails.value.data.currency_id;
            select_currency.value.code = businessDetails.value.data.currency_name;
        }

    } catch (error) {
        console.error(error);
    }
};


const getSales = async () => {
    try {
        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = null;
        }
        const params = {
            currency_id: currency_value.value,
            year: selectedYear.value,
        };
        const response = (await axios.post(route('dashboard.sales.get'), params)).data;
        sales.value = Array.isArray(response) ? response : [];
    } catch (error) {
    }
};

const getExpenseChartData = async () => {
    try {
        // const currencyId = select_currency.value ? select_currency.value.id : null
        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = null;
        }

        const monthId = select_month.value ? select_month.value.id : null

        const params = {
            currency_id: currency_value.value,
            monthly_id: monthId,
        };

        const response = (await axios.post(route('dashboard.expensesChartData.get'), params)).data;
        expensesDetails.value = response.expenses;
        expenseLabelDetails.value = response.categories;

        allZero.value = expensesDetails.value.every(value => value === 0);
    } catch (error) {
    }
};

const getIncomes = async () => {
    try {
        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = null;
        }
        const params = {
            currency_id: currency_value.value,
            year: selectedYear.value,
        };
        const response = (await axios.post(route('dashboard.incomes.get'), params)).data;
        incomes.value = response;
    } catch (error) {
    }
};

const getExpenses = async () => {
    try {
        const monthId = select_month.value ? select_month.value.id : null

        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = null;
        }
        const params = {
            currency_id: currency_value.value,
            monthly_id: monthId,
            year: selectedYear.value,
        };
        const response = (await axios.post(route('dashboard.expenses.get'), params)).data;
        expenses.value = response;
    } catch (error) {
    }
};

const getTransactionBalance = async () => {
    try {
        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = 'null';
        }
        const response = await axios.get(route('dashboard.transactionBalance.get', currency_value.value));
        if (response.data.transactionBalance == null) {
            transactionBalance.value.amount = 0.00;
            transactionBalance.value.code = select_currency.value.code;
        } else {
            transactionBalance.value = response.data.transactionBalance;
        }

    } catch (error) {
    }
};

const getTotalTaxAmount = async () => {
    try {
        if (select_currency.value.id) {
            currency_value.value = select_currency.value.id;
        } else {
            currency_value.value = 'null';
        }
        const response = await axios.get(route('dashboard.totalTax.get', currency_value.value));
        if (response.data.totalTaxAmount == null) {
            totalTaxAmount.value.amount = 0.00;
            totalTaxAmount.value.code = select_currency.value.code;
        } else {
            totalTaxAmount.value.amount = response.data.totalTaxAmount;
            totalTaxAmount.value.code = select_currency.value.code;
        }

    } catch (error) {
    }
}

const setCurrentMonth = () => {
    try {
        const currentMonth = new Date().getMonth() + 1;
        select_month.value = months.find(month => month.id === currentMonth)
    } catch (error) {
    }
};

const getDashboardController = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        const response = await axios.get(route('dashboard.controller.get'));
        const data = response.data;

        Object.keys(dashboardItems).forEach((key, index) => {
            dashboardItems[key] = Boolean(data[index].status);
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

const totalOfSales = computed(() => {
    return sales.value.reduce((subTotal, sale) => {
        const amount = parseFloat(sale.toString().replace(/,/g, '')) || 0;
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfIncomes = computed(() => {
    return incomes.value.reduce((subTotal, income) => {
        const amount = parseFloat(income.toString().replace(/,/g, '')) || 0;
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

const totalOfExpenses = computed(() => {
    return expenses.value.reduce((subTotal, expense) => {
        const amount = parseFloat(expense.toString().replace(/,/g, '')) || 0;
        return subTotal + amount;
    }, 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

onMounted(() => {
    getTransactionBalance();
    getReOrderItems();
    getBusinessDetails();
    getCurrencies();
    getExpenseChartData();
    setCurrentMonth();
    getDashboardController();
    getTotalTaxAmount();
    setTimeout(() => {
        getSales();
        getIncomes();
        getExpenses();
    }, 1000);
});

watch(select_currency, (newValue) => {
    getExpenseChartData();
    getSales();
    getIncomes();
    getExpenses();
    getTransactionBalance();
    getTotalTaxAmount();
});

watch(select_month, (newValue) => {
    getExpenseChartData();
});

watch(selectedYear, (newValue) => {
    getSales();
    getIncomes();
    getExpenses();
});

const formattedNumber = (number) => {
    if (number == undefined || number == null) {
        return "";
    }
    return number.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true
    });
};

const openPerformanceChartModal = () => {
    $('#performanceChartModal').modal('show');
    getSales();
    getIncomes();
    getExpenses();
};

const weeklyModal = () => {
    targets.value.weekly_target = null;
    $('#weeklyModal').modal('show');
};

const monthlyModal = () => {
    targets.value.monthly_target = null;
    $('#monthlyModal').modal('show');
};

const yearlyModal = () => {
    targets.value.yearly_target = null;
    $('#yearlyModal').modal('show');
};

const formatLargeNumber = (num) => {
    num = parseFloat(num);

    let formattedNumber;
    if (num >= 1e12) {
        formattedNumber = (num / 1e12).toFixed(2) + 'T';
    } else if (num >= 1e9) {
        formattedNumber = (num / 1e9).toFixed(2) + 'B';
    } else if (num >= 1e6) {
        formattedNumber = (num / 1e6).toFixed(2) + 'M';
    } else {
        formattedNumber = num.toFixed(2);
    }

    const addCommas = (numberString) => {
        const parts = numberString.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return parts.join('.');
    };

    return addCommas(formattedNumber);
};

const viewProductSalesReport = () => {
    router.visit(route('report.productSales.index'));
}

const saveTargets = async () => {
    if (targets.value.monthly_target == '') {
        targets.value.monthly_target = null;
    }

    if (targets.value.weekly_target < 0 || targets.value.monthly_target < 0 || targets.value.yearly_target < 0) {
        errorMessage('The target field must be at least 0');
    } else if (targets.value.monthly_target == null) {
        errorMessage('The monthly target amount is required');
    } else if (targets.value.weekly_target > 1000000000 || targets.value.monthly_target > 1000000000 || targets.value.yearly_target > 1000000000) {
        errorMessage('The target field must not be greater than 1000000000');
    } else {
        const res = await axios.post(route('dashboard.targets.store'), targets.value);
        successMessage('Target amount updated successfully');
        targets.value = {};
        $('#weeklyModal').modal('hide');
        $('#monthlyModal').modal('hide');
        $('#yearlyModal').modal('hide');
        window.location.reload();
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
</script>

<style lang="css" scoped>
.currency-paragraph {
    max-height: 3em;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-header,
.card-body {
    flex-shrink: 0;
}

.card-title {
    flex-grow: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fs-1 {
    font-size: 1.50rem !important;
}

.fs-6 {
    font-size: 0.875rem !important;
}

.text-sm {
    font-size: 0.75rem !important;
}

@media (max-width: 576px) {
    .card-header .fs-1 {
        font-size: 1rem !important;
    }

    .card-header .fs-6 {
        font-size: 0.75rem !important;
    }
}

.custom-month-multiselect {
    text-align: center;
    width: 110px;
}

.custom-currency-multiselect {
    text-align: center;
    width: 80px;
}

.custom-year-multiselect {
    text-align: center;
    width: 80px;
    height: 36px;
}

.tick-color1 {
    color: #008ffb;
}

.tick-color2 {
    color: #00e396;
}

.tick-color3 {
    color: #ff4560;
}

.full-height {
    margin-top: 2vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.main-number-format {
    font-size: 20px;
}

.secondary-number-format {
    font-size: 12px;
}

.top-selling-product-card {
    height: 365px;
}
</style>
