<template>
    <div id="kt_header" style="" class="header align-items-stretch navbar-custom-bg">

        <!--begin::Container-->
        <div class=" container-fluid d-flex align-items-stretch justify-content-between">


            <!--begin::Aside mobile toggle-->
            <div class="d-flex align-items-center d-lg-none ms-n1 me-2" title="Show aside menu">
                <div class="btn btn-icon btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                    id="kt_aside_mobile_toggle" style="border: 1px solid #fff;">
                    <i class="bi bi-list fs-3x" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                        aria-controls="offcanvasWithBothOptions" style="color: #fff;"></i>
                </div>
            </div>

            <div class="offcanvas offcanvas-start w-75" data-bs-scroll="true" data-bs-backdrop="static" tabindex="-1"
                id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel"
                style="background-color: #091f3e;">
                <div class="offcanvas-header">
                    <img alt="Logo" src="../../../src/logo/logo.webp" class="ml-1 h-35px" />
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                        style="filter: invert(1);"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Dashboard -->
                    <Link :href="route('dashboard')">
                    <div class="p-3 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/dashboard') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-columns-gap fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Dashboard</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Cart -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('cart', 'fullscreen')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/order') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-cart fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Cart</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Hold -->
                    <Link :href="route('cart.hold.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/saved-order') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-cart-plus fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Hold</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Return -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('return.view')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/returns') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-arrow-repeat fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Returns</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Bills -->
                    <Link :href="route('receipt.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/bills/view') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-receipt fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Bills</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Credit Bills -->
                    <Link :href="route('receipt.credit')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/bills/credit') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-hourglass fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Credit Bills</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Invoices -->
                    <Link :href="route('invoice.all.view')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/invoice') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-file-earmark-ruled fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Invoices</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Quotations -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('quotation.all.view')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/quotation') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-chat-left-quote fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Quotations</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Purchase Orders -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('purchaseOrder.all.view')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/purchase-order') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-file-earmark-arrow-up fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Purchase Orders</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Products -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('products.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/products') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-box2 fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Products</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Customers -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('customer.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/customer') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-people fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Customers</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Suppliers -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('supplier.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/supplier') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-briefcase fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Suppliers</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Expenses -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('expenses.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/expenses') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-cash-stack fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Expenses</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Employees -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('employee.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/employee') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-person-page_props fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Employees</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Payrolls -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('payrolls.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/payrolls') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-wallet2 fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Payrolls</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Transactions -->
                    <Link v-if="props.auth.user.user_role_id != 2" :href="route('transaction.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/transaction') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-arrow-down-up fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Transactions</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Users -->
                    <Link v-if="props.auth.user.user_role_id == 1" :href="route('user.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/user') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-person fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Users</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Reports -->
                    <Link v-if="props.auth.user.user_role_id != 2 && props.auth.user.user_role_id != 3"
                        :href="route('report.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/report') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-graph-up-arrow fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Reports</span>
                        </span>
                    </div>
                    </Link>


                    <!-- Settings -->
                    <Link v-if="props.auth.user.user_role_id == 1" :href="route('configuration.index')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom"
                        :class="{ 'active-color': $page.url.startsWith('/configuration') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-gear fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Settings</span>
                        </span>
                    </div>
                    </Link>

                </div>
            </div>
            <!--end::Aside mobile toggle-->

            <!--begin::Mobile logo-->
            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                <!-- <a href="/cart" class="d-lg-none">
                    <img alt="Logo" src="../../../src/logo/logo.webp" class="h-25px" />
                </a> -->
            </div>
            <!--end::Mobile logo-->

            <!--begin::Wrapper-->
            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                <!--begin::Navbar-->
                <div class="d-flex align-items-stretch" id="kt_header_nav">

                    <!--begin::Menu wrapper-->
                    <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                        data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                        data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
                        data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                        data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                        <!--begin::Menu-->
                    </div>

                </div>

            </div>
            <div class="space-x-4 d-flex align-items-center">

                <div class="d-flex align-items-center flex-grow-1 justify-content-center">
                    <div class="d-flex align-items-center">
                        <!-- User name and icon now wrapped in a div with border and padding -->
                        <div class="px-3 btn btn-icon w-100 h-20px h-md-30px d-flex align-items-center user-profile-btn"
                            data-bs-toggle="dropdown" data-kt-menu-placement="bottom-end"
                            style="border: 1px solid #fff; border-radius: 5px; padding: 5px 15px; background: transparent;">
                            <!-- User Icon -->
                            <i class="mr-2 bi bi-person fs-4 d-none d-sm-inline" style="color: #fff"></i>
                            <i class="mr-2 bi bi-person fs-5 d-inline d-sm-none" style="color: #fff;"></i>
                            <!-- User Name -->
                            <span class="ml-2 ms-1" style="color: #fff;">{{ props.auth.user.name }}</span>
                        </div>

                        <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-200px"
                            data-kt-menu="true">
                            <!-- Added icons to the dropdown items -->
                            <div class="text-gray-500 menu-item ps-8 pe-6">
                                {{ props.auth.user.email }}
                            </div>
                            <div class="px-5 menu-item border-top">
                                <!-- My Profile with icon -->
                                <Link :href="route('profile')" method="get" as="button"
                                    class="mt-2 profile-dropdown-item profile-button rounded-2 btn-active-light-primary">
                                <i class="bi bi-person-circle fs-5 me-2" style="color: #287fff;"></i> My Profile
                                </Link>

                                <!-- Log Out with icon -->
                                <Link :href="route('logout')" method="post" as="button"
                                    class="mt-2 logout-dropdown-item rounded-2">
                                <i class="bi bi-box-arrow-right fs-5 me-2 text-danger"></i> Logout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const userHasRole = computed(() => (roleName) => {
    return props.auth.user.role_name === roleName;
});
</script>

<style lang="scss" scoped>
.navbar-custom-bg {
    background-color: #091f3e !important;
}

.nav-link-custom {
    border: 1px solid #fff;
    border-radius: 5px;
    color: #fff !important;
    background: transparent !important;
    transition: background 0.2s, color 0.2s;
}

.nav-link-custom .menu-title-copy,
.nav-link-custom .bi {
    color: #fff !important;
}

.nav-link-custom.active-color {
    background-color: #193a6a !important;
    color: #fff !important;
}

.active-color {
    background-color: #193a6a !important;
    color: #fff !important;
}

.border-top {
    border-top: 1px solid #fff !important;
}

.user-profile-btn {
    transition: all 0.3s ease;
}

.user-profile-btn:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.8) !important;
}

.user-profile-btn:hover i,
.user-profile-btn:hover span {
    color: #fff !important;
}

@media (max-width: 768px) {
    .nav-items {
        margin-right: 25px;
    }
}
</style>
