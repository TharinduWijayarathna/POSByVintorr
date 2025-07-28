<template>
    <AppLayout title="Manage System Users">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                                <h1 class="main-header-text">
                                    Manage System Users
                                </h1>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                                <button class="btn btn-info fw-bold" data-toggle="tooltip" data-placement="bottom"
                                    title="Add new user" style="font-weight: bold" @click="openNewModal">
                                    <i class="bi bi-plus-lg"></i> Add User
                                </button>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">

                                <!-- Desktop Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">
                                        </div>
                                        <select class="form-select form-select-sm" v-model="userStatus"
                                            data-control="select2" data-placeholder="Select an option"
                                            @change="getSearch">
                                            <option value="0" disabled selected>Select by Status</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-md-2 mb-md-0">
                                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">
                                        </div>
                                        <input v-model="search" type="text" class="form-control form-control-sm"
                                            placeholder="Search by Name" @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="col-md-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2 col-md-5 mb-md-0"></div>

                                </div>

                                <!-- Mobile Filters -->
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openUserFilterModal">
                                                <i class="bi bi-funnel"></i> <!-- Funnel icon for filtering -->
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </div>
                                        <div v-if="users.length > 0">
                                            <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                                <label>
                                                    <select name="kt_ecommerce_products_table_length"
                                                        aria-controls="kt_ecommerce_products_table"
                                                        class="form-select form-select-sm form-select-solid" :value="2"
                                                        v-model="pageCount" @change="perPageChange">
                                                        <option v-for="perPageCount in perPage" :key="perPageCount"
                                                            :value="perPageCount" v-text="perPageCount"></option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State for Users -->
                                <div class="text-center row"
                                    v-if="emptyImageVal == 1 && userStatus == 0 && search == null">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-people fs-1"></i>
                                        <!-- You can replace the icon with any appropriate one -->
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No Users Found</h1>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">Your users will appear here once added.</p>
                                    </div>
                                </div>

                                <!-- Empty Search Results State -->
                                <div class="text-center row"
                                    v-if="emptyImageVal == 1 && (userStatus != 0 || search != null)">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                        <!-- Search icon for no results -->
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No Results Found</h1>
                                    </div>
                                    <div class="mt-2 text-center col-12">
                                        <p class="text-gray-700 fs-3">Try adjusting your search filters.</p>
                                    </div>
                                </div>

                                <div v-if="users.length > 0" class="row">

                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">User Status</th>
                                                    <th>Name</th>
                                                    <th>Email Address</th>
                                                    <th>User Role</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="user in users" :key="user.id">
                                                    <!-- User Status -->
                                                    <td class="py-2 ps-3 text-start">
                                                        <span v-if="user.deleted_at != null"
                                                            class="badge badge-light-danger">Inactive</span>
                                                        <span v-if="user.deleted_at == null"
                                                            class="badge badge-light-success active-status">Active</span>
                                                    </td>

                                                    <!-- Name -->
                                                    <td class="py-2">{{ truncateText(user.name) }}</td>

                                                    <!-- Email Address -->
                                                    <td class="py-2">{{ truncateText(user.email) }}</td>

                                                    <!-- User Role -->
                                                    <td class="py-2">
                                                        <span v-if="user.user_role_id == 1">Administrator</span>
                                                        <span v-if="user.user_role_id == 2">Cashier</span>
                                                        <span v-if="user.user_role_id == 3">Auditor</span>
                                                        <span v-if="user.user_role_id == 4">Inspector</span>
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Edit Button -->
                                                            <button v-if="user.deleted_at == null"
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Edit User" @click="editUser(user.id)">
                                                                <i
                                                                    class="p-2 bi bi-pencil-square text-dark-square fs-5 text-dark"></i>
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <button v-if="user.deleted_at == null"
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Delete User" @click="deleteUser(user.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </button>

                                                            <!-- Restore Button -->
                                                            <button v-if="user.deleted_at != null"
                                                                class="border btn btn-sm border-info text-info d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Restore User" @click="restoreUser(user.id)">
                                                                <i
                                                                    class="p-2 bi bi-arrow-clockwise fs-5 restore-btn"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <table class="table table-bordered table-striped fs-6">
                                            <tbody>
                                                <tr v-for="user in users" :key="user.id" class="border-bottom">
                                                    <!-- User Status -->
                                                    <td class="text-gray-400 text-uppercase">Status</td>
                                                    <td class="text-end">
                                                        <span v-if="user.deleted_at != null"
                                                            class="badge badge-danger">Inactive</span>
                                                        <span v-if="user.deleted_at == null"
                                                            class="badge badge-info">Active</span>
                                                    </td>
                                                </tr>
                                                <tr v-for="user in users" :key="'name-' + user.id"
                                                    class="border-bottom">
                                                    <!-- User Name -->
                                                    <td class="text-gray-400 text-uppercase">Name</td>
                                                    <td class="text-end">{{ truncateText(user.name) }}</td>
                                                </tr>
                                                <tr v-for="user in users" :key="'email-' + user.id"
                                                    class="border-bottom">
                                                    <!-- User Email -->
                                                    <td class="text-gray-400 text-uppercase">Email</td>
                                                    <td class="text-end">{{ truncateText(user.email) }}</td>
                                                </tr>
                                                <tr v-for="user in users" :key="'role-' + user.id"
                                                    class="border-bottom">
                                                    <!-- User Role -->
                                                    <td class="text-gray-400 text-uppercase">Role</td>
                                                    <td class="text-end">
                                                        <span v-if="user.user_role_id == 1"
                                                            class="badge badge-success">Admin</span>
                                                        <span v-if="user.user_role_id == 2"
                                                            class="badge badge-success">Cashier</span>
                                                        <span v-if="user.user_role_id == 3"
                                                            class="badge badge-success">Audit</span>
                                                        <span v-if="user.user_role_id == 4"
                                                            class="badge badge-success">Inspector</span>
                                                    </td>
                                                </tr>
                                                <tr v-for="user in users" :key="'actions-' + user.id"
                                                    class="border-bottom">
                                                    <!-- Actions -->
                                                    <td class="text-gray-400 text-uppercase">Actions</td>
                                                    <td class="text-end">
                                                        <a v-if="user.deleted_at == null" href="javascript:void(0)"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Edit user" class="p-2 text-primary"
                                                            @click="editUser(user.id)">
                                                            <i class="bi bi-pencil-square text-dark fs-4"></i>
                                                        </a>
                                                        <a v-if="user.deleted_at == null" href="javascript:void(0)"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Delete user" class="p-2 text-danger"
                                                            @click="deleteUser(user.id)">
                                                            <i class="bi bi-trash-fill fs-4 text-danger"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div v-if="users.length > 0" class="my-3 row align-items-center">
                                    <!-- Entries Dropdown -->
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <label class="mb-0 me-2">Show</label>
                                        <select name="kt_ecommerce_products_table_length"
                                            aria-controls="kt_ecommerce_products_table"
                                            class="w-auto mx-1 form-select form-select-sm form-select-solid"
                                            v-model="pageCount" @change="perPageChange">
                                            <option v-for="perPageCount in perPage" :key="perPageCount"
                                                :value="perPageCount">
                                                {{ perPageCount }}
                                            </option>
                                        </select>
                                        <label class="mb-0 ms-2">entries</label>
                                    </div>
                                    <!-- Pagination Info and Controls -->
                                    <div class="col-sm-6 d-flex justify-content-end align-items-center">
                                        <!-- Pagination Info -->
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>
                                        <!-- Pagination Controls -->
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="mb-0 pagination">
                                                <!-- Previous Button -->
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)" class="page-link">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                                <!-- Page Numbers -->
                                                <template v-for="(page, index) in pagination.last_page">
                                                    <template
                                                        v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                                        <li class="paginate_button page-item" :key="index"
                                                            :class="pagination.current_page == page ? 'active' : ''">
                                                            <a href="javascript:void(0)" @click="setPage(page)"
                                                                class="page-link">{{ page }}</a>
                                                        </li>
                                                    </template>
                                                </template>
                                                <!-- Next Button -->
                                                <li class="paginate_button page-item next"
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page + 1)" class="page-link">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
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

        <template #modal>
            <!-- User Filter Modal -->
            <div class="modal fade" id="userFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="userFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Filter System Users</h5>
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
                                        <select id="modalOrderStatus"
                                            class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="userStatus" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Status</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearch" v-model="search" type="text"
                                            class="form-control form-control-sm" placeholder="Serach by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="userFilterModalClear"
                                            class="btn btn-light-primary ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear Filters
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyUserFilter" class="btn btn-light-primary ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Get Results
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add User Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New System User</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="createUser" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Name</div>
                                <input v-model="new_user.name" type="text" class="form-control form-control-sm"
                                    placeholder="Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>

                                <div class="text-gray-600 col-form-label">Email Address</div>
                                <input v-model="new_user.email" type="email" class="form-control form-control-sm"
                                    placeholder="Email" />
                                <small v-if="validationErrors.email"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.email }}</small>

                                <div class="text-gray-600 col-form-label">Password</div>
                                <input v-model="new_user.password" type="password" name="password" id="password"
                                    class="form-control form-control-sm" placeholder="Password" />
                                <small v-if="validationErrors.password"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.password }}</small>

                                <div class="text-gray-600 col-form-label">Confirm Password</div>
                                <input v-model="new_user.con_password" type="password" name="password" id="password"
                                    class="form-control form-control-sm" placeholder="Password" />
                                <small v-if="validationErrors.con_password"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.con_password }}</small>

                                <div class="text-gray-600 col-form-label">User Role</div>
                                <multiselect v-model="new_user.user_role_id" :options="user_role" label="value"
                                    data-toggle="tooltip" data-placement="bottom" title="Select user role" track-by="id"
                                    placeholder="Select Role">
                                </multiselect>

                                <small v-if="validationErrors.user_role_id"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.user_role_id }}</small>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="mt-2 btn btn-light-primary me-2"
                                            data-toggle="tooltip" data-placement="bottom" title="Add new user"
                                            style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Sytem User Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Update Sytem User</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="updateUser" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Name</div>
                                <input v-model="edit_user.name" type="text" class="form-control form-control-sm"
                                    placeholder="Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>

                                <div class="text-gray-600 col-form-label">Email Address</div>
                                <input v-model="edit_user.email" type="text" class="form-control form-control-sm"
                                    placeholder="Email" />
                                <small v-if="validationErrors.email"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.email }}</small>

                                <div class="text-gray-600 col-form-label">Password</div>
                                <input v-model="edit_user.password" type="password" name="password" id="password"
                                    class="form-control form-control-sm" placeholder="Password" />
                                <small v-if="validationErrors.password"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.password }}</small>

                                <div class="text-gray-600 col-form-label">Confirm Password</div>
                                <input v-model="edit_user.con_password" type="password" name="password" id="password"
                                    class="form-control form-control-sm" placeholder="Password" />
                                <small v-if="validationErrors.con_password"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.con_password }}</small>

                                <div class="text-gray-600 col-form-label">User Role</div>
                                <multiselect v-model="edit_user.user_role" :options="user_role" label="value"
                                    data-toggle="tooltip" data-placement="bottom" title="Select user role" track-by="id"
                                    placeholder="Select Role">
                                </multiselect>
                                <small v-if="validationErrors.user_role_id"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.user_role_id }}</small>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="mt-2 btn btn-light-info me-2" data-toggle="tooltip"
                                            data-placement="bottom" title="Save changes" style="font-weight: bold">
                                            UPDATE
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm User Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0 text-muted">Are you sure you want to delete this user?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" style="font-weight: bold" data-toggle="tooltip"
                                data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-danger" style="font-weight: bold" data-toggle="tooltip"
                                data-placement="bottom" title="Delete user" @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i> DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Restore Confirmation Modal -->
            <div class="modal fade" id="restoreUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Restore</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to restore that user?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-primary" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Restore user"
                                @click.prevent="confirmRestore">
                                <i class="bi bi-arrow-clockwise"></i>
                                RESTORE
                            </button>
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
import _ from 'lodash';

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const search = ref(null);
const userStatus = ref(0);
const users = ref([]);
const emptyImageVal = ref(0);
const edit_user = ref({});
const select_value_type = ref(null);

const new_user = ref({});
const select_edit_user_role = ref(null);
const user_roles = ref([]);

const loading_bar = ref(null);

const selectUserId = ref(0);

const validationErrors = ref({});
const validationMessage = ref(null);



const user_role = ref([
    { id: 1, value: 'Admin' },
    { id: 2, value: 'Cashier' },
    { id: 3, value: 'Audit' },
    { id: 4, value: 'Inspector' }
]);

const setPage = (new_page) => {
    page.value = new_page;
    reload();
};

const getSearch = async () => {
    page.value = 1;
    reload();
};

const debouncedGetSearch = _.debounce(getSearch, 1000);

const perPageChange = async () => {
    page.value = 1;
    reload();
};

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

const openUserFilterModal = () => {
    loading_bar.value.start();
    $('#userFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyUserFilter = () => {
    $('#userFilterModal').modal('hide');
    reload();
};

const userFilterModalClear = async () => {
    emptyImageVal.value = 0;
    search.value = null;
    userStatus.value = 0;
    reload();
    $('#userFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('user.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search.value,
                search_status: userStatus.value,
            },
        })
    ).data;
    users.value = tableData.data;
    pagination.value = tableData.meta;

    if (users.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const createUser = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {

        new_user.value.user_role_id = new_user.value?.user_role_id?.id;
        await axios.post(route("user.store"), new_user.value);
        reload();
        $("#userModal").modal("hide");
        new_user.value = {};
        // select_value_type.value = null;
        successMessage("User created successfully")
        getUsers();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
    }
};

const getUsers = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const tableData = (await axios.get(route("user.all"))).data;
        users.value = tableData.data;
        pagination.value = tableData.meta;

        if (users.value.length > 0) {
            emptyImageVal.value = 0;
        } else {
            emptyImageVal.value = 1;
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
    }
};

const getUserRoles = async () => {
    try {
        const tableData = (await axios.get(route("role.all"))).data;
        user_roles.value = tableData;
    } catch (error) {
        convertValidationError(error);
    }
};

const editUser = async (id) => {

    resetValidationErrors();
    try {
        const user = (await axios.get(route('user.get', id))).data
        const userData = user.data;

        edit_user.value = {
            id: userData.id,
            name: userData.name,
            email: userData.email,
            user_role_id: userData.user_role_id,
            user_role: user_role.value.find(role => role.id === userData.user_role_id)
            // user_role: userData.user_role,
        }
        // if (edit_user.value.user_role_id) {
        //     select_edit_user_role.value = edit_user.value.user_role_id;
        // } else {
        //     select_edit_user_role.value = null;
        // }

        $('#editUserModal').modal('show')
    } catch (error) {
        convertValidationNotification(error);
    }

};

const updateUser = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();

    if (edit_user.value.password !== edit_user.value.con_password) {
        errorMessage("Passwords do not match");
        nextTick(() => {
            loading_bar.value.finish();
        });
        return;
    }

    try {

        edit_user.value.user_role_id = edit_user.value.user_role.id;
        edit_user.value.user_role = edit_user.value.user_role_id;
        const response = await axios.post(route('user.update', edit_user.value.id), edit_user.value);
        if (response.data == "success") {
            reload();
            $('#editUserModal').modal('hide');
            edit_user.value = {};
            select_edit_user_role.value = null;
            successMessage("User updated successfully");
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
        edit_user.value.user_role = user_role.value.find(role => role.id === edit_user.value.user_role);
    }
};


const deleteUser = async (id) => {


    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        // const response = await axios.delete(route('user.delete', id));
        selectUserId.value = id;
        $('#deleteUserModal').modal('show');
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }

}

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        const response = await axios.delete(route('user.delete', selectUserId.value));
        successMessage('User deleted successfully!');

        $('#deleteUserModal').modal('hide');
        selectUserId.value = '';
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

const restoreUser = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        selectUserId.value = id;
        $('#restoreUserModal').modal('show');
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }

}

const confirmRestore = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        axios.get(route("user.restore", selectUserId.value));
        reload();
        successMessage('User restored successfully!');

        $('#restoreUserModal').modal('hide');
        selectUserId.value = '';
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationNotification(error);
    }
}

function clearFilters() {
    emptyImageVal.value = 0;
    search.value = null;
    userStatus.value = 0;
    reload();
}

function openNewModal() {

    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    new_user.value = {};
    $('#userModal').modal('show');
    nextTick(() => {
        loading_bar.value.finish();
    });

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

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

onMounted(() => {
    getUsers();
    getUserRoles();

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
