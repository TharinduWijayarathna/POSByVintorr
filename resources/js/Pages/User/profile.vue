<template>
    <AppLayout title="User Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                                <h1 class="main-header-text">
                                    Update Your Profile
                                </h1>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <form @submit.prevent="updateUser" enctype="multipart/form-data">
                                    <div class="row">
                                        <!-- Left Column (Name and Email) -->
                                        <div class="col-md-6">
                                            <div class="text-gray-600 col-form-label">Name</div>
                                            <input v-model="edit_user.name" type="text"
                                                class="form-control form-control-sm" placeholder="Name" />
                                            <small v-if="validationErrors.name"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.name }}</small>

                                            <div class="text-gray-600 col-form-label">Email</div>
                                            <input v-model="edit_user.email" type="text"
                                                class="form-control form-control-sm" placeholder="Email"
                                                autocomplete="off" aria-autocomplete="off" />
                                            <small v-if="validationErrors.email"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.email }}</small>
                                        </div>

                                        <!-- Right Column (Password and Confirm Password) -->
                                        <div class="col-md-6">
                                            <div class="text-gray-600 col-form-label">Password</div>
                                            <input v-model="edit_user.password" type="password"
                                                class="form-control form-control-sm" placeholder="Password"
                                                autocomplete="off" aria-autocomplete="off" />
                                            <small v-if="validationErrors.password"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.password }}</small>

                                            <div class="text-gray-600 col-form-label">Confirm Password</div>
                                            <input v-model="edit_user.con_password" type="password" name="password"
                                                id="password" class="form-control form-control-sm"
                                                placeholder="Confirm Password" />
                                            <small v-if="validationErrors.con_password"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ validationErrors.con_password }}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mt-4 col-12 text-end">
                                            <button type="submit" class="mt-2 btn btn-light-info me-0"
                                                data-toggle="tooltip" data-placement="bottom" title="Save changes"
                                                style="font-weight: bold">
                                                UPDATE
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="mt-5 col-lg-12 mt-lg-5">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="-mb-2 col-12 d-flex justify-content-start align-items-center">
                                <h1 class="main-header-text">
                                    Your Login Activity
                                </h1>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">

                                <div class="mb-3 row align-items-center">

                                    <div v-if="sessions.length > 0"
                                        class="col-md-12 d-flex justify-content-end align-self-end">
                                        <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                            <label><select name="kt_ecommerce_products_table_length"
                                                    aria-controls="kt_ecommerce_products_table"
                                                    class="form-select form-select-sm form-select-solid" :value="2"
                                                    v-model="pageCount" @change="perPageChange">
                                                    <option v-for="perPageCount in perPage" :key="perPageCount"
                                                        :value="perPageCount" v-text="perPageCount" />
                                                </select></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Table -->
                                <!-- <div class="row" v-if="emptyImageVal == 1 && userStatus == 0 && search == null">
                                    <div class="text-center col-12 mt-15">
                                        <img src="../../../src/images/EmptyState/users.webp" height="200" />
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No any users</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">Your SparkPos users will appear here</p>
                                    </div>
                                </div>
                                <div class="row" v-if="emptyImageVal == 1 && (userStatus != 0 || search != null)">
                                    <div class="text-center col-12 mt-15">
                                        <img src="../../../src/images/EmptyState/empty-search-results.webp"
                                            height="200" />
                                    </div>
                                    <div class="mt-8 text-center col-12 mb-15">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No result found</h1>
                                    </div>
                                </div> -->
                                <div v-if="sessions.length > 0" class="row">
                                    <!-- Desktop View -->
                                    <div class="table-responsive d-none d-md-block">
                                        <div class="row gx-4 gy-4">
                                            <div v-for="session in sessions" :key="session.id"
                                                class="col-lg-4 col-md-6">
                                                <div class="shadow-sm card border-light">
                                                    <div class="p-4 card-body">
                                                        <div class="mb-3 d-flex align-items-center">

                                                            <i class="bi bi-laptop text-dark me-3"></i>

                                                            <h5 class="mb-0 card-title">Session Info</h5>
                                                        </div>
                                                        <div class="session-details">
                                                            <p><strong>IP Address:</strong> {{ session.ip_address }}</p>
                                                            <p><strong>User Agent:</strong> {{
                                                                truncateText(session.user_agent) }}</p>
                                                            <p><strong>Login At:</strong> {{
                                                                formatDate(session.login_at) }}</p>
                                                            <p><strong>Logout At:</strong>
                                                                <span v-if="session.logout_at != null">
                                                                    {{ formatDate(session.logout_at) }}
                                                                </span>
                                                                <span v-else>Currently logged in</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile View -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div class="accordion" id="sessionAccordion">
                                            <div v-for="(session, index) in sessions" :key="session.id"
                                                class="accordion-item">
                                                <h2 class="accordion-header" :id="'heading' + index">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" :data-bs-target="'#collapse' + index"
                                                        aria-expanded="false" :aria-controls="'collapse' + index">
                                                        Session - IP: {{ session.ip_address }}
                                                    </button>
                                                </h2>
                                                <div :id="'collapse' + index" class="accordion-collapse collapse"
                                                    :aria-labelledby="'heading' + index"
                                                    data-bs-parent="#sessionAccordion">
                                                    <div class="accordion-body">
                                                        <p><strong>User Agent:</strong> {{
                                                            truncateMobileText(session.user_agent) }}</p>
                                                        <p><strong>Login At:</strong> {{ formatDate(session.login_at) }}
                                                        </p>
                                                        <p><strong>Logout At:</strong>
                                                            <span v-if="session.logout_at != null">{{
                                                                formatDate(session.logout_at) }}</span>
                                                            <span v-else>Currently logged in</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <!-- Pagination -->
                                <div v-if="sessions.length > 0" class="my-3 row ps-1 ps-md-0">

                                    <div class="col-sm-6">
                                        <div for="purchase_uom" class="text-gray-600 col-form-label">
                                            Showing {{ pagination.from }} to
                                            {{ pagination.to }} of
                                            {{ pagination.total }} entries
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_previous"><a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)"
                                                        aria-controls="kt_ecommerce_sales_table" data-dt-idx="0"
                                                        tabindex="0" class="page-link"><i class="previous"></i></a></li>
                                                <template v-for="(page, index) in pagination.last_page">
                                                    <template
                                                        v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                                        <li class="paginate_button page-item" :key="index"
                                                            :class="pagination.current_page == page ? 'active' : ''"><a
                                                                href="javascript:void(0)" @click="setPage(page)"
                                                                aria-controls="kt_ecommerce_sales_table" data-dt-idx="1"
                                                                tabindex="0" class="page-link">{{ page }}</a></li>
                                                    </template>
                                                </template>
                                                <li class="paginate_button page-item next"
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_next"><a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page + 1)"
                                                        aria-controls="kt_ecommerce_sales_table" data-dt-idx="6"
                                                        tabindex="0" class="page-link"><i class="next"></i></a>
                                                </li>
                                            </ul>
                                        </div>
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
import { ref, onMounted, watch, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'

import Multiselect from "vue-multiselect";
import moment from 'moment';
import _ from 'lodash';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const users = ref([]);
const emptyImageVal = ref(0);
const edit_user = ref({});
const sessions = ref({});

const formatDate = (value) => {
    return moment(String(value)).format('DD/MM/YYYY HH:mm:ss');
};

const select_edit_user_role = ref(null);

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
    const { message } = error.response.data;

    errorMessage(message);
};

const convertValidationError = (err) => {
    resetValidationErrors(err);
    if (!(err.response && err.response.data)) return;
    const { message, errors } = err.response.data;
    validationMessage.value = message;
    if (errors) {
        for (const error in errors) {
            validationErrors.value[error] = errors[error][0];
        }
    }
};

const perPageChange = async () => {
    page.value = 1;
    getSessions();
};

const setPage = (new_page) => {
    page.value = new_page;
    getSessions();
};

const getSessions = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('get.sessions'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
            },
        })
    ).data;
    sessions.value = tableData.data;
    pagination.value = tableData.meta;
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getMyDetails = async (id) => {

    resetValidationErrors();
    try {
        const user = (await axios.get(route('myDetails.get', id))).data
        const userData = user.data;

        edit_user.value = {
            id: userData.id,
            name: userData.name,
            email: userData.email,
        }

    } catch (error) {
        convertValidationNotification(error);
    }

};

const updateUser = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();

    if (edit_user.value.password === '' || edit_user.value.password === undefined) {
        edit_user.value.password = null;
    }
    if (edit_user.value.con_password === '' || edit_user.value.con_password === undefined) {
        edit_user.value.con_password = null;
    }

    if (edit_user.value.password !== edit_user.value.con_password) {
        errorMessage("Passwords do not match");
        nextTick(() => {
            loading_bar.value.finish();
        });
        return;
    }

    try {

        const response = await axios.post(route('profile.update', edit_user.value.id), edit_user.value);
        if (response.data == "success") {
            getMyDetails();
            successMessage("Profile updated successfully");
        } else {
            errorMessage(response.data)
        }

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error)
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

const truncateMobileText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 25 ? text.substring(0, 25) + '...' : text;
    }
    return '';
};

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 50 ? text.substring(0, 50) + '...' : text;
    }
    return '';
};

onMounted(() => {
    getSessions();

    edit_user.value.email = '';
    edit_user.value.password = '';
    setTimeout(() => {
        getMyDetails(props.auth.user.id);
    }, 1000);
});

</script>

<style lang="scss" scoped>
.active-status {
    background-color: #DBFFE4;
    color: green;
}

.restore-btn {
    color: green;
}
</style>
