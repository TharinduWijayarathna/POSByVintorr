<template>
    <AppLayout title="Products Management">
        <template #content>
            <div class="p-0 flex-grow-1 page-top">
                <div class="row content-margin2 justify-content-center">
                    <div class="mt-5 col-lg-12 mt-lg-0 custom-component">
                        <div class="mt-0 row d-flex justify-content-start align-items-center col-form-label">
                            <div class="mb-2 col-12 col-sm-6 col-md-6 d-flex justify-content-start align-items-center">

                                <h1 class="main-header-text">
                                    My Products
                                </h1>

                            </div>

                            <!-- Desktop view -->
                            <div
                                class="col-12 col-sm-12 col-md-8 col-xl-6 col-xxl-6 d-none d-md-flex justify-content-end justify-content-md-end">
                                <Link :href="route('product.deleted.list')" class="btn trash-btn me-3 ps-2 pe-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list">
                                <div class="trash-icon">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </div>

                                </Link>
                                <button data-toggle="tooltip" data-placement="bottom" title="Import products"
                                    @mouseover="hovered = true" @mouseleave="hovered = false" @click="openImportModal"
                                    class="btn export-btn me-2 ps-4 pe-1">
                                    <div class="export-icon">
                                        <i class="bi bi-box-arrow-in-left fs-2 export-icon "></i>
                                    </div>
                                </button>
                                <Link :href="route('products.category')" class="px-4 btn btn-info me-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Product categories"
                                    style="font-weight: bold">
                                <i class="bi bi-list"></i>Categories
                                </Link>
                                <button class="px-4 btn btn-info" data-toggle="tooltip" data-placement="bottom"
                                    title="Add new product" style="font-weight: bold" @click="openNewModal">
                                    <i class="bi bi-plus-lg"></i> Add Product
                                </button>
                            </div>

                            <!-- Mobile View -->
                            <div class="col-12 d-flex d-md-none justify-content-end justify-content-md-end">
                                <Link :href="route('product.deleted.list')" class="btn trash-btn me-2 ps-2 pe-2"
                                    data-toggle="tooltip" data-placement="bottom" title="Deleted list">
                                <div class="trash-icon">
                                    <i class="bi bi-trash-fill trash-icon fs-2"></i>
                                </div>

                                </Link>
                                <button data-toggle="tooltip" data-placement="bottom" title="Import products"
                                    @mouseover="hovered = true" @mouseleave="hovered = false" @click="openImportModal"
                                    class="btn export-btn me-2 ps-4 pe-1">
                                    <div class="export-icon">
                                        <i class="bi bi-box-arrow-in-left fs-2 export-icon"></i>
                                    </div>
                                </button>
                                <Link :href="route('products.category')"
                                    class="btn btn-info export-btn ps-3 pe-2 me-2 ps-4 pe-1" data-toggle="tooltip"
                                    data-placement="bottom" title="Product categories" style="font-weight: bold">
                                <i class="bi bi-list fs-2 export-icon ms-1"></i>
                                </Link>
                                <button class="px-4 btn btn-info" data-toggle="tooltip" data-placement="bottom"
                                    title="Add new product" style="font-weight: bold" @click="openNewModal">
                                    <i class="bi bi-plus-lg"></i> Add Product
                                </button>
                            </div>
                        </div>
                        <div class="shadow card pb-15 pb-md-0">
                            <div class="p-3 text-sm card-body p-md-9">
                                <!-- Filters -->
                                <div class="mb-3 row align-items-center d-none d-lg-flex">
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="codeFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="nameFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Search by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>

                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="minPriceFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Min. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <input v-model="maxPriceFilter" type="text"
                                            class="mb-2 form-control form-control-sm" placeholder="Max. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                    <div class="mb-2 col-xl-4 col-xxl-2 mb-xl-0 pe-xxl-0">
                                        <select class="mb-2 form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="stockFilter" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Select Stock</option>
                                            <option value="1">No Stock</option>
                                            <option value="2">Re-Order Level Reached</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-xxl-1 align-self-end">
                                        <button @click="clearFilters" class="p-5 mb-2 square-clear-button"
                                            data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <!-- <div class="mb-2 col-xl-2 col-xxl-0 mb-xl-0"></div> -->

                                </div>
                                <div class="px-2 py-3 text-sm filters-margin card-body d-block d-lg-none">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="space-x-4">
                                            <button class="p-5 square-clear-button"
                                                @click.prevent="openProductsFilterModal">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <button class="p-5 square-clear-button" @click="clearFilters"
                                                data-toggle="tooltip" data-placement="bottom" title="Clear filters">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div v-if="products.length > 0">
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

                                <!-- Product Table -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && codeFilter == '' && nameFilter == '' && minPriceFilter == '' && maxPriceFilter == '' && stockFilter == 0">

                                    <!-- Icon for No Products State -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-box fs-1"></i>
                                    </div>

                                    <!-- Heading for No Products State -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Products Available</h2>
                                    </div>

                                    <!-- Subtext for No Products State -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Your products and services will appear here once
                                            available.</p>
                                    </div>
                                </div>

                                <!-- Alternate State for No Search Results -->
                                <div class="row"
                                    v-if="emptyImageVal == 1 && (codeFilter != '' || nameFilter != '' || minPriceFilter != '' || maxPriceFilter != '' || stockFilter != 0)">

                                    <!-- Icon for No Search Results -->
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>

                                    <!-- Heading for No Search Results -->
                                    <div class="mt-8 text-center col-12">
                                        <h2 class="text-gray-700 heading fw-bold fs-2hx">No Result Found</h2>
                                    </div>

                                    <!-- Subtext for No Search Results -->
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-600 fs-5">Try adjusting your filters to find the products
                                            you're looking for.</p>
                                    </div>
                                </div>

                                <div v-if="products.length > 0" class="row">
                                    <!-- Desktop view -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                            <thead>
                                                <tr class="text-white text-start fw-bold fs-7 text-uppercase"
                                                    style="background-color: black;">
                                                    <th class="text-center">Image</th>
                                                    <th class="ps-3">Code</th>
                                                    <th class="text-start">Barcode</th>
                                                    <th>Name</th>
                                                    <th>Unit</th>
                                                    <th class="text-end">Priority</th>
                                                    <th class="text-end">Stock</th>
                                                    <th class="text-end">ROL</th>
                                                    <th class="text-end">Product Cost</th>
                                                    <th class="text-end">Selling Price</th>
                                                    <th class="text-end pe-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-semibold">
                                                <tr v-for="product in products">
                                                    <td class="py-2 text-center align-items-center">
                                                        <div class="d-flex justify-content-center">
                                                            <div class="product-admin-table-image-outline"
                                                                v-if="product.image_url">
                                                                <img :src="product.image_url"
                                                                    class="product-admin-table-image" />
                                                            </div>
                                                            <div class="product-admin-table-image-outline" v-else>
                                                                <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                                    class="product-admin-table-image" />
                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="py-2 ps-3">{{ product.code }}</td>
                                                    <td class="py-2">{{ product.barcode }}</td>
                                                    <td class="py-2">{{ truncateText(product.name) }}</td>
                                                    <td v-if="product.unit != null" class="py-2">{{ product.unit_name }}
                                                    </td>
                                                    <td v-else class="py-2"></td>
                                                    <td class="py-2 text-end">{{ product.order_no }}</td>
                                                    <td v-if="product.product_type == 1" class="py-2 text-end">{{
                                                        product.formatted_stock_qty }}</td>
                                                    <td v-else class="py-2 text-end"></td>
                                                    <td v-if="product.product_type == 1" class="py-2 text-end">{{
                                                        product.formatted_rol }}</td>
                                                    <td v-else class="py-2 text-end"></td>
                                                    <!-- <td class="py-2 text-end" :class="{
                                                        'text-danger': (product.formatted_rol > 0 && (product.formatted_rol == product.formatted_stock_qty || product.formatted_rol > product.formatted_stock_qty))
                                                    }">{{ product.formatted_rol }}</td> -->
                                                    <td class="py-2 text-end">{{ product.formatted_product_price }}</td>
                                                    <td class="py-2 text-end">{{ product.formatted_selling_price }}</td>
                                                    <td class="py-2 text-end pe-1">
                                                        <div class="d-flex justify-content-end">
                                                            <!-- Edit stock button (only visible when product_type is 1) -->
                                                            <button v-if="product.product_type == 1"
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit stock"
                                                                @click="editStock(product.id)">
                                                                <i class="p-2 bi bi-bar-chart fs-5 text-dark"></i>
                                                            </button>

                                                            <!-- Edit product button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit product"
                                                                @click="editProduct(product.id)">
                                                                <i class="p-2 bi bi-pencil-square text-dark fs-3"></i>
                                                            </button>

                                                            <!-- Print barcode button -->
                                                            <button
                                                                class="border btn btn-sm border-dark text-dark d-flex align-items-center justify-content-center me-2"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Print barcode"
                                                                @click="openBarcodeCountModal(product.id)">
                                                                <i class="p-2 bi bi-upc-scan fs-5 text-dark"></i>
                                                            </button>

                                                            <!-- Delete product button -->
                                                            <button
                                                                class="border btn btn-sm border-danger text-danger d-flex align-items-center justify-content-center"
                                                                style="width: 36px; height: 36px;" data-toggle="tooltip"
                                                                data-placement="bottom" title="Delete product"
                                                                @click="deleteProduct(product.id)">
                                                                <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>

                                                </tr>

                                                <!-- End of Table Data Rows -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile view -->
                                    <div class="table-responsive d-block d-md-none">
                                        <div v-for="product in products" :key="product.id"
                                            class="p-3 mb-4 border rounded">
                                            <table class="table table-bordered table-striped fs-6">
                                                <tbody>
                                                    <!-- Product Image -->
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="product-admin-table-image-outline"
                                                                    v-if="product.image_url">
                                                                    <img :src="product.image_url"
                                                                        class="product-admin-table-image" />
                                                                </div>
                                                                <div class="product-admin-table-image-outline" v-else>
                                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                                        class="product-admin-table-image" />
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Product Code -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Code</td>
                                                        <td class="text-end">{{ product.code }}</td>
                                                    </tr>

                                                    <!-- Product Name -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Name</td>
                                                        <td class="text-end">{{ truncateText(product.name) }}</td>
                                                    </tr>

                                                    <!-- Barcode -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Barcode</td>
                                                        <td class="text-end">{{ product.barcode }}</td>
                                                    </tr>

                                                    <!-- Product Unit -->
                                                    <tr v-if="product.unit != null">
                                                        <td class="text-gray-400 text-uppercase">Unit</td>
                                                        <td class="text-end">{{ product.unit_name }}</td>
                                                    </tr>

                                                    <!-- Product Priority -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Priority</td>
                                                        <td class="text-end">{{ product.order_no }}</td>
                                                    </tr>

                                                    <!-- Product Stock -->
                                                    <tr v-if="product.product_type == 1">
                                                        <td class="text-gray-400 text-uppercase">Stock</td>
                                                        <td class="text-end">{{ product.formatted_stock_qty }}</td>
                                                    </tr>

                                                    <!-- Product ROL -->
                                                    <tr v-if="product.product_type == 1">
                                                        <td class="text-gray-400 text-uppercase">ROL</td>
                                                        <td class="text-end">{{ product.formatted_rol }}</td>
                                                    </tr>

                                                    <!-- Product Cost -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Product Cost</td>
                                                        <td class="text-end">{{ product.formatted_product_price }}</td>
                                                    </tr>

                                                    <!-- Selling Price -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Selling Price</td>
                                                        <td class="text-end">{{ product.formatted_selling_price }}</td>
                                                    </tr>

                                                    <!-- Actions -->
                                                    <tr>
                                                        <td class="text-gray-400 text-uppercase">Actions</td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end">
                                                                <!-- Edit Stock Button -->
                                                                <a v-if="product.product_type == 1"
                                                                    href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Edit stock" @click="editStock(product.id)">
                                                                    <i class="bi bi-bar-chart text-dark fs-3"></i>
                                                                </a>
                                                                <!-- Edit Product Button -->
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Edit product"
                                                                    @click="editProduct(product.id)">
                                                                    <i class="bi bi-pencil-square text-dark fs-3"></i>
                                                                </a>
                                                                <!-- Print Barcode Button -->
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Print barcode"
                                                                    @click="openBarcodeCountModal(product.id)">
                                                                    <i class="bi bi-upc-scan text-dark fs-3"></i>
                                                                </a>
                                                                <!-- Delete Product Button -->
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete product"
                                                                    @click="deleteProduct(product.id)">
                                                                    <i class="bi bi-trash-fill fs-3 text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pagination -->
                                <div v-if="products.length > 0" class="my-3 row align-items-center">
                                    <!-- Entries Dropdown Section -->
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
                                        <div class="mb-0 text-gray-600 col-form-label me-3">
                                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total
                                            }} entries
                                        </div>
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_products_table_paginate">
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
            <!-- Product Filter Modal -->
            <div class="modal fade" id="productsFilterModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="productsFilterModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder breadcrumb-yellow text-gradient title_modal">
                                Product Filters</h5>
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
                                        <input id="modalSearchCode" v-model="codeFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Code"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchName" v-model="nameFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Search by Name"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchMinSell" v-model="minPriceFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Min. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <input id="modalSearchMaxSell" v-model="maxPriceFilter" type="text"
                                            class="form-control form-control-sm" placeholder="Max. Selling Price"
                                            @keyup="debouncedGetSearch" />
                                    </div>
                                </div>
                                <div class="mt-2 col-12 mt-md-1">
                                    <div class="items-center text-muted">
                                        <select id="modalSelectStock"
                                            class="form-select form-select-sm ps-2 pe-0 custom-select-icon"
                                            v-model="stockFilter" data-control="select2"
                                            data-placeholder="Select an option" @keyup="getSearch">
                                            <option value="0" disabled selected>Select Stock</option>
                                            <option value="1">No Stock</option>
                                            <option value="2">Re-Order Level Reached</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2">
                                        <button @click="productsFilterModalClear" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="py-4 mt-2 text-right">
                                        <button @click="applyProductsFilter" class="btn btn-info ms-4 fw-bold">
                                            <i class="bi bi-funnel"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Product Modal -->
            <div class="modal fade modal-lg add-product-modal" id="productModal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body add-product-modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Add New Product</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" @click="closeNewProductModal"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="saveProduct" enctype="multipart/form-data">

                                <div class="row">
                                    <!-- Product Image -->
                                    <div class="col-lg-4 col-12">
                                        <div class="row">
                                            <div class="pt-3 mb-5 col-md-12 d-flex justify-content-center">
                                                <div class="p-2 image-input image-chooser-border"
                                                    data-kt-image-input="true" id="product-image-content">
                                                    <div class="image-input-wrapper w-200px h-200px"
                                                        style="overflow: hidden; position: relative;">
                                                        <img v-if="product.image_url" :src="product.image_url"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Change product image" class="mb-2 img-fluid"
                                                            data-bs-toggle="dropdown"
                                                            data-kt-menu-placement="bottom-end"
                                                            style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1;" />
                                                        <img v-else data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new product image"
                                                            src="@/../src/assets/media/stock/food/image-picker-placeholder.webp"
                                                            @click="openImageFile" class="mb-2 img-fluid"
                                                            style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1;" />
                                                    </div>
                                                    <button v-if="product.image_url" type="button"
                                                        class="product-image-toggle" data-bs-toggle="dropdown"
                                                        data-kt-menu-placement="bottom-end">
                                                    </button>
                                                    <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                        data-kt-menu="true" id="toggle-box">

                                                        <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                                            @click="openImageFile">
                                                            <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                class="text-gray-700 ms-2">Change
                                                                Image</span>
                                                        </div>
                                                        <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                                            @click.prevent="selectImageRemove">
                                                            <i class="bi bi-trash text-danger"></i> <span
                                                                class="text-danger ms-2">Remove
                                                                Image</span>
                                                        </div>

                                                    </div>

                                                    <input type="file" hidden ref="fileInput"
                                                        accept="image/jpg, image/png" id="product_image" name="avatar"
                                                        @change="onFileChange">
                                                    <input type="hidden" name="avatar_remove">

                                                </div>

                                                <div class="modal-content" id="crop-modal">
                                                    <div class="modal-body">
                                                        <h5 class="mb-5 modal-title" id="imageCropperModalLabel">Crop
                                                            Image
                                                        </h5>
                                                        <div class="cropper-container">
                                                            <img ref="cropperImage" :src="cropperImageSrc"
                                                                alt="Crop Image" class="img-fluid cropper-image">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            @click="closeCrop">Cancel</button>
                                                        <button type="button" class="btn btn-primary"
                                                            @click="cropImage">Ok</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center text-gray-400 col-12"
                                                v-if="product.image_url == null">
                                                Image should be less than 5MB
                                            </div>
                                            <div class="pt-2 text-center col-12">
                                                <small v-if="!imageValidation && validationErrors.image"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.image }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 col-lg-8 row">
                                        <div class="col-lg-6">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                                title="General"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                :class="{
                                                    active: tabIndex === 1,
                                                }" @click="(tabIndex = 1)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">General</span>
                                            </a>
                                        </div>
                                        <div class="mt-2 col-lg-6 mt-lg-0">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                                title="Taxes"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                :class="{
                                                    active: tabIndex === 2,
                                                }" @click="(tabIndex = 2)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">Taxes</span>
                                            </a>
                                        </div>

                                        <!-- General Data -->
                                        <div :class="{ 'd-none': tabIndex !== 1 }" class="mt-5 col-lg-12">
                                            <div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Name</div>
                                                    <input v-model="product.name" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Name" />
                                                    <small v-if="validationErrors.name"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.name }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Barcode</div>
                                                    <input v-model="product.barcode" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Barcode" />
                                                    <small v-if="validationErrors.barcode"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.barcode }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Description</div>
                                                    <textarea v-model="product.description" class="form-control"
                                                        placeholder="Enter Product Introduction" data-kt-autosize="true"
                                                        style="resize: none; font-size: 12px;" rows="1.5"></textarea>
                                                    <small v-if="validationErrors.description"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.description }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Product Category<i
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new category"
                                                            v-if="page_props.auth.user.user_role_id == 1"
                                                            class="cursor-pointer bi bi-plus-lg-fill fs-5 ms-2 plus-btn"
                                                            @click.prevent="addNewCategory()"></i>
                                                    </div>
                                                    <Multiselect v-model="select_category" :options="categories"
                                                        class="z__index" data-toggle="tooltip" data-placement="bottom"
                                                        title="Select category" :showLabels="false"
                                                        :close-on-select="true" :clear-on-select="false"
                                                        :searchable="true" placeholder="Select Category" label="name"
                                                        track-by="id" max-height="200" />
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Product Cost</div>
                                                    <input v-model="product.cost" type="number"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Cost" />
                                                    <small v-if="validationErrors.cost"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.cost }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Selling Price</div>
                                                    <input v-model="product.selling" type="number"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Selling Price" />
                                                    <small v-if="validationErrors.selling"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.selling }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Priority</div>
                                                    <input v-model="product.order_no" type="number"
                                                        class="form-control form-control-sm" placeholder="Priority" />
                                                    <small v-if="validationErrors.order_no"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.order_no }}</small>
                                                </div>

                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Visible to inspector?
                                                    </div>
                                                </div>
                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_visible" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="visibilityRadioDefault" id="visibilityRadioDefault2"
                                                            :checked="is_visible" @change="selectVisible">
                                                        <label class="form-check-label ps-5"
                                                            for="visibilityRadioDefault2">
                                                            Yes
                                                        </label>
                                                    </div>
                                                </div>
                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_hide" class="form-check-input" type="radio"
                                                            style="width: 16px; height: 16px;"
                                                            name="visibilityRadioDefault" id="visibilityRadioDefault1"
                                                            :checked="is_hide" @change="selectHide">
                                                        <label class="form-check-label ps-5 pe-5"
                                                            for="visibilityRadioDefault1">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Do you want to keep
                                                        track
                                                        of the stock of this product?</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_non_stockable" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="flexRadioDefault" id="flexRadioDefault2"
                                                            :checked="is_non_stockable" @change="selectNonStockable">
                                                        <label class="form-check-label ps-5" for="flexRadioDefault2">
                                                            No. Don't track the stock count
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_stockable" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="flexRadioDefault" id="flexRadioDefault1"
                                                            :checked="is_stockable" @change="selectStockable">
                                                        <label class="form-check-label ps-5 pe-5"
                                                            for="flexRadioDefault1">
                                                            Yes. Track the stock count
                                                        </label>
                                                    </div>
                                                </div>

                                                <div v-if="is_stockable == true" class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">
                                                        Product Quantity
                                                    </div>
                                                    <input v-model="product.stock_quantity" type="number"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Quantity" />
                                                    <small v-if="validationErrors.stock_quantity"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.stock_quantity }}</small>
                                                </div>

                                                <div v-if="is_stockable == true" class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Re
                                                        Order Level
                                                    </div>
                                                    <input v-model="product.rol" type="number"
                                                        class="form-control form-control-sm"
                                                        placeholder="Re Order Level" />
                                                    <small v-if="validationErrors.rol"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.rol }}</small>
                                                </div>

                                                <div v-if="is_stockable == true" class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">
                                                        Unit
                                                        Type</div>
                                                    <Multiselect v-model="select_unit" :options="units" class="z__index"
                                                        :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Unit" label="title" track-by="id"
                                                        max-height="200" />
                                                </div>

                                                <div class="mt-4 col-12 text-end">
                                                    <button type="submit" class="btn btn-info" style="font-weight: bold"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Add new product">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Taxes -->
                                        <div :class="{ 'd-none': tabIndex !== 2 }" class="mt-5 col-lg-12">
                                            <div class="mt-0 row">
                                                <label class="pb-2 text-gray-600 col-form-label">Product Tax
                                                    <i data-toggle="tooltip" data-placement="bottom" title="Add new tax"
                                                        v-if="page_props.auth.user.user_role_id == 1"
                                                        class="cursor-pointer bi bi-plus-lg-fill fs-5 ms-2 plus-btn"
                                                        @click.prevent="addNewTax()"></i>
                                                </label>
                                            </div>
                                            <div class="row">
                                                <div class="col-10">
                                                    <Multiselect v-model="select_tax" :options="taxes" class="z__index"
                                                        data-toggle="tooltip" data-placement="bottom" title="Select tax"
                                                        :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Tax" label="name" track-by="id"
                                                        max-height="200" />
                                                </div>
                                                <div class="col-2">
                                                    <button class="px-4 btn btn-sm btn-info" type="button"
                                                        data-toggle="tooltip" data-placement="bottom" title="Add tax"
                                                        style="font-weight: bold" @click="addProductTaxToTempArray">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="table-responsive d-none d-md-block">
                                                <table
                                                    class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                                    <thead>
                                                        <tr
                                                            class="text-gray-400 text-start fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="ps-3">Name</th>
                                                            <th>Abbreviation</th>
                                                            <th class="text-end">Rate</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">
                                                        <!-- Table Data Rows -->
                                                        <tr v-for="tax in temp_product_taxes" class="cursor-pointer">
                                                            <td class="py-2 ps-3">{{
                                                                truncateText(tax.tax_name) }}</td>
                                                            <td class="py-2">
                                                                {{ truncateText(tax.tax_abbreviation) }}</td>
                                                            <td class="py-2 text-end">
                                                                {{ truncateText(tax.tax_rate) }}</td>
                                                            <td class="py-2 text-end pe-2">
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete tax"
                                                                    @click="removeTaxFromTemp(tax.tax_id)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </td>
                                                        </tr>

                                                        <!-- End of Table Data Rows -->
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>


                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add new Category -->
            <div class="modal fade category-card add-new-category-modal" id="categoryModal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" role="dialog">
                <div class="pb-0 modal-dialog" role="document">
                    <div class="pt-0 pb-0 modal-content category-modal">
                        <div class="p-0 pt-0 modal-body d-flex justify-content-center">
                            <div class="pt-0 pb-0 inside-category-card">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-5 col-8">
                                                    <h5 class="modal-title">Add New Category</h5>
                                                </div>
                                                <div class="mb-5 col-4 text-end">
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close" data-toggle="tooltip" data-placement="bottom"
                                                        title="Close" @click="closeCategoryModal"></button>
                                                </div>
                                            </div>
                                            <form @submit.prevent="saveNewCategory" enctype="multipart/form-data">
                                                <div class="text-gray-600 col-form-label">Name</div>
                                                <input type="text" v-model="categoryData.name"
                                                    class="form-control form-control-sm" placeholder="Name" />
                                                <div class="text-gray-600 col-form-label">Remark</div>
                                                <textarea v-model="categoryData.remarks"
                                                    class="form-control category-text-area"
                                                    placeholder="Enter Category Remark" data-kt-autosize="true"
                                                    rows="2"></textarea>
                                                <div class="row">
                                                    <div class="mt-4 col-12 text-end">
                                                        <button type="submit" class="mt-2 btn btn-info me-2 fw-bold"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new category">
                                                            ADD
                                                        </button>
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
            </div>
            <!-- Add new Tax -->
            <div class="modal fade category-card add-new-category-modal" id="taxModal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" role="dialog">
                <div class="pb-0 modal-dialog" role="document">
                    <div class="pt-0 pb-0 modal-content category-modal">
                        <div class="p-0 pt-0 modal-body d-flex justify-content-center">
                            <div class="pt-0 pb-0 inside-category-card">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-5 col-8">
                                                    <h5 class="modal-title">Add New Tax</h5>
                                                </div>
                                                <div class="mb-5 col-4 text-end">
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close" data-toggle="tooltip" data-placement="bottom"
                                                        title="Close" @click="closeTaxModal"></button>
                                                </div>
                                            </div>
                                            <form @submit.prevent="saveNewTax" enctype="multipart/form-data">
                                                <div>
                                                    <div class="text-gray-600 col-form-label">Name</div>
                                                    <input type="text" v-model="taxData.name"
                                                        class="form-control form-control-sm" placeholder="Name" />

                                                </div>
                                                <div>
                                                    <div class="text-gray-600 col-form-label">Abbreviation</div>
                                                    <input type="text" v-model="taxData.abbreviation"
                                                        class="form-control form-control-sm"
                                                        placeholder="Abbreviation" />

                                                </div>
                                                <div>
                                                    <div class="text-gray-600 col-form-label">Rate</div>
                                                    <input type="text" v-model="taxData.rate"
                                                        class="form-control form-control-sm" placeholder="Rate" />

                                                </div>
                                                <div class="row">
                                                    <div class="mt-4 col-12 text-end">
                                                        <button type="submit" class="mt-2 btn btn-info me-2 fw-bold"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new tax">
                                                            ADD
                                                        </button>
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
            </div>

            <!-- Update Product Modal -->
            <div class="modal fade modal-lg update-product-modal" id="productUpdateModal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h5 class="modal-title" style="color: #071437">Edit Product</h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" @click="closeEditProductModal"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="updateProduct" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-lg-4 col-12">
                                        <div class="row">
                                            <div class="pt-3 mb-5 col-md-12 d-flex justify-content-center">
                                                <div class="p-2 image-input image-chooser-border"
                                                    data-kt-image-input="true" id="edit-product-image-content">
                                                    <div class="image-input-wrapper w-200px h-200px"
                                                        style="overflow: hidden; position: relative;">
                                                        <img v-if="edit_product.image_url" :src="edit_product.image_url"
                                                            class="mb-2 img-fluid" data-toggle="tooltip"
                                                            data-placement="bottom" title="Change product image"
                                                            style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                                        <img v-else data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new product image"
                                                            src="@/../src/assets/media/stock/food/image-picker-placeholder.webp"
                                                            @click="openEditImageFile" class="mb-2 img-fluid"
                                                            style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                                    </div>
                                                    <button v-if="edit_product.image_url" type="button"
                                                        class="product-image-toggle" data-bs-toggle="dropdown"
                                                        data-kt-menu-placement="bottom-end">
                                                        <i
                                                            class="text-white bi bi-pencil-square text-dark-fill fs-3hx logo-edit-pencil"></i>
                                                    </button>
                                                    <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                                        data-kt-menu="true" id="edit-toggle-box">

                                                        <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                                            @click="openEditImageFile">
                                                            <i class="text-gray-700 bi bi-image-fill"></i> <span
                                                                class="text-gray-700 ms-2">Change
                                                                Image</span>
                                                        </div>
                                                        <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                                            @click.prevent="selectEditImageRemove(edit_product.id)">
                                                            <i class="bi bi-trash text-danger"></i> <span
                                                                class="text-danger ms-2">Remove
                                                                Image</span>
                                                        </div>

                                                    </div>

                                                    <input type="file" ref="fileInput" accept="image/jpg, image/png"
                                                        id="edit_product_image" name="avatar" hidden
                                                        @change="onFileChangeEdit">
                                                    <input type="hidden" name="avatar_remove">

                                                </div>

                                                <div class="modal-content" id="edit-crop-modal">
                                                    <div class="modal-body">
                                                        <h5 class="mb-5 modal-title" id="imageCropperModalLabel">Crop
                                                            Image
                                                        </h5>
                                                        <div class="cropper-container">
                                                            <img ref="cropperImage" :src="cropperEditImageSrc"
                                                                alt="Crop Image" class="img-fluid cropper-image">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            @click="closeEditCrop">Cancel</button>
                                                        <button type="button" class="btn btn-primary"
                                                            @click="cropEditImage">Ok</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center text-gray-400 col-12"
                                                v-if="edit_product.image_url == null">
                                                Image should be less than 5MB
                                            </div>
                                            <div class="pt-2 text-center col-12">
                                                <small v-if="!editImageValidation && validationErrors.image"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.image }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-lg-8 row">
                                        <div class="col-lg-6">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                                title="General"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                :class="{
                                                    active: tabIndex === 1,
                                                }" @click="(tabIndex = 1)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">General</span>
                                            </a>
                                        </div>
                                        <div class="mt-2 col-lg-6 mt-lg-0">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                                title="Taxes"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                :class="{
                                                    active: tabIndex === 2,
                                                }" @click="(tabIndex = 2)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">Taxes</span>
                                            </a>
                                        </div>

                                        <!-- General Data -->
                                        <div :class="{ 'd-none': tabIndex !== 1 }" class="mt-5 col-lg-12">

                                            <div class="">
                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Name</div>
                                                    <input v-model="edit_product.name" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Name" />
                                                    <small v-if="validationErrors.name"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.name }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Barcode</div>
                                                    <input v-model="edit_product.barcode" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Barcode" />
                                                    <small v-if="validationErrors.barcode"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.barcode }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="text-gray-600 col-form-label">Description</div>
                                                    <textarea v-model="edit_product.description" class="form-control"
                                                        placeholder="Enter Product Introduction" data-kt-autosize="true"
                                                        style="resize: none; font-size: 12px;" rows="2"></textarea>
                                                    <small v-if="validationErrors.description"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.description }}</small>
                                                </div>
                                                <div class="col-12">
                                                    <div class="text-gray-600 col-form-label">Product Category <i
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Add new category"
                                                            v-if="page_props.auth.user.user_role_id == 1"
                                                            class="cursor-pointer bi bi-plus-lg-fill fs-5 ms-2 plus-btn"
                                                            @click.prevent="addNewCategory()"></i></div>

                                                    <Multiselect v-model="select_edit_category" :options="categories"
                                                        class="z__index" data-toggle="tooltip" data-placement="bottom"
                                                        title="Select category" :showLabels="false"
                                                        :close-on-select="true" :clear-on-select="false"
                                                        :searchable="true" placeholder="Select Category" label="name"
                                                        track-by="id" max-height="200" />
                                                </div>
                                                <div class="col-12">
                                                    <div class="text-gray-600 col-form-label">Product Cost</div>
                                                    <input v-model="edit_product.cost" type="cost"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Cost" />
                                                    <small v-if="validationErrors.cost"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.cost }}</small>
                                                </div>
                                                <div class="col-12">
                                                    <div class="text-gray-600 col-form-label">Selling Price</div>
                                                    <input v-model="edit_product.selling" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Selling Price" />
                                                    <small v-if="validationErrors.selling"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.selling }}</small>
                                                </div>
                                                <div class="col-12">
                                                    <div class="text-gray-600 col-form-label">Priority</div>
                                                    <input v-model="edit_product.order_no" type="text"
                                                        class="form-control form-control-sm" placeholder="Priority" />
                                                    <small v-if="validationErrors.order_no"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.order_no }}</small>
                                                </div>
                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Visible to
                                                        inspector?
                                                    </div>
                                                </div>
                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_visible" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="visibilityRadioDefault" id="visibilityRadioDefault2"
                                                            :checked="is_visible" @change="selectVisible">
                                                        <label class="form-check-label ps-5"
                                                            for="visibilityRadioDefault2">
                                                            Yes
                                                        </label>
                                                    </div>
                                                </div>
                                                <div v-if="props.auth.user.user_role_id == 1" class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_hide" class="form-check-input" type="radio"
                                                            style="width: 16px; height: 16px;"
                                                            name="visibilityRadioDefault" id="visibilityRadioDefault1"
                                                            :checked="is_hide" @change="selectHide">
                                                        <label class="form-check-label ps-5 pe-5"
                                                            for="visibilityRadioDefault1">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Do you want to
                                                        keep track
                                                        of the stock of this product?</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_non_stockable" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="flexRadioDefault" id="flexRadioDefault2"
                                                            :checked="is_non_stockable" @change="selectNonStockable">
                                                        <label class="form-check-label ps-5" for="flexRadioDefault2">
                                                            No. Don't track the stock count
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline d-flex align-items-center">
                                                        <input v-model="is_stockable" class="form-check-input"
                                                            type="radio" style="width: 16px; height: 16px;"
                                                            name="flexRadioDefault" id="flexRadioDefault1"
                                                            :checked="is_stockable" @change="selectStockable">
                                                        <label class="form-check-label ps-5 pe-5"
                                                            for="flexRadioDefault1">
                                                            Yes. Track the stock count
                                                        </label>
                                                    </div>
                                                </div>
                                                <div v-if="is_stockable == true" class="col-12">
                                                    <div class="text-gray-600 col-form-label">Re Order Level
                                                    </div>
                                                    <input v-model="edit_product.rol" type="number"
                                                        class="form-control form-control-sm" step="1"
                                                        placeholder="Re Order Level" />
                                                    <small v-if="validationErrors.rol"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.rol }}</small>
                                                </div>
                                                <div v-if="is_stockable == true" class="col-12">
                                                    <div class="text-gray-600 col-form-label">Unit Type</div>
                                                    <Multiselect v-model="select_edit_unit" :options="units"
                                                        class="z__index" data-toggle="tooltip" data-placement="bottom"
                                                        title="Select unit type" :showLabels="false"
                                                        :close-on-select="true" :clear-on-select="false"
                                                        :searchable="true" placeholder="Select Unit" label="title"
                                                        track-by="id" max-height="200" />
                                                </div>
                                                <div class="mt-4 col-12 text-end">
                                                    <button type="submit" class="btn btn-light-info"
                                                        style="font-weight: bold" data-toggle="tooltip"
                                                        data-placement="bottom" title="Save changes">
                                                        UPDATE
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Taxes -->
                                        <div :class="{ 'd-none': tabIndex !== 2 }" class="mt-5 col-lg-12">
                                            <div class="mt-0 row">
                                                <label class="pb-2 text-gray-600 col-form-label">Product Tax
                                                    <i data-toggle="tooltip" data-placement="bottom" title="Add new tax"
                                                        v-if="page_props.auth.user.user_role_id == 1"
                                                        class="cursor-pointer bi bi-plus-lg-fill fs-5 ms-2 plus-btn"
                                                        @click.prevent="addNewTax()"></i>
                                                </label>
                                            </div>
                                            <div class="row">
                                                <div class="col-10">
                                                    <Multiselect v-model="select_edit_tax" :options="taxes"
                                                        class="z__index" data-toggle="tooltip" data-placement="bottom"
                                                        title="Select tax" :showLabels="false" :close-on-select="true"
                                                        :clear-on-select="false" :searchable="true"
                                                        placeholder="Select Tax" label="name" track-by="id"
                                                        max-height="200" />
                                                </div>
                                                <div class="col-2">
                                                    <button class="px-4 btn btn-sm btn-info" type="button"
                                                        data-toggle="tooltip" data-placement="bottom" title="Add tax"
                                                        style="font-weight: bold"
                                                        @click="addTaxesToEditProductsTemp(select_edit_tax)">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="table-responsive d-none d-md-block">
                                                <table
                                                    class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
                                                    <thead>
                                                        <tr
                                                            class="text-gray-400 text-start fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="ps-3">Name</th>
                                                            <th>Abbreviation</th>
                                                            <th class="text-end">Rate</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">
                                                        <!-- Table Data Rows -->
                                                        <tr v-for="tax in edit_product_taxes" class="cursor-pointer">
                                                            <td class="py-2 ps-3">{{
                                                                truncateText(tax.tax_name) }}</td>
                                                            <td class="py-2">
                                                                {{ truncateText(tax.tax_abbreviation) }}</td>
                                                            <td class="py-2 text-end">
                                                                {{ truncateText(tax.tax_rate) }}</td>
                                                            <td class="py-2 text-end pe-2">
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete tax" @click="taxToBeRemoved(tax)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr v-for="tax in new_taxes_to_be_added" :key="tax.tax_id"
                                                            class="cursor-pointer">
                                                            <td class="py-2 ps-3">{{ truncateText(tax.tax_name) }}</td>
                                                            <td class="py-2">{{ truncateText(tax.tax_abbreviation) }}
                                                            </td>
                                                            <td class="py-2 text-end">{{ truncateText(tax.tax_rate) }}
                                                            </td>
                                                            <td class="py-2 text-end pe-2">
                                                                <a href="javascript:void(0)" class="p-2"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Delete tax" @click="taxToBeRemoved(tax)">
                                                                    <i
                                                                        class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
                                                                </a>
                                                            </td>
                                                        </tr>

                                                        <!-- End of Table Data Rows -->
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Stock Modal -->
            <div class="modal fade" id="stockModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="updateStock" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-8">
                                        <h5 class="modal-title" style="color: #071437">Stock Update</h5>
                                    </div>
                                    <div class="mb-5 col-4 text-end">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close" data-toggle="tooltip" data-placement="bottom"
                                            title="Close"></button>
                                    </div>
                                </div>
                                <div class="mt-4 row">
                                    <div class="text-gray-600 col-5 col-md-4 form-label">Transaction Type</div>
                                    <div class="col-7 col-md-8">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="form-check form-check-inline d-flex align-items-center"
                                                    data-toggle="tooltip" data-placement="bottom" title="Stock in">
                                                    <input v-model="stock_in" class="form-check-input" type="radio"
                                                        style="width: 16px; height: 16px;" name="flexRadioDefault"
                                                        id="flexRadioDefault1" :checked="stock_in">
                                                    <label class="form-check-label ps-5" for="flexRadioDefault1">
                                                        Stock In
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="form-check form-check-inline d-flex align-items-center"
                                                    data-toggle="tooltip" data-placement="bottom" title="Stock out">
                                                    <input v-model="stock_out" class="form-check-input" type="radio"
                                                        style="width: 16px; height: 16px;" name="flexRadioDefault"
                                                        id="flexRadioDefault2" :checked="stock_out">
                                                    <label class="form-check-label ps-5" for="flexRadioDefault2">
                                                        Stock Out
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-5 text-gray-600 col-12 form-label">Quantity</div>
                                    <div class="col-12"><input v-model="stock.quantity" type="number"
                                            class="form-control form-control-sm" placeholder="Product Quantity" /></div>

                                    <div class="mt-5 text-gray-600 col-12 form-label">Remark</div>
                                    <div class="col-120"><textarea v-model="stock.remarks" class="form-control"
                                            placeholder="Enter Remark" data-kt-autosize="true"
                                            style="resize: none; font-size: 12px;" rows="2"></textarea></div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="mt-5 btn btn-light-info" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Barcode Modal -->
            <div class="modal fade" id="barcodeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="generateBarcode" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-8">
                                        <h5 class="modal-title" style="color: #071437">Barcode Print</h5>
                                    </div>
                                    <div class="mb-5 col-4 text-end">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close" data-toggle="tooltip" data-placement="bottom"
                                            title="Close"></button>
                                    </div>
                                </div>
                                <span class="text-danger">Note: This will generate the product code as a printable barcode
                                </span>
                                <div class="row">
                                    <div class="mt-5 text-gray-600 col-12 form-label">Product</div>
                                    <div class="col-12"><input v-model="product_details.name" type="text"
                                            class="form-control form-control-sm" placeholder="Product Name" disabled
                                            readonly /></div>

                                    <div class="mt-5 text-gray-600 col-12 form-label">Quantity</div>
                                    <div class="col-12"><input v-model="product_details.quantity" type="number" min="1"
                                            class="form-control form-control-sm" placeholder="Enter Quantity" /></div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="mt-5 btn btn-light-info" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes">
                                            Generate
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this product?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger" style="font-weight: bold"
                                data-toggle="tooltip" data-placement="bottom" title="Delete product"
                                @click.prevent="confirmDelete(selectedProductId)">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="import_modal" tabindex="-1" data-bs-backdrop="static"
                aria-labelledby="addNewCardTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="border-0 shadow-lg modal-content rounded-3">
                        <!-- Modal Header -->
                        <div class="pb-0 border-0 modal-header">
                            <h3 class="modal-title ms-2">Import Data</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-5 py-4 modal-body">
                            <div class="row gx-5">
                                <!-- Step 1: Download Template -->
                                <div class="p-4 text-center col-md-5 d-flex flex-column align-items-center">
                                    <div class="mb-3 text-white d-flex align-items-center justify-content-center bg-primary rounded-circle"
                                        style="width: 40px; height: 40px; font-weight: bold;">
                                        1
                                    </div>
                                    <h5 class="mb-3 fw-bold">Download Template</h5>
                                    <p class="mb-4 text-muted">Download the template file and fill in your data.</p>
                                    <button class="btn btn-primary" @click="downloadSampleExcelFile"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Download sample Excel file">
                                        Download Template
                                    </button>
                                </div>

                                <!-- Vertical Divider for Larger Screens -->
                                <div class="col-md-1 d-none d-md-flex align-items-center">
                                    <div class="mx-auto border-end h-100"
                                        style="border-color: #dee2e6 !important; height: 80%;"></div>
                                </div>

                                <!-- Step 2: Upload File -->
                                <div class="p-4 text-center col-md-5 d-flex flex-column align-items-center">
                                    <div class="mb-3 text-white d-flex align-items-center justify-content-center bg-primary rounded-circle"
                                        style="width: 40px; height: 40px; font-weight: bold;">
                                        2
                                    </div>
                                    <h5 class="mb-3 fw-bold">Upload Your File</h5>
                                    <p class="mb-4 text-muted">Upload your completed file to import the data.</p>

                                    <!-- Upload Form -->
                                    <div v-if="uploading == 0">
                                        <form role="form" @submit.prevent="importProductsFile"
                                            enctype="multipart/form-data" class="w-100">
                                            <input type="file" ref="file" class="mb-2 form-control"
                                                @input="product_file = $event.target.files[0]" />
                                            <div v-if="validationErrors.product_file" class="mt-1 text-danger small">
                                                {{ validationErrors.product_file }}
                                            </div>
                                            <button type="submit" class="mt-3 btn btn-primary w-100"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Import Excel file">
                                                Import Data
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Loading Indicator -->
                                    <div v-else class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-primary">Uploading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Image crop Modal -->
            <div class="modal fade category-card crop-product-image-modal" id="imageCropperModal"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="imageCropperModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageCropperModalLabel">Crop Image</h5>
                            <button type="button" class="btn-close" @click="closeCrop"></button>
                        </div>
                        <div class="modal-body">
                            <div class="cropper-container">
                                <img ref="cropperImage" :src="cropperImageSrc" alt="Crop Image"
                                    class="img-fluid cropper-image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeCrop">Cancel</button>
                            <button type="button" class="btn btn-primary" @click="cropImage">Ok</button>
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
import { ref, reactive, onMounted, watch, nextTick } from "vue";
import axios from 'axios';
import Multiselect from 'vue-multiselect';
import Swal from 'sweetalert2';
import { Link } from '@inertiajs/vue3';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'

import _ from 'lodash';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const { props } = usePage();
const page_props = props;

const page = ref(1);
const perPage = ref([25, 50, 100]);
const pageCount = ref(25);
const pagination = ref({});

const imageValidation = ref('');
const editImageValidation = ref('');
const products = ref([]);
const emptyImageVal = ref(0);
const categories = ref([]);
const units = ref([]);
const select_category = ref([]);
const select_edit_category = ref(null);
const select_unit = ref([]);
const select_edit_unit = ref({});

const tabIndex = ref(1);

const taxes = ref([]);
const temp_product_taxes = ref([]);
const select_tax = ref([]);
const select_edit_tax = ref(null);
const edit_product_taxes = ref([]);
const temp_taxes_to_be_deleted = ref([]);
const new_taxes_to_be_added = ref([]);

// const product = ref({});
// const edit_product = ref({});
const selectedProductId = ref(null);

const stock = ref({});
const product_details = ref({});

const stock_in = ref(false);
const stock_out = ref(false);
const is_stockable = ref(false);
const is_non_stockable = ref(true);
const is_visible = ref(true);
const is_hide = ref(false);

const uploading = ref(0);
const product_file = ref(null);

// Filter variables
const codeFilter = ref("");
const nameFilter = ref("");
const minCostFilter = ref("");
const maxCostFilter = ref("");
const minPriceFilter = ref("");
const maxPriceFilter = ref("");
const stockFilter = ref(0);

const validationErrors = ref({});
const validationMessage = ref(null);

const product = reactive({
    id: null,
    image_url: null,
    name: null,
    description: null,
    cost: null,
    selling: null,
    order_no: null,
    stock_quantity: null,
    rol: null,
});
const product_image = ref(null);
const cropped_product_image = ref(null);
const cropperImageSrc = ref('');
const cropper = ref(null);

const edit_product = reactive({
    id: null,
    image_url: null,
    name: null,
    description: null,
    cost: null,
    selling: null,
    order_no: null,
    stock_quantity: null,
    rol: null,
});
const edit_product_image = ref(null);
const cropped_edit_product_image = ref(null);
const cropperEditImageSrc = ref('');
const cropperEdit = ref(null);

const loading_bar = ref(null);


const categoryData = ref({});
const taxData = ref({});
const productModals = ref(0);

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

const openImageFile = async () => {

    const fileInput = document.getElementById('product_image');
    fileInput.click();
};



const openEditImageFile = async () => {

    const fileInput = document.getElementById('edit_product_image');
    fileInput.click();

};

// const onFileChange = (e) => {
//     product.value.image = e.target.files[0];
//     product_image.value = e.target.files[0];
// };

// const onFileChange = (e) => {

//     const fileInputs = document.getElementById('product_image');
//     if (fileInputs.files[0]) {
//         const file = fileInputs.files[0];
//         if (file.size < 5 * 1024 * 1024) {
//             product.value.image = e.target.files[0];
//             product_image.value = e.target.files[0];

//             const file = e.target.files[0];
//             if (file) {
//                 const reader = new FileReader();
//                 reader.onload = (e) => {
//                     product.value.image_url = e.target.result;
//                 };
//                 reader.readAsDataURL(file);
//             }
//         } else {
//             errorMessage('Image size should be less than 5MB');
//             const fileInput = document.getElementById('product_image');
//             fileInput.value = null;
//         }
//     }
// };

const onFileChange = (e) => {
    const fileInput = document.getElementById('product_image');
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        if (file.size < 5 * 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = (e) => {
                cropperImageSrc.value = e.target.result;
                showCropperModal();
            };
            reader.readAsDataURL(file);
        } else {
            errorMessage('Image size should be less than 5MB');
            const fileInput = document.getElementById('product_image');
            fileInput.value = null;
        }
    }
};

const showCropperModal = () => {
    const modal = new bootstrap.Modal(document.getElementById('imageCropperModal'));
    modal.show();
    nextTick(() => {
        const image = document.querySelector('#imageCropperModal img');
        cropper.value = new Cropper(image, {
            aspectRatio: NaN,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            background: false,
            cropBoxResizable: true,
            minContainerWidth: 400,
            minContainerHeight: 400,
            ready() {
                const containerData = cropper.value.getContainerData();
                const cropBoxWidth = 400;
                const cropBoxHeight = 400;

                const left = (containerData.width - cropBoxWidth) / 2;
                const top = (containerData.height - cropBoxHeight) / 2;

                cropper.value.setCropBoxData({
                    width: cropBoxWidth,
                    height: cropBoxHeight,
                    left: left,
                    top: top
                });
            }
        });
    });
};

const cropImage = () => {
    const canvas = cropper.value.getCroppedCanvas({
        width: 200,
        height: 200,
    });
    canvas.toBlob((blob) => {
        const file = new File([blob], 'cropped-image.png', { type: 'image/png' });
        product_image.value = file;
        cropped_product_image.value = product_image.value;
        const reader = new FileReader();
        reader.onload = (e) => {
            product.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    cropper.value.destroy();
    const product_image = document.getElementById('product-image-content');
    const crop_modal = document.getElementById('imageCropperModal');
    product_image.style.display = 'block';
    crop_modal.style.display = 'none';
    const fileInput = document.getElementById('product_image');
    fileInput.value = null;
    $('#imageCropperModal').modal('hide');
};

const closeCrop = () => {
    const fileInput = document.getElementById('product_image');
    fileInput.value = null;
    cropper.value.destroy();
    const product_image = document.getElementById('imageCropperModal');
    const crop_modal = document.getElementById('imageCropperModal');
    product_image.style.display = 'block';
    crop_modal.style.display = 'none';
    $('#imageCropperModal').modal('hide');
};

const selectImageRemove = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const fileInput = document.getElementById('product_image');
        fileInput.value = null;
        product.image_url = null;
        cropped_product_image.value = null;
        validationErrors.value.image = null;
        product.value.image = null;
        const toggle_box = document.getElementById('toggle-box');
        toggle_box.classList.remove('show');
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const selectEditImageRemove = async (product_id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const fileInput = document.getElementById('edit_product_image');
        fileInput.value = null;
        const toggle_box = document.getElementById('edit-toggle-box');
        toggle_box.classList.remove('show');
        edit_product.value.image_url = null;
        cropped_edit_product_image.value = null;
        await axios.get(route('product.image.remove', product_id));
        edit_product.value.image_id = null;
        edit_product.image_url = null;
        validationErrors.value.image = null;
        reload();
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

// const onFileChangeEdit = (e) => {

//     const fileInputs = document.getElementById('edit_product_image');
//     if (fileInputs.files[0]) {
//         const file = fileInputs.files[0];
//         if (file.size < 5 * 1024 * 1024) {
//             edit_product.value.image = e.target.files[0];
//             edit_product_image.value = e.target.files[0];

//             const file = e.target.files[0];
//             if (file) {
//                 const reader = new FileReader();
//                 reader.onload = (e) => {
//                     edit_product.value.image_url = e.target.result;
//                 };
//                 reader.readAsDataURL(file);
//             }
//         } else {
//             errorMessage('Image size should be less than 5MB');
//             const fileInput = document.getElementById('edit_product_image');
//             fileInput.value = null;
//         }
//     }
// };

const onFileChangeEdit = (e) => {
    const fileInput = document.getElementById('edit_product_image');
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        if (file.size < 5 * 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = (e) => {
                cropperEditImageSrc.value = e.target.result;
                showEditCropperModal();
            };
            reader.readAsDataURL(file);
        } else {
            errorMessage('Image size should be less than 5MB');
            const fileInput = document.getElementById('edit_product_image');
            fileInput.value = null;
        }
    }
};

const showEditCropperModal = () => {
    const edit_product_image = document.getElementById('edit-product-image-content');
    const edit_crop_modal = document.getElementById('edit-crop-modal');
    edit_product_image.style.display = 'none';
    edit_crop_modal.style.display = 'block';
    nextTick(() => {
        const edit_image = document.querySelector('#edit-crop-modal img');
        cropperEdit.value = new Cropper(edit_image, {
            aspectRatio: NaN, // Adjust as necessary
            viewMode: 1, // Adjust as necessary
            autoCropArea: 1, // Ensures the whole image is visible in the cropper
            responsive: true,
            background: false,
            cropBoxResizable: true, // Allow resizable crop box
            minContainerWidth: 175, // Minimum width of the container
            minContainerHeight: 175,
            ready() {
                const containerData = cropperEdit.value.getContainerData();
                const cropBoxWidth = 200; // Adjust crop box width as necessary
                const cropBoxHeight = 180; // Adjust crop box height as necessary

                const left = (containerData.width - cropBoxWidth) / 2;
                const top = (containerData.height - cropBoxHeight) / 2;

                cropperEdit.value.setCropBoxData({
                    width: cropBoxWidth,
                    height: cropBoxHeight,
                    left: left,
                    top: top
                });
            }
        });
    });
};

const cropEditImage = () => {
    const canvas = cropperEdit.value.getCroppedCanvas({
        width: 200,
        height: 200,
    });
    canvas.toBlob((blob) => {
        const file = new File([blob], 'cropped-image.png', { type: 'image/png' });
        edit_product_image.value = file;
        cropped_edit_product_image.value = edit_product_image.value;
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_product.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    cropperEdit.value.destroy();
    const edit_product_image = document.getElementById('edit-product-image-content');
    const edit_crop_modal = document.getElementById('edit-crop-modal');
    edit_product_image.style.display = 'block';
    edit_crop_modal.style.display = 'none';
    const fileInput = document.getElementById('edit_product_image');
    fileInput.value = null;
};

const closeEditCrop = () => {
    const fileInput = document.getElementById('edit_product_image');
    fileInput.value = null;
    cropperEdit.value.destroy();
    const edit_product_image = document.getElementById('edit-product-image-content');
    const edit_crop_modal = document.getElementById('edit-crop-modal');
    edit_product_image.style.display = 'block';
    edit_crop_modal.style.display = 'none';
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

const openProductsFilterModal = () => {
    loading_bar.value.start();
    $('#productsFilterModal').modal('show');
    loading_bar.value.finish();
};

const applyProductsFilter = () => {
    $('#productsFilterModal').modal('hide');
    reload();
};

const productsFilterModalClear = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    nameFilter.value = "";
    minCostFilter.value = "";
    maxCostFilter.value = "";
    minPriceFilter.value = "";
    maxPriceFilter.value = "";
    stockFilter.value = 0;
    reload();
    nextTick(() => {
        loading_bar.value.finish();
    });
    $('#productsFilterModal').modal('hide');
};

const reload = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    const tableData = (
        await axios.get(route('products.all'), {
            params: {
                page: page.value,
                per_page: pageCount.value,
                search_product_code: codeFilter.value,
                search_product_name: nameFilter.value,
                search_product_cost_min: minCostFilter.value,
                search_product_cost_max: maxCostFilter.value,
                search_product_selling_min: minPriceFilter.value,
                search_product_selling_max: maxPriceFilter.value,
                search_order_stock: stockFilter.value,
            },
        })
    ).data;

    products.value = tableData.data;
    pagination.value = tableData.meta;

    if (products.value.length > 0) {
        emptyImageVal.value = 0;
    } else {
        emptyImageVal.value = 1;
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const getTaxes = async () => {
    try {
        const response = (await axios.get(route('tax.list'))).data;
        taxes.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

const getCategories = async () => {
    try {
        const response = (await axios.get(route('categories.all'))).data;
        categories.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

const getUnits = async () => {
    try {
        const response = (await axios.get(route('units.all'))).data;
        units.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

const saveProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    imageValidation.value = '';
    try {

        const crop_modal = document.getElementById('crop-modal');
        if (window.getComputedStyle(crop_modal).display === 'none') {
            if (cropped_product_image.value != null) {
                product.value.image = cropped_product_image.value;
            } else {
                product.value.image = null;
            }

            if (select_category.value != null) {
                product.value.product_category_id = select_category.value.id;
            }

            if (select_tax.value != null) {
                product.value.product_tax_id = select_tax.value.id;
            }

            if (select_unit.value != null) {
                product.value.unit = select_unit.value.id;
                product.value.unit_name = select_unit.value.title;
            }

            if (is_stockable.value == true) {
                product.value.product_type = 1;
            }
            if (is_non_stockable.value == true) {
                product.value.product_type = 0;
            }

            if (is_visible.value == true) {
                product.value.visibility = 0;
            }
            if (is_hide.value == true) {
                product.value.visibility = 1;
            }
            product.value.name = product.name;
            product.value.description = product.description;
            product.value.cost = product.cost;
            product.value.selling = product.selling;
            product.value.order_no = product.order_no;
            product.value.stock_quantity = product.stock_quantity;
            product.value.rol = product.rol;
            product.value.barcode = product.barcode;

            const response = await axios.post(route('product.store'), product.value, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data == "this priority number already exists") {
                errorMessage('this priority number already exists');
                nextTick(() => {
                    loading_bar.value.finish();
                });
            } else {
                successMessage('Product added successfully!');

                const product_id = response.data ? response.data : null;

                if (product_id && temp_product_taxes.value.length > 0) {
                    await addTaxesToProducts(product_id);
                }

                product.value = {};
                product_image.value = null;
                select_category.value = [];
                select_tax.value = [];
                select_unit.value = [];
                temp_product_taxes.value = [];

                const fileInput = document.getElementById('product_image');
                fileInput.value = null;

                imageValidation.value = '';

                $('#productModal').modal('hide');
                reload();

            }
        } else {
            errorMessage('First crop the image');
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

const addTaxesToProducts = async (product_id) => {
    try {
        const response = await axios.post(route('product.taxes.store', product_id), temp_product_taxes.value);
        temp_product_taxes.value = [];
    } catch (error) {
        convertValidationError(error);
    }
};

const editProduct = async (id) => {

    resetValidationErrors();
    try {
        cropped_edit_product_image.value = null;
        const response = (await axios.get(route("product.get", id))).data;
        edit_product.id = response.id;
        edit_product.name = response.name;
        edit_product.description = response.description;
        edit_product.cost = response.cost;
        edit_product.selling = response.selling;
        edit_product.order_no = response.order_no;
        edit_product.stock_quantity = response.stock_quantity;
        edit_product.rol = response.rol;
        edit_product.image_url = response.image_url;
        edit_product.value = response;
        edit_product.barcode = response.barcode;

        if (edit_product.value.product_category_id) {
            select_edit_category.value = edit_product.value.product_category;
        } else {
            select_edit_category.value = null;
        }

        select_edit_unit.value = {};
        if (edit_product.value.unit) {
            select_edit_unit.value.id = edit_product.value.unit;
            select_edit_unit.value.title = edit_product.value.unit_name;
        } else {
            select_edit_unit.value = null;
        }

        if (edit_product.value.product_type == 1) {
            is_stockable.value = true;
            is_non_stockable.value = false;
        } else {
            is_non_stockable.value = true;
            is_stockable.value = false;
        }

        if (edit_product.value.visibility == 0) {
            is_visible.value = true;
            is_hide.value = false;
        } else {
            is_hide.value = true;
            is_visible.value = false;
        }

        edit_product.value.formatted_rol = edit_product.value.rol.toLocaleString();
        edit_product.value.rol = parseInt(edit_product.value.formatted_rol.replace(/,/g, ''), 10);

        document.getElementById('edit_product_image').value = '';
        editImageValidation.value = '';
        imageValidation.value = '';

        const edit_product_image = document.getElementById('edit-product-image-content');
        const edit_crop_modal = document.getElementById('edit-crop-modal');
        edit_product_image.style.display = 'block';
        edit_crop_modal.style.display = 'none';

        await getProductTaxes(id);

        productModals.value = 2;
        $('#productUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }



};

const getProductTaxes = async (product_id) => {
    try {
        const response = (await axios.get(route('product.taxes.get', product_id))).data;
        edit_product_taxes.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
};

// temporarily store the new taxes removed when updating the product
const taxToBeRemoved = (tax) => {
    const existsInEdit = edit_product_taxes.value.some(t => t.tax_id === tax.tax_id);

    if (existsInEdit) {
        temp_taxes_to_be_deleted.value.push(tax);
        edit_product_taxes.value = edit_product_taxes.value.filter(t => t.tax_id !== tax.tax_id);
    } else {
        new_taxes_to_be_added.value = new_taxes_to_be_added.value.filter(t => t.tax_id !== tax.tax_id);
    }

    successMessage('Tax removed successfully');
};

// temporarily store the new taxes selected when updating the product
const addTaxesToEditProductsTemp = () => {
    try {

        if (select_edit_tax.value != null && select_edit_tax.value != []) {
            const { id: tax_id, name: tax_name, rate: tax_rate, abbreviation: tax_abbreviation } = select_edit_tax.value;

            const tax_data = {
                tax_id: tax_id,
                tax_name: tax_name,
                tax_rate: tax_rate,
                tax_abbreviation: tax_abbreviation,
            };

            const index = new_taxes_to_be_added.value.findIndex(item => item.tax_id == tax_id);
            const already_index = edit_product_taxes.value.findIndex(item => item.tax_id == tax_id);

            if (index === -1 && already_index === -1) {
                new_taxes_to_be_added.value.push(tax_data);
                select_edit_tax.value = null;
                successMessage('Tax added successfully');
            } else {
                errorMessage('This tax already exists');
            }
        } else {
            errorMessage('No tax selected');
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

const updateProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        resetValidationErrors();
        editImageValidation.value = '';

        const edit_crop_modal = document.getElementById('edit-crop-modal');
        if (window.getComputedStyle(edit_crop_modal).display === 'none') {

            if (cropped_edit_product_image.value != null) {
                edit_product.value.image = cropped_edit_product_image.value;
            } else {
                edit_product.value.image = null;
            }

            if (select_edit_category.value != null) {
                edit_product.value.product_category_id = select_edit_category.value.id;
            } else {
                edit_product.value.product_category_id = null;
            }

            if (select_edit_unit.value != null) {
                edit_product.value.unit = select_edit_unit.value.id;
                edit_product.value.unit_name = select_edit_unit.value.title;
            } else {
                edit_product.value.unit = null;
            }

            if (is_stockable.value == true) {
                edit_product.value.product_type = 1;
            }
            if (is_non_stockable.value == true) {
                edit_product.value.product_type = 0;
            }

            if (is_visible.value == true) {
                edit_product.value.visibility = 0;
            }
            if (is_hide.value == true) {
                edit_product.value.visibility = 1;
            }
            edit_product.value.name = edit_product.name;
            edit_product.value.description = edit_product.description;
            edit_product.value.cost = edit_product.cost;
            edit_product.value.selling = edit_product.selling;
            edit_product.value.order_no = edit_product.order_no;
            edit_product.value.stock_quantity = edit_product.stock_quantity;
            edit_product.value.rol = edit_product.rol;
            edit_product.value.barcode = edit_product.barcode;

            const response = await axios.post(route("product.update", edit_product.value.id), edit_product.value, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data == "this priority number already exists") {
                errorMessage('this priority number already exists');
                nextTick(() => {
                    loading_bar.value.finish();
                });
            } else {

                if (temp_taxes_to_be_deleted.value.length > 0) {
                    await deleteTaxesFromProduct(edit_product.value.id)
                }

                if (new_taxes_to_be_added.value.length > 0) {
                    await storeNewTaxesForProduct(edit_product.value.id)
                }

                successMessage('Product updated successfully!');

                edit_product.value = {};
                edit_product_image.value = null;
                edit_product_taxes.value = [];
                temp_taxes_to_be_deleted.value = [];
                new_taxes_to_be_added.value = [];

                const fileInput = document.getElementById('edit_product_image');
                fileInput.value = null;

                editImageValidation.value = '';
                $('#productUpdateModal').modal('hide');
                reload();

            }
        } else {
            errorMessage('First crop the image');
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

// delete the taxes removed when updating the product
const deleteTaxesFromProduct = async (product_id) => {
    try {
        const response = await axios.post(route('product.taxes.delete', product_id), temp_taxes_to_be_deleted.value);

    } catch (error) {
        convertValidationNotification(error);
    }
};

// save the selected taxes when updating the product
const storeNewTaxesForProduct = async (product_id) => {
    try {
        const response = axios.post(route('product.taxes.store', product_id), new_taxes_to_be_added.value);

    } catch (error) {
        convertValidationNotification(error);
    }
};

const deleteProduct = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("product.get", id))).data;
        edit_product.value = response;

        $('#deleteModal').modal('show');
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


        const response = (await axios.delete(route("product.delete", edit_product.value.id))).data;
        successMessage('Product deleted successfully!');

        $('#deleteModal').modal('hide');
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

const updateStock = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (stock_in.value == true) {
            stock.value.transaction_type_id = 1;
        }
        if (stock_in.value == "on") {
            stock.value.transaction_type_id = 1;
        } else if (stock_out.value == "on") {
            stock.value.transaction_type_id = 0;
        }

        const response = (await axios.post(route("stock.update", stock.value.id), stock.value)).data;
        successMessage('Stock updated successfully!');

        $('#stockModal').modal('hide');
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
};

const openBarcodeCountModal = async (id) => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("product.get", id))).data;
        product_details.value = response;

        $('#barcodeModal').modal('show');
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

const generateBarcode = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (product_details.value.quantity == null || product_details.value.quantity == '') {
            errorMessage('The print quantity field is required.');
        } else if (product_details.value.quantity < 1) {
            errorMessage('The print quantity field must be at least 1.');
        } else if (product_details.value.quantity > 500) {
            errorMessage('The print quantity should be less than 500.');
        } else {
            const params = {
                product_id: product_details.value.id,
                name: product_details.value.name,
                code: product_details.value.code,
                selling: product_details.value.selling,
                print_quantity: product_details.value.quantity ?? null,
            };

            const config = {
                responseType: 'blob'  // Handle response as a blob
            };

            const response = await axios.post(route("products.barcode.print"), params, config);

            const url = window.URL.createObjectURL(new Blob([response.data], { type: response.headers['content-type'] }));
            window.open(url, "_blank");
            $('#barcodeModal').modal('hide');
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
};

watch(stockFilter, (newValue) => {
    stockFilter.value = newValue;
    getSearch();
});

function clearFilters() {
    nextTick(() => {
        loading_bar.value.start();
    });
    emptyImageVal.value = 0;
    codeFilter.value = "";
    nameFilter.value = "";
    minCostFilter.value = "";
    maxCostFilter.value = "";
    minPriceFilter.value = "";
    maxPriceFilter.value = "";
    stockFilter.value = 0;
    reload();
    nextTick(() => {
        loading_bar.value.finish();
    });
}

function openNewModal() {

    nextTick(() => {
        loading_bar.value.start();
    });
    productModals.value = 1;
    editImageValidation.value = '';
    imageValidation.value = '';
    resetValidationErrors();
    for (const key in product) {
        if (Object.prototype.hasOwnProperty.call(product, key)) {
            product[key] = null;
        }
    }
    product.value = {};
    cropped_product_image.value = null;
    select_category.value = null;
    select_tax.value = null;
    select_unit.value = null;
    const fileInput = document.getElementById('product_image');
    fileInput.value = null;
    is_stockable.value = false;
    is_non_stockable.value = true;
    is_visible.value = true;
    is_hide.value = false;
    product.image_url = null;
    const product_image = document.getElementById('product-image-content');
    const crop_modal = document.getElementById('crop-modal');
    product_image.style.display = 'block';
    crop_modal.style.display = 'none';
    $('#productModal').modal('show');
    nextTick(() => {
        loading_bar.value.finish();
    });

}

function closeNewProductModal() {

    nextTick(() => {
        loading_bar.value.start();
    });
    productModals.value = '';
    tabIndex.value = 1;
    temp_product_taxes.value = [];
    const crop_modal = document.getElementById('crop-modal');
    if (window.getComputedStyle(crop_modal).display === 'block') {
        closeCrop();
    }
    $('#productModal').modal('hide');
    nextTick(() => {
        loading_bar.value.finish();
    });

}

function closeEditProductModal() {

    nextTick(() => {
        loading_bar.value.start();
    });
    productModals.value = '';
    edit_product_taxes.value = [];
    temp_taxes_to_be_deleted.value = [];
    select_edit_tax.value = null;
    new_taxes_to_be_added.value = [];
    tabIndex.value = 1;
    const edit_crop_modal = document.getElementById('edit-crop-modal');
    if (window.getComputedStyle(edit_crop_modal).display === 'block') {
        closeEditCrop();
    }
    $('#productUpdateModal').modal('hide');
    nextTick(() => {
        loading_bar.value.finish();
    });

}

const exportProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route('product.export'))).data.url;
        const url = response;
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'products.xlsx');
        document.body.appendChild(link);
        link.click();
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

const openImportModal = () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    product_file.value = null;
    $('#import_modal').modal('show');
    nextTick(() => {
        loading_bar.value.finish();
    });

};

const downloadSampleExcelFile = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = await axios.get(route('products.download_sample_excel'), { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;

        const date = new Date();
        const file_name = `products-${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}T${date.getHours().toString().padStart(2, '0')}-${date.getMinutes().toString().padStart(2, '0')}-${date.getSeconds().toString().padStart(2, '0')}.xlsx`;

        link.setAttribute('download', file_name); // Set the download attribute to the generated file name
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
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


const importProductsFile = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        resetValidationErrors();
        uploading.value = 1;
        const formData = new FormData();

        if (!product_file.value) {
            errorMessage('Please upload an Excel file (XLSX)');
            uploading.value = 0;
            product_file.value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }

        // Check file type
        if (product_file.value.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            errorMessage('File type is not valid. Please upload an Excel file (XLSX)');
            uploading.value = 0;
            product_file.value = null;
            document.querySelector('input[type="file"]').value = null;
            nextTick(() => {
                loading_bar.value.finish();
            });
            return;
        }

        formData.append('product_file', product_file.value);

        const response = (await axios.post(route('products.import'), formData)).data;

        product_file.value = null;

        if (response.message) {
            successMessage(response.message);
            uploading.value = 0;
            $('#import_modal').modal('hide');
            reload();
        }

        if (response.errors && response.errors.length > 0) {
            let errorMessages = response.errors.toString().replace(/,/g, '<br>');
            if (response.message) {
                errorMessages = `${response.message}<br>${errorMessages}`;
            }
            uploading.value = 0;
            errorMessage(`${errorMessages}`);
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

        uploading.value = 0;
        product_file.value = null;
        convertValidationError(error);
    }
};


const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const selectStockable = () => {
    is_non_stockable.value = false;
    is_stockable.value = true;
};

const selectNonStockable = () => {
    is_non_stockable.value = true;
    is_stockable.value = false;
};

const selectVisible = () => {
    is_visible.value = true;
    is_hide.value = false;
};

const selectHide = () => {
    is_visible.value = false;
    is_hide.value = true;
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
        customClass: {
            popup: 'custom-swal-toast',
        },
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
        customClass: {
            popup: 'custom-swal-toast',
        },
    });
};

const addNewCategory = () => {
    categoryData.value = {};
    $('#categoryModal').modal('show');
}

const closeCategoryModal = () => {
    $('#categoryModal').modal('hide');
}

const addNewTax = () => {
    taxData.value = {};
    $('#taxModal').modal('show');
}

const closeTaxModal = () => {
    $('#taxModal').modal('hide');
}

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
            setNewCategory();
        }


    } catch (error) {
        convertValidationNotification(error);
    }
};

const setNewCategory = async () => {
    resetValidationErrors();
    try {

        const response = await axios.get(route("category.latest.get"));
        if (productModals.value == 2) {
            select_edit_category.value = response.data;
        } else if (productModals.value == 1) {
            select_category.value = response.data;
        }



    } catch (error) {
        convertValidationNotification(error);
    }
};

const saveNewTax = async () => {
    resetValidationErrors();
    try {


        const response = (await axios.post(route("tax.store"), taxData.value)).data;
        if (response === "This tax already exists") {
            errorMessage('This tax already exists');
        } else {
            successMessage('New tax registration is successful');
            closeTaxModal();
            getTaxes();
            setNewTax();
        }


    } catch (error) {
        convertValidationNotification(error);
    }
}


const setNewTax = async () => {
    resetValidationErrors();
    try {

        const response = await axios.get(route("tax.latest.get"));
        if (productModals.value == 2) {
            select_edit_tax.value = response.data;
        } else if (productModals.value == 1) {
            select_tax.value = response.data;
        }



    } catch (error) {
        convertValidationNotification(error);
    }
}

// temporarily store the taxes selected when creating a product
const addProductTaxToTempArray = async () => {
    try {

        if (select_tax.value != null && select_tax.value != []) {
            const tax = select_tax.value;

            const tax_id = tax.id;
            const tax_name = tax.name;
            const tax_rate = tax.rate;
            const tax_abbreviation = tax.abbreviation;

            const tax_data = {
                tax_id: tax_id,
                tax_name: tax_name,
                tax_rate: tax_rate,
                tax_abbreviation: tax_abbreviation,
            };

            const index = temp_product_taxes.value.findIndex(item => item.tax_id === tax_id);

            if (index === -1) {
                temp_product_taxes.value.push(tax_data);
                select_tax.value = null;
                successMessage('Tax added successfully');

            } else {
                errorMessage('This tax already exists');
            }
        } else {
            errorMessage('No tax selected');
        }

    } catch (error) {
        convertValidationNotification(error);
    }
};

// remove from the temporarily selected taxes when creating a product
const removeTaxFromTemp = (tax_id) => {
    temp_product_taxes.value = temp_product_taxes.value.filter(item => item.tax_id !== tax_id);

    successMessage('Tax removed successfully');
};


onMounted(() => {
    reload();
    getCategories();
    getTaxes();
    getUnits();

    if (page_props.check_rol == 2) {
        stockFilter.value = 2;
        reload();
    }

    const crop_modal = document.getElementById('crop-modal');
    crop_modal.style.display = 'none';

    const edit_crop_modal = document.getElementById('edit-crop-modal');
    edit_crop_modal.style.display = 'none';

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
.product-image-header {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;

    .product-image-outline {
        width: 200px;
        height: 200px;
        overflow: hidden;

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
}


@media (max-width: 767.98px) {
    .download-btn {
        margin-top: 10px !important;
    }
}

@media (min-width: 768px) {
    .download-btn {
        margin-top: 44px !important;
    }
}

.modal-dialog-width {
    max-width: 600px;
}

.modal-body-padding {
    padding: 14px;
}

.download-section {
    background-color: #F3F2F7;
    position: relative;
    border-radius: 10px;
}

.download-caption {
    font-size: 12px;
    font-weight: 600;
    color: #a4a4a7;
}

.main-modal-section {
    column-gap: 14px;
}

.download-btn-header {
    position: absolute;
    bottom: 16px;
    left: 0;
    right: 0;
    margin: auto;
}

.add-btn-section {
    background-color: #F3F2F7;
    border-radius: 10px;
}
</style>
