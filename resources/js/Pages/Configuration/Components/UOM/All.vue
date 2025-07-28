<template>

    <!-- Desktop View -->
    <div class="row d-none d-md-flex">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                <div class="-mb-3 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                    <h1 class="text-gray-600">Unit of Measurements</h1>
                </div>
                <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                    <a href="javascript:void(0)" class="btn btn-info fw-bold" data-toggle="tooltip"
                        data-placement="bottom" title="Add new unit of measurement" @click.prevent="openAddUOM">
                        <i class="bi bi-plus-lg"></i> ADD NEW
                    </a>
                </div>
            </div>
            <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                <div class="-mb-3 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                    <h3 class="text-gray-500">Unit Name</h3>
                </div>
                <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                    <div v-if="unitOfMeasures.length > 0" class=" ps-0 d-flex justify-content-end align-self-end">
                        <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                            <label><select name="kt_ecommerce_products_table_length"
                                    aria-controls="kt_ecommerce_products_table"
                                    class="form-select form-select-sm form-select-solid" :value="2" v-model="pageCount"
                                    @change="perPageChange">
                                    <option v-for="perPageCount in perPage" :key="perPageCount" :value="perPageCount"
                                        v-text="perPageCount" />
                                </select></label>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="unitOfMeasures.length > 0" class="row">
                <div class="table-responsive ">
                    <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                        <tbody class="text-gray-600 fw-semibold">
                            <!-- Table Data Rows -->
                            <tr v-for="(uom, index) in unitOfMeasures" :key="index" class="cursor-pointer">
                                <td class="py-2" @click.prevent="editInvoice(uom.id)">{{ uom.title }}</td>
                                <td class="py-2 text-end">
                                    <a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="bottom"
                                        title="Edit unit of measurement" @click="showEditModal(uom.id)">
                                        <i class="bi bi-pencil-square text-dark fs-3 me-3"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="bottom"
                                        title="Delete unit of measurement" @click="showDeleteModal(uom.id)">
                                        <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div v-if="unitOfMeasures.length > 0" class="my-3 row">
                <div class="col-sm-6">
                    <div for="purchase_uom" class="text-gray-600 col-form-label">
                        Showing {{ pagination.from }} to
                        {{ pagination.to }} of
                        {{ pagination.total }} entries
                    </div>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <div class="dataTables_paginate paging_simple_numbers" id="kt_ecommerce_sales_table_paginate">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous"
                                :class="pagination.current_page == 1 ? 'disabled' : ''"
                                id="kt_ecommerce_sales_table_previous">
                                <a href="javascript:void(0)" @click="setPage(pagination.current_page - 1)"
                                    aria-controls="kt_ecommerce_sales_table" data-dt-idx="0" tabindex="0"
                                    class="page-link"><i class="previous"></i></a>
                            </li>
                            <template v-for="(page, index) in pagination.last_page">
                                <template
                                    v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                    <li class="paginate_button page-item" :key="index"
                                        :class="pagination.current_page == page ? 'active' : ''"><a
                                            href="javascript:void(0)" @click="setPage(page)"
                                            aria-controls="kt_ecommerce_sales_table" data-dt-idx="1" tabindex="0"
                                            class="page-link">{{ page }}</a></li>
                                </template>
                            </template>
                            <li class="paginate_button page-item next"
                                :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                id="kt_ecommerce_sales_table_next"><a href="javascript:void(0)"
                                    @click="setPage(pagination.current_page + 1)"
                                    aria-controls="kt_ecommerce_sales_table" data-dt-idx="6" tabindex="0"
                                    class="page-link"><i class="next"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <!-- Mobile View -->
    <div class="d-block d-md-none w-100">
        <div class="col-12">
            <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                <div class="mb-2 col-12 d-flex justify-content-end align-items-center">
                    <a href="javascript:void(0)" class="btn btn-info fw-bold" data-toggle="tooltip"
                        data-placement="bottom" title="Add new unit of measurement" @click.prevent="openAddUOM">
                        <i class="bi bi-plus-lg"></i> ADD NEW
                    </a>
                </div>
                <div class="-mb-3 col-12 d-flex justify-content-start align-items-center">
                    <h1 class="text-gray-600">Unit of Measurements</h1>
                </div>
            </div>
            <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                <div class="-mb-3 col-6 d-flex justify-content-start align-items-center">
                    <h3 class="text-gray-500">Unit Name</h3>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <div v-if="unitOfMeasures.length > 0" class=" ps-0 d-flex justify-content-end align-self-end">
                        <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                            <label><select name="kt_ecommerce_products_table_length"
                                    aria-controls="kt_ecommerce_products_table"
                                    class="form-select form-select-sm form-select-solid" :value="2" v-model="pageCount"
                                    @change="perPageChange">
                                    <option v-for="perPageCount in perPage" :key="perPageCount" :value="perPageCount"
                                        v-text="perPageCount" />
                                </select></label>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="unitOfMeasures.length > 0" class="row">
                <div class="table-responsive ">
                    <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                        <tbody class="text-gray-600 fw-semibold">
                            <tr v-for="(uom, index) in unitOfMeasures" :key="index" class="cursor-pointer">
                                <td class="py-2" @click.prevent="editInvoice(uom.id)">{{ uom.title }}</td>
                                <td class="py-2 text-end">
                                    <a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="bottom"
                                        title="Edit unit of measurement" @click="showEditModal(uom.id)">
                                        <i class="bi bi-pencil-square text-dark fs-3 me-3"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="bottom"
                                        title="Delete unit of measurement" @click="showDeleteModal(uom.id)">
                                        <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="unitOfMeasures.length > 0" class="my-3 row">
                <div class="col-sm-6">
                    <div for="purchase_uom" class="text-gray-600 col-form-label">
                        Showing {{ pagination.from }} to
                        {{ pagination.to }} of
                        {{ pagination.total }} entries
                    </div>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <div class="dataTables_paginate paging_simple_numbers" id="kt_ecommerce_sales_table_paginate">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous"
                                :class="pagination.current_page == 1 ? 'disabled' : ''"
                                id="kt_ecommerce_sales_table_previous">
                                <a href="javascript:void(0)" @click="setPage(pagination.current_page - 1)"
                                    aria-controls="kt_ecommerce_sales_table" data-dt-idx="0" tabindex="0"
                                    class="page-link"><i class="previous"></i></a>
                            </li>
                            <template v-for="(page, index) in pagination.last_page">
                                <template
                                    v-if="page == 1 || page == pagination.last_page || Math.abs(page - pagination.current_page) < 5">
                                    <li class="paginate_button page-item" :key="index"
                                        :class="pagination.current_page == page ? 'active' : ''"><a
                                            href="javascript:void(0)" @click="setPage(page)"
                                            aria-controls="kt_ecommerce_sales_table" data-dt-idx="1" tabindex="0"
                                            class="page-link">{{ page }}</a></li>
                                </template>
                            </template>
                            <li class="paginate_button page-item next"
                                :class="pagination.current_page == pagination.last_page ? 'disabled' : ''"
                                id="kt_ecommerce_sales_table_next"><a href="javascript:void(0)"
                                    @click="setPage(pagination.current_page + 1)"
                                    aria-controls="kt_ecommerce_sales_table" data-dt-idx="6" tabindex="0"
                                    class="page-link"><i class="next"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="uomModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-6 col-10">
                            <h5 class="text-gray-900 modal-title">ADD NEW UNIT OF MEASUREMENT
                            </h5>
                        </div>
                        <div class="mb-6 col-2 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeAddModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="saveUOM" enctype="multipart/form-data">
                        <div class="mt-6 mb-6 row">
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Name</label>
                                <input v-model="unitOfMeasure.name" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mt-4 col-12 text-end">
                                <button type="submit" class="px-10 mt-2 btn btn-info me-2 fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new Unit of measurement">
                                    ADD
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close" @click="closeDeleteModal"></button>
                </div>
                <div class="modal-body fs-7">
                    Are you sure you want to delete this UOM?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-darkr fw-bold" data-dismiss="modal" data-toggle="tooltip"
                        data-placement="bottom" title="Cancel" @click="closeDeleteModal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-light-danger fw-bold" data-toggle="tooltip"
                        data-placement="bottom" title="Delete selected unit of measurement"
                        @click.prevent="deleteUOM(u_o_m_id)">
                        <i class="bi bi-trash"></i>
                        DELETE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-6 col-10">
                            <h5 class="text-gray-900 modal-title">EDIT UNIT OF MEASUREMENT
                            </h5>
                        </div>
                        <div class="mb-6 col-2 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeEditModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="editUOM" enctype="multipart/form-data">
                        <div class="mt-6 mb-6 row">
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Name</label>
                                <input v-model="selectUOM.name" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Name" />

                            </div>
                        </div>
                        <div class="row">
                            <div class="mt-4 col-12 text-end">
                                <button type="submit" class="btn btn-light-info fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Save changes">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <Loader ref="loading_bar" />
</template>


<script setup>
import { ref, reactive, onMounted, nextTick, watch } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const loading_bar = ref(null);
const unitOfMeasures = ref({});
const validationErrors = ref({});
const validationMessage = ref(null);

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});
const u_o_m_id = ref(0);
const unitOfMeasure = ref({});
const selectUOM = ref({});

const perPageChange = async () => {
    page.value = 1;
    reload();
};

const setPage = async (new_page) => {
    page.value = new_page;
    reload();
};

const saveUOM = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const response = await axios.post(route('units.store'), unitOfMeasure.value);
        successMessage('New UOM created successfully');
        $('#uomModal').modal('hide');
        reload();
        if (response.data == 'This unit already exists') {
            errorMessage("This UOM already exists");
        }

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

const editUOM = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {

        await axios.post(route('units.update', selectUOM.value.id), selectUOM.value);
        successMessage('UOM updated successfully');
        $('#editModal').modal('hide');
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

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('units.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,

            },
        })
    ).data;

    unitOfMeasures.value = tableData.data;
    pagination.value = tableData.meta;


    nextTick(() => {
        loading_bar.value.finish();
    });
};


const deleteUOM = async (uomID) => {
    try {
        await axios.delete(route("units.delete", uomID));
        closeDeleteModal();
        successMessage('UOM deleted successfully');
        reload();
    } catch (error) {
    }
}

function showDeleteModal(uom_ID) {
    u_o_m_id.value = uom_ID;
    $("#deleteModal").modal("show");
}

const showEditModal = async (uom_ID) => {
    u_o_m_id.value = uom_ID;
    const response = await axios.get(route("units.get", uom_ID));
    selectUOM.value = response.data;
    selectUOM.value.name = response.data.title;
    $("#editModal").modal("show");
}

function closeDeleteModal() {
    $("#deleteModal").modal("hide");
}

function closeEditModal() {
    $("#editModal").modal("hide");
}

function closeAddModal() {
    $("#uomModal").modal("hide");
}

const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
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

const openAddUOM = async () => {
    unitOfMeasure.value = {};
    $('#uomModal').modal('show');
}

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    let { message } = error.response.data;
    errorMessage(message);
};

onMounted(() => {
    reload();
});

</script>

<style lang="css" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}
</style>
