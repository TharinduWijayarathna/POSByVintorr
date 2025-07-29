<template>

    <!-- Desktop View -->
    <div class="row d-none d-md-flex">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
                <div class="-mb-3 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">
                    <h1 class="text-gray-600">Tax Registry</h1>
                </div>
                <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-end align-items-center">
                    <a href="javascript:void(0)" class="btn btn-info fw-bold" data-toggle="tooltip"
                        data-placement="bottom" title="Add new tax" @click.prevent="openAddTax">
                        <i class="bi bi-plus-lg"></i> ADD NEW
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 row align-items-center me-0 pe-0">
                    <div class="mb-2 col-md-3 mb-md-0">
                        <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">Search</div>
                        <input v-model="search_tax" type="text" class="form-control form-control-sm" placeholder=""
                            @keyup="debouncedGetSearch" />
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button @click="clearFilters" class="p-5 square-clear-button" data-toggle="tooltip"
                            data-placement="bottom" title="Clear filters">
                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="mb-2 col-md-5 mb-md-0">
                    </div>
                    <div v-if="taxes.length > 0" class="col-md-2 d-flex justify-content-end align-self-end me-0 pe-0">
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

                <!-- Empty State -->
                <div class="row" v-if="emptyImageVal == 1 && search_tax == ''">
                    <div class="text-center col-12 mt-15">
                        <i class="text-gray-400 bi bi-currency-exchange fs-1"></i>
                    </div>
                    <div class="mt-8 text-center col-12">
                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No any taxes</h1>
                    </div>
                    <div class="mt-2 text-center col-12 mb-15">
                        <p class="text-gray-700 fs-3">Your taxes will appear here</p>
                    </div>
                </div>
                <div class="row" v-if="emptyImageVal == 1 && (search_tax != '')">
                    <div class="text-center col-12 mt-15">
                        <i class="text-gray-400 bi bi-search fs-1"></i>
                    </div>
                    <div class="mt-8 text-center col-12 mb-15">
                        <h1 class="text-gray-700 heading fw-bold fs-2hx">No result found</h1>
                    </div>
                </div>

                <div v-if="taxes.length > 0">
                    <div class="table-responsive ">
                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                            <thead class="text-gray-500">
                                <tr>
                                    <th class="py-2 text-start ps-6 ms-0">
                                        <h3 class="text-gray-500 ps-0 ms-0">Name</h3>
                                    </th>
                                    <th class="py-2">
                                        <h3 class="text-gray-500">Abbreviation</h3>
                                    </th>
                                    <th class="py-2 text-right">
                                        <h3 class="text-gray-500">Rate</h3>
                                    </th>
                                    <th class="py-2 text-end pe-6 me-0">
                                        <!-- <div v-if="taxes.length > 0"
                                    class=" ps-0 d-flex justify-content-end align-self-end pe-0 me-0">
                                    <div class="dataTables_length" id="kt_ecommerce_products_table_length">
                                        <label><select name="kt_ecommerce_products_table_length"
                                                aria-controls="kt_ecommerce_products_table"
                                                class="form-select form-select-sm form-select-solid" :value="2"
                                                v-model="pageCount" @change="perPageChange">
                                                <option v-for="perPageCount in perPage" :key="perPageCount"
                                                    :value="perPageCount" v-text="perPageCount" />
                                            </select></label>
                                    </div>
                                </div> -->
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <!-- Table Data Rows -->
                                <tr v-for="(tax, index) in taxes" :key="index" class="cursor-pointer">
                                    <td class="py-2 ps-6">{{ tax.name }}</td>
                                    <td class="py-2">{{ tax.abbreviation }}</td>
                                    <td class="py-2 text-right">{{ tax.rate }}</td>
                                    <td class="py-2 pe-6 text-end">
                                        <a href="javascript:void(0)" class="" data-toggle="tooltip"
                                            data-placement="bottom" title="Edit tax" @click="showEditModal(tax.id)">
                                            <i class="bi bi-pencil-square text-dark fs-3 me-3"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="" data-toggle="tooltip"
                                            data-placement="bottom" title="Delete tax" @click="showDeleteModal(tax.id)">
                                            <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div v-if="taxes.length > 0" class="my-3 row">
                <div class="col-sm-6">
                    <div for="purchase_tax" class="text-gray-600 col-form-label">
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
    <div class="table-responsive d-block d-md-none">
        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
            <div class="mb-2 col-12 d-flex justify-content-end align-items-center">
                <a href="javascript:void(0)" class="btn btn-info fw-bold" data-toggle="tooltip" data-placement="bottom"
                    title="Add new unit of measurement" @click.prevent="openAddTax">
                    <i class="bi bi-plus-lg"></i> ADD NEW
                </a>
            </div>
            <div class="-mb-3 col-12 d-flex justify-content-start align-items-center">
                <h1 class="text-gray-600">Tax Registry</h1>
            </div>
        </div>
        <div class="flex-wrap mt-0 d-flex justify-content-start align-items-center col-form-label">
            <div class="mb-2 col-6 mb-md-0">
                <div for="purchase_uom" class="pt-0 pb-1 text-gray-600 col-form-label">Search</div>
                <input v-model="search_tax" type="text" class="form-control form-control-sm" placeholder=""
                    @keyup="debouncedGetSearch" />
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
                <div v-if="taxes.length > 0" class=" ps-0 d-flex justify-content-end align-self-end">
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
        <table class="fs-6" width="100%">
            <tbody>
                <tr class="odd" v-for="tax in taxes">
                    <td class="table-responsive-width" @click="showEditModal(tax.id)">
                        <div class="row">
                            <div class="text-gray-400 col-6 col-sm-5 left-side text-uppercase">
                                Name</div>
                            <div class="col-6 col-sm-7 right-side text-end">
                                <div>
                                    {{ tax.name }}
                                </div>
                            </div>
                        </div>
                        <div class="row row-divider">
                            <div class="text-gray-400 col-6 col-sm-5 left-side text-uppercase">
                                Abbreviation</div>
                            <div class="col-6 col-sm-7 right-side text-end">
                                <span>{{ tax.abbreviation }}</span>
                            </div>
                        </div>

                        <div class="row row-divider">
                            <div class="text-gray-400 col-6 col-sm-5 left-side text-uppercase">
                                Rate</div>
                            <div class="col-6 col-sm-7 right-side text-end">
                                <span>{{ tax.rate }}</span>
                            </div>
                        </div>
                        <div class="row row-divider">
                            <div class="text-gray-400 col-6 col-sm-5 left-side text-uppercase">
                            </div>
                            <div class="col-6 col-sm-7 right-side text-end ">
                                <a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="bottom"
                                    title="Deleted tax" @click="showDeleteModal(tax.id)">
                                    <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                </a>
                            </div>
                        </div>
                        <!-- <hr class="hr-no-margin "> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="taxModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-6 col-10">
                            <h5 class="text-gray-900 modal-title">ADD NEW TAX
                            </h5>
                        </div>
                        <div class="mb-6 col-2 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeAddModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="saveTax" enctype="multipart/form-data">
                        <div class="mt-6 mb-6 row">
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Name</label>
                                <input v-model="tax.name" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>
                            </div>
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Abbreviation</label>
                                <input v-model="tax.abbreviation" type="number" class="form-control form-control-sm"
                                    placeholder="Enter Abbreviation" />
                                <small v-if="validationErrors.abbreviation"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.abbreviation }}</small>
                            </div>
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Rate</label>
                                <input v-model="tax.rate" type="number" class="form-control form-control-sm"
                                    placeholder="Enter Rate" />
                                <small v-if="validationErrors.rate"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.rate }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mt-4 col-12 text-end">
                                <button type="submit" class="px-10 mt-2 btn btn-info me-2 fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Add new Tax">
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
    <div class="modal fade" id="deleteTaxModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        data-toggle="tooltip" data-placement="bottom" title="Close" @click="closeDeleteModal"></button>
                </div>
                <div class="modal-body fs-7">
                    Are you sure you want to delete this Tax?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-darkr fw-bold" data-dismiss="modal" data-toggle="tooltip"
                        data-placement="bottom" title="Cancel" @click="closeDeleteModal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-light-danger fw-bold" data-toggle="tooltip"
                        data-placement="bottom" title="Delete selected tax" @click.prevent="deleteTax(tax_id)">
                        <i class="bi bi-trash"></i>
                        DELETE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-6 col-10">
                            <h5 class="text-gray-900 modal-title">EDIT TAX
                            </h5>
                        </div>
                        <div class="mb-6 col-2 text-end">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeEditModal"></button>
                        </div>
                    </div>
                    <form @submit.prevent="editTax" enctype="multipart/form-data">
                        <div class="mt-6 mb-6 row">
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Name</label>
                                <input v-model="selectTax.name" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Name" />
                                <small v-if="validationErrors.name"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.name }}</small>

                            </div>
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Abbreviation</label>
                                <input v-model="selectTax.abbreviation" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Abbreviation" />
                                <small v-if="validationErrors.abbreviation"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.abbreviation }}</small>

                            </div>
                            <div class="mb-4 col-12">
                                <label class="mb-2 text-gray-600">Rate</label>
                                <input v-model="selectTax.rate" type="text" class="form-control form-control-sm"
                                    placeholder="Enter Rate" />
                                <small v-if="validationErrors.rate"
                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                    {{ validationErrors.rate }}</small>

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
import _ from 'lodash';

const { props } = usePage();

const loading_bar = ref(null);
const taxes = ref({});
const validationErrors = ref({});
const validationMessage = ref(null);

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});
const tax_id = ref(0);
const tax = ref({});
const selectTax = ref({});

const search_tax = ref("");
const emptyImageVal = ref(0);

const perPageChange = async () => {
    page.value = 1;
    reload();
};

const setPage = async (new_page) => {
    page.value = new_page;
    reload();
};

const getSearch = async () => {
    page.value = 1;
    reload();
};

const debouncedGetSearch = _.debounce(getSearch, 1000);


// Clear filters Method
function clearFilters() {
    emptyImageVal.value = 0;
    search_tax.value = "";
    reload();
}

const saveTax = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {
        const response = await axios.post(route('tax.store'), tax.value);
        successMessage('New Tax created successfully');
        $('#taxModal').modal('hide');
        reload();
        if (response.data == 'This tax already exists') {
            errorMessage("This Tax already exists");
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
}

const editTax = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {

        await axios.post(route('tax.update', selectTax.value.id), selectTax.value);
        successMessage('Tax updated successfully');
        $('#editTaxModal').modal('hide');
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
}

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('tax.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                "filter[search]": search_tax.value,
            },
        })
    ).data;

    taxes.value = tableData.data;
    pagination.value = tableData.meta;

    if (taxes.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};


const deleteTax = async (uomID) => {
    try {

        await axios.delete(route("tax.delete", uomID));
        closeDeleteModal();
        successMessage('Tax deleted successfully');
        reload();


    } catch (error) {
    }
}

function showDeleteModal(id) {

    tax_id.value = id;
    $("#deleteTaxModal").modal("show");

}

const showEditModal = async (id) => {
    resetValidationErrors();

    tax_id.value = id;
    const response = await axios.get(route("tax.get", id));
    selectTax.value = response.data;
    $("#editTaxModal").modal("show");

}

function closeDeleteModal() {
    $("#deleteTaxModal").modal("hide");
}

function closeEditModal() {
    $("#editTaxModal").modal("hide");
}

function closeAddModal() {
    $("#taxModal").modal("hide");
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

const openAddTax = async () => {

    tax.value = {};
    $('#taxModal').modal('show');

}

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    let { message } = error.response.data;
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


onMounted(() => {
    reload();
});

</script>

<style lang="css" scoped>
.cursor-pointer:hover {
    background-color: #f0f0f0;
}
</style>
