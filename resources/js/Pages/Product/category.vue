<template>
    <AppLayout title="Product Categories">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2 justify-content-center">
                    <div class="mt-5 col-lg-12 mt-lg-0 custom-component">
                        <!-- Header Section -->
                        <div class="mt-0 row d-flex justify-content-between align-items-center col-form-label">
                            <div class="col-12 col-md-4 d-flex align-items-center">
                                <h1 class="main-header-text">Manage Your Categories</h1>
                            </div>
                            <div class="col-12 col-md-8 d-flex justify-content-end">
                                <button class="btn btn-info fw-bold" data-toggle="tooltip" title="Add a Category"
                                    @click="showCategoryModal(selectedCategoryData = {})">
                                    <i class="bi bi-plus-lg"></i> Add Category
                                </button>
                            </div>
                        </div>

                        <!-- Card Section -->
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">

                                <!-- Empty State -->
                                <div class="text-center row" v-if="categories.length === 0">
                                    <div class="mt-5 col-12">
                                        <i class="text-gray-400 bi bi-folder fs-1"></i>
                                    </div>
                                    <div class="mt-3 col-12">
                                        <h2 class="text-gray-700 fw-bold fs-4">No Categories Available</h2>
                                        <p class="text-gray-600 fs-5">Create categories to organize your products</p>
                                    </div>
                                </div>

                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4 flex-grow-1"></div>
                                        <!-- Add this empty div to push the select dropdown to the right -->
                                        <div class="ml-auto dataTables_length" id="kt_ecommerce_products_table_length">
                                            <label>
                                                <select name="kt_ecommerce_products_table_length"
                                                    aria-controls="kt_ecommerce_products_table"
                                                    class="w-auto mx-1 form-select form-select-sm form-select-solid"
                                                    v-model="pageCount" @change="perPageChange">
                                                    <option v-for="perPageCount in perPage" :key="perPageCount"
                                                        :value="perPageCount">
                                                        {{ perPageCount }}
                                                    </option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>




                                <!-- Category Table -->
                                <div class="row" v-if="categories.length !== 0">
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="ps-3">Category Name</th>
                                                    <th>Description</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="category, key in categories" :key="key">
                                                    <td class="py-2 ps-3">{{ truncateText(category.name) }}</td>
                                                    <td class="py-2">{{ truncateText(category.remarks) }}</td>
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Edit Button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Edit" @click="editCategory(category.id)">
                                                                <i
                                                                    class="p-2 bi bi-pencil-square text-dark-square fs-5 text-dark"></i>
                                                                <!-- Dark border and icon color -->
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                title="Delete" @click="showDeleteModal(category.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                <!-- Red border and icon color -->
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="category, key in categories" :key="key"
                                            class="p-3 mb-4 border rounded">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Category Name -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Category Name</td>
                                                        <td class="text-end">{{ truncateText(category.name) }}</td>
                                                    </tr>

                                                    <!-- Description -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Description</td>
                                                        <td class="text-end"
                                                            style="word-wrap: break-word; white-space: normal;">
                                                            {{ truncateText(category.remarks) }}
                                                        </td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <!-- Edit Button -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    title="Edit" @click="editCategory(category.id)">
                                                                    <i
                                                                        class="p-2 bi bi-pencil-square fs-5 text-dark"></i>
                                                                </a>
                                                                <!-- Delete Button -->
                                                                <a href="javascript:void(0)" data-toggle="tooltip"
                                                                    title="Delete"
                                                                    @click="showDeleteModal(category.id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Section: Pagination and Entries Dropdown -->
                                <div class="my-3 row align-items-center">
                                    <div class="col-sm-6 d-none d-lg-flex align-items-center">
                                        <!-- Entries Dropdown -->
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
                                    <div class="col-sm-6 d-flex justify-content-end align-items-center">
                                        <!-- Pagination Info and Controls -->
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''">
                                                    <a href="javascript:void(0)"
                                                        @click="setPage(pagination.current_page - 1)" class="page-link">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
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

    </AppLayout>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-5 col-8">
                            <h5 v-if="selectedCategoryData.id" class="modal-title" style="color: #071437">Edit Category
                            </h5>
                            <h5 v-else class="modal-title" style="color: #071437">Create a New Category</h5>
                        </div>
                        <div class="mb-5 col-4 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeCategoryModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="saveNewCategory" enctype="multipart/form-data">
                        <div class="text-gray-600 col-form-label">Category Name</div>
                        <input v-model="categoryData.name" type="text" class="form-control form-control-sm"
                            placeholder="Enter category name" />

                        <div class="text-gray-600 col-form-label">Category Description</div>
                        <textarea v-model="categoryData.remarks" class="form-control"
                            placeholder="Enter category description" data-kt-autosize="true"
                            style="resize: none; font-size: 12px;" rows="2"></textarea>
                        <div class="row">
                            <div class="mt-4 col-12 text-end">
                                <button v-if="selectedCategoryData.id" type="button"
                                    class="mt-2 btn btn-light-info me-2" style="font-weight: bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Save changes to this category"
                                    @click.prevent="updateCategory(selectedCategoryData.id)">
                                    Save Changes
                                </button>
                                <button v-else type="submit" class="mt-2 btn btn-info me-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add this new category" style="font-weight: bold">
                                    Add Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Category Deletion</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close" @click="closeDeleteModal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" style="font-weight: bold" data-dismiss="modal"
                        data-toggle="tooltip" data-placement="bottom" title="Cancel and keep category"
                        @click="closeDeleteModal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-light-danger" style="font-weight: bold" data-toggle="tooltip"
                        data-placement="bottom" title="Permanently delete this category"
                        @click.prevent="deleteCategory(selectedCategoryId)">
                        <i class="bi bi-trash"></i>
                        Delete Category
                    </button>
                </div>
            </div>
        </div>
    </div>



</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import { usePage } from '@inertiajs/vue3'

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const categories = ref([]);

const categoryData = ref({});
const selectedCategoryId = ref(null);

const selectedCategoryData = ref({});

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

const editCategory = async (id) => {

    try {
        const category = (await axios.get(route('category.get', id))).data;
        selectedCategoryData.value = category;
        showCategoryModal(selectedCategoryData.value);
    } catch (error) {
        console.error(error);
    }



};

// Show Add/Edit Category Modal
const showCategoryModal = async (selectedCategoryData) => {

    if (selectedCategoryData) {
        categoryData.value.name = selectedCategoryData.name;
        categoryData.value.remarks = selectedCategoryData.remarks;
        $("#categoryModal").modal("show");
    } else {
        categoryData.value.name = "";
        categoryData.value.remarks = "";
        $("#categoryModal").modal("show");
    }

};

// Show Delete Confirmation Modal
function showDeleteModal(categoryId) {

    selectedCategoryId.value = categoryId;
    $("#deleteModal").modal("show");

}

// Delete Category (Delete Confirmation Modal)
function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

// Close Add/Edit, Category (Close Modal)
const closeCategoryModal = async () => {
    $("#categoryModal").modal("hide");
    categoryData.value.stock_quantity = "";
};

const saveNewCategory = async () => {
    resetValidationErrors();
    try {


        const response = (await axios.post(route("category.store"), categoryData.value)).data;
        if (response === "This category already exists") {
            errorMessage('This category already exists');
        } else {
            successMessage('New category registration is successful');
            closeCategoryModal();
            getCategories();
        }


    } catch (error) {
        convertValidationNotification(error);
    }
};

const updateCategory = async (id) => {
    resetValidationErrors();
    try {


        await axios.post(route('category.update', id), categoryData.value);
        successMessage('Category successfully updated');
        closeCategoryModal();
        getCategories();


    } catch (error) {
        convertValidationNotification(error);
    }
};

const setPage = (new_page) => {
    page.value = new_page;
    reload();
};
const perPageChange = async () => {
    page.value = 1;
    reload();
};

const reload = async () => {
    const tableData = (
        await axios.get(route('categoryDetails.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
            },
        })
    ).data;

    categories.value = tableData.data;
    pagination.value = tableData.meta;
};

const getCategories = async () => {
    try {
        const tableData = (await axios.get(route('categoryDetails.all'))).data;

        categories.value = tableData.data;
        pagination.value = tableData.meta;
    } catch (error) {

    }
};

const deleteCategory = async (categoryId) => {
    try {


        await axios.delete(route("category.delete", categoryId));
        closeDeleteModal();
        successMessage('Category deleted successfully');
        getCategories();


    } catch (error) {
    }
}

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
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

onMounted(() => {
    getCategories();
});

</script>

<style lang="scss" scoped></style>
