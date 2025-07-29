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
                id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="background-color: #091f3e;">
                <div class="offcanvas-header">
                    <img alt="Logo" src="../../../src/logo/logo.webp" class="ml-1 h-35px" />
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Dashboard -->
                    <Link :href="route('dashboard')">
                    <div class="p-3 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/dashboard') }">
                        <span class="menu-link d-flex align-items-center">
                            <span>
                                <i class="mr-3 bi bi-columns-gap fs-3x" style="color: #fff;"></i>
                            </span>
                            <span class="menu-title-copy fs-2x" style="color: #fff;">Dashboard</span>
                        </span>
                    </div>
                    </Link>

                    <!-- Cart -->
                    <Link v-if="props.auth.user.user_role_id != 3" :href="route('cart','fullscreen')">
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/order') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/saved-order') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/returns') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/bills/view') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/invoice') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/quotation') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/products') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/customer') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/supplier') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/expenses') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/employee') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/payrolls') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/transaction') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/user') }">
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
                    <div class="p-3 mt-2 mb-2 col-12 nav-link-custom" :class="{ 'active-color': $page.url.startsWith('/report') }">
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
                        <div class="px-3 btn btn-icon btn-active-light-primary w-100 h-20px h-md-30px d-flex align-items-center"
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

    <Loader ref="loading_bar" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';

const { props } = usePage();

const userHasRole = computed(() => (roleName) => {
    return props.auth.user.role_name === roleName;
});

const currentDate = ref(formatDate(new Date()));
const currentTime = ref(formatTime(new Date()));

const bandwidth = ref(0);
const isLoading = ref(true);

const cal_input = ref("");
const resultDisplayed = ref(false);

const feedbackData = ref({});
const loading_bar = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const feedbackOption = ref([
    { id: '1', name: 'An idea to share' },
    { id: '2', name: 'A feedback to give' },
    { id: '3', name: 'An issue to report' },
    { id: '4', name: 'A question to ask' }
]);

const getIconClass = (id) => {
    switch (id) {
        case '1':
            return 'bi bi-lightbulb text-dark icon-setting';
        case '2':
            return 'bi bi-chat-dots text-dark icon-setting';
        case '3':
            return 'bi bi-exclamation-triangle text-dark icon-setting';
        case '4':
            return 'bi bi-question-circle text-dark icon-setting';
        default:
            return '';
    }
};

setInterval(() => {
    currentDate.value = formatDate(new Date());
    currentTime.value = formatTime(new Date());
}, 1000);

function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}

function formatTime(date) {
    let hours = date.getHours();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12; // Convert to 12-hour format
    hours = String(hours).padStart(2, '0'); // Ensure two digits
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${hours}:${minutes}:${seconds} ${ampm}`;
}

const showDevModal = () => {
    feedbackData.value = {};
    $('#contactDevModal').modal('show');
};

const submitFeedback = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (feedbackData.value.type?.id) {
            feedbackData.value.type = feedbackData.value.type?.id;
        }
        const response = await axios.post(route('user.feedback.send'), feedbackData.value);
        nextTick(() => {
            loading_bar.value.finish();
        });
        $('#contactDevModal').modal('hide');
        successMessage('Feedback send successfully');

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

onMounted(() => {

    if (navigator.connection) {

        bandwidth.value = navigator.connection.downlink.toFixed(2);
        isLoading.value = false;

        navigator.connection.addEventListener('change', () => {
            bandwidth.value = navigator.connection.downlink.toFixed(2);
        });
    } else {
        props.connectionType = 'Unknown';
        bandwidth.value = 0;
        isLoading.value = false;
    }

});

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
        html: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 8000,
        timerProgressBar: true,
    });
};
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
.calculator {
    background-color: #f0f0f0;
    padding: 20px;
    border-radius: 10px;
}

.cal_input {
    border: 2px solid #091f3e;
    border-radius: 5px;
    height: 60px;
    text-align: right;
    font-size: 2.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border .2s ease-in-out, box-shadow .2s ease-in-out;
}

.cal_input:hover {
    border: 2px solid #091f3e;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.numbers div,
.operators div {
    background-color: #ffffff;
    /* Clean white background */
    border: 2px solid #ddd;
    /* Light border for contrast */
    border-radius: 10px;
    /* Slightly larger radius for rounded corners */
    text-align: center;
    cursor: pointer;
    font-size: 2rem;
    /* Larger font size for better readability */
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Subtle shadow for depth */
    transition: all 0.3s ease-in-out;
    /* Smooth transition for background, border, and shadow */
}

.numbers div:hover,
.operators div:hover {
    background-color: #f7f7f7;
    /* Lighter hover background */
    border-color: #888;
    /* Slightly darker border */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    /* Stronger shadow on hover for depth */
    transform: translateY(-2px);
    /* Subtle lift effect */
}

.numbers div:active,
.operators div:active {
    background-color: #e0e0e0;
    /* Darker background on click */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    /* Reduced shadow on click for pressed effect */
    transform: translateY(2px);
    /* Slight pressed effect */
}

div.equal {
    background-color: #091f3e;
    /* Vibrant green for the equal button */
    border: 2px solid #091f3e;
    /* Matching border color */
    border-radius: 10px;
    /* Smoother rounded corners */
    text-align: center;
    cursor: pointer;
    color: #FFF;
    /* White text for contrast */
    font-size: 2rem;
    /* Larger font size for readability */
    padding: 15px;
    /* Adequate padding for better click area */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Soft shadow for depth */
    transition: all 0.3s ease-in-out;
    /* Smooth transition for hover and active states */
}

div.equal:hover {
    background-color: #007B2F;
    /* Darker green on hover */
    border-color: #007B2F;
    /* Darker border on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    /* Stronger shadow on hover */
    transform: translateY(-2px);
    /* Lift effect for hover */
}

div.equal:active {
    background-color: #005d27;
    /* Even darker green when clicked */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    /* Lighter shadow when pressed */
    transform: translateY(2px);
    /* Slight pressed effect */
}


.border-top {
    border-top: 1px solid #fff !important;
}

.main-account-alert {
    font-size: medium;
}

@media (max-width: 768px) {
    .nav-items {
        margin-right: 25px;
    }
}
</style>
