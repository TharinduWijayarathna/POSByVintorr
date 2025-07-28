<template>
    <AppLayout title="Expense Categories">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2">
                    <div class="mt-5 col-lg-12 mt-lg-0">
                        <div
                            class="mt-0 d-flex flex-column flex-sm-row justify-content-start align-items-center col-form-label">
                            <div
                                class="pb-0 mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center pb-sm-3">

                                <h1 class="main-header-text">
                                    Expense Categories
                                </h1>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8 col-xl-6 col-xxl-6 d-flex justify-content-end">
                                <button class="btn btn-info fw-bold" data-toggle="tooltip" data-placement="bottom"
                                    title="Add new category" @click="showCategoryModal(selectedCategoryData = {})">
                                    <i class="bi bi-plus-lg"></i> Add Category
                                </button>
                            </div>
                        </div>
                        <div class="shadow card">
                            <div class="py-3 text-sm card-body">



                                <div class="row" v-if="categories.length == 0">
                                    <!-- Icon for Empty State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-folder fs-1"></i>
                                    </div>

                                    <!-- Heading for Empty State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Categories</h2>
                                    </div>

                                    <!-- Subtext for Empty State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Categories will appear here.</p>
                                    </div>
                                </div>

                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">

                                        </div>
                                        <div v-if="categories.length > 0">
                                            <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                                <label>
                                                    <select name="kt_ecommerce_products_table_length"
                                                        aria-controls="kt_ecommerce_products_table"
                                                        class="form-select form-select-sm form-select-solid" :value="2"
                                                        v-model="pageCount" @change="perPageChange">
                                                        <option v-for="perPageCount in perPage" :key="perPageCount"
                                                            :value="perPageCount" v-text="perPageCount" />
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Category Table -->
                                <div class="row d-none d-lg-flex" v-if="categories.length != 0">
                                    <div class="table-responsive">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <!-- <th class="ps-3">#</th> -->
                                                    <th class="ps-3">Name</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="category, key in categories" :key="key">
                                                    <!-- <td class="py-2 ps-3">{{ key + 1 }}</td> -->
                                                    <td class="py-2 ps-3">{{ category.name }}</td>
                                                    <td class="py-2 text-end pe-3">
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;"
                                                                href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit category"
                                                                @click="editCategory(category.id)">
                                                                <i
                                                                    class="p-2 bi bi-pencil-square text-dark-square fs-5 text-dark"></i>
                                                            </button>
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;"
                                                                href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete category"
                                                                @click="showDeleteModal(category.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-3 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Mobile Table View -->

                                <div class="table-responsive d-block d-md-none" v-if="categories.length != 0">
                                    <div v-for="(category, key) in categories" :key="key" style="margin-bottom: 20px;">
                                        <!-- Start a new table for each category -->
                                        <table class="table table-bordered table-striped fs-6">
                                            <tbody>
                                                <!-- Category Name -->
                                                <tr>
                                                    <td class="text-gray-400 text-uppercase">Name</td>
                                                    <td class="text-end">{{ category.name }}</td>
                                                </tr>

                                                <!-- Actions -->
                                                <tr>
                                                    <td class="text-gray-400 text-uppercase">Actions</td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end">

                                                            <a href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit category"
                                                                @click="editCategory(category.id)">
                                                                <i class="p-2 bi bi-pencil-square fs-5 text-dark"></i>
                                                            </a>

                                                            <a href="javascript:void(0)" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete category"
                                                                @click="showDeleteModal(category.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </a>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- Pagination -->
                                <div v-if="categories.length != 0" class="my-3 row align-items-center ps-1 ps-md-0">
                                    <!-- Entries Per Page Dropdown -->
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
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="mb-0 pagination">
                                                <li class="paginate_button page-item previous"
                                                    :class="pagination.current_page == 1 ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_previous">
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
                                                    :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                                    id="kt_ecommerce_sales_table_next">
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
                            <h5 v-if="selectedCategoryData.id" class="modal-title" style="color: #071437">Update
                                Category</h5>
                            <h5 v-else class="modal-title" style="color: #071437">Add New Category</h5>
                        </div>
                        <div class="mb-5 col-4 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeCategoryModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="saveNewCategory" enctype="multipart/form-data">
                        <div class="text-gray-600 col-form-label">Name</div>
                        <input v-model="categoryData.name" type="text" class="form-control form-control-sm"
                            placeholder="Name" />
                        <div class="row">
                            <div class="mt-4 col-12 text-end">
                                <button v-if="selectedCategoryData.id" type="button" data-toggle="tooltip"
                                    data-placement="bottom" title="Save changes" class="mt-2 btn btn-light-info me-2"
                                    style="font-weight: bold" @click.prevent="updateCategory(selectedCategoryData.id)">
                                    UPDATE
                                </button>
                                <button v-else type="submit" class="mt-2 btn btn-info me-2" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new category" style="font-weight: bold">
                                    ADD
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
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close" @click="closeDeleteModal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-darkr" style="font-weight: bold" data-dismiss="modal"
                        data-toggle="tooltip" data-placement="bottom" title="Cancel" @click="closeDeleteModal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-light-danger" style="font-weight: bold" data-toggle="tooltip"
                        data-placement="bottom" title="Delete category"
                        @click.prevent="deleteCategory(selectedCategoryId)">
                        <i class="bi bi-trash"></i>
                        DELETE
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
        const category = (await axios.get(route('expenses.category.get', id))).data;
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


        const response = (await axios.post(route("expenses.category.store"), categoryData.value)).data;
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


        await axios.post(route('expenses.category.update', id), categoryData.value);
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
        await axios.get(route('expenses.category.all'), {
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
        const tableData = (await axios.get(route('expenses.category.all'))).data;

        categories.value = tableData.data;
        pagination.value = tableData.meta;
    } catch (error) {

    }
};

const deleteCategory = async (categoryId) => {
    try {


        await axios.delete(route("expenses.category.delete", categoryId));
        closeDeleteModal();
        successMessage('Category deleted successfully');
        getCategories();


    } catch (error) {
    }
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

onMounted(() => {
    getCategories();
});

</script>

<style lang="scss" scoped></style>
