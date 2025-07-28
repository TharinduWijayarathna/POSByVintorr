<template>
    <AppLayout title="Settings">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                Settings;
                            </h1>
                        </div>

                        <div class="shadow card">
                            <div class="p-3 text-sm card-body p-md-4">
                                <!-- Desktop View -->
                                <div class="card-header d-none d-md-block">
                                    <ul
                                        class="border-transparent nav nav-stretch nav-line-tabs nav-line-tabs-2x fs-5 fw-bold">
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7" data-toggle="tooltip"
                                                data-placement="bottom" title="Business details"
                                                :class="[showBusinessDetails == 1 ? 'active' : '']"
                                                href="#business-details" @click="showBusinessDetailsTab">
                                                Business Details</a>
                                        </li>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                data-toggle="tooltip" data-placement="bottom" title="Bills & invoices"
                                                :class="[showBills == 1 ? 'active' : '']" href="#bill-template"
                                                @click="showBillTemplateTab">
                                                Bill Template</a>
                                        </li>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                data-toggle="tooltip" data-placement="bottom" title="Bills & invoices"
                                                :class="[showInvoices == 1 ? 'active' : '']" href="#invoice-template"
                                                @click="showInvoiceTemplateTab">
                                                Invoice Template</a>
                                        </li>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                data-toggle="tooltip" data-placement="bottom"
                                                title="Dashboard configurations"
                                                :class="[dashboard == 1 ? 'active' : '']" href="#dashboard"
                                                @click="showDashboardTab">
                                                Dashboard</a>
                                        </li>
                                        <div class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                data-toggle="tooltip" data-placement="bottom" title="Unit of measures"
                                                :class="[showUOM == 1 ? 'active' : '']" href="#UOM" @click="showUOMTab">
                                                Unit Of Measurements</a>
                                        </div>
                                        <div class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                data-toggle="tooltip" data-placement="bottom" title="Tax Registry"
                                                :class="[showTaxRegistry == 1 ? 'active' : '']" href="#TaxRegistry"
                                                @click="showTaxRegistryTab">
                                                Tax Registry</a>
                                        </div>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                :class="[showMonthlyReport == 1 ? 'active' : '']" href="#monthly-report"
                                                data-toggle="tooltip" data-placement="bottom" title="Monthly report"
                                                @click="showMonthlyReportTab">
                                                Monthly Reports</a>
                                        </li>
                                        <li class="mt-2 nav-item">
                                            <a class="py-5 nav-link text-active-primary ms-0 me-7 "
                                                :class="[showAccountReset == 1 ? 'active' : '']" href="#account-reset"
                                                data-toggle="tooltip" data-placement="bottom" title="Reset account data"
                                                @click="showAccountResetTab">
                                                Account Reset</a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Mobile View -->
                                <div class="row horizontal-btn-scroller d-flex d-md-none">
                                    <div class="mt-1 mb-1 col-5 business-d-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5" data-toggle="tooltip"
                                            data-placement="bottom" title="Business details"
                                            :class="[showBusinessDetails == 1 ? 'active' : '']" href="#business-details"
                                            @click="showBusinessDetailsTab">
                                            Business Details</a>
                                    </div>
                                    <div class="mt-1 mb-1 text-center col-5 bill-template-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 " data-toggle="tooltip"
                                            data-placement="bottom" title="Bills & invoices"
                                            :class="[showBills == 1 ? 'active' : '']" href="#bill-template"
                                            @click="showBillTemplateTab">
                                            Bill Template</a>
                                    </div>
                                    <div class="mt-1 mb-1 text-center col-5 invoice-template-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 " data-toggle="tooltip"
                                            data-placement="bottom" title="Bills & invoices"
                                            :class="[showInvoices == 1 ? 'active' : '']" href="#invoice-template"
                                            @click="showInvoiceTemplateTab">
                                            Invoice Template</a>
                                    </div>
                                    <div class="mt-1 mb-1 text-center col-5 dashboard-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 " data-toggle="tooltip"
                                            data-placement="bottom" title="Dashboard configurations"
                                            :class="[dashboard == 1 ? 'active' : '']" href="#dashboard"
                                            @click="showDashboardTab">
                                            Dashboard</a>
                                    </div>
                                    <div class="mt-1 mb-1 text-center col-5 uom-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 " data-toggle="tooltip"
                                            data-placement="bottom" title="Unit of measures"
                                            :class="[showUOM == 1 ? 'active' : '']" href="#UOM" @click="showUOMTab">
                                            Unit Of Measurements</a>
                                    </div>
                                    <div class="mt-1 mb-1 text-center col-5 tax-registry-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 " data-toggle="tooltip"
                                            data-placement="bottom" title="Tax Registry"
                                            :class="[showTaxRegistry == 1 ? 'active' : '']" href="#TaxReshowTaxRegistry"
                                            @click="showTaxRegistryTab">
                                            Tax Registry</a>
                                    </div>
                                    <div class="mt-1 mb-1 col-5 monthly-report-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 "
                                            :class="[showMonthlyReport == 1 ? 'active' : '']" href="#monthly-report"
                                            data-toggle="tooltip" data-placement="bottom" title="Monthly report"
                                            @click="showMonthlyReportTab">
                                            Monthly Reports</a>
                                    </div>
                                    <div class="mt-1 mb-1 col-5 monthly-report-width">
                                        <a class="py-5 nav-link text-active-primary ms-0 fs-5 "
                                            :class="[showAccountReset == 1 ? 'active' : '']" href="#account-reset"
                                            data-toggle="tooltip" data-placement="bottom" title="Monthly report"
                                            @click="showAccountResetTab">
                                            Account Reset</a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 text-sm card-body p-md-2 ">
                                <div>
                                    <div class="p-2" :class="[showBusinessDetails == 1 ? 'd-block' : 'd-none']"
                                        id="business-details">
                                        <BusinessDetails />
                                    </div>
                                    <div class="p-2" :class="[showBills == 1 ? 'd-block' : 'd-none']"
                                        id="bill-template">
                                        <BillsAndInvoices />
                                    </div>
                                    <div class="p-2" :class="[showInvoices == 1 ? 'd-block' : 'd-none']"
                                        id="invoice-template">
                                        <InvoiceTemplates />
                                    </div>
                                    <div class="p-2" :class="[dashboard == 1 ? 'd-block' : 'd-none']" id="dashboard">
                                        <dashboard />
                                    </div>
                                    <div class="p-2" :class="[showUOM == 1 ? 'd-block' : 'd-none']" id="UOM">
                                        <UOM />
                                    </div>
                                    <div class="p-2" :class="[showTaxRegistry == 1 ? 'd-block' : 'd-none']"
                                        id="TaxRegistry">
                                        <TaxRegistry />
                                    </div>
                                    <div class="p-2" :class="[showMonthlyReport == 1 ? 'd-block' : 'd-none']"
                                        id="monthly-report">
                                        <MonthlyReport />
                                    </div>
                                    <div class="p-2" :class="[showAccountReset == 1 ? 'd-block' : 'd-none']"
                                        id="account-reset">
                                        <AccountReset />
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import Loader from '@/Components/Basic/LoadingBar.vue';

import BillsAndInvoices from '@/Pages/Configuration/Components/BillsAndInvoices/All.vue';
import InvoiceTemplates from '@/Pages/Configuration/Components/InvoiceTemplates/All.vue';
import BusinessDetails from '@/Pages/Configuration/Components/BusinessDetails/BusinessDetails.vue';
import MonthlyReport from '@/Pages/Configuration/Components/MonthlyReport/All.vue';
import Dashboard from '@/Pages/Configuration/Components/Dashboard/All.vue';
import UOM from '@/Pages/Configuration/Components/UOM/All.vue';
import TaxRegistry from '@/Pages/Configuration/Components/TaxRegistry/All.vue';
import AccountReset from '@/Pages/Configuration/Components/AccountReset/All.vue';

const showBusinessDetails = ref(1);
const showBills = ref(0);
const showInvoices = ref(0);
const showPublicSite = ref(0);
const showMonthlyReport = ref(0);
const dashboard = ref(0);
const showUOM = ref(0);
const showTaxRegistry = ref(0);
const showAccountReset = ref(0);

function showBusinessDetailsTab() {
    showBusinessDetails.value = 1;
    showBills.value = 0;
    showInvoices.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    showAccountReset.value = 0;
}

function showBillTemplateTab() {
    showBills.value = 1;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    showAccountReset.value = 0;
}

function showInvoiceTemplateTab() {
    showBills.value = 0;
    showInvoices.value = 1;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    showAccountReset.value = 0;
}

function showMonthlyReportTab() {
    showBills.value = 0;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 1;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    showAccountReset.value = 0;
}

function showDashboardTab() {
    showBills.value = 0;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    dashboard.value = 1;
    showAccountReset.value = 0;
}

function showUOMTab() {
    showBills.value = 0;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 1;
    showTaxRegistry.value = 0;
    showAccountReset.value = 0;
}

function showTaxRegistryTab() {
    showBills.value = 0;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 1;
    showAccountReset.value = 0;
}

function showAccountResetTab() {
    showBills.value = 0;
    showInvoices.value = 0;
    showBusinessDetails.value = 0;
    showPublicSite.value = 0;
    showMonthlyReport.value = 0;
    dashboard.value = 0;
    showUOM.value = 0;
    showTaxRegistry.value = 0;
    showAccountReset.value = 1;
}
</script>

<style lang="css" scoped>
.card-header {
    min-height: 50px;
}

</style>
