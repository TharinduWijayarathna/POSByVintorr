<template>
    <div id="kt_aside" class="pt-5 pb-5 overflow-visible aside pt-lg-0" data-kt-drawer="true"
        data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'80px', '300px': '100px'}" data-kt-drawer-direction="start"
        data-kt-drawer-toggle="#kt_aside_mobile_toggle">
        <div class="mt-2 mb-2 aside-logo flex-column-auto" id="kt_aside_logo">
            <a :href="route('dashboard')">
                <img alt="Logo" src="../../../src/logo/logo.webp" class="h-80px logo" />
            </a>
        </div>

        <div class="aside-menu flex-column-fluid" id="kt_aside_menu">
            <!--begin::Aside Menu-->
            <div class="pb-20 my-2 hover-scroll-y side-scroller my-lg-5 scroll-ms" id="kt_aside_menu_wrapper"
                data-kt-scroll="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
                data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="5px">
                <div class="pb-20 menu menu-column menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold"
                    data-kt-menu="true">

                    <!-- Dashboard Icon -->
                    <Link :href="route('dashboard')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/dashboard') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/dashboard') }">
                        <span class="menu-icon-copy">
                            <i class="bi bi-speedometer2 me-3"></i><span class="menu-title-copy">Dashboard</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Cart Icon -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('cart', 'sidebar')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/order') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/order') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-basket3 me-3"></i><span class="menu-title-copy">Cart</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Hold Icon -->
                    <Link :href="route('cart.hold.index')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/saved-order') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/saved-order') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-clipboard2-check me-3"></i><span class="menu-title-copy">Hold</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Returns Icon -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('return.view')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/returns') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/returns') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-arrow-repeat me-3"></i><span class="menu-title-copy">Returns</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Bills Icon -->
                    <Link :href="route('receipt.index')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/bills/view') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/bills/view') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-cash-stack me-3"></i><span class="menu-title-copy">Bills</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Credit Bills Icon -->
                    <Link :href="route('receipt.credit')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/bills/credit') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/bills/credit') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-wallet2 me-3"></i><span class="text-center menu-title-copy">Credit
                                Bills</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Invoices Icon -->
                    <Link :href="route('invoice.all.view')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/invoice') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/invoice') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-journal-check me-3"></i><span class="menu-title-copy">Invoices</span>
                        </span>
                    </span>
                    </Link>


                    <div id="hidden-div-two"></div>

                    <!-- Quotations Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('quotation.all.view')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/quotation') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/quotation') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-file-text me-3"></i><span class="menu-title-copy">Quotations</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Purchase Orders Icon (PO) -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('purchaseOrder.all.view')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/purchase-order') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/purchase-order') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-journal-arrow-up me-3"></i><span class="menu-title-copy">Purchase
                                Order</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Products Icon -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('products.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/products') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/products') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-bar-chart me-3"></i><span class="menu-title-copy">Products</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Customers Icon -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('customer.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/customer') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/customer') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-person-circle me-3"></i><span class="menu-title-copy">Customers</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Suppliers Icon -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('supplier.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/supplier') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/supplier') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-truck me-3"></i><span class="menu-title-copy">Suppliers</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Expenses Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('expenses.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/expenses') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/expenses') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-wallet-fill me-3"></i><span class="menu-title-copy">Expenses</span>
                        </span>
                    </span>
                    </Link>


                    <!-- Employees Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('employee.index')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/employee') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/employee') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-people-fill me-3"></i><span class="menu-title-copy">Employees</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Payrolls Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('payrolls.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/payrolls') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/payrolls') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-currency-dollar me-3"></i><span class="menu-title-copy">Payrolls</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Transactions Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('transaction.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/transaction') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/transaction') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-bank2 me-3"></i><span class="menu-title-copy">Transactions</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Users Icon -->
                    <Link v-if="props.auth.user.user_role_id == 1" :href="route('user.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/user') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/user') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-people me-3"></i><span class="menu-title-copy">Users</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Reports Icon -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('report.index')" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start" class="py-1 menu-item here show"
                        :class="{ active: $page.url.startsWith('/report') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/report') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-bar-chart-line-fill me-3"></i><span class="menu-title-copy">Reports</span>
                        </span>
                    </span>
                    </Link>

                    <!-- Settings Icon -->
                    <Link v-if="props.auth.user.user_role_id == 1" :href="route('configuration.index')"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-1 menu-item here show" :class="{ active: $page.url.startsWith('/configuration') }">
                    <span class="menu-link menu-center" :class="{ active: $page.url.startsWith('/configuration') }">
                        <span class="menu-icon-copy me-0">
                            <i class="bi bi-sliders me-3"></i><span class="menu-title-copy">Settings</span>
                        </span>
                    </span>
                    </Link>


                    <div id="hidden-div"></div>
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Aside Menu-->
        </div>
        <!--end::Aside menu-->
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();

const userHasRole = computed(() => (roleName) => {
    return props.auth.user.role_name === roleName;
});

const scrollToDiv = async () => {
    const element = document.getElementById("hidden-div");
    if (element) {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
    }
};

const scrollToDivTwo = async () => {
    const element = document.getElementById("hidden-div-two");
    if (element) {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
    }
};

onMounted(() => {
    const url = window.location.href; // Get the full URL
    if (
        url.includes("/expenses") ||
        url.includes("/employee") ||
        url.includes("/payrolls") ||
        url.includes("/transaction") ||
        url.includes("/user") ||
        url.includes("/report") ||
        url.includes("/configuration")
    ) {
        setTimeout(() => {
            scrollToDiv();
        }, 1000);
    }
    if (
        url.includes("/purchase-order") ||
        url.includes("/products") ||
        url.includes("/customer") ||
        url.includes("/supplier")
    ) {
        setTimeout(() => {
            scrollToDivTwo();
        }, 1000);
    }
});
</script>

<style lang="css" scoped>
.side-scroller {
    height: 100vh;
    overflow-y: scroll;
}

.side-scroller::-webkit-scrollbar {
    display: none;
}

.no-drop {
    cursor: no-drop;
}

/* Remove all uppercase text and ensure normal case */
.menu-title-copy {
    text-transform: none !important;
}

/* Ensure all text in sidebar is normal case */
#kt_aside * {
    text-transform: none !important;
}
</style>
