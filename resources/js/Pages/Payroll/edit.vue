<template>
    <AppLayout title="Edit Payrolls">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div class="pb-0 mt-0 d-flex justify-content-start align-items-center col-form-label pb-sm-3">
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <h1 style="margin-right: auto; font-size: 28px; color: #071437">
                                    Payroll&nbsp;Details&nbsp;
                                </h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="py-0 mb-5 col-12 mb-sm-5 mb-md-5 mb-xl-0 col-md-12 col-xl-6 form-input-section">

                                <div class="shadow card h-100">
                                    <div class="p-4 py-1 text-sm p-md-7 py-md-7 card-body">

                                        <form @submit.prevent="updatePayroll" enctype="multipart/form-data">
                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Code</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input v-model="edit_payroll.code" type="cost"
                                                        class="form-control form-control-sm" placeholder="Code"
                                                        disabled />
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Employee <i
                                                            class="ml-4 cursor-pointer bi bi-plus-lg-fill fs-5 plus-btn-employee"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new employee"
                                                            v-if="edit_payroll.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                            @click.prevent="showEmployeeModal"></i></div>
                                                </div>
                                                <div class="pt-2 col-md-9 d-flex multiselect-wrapper">
                                                    <Multiselect v-model="edit_select_employee"
                                                        :options="employeesSearch" class="z__index remove-column"
                                                        :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Employee" label="name" track-by="id"
                                                        max-height="200"
                                                        :disabled="edit_payroll.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                                        @search-change="getEmployeesSearch" :internal-search="false">
                                                        <template #noOptions>
                                                            <div>Press a key to select Employee</div>
                                                        </template>
                                                        <template #noResult>
                                                            <div>No matching employees found</div>
                                                        </template>
                                                    </Multiselect>
                                                    <!-- <button
                                                        v-if="edit_payroll.supplier_id != null && page_props.auth.user.user_role_id == 1"
                                                        type="button" class="supplier-remove-btn mr-9"
                                                        data-bs-toggle="dropdown" data-kt-menu-placement="bottom-end"
                                                        @click.prevent="removeEmployee">
                                                        <i class="bi bi-x-lg fs-3 text-danger supplier-remove-icon"></i>
                                                    </button> -->
                                                </div>
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <small v-if="validationErrors.employee_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.employee_id }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Date</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input type="date" v-model="edit_date"
                                                        class="form-control form-control-sm" :format="'dd/MM/yyyy'"
                                                        :disabled="edit_payroll.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)" />
                                                    <small v-if="validationErrors.date"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.date }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Currency</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <Multiselect v-model="edit_select_currency" :options="currencies"
                                                        class="z__index" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Currency" label="code" track-by="id"
                                                        max-height="200"
                                                        :disabled="edit_payroll.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)" />
                                                    <small v-if="validationErrors.currency_id"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.currency_id }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Amount</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <input v-model="edit_payroll.amount" type="cost"
                                                        class="form-control form-control-sm" placeholder="Amount"
                                                        :disabled="edit_payroll.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)" />
                                                    <small v-if="validationErrors.amount"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.amount }}</small>
                                                </div>
                                            </div>

                                            <div class="mt-3 row">
                                                <div class="col-md-3">
                                                    <div class="text-gray-600 col-form-label">Description</div>
                                                </div>
                                                <div class="pt-2 col-md-9">
                                                    <textarea v-model="edit_payroll.description" class="form-control"
                                                        placeholder="Payroll Description" data-kt-autosize="true"
                                                        style="resize: none; font-size: 12px;" rows="4"
                                                        :disabled="edit_payroll.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"></textarea>
                                                    <small v-if="validationErrors.description"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.description }}</small>
                                                </div>
                                            </div>

                                            <div class="row d-none d-md-flex"
                                                v-if="edit_payroll.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                                <div class="col-12 text-end align-items-end action-btn-margin">
                                                    <button type="button" class="btn btn-primary ms-2 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Print voucher"
                                                        @click.prevent="payrollPrint(edit_payroll.id)">
                                                        PRINT
                                                    </button>
                                                    <button type="submit" class="btn btn-info ms-2 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Update payroll">
                                                        UPDATE
                                                    </button>
                                                    <button type="button" class="btn btn-warning ms-2 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Email payroll" @click.prevent="showCustomerEmailModal">
                                                        EMAIL
                                                    </button>
                                                    <button type="button" class="btn btn-danger ms-2 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Delete payroll" @click="deletePayroll(edit_payroll.id)">
                                                        DELETE
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-5 row d-md-none"
                                                v-if="edit_payroll.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                                <div class="mb-3 col-6 col-sm-6">
                                                    <button type="button" class="btn btn-primary w-100 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Print voucher"
                                                        @click.prevent="payrollPrint(edit_payroll.id)">
                                                        PRINT
                                                    </button>
                                                </div>
                                                <div class="mb-3 col-6 col-sm-6">
                                                    <button type="submit" class="btn btn-info w-100 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Update payroll">
                                                        UPDATE
                                                    </button>
                                                </div>
                                                <div class="mb-3 col-6 col-sm-6">
                                                    <button type="button" class="btn btn-warning w-100 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Email payroll" @click.prevent="showCustomerEmailModal">
                                                        EMAIL
                                                    </button>
                                                </div>
                                                <div class="mb-3 col-6 col-sm-6">
                                                    <button type="button" class="btn btn-danger w-100 fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Delete payroll" @click="deletePayroll(edit_payroll.id)">
                                                        DELETE
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" v-else>
                                                <div
                                                    class="mb-4 col-12 text-end align-items-end action-btn-margin mb-md-0">
                                                    <button type="button" class="btn btn-warning fw-bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Restore payroll"
                                                        v-if="(page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                        @click.prevent="restorePayroll(edit_payroll.id)">
                                                        RESTORE
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="py-0 col-12 col-md-12 col-xl-6 image-input-section">
                                <div class="shadow card h-100">
                                    <div class="p-4 py-0 text-sm p-md-7 py-md-3 card-body">
                                        <form @submit.prevent="updatePayroll" enctype="multipart/form-data">
                                            <div class="mt-3 row">
                                                <div class="col-md-12">
                                                    <div class="text-gray-600 col-form-label">Payment Slip Image</div>
                                                </div>
                                                <div class="pt-2 col-md-12 d-flex justify-content-center">
                                                    <!-- Desktop View -->
                                                    <div class="p-2 image-input image-chooser-border d-none d-md-flex"
                                                        :class="{ 'drag-over': isDragOver }"
                                                        @dragover.prevent="handleDragOver"
                                                        @drop.prevent="handleFileDrop"
                                                        @dragleave.prevent="handleDragLeave">
                                                        <div class="image-input-wrapper w-400px h-400px"
                                                            style="overflow: hidden; position: relative;">
                                                            <template v-if="edit_payroll.isPdf == 1">
                                                                <iframe :src="edit_payroll.image_url" width="100%"
                                                                    height="400px"></iframe>
                                                            </template>
                                                            <!-- Otherwise, show an image -->
                                                            <template v-else>
                                                                <img v-if="edit_payroll.image_url && edit_payroll.isPdf == 0"
                                                                    :src="edit_payroll.image_url"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution" />
                                                                <img v-else @click="openEditImageFile"
                                                                    src="@/../src/assets/media/receipt/expense-receipt-placeholder.png"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution" />
                                                            </template>
                                                        </div>
                                                        <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                            data-kt-menu="true" id="edit-toggle-box">

                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 0"
                                                                @click="downloadImageFile(edit_payroll.image_url)"
                                                                class="px-5 py-2 cursor-pointer menu-item">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download Image</span>
                                                            </a>
                                                            <div class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    Image</span>
                                                            </div>
                                                            <div class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    Image</span>
                                                            </div>

                                                        </div>
                                                        <input type="file" ref="fileInput"
                                                            v-if="edit_payroll.deleted_at == null"
                                                            accept="image/jpg, image/png, application/pdf"
                                                            id="payroll_image" name="avatar" hidden
                                                            @change="onFileChangeEdit">
                                                        <input type="hidden" name="avatar_remove">

                                                        <span
                                                            v-if="edit_payroll.image_url && edit_payroll.deleted_at == null"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-40px h-40px bg-body"
                                                            data-kt-image-input-action="change"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1" data-bs-toggle="dropdown"
                                                            data-kt-menu-placement="left-start">
                                                            <i class="bi bi-three-dots-vertical fs-2"></i>
                                                        </span>
                                                        <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                            data-kt-menu="true" id="edit-toggle-box">
                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 1"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click="downloadReceipt(edit_payroll.image_url)">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download
                                                                    File</span>
                                                            </a>
                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf === 0"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click="downloadImageFile(edit_payroll.image_url)">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download Image</span>
                                                            </a>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 1 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    File</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 0 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    Image</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 1 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    File</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 0 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    Image</span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Mobile View -->
                                                    <div class="p-2 image-input image-chooser-border-mobile d-block d-md-none"
                                                        :class="{ 'drag-over': isDragOver }"
                                                        @dragover.prevent="handleDragOver"
                                                        @drop.prevent="handleFileDrop"
                                                        @dragleave.prevent="handleDragLeave">
                                                        <div class="image-input-wrapper-mobile">
                                                            <template v-if="edit_payroll.isPdf == 1">
                                                                <iframe :src="edit_payroll.image_url" width="100%"
                                                                    height="400px"></iframe>
                                                            </template>
                                                            <!-- Otherwise, show an image -->
                                                            <template v-else>
                                                                <img v-if="edit_payroll.image_url && edit_payroll.isPdf == 0"
                                                                    :src="edit_payroll.image_url"
                                                                    class="mb-2 img-fluid image-pdf-fix-resolution-mobile" />
                                                                <img v-else @click="openEditImageFile"
                                                                    src="@/../src/assets/media/receipt/expense-receipt-placeholder.png" />
                                                            </template>
                                                        </div>
                                                        <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                            data-kt-menu="true" id="edit-toggle-box">

                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 0"
                                                                @click="downloadImageFile(edit_payroll.image_url)"
                                                                class="px-5 py-2 cursor-pointer menu-item">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download Image</span>
                                                            </a>
                                                            <div class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    Image</span>
                                                            </div>
                                                            <div class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    Image</span>
                                                            </div>

                                                        </div>

                                                        <input type="file" ref="fileInput"
                                                            v-if="edit_payroll.deleted_at == null"
                                                            accept="image/jpg, image/png, application/pdf"
                                                            id="payroll_image" name="avatar" hidden
                                                            @change="onFileChangeEdit">
                                                        <input type="hidden" name="avatar_remove">

                                                        <span
                                                            v-if="edit_payroll.image_url && edit_payroll.deleted_at == null"
                                                            class="shadow btn btn-icon btn-circle btn-active-color-primary w-40px h-40px bg-body"
                                                            data-kt-image-input-action="change"
                                                            aria-label="Change avatar"
                                                            data-bs-original-title="Change avatar"
                                                            data-kt-initialized="1" data-bs-toggle="dropdown"
                                                            data-kt-menu-placement="left-start">
                                                            <i class="bi bi-three-dots-vertical fs-2"></i>
                                                        </span>
                                                        <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                            data-kt-menu="true" id="edit-toggle-box">
                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 1"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click="downloadReceipt(edit_payroll.image_url)">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download
                                                                    File</span>
                                                            </a>
                                                            <a v-if="edit_payroll.image_url && edit_payroll.isPdf === 0"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click="downloadImageFile(edit_payroll.image_url)">
                                                                <i class="text-gray-700 bi bi-download"></i> <span
                                                                    class="text-gray-700 ms-2">Download Image</span>
                                                            </a>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 1 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    File</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 0 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-3 mt-1 cursor-pointer menu-item border-top"
                                                                @click="openEditImageFile">
                                                                <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                    class="text-gray-700 ms-2">Change
                                                                    Image</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 1 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    File</span>
                                                            </div>
                                                            <div v-if="edit_payroll.image_url && edit_payroll.isPdf == 0 && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                class="px-5 py-2 cursor-pointer menu-item"
                                                                @click.prevent="showImageDeleteModal">
                                                                <i class="bi bi-trash text-danger"></i> <span
                                                                    class="text-danger ms-2">Remove
                                                                    Image</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="edit_payroll.image_url == null" class="pt-4 text-center text-gray-400 col-12">
                                                    File should be less than 5MB
                                                </div>
                                            </div>

                                            <div class="mt-10 row">
                                                <div class="mt-4 col text-end">

                                                    <!-- <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 1"
                                                        :href="edit_payroll.image_url" target="_blank"
                                                        class="btn btn-success download-btn">
                                                        DOWNLOAD
                                                    </a>
                                                    <a v-if="edit_payroll.image_url && edit_payroll.isPdf == 0"
                                                        @click="downloadReceipt(edit_payroll.image_url)"
                                                        class="btn btn-success download-btn">
                                                        DOWNLOAD
                                                    </a> -->

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #modal>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="payrollDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this payroll?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                @click.prevent="confirmDelete">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Image Modal -->
            <div class="modal fade" id="imageDeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this receipt?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                @click.prevent="removeImage(edit_payroll.id)">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Employee Modal -->
            <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewEmployee" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title" style="color: #071437">Add New Employee</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            @click="closeEmployeeModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Name</lable>
                                        <input v-model="employeeData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Employee Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="employeeData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="employeeData.email" type="email"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                        <small v-if="validationErrors.email"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.email }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 2">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[emailIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="employeeData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="emailIndex = 3">+
                                                    Another
                                                    Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[emailIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Email 3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="employeeData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone No.1</lable>
                                        <input v-model="employeeData.contact" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Phone Number1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 2">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 1 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.2</lable>
                                        <div class="row">
                                            <div class="col-11" :class="[phoneIndex === 3 ? 'col-12' : '',]">
                                                <input v-model="employeeData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number2" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary"
                                                    @click="phoneIndex = 3">+
                                                    Another
                                                    Phone No.</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 col-12" :class="[phoneIndex <= 2 ? 'd-none' : '',]">
                                        <lable class="text-gray-600">Phone No.3</lable>
                                        <div class="row">
                                            <div class="col-11">
                                                <input v-model="employeeData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number3" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Category Modal -->
            <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New Category</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                        @click="closeCategoryModal"></button>
                                </div>
                            </div>
                            <form @submit.prevent="saveNewCategory" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Name</div>
                                <input v-model="categoryData.name" type="text" class="form-control form-control-sm"
                                    placeholder="Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>

                                <!-- <div class="text-gray-600 col-form-label">Remark</div>
                        <textarea v-model="categoryData.remarks" class="form-control" placeholder="Enter category remark"
                            data-kt-autosize="true" style="resize: none; font-size: 12px;" rows="2"></textarea> -->
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="mt-2 btn btn-info" style="font-weight: bold">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mail Modal -->
            <div class="modal fade" id="send_mail" tabindex="-1" data-bs-backdrop="static"
                aria-labelledby="addNewCardTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="pb-4 pl-4 pr-4 modal-content">
                        <div class="pb-5 modal-body px-sm-5 mx-50">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h1 class="modal-title" style="color: #071437">Send Payrolls</h1>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <form id="addNewCardValidation" class="row gy-1 gx-2 mt-75"
                                @submit.prevent="sendPayrollEmail()">
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Send To</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email" class="form-control emailinput" id="to"
                                            v-model="employee_email.to" placeholder="Enter Email Address" />
                                    </div>

                                    <small v-if="validationErrors.to" id="title"
                                        class="text-danger form-text text-error-msg error">
                                        {{ validationErrors.to }}
                                    </small>

                                </div>
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Send CC</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email" id="cc" class="form-control emailinput"
                                            v-model="employee_email.cc" placeholder="Enter Email Addresses" />
                                    </div>
                                    <div id='output'></div>
                                </div>
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Subject</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" v-model="employee_email.subject"
                                            placeholder="Enter Email Subject" />
                                    </div>
                                </div>
                                <div class="mt-4 col-12">
                                    <div class="mb-2">
                                        <label class="form-label line-height-3" for="modalAddCardNumber">
                                            Message
                                        </label>
                                        <QuillEditor v-model="employee_email.message" :toolbar="emailToolbarOptions"
                                            id="email-message" @keyup="setInvoiceNote" :options="quillOptions"
                                            placeholder="Note">
                                        </QuillEditor>
                                    </div>
                                </div>
                                <div class="mt-4 text-right col-12">
                                    <button type="button" class="mr-2 btn btn-light-darkr fw-bold"
                                        data-bs-dismiss="modal">
                                        CANCEL
                                    </button>
                                    <button type="submit" class="mt-1 btn btn-info fw-bold">
                                        <i class="bi bi-send"></i>
                                        SEND
                                    </button>
                                </div>
                            </form>
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
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import { Link } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import moment from 'moment';
import Datepicker from 'vue3-datepicker'
import { QuillEditor } from '@vueup/vue-quill';


const { props } = usePage();
const page_props = props;

const emailIndex = ref(1);
const phoneIndex = ref(1);
const employeeData = ref({});

const categories = ref([]);
const employees = ref([]);
const select_category = ref([]);
const edit_select_category = ref({});
const select_edit_category = ref({});
const select_employee = ref([]);
const edit_select_employee = ref([]);
const select_edit_unit = ref({});
const employeesSearch = ref([]);

const product = ref({});
const payroll = ref({});
const edit_payroll = ref({});

const stock = ref({});

const stock_in = ref(false);
const stock_out = ref(false);

// Filter variables
const codeFilter = ref("");
const search_category = ref(null);
const search_category_id = ref(null);
const search_employee = ref(null);
const search_supplier_id = ref(null);
const search_date = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const loading_bar = ref(null);

const isDragOver = ref(false);

const date = ref(new Date());
const edit_date = ref(new Date());
const placeholderText = 'DD/MM/YYYY';
const business_details = ref({});
const currencies = ref([]);
const select_currency = ref({});
const edit_select_currency = ref({});

const payroll_image = ref(null);
const edit_payroll_image = ref(null);
const categoryData = ref({});

const employee_email = ref({});
const selectedEmployeeData = ref({});



const onFileChangeEdit = (e) => {

    const fileInputs = document.getElementById('payroll_image');
    if (fileInputs.files[0]) {
        const file = fileInputs.files[0];
        if (file.size < 5 * 1024 * 1024) {
            edit_payroll.value.image = e.target.files[0];
            edit_payroll_image.value = e.target.files[0];

            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    edit_payroll.value.image_url = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
};

const handleDragOver = (e) => {
    e.preventDefault();
    isDragOver.value = true;
};

const handleFileDrop = (e) => {
    if (page_props.auth.user.user_role_id != 3) {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.size < 5 * 1024 * 1024) {
            edit_payroll.value.image = file;
            edit_payroll_image.value = file;
            updatePayroll();
            const reader = new FileReader();
            reader.onload = (event) => {
                edit_payroll.value.image_url = event.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            errorMessage('File size should be less than 5MB');
        }
        isDragOver.value = false;
    } else {
        isDragOver.value = false;
        getPayroll();
        errorMessage('Access denied');
    }
};

const handleDragLeave = (e) => {
    e.preventDefault();
    isDragOver.value = false;
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

watch(search_category, (newValue) => {
    if (newValue) {
        search_category_id.value = newValue.id;
    } else {
        search_category_id.value = null;
    }
});

watch(search_employee, (newValue) => {
    if (newValue) {
        search_supplier_id.value = newValue.id;
    } else {
        search_supplier_id.value = null;
    }
});

watch(search_date, (newValue) => {
    search_date.value = newValue;
});

const removeEmployee = async () => {
    try {
        const response = await axios.delete(route('payrolls.employee.delete', edit_payroll.value.id));
        successMessage('Employee removed successfully!');
        edit_select_employee.value = [];
        getPayroll();

    } catch (error) {
        convertValidationError(error);
    }
};


const getCategories = async () => {
    try {
        const response = (await axios.get(route('payrolls.category.multiselect.all'))).data;
        categories.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployees = async () => {
    try {
        const response = (await axios.get(route('employee.multiselect.all'))).data;
        employees.value = response;
    } catch (error) {
        convertValidationError(error);
    }
};

const getEmployeesSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('employee.multiselect.all.search', { query }));
            employeesSearch.value = response.data;
        } catch (error) {
            employeesSearch.value = [];
        }
    } else {
        employeesSearch.value = [];
    }
};

const getPayroll = async () => {
    try {
        const response = (await axios.get(route("payroll.get", page_props.payroll_id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.supplier_id) {
            const employee = (await axios.get(route('supplier.payroll.get', edit_payroll.value.supplier_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }
        if (edit_payroll.value.date) {
            const createdDate = new Date(edit_payroll.value.date);
            const year = createdDate.getFullYear();
            let month = (createdDate.getMonth() + 1).toString().padStart(2, '0');
            let day = createdDate.getDate().toString().padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;

            edit_date.value = formattedDate;
        }

        const isPdf = edit_payroll.value.image_url.toLowerCase().endsWith('.pdf');
        edit_payroll.value.isPdf = isPdf ? 1 : 0;

        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

    } catch (error) {
        convertValidationNotification(error);
    }
};

const loadData = async (id) => {
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;

        if (edit_payroll.value.payroll_category_id) {
            edit_select_category.value.id = edit_payroll.value.payroll_category_id;
            edit_select_category.value.name = edit_payroll.value.category_name;
        }

        if (edit_payroll.value.supplier_id) {
            const employee = (await axios.get(route('employee.get', edit_payroll.value.supplier_id))).data
            edit_select_employee.value = employee;
        }

        if (edit_payroll.value.currency_id) {
            edit_select_currency.value.id = edit_payroll.value.currency_id;
            edit_select_currency.value.code = edit_payroll.value.currency_name;
        }

        if (edit_payroll.value.date) {
            edit_date.value = new Date(edit_payroll.value.date);
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const updatePayroll = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();


        const fileInputs = document.getElementById('payroll_image');
        if (fileInputs.files[0]) {
            const file = fileInputs.files[0];
            if (file.size > 5 * 1024 * 1024) {
                errorMessage('File size should be less than 5MB');
                edit_payroll.value.image = edit_payroll_image.value;
                edit_payroll_image.value = edit_payroll_image.value;

                const fileInput = document.getElementById('payroll_image');
                fileInput.value = null;

                nextTick(() => {
                    loading_bar.value.finish();
                });

                return;
            }
        }

        if (edit_select_category.value != null) {
            edit_payroll.value.payroll_category_id = edit_select_category.value.id;
        }

        if (edit_select_employee.value != null) {
            edit_payroll.value.employee_id = edit_select_employee.value.id;
        } else {
            edit_payroll.value.employee_id = null;
        }

        if (edit_select_currency.value != null) {
            edit_payroll.value.currency_id = edit_select_currency.value.id;
        }

        if (edit_date.value != null) {
            edit_payroll.value.date = edit_date.value;
        }

        if (edit_payroll_image.value != null) {
            edit_payroll.value.image = edit_payroll_image.value;
        } else {
            edit_payroll.value.image = null;
        }

        const response = await axios.post(route("payroll.update", edit_payroll.value.id), edit_payroll.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        successMessage('Payroll updated successfully!');

        edit_payroll_image.value = null;

        const fileInput = document.getElementById('payroll_image');
        fileInput.value = null;

        getPayroll();


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

const removeImage = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        await axios.get(route("payrolls.image.remove", id)).then((response) => {
            loadData(id);
            $("#imageDeleteModal").modal("hide");
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

const downloadReceipt = async (imagePath) => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        const response = await axios.get(route('payrolls.downloadReceipt'), {
            responseType: 'blob',
            params: {
                imagePath: imagePath,
            }
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));

        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', edit_payroll.value.code + '-payroll.pdf');

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // window.open(imagePath);

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        console.error('Error downloading image:', error);
    }
};

const downloadImageFile = async (imagePath) => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        const response = await axios.get(route('payrolls.downloadReceipt'), {
            responseType: 'blob',
            params: {
                imagePath: imagePath,
            }
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));

        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', edit_payroll.value.code + '-payroll.jpg');

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        console.error('Error downloading image:', error);
    }
};

const payrollPrint = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('payroll.print', id), { responseType: "blob" });
        const url = window.URL.createObjectURL(print.data);
        window.open(url, "_blank");
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }
};

const deletePayroll = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("payroll.get", id))).data;
        edit_payroll.value = response;
        const isPdf = edit_payroll.value.image_url.toLowerCase().endsWith('.pdf');
        edit_payroll.value.isPdf = isPdf ? 1 : 0;

        $('#payrollDeleteModal').modal('show');
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

function showImageDeleteModal() {

    $("#imageDeleteModal").modal("show");

}

const confirmDelete = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        const response = (await axios.delete(route("payroll.delete", page_props.payroll_id))).data;
        successMessage('Payroll deleted successfully!');

        $('#payrollDeleteModal').modal('hide');
        window.location.href = route('payrolls.index');

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

const showEmployeeModal = async () => {

    employeeData.value = {};
    $("#employeeModal").modal("show");

};

const saveNewEmployee = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {



        // add the type of the employee as 1
        employeeData.value.type = 1;

        const response = await axios.post(route("employee.all.store"), employeeData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New employee registration is successful');
            closeEmployeeModal();
            getEmployees();
            edit_select_employee.value = response.data;
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

const closeEmployeeModal = async () => {
    $("#employeeModal").modal("hide");
};

const showPayrollCategoryModal = async () => {

    categoryData.value.name = "";
    categoryData.value.remarks = "";
    $("#categoryModal").modal("show");

};

const closeCategoryModal = async () => {
    $("#categoryModal").modal("hide");
    categoryData.value.stock_quantity = "";
};

const saveNewCategory = async () => {
    resetValidationErrors();
    try {


        const response = (await axios.post(route("payrolls.category.store"), categoryData.value)).data;
        if (response === "This category already exists") {
            errorMessage('This category already exists');
        } else {
            successMessage('New category registration is successful');
            closeCategoryModal();
            getCategories();
            edit_select_category.value = response;
        }


    } catch (error) {
        convertValidationError(error);
    }
};

const editStock = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("product.get", id))).data;
        stock.value = response;

        stock_in.value = true;

        $('#stockModal').modal('show');
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

const getCurrency = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("currency.list"))).data;
        currencies.value = response.data;

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            select_currency.value.id = business_details.value.currency_id;
            select_currency.value.code = business_details.value.currency_name;
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const openEditImageFile = async () => {

    if (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4) {
        if (edit_select_employee.value == null) {
            errorMessage('Please select an employee');
        } else if (edit_payroll.value.amount == null) {
            errorMessage('Please enter payroll amount');
        } else {
            const fileInput = document.getElementById('payroll_image');
            fileInput.click();
            fileInput.addEventListener('change', updatePayroll);
        }
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

const showCustomerEmailModal = () => {

    resetValidationErrors();
    getCustomerData();
    $("#send_mail").modal("show");



};

const getCustomerData = async () => {
    const employee = (await axios.get(route('supplier.payroll.get', edit_payroll.value.supplier_id))).data
    selectedEmployeeData.value = employee;
    setEmployeeEmailDetails(selectedEmployeeData);
};

const setEmployeeEmailDetails = async (selectedEmployeeData) => {
    try {
        const configuration_details = (await axios.get(route("configuration.get"))).data.data;
        employee_email.value.to = selectedEmployeeData.value.email;
        employee_email.value.cc = configuration_details.email;
        employee_email.value.subject = "Salary Slip Number : " + edit_payroll.value.code;

        employee_email.value.message =
            "<p>Hi " + selectedEmployeeData.value.name +
            ",</p><p>I hope you’re doing well! Please see the attached salary slip number " +
            edit_payroll.value.code +
            "</p><p>Don’t hesitate to reach out if you have any questions.</p><p>Kind regards!</p>";

        const editorContainer = document.querySelector('#email-message .ql-editor');
        editorContainer.innerHTML = employee_email.value.message;
    } catch (error) {
        errorMessage('failed to set customer data');
    }
};

const sendPayrollEmail = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        const editorContainer = document.querySelector('#email-message .ql-editor');
        employee_email.value.message = editorContainer.innerHTML;
        const response = await axios.post(route("payrolls.mail.send", edit_payroll.value.id), employee_email.value);
        successMessage(response.data.message);
        closeEmployeeEmailModal();
        reload();

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

const restorePayroll = async (payrollId) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.get(route("payrolls.restore", payrollId));
        window.location.href = route("payrolls.index");
        successMessage('Payroll restored successfully');
        getPayroll();

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

const closeEmployeeEmailModal = () => {
    resetValidationErrors();
    $("#send_mail").modal("hide");
};

const emailToolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline'], // toggled buttons
];

onMounted(() => {
    getEmployees();
    getCurrency();
    getBusinessDetails();
    getPayroll();
});

watch(() => {
    if (stock_in.value == true) {
        stock_out.value = false;
    } else if (stock_out.value == true) {
        stock_in.value = false;
    }
});

</script>

<style lang="scss" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}

.plus-btn {
    color: #1464a4;
}

.plus-btn-employee {
    color: #1464a4;
    margin-left: 6px;
}

.edit-btn {
    color: #1464a4;
}

.download-btn {
    background-color: #48d16a;
    color: rgb(255, 255, 255);
    font-weight: bold;
}

.delete-btn {
    color: red;
}

.cancel-btn {
    background-color: rgba(128, 128, 128, 0.192);
    color: black;
}
</style>
