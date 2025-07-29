<template>
    <div ref="fullscreenContent" id="full-screen" class="bg-secondary">
        <PosLayout title="POS Cart">
            <template #content>

                <div class="p-0 bg-white flex-grow-1 page-top">
                    <div class="row content-margin3">
                        <!-- Item section -->
                        <div class="mt-5 col-lg-6 col-xl-7 mt-lg-0 pb-lg-0 d-none d-md-none d-lg-block">
                            <div class="border input-group rounded-3 align-items-center">
                                <i class="cursor-pointer bi bi-upc-scan fs-2 ms-3 me-1" @click="openQrScannerModal"></i>
                                <input type="text" class="border-0 form-control cart-search" name="search"
                                    data-toggle="tooltip" data-placement="bottom" title="Search items"
                                    v-model="cart.name" aria-label="Example text with button addon"
                                    aria-describedby="button-addon1" placeholder="Product search / Barcode"
                                    v-bind="$attrs" autofocus ref="barcodeInput" />
                                <div class="input-group-append">
                                    <button class="px-2 bg-white border-0 btn" type="button " data-toggle="tooltip"
                                        data-placement="bottom" title="Clear filter" @click="clearFilters">
                                        <i class="fa fa-x" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12">
                                    <div class="p-2 card payment-done-card">
                                        <div class="mx-1 row horizontal-btn-scroller">
                                            <div class="mt-1 mb-1 col-6 col-md-3">
                                                <a href="javascript:void(0)" @click.prevent="getAllProducts"
                                                    class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                    :class="{
                                                        active: selectedId === null,
                                                    }" data-kt-button="true">
                                                    <span class="fs-7 fw-bold d-block">All</span>
                                                </a>
                                            </div>
                                            <div v-for="category in categories" class="mt-1 mb-1 col-6 col-md-3">
                                                <a href="javascript:void(0)" @click.prevent="
                                                    getProductsFromCategory(
                                                        category.id
                                                    )
                                                    "
                                                    class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                    :class="{
                                                        active:
                                                            category.id ===
                                                            selectedId,
                                                    }" @click="selectItem(category.id)" data-kt-button="true">
                                                    <span class="fs-7 fw-bold d-block">{{ category.name }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-20 row" v-if="
                                cart.name == '' &&
                                products.length == 0 &&
                                selectedId == null &&
                                loadFinished
                            ">
                                <div class="text-center col-12 mt-15">
                                    <i class="text-gray-400 bi bi-cart-x fs-1"></i>
                                </div>
                                <div class="mt-8 text-center col-12">
                                    <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                        No any products
                                    </h1>
                                </div>
                            </div>
                            <div class="mt-20 row" v-if="
                                cart.name != '' &&
                                products.length == 0 &&
                                selectedId == null
                            ">
                                <div class="text-center col-12 mt-15">
                                    <i class="text-gray-400 bi bi-search fs-1"></i>
                                </div>
                                <div class="mt-8 text-center col-12 mb-15">
                                    <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                        No result found
                                    </h1>
                                </div>
                            </div>
                            <div class="mt-20 row" v-if="products.length == 0 && selectedId != null">
                                <div class="text-center col-12 mt-15">
                                    <i class="text-gray-400 bi bi-cart-x fs-1"></i>
                                </div>
                                <div class="mt-8 text-center col-12">
                                    <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                        No result found
                                    </h1>
                                </div>
                                <div class="mt-2 text-center col-12 mb-15">
                                    <p class="text-gray-700 fs-3">
                                        In this category
                                    </p>
                                </div>
                            </div>
                            <div v-if="products.length > 0" class="mt-1 row product-scroller">
                                <div v-for="product in products" class="cursor-pointer col-md-3 col-6">
                                    <div @click.prevent="selectProduct(product.id)" class="p-3 mt-3 card card-flush">
                                        <!--begin::Body-->
                                        <div class="p-0 text-center card-body">
                                            <!--begin::Food img-->
                                            <div class="image-container">
                                                <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                    class="mb-4 rounded-3" alt="" v-if="!product.image_url" />
                                                <img :src="product.image_url" class="mb-4 rounded-3" alt=""
                                                    v-if="product.image_url" />
                                            </div>

                                            <!--end::Food img-->

                                            <!--begin::Info-->
                                            <div class="mt-3 mb-2">
                                                <!--begin::Title-->
                                                <div class="text-center h-49px" data-toggle="tooltip"
                                                    data-placement="bottom" :title="product.name" style="
                                                    overflow-y: auto;
                                                    -webkit-overflow-scrolling: touch;
                                                ">
                                                    <span class="mt-1 text-gray-600 fw-semibold d-block fs-5">{{
                                                        truncateText(
                                                            product.name
                                                        )
                                                    }}</span>
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Info-->

                                            <!--begin::Total-->
                                            <span class="mt-1 text-gray-800 fw-semibold d-block fs-3">{{
                                                product.formatted_selling_price
                                                }}</span>
                                            <!--end::Total-->
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                </div>
                                <div v-if="products.length > 0" class="my-3 row ps-1 ps-md-0">
                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="kt_ecommerce_sales_table_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous" :class="pagination.current_page == 1
                                                    ? 'disabled'
                                                    : ''
                                                    " id="kt_ecommerce_sales_table_previous">
                                                    <a href="javascript:void(0)" @click="
                                                        setPage(
                                                            pagination.current_page -
                                                            1
                                                        )
                                                        " aria-controls="kt_ecommerce_sales_table" data-dt-idx="0"
                                                        tabindex="0" class="page-link"><i class="previous"></i></a>
                                                </li>
                                                <template v-for="(
                                                    page, index
                                                ) in pagination.last_page">
                                                    <template v-if="
                                                        page == 1 ||
                                                        page ==
                                                        pagination.last_page ||
                                                        Math.abs(
                                                            page -
                                                            pagination.current_page
                                                        ) < 5
                                                    ">
                                                        <li class="paginate_button page-item" :key="index" :class="pagination.current_page ==
                                                            page
                                                            ? 'active'
                                                            : ''
                                                            ">
                                                            <a href="javascript:void(0)" @click="
                                                                setPage(page)
                                                                " aria-controls="kt_ecommerce_sales_table"
                                                                data-dt-idx="1" tabindex="0" class="page-link">{{ page
                                                                }}</a>
                                                        </li>
                                                    </template>
                                                </template>
                                                <li class="paginate_button page-item next" :class="pagination.current_page ==
                                                    pagination.last_page
                                                    ? 'disabled'
                                                    : ''
                                                    " id="kt_ecommerce_sales_table_next">
                                                    <a href="javascript:void(0)" @click="
                                                        setPage(
                                                            pagination.current_page +
                                                            1
                                                        )
                                                        " aria-controls="kt_ecommerce_sales_table" data-dt-idx="6"
                                                        tabindex="0" class="page-link"><i class="next"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- main section -->
                        <div class="mt-4 col-md-12 col-lg-6 col-xl-5 mt-lg-0 pb-lg-0 d-block d-md-block">
                            <div class="shadow card payment-done-card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="p-5 pt-0 pb-20 pl-5 pr-5 p-md-8 pt-md-0 pr-md-30 pl-md-30 pb-md-30">
                                            <div class="mt-5 row mb-md-0">
                                                <div class="pt-0 col-lg-8 d-flex">
                                                    <div class="py-0 w-100">
                                                        <a v-if="
                                                            selectCustomer ==
                                                            null
                                                        " href="#" @click="
                                                            showCustomerModal()
                                                            " data-toggle="tooltip" data-placement="bottom"
                                                            title="Add customer"
                                                            class="py-2 btn btn-sm fs-4 fw-bold w-100 text-sm-center text-lg-start customer-btn">&plus;
                                                            CUSTOMER</a>
                                                        <a v-if="
                                                            selectCustomer !=
                                                            null
                                                        " href="#" data-toggle="tooltip" data-placement="bottom"
                                                            title="Remove selected customer" @click.prevent="removeCustomer
                                                                "
                                                            class="py-2 btn btn-sm fs-4 fw-bold w-100 text-sm-center text-lg-start customer-btn">✖
                                                            {{
                                                                truncateText(
                                                                    selectCustomerName
                                                                )
                                                            }}</a>
                                                    </div>
                                                </div>
                                                <div class="mt-3 mb-3 col-lg-4 mt-lg-0">
                                                    <a href="javascript:void(0)" @click.prevent="clearOrderConfirmation
                                                        " data-toggle="tooltip" data-placement="bottom"
                                                        title="Clear items"
                                                        class="py-2 btn btn-sm fs-4 fw-bold w-100 clear-btn">CLEAR</a>
                                                </div>
                                            </div>

                                            <!--begin::Desktop view Table container-->
                                            <div class="table-responsive d-none d-md-block select_product_scroller product-item-list"
                                                id="scroller">
                                                <!--begin::Table-->
                                                <table class="table my-0 align-middle gs-0 gy-2">
                                                    <!--begin::Table head-->
                                                    <thead></thead>
                                                    <!--end::Table head-->

                                                    <!--begin::Table body-->
                                                    <tbody>
                                                        <tr v-for="(
                                                            orderProduct, key
                                                        ) in orderProducts" :key="key" data-kt-pos-element="item"
                                                            data-kt-pos-item-price="33">
                                                            <td class="text-end">
                                                                <span class="text-gray-600 fw-bold fs-7">{{
                                                                    key + 1
                                                                    }}</span>
                                                            </td>
                                                            <td @click.prevent="
                                                                editQty(
                                                                    orderProduct
                                                                )
                                                                " data-toggle="tooltip" data-placement="bottom"
                                                                title="Edit item">
                                                                <div class="cart-image-outline" v-if="
                                                                    !orderProduct.image_url
                                                                ">
                                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                                        class="rounded-3 cart-image" />
                                                                </div>
                                                                <div class="cart-image-outline" v-if="
                                                                    orderProduct.image_url
                                                                ">
                                                                    <img :src="orderProduct.image_url
                                                                        " class="rounded-3 cart-image" />
                                                                </div>
                                                            </td>
                                                            <td class="py-0 pe-0" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit item"
                                                                @click.prevent="
                                                                    editQty(
                                                                        orderProduct
                                                                    )
                                                                    ">
                                                                <div class="align-items-center item-name-div">
                                                                    <span
                                                                        class="text-gray-800 cursor-pointer fw-bold text-hover-primary fs-6 me-1"
                                                                        style="
                                                                        white-space: normal;
                                                                    " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom" :title="orderProduct.product_name
                                                                            ">{{
                                                                                truncateText(
                                                                                    orderProduct.product_name
                                                                                )
                                                                            }}</span>
                                                                </div>
                                                            </td>

                                                            <td class="pe-0">
                                                                <!--begin::Dialer-->
                                                                <div class="position-relative d-flex align-items-center"
                                                                    data-kt-dialer="true" data-kt-dialer-min="1"
                                                                    data-kt-dialer-max="10" data-kt-dialer-step="1"
                                                                    data-kt-dialer-decimals="0">
                                                                    <!--begin::Decrease control-->
                                                                    <a href="javascript:void(0)" @click.prevent="
                                                                        decreaseQty(
                                                                            orderProduct.product_id
                                                                        )
                                                                        " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom"
                                                                        title="Decrease quantity"
                                                                        class="pt-1 pb-1 mr-1 btn btn-sm btn-light btn-icon-gray-400 ps-1 pe-0"
                                                                        data-kt-dialer-control="decrease">
                                                                        <i class="bi bi-dash-lg"></i>
                                                                    </a>
                                                                    <!--end::Decrease control-->

                                                                    <!--begin::Input control-->
                                                                    <input type="text"
                                                                        class="px-0 mr-1 text-center text-gray-800 border-0 form-control fs-4 fw-bold w-60px"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        title="Change quantity"
                                                                        data-kt-dialer-control="input"
                                                                        placeholder="Amount" name="manageBudget"
                                                                        readonly :value="formatQuantity(
                                                                            orderProduct.quantity
                                                                        )
                                                                            " />
                                                                    <!--end::Input control-->

                                                                    <!--begin::Increase control-->
                                                                    <a href="javascript:void(0)" @click.prevent="
                                                                        increaseQty(
                                                                            orderProduct.product_id
                                                                        )
                                                                        " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom"
                                                                        title="Increase quantity"
                                                                        class="p-0 pt-1 pb-1 m-0 ml-2 btn btn-sm btn-light btn-icon-gray-400 ps-1 pe-0"
                                                                        data-kt-dialer-control="increase">
                                                                        <i class="bi bi-plus-lg"></i>
                                                                    </a>
                                                                    <!--end::Increase control-->
                                                                </div>
                                                                <!--end::Dialer-->
                                                            </td>

                                                            <td class="text-end">
                                                                <span class="fw-bold text-primary fs-4"
                                                                    data-kt-pos-element="item-total">{{
                                                                        orderProduct.formatted_sub_total
                                                                    }}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>
                                                <div class="table-bottom-height"></div>
                                                <!-- <div id="targetDiv"></div> -->
                                                <!--end::Table-->
                                            </div>
                                            <!--end::Table container-->

                                            <!--begin::Mobile view Table container-->
                                            <div class="table-responsive d-block d-md-none select_product_scroller product-item-list"
                                                id="scroller">
                                                <!--begin::Table-->
                                                <table class="table my-0 align-middle gs-0 gy-2">
                                                    <!--begin::Table head-->
                                                    <thead></thead>
                                                    <!--end::Table head-->

                                                    <!--begin::Table body-->
                                                    <tbody class="">
                                                        <tr v-for="(
                                                            orderProduct, key
                                                        ) in orderProducts" :key="key" data-kt-pos-element="item"
                                                            data-kt-pos-item-price="33">
                                                            <td @click.prevent="
                                                                editQty(
                                                                    orderProduct
                                                                )
                                                                " data-toggle="tooltip" data-placement="bottom"
                                                                title="Edit item">
                                                                <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                                    class="w-40px h-40px rounded-3" v-if="
                                                                        !orderProduct.image_url
                                                                    " />
                                                                <img :src="orderProduct.image_url
                                                                    " class="w-50px h-50px rounded-3" v-if="
                                                                        orderProduct.image_url
                                                                    " />
                                                            </td>
                                                            <td class="py-0 pe-0" data-toggle="tooltip"
                                                                data-placement="bottom" title="Edit item"
                                                                @click.prevent="
                                                                    editQty(
                                                                        orderProduct
                                                                    )
                                                                    ">
                                                                <div class="align-items-center item-name-div">
                                                                    <span
                                                                        class="text-gray-800 cursor-pointer fw-bold text-hover-primary fs-6 me-1"
                                                                        style="
                                                                        white-space: normal;
                                                                    " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom" :title="orderProduct.product_name
                                                                            ">{{
                                                                                truncateTextMobile(
                                                                                    orderProduct.product_name
                                                                                )
                                                                            }}</span>
                                                                </div>
                                                            </td>

                                                            <td class="pe-0">
                                                                <!--begin::Dialer-->
                                                                <div class="position-relative d-flex align-items-center"
                                                                    data-kt-dialer="true" data-kt-dialer-min="1"
                                                                    data-kt-dialer-max="10" data-kt-dialer-step="1"
                                                                    data-kt-dialer-decimals="0">
                                                                    <!--begin::Decrease control-->
                                                                    <a href="javascript:void(0)" @click.prevent="
                                                                        decreaseQty(
                                                                            orderProduct.product_id
                                                                        )
                                                                        " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom"
                                                                        title="Decrease quantity"
                                                                        class="pt-1 pb-1 mr-1 btn btn-sm btn-light btn-icon-gray-400 ps-1 pe-0"
                                                                        data-kt-dialer-control="decrease">
                                                                        <i class="bi bi-dash-lg"></i>
                                                                    </a>
                                                                    <!--end::Decrease control-->

                                                                    <!--begin::Input control-->
                                                                    <input type="text"
                                                                        class="px-0 mr-1 text-center text-gray-800 border-0 form-control fs-4 fw-bold w-60px"
                                                                        data-toggle="tooltip" data-placement="bottom"
                                                                        title="Change quantity"
                                                                        data-kt-dialer-control="input"
                                                                        placeholder="Amount" name="manageBudget"
                                                                        readonly :value="formatQuantity(
                                                                            orderProduct.quantity
                                                                        )
                                                                            " />
                                                                    <!--end::Input control-->

                                                                    <!--begin::Increase control-->
                                                                    <a href="javascript:void(0)" @click.prevent="
                                                                        increaseQty(
                                                                            orderProduct.product_id
                                                                        )
                                                                        " type="button" data-toggle="tooltip"
                                                                        data-placement="bottom"
                                                                        title="Increase quantity"
                                                                        class="p-0 pt-1 pb-1 m-0 ml-2 btn btn-sm btn-light btn-icon-gray-400 ps-1 pe-0"
                                                                        data-kt-dialer-control="increase">
                                                                        <i class="bi bi-plus-lg"></i>
                                                                    </a>
                                                                    <!--end::Increase control-->
                                                                </div>
                                                                <!--end::Dialer-->
                                                            </td>

                                                            <td class="text-end">
                                                                <span class="fw-bold text-primary fs-4"
                                                                    data-kt-pos-element="item-total">{{
                                                                        orderProduct.formatted_sub_total
                                                                    }}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>
                                                <div class="table-bottom-height"></div>
                                                <!-- <div id="targetDiv"></div> -->
                                                <!--end::Table-->
                                            </div>
                                            <!--end::Table container-->

                                            <a href="#" @click="showAddProductModal()" data-toggle="tooltip"
                                                data-placement="bottom" title="Add customer"
                                                class="py-2 mt-6 btn btn-sm fs-4 fw-bold w-100 text-sm-center text-lg-start add-cart-product-btn d-md-block d-lg-none">&plus;
                                                ADD PRODUCT</a>

                                            <div v-if="orderProducts.length >= 1" class="mt-5 row">
                                                <div class="col-12 col-lg-4 col-md-4 col-xl-4" v-if="discount != 0">
                                                    <h1 class="mt-3 mb-5 text-gray-800 fw-bold fs-3">
                                                        Discount
                                                    </h1>
                                                </div>
                                                <div v-if="discount != 0"
                                                    class="mb-3 col-6 col-lg-4 col-md-4 col-xl-4 mb-md-0">
                                                    <a href="javascript:void(0)" data-toggle="tooltip"
                                                        data-placement="bottom" title="Remove discount" @click.prevent="removeDiscount
                                                            "
                                                        class="py-2 btn btn-sm fs-4 fw-bold w-100 clear-btn">Remove</a>
                                                </div>
                                                <div v-if="discount != 0"
                                                    class="mb-3 col-6 col-lg-4 col-md-4 col-xl-4 mb-md-0">
                                                    <a href="#" @click="
                                                        showChangeDiscountModal()
                                                        " data-toggle="tooltip" data-placement="bottom"
                                                        title="Change discount"
                                                        class="py-2 btn btn-sm fs-4 fw-bold w-100 discount-btn">Change</a>
                                                </div>
                                                <div class="col-6 col-lg-6 col-md-6 col-xl-6" v-if="discount == 0">
                                                    <h1 class="mt-3 mb-5 text-gray-800 fw-bold fs-3">
                                                        Discount
                                                    </h1>
                                                </div>
                                                <div v-if="discount == 0" class="col-6 col-lg-6 col-md-6 col-xl-6">
                                                    <a href="#" @click="showDiscountModal()" data-toggle="tooltip"
                                                        data-placement="bottom" title="Add discount"
                                                        class="py-2 btn btn-sm fs-4 fw-bold w-100 discount-btn">&plus;
                                                        Discount</a>
                                                </div>
                                            </div>

                                            <div v-if="orderProducts.length == 0" class="mt-5 row">
                                                <div class="col-12 mt-lg-14"></div>
                                            </div>

                                            <!--begin::Summary-->
                                            <div class="mt-2 col-12">
                                                <div class="p-3 mb-3 d-flex flex-stack bg-success rounded-3 p-xxl-5">
                                                    <div class="text-white fs-6 fw-bold">
                                                        <span class="mb-2 d-block lh-1">Subtotal</span>
                                                        <span class="mb-2 d-block">Discounts
                                                            <label v-if="
                                                                viewPercentage !=
                                                                0
                                                            ">({{
                                                                viewPercentage
                                                            }}%)</label></span>
                                                        <span class="mb-2 d-block lh-1">Total Tax</span>
                                                        <span class="mt-5 d-block fs-1 lh-1">Total</span>
                                                    </div>

                                                    <div class="text-white fs-6 fw-bold text-end">
                                                        <span class="mb-2 d-block lh-1" data-kt-pos-element="total">{{
                                                            currency }}
                                                            {{
                                                                Number(
                                                                    subTotal
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                        <span class="mb-2 d-block" data-kt-pos-element="discount">- {{
                                                            currency }}
                                                            {{
                                                                Number(
                                                                    discount
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                        <span class="mb-2 d-block" data-kt-pos-element="totalTax">+ {{
                                                            currency }}
                                                            {{
                                                                Number(
                                                                    totalTax
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                        <span class="mt-5 d-block fs-1 lh-1"
                                                            data-kt-pos-element="grant-total">{{ currency }}
                                                            {{
                                                                Number(
                                                                    total
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                    </div>
                                                </div>
                                                <!--end::Summary-->

                                                <!--begin::Paid Amount-->
                                                <div class="m-0">
                                                    <h1 class="mb-1 text-gray-800 fw-bold fs-xl-1">
                                                        Paid Amount
                                                    </h1>

                                                    <div data-kt-buttons="true"
                                                        data-kt-buttons-target="[data-kt-button]">
                                                        <input v-model="paidAmount" type="text" id="paidAmount"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Enter amount"
                                                            class="mt-1 mb-2 form-control form-control-sm fs-2"
                                                            placeholder="Paid amount" @keyup="getChange" />

                                                        <div class="d-inline fs-2">
                                                            Change: {{ currency }}
                                                            <span v-if="
                                                                changeAmount ==
                                                                0
                                                            ">0.00</span>
                                                            <span v-if="
                                                                changeAmount > 0
                                                            " class="text-primary">{{
                                                                Number(
                                                                    changeAmount
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                            <span v-if="
                                                                changeAmount < 0
                                                            " class="text-danger">{{
                                                                Number(
                                                                    changeAmount
                                                                ).toLocaleString(
                                                                    "en-US",
                                                                    {
                                                                        minimumFractionDigits: 2,
                                                                    }
                                                                )
                                                            }}</span>
                                                        </div>
                                                    </div>

                                                    <!--begin::Actions-->
                                                    <div class="mt-3 row" v-if="
                                                        orderProducts.length >=
                                                        1
                                                    ">
                                                        <div class="col-lg-5">
                                                            <a href="javascript:void(0)" @click.prevent="holdCart
                                                                " data-toggle="tooltip" data-placement="bottom"
                                                                title="Hold bill"
                                                                class="py-4 btn fs-1 w-100 hold-btn fw-bold">HOLD</a>
                                                        </div>
                                                        <div class="mt-3 col-lg-7 mt-lg-0">
                                                            <a href="javascript:void(0)" @click.prevent="paymentDone
                                                                " data-toggle="tooltip" data-placement="bottom"
                                                                title="Confirm bill"
                                                                class="py-4 btn fs-1 w-100 done-btn fw-bold">DONE</a>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 row" v-if="
                                                        orderProducts.length ==
                                                        0
                                                    ">
                                                        <div class="mt-3 col-lg-12 mt-lg-0">
                                                            <button class="py-4 btn fs-1 w-100 done-btn fw-bold">
                                                                Place an order first
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!--end::Actions-->
                                                </div>
                                            </div>
                                            <!--end::Payment Method-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </template>
            <template #modal>
                <!-- Customer Modal -->
                <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color: #071437">
                                    Add Customer
                                </h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                    data-toggle="tooltip" data-placement="bottom" title="Close"
                                    @click="closeCustomerModal"></button>
                            </div>
                            <div class="modal-body">
                                <form enctype="multipart/form-data">
                                    <div class="mb-8 row">
                                        <div class="mt-2 col-md-4">
                                            <span class="text-gray-600">PHONE NUMBER</span>
                                        </div>
                                        <div class="col-10 col-md-6">
                                            <input type="text" class="form-control form-control-sm"
                                                v-model="customer.contact" placeholder="Phone Number" required />
                                            <small v-if="setError"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{ setError }}</small>
                                            <small v-if="validationErrors.contact"
                                                class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                {{
                                                    validationErrors.contact
                                                }}</small>
                                        </div>
                                        <div class="col-2 col-md-2">
                                            <div class="py-0 row h-100">
                                                <div class="col-12 justify-content-end text-end">
                                                    <a class="py-5 cursor-pointer square-customer-button"
                                                        @click.prevent="searchLoyaltyCustomer
                                                            " data-toggle="tooltip" data-placement="bottom"
                                                        title="Search customer using phone number">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="loyalty_customer.name != null">
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">NAME</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="loyalty_customer.name" placeholder="Name" disabled />
                                                <small v-if="validationErrors.name"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{
                                                        validationErrors.name
                                                    }}</small>
                                            </div>
                                        </div>
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">EMAIL ADDRESS</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="email" class="form-control form-control-sm"
                                                    v-model="loyalty_customer.email" placeholder="Email Address"
                                                    disabled />
                                            </div>
                                        </div>
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">ADDRESS</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm" v-model="loyalty_customer.address
                                                    " placeholder="Address" disabled />
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <div class="col-12 justify-content-end text-end">
                                                <a class="cursor-pointer square-customer-button" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add customer" @click.prevent="
                                                        saveCustomer(
                                                            loyalty_customer
                                                        )
                                                        ">
                                                    <i class="bi bi-chevron-double-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="loyalty_customer.name == null">
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">NAME</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="customer.name" placeholder="Name" required />
                                                <small v-if="validationErrors.name"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{
                                                        validationErrors.name
                                                    }}</small>
                                            </div>
                                        </div>
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">EMAIL ADDRESS</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="email" class="form-control form-control-sm"
                                                    v-model="customer.email" placeholder="Email Address" required />
                                            </div>
                                        </div>
                                        <div class="mt-2 row">
                                            <div class="mt-2 col-md-4">
                                                <span class="text-gray-600">ADDRESS</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="customer.address" placeholder="Address" required />
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <div class="col-12 justify-content-end text-end">
                                                <a type="button" class="cursor-pointer square-clear-button"
                                                    @click.prevent="saveNewCustomer">
                                                    <i class="bi bi-chevron-double-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Discount Modal -->
                <div class="modal fade" id="discountModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color: #071437">
                                    Discount
                                </h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                    @click="closeDiscountModal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-8 row">
                                    <div class="mt-2 col-md-4 text-end">
                                        <span class="text-gray-800">Subtotal</span><br />
                                        <span class="text-gray-600">{{
                                            Number(subTotal).toLocaleString(
                                                "en-US",
                                                {
                                                    minimumFractionDigits: 2,
                                                }
                                            )
                                        }}</span><br />
                                        <span class="text-gray-800">Discount</span><br />
                                        <span class="text-gray-600">{{
                                            Number(newDiscount).toLocaleString(
                                                "en-US",
                                                {
                                                    minimumFractionDigits: 2,
                                                }
                                            )
                                                ? Number(
                                                    newDiscount
                                                ).toLocaleString("en-US", {
                                                    minimumFractionDigits: 2,
                                                })
                                                : 0
                                        }}</span><br />
                                        <hr />
                                        <span class="text-gray-800">New Total</span><br />
                                        <span class="text-gray-600">{{
                                            Number(newTotal).toLocaleString(
                                                "en-US",
                                                {
                                                    minimumFractionDigits: 2,
                                                }
                                            )
                                        }}</span>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="javascript:void(0)"
                                                    class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                    :class="{
                                                        active: tabIndex === 1,
                                                    }" data-toggle="tooltip" data-placement="bottom" title="Amount"
                                                    @click="
                                                        (tabIndex = 1),
                                                        (percentage = '')
                                                        " data-kt-button="true">
                                                    <span class="fs-7 fw-bold d-block">Amount</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0)"
                                                    class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                    :class="{
                                                        active: tabIndex === 2,
                                                    }" @click="
                                                        (tabIndex = 2),
                                                        (amount = '')
                                                        " data-toggle="tooltip" data-placement="bottom" title="Percentage"
                                                    data-kt-button="true">
                                                    <span class="fs-7 fw-bold d-block">Percentage</span>
                                                </a>
                                            </div>
                                            <div :class="[
                                                tabIndex === 2 ? 'd-none' : '',
                                            ]" class="mt-5 col-12 d-block">
                                                <input type="number" class="form-control form-control-sm"
                                                    v-model="amount" data-toggle="tooltip" data-placement="bottom"
                                                    title="Change amount" placeholder="Amount" required />
                                            </div>
                                            <div :class="[
                                                tabIndex === 1 ? 'd-none' : '',
                                            ]" class="mt-5 col-12 d-block">
                                                <input type="number" class="form-control form-control-sm"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Change percentage" v-model="percentage"
                                                    placeholder="Percentage" required />
                                            </div>
                                        </div>
                                        <div class="mt-5 row">
                                            <div class="col-12">
                                                <a href="javascript:void(0)" @click.prevent="
                                                    addDiscount(newDiscount)
                                                    "
                                                    class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                    data-toggle="tooltip" data-placement="bottom" title="Add discount"
                                                    data-kt-button="true">
                                                    <span class="fs-6 fw-bold d-block">ADD DISCOUNT</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Qty Update Modal -->
                <div class="modal fade" id="qtyUpdateModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-5 col-8">
                                        <h5 class="modal-title custom-modal-title">
                                            {{ edit_product.name }}
                                        </h5>
                                    </div>
                                    <div class="mb-5 col-4 text-end">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <form @submit.prevent="selectItemUpdate" enctype="multipart/form-data">
                                    <div class="text-gray-600 col-form-label">
                                        Unit Price
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="edit_select_item.unit_price
                                                " type="text" class="form-control form-control-sm"
                                                placeholder="Unit Price" />
                                        </div>
                                    </div>
                                    <div class="text-gray-600 col-form-label">
                                        Quantity
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <input v-model="edit_select_item.quantity" type="text"
                                                :disabled="edit_product.deleted_at" class="form-control form-control-sm"
                                                placeholder="Quantity" />
                                        </div>
                                    </div>
                                    <div class="mt-5 row">
                                        <div class="text-gray-500 col-12">
                                            <hr />
                                        </div>
                                        <div class="text-center col-6">
                                            <button type="button" @click.prevent="removeItem"
                                                class="btn btn-light-danger w-100" style="font-weight: bold">
                                                REMOVE ITEM
                                            </button>
                                        </div>
                                        <div class="text-center col-6">
                                            <button type="submit" class="btn btn-light-info w-100"
                                                style="font-weight: bold">
                                                UPDATE
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Confirmation Modal -->
                <div class="modal fade" id="creditConfirmModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Credit Confirm</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="text-gray-700 modal-body fs-4">
                                Do you want to add a credit bill of {{ currency }}
                                {{
                                    Number(Math.abs(changeAmount)).toLocaleString(
                                        "en-US",
                                        {
                                            minimumFractionDigits: 2,
                                        }
                                    )
                                }}
                                ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" style="font-weight: bold"
                                    @click.prevent="confirmCredit">
                                    Yes, confirm it!
                                </button>
                                <button type="button" class="btn btn-light-success" style="font-weight: bold"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Product Modal -->
                <div class="modal fade d-md-hide" id="addProductModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Add Product</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="mt-5 mt-lg-0 pb-lg-0">
                                <div class="mx-3 border input-group rounded-3 align-items-center"
                                    style="background-color: white; width: 92vw">
                                    <i class="cursor-pointer bi bi-upc-scan fs-2 ms-3 me-1"
                                        @click="openQrScannerModal"></i>
                                    <input type="text" class="border-0 form-control cart-search" name="search"
                                        data-toggle="tooltip" data-placement="bottom" title="Search items"
                                        v-model="cart.name" aria-label="Example text with button addon"
                                        aria-describedby="button-addon1" placeholder="Product search / Barcode"
                                        v-bind="$attrs" autofocus ref="barcodeInput" />
                                    <div class="input-group-append">
                                        <button class="px-5 bg-white border-0 btn" type="button " data-toggle="tooltip"
                                            data-placement="bottom" title="Clear filter" @click="clearFilters">
                                            <i class="fa fa-x" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-12">
                                        <div class="p-2 card payment-done-card">
                                            <div class="mx-1 row horizontal-btn-scroller">
                                                <div class="mt-1 mb-1 col-6 col-md-3">
                                                    <a href="javascript:void(0)" @click.prevent="getAllProducts
                                                        "
                                                        class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                        :class="{
                                                            active:
                                                                selectedId === null,
                                                        }" data-kt-button="true">
                                                        <span class="fs-7 fw-bold d-block">All</span>
                                                    </a>
                                                </div>
                                                <div v-for="category in categories" class="mt-1 mb-1 col-6 col-md-3">
                                                    <a href="javascript:void(0)" @click.prevent="
                                                        getProductsFromCategory(
                                                            category.id
                                                        )
                                                        "
                                                        class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                        :class="{
                                                            active:
                                                                category.id ===
                                                                selectedId,
                                                        }" @click="
                                                            selectItem(category.id)
                                                            " data-kt-button="true">
                                                        <span class="fs-7 fw-bold d-block">{{
                                                            category.name
                                                        }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-20 row" v-if="
                                    cart.name == '' &&
                                    products.length == 0 &&
                                    selectedId == null &&
                                    loadFinished
                                ">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-cart-x fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                            No any products
                                        </h1>
                                    </div>
                                </div>
                                <div class="mt-20 row" v-if="
                                    cart.name != '' &&
                                    products.length == 0 &&
                                    selectedId == null
                                ">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-search fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12 mb-15">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                            No result found
                                        </h1>
                                    </div>
                                </div>
                                <div class="mt-20 row" v-if="
                                    products.length == 0 && selectedId != null
                                ">
                                    <div class="text-center col-12 mt-15">
                                        <i class="text-gray-400 bi bi-cart-x fs-1"></i>
                                    </div>
                                    <div class="mt-8 text-center col-12">
                                        <h1 class="text-gray-700 heading fw-bold fs-2hx">
                                            No result found
                                        </h1>
                                    </div>
                                    <div class="mt-2 text-center col-12 mb-15">
                                        <p class="text-gray-700 fs-3">
                                            In this category
                                        </p>
                                    </div>
                                </div>
                                <div v-if="products.length > 0" class="mt-1 row product-scroller">
                                    <div v-for="product in products" class="cursor-pointer col-md-3 col-6">
                                        <div @click.prevent="
                                            selectProductMobile(product.id)
                                            " class="p-3 mt-3 card card-flush">
                                            <!--begin::Body-->
                                            <div class="p-0 text-center card-body">
                                                <!--begin::Food img-->
                                                <div class="image-container">
                                                    <img src="@/../src/assets/media/stock/food/product_image.webp"
                                                        class="mb-4 rounded-3" alt="" v-if="!product.image_url" />
                                                    <img :src="product.image_url" class="mb-4 rounded-3" alt=""
                                                        v-if="product.image_url" />
                                                </div>

                                                <!--end::Food img-->

                                                <!--begin::Info-->
                                                <div class="mt-3 mb-2">
                                                    <!--begin::Title-->
                                                    <div class="text-center h-49px" data-toggle="tooltip"
                                                        data-placement="bottom" :title="product.name" style="
                                                        overflow-y: auto;
                                                        -webkit-overflow-scrolling: touch;
                                                    ">
                                                        <span class="mt-1 text-gray-600 fw-semibold d-block fs-5">{{
                                                            truncateText(
                                                                product.name
                                                            )
                                                        }}</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Info-->

                                                <!--begin::Total-->
                                                <span class="mt-1 text-gray-800 fw-semibold d-block fs-3">{{
                                                    product.formatted_selling_price
                                                }}</span>
                                                <!--end::Total-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="clearModel" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Clear Cart</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to clear all items from the
                                cart?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-darkr fw-bold" data-bs-dismiss="modal"
                                    data-toggle="tooltip" data-placement="bottom" title="Cancel">
                                    CANCEL
                                </button>
                                <button type="button" class="btn btn-light-danger fw-bold" @click.prevent="clearOrder"
                                    data-toggle="tooltip" data-placement="bottom" title="Delete quotation">
                                    <i class="bi bi-trash"></i>
                                    CLEAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR scanner modal -->
                <div class="modal fade" id="qrScannerModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="qrScannerModalLabel">
                                    Scan Barcode
                                </h1>
                                <button type="button" class="btn-close" @click="closeQrScannerModal"></button>
                            </div>
                            <div class="pt-0 modal-body">
                                <div class="col-12 h-30px d-flex align-items-center justify-content-center">
                                    <span v-if="added_alert == true" class="text-green">Product added to Cart</span>
                                    <span v-if="not_error_alert == true" class="text-danger">Product not found</span>
                                </div>
                                <div id="reader" style="width: 100%"></div>
                                <div class="pt-5 col-12 text-end">
                                    <button type="button" class="btn btn-sm btn-secondary" @click="closeQrScannerModal">
                                        Stop Scanning
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #loader>
                <Loader ref="loading_bar" />
            </template>
        </PosLayout>

    </div>
</template>

<script setup>
import PosLayout from "@/Layouts/PosLayout.vue";
import { onMounted, ref, watch, nextTick } from "vue";
import axios from "axios";
import { debounce } from "lodash";
import Swal from "sweetalert2";
import Loader from "@/Components/Basic/LoadingBar.vue";
import { usePage } from "@inertiajs/vue3";
import { Html5Qrcode } from "html5-qrcode";

const { props } = usePage();

const loading = ref(false);
const cart = ref({ name: "", product_category_id: null });
const products = ref([]);
const page = ref(1);
const pageCount = ref(28);
const pagination = ref({});
const orderProducts = ref([]);
const categories = ref([]);
const selectedId = ref(null);
const subTotal = ref(0);
const totalTax = ref(0);
const total = ref(0);
const discount = ref(0);
const setDiscountType = ref(0);
const customer = ref({});
const loyalty_customer = ref({});
const selectCustomer = ref("");
const selectCustomerName = ref("");
const orderId = ref(null);

const tabIndex = ref(1);
const amount = ref("");
const percentage = ref("");
const viewPercentage = ref(0);
const newDiscount = ref(0);
const newTotal = ref(0);

const edit_product = ref({});
const edit_select_item = ref({});

const item_details = ref({});

const unit_price = ref("");
const paidAmount = ref(null);
const changeAmount = ref(0);

const business_details = ref({});
const currency = ref("");

const barcodeInput = ref(null);
const html5QrCode = ref(null);
const beepSound = ref(null);
const added_alert = ref(false);
const not_error_alert = ref(false);

const scrollContainer = ref(null);

const loading_bar = ref(null);

const setError = ref("");
const validationErrors = ref({});
const validationMessage = ref(null);

const loadFinished = ref(false);

const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const convertValidationNotification = (error) => {
    resetValidationErrors(error);
};

const convertValidationError = (err) => {
    resetValidationErrors();
    if (!(err.response && err.response.data)) return;
    const { message, errors } = err.response.data;
    validationMessage.value = message;
    if (errors) {
        for (const error in errors) {
            validationErrors.value[error] = errors[error][0];
        }
    }
};

function clearFilters() {
    cart.value = {};
    getAllProducts();
}
const setPage = (new_page) => {
    page.value = new_page;
    getAllProducts();
};
const getAllProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        // const productData = (await axios.get(route('cart.products.all'))).data;
        // products.value = productData;
        const tableData = (
            await axios.get(route("products.all"), {
                params: {
                    page: page.value,
                    per_page: pageCount.value,
                },
            })
        ).data;
        products.value = tableData.data;
        pagination.value = tableData.meta;

        selectedId.value = null;
        barcodeInput.value.focus();
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
};

const getCategories = async () => {
    try {
        const categoryData = (await axios.get(route("categories.all"))).data;
        categories.value = categoryData.data;
    } catch (error) { }
};

const selectItem = (id) => {
    selectedId.value = id;
};

const getProductsFromCategory = async (categoryId) => {
    try {
        const productData = (
            await axios.get(route("products.category.all", categoryId))
        ).data;
        products.value = productData;
    } catch (error) { }
};

const onSearchByNameBarcode = debounce(async () => {
    try {
        if (cart.value.name.length < 1) {
            getAllProducts();
        } else {
            loading.value = true;
            searchByName(cart.value.name, selectedId.value);
        }
    } catch (error) { }
}, 250);

const searchByName = async (name, product_category_id) => {
    try {
        if (product_category_id === null) {
            product_category_id = 0;
        }

        const filter_letter = {
            name: name,
            product_category_id: product_category_id,
            order_id: orderId.value,
        };

        const res = await axios.post(route("product.name.all"), filter_letter);
        products.value = res.data;

        // Auto-select the first product if the search returned any results
        if (products.value && products.value.length > 0) {
            const firstProductId = products.value[0].id; // Assuming the product object has an 'id' field
            await selectProduct(firstProductId);
        }

        loading.value = false;
    } catch (error) {
        loading.value = false;
    }
};

const selectProduct = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const productData = (
            await axios.get(route("cart.select.product", product_id))
        ).data;
        getOderProduct();
        calculateTotals();
        scrollToBottom();
        cart.value.name = "";
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

const selectProductMobile = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const productData = (
            await axios.get(route("cart.select.product", product_id))
        ).data;
        getOderProduct();
        calculateTotals();
        scrollToBottom();
        cart.value.name = "";
        successMessage("Product added successfully");
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

const scrollToBottom = () => {
    if (scrollContainer.value) {
        scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }
};

const getOderProduct = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const orderProductData = (await axios.get(route("cart.get.products")))
            .data;
        orderProducts.value = orderProductData;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
};

const formatQuantity = (quantity) => {
    return Number(quantity).toFixed(0);
};

const clearOrderConfirmation = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        $("#clearModel").modal("show");
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

const clearOrder = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        (await axios.get(route("cart.clear.order"))).data;
        $("#clearModel").modal("hide");
        successMessage("Clearing the order is successful");
        paidAmount.value = null;
        getOderProduct();
        calculateTotals();
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }

};

const calculateTotals = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const order = (await axios.get(route("cart.getsubtotal.order"))).data;
        const formattedSubTotal = Number(order.sub_total).toFixed(2);
        const formattedTotalTax = Number(order.total_tax).toFixed(2);
        const formattedTotal = Number(order.total).toFixed(2);
        const formattedDiscount = Number(order.discount).toFixed(2);
        selectCustomer.value = order.customer_id;
        selectCustomerName.value = order.customer_name;
        orderId.value = order.id;
        setDiscountType.value = order.discount_type;
        subTotal.value = formattedSubTotal;
        totalTax.value = formattedTotalTax;
        total.value = formattedTotal;
        discount.value = formattedDiscount;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
    getChange();
};

const getChange = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const changeValue = paidAmount.value - total.value;
        changeAmount.value = Number(changeValue).toFixed(2);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
};

const decreaseQty = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        await axios.get(route("cart.decrease.product", { product_id }));
        getOderProduct();
        calculateTotals();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        if (error.response && error.response.status === 404) {
            errorMessage(error.response.data.error);
        } else {
            convertValidationNotification(error);
        }
    }
};

const increaseQty = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        await axios.get(route("cart.increase.product", { product_id }));
        getOderProduct();
        calculateTotals();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        if (error.response && error.response.status === 404) {
            errorMessage(error.response.data.error);
        } else {
            convertValidationNotification(error);
        }
    }
};

const editQty = async (pos_order_item) => {
    try {
        const response = (
            await axios.get(route("product.get", pos_order_item.product_id))
        ).data;

        edit_product.value = response;
        setOrderProductValue(pos_order_item.id);
        $("#qtyUpdateModal").modal("show");
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorMessage(error.response.data.error);
        } else {
            convertValidationNotification(error);
        }
    }
};

const setOrderProductValue = async (pos_order_item_id) => {
    try {
        const response = (
            await axios.get(route("order.product.get", pos_order_item_id))
        ).data;
        item_details.value = response;
        edit_select_item.value.unit_price =
            item_details.value.formatted_unit_price;
        edit_select_item.value.quantity = Math.floor(
            item_details.value.quantity
        );
        $("#qtyUpdateModal").modal("show");
    } catch (error) {
        convertValidationNotification(error);
    }
};

const selectItemUpdate = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (
            typeof edit_select_item.value.unit_price === "string" &&
            edit_select_item.value.unit_price
        ) {
            edit_select_item.value.unit_price = parseFloat(
                edit_select_item.value.unit_price.replace(/,/g, "")
            );
        } else {
            edit_select_item.value.unit_price =
                edit_select_item.value.unit_price;
        }

        await axios.post(
            route("cart.product.qty", item_details.value.id),
            edit_select_item.value
        );
        successMessage("Updated successfully");

        edit_select_item.value = {};

        $("#qtyUpdateModal").modal("hide");

        getOderProduct();
        calculateTotals();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        if (error.response && error.response.status === 404) {
            errorMessage(error.response.data.error);
        } else {
            convertValidationNotification(error);
        }
    }
};

const removeItem = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        (await axios.get(route("cart.remove.product", item_details.value.id)))
            .data;
        successMessage("Item removed");

        scrollToBottom();
        $("#qtyUpdateModal").modal("hide");

        getOderProduct();
        calculateTotals();

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

const showCustomerModal = async () => {
    resetValidationErrors();
    customer.value.contact = "";
    customer.value.name = "";
    customer.value.email = "";
    customer.value.address = "";
    loyalty_customer.value = {};
    loyalty_customer.value.name = "";
    loyalty_customer.value.email = "";
    loyalty_customer.value.address = "";
    setError.value = "";
    $("#customerModal").modal("show");
};

const closeCustomerModal = async () => {
    $("#customerModal").modal("hide");
};

const showDiscountModal = async () => {

    amount.value = "";
    percentage.value = "";
    $("#discountModal").modal("show");

};

const showChangeDiscountModal = async () => {

    if (setDiscountType.value == 0) {
        tabIndex.value = 1;
        amount.value = discount.value;
    } else if (setDiscountType.value == 1) {
        tabIndex.value = 2;
        percentage.value = (discount.value / subTotal.value) * 100;
    }

    $("#discountModal").modal("show");

};

const showAddProductModal = async () => {

    $("#addProductModal").modal("show");

};

const closeDiscountModal = async () => {
    $("#discountModal").modal("hide");
};

const showTaxModal = async () => {
    $("#taxModal").modal("show");
};

const closeTaxModal = async () => {
    $("#taxModal").modal("hide");
};

let canRunGetProductByCode = true;

const getProductByCode = async (code) => {
    try {
        const response = await axios.get(route("get.product.code", { code }));
        if (response.data.id) {
            beepSound.value.play();
            selectProduct(response.data.id);
            added_alert.value = true;
        } else {
            not_error_alert.value = true;
        }
    } catch (error) {
        console.error("Error fetching product by code:", error);
    }
};

const openQrScannerModal = async () => {
    $("#qrScannerModal").modal("show");
    // Scanner
    html5QrCode.value = new Html5Qrcode("reader");
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        if (decodedText) {
            if (canRunGetProductByCode) {
                getProductByCode(decodedText);
                canRunGetProductByCode = false;
                setTimeout(() => {
                    canRunGetProductByCode = true;
                }, 2000); // 2 seconds delay
                setTimeout(() => {
                    added_alert.value = false;
                    not_error_alert.value = false;
                }, 3000);
            }
        }
    };
    const config = { fps: 10, qrbox: getQrboxSize() };

    // Start scanning.
    html5QrCode.value
        .start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch((err) => {
            console.error(`Error starting QR code scan: ${err}`);
        });
    // Scanner End
};

function getQrboxSize() {
    const screenWidth = window.innerWidth;
    if (screenWidth >= 1024) {
        return { width: 300, height: 300 };
    } else {
        return { width: 280, height: 280 };
    }
}

const closeQrScannerModal = async () => {
    $("#qrScannerModal").modal("hide");
    // Stop scanning when the component is unmounted.
    if (html5QrCode.value) {
        html5QrCode.value
            .stop()
            .then(() => {
                html5QrCode.value.clear();
            })
            .catch((err) => {
                console.error(`Error stopping QR code scan: ${err}`);
            });
    }
};

const searchLoyaltyCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (customer.value.contact == "") {
            setError.value = "The contact is required";
        } else {
            const tableData = (
                await axios.get(
                    route("customer.number.get", customer.value.contact)
                )
            ).data;
            loyalty_customer.value = tableData;
            setError.value = "";
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

const saveNewCustomer = async () => {
    resetValidationErrors();
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const customerData = (
            await axios.post(route("customer.store"), customer.value)
        ).data;
        if (customerData == "exists") {
            errorMessage("This customer already exists");
        } else {
            saveCustomer(customerData);
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

const saveCustomer = async (customer) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (loyalty_customer.value.name == "") {
            setError.value = "The contact is required";
        } else {
            if (customer) {
                const customer_all_details = (
                    await axios.post(route("cart.update"), customer)
                ).data;
                closeCustomerModal();
                selectCustomer.value = customer_all_details.customer_id;
                selectCustomerName.value =
                    customer_all_details.customer_name;
                successMessage("Customer selected successfully");
                setError.value = "";
            }
            setError.value = "";
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

const removeCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        await axios.get(route("customer.remove", orderId.value));
        selectCustomer.value = null;
        successMessage("Successfully removed the customer");
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }

};

const holdCart = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        axios.get(route("cart.hold")).then((response) => {
            window.location.href = route("cart.index");
            successMessage("Holding the order is successful");
        });


        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const addDiscount = async (newDiscount) => {
    nextTick(() => {
        loading_bar.value.start();
    });


    if (newDiscount == 0) {
        errorMessage("Please enter discount amount");
    } else {
        if (amount.value < 0) {
            amount.value = "";
            errorMessage("The discount amount should be more than 0");
        } else if (amount.value > subTotal.value) {
            amount.value = "";
            errorMessage(
                "The discount amount should be less than the subtotal"
            );
        } else if (percentage.value > 100) {
            percentage.value = "";
            errorMessage("Discount percentage should be less than 100%");
        } else if (percentage.value < 0) {
            percentage.value = "";
            errorMessage("The discount percentage should be more than 0%");
        } else {
            const discount_data = {
                discountType: tabIndex.value,
                discountAmount: newDiscount,
                orderId: orderId.value,
            };
            await axios.post(route("cart.discount"), discount_data);
            viewPercentage.value = percentage.value;
            calculateTotals();
            closeDiscountModal();
            successMessage("Discount added successfully");
        }
    }

    nextTick(() => {
        loading_bar.value.finish();
    });
};

const removeDiscount = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.get(route("remove.discount", orderId.value));
        calculateTotals();
        tabIndex.value = 1;
        viewPercentage.value = 0;
        successMessage("Discount removed successfully");
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const paymentDone = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (changeAmount.value < 0 && selectCustomer.value == null) {
            errorMessage("Please select the customer for credit bill");
        } else if (
            paidAmount.value == null ||
            paidAmount.value == "" ||
            paidAmount.value < 0
        ) {
            const paidAmountInput = document.getElementById("paidAmount");
            paidAmountInput.style.borderColor = "red";
            if (paidAmount.value != null && paidAmount.value < 0) {
                errorMessage("The paid amount must be at least 0");
            } else {
                errorMessage("Please add the amount paid");
            }
        } else if (changeAmount.value < 0 && selectCustomer.value != null) {
            const paidAmountInput = document.getElementById("paidAmount");
            paidAmountInput.style.borderColor = "#edeff4";
            $("#creditConfirmModal").modal("show");
        } else {
            const data = {
                order_id: orderId.value,
                customer_id: selectCustomer.value,
                order_total: total.value,
                paid_amount: paidAmount.value,
                balance: changeAmount.value,
            };

            await axios
                .post(route("order.done"), data)
                .then(async (response) => {
                    const print = await axios.get(
                        route("payment.print", orderId.value),
                        { responseType: "blob" }
                    );
                    const url = window.URL.createObjectURL(print.data);
                    window.open(url, "_blank");

                    successMessage("Order is successful");

                    window.location.href = route("cart.index");
                });
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

const confirmCredit = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const data = {
            order_id: orderId.value,
            customer_id: selectCustomer.value,
            order_total: total.value,
            paid_amount: paidAmount.value,
            balance: changeAmount.value,
        };

        await axios.post(route("order.done"), data);

        $("#creditConfirmModal").modal("hide");

        const print = await axios.get(route("payment.print", orderId.value), {
            responseType: "blob",
        });
        const url = window.URL.createObjectURL(print.data);
        window.open(url, "_blank");

        successMessage("Order is successful");

        window.location.href = route("cart.index");

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

watch(
    () => cart.value.name,
    () => {
        onSearchByNameBarcode();
    }
);

watch([amount, subTotal], () => {
    const formattedNewDiscount = Number(amount.value).toFixed(2);
    newDiscount.value = formattedNewDiscount;
    const formattedNewTotal = Number(subTotal.value - amount.value).toFixed(2);
    newTotal.value = formattedNewTotal;
});
watch([percentage, subTotal], () => {
    const formattedNewDiscount = Number(
        subTotal.value * (percentage.value / 100)
    ).toFixed(2);
    newDiscount.value = formattedNewDiscount;
    const formattedNewTotal = Number(
        subTotal.value - newDiscount.value
    ).toFixed(2);
    newTotal.value = formattedNewTotal;
});

const successMessage = (message) => {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

const errorMessage = (message) => {
    Swal.fire({
        icon: "error",
        text: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

const getBusinessDetails = async () => {
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            currency.value = business_details.value.currency_name;
        }

        loadFinished.value = true;
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

const truncateText = (text) => {
    if (text && typeof text === "string") {
        return text.length > 20 ? text.substring(0, 20) + "..." : text;
    }
    return "";
};

const truncateTextMobile = (text) => {
    if (text && typeof text === "string") {
        return text.length > 10 ? text.substring(0, 10) + "..." : text;
    }
    return "";
};

// Reference to the content that will go fullscreen
const fullscreenContent = ref(null);

// Toggle full-screen mode
const toggleFullScreen = () => {
    if (!document.fullscreenElement) {
        // Enter full-screen mode
        if (fullscreenContent.value.requestFullscreen) {
            fullscreenContent.value.requestFullscreen();
        } else if (fullscreenContent.value.mozRequestFullScreen) { // Firefox
            fullscreenContent.value.mozRequestFullScreen();
        } else if (fullscreenContent.value.webkitRequestFullscreen) { // Chrome, Safari, Opera
            fullscreenContent.value.webkitRequestFullscreen();
        } else if (fullscreenContent.value.msRequestFullscreen) { // IE/Edge
            fullscreenContent.value.msRequestFullscreen();
        }
    } else {
        // Exit full-screen mode
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari, Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    }
};

onMounted(() => {
    toggleFullScreen();
    scrollContainer.value = document.querySelector(".select_product_scroller");
    barcodeInput.value.focus();
    getAllProducts();
    getCategories();
    getOderProduct();
    calculateTotals();
    getBusinessDetails();
});
</script>

<style lang="scss" scoped>
.cart-search:focus {
    box-shadow: none;
    outline: none;
}

.product-item-list {
    min-height: 40vh;
    max-height: 42vh;
}

.custom-modal-title {
    color: #071437;
    white-space: normal;
    word-wrap: break-word;
    max-width: 100%;
    color: #071437;
    white-space: normal;
}

.table-bottom-height {
    height: 52px;
}

.text-green {
    color: rgb(0, 116, 0);
}

#reader {
    border: 1px solid gray;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
