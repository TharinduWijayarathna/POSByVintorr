<template>
    <AppLayout title="Quotation Management">
        <template #content>
            <div class="mt-4 d-flex flex-column flex-lg-row mt-md-0">
                <!--begin::Content-->
                <div class="mb-10 flex-lg-row-fluid mb-lg-0 me-lg-7 me-xl-5">
                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card body-->
                        <div class="p-5 card-body p-sm-12">
                            <!--begin::Form-->
                            <form action="" id="kt_invoice_form">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column align-items-start flex-xxl-row">
                                    <!--begin::Input group-->
                                    <div class="order-1 mt-3 d-flex align-items-center flex-equal fw-row me-4 mt-xxl-0"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover"
                                        data-bs-original-title="Specify invoice date" data-kt-initialized="1">
                                        <!--begin::Date-->
                                        <span class="text-gray-800 fs-2x fw-bold">Quotation:</span>&nbsp;
                                        <span class="text-gray-500 fs-2 fw-bold">{{ quotation.code }}</span>

                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                </div>
                                <!--end::Top-->

                                <!--begin::Separator-->
                                <div class="my-10 separator separator-dashed"></div>
                                <!--end::Separator-->

                                <!--begin::Wrapper-->
                                <div class="mb-0">
                                    <!--begin::Row-->
                                    <div class="mb-5 row gx-10">

                                        <!--begin::Col-->
                                        <div class="col-lg-6 ps-sm-0 ps-md-5 quo-padding">
                                            <label class="mb-3 text-gray-700 form-label fs-6 fw-bold">Quotation To <i
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Add new customer"
                                                    v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                    class="cursor-pointer bi bi-plus-lg-fill fs-5 plus-btn"
                                                    @click.prevent="showCustomerModal(emailIndex = 1, phoneIndex = 1)"></i></label>


                                            <div class="mb-2 d-flex position-relative"
                                                v-if="quotation.deleted_at == null">
                                                <Multiselect v-model="select_customer" :options="customersSearch"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Select customer" class="z__index w-100" :showLabels="false"
                                                    :close-on-select="true" :searchable="true"
                                                    :placeholder="computedPlaceholder" label="name" track-by="id"
                                                    :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                                    max-height="200" @search-change="getCustomerSearch"
                                                    :internal-search="false">
                                                    <template #noOptions>
                                                        <div>Press a key to select Customer</div>
                                                    </template>
                                                    <template #noResult>
                                                        <div>No matching customers found</div>
                                                    </template>
                                                </Multiselect>

                                                <a v-if="loyalty_customer.customer_name && quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                    href="#" data-toggle="tooltip" data-placement="bottom"
                                                    title="Remove selected customer"
                                                    class="position-absolute user-remove-btn"
                                                    @click.prevent="removeCustomer">
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                </a>
                                            </div>
                                            <div class="mb-2" v-else>
                                                <input v-model="quotation.customer_name" type="text"
                                                    class="form-control form-control-solid form-control-sm"
                                                    placeholder="Name" @keyup="editQuotationCustomerDetails"
                                                    :disabled="quotation.deleted_at != null">
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-2">
                                                <input v-model="quotation.customer_mobile" type="text"
                                                    class="form-control form-control-solid form-control-sm"
                                                    placeholder="Contact" @keyup="editQuotationCustomerDetails"
                                                    :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)">
                                            </div>
                                            <div class="mb-2">
                                                <input v-model="quotation.customer_email" type="text"
                                                    class="form-control form-control-solid form-control-sm"
                                                    placeholder="Email" @keyup="editQuotationCustomerDetails"
                                                    :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)">
                                            </div>
                                            <div class="mb-2">
                                                <input v-model="quotation.customer_address" type="text"
                                                    class="form-control form-control-solid form-control-sm"
                                                    placeholder="Address" @keyup="editQuotationCustomerDetails"
                                                    :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)">
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <div class="mt-10 col-lg-3 ps-0">
                                        </div>
                                        <div class="text-right col-lg-3">
                                            <label class="text-gray-700 form-label fw-bold fs-6">Project /
                                                Ref</label>
                                            <input v-model="ref_no" type="text"
                                                class="form-control form-control-solid form-control-sm"
                                                placeholder="xxxx" @keyup="storeRef"
                                                :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)">

                                            <div class="row">
                                                <div v-for="quotation_parameter in quotation_parameters"
                                                    class="mt-2 col-12">
                                                    <div class="label-container">
                                                        <label class="text-gray-700 form-label fw-bold fs-6">{{
                                                            quotation_parameter.name }}</label>
                                                        <a v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                            href="javascript:void(0)" class="active-color-primary"
                                                            @click="getCustomField(quotation_parameter.id)">
                                                            <i data-toggle="tooltip" data-placement="bottom"
                                                                title="Edit custom filed"
                                                                class="pl-1 fa-solid fa-ellipsis-vertical three-dots-icon-color"></i>
                                                        </a>
                                                    </div>

                                                    <input type="text"
                                                        class="form-control form-control-solid form-control-sm"
                                                        v-model="quotation_parameter.description"
                                                        :placeholder="quotation_parameter.name"
                                                        :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                                        @keyup="saveParameterDescription(quotation_parameter.id, quotation_parameter)">
                                                </div>
                                                <div class="mt-4 col-12">
                                                    <a v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                        data-toggle="tooltip" data-placement="bottom"
                                                        title="Add new custom field" href="javascript:void(0)"
                                                        class="active-color-primary add-new-text-format"
                                                        @click="openNewParameterModel">
                                                        <i class="bi bi-plus-lg add-new-text-format"></i>
                                                        New Custom Field</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->

                                    <!--begin::Table wrapper-->
                                    <div class="mb-10 table-responsive-overflow-x">
                                        <!--begin::Table-->
                                        <table class="table mb-0 text-gray-700 g-5 gs-0 fw-bold"
                                            data-kt-element="items">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="text-gray-700 border-bottom fs-7 fw-bold text-uppercase">
                                                    <th class="py-3 ps-2 min-w-200px w-475px">Item</th>
                                                    <th></th>
                                                    <th class="py-3 min-w-75px w-100px text-end">QTY</th>
                                                    <th class="py-3 min-w-120px w-150px text-end">Unit Price</th>
                                                    <th v-if="quotation.currency_name"
                                                        class="py-3 min-w-100px w-150px text-end pe-2">Total ({{
                                                            quotation.currency_name }})
                                                    </th>
                                                    <th v-else class="py-3 min-w-100px w-150px text-end pe-2">Total ({{
                                                        currency
                                                    }})
                                                    </th>
                                                    <th v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                        class="py-3 pe-2 min-w-75px w-75px text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->

                                            <!--begin::Table body-->
                                            <tbody>
                                                <tr v-for="orderProduct, key in orderProducts"
                                                    class="border-bottom border-bottom-dashed" data-kt-element="item">
                                                    <td class="py-1 ps-2 pe-7">
                                                        {{ truncateText(orderProduct.product_name) }}
                                                        <br />
                                                        <span v-if="orderProduct.description" class="description-text">
                                                            {{
                                                                truncateDescription(orderProduct.description)
                                                            }}
                                                        </span>
                                                    </td>
                                                    <td class="py-1"></td>

                                                    <td class="py-1 text-end ps-0">
                                                        {{ orderProduct.quantity }}
                                                    </td>

                                                    <td class="py-1 text-end">
                                                        {{ orderProduct.formatted_unit_price }}
                                                    </td>

                                                    <td class="py-1 text-end text-nowrap pe-2">
                                                        {{ orderProduct.formatted_sub_total }}
                                                    </td>
                                                    <td v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                        class="py-1 text-end text-nowrap pe-2">
                                                        <a href="javascript:0;" type="button" data-toggle="tooltip"
                                                            data-placement="bottom" title="Edit item"
                                                            @click.prevent="editQty(orderProduct)"><i
                                                                class="bi bi-pencil-square text-dark fs-5"></i></a>
                                                    </td>
                                                </tr>
                                                <tr v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                    class="border-bottom border-bottom-dashed multiselect-header"
                                                    data-kt-element="item">
                                                    <td class="pe-7 multiselect-dropdown">
                                                       
                                                        <Multiselect v-model="select_product" :options="productsSearch.map(product => ({
                                                            ...product, name: truncateText(product.name),
                                                            searchableText: `${product.code} : ${truncateText(product.name)}`
                                                        }))" data-toggle="tooltip" data-placement="bottom"
                                                            title="Select product" class="z__index"
                                                            :class="{ 'error-border': changeProductBorder === 1 }"
                                                            :showLabels="false" :close-on-select="true"
                                                            :searchable="true" placeholder="Select Product"
                                                            label="searchableText" track-by="id" max-height="200"
                                                            @search-change="getProductSearch" :internal-search="false">
                                                            <template #noOptions>
                                                                <div>Press a key to select Product</div>
                                                            </template>
                                                            <template #noResult>
                                                                <div>No matching products found</div>
                                                            </template>
                                                            <template #beforeList>
                                                                <div @click="openNewModal"
                                                                    class="btn btn-info multiselect-add-new-button"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Add new product" style="font-weight: bold">
                                                                    <i data-toggle="tooltip" data-placement="bottom"
                                                                        title="Add item"
                                                                        class="bi bi-plus-lg add-new-plus-icon"></i>
                                                                    New
                                                                    Product
                                                                </div>
                                                            </template>
                                                        </Multiselect>
                                                        <textarea v-model="selected_product.description"
                                                            class="pt-2 mt-2 form-control form-control-solid"
                                                            placeholder="Product Description" data-kt-autosize="true"
                                                            style="resize: none; outline: none; box-shadow: none;"
                                                            rows="2"></textarea>
                                                    </td>

                                                    <td></td>

                                                    <td class="ps-0">
                                                        <input type="number"
                                                            class="form-control form-control-solid form-control-sm"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Change quantity" min="1" name="quantity[]"
                                                            placeholder="0" @keyup="selectItemUpdate"
                                                            v-model="selected_product.quantity"
                                                            data-kt-element="quantity"
                                                            :class="{ 'error-border': changeQuantityBorder === 1 }">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-solid form-control-sm text-end"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Change unit price" name="price[]" placeholder="0.00"
                                                            @keyup="selectItemUpdate"
                                                            v-model="selected_product.formatted_selling_price"
                                                            data-kt-element="price">
                                                    </td>

                                                    <td class="pt-8 text-end text-nowrap">
                                                        <span data-kt-element="total">{{
                                                            new_total.toLocaleString(undefined, { minimumFractionDigits:
                                                            2,
                                                            maximumFractionDigits: 2 }) }}</span>
                                                    </td>

                                                    <td class="pt-5 text-end">
                                                        <button type="button" data-toggle="tooltip"
                                                            data-placement="bottom" title="Add item"
                                                            class="btn btn-sm btn-icon btn-primary"
                                                            data-kt-element="remove-item"
                                                            @click.prevent="addToQuotation">
                                                             <span class="bi bi-plus-lg-fill fs-4"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Add item">
                                                                    +
                                                                    </span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->

                                            <!--begin::Table foot-->
                                            <tfoot>
                                                <tr
                                                    class="text-gray-700 align-top border-top border-top-dashed fs-6 fw-bold">
                                                    <th></th>
                                                    <th></th>

                                                    <th colspan="2" class="ps-0">
                                                        <div class="d-flex flex-column align-items-start ">
                                                            <div class="fs-5">Subtotal</div>
                                                        </div>
                                                    </th>

                                                    <th colspan="2" class="text-end pe-2">
                                                        <span v-if="quotation.currency_name"
                                                            data-kt-element="sub-total">{{
                                                                quotation.currency_name
                                                            }} {{ Number(subTotal).toLocaleString('en-US', {
                                                                minimumFractionDigits: 2
                                                            }) }}</span>
                                                        <span v-else data-kt-element="sub-total">{{ currency
                                                            }} {{ Number(subTotal).toLocaleString('en-US', {
                                                                minimumFractionDigits: 2
                                                            }) }}</span>
                                                    </th>
                                                </tr>
                                                <tr v-if="orderProducts.length >= 1"
                                                    class="text-gray-700 align-top fs-6 fw-bold">
                                                    <th class="pt-0 text-primary">
                                                    </th>
                                                    <th class="pt-0 text-primary">
                                                    </th>

                                                    <th colspan="2"
                                                        class="pt-0 border-bottom border-bottom-dashed ps-0">
                                                        <div class="d-flex flex-column align-items-start">
                                                            <a v-if="discount == 0 && quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Add new discount" href="#"
                                                                class="py-1 btn btn-link add-new-text-format"
                                                                @click="showDiscountModal()"><i
                                                                    class="bi bi-plus-lg add-new-text-format"></i>Add
                                                                discount</a>
                                                            <a v-if="discount != 0 && quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                href="#" class="py-1 btn btn-link" data-toggle="tooltip"
                                                                data-placement="bottom" title="Change current discount"
                                                                @click="showChangeDiscountModal()">Change
                                                                discount <span v-if="viewPercentage != 0">({{
                                                                    viewPercentage
                                                                }}%)</span>
                                                            </a>
                                                            <div v-if="quotation.deleted_at != null" class="py-1">
                                                                Discount <span v-if="viewPercentage != 0">({{
                                                                    viewPercentage
                                                                }}%)</span></div>
                                                        </div>
                                                    </th>

                                                    <th colspan="2"
                                                        class="pt-0 border-bottom border-bottom-dashed text-end pe-2">
                                                        <span data-kt-element="sub-total"><a
                                                                v-if="discount != 0 && quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                                href="#" class="py-1 btn btn-link"
                                                                @click.prevent="removeDiscount"><i data-toggle="tooltip"
                                                                    data-placement="bottom" title="Remove discount"
                                                                    class="bi bi-dash-circle-fill text-danger discount-remove-btn"></i></a>
                                                            {{ quotation.currency_name }} {{
                                                                Number(discount).toLocaleString('en-US', {
                                                                    minimumFractionDigits: 2
                                                                }) }}</span>
                                                    </th>
                                                </tr>


                                                <tr class="text-gray-700 align-top fw-bold">
                                                    <th class="pt-0 text-primary">
                                                    </th>
                                                    <th class="pt-0 text-primary">
                                                    </th>
                                                    <th colspan="2" class="ps-0">
                                                        <div class="d-flex flex-column align-items-start ">
                                                            <div class="fs-5">Total Tax</div>
                                                        </div>
                                                    </th>

                                                    <th colspan="2" class="text-end pe-2">
                                                        <span v-if="quotation.currency_name"
                                                            data-kt-element="sub-total">{{
                                                                quotation.currency_name
                                                            }} {{ Number(totalTax).toLocaleString('en-US', {
                                                                minimumFractionDigits: 2
                                                            }) }}</span>
                                                        <span v-else data-kt-element="sub-total">{{ currency
                                                            }} {{ Number(totalTax).toLocaleString('en-US', {
                                                                minimumFractionDigits: 2
                                                            }) }}</span>
                                                    </th>
                                                </tr>


                                                <tr class="text-gray-700 align-top fw-bold">
                                                    <th></th>
                                                    <th></th>

                                                    <th colspan="2" class="fs-4 ps-0">Total</th>

                                                    <th colspan="2" class="text-end fs-4 text-nowrap pe-2">
                                                        <span v-if="quotation.currency_name"
                                                            data-kt-element="grand-total">{{ quotation.currency_name }}
                                                            {{
                                                                Number(total).toLocaleString('en-US', {
                                                                    minimumFractionDigits: 2
                                                                })
                                                            }}</span>
                                                        <span v-else data-kt-element="grand-total">{{ currency }} {{
                                                            Number(total).toLocaleString('en-US', {
                                                                minimumFractionDigits: 2
                                                            })
                                                        }}</span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                            <!--end::Table foot-->
                                        </table>
                                    </div>
                                    <!--end::Table-->

                                    <!--begin::Item template-->
                                    <table class="table d-none" data-kt-element="item-template">
                                        <tbody>
                                            <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                                <td class="pe-7">
                                                    <input type="text" class="mb-2 form-control form-control-solid"
                                                        name="name[]" placeholder="Item name">

                                                    <input type="text" class="form-control form-control-solid"
                                                        name="description[]" placeholder="Description">
                                                </td>

                                                <td class="ps-0">
                                                    <input type="text" class="form-control form-control-solid" min="1"
                                                        name="quantity[]" placeholder="1" data-kt-element="quantity">
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control form-control-solid text-end"
                                                        name="price[]" placeholder="0.00" data-kt-element="price">
                                                </td>

                                                <td class="pt-8 text-end">
                                                    $<span data-kt-element="total">0.00</span>
                                                </td>

                                                <td class="pt-5 text-end">
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-active-color-primary"
                                                        data-kt-element="remove-item">
                                                        <i class="ki-outline ki-trash fs-3"></i> </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table d-none" data-kt-element="empty-template">
                                        <tbody>
                                            <tr data-kt-element="empty">
                                                <th colspan="5" class="py-10 text-center text-muted">
                                                    No items
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Item template-->

                                    <!--begin::Notes-->
                                    <div class="mb-0 quotation-note">
                                        <label class="text-gray-700 form-label fs-6 fw-bold">NOTE</label>

                                        <textarea v-model="note" name="notes"
                                            class="form-control form-control-solid note-field-style"
                                            @input="onTextNoteChange()" rows="3" placeholder="Note"
                                            :disabled="quotation.deleted_at != null || quotation.credit_status == 1 || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                            style="resize: none">
    </textarea>

                                    </div>
                                    <!--end::Notes-->

                                    <!-- Footer Parameter Section -->
                                    <div class="mt-3 quotation-footer-parameter">
                                        <div v-for="(quotation_footer_parameter, index) in quotation_footer_parameters"
                                            :key="quotation_footer_parameter.id">
                                            <label class="pt-10 text-gray-700 form-label fw-bold fs-6"
                                                style="text-transform:uppercase">
                                                <a href="javascript:void(0)" class="active-color-primary"
                                                    v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)"
                                                    @click="getFooterCustomField(quotation_footer_parameter.id)">
                                                    <i data-toggle="tooltip" data-placement="bottom"
                                                        title="Edit footer custom field"
                                                        class="bi bi-three-dots-vertical add-new-text-format"></i>
                                                </a>
                                                {{ quotation_footer_parameter.name }}
                                            </label>

                                            <textarea v-model="quotation_footer_parameter.description"
                                                class="form-control form-control-solid" :rows="3" placeholder="Note"
                                                style="resize: none;"
                                                :disabled="quotation.deleted_at != null || quotation.credit_status == 1 || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                                @input="onFooterDescriptionChange(quotation_footer_parameter.id, quotation_footer_parameter.description)">
        </textarea>

                                        </div>
                                        <div class="pt-4 mt-4 col-12"
                                            v-if="quotation.deleted_at == null && (page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                            <a href="javascript:void(0)"
                                                class="active-color-primary add-new-text-format" data-toggle="tooltip"
                                                data-placement="bottom" title="Add new custom filed"
                                                @click="openNewFooterParameterModel">
                                                <i class="bi bi-plus-lg add-new-text-format"></i> New Custom
                                                Field
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Footer Parameter Section -->

                                </div>
                                <!--end::Wrapper-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->

                <!--begin::Sidebar-->
                <div class="mb-4 flex-lg-auto min-w-lg-355px mb-md-0">
                    <!--begin::Card-->
                    <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                        data-kt-sticky-width="{lg: '250px', lg: '300px'}" data-kt-sticky-left="auto"
                        data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">

                        <!--begin::Card body-->
                        <div class="p-5 py-10 card-body p-md-1 px-md-8">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="text-gray-700 form-label fw-bold fs-6">Currency</label>
                                <!--end::Label-->

                                <!--begin::Select-->
                                <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                                    data-toggle="tooltip" data-placement="bottom" title="Select currency"
                                    :showLabels="false" :close-on-select="true" :clear-on-select="false"
                                    :searchable="true" placeholder="Select Currency" label="code" track-by="id"
                                    :disabled="quotation.deleted_at != null || (page_props.auth.user.user_role_id != 1 && page_props.auth.user.user_role_id != 4)"
                                    max-height="200" />
                                <!--end::Select-->
                            </div>

                            <div class="mt-5 mb-0">
                                <div class="mb-5 row" v-if="quotation.deleted_at == null && orderProducts.length > 0">
                                    <div class="col-4 pe-0"
                                        v-if="(page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                        <a @click.prevent="historyPrint()" type="submit" href="#" data-toggle="tooltip"
                                            data-placement="bottom" title="Print quotation"
                                            class="btn btn-primary w-100">PRINT
                                        </a>
                                    </div>
                                    <div class="col-12" v-else>
                                        <a @click.prevent="historyPrint()" type="submit" href="#" data-toggle="tooltip"
                                            data-placement="bottom" title="Print quotation"
                                            class="btn btn-primary w-100">PRINT
                                        </a>
                                    </div>
                                    <div class="col-4"
                                        v-if="(page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                        <a href="javascript:void(0)" class="btn w-100 btn-warning" data-toggle="tooltip"
                                            data-placement="bottom" title="Email quotation"
                                            @click.prevent="showCustomerEmailModal">EMAIL</a>
                                    </div>
                                    <div class="col-4 ps-0"
                                        v-if="(page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                        <a href="javascript:void(0)" @click.prevent="deleteQuotation()"
                                            data-toggle="tooltip" data-placement="bottom" title="Delete quotation"
                                            class="btn w-100 btn-danger">DELETE</a>
                                    </div>
                                </div>
                                <div class="mb-5 row" v-if="quotation.deleted_at == null && orderProducts.length > 0">
                                    <div class="col"
                                        v-if="(page_props.auth.user.user_role_id == 1 || page_props.auth.user.user_role_id == 4)">
                                        <a @click.prevent="convertToInvoice()" type="submit" href="#"
                                            class="btn btn-success w-100" data-toggle="tooltip" data-placement="bottom"
                                            title="Convert to invoice" style="background-color:rgb(5, 161, 5);">CONVERT
                                            TO INVOICE
                                        </a>
                                    </div>

                                </div>
                                <div class="mb-5 row" v-if="quotation.deleted_at != null">
                                    <div class="col">
                                        <a @click.prevent="restoreInvoice()" type="submit" href="#"
                                            data-toggle="tooltip" data-placement="bottom" title="Restore quotation"
                                            class="btn btn-warning w-100">RESTORE
                                        </a>
                                    </div>

                                </div>

                            </div>

                            <!--end::Actions-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
            </div>
        </template>
        <template #modal>
            <!-- Customer Modal -->
            <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title custom-title">Add Customer</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                @click="closeCustomerModal"></button>
                        </div>
                        <div class="modal-body">
                            <form enctype="multipart/form-data">
                                <div class="mb-8 row">
                                    <div class="mt-2 col-md-4">
                                        <span class="text-gray-600">PHONE NUMBER</span>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm"
                                            v-model="customer.contact" placeholder="Phone Number" required />
                                    </div>
                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-12 justify-content-end text-end">
                                                <a class="square-clear-button" @click.prevent="setQuotationCustomer">
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
                                        </div>
                                    </div>
                                    <div class="mt-2 row">
                                        <div class="mt-2 col-md-4">
                                            <span class="text-gray-600">EMAIL ADDRESS</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="email" class="form-control form-control-sm"
                                                v-model="loyalty_customer.email" placeholder="Email Address" disabled />
                                        </div>
                                    </div>
                                    <div class="mt-2 row">
                                        <div class="mt-2 col-md-4">
                                            <span class="text-gray-600">ADDRESS</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm"
                                                v-model="loyalty_customer.address" placeholder="Address" disabled />
                                        </div>
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="col-12 justify-content-end text-end">
                                            <a class="square-clear-button"
                                                @click.prevent="saveCustomer(loyalty_customer)">
                                                <i class=" bi bi-chevron-double-right"></i>
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
                                            <a type="button" class="square-clear-button"
                                                @click.prevent="saveNewCustomer">
                                                <i class=" bi bi-chevron-double-right"></i>
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
                            <h5 class="modal-title custom-title">Discount</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"
                                @click="closeDiscountModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-8 row">
                                <div class="mt-2 col-md-4 text-end">
                                    <span class="text-gray-800">Subtotal</span><br>
                                    <span class="text-gray-600">{{ Number(subTotal).toLocaleString('en-US', {
                                        minimumFractionDigits: 2
                                    }) }}</span><br>
                                    <div class="py-1"></div>
                                    <span class="text-gray-800">Discount</span><br>
                                    <span class="text-gray-600">{{ Number(newDiscount).toLocaleString('en-US', {
                                        minimumFractionDigits: 2
                                    }) ? Number(newDiscount).toLocaleString('en-US', {
                                        minimumFractionDigits: 2
                                    }) : 0 }}</span><br>
                                    <hr />
                                    <span class="text-gray-800">New Total</span><br>
                                    <span class="text-gray-600">{{ Number(newTotal).toLocaleString('en-US', {
                                        minimumFractionDigits: 2
                                    }) }}</span>
                                </div>
                                <!-- <div class="col-md-1"></div> -->
                                <div class="col-md-8 ps-5">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0)"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                                data-toggle="tooltip" data-placement="bottom" title="Amount"
                                                :class="{ 'active': tabIndex === 1 }"
                                                @click="tabIndex = 1, percentage = ''" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">Amount</span>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0)"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                                data-toggle="tooltip" data-placement="bottom" title="Percentage"
                                                :class="{ 'active': tabIndex === 2 }" @click="tabIndex = 2, amount = ''"
                                                data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">Percentage</span>
                                            </a>
                                        </div>
                                        <div :class="[tabIndex === 2 ? 'd-none' : '',]" class="mt-5 col-12 d-block">
                                            <input type="number" class="form-control form-control-sm" v-model="amount"
                                                data-toggle="tooltip" data-placement="bottom" title="Change amount"
                                                placeholder="Amount" required />
                                        </div>
                                        <!-- <div v-else :class="[tabIndex === 2 ? 'd-none' : '',]"
                                            class="mt-5 col-12 d-block">
                                            <input type="number" class="form-control form-control-sm" v-model="amount"
                                                data-toggle="tooltip" data-placement="bottom" title="Enter amount"
                                                placeholder="Amount" required />
                                        </div> -->
                                        <div :class="[tabIndex === 1 ? 'd-none' : '',]" class="mt-5 col-12 d-block">
                                            <input type="number" class="form-control form-control-sm"
                                                data-toggle="tooltip" data-placement="bottom" title="Change percentage"
                                                v-model="percentage" placeholder="Percentage" required />
                                        </div>
                                        <!-- <div v-else :class="[tabIndex === 1 ? 'd-none' : '',]"
                                            class="mt-5 col-12 d-block">
                                            <input type="number" class="form-control form-control-sm"
                                                data-toggle="tooltip" data-placement="bottom" title="Enter percentage"
                                                v-model="percentage" placeholder="Percentage" required />
                                        </div> -->
                                    </div>
                                    <div class="mt-5 row">
                                        <div class="col-12">
                                            <a href="javascript:void(0)" @click.prevent="addDiscount(newDiscount)"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 "
                                                data-kt-button="true" data-toggle="tooltip" data-placement="bottom"
                                                title="Add discount">
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
                                    <h5 class="modal-title custom-title">
                                        <!-- {{ edit_product.name }} -->
                                        Edit Product
                                    </h5>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form @submit.prevent="selectQuotationItemUpdate" enctype="multipart/form-data">
                                <div class="text-gray-600 col-form-label">Product Name</div>
                                <div class="row">
                                    <div class="col-12">
                                        <input v-model="edit_select_item.product_name" type="text"
                                            class="form-control form-control-sm" placeholder="Product Name" />
                                    </div>
                                </div>
                                <div class="text-gray-600 col-form-label">Description</div>
                                <div class="row">
                                    <div class="col-12">
                                        <input v-model="edit_select_item.description" type="text"
                                            class="form-control form-control-sm" placeholder="Description" />
                                    </div>
                                </div>
                                <div class="text-gray-600 col-form-label">Quantity</div>
                                <div class="row">
                                    <div class="col-12">
                                        <input v-model="edit_select_item.quantity" type="text"
                                            class="form-control form-control-sm" placeholder="Quantity" />
                                    </div>
                                </div>
                                <div class="text-gray-600 col-form-label">Unit Price</div>
                                <div class="row">
                                    <div class="col-12">
                                        <input v-model="edit_select_item.unit_price" type="text"
                                            class="form-control form-control-sm" placeholder="Unit Price" />
                                    </div>
                                </div>
                                <div class="mt-5 row">
                                    <div class="text-gray-500 col-12">
                                        <hr />
                                    </div>
                                    <div class="text-center col-6">
                                        <button type="button" @click.prevent="removeItem" data-toggle="tooltip"
                                            data-placement="bottom" title="Remove this item from Quotation"
                                            class="btn btn-light-danger w-100 fw-bold">
                                            REMOVE ITEM
                                        </button>
                                    </div>
                                    <div class="text-center col-6">
                                        <button type="submit" class="btn btn-light-info w-100 fw-bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes">
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-gray-700 modal-body fs-4">
                            Do you want to add a credit invoice of {{ currency }} {{
                                Number(Math.abs(changeAmount)).toLocaleString('en-US', {
                                    minimumFractionDigits: 2
                                }) }} ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info fw-bold" @click.prevent="confirmCredit">
                                Yes, confirm it!
                            </button>
                            <button type="button" class="btn btn-light-success fw-bold" data-bs-dismiss="modal">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title custom-title">PAYMENT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="savePayment" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="payment_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="payment.balance" type="text" class="form-control form-control-sm"
                                    placeholder="Enter paid amount" />
                                <div class="mt-2 text-gray-600 col-form-label">Remark</div>
                                <textarea v-model="payment.remark" class="form-control custom-text-area"
                                    placeholder="Enter payment remark" data-kt-autosize="true" rows="2"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="mt-2 btn btn-info me-2 fw-bold">
                                    ADD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Payment Modal -->
            <div class="modal fade" id="editBillModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title custom-title">EDIT PAYMENT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="updatePayment" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="text-gray-600 col-form-label">Date</div>
                                <Datepicker v-model="edit_payment_date" class="form-control form-control-sm"
                                    :placeholder="placeholderText" :format="'dd/MM/yyyy'" />
                                <div class="text-gray-600 col-form-label">Amount</div>
                                <input v-model="billData.accepted_amount
                                    " type="text" class="form-control form-control-sm"
                                    placeholder="Enter paid amount" />
                                <div class="mt-2 text-gray-600 col-form-label">Remark</div>
                                <textarea v-model="billData.remark" class="form-control custom-text-area"
                                    placeholder="Enter payment remark" data-kt-autosize="true" rows="2"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="mt-2 btn btn-light-info me-2 fw-bold">
                                    UPDATE
                                </button>
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
                            Are you sure you want to delete this quotation?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr fw-bold" data-bs-dismiss="modal"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger fw-bold" @click.prevent="confirmDelete"
                                data-toggle="tooltip" data-placement="bottom" title="Delete quotation">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Quick Customer Modal -->
            <div class="modal fade" id="quickCustomerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="saveNewCustomer" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-6 col-8">
                                        <h5 class="modal-title custom-title">Add New Customer</h5>
                                    </div>
                                    <div class="mb-6 col-4 text-end">
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                            data-toggle="tooltip" data-placement="bottom" title="Close"
                                            @click="closeCustomerModal"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Customer</lable>
                                        <input v-model="customerData.name" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Customer Name" />
                                        <small v-if="validationErrors.name"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.name }}</small>
                                    </div>
                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Address</lable>
                                        <input v-model="customerData.address" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Address" />
                                        <small v-if="validationErrors.address"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.address }}</small>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Email 1</lable>
                                        <input v-model="customerData.email" type="email"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Email 1" />
                                        <small v-if="validationErrors.email"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.email }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another email address"
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
                                                <input v-model="customerData.email2" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 2" />
                                                <small v-if="validationErrors.email2"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.email2 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[emailIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear email address"
                                                    @click="emailIndex = 1, clearEmail2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[emailIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another email address"
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
                                                <input v-model="customerData.email3" type="email"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Email 3" />
                                                <small v-if="validationErrors.email3"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.email3 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer email address"
                                                    @click="emailIndex = 2, clearEmail3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Phone No.1</lable>
                                        <input v-model="customerData.contact" type="text"
                                            class="mt-1 form-control form-control-sm"
                                            placeholder="Enter Phone Number1" />
                                        <small v-if="validationErrors.contact"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.contact }}</small>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 1 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another phone number"
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
                                                <input v-model="customerData.contact2" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number2" />
                                                <small v-if="validationErrors.contact2"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.contact2 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center"
                                                :class="[phoneIndex === 3 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer phone number"
                                                    @click="phoneIndex = 1, clearContact2()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mt-2 mb-2 col-12" :class="[phoneIndex !== 2 ? 'd-none' : '',]">
                                                <a href="javascript:void(0)" class="text-primary" data-toggle="tooltip"
                                                    data-placement="bottom" title="Add another phone number"
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
                                                <input v-model="customerData.contact3" type="text"
                                                    class="mt-1 form-control form-control-sm"
                                                    placeholder="Enter Phone Number3" />
                                                <small v-if="validationErrors.contact3"
                                                    class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                    {{ validationErrors.contact3 }}</small>
                                            </div>
                                            <div class="col-1 d-flex align-items-center">
                                                <a href="javascript:void(0)" class="text-success d-inline-block"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="Clear customer phone number"
                                                    @click="phoneIndex = 2, clearContact3()"><i
                                                        class="bi bi-dash-circle-fill text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-12">
                                        <lable class="text-gray-600">Website</lable>
                                        <input v-model="customerData.website" type="text"
                                            class="mt-1 form-control form-control-sm" placeholder="Enter Website" />
                                        <small v-if="validationErrors.website"
                                            class="mt-1 text-sm text-danger form-text text-error-msg error">
                                            {{ validationErrors.website }}</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info fw-bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Add new customer">
                                            ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Product Modal -->
            <div class="modal fade modal-lg add-product-modal" id="productModal" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
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
                                                    active: product_tab_index === 1,
                                                }" @click="(product_tab_index = 1)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">General</span>
                                            </a>
                                        </div>
                                        <div class="mt-2 col-lg-6 mt-lg-0">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom"
                                                title="Taxes"
                                                class="px-4 border border-gray-200 btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100"
                                                :class="{
                                                    active: product_tab_index === 2,
                                                }" @click="(product_tab_index = 2)" data-kt-button="true">
                                                <span class="fs-7 fw-bold d-block">Taxes</span>
                                            </a>
                                        </div>

                                        <!-- General Data -->
                                        <div :class="{ 'd-none': product_tab_index !== 1 }" class="mt-5 col-lg-12">
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
                                                    <input v-model="product.cost" type="cost"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Cost" />
                                                    <small v-if="validationErrors.cost"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.cost }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Selling Price</div>
                                                    <input v-model="product.selling" type="text"
                                                        class="form-control form-control-sm"
                                                        placeholder="Product Selling Price" />
                                                    <small v-if="validationErrors.selling"
                                                        class="mt-1 text-sm text-danger form-text text-error-msg error">
                                                        {{ validationErrors.selling }}</small>
                                                </div>

                                                <div class="col-12">
                                                    <div class="pb-2 text-gray-600 col-form-label">Priority</div>
                                                    <input v-model="product.order_no" type="text"
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
                                                    <input v-model="product.stock_quantity" type="text"
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
                                                    <button type="submit" class="btn btn-info"
                                                        style="font-weight: bold" data-toggle="tooltip"
                                                        data-placement="bottom" title="Add new product">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Taxes -->
                                        <div :class="{ 'd-none': product_tab_index !== 2 }" class="mt-5 col-lg-12">
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
                                                <table class="table mt-5 align-middle table-hover table-striped fs-6 gy-3">
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
                                                                    <i class="p-2 bi bi-trash-fill fs-5 text-danger"></i>
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
                                                        <button type="submit"
                                                            class="mt-2 btn btn-info me-2 fw-bold"
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
                                                        <button type="submit"
                                                            class="mt-2 btn btn-info me-2 fw-bold"
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

            <!-- Add Footer Parameter Modal -->
            <div class="modal fade" id="newFooterParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title custom-title">NEW FOOTER CUSTOM FIELD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <form @submit.prevent="saveFooterParameter" enctype="multipart/form-data">
                            <div class="py-0 modal-body">
                                <div class="text-gray-600 col-form-label">Custom Field Title</div>
                                <input v-model="parameter_footer_title.title" type="text"
                                    class="form-control form-control-sm" placeholder="Enter new custom field title" />
                                <small v-if="validationErrors.title" id="title"
                                    class="text-danger form-text text-error-msg error">{{
                                        validationErrors.title
                                    }}</small>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="submit" class="mt-2 btn btn-info me-2 fw-bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Add new footer custom field">
                                    ADD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Footer Parameter Modal -->
            <div class="modal fade" id="editFooterParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title custom-title">EDIT FOOTER CUSTOM FIELD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <form @submit.prevent="updateFooterParameter(quotation_footer_parameter_field.id)"
                            enctype="multipart/form-data">
                            <div class="py-0 modal-body">
                                <div class="text-gray-600 col-form-label">Custom Field Title</div>
                                <input v-model="parameter_footer_field" type="text" class="form-control form-control-sm"
                                    placeholder="Enter new custom field title" />
                                <small v-if="validationErrors.title" id="title"
                                    class="text-danger form-text text-error-msg error">{{
                                        validationErrors.title
                                    }}</small>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="submit" class="mt-2 btn btn-light-info me-2 fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Save changes">
                                    UPDATE
                                </button>
                                <button type="button" class="mt-2 btn btn-light-danger fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Delete footer custom field"
                                    @click="deleteFooterParameter">
                                    DELETE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer Parameter Delete Confirmation Modal -->
            <div class="modal fade" id="deleteFooterParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <b>{{ parameter_footer_field }}</b> field?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr fw-bold" data-bs-dismiss="modal"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger fw-bold" data-toggle="tooltip"
                                data-placement="bottom" title="Delete Footer parameter"
                                @click.prevent="confirmDeleteFooterParameter">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of footer parameter -->

            <!-- Add Header Parameter Modal -->
            <div class="modal fade" id="newParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title custom-title">NEW CUSTOM FIELD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <form @submit.prevent="saveParameter" enctype="multipart/form-data">
                            <div class="py-0 modal-body">
                                <div class="text-gray-600 col-form-label">Custom Field Title</div>
                                <input v-model="parameter_title.title" type="text" class="form-control form-control-sm"
                                    placeholder="Enter new custom field title" />
                                <small v-if="validationErrors.title" id="title"
                                    class="text-danger form-text text-error-msg error">{{
                                        validationErrors.title
                                    }}</small>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="submit" class="mt-2 btn btn-info me-2 fw-bold"
                                    data-toggle="tooltip" data-placement="bottom" title="Add new custom field">
                                    ADD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Header Parameter Modal -->
            <div class="modal fade" id="editParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title custom-title">EDIT CUSTOM FIELD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                        </div>
                        <form @submit.prevent="updateParameter(quotation_parameter_field.id)"
                            enctype="multipart/form-data">
                            <div class="py-0 modal-body">
                                <div class="text-gray-600 col-form-label">Custom Field Title</div>
                                <input v-model="parameter_field" type="text" class="form-control form-control-sm"
                                    placeholder="Enter new custom field title" />
                                <small v-if="validationErrors.title" id="title"
                                    class="text-danger form-text text-error-msg error">{{
                                        validationErrors.title
                                    }}</small>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="submit" class="mt-2 btn btn-light-info me-2 fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Save changes">
                                    UPDATE
                                </button>
                                <button type="button" class="mt-2 btn btn-light-danger fw-bold" data-toggle="tooltip"
                                    data-placement="bottom" title="Delete custom field" @click="deleteParameter">
                                    DELETE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Parameter Delete Confirmation Modal -->
            <div class="modal fade" id="deleteParameterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <b>{{ parameter_field }}</b> field?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-darkr fw-bold" data-bs-dismiss="modal"
                                data-toggle="tooltip" data-placement="bottom" title="Cancel">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-light-danger fw-bold" data-toggle="tooltip"
                                data-placement="bottom" title="Delete parameter"
                                @click.prevent="confirmDeleteParameter">
                                <i class="bi bi-trash"></i>
                                DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer mail modal -->
            <div class="modal fade" id="send_mail" tabindex="-1" data-bs-backdrop="static"
                aria-labelledby="addNewCardTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="pb-4 pl-4 pr-4 modal-content">
                        <div class="pb-5 modal-body px-sm-5 mx-50">
                            <div class="row">
                                <div class="mb-5 col-8">
                                    <h1 class="modal-title" style="color: #071437">Send Quotation</h1>
                                </div>
                                <div class="mb-5 col-4 text-end">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        data-toggle="tooltip" data-placement="bottom" title="Close"></button>
                                </div>
                            </div>
                            <form id="addNewCardValidation" class="row gy-1 gx-2 mt-75"
                                @submit.prevent="sendQuotationEmail()">
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Send To</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email" class="form-control emailinput" id="to"
                                            v-model="customer_email.to" placeholder="Enter Email Address" />
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
                                            v-model="customer_email.cc" placeholder="Enter Email Addresses" />
                                    </div>
                                    <div id='output'></div>
                                </div>
                                <div class="mt-4 col-12">
                                    <label class="form-label" for="modalAddCardNumber">Subject</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" v-model="customer_email.subject"
                                            placeholder="Enter Email Subject" />
                                    </div>
                                </div>
                                <div class="mt-4 col-12">
                                    <div class="mb-2">
                                        <label class="form-label line-height-3" for="modalAddCardNumber">
                                            Message
                                        </label>
                                        <QuillEditor v-model="customer_email.message" :toolbar="emailToolbarOptions"
                                            id="email-message" @keyup="setInvoiceNote" :options="quillOptions"
                                            placeholder="Note">
                                        </QuillEditor>
                                    </div>
                                </div>
                                <div class="mt-4 text-right col-12">
                                    <button type="button" class="mr-2 btn btn-light-darkr fw-bold" data-toggle="tooltip"
                                        data-placement="bottom" title="Cancel" data-bs-dismiss="modal">
                                        CANCEL
                                    </button>
                                    <button type="submit" class="mt-1 btn btn-info fw-bold"
                                        data-toggle="tooltip" data-placement="bottom" title="Send quotation email">
                                        <i class="bi bi-send"></i>
                                        SEND
                                    </button>
                                </div>
                            </form>
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
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref, reactive, watch, nextTick, computed } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import { usePage } from '@inertiajs/vue3'
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue3-datepicker'
import { QuillEditor } from '@vueup/vue-quill';
import { errorMessages } from 'vue/compiler-sfc';

import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const { props } = usePage();
const page_props = props;

const loading = ref(false);
const cart = ref({ name: '', product_category_id: null });
const products = ref([]);
const orderProducts = ref([]);
const categories = ref([]);
const selectedId = ref(null);
const subTotal = ref(0);
const totalTax = ref(0);
const total = ref(0);
const discount = ref(0);
const customer_paid = ref(0);
const setDiscountType = ref(0);
const customer = ref({});
const loyalty_customer = ref({});
const selected_product = ref({});
const selectCustomer = ref('');
const selectCustomerName = ref('');
const orderId = ref(null);
const quotation = ref({});

const from_date = ref(new Date());
const due_date = ref(null);
const placeholderText = 'DD/MM/YYYY';
const payment_date = ref(new Date());
const edit_payment_date = ref(null);
const currencies = ref([]);
const select_currency = ref(null);
const ref_no = ref('');
const select_search_customer = ref({});
const select_customer = ref(null);
const customers = ref([]);
const customer_id = ref(null);
const currency_id = ref(null);
const product_id = ref(null);

const viewSelect = ref(0);
const selected_customer = ref(null);
const select_product = ref(null);
const new_total = ref(0);

const tabIndex = ref(1);
const product_tab_index = ref(1);
const amount = ref('');
const percentage = ref('');
const viewPercentage = ref(0);
const newDiscount = ref(0);
const newTotal = ref(0);

const taxes = ref([]);
const temp_product_taxes = ref([]);
const select_tax = ref([]);

const customerData = ref({});
const emailIndex = ref(1);
const phoneIndex = ref(1);

const edit_product = ref({});
const edit_select_item = ref({});

const item_details = ref({});

const unit_price = ref('');
const paidAmount = ref(null);
const changeAmount = ref(0);

const business_details = ref({});
const currency = ref('');
const select_currency_id = ref(null);
const note = ref('');
const edit_note = ref({});

const changeProductBorder = ref(0);
const changeQuantityBorder = ref(0);

const bills = ref({});
const payment = ref({});

const billData = ref({});

const barcodeInput = ref(null);

const scrollContainer = ref(null);

const loading_bar = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);
const error_text = ref({});

const is_stockable = ref(false);
const is_non_stockable = ref(true);
const imageValidation = ref('');
const editImageValidation = ref('');
const units = ref([]);
const select_category = ref([]);
const select_unit = ref([]);
const is_visible = ref(true);
const is_hide = ref(false);

const quotation_footer_parameters = ref({});
const parameter_footer_title = ref({});
const quotation_footer_parameter_field = ref({});
const parameter_footer_field = ref('');

const quotation_parameters = ref({});
const parameter_title = ref({});
const quotation_parameter_field = ref({});
const parameter_field = ref('');

const strippedNote = ref(null);
const customersSearch = ref([]);
const productsSearch = ref([]);

const customer_email = ref({});
const selectedCustomerData = ref({});
const categoryData = ref({});
const taxData = ref({});


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



const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const computedPlaceholder = computed(() => {
    return quotation.value.customer_name ? quotation.value.customer_name : "Select Customer";
});

const getCustomerSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('customer.multiselect.all.search', { query }));
            customersSearch.value = response.data;
        } catch (error) {
            customersSearch.value = [];
        }
    } else {
        customersSearch.value = [];
    }
};

const getProductSearch = async (query) => {
    if (query) {
        try {
            const response = await axios.get(route('product.multiselect.all.search', { query }));
            productsSearch.value = response.data;

        } catch (error) {
            productsSearch.value = [];
        }
    } else {
        getLimitedProducts();
    }
};

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    const { message } = error.response.data;

    error_text.value = error.response.data;

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

const toolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline'], // toggled buttons

    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ script: 'sub' }, { script: 'super' }], // superscript/subscript
];

const emailToolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline'], // toggled buttons
];

function clearFilters() {
    cart.value = {};
    getLimitedProducts();
}

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

        select_tax.value = response.data;




    } catch (error) {
        convertValidationNotification(error);
    }
}


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


const historyPrint = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const print = await axios.get(route('quotation.print', page_props.quotation_id), { responseType: "blob" });
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

const deleteQuotation = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("quotation.delete.confirm", page_props.quotation_id))).data;
        quotation.value = response;

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


        const response = (await axios.delete(route("quotation.delete", quotation.value.id))).data;
        window.location.href = route("quotation.all.view");
        successMessage('Quotation deleted successfully!');

        $('#deleteModal').modal('hide');
        getQuotation();

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

const restoreInvoice = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("quotation.restore", page_props.quotation_id))).data;
        window.location.href = route("quotation.all.view");
        successMessage('Quotation restored successfully');

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

const convertToInvoice = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("quotation.convert", page_props.quotation_id))).data;
        successMessage('Successfully converted to an invoice.');
        window.location.href = route('invoice.load', response);

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

const getLimitedProducts = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const productData = (await axios.get(route("limited.products"))).data;
        productsSearch.value = productData;
        selectedId.value = null;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const selectItem = (id) => {
    selectedId.value = id;
};

const getProductsFromCategory = async (categoryId) => {
    try {
        const productData = (await axios.get(route('products.category.all', categoryId))).data;
        products.value = productData;
    } catch (error) {

    }
};

const onSearchByNameBarcode = debounce(async () => {
    try {
        if (cart.value.name.length < 1) {
            getLimitedProducts();
        } else {
            loading.value = true;
            searchByName(cart.value.name, selectedId.value);
        }
    } catch (error) {
    }
}, 250);

const searchByName = async (name, product_category_id) => {
    try {
        if (product_category_id === null) {
            product_category_id = 0;
        }

        const filter_letter = {
            name: name,
            product_category_id: product_category_id,
        };

        const res = await axios.post(route('product.name.all'), filter_letter);
        products.value = res.data;
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
        const productData = (await axios.get(route('cart.select.product', product_id))).data;
        getOderProduct();
        calculateTotals();
        // scrollToTarget();
        scrollToBottom();
        cart.value.name = "";
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const addToQuotation = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (product_id.value == null) {
            changeProductBorder.value = 1;
        } else {
            changeProductBorder.value = 0;
        }
        if (selected_product.value.quantity == null || selected_product.value.quantity <= 0) {
            changeQuantityBorder.value = 1;
        } else {
            changeQuantityBorder.value = 0;
        }

        if (selected_product.value.formatted_selling_price != null || selected_product.value.formatted_selling_price != '') {
            selected_product.value.selling_price = parseFloat(selected_product.value.formatted_selling_price.replace(/,/g, ''));
        }

        selected_product.value.product_id = product_id.value;
        selected_product.value.quotation_id = page_props.quotation_id;

        const productData = (await axios.post(route('quotation.item.add'), selected_product.value)).data;
        select_product.value = null;
        if (select_product.value == null) {
            product_id.value = null;
        }
        selected_product.value = {};
        new_total.value = 0;

        changeProductBorder.value = 0;
        changeQuantityBorder.value = 0;

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

const storeRef = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        if (ref_no.value.length > 120) {
            getQuotation();
            errorMessage("Project / Ref must be less than 120 characters");
        } else {
            const ref_data = {
                ref: ref_no.value,
            };
            await axios.post(route('edit.quotation.ref', page_props.quotation_id), ref_data).data;
        }
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

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
        const quotationProductData = (await axios.get(route('quotation.get.related.products', page_props.quotation_id))).data;
        orderProducts.value = quotationProductData;
        if (orderProducts.value.length == 0 && Number(discount.value) > 0.00) {
            removeDiscount();
        }
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }
};

const getQuotation = async () => {
    try {
        const quotationData = (await axios.get(route('quotation.related.get', page_props.quotation_id))).data;
        quotation.value = quotationData;
        ref_no.value = quotation.value.ref_no;
        ;
        if (quotation.value.note) {
            note.value = stripHtmlTags(quotation.value.note);
        }

        if (quotation.value.customer_id != null) {
            selected_customer.value = quotation.value.customer_id;
            loyalty_customer.value.customer_name = quotation.value.customer_name;
            loyalty_customer.value.customer_mobile = quotation.value.customer_mobile;
            loyalty_customer.value.customer_email = quotation.value.customer_email;
            loyalty_customer.value.customer_address = quotation.value.customer_address;
        } else {
            const walkingCustomer = { id: 0, name: "Walking Customer" };
            select_customer.value = walkingCustomer;
        }



        if (quotation.value.currency_id != null) {
            select_currency.value = quotation.value.currency;
        }
    } catch (error) {

    }
};

const getCustomer = async () => {
    try {
        const tableData = (await axios.get(route('customer.multiselect.all'))).data;
        customers.value = tableData;

        // Adding a walking customer
        const newCustomer = { id: 0, name: "Walking Customer" };
        customers.value.push(newCustomer);
    } catch (error) {

    }
};

const calculateTotals = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const order = (await axios.get(route('quotation.calculate.totals', page_props.quotation_id))).data;
        const formattedSubTotal = Number(order.sub_total).toFixed(2);
        const formattedTotalTax = Number(order.total_tax).toFixed(2);
        const formattedTotal = Number(order.total).toFixed(2);
        const formattedDiscount = Number(order.discount).toFixed(2);
        const formattedCustomerPaid = Number(order.customer_paid).toFixed(2);
        selectCustomer.value = order.customer_id;
        selectCustomerName.value = order.customer_name;
        orderId.value = order.id;
        setDiscountType.value = order.discount_type;
        subTotal.value = formattedSubTotal;
        totalTax.value = formattedTotalTax;
        total.value = formattedTotal;
        discount.value = formattedDiscount;
        customer_paid.value = formattedCustomerPaid;

        if (Number(order.sub_total) < Number(order.discount)) {
            removeDiscount();
        }

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }

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
    } catch (error) {

    }
};

const decreaseQty = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    (await axios.get(route('cart.decrease.product', product_id))).data;
    getOderProduct();
    calculateTotals();
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const increaseQty = async (product_id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    (await axios.get(route('cart.increase.product', product_id))).data;
    getOderProduct();
    calculateTotals();
    nextTick(() => {
        loading_bar.value.finish();
    });
};

const editQty = async (order_item) => {

    try {
        const response = (await axios.get(route("product.get", order_item.product_id))).data;
        edit_product.value = response;
        setOrderProductValue(order_item.id);
        $('#qtyUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }

};

const setOrderProductValue = async (order_item_id) => {
    try {
        const details = {
            id: order_item_id,
            quotation_id: page_props.quotation_id,
        }

        const response = await axios.post(route("quotation.related.products"), details);
        item_details.value = response.data;
        edit_select_item.value.product_name = item_details.value.product_name;
        edit_select_item.value.description = item_details.value.description;
        edit_select_item.value.unit_price = item_details.value.formatted_unit_price;
        edit_select_item.value.quantity = Math.floor(item_details.value.quantity);
        $('#qtyUpdateModal').modal('show');
    } catch (error) {
        convertValidationNotification(error);
    }
};

const selectItemUpdate = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (selected_product.value.formatted_selling_price) {
            selected_product.value.selling_price = parseFloat(selected_product.value.formatted_selling_price.replace(/,/g, ''));
        }

        const response = await axios.post(route("quotation.product.set"), selected_product.value);
        new_total.value = response.data;

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

const selectQuotationItemUpdate = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (edit_select_item.value.unit_price) {
            const unitPriceString = edit_select_item.value.unit_price.toString();
            edit_select_item.value.unit_price = parseFloat(unitPriceString.replace(/,/g, ""));
        }
        edit_select_item.value.quotation_id = page_props.quotation_id;

        if (edit_select_item.value.quantity <= 0) {
            errorMessage('The quantity filed must be at least 1');
            edit_select_item.value.unit_price = item_details.value.formatted_unit_price;
            edit_select_item.value.quantity = Math.floor(item_details.value.quantity);
        } else {
            const response = await axios.post(route("quotation.product.update", item_details.value.id), edit_select_item.value);

            if (response.data == 'Product not found') {
                errorMessage('Product not found');
            } else {
                successMessage('Updated successfully');
            }

            edit_select_item.value = {};

            $('#qtyUpdateModal').modal('hide');

            getOderProduct();
            calculateTotals();
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

const removeItem = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        const quotationData_data = {
            id: item_details.value.id,
            quotation_id: page_props.quotation_id,
        };

        (await axios.post(route('quotation.remove.item'), quotationData_data)).data;
        successMessage('Item removed');

        scrollToBottom();
        $('#qtyUpdateModal').modal('hide');

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

const unitPriceUpdate = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (unit_price.value) {
            unit_price.value = parseFloat(unit_price.value.replace(/,/g, ''));
        }

        await axios.post(route('cart.update.unit.price'), {
            product_id: edit_product.value.id,
            unit_price: unit_price.value
        });
        successMessage('Selling price changed');

        getOderProduct();
        calculateTotals();

        unit_price.value = "";

        $('#qtyUpdateModal').modal('hide');

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

    customerData.value = {};
    $("#quickCustomerModal").modal("show");

};

const saveNewCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const response = await axios.post(route("customer.all.store"), customerData.value);
        if (response.data == "This contact number already registered") {
            errorMessage('This contact number already registered');
        } else {
            successMessage('New customer registration is successful');
            closeCustomerModal();
            getCustomer();
            select_customer.value = response.data;
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

const closeCustomerModal = async () => {
    $("#quickCustomerModal").modal("hide");
};

const showDiscountModal = async () => {

    amount.value = '';
    percentage.value = '';
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

const closeDiscountModal = async () => {
    $("#discountModal").modal("hide");
};

const showTaxModal = async () => {
    $("#taxModal").modal("show");
};

const closeTaxModal = async () => {
    $("#taxModal").modal("hide");
};

const setQuotationCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (select_search_customer.value != null) {
            customer_id.value = select_search_customer.value.id;
        }
        if (select_customer.value != null) {
            customer_id.value = select_customer.value.id;
        }


        const details = {
            customer_id: customer_id.value,
            quotation_id: page_props.quotation_id,
        }
        const tableData = await axios.post(route('quotation.customer.edit'), details).data;
        getQuotation();
        calculateTotals();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }
};

const editQuotationCustomerDetails = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios.post(route('edit.quotation.customer', page_props.quotation_id), quotation.value);
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }

};

const setQuotationCurrency = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (select_currency.value != null) {
            currency_id.value = select_currency.value.id;
        }


        const details = {
            currency_id: currency_id.value,
            quotation_id: page_props.quotation_id,
        }
        const tableData = (
            await axios.post(
                route('quotation.currency.edit'), details
            )
        ).data;
        calculateTotals();
        getQuotation();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }
};

const getLoyaltyCustomer = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        const tableData = await axios.get(route('edit.quotation.loyalty.customers', page_props.quotation_id));
        loyalty_customer.value = tableData.data;
        if (loyalty_customer.value) {
            select_customer.value = loyalty_customer.value;
            select_customer.value.name = truncateText(select_customer.value.name);
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

watch(select_customer, (newValue) => {
    if (newValue.id != 0) {
        setQuotationCustomer();
        loyalty_customer.value = select_customer.value;
    }
});

const getProductDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (select_product.value != null) {
            product_id.value = select_product.value.id;
        }


        const tableData = (
            await axios.get(
                route('product.get', product_id.value)
            )
        ).data;
        selected_product.value = tableData;

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });

    }
};

const saveCustomer = async (customer) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        if (customer) {
            const customer_all_details = (await axios.post(route('cart.update'), customer)).data;
            selectCustomer.value = customer_all_details.customer_id;
            selectCustomerName.value = customer_all_details.customer_name;
            closeCustomerModal();
            successMessage('Customer selected successfully');
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
        await axios.get(route('quotation.customer.remove', quotation.value.id)).then((response) => {
            select_search_customer.value = {};
            loyalty_customer.value = {};
            select_customer.value = null;
            // calculateTotals();
            successMessage('Successfully removed the customer');

            loyalty_customer.value = {};
            const walkingCustomer = { id: 0, name: "Walking Customer" };
            select_customer.value = walkingCustomer;
        });
        getQuotation();

        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {

    }

};

const onTextNoteChange = async () => {
    setQuotationNote();
};

const setQuotationNote = async () => {
    try {

        const requestData = {
            note: note.value
        };
        axios
            .post(
                route("quotation.note.edit", page_props.quotation_id),
                requestData
            )
            .then((response) => { });

    } catch (error) { }
};

const addDiscount = async (newDiscount) => {
    nextTick(() => {
        loading_bar.value.start();
    });


    if (newDiscount == 0) {
        errorMessage("Please enter discount amount");
    } else {
        if (amount.value < 0) {
            amount.value = '';
            errorMessage('The discount amount should be more than 0');
        } else if (amount.value > subTotal.value) {
            amount.value = '';
            errorMessage('The discount amount should be less than the subtotal');
        } else if (percentage.value > 100) {
            percentage.value = '';
            errorMessage('Discount percentage should be less than 100%');
        } else if (percentage.value < 0) {
            percentage.value = '';
            errorMessage('The discount percentage should be more than 0%');
        } else {
            const discount_data = {
                discountType: tabIndex.value,
                discountAmount: newDiscount,
                orderId: orderId.value,
            };
            await axios.post(route('quotation.discount'), discount_data);
            viewPercentage.value = percentage.value;
            calculateTotals();
            closeDiscountModal();
            successMessage('Discount added successfully');
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
        await axios.get(route('remove.quotation.discount', orderId.value));
        calculateTotals();
        tabIndex.value = 1;
        viewPercentage.value = 0;
        successMessage('Discount removed successfully');
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
            errorMessage('Please select the customer for credit invoice');
        } else if (paidAmount.value == null || paidAmount.value == '') {
            const paidAmountInput = document.getElementById("paidAmount");
            paidAmountInput.style.borderColor = 'red';
            errorMessage('Please add the amount paid');
        } else if (changeAmount.value < 0 && selectCustomer.value != null) {
            const paidAmountInput = document.getElementById("paidAmount");
            paidAmountInput.style.borderColor = '#edeff4';
            $('#creditConfirmModal').modal('show');
        } else {

            if (select_currency.value != null) {
                select_currency_id.value = select_currency.value.id;
            }

            const data = {
                order_id: orderId.value,
                customer_id: selectCustomer.value,
                order_total: total.value,
                paid_amount: paidAmount.value,
                balance: changeAmount.value,
                date: from_date.value,
                due_date: due_date.value,
                currency_id: select_currency_id.value,
            }

            await axios.post(route('order.done'), data).then(async (response) => {
                const print = await axios.get(route('payment.print', orderId.value), { responseType: "blob" });
                const url = window.URL.createObjectURL(print.data);
                window.open(url, "_blank");

                successMessage('Order is successful');

                window.location.href = route("quotation.index");
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
        }

        await axios.post(route('order.done'), data);

        $('#creditConfirmModal').modal('hide');

        const print = await axios.get(route('payment.print', orderId.value), { responseType: "blob" });
        const url = window.URL.createObjectURL(print.data);
        window.open(url, "_blank");

        successMessage('Order is successful');

        window.location.href = route("quotation.index");

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
    }
};

watch(() => cart.value.name, () => {
    onSearchByNameBarcode();
});

watch(select_currency, (newValue) => {
    if (select_currency.value.id != quotation.value.currency_id) {
        setQuotationCurrency();
    }
});

watch(() => select_product.value, () => {
    // if (select_product.value.id) {
    new_total.value = 0;
    changeProductBorder.value = 0
    getProductDetails();
    // }
});

watch([amount, subTotal], () => {
    const formattedNewDiscount = Number(amount.value).toFixed(2);
    newDiscount.value = formattedNewDiscount;
    const formattedNewTotal = Number(subTotal.value - amount.value).toFixed(2);
    newTotal.value = formattedNewTotal;
});
watch([percentage, subTotal], () => {
    const formattedNewDiscount = Number(subTotal.value * (percentage.value / 100)).toFixed(2);
    newDiscount.value = formattedNewDiscount;
    const formattedNewTotal = Number(subTotal.value - newDiscount.value).toFixed(2);
    newTotal.value = formattedNewTotal;
});

const expireMessage = async () => {
    try {
        showExpireModal.value = true;
        const modal = new bootstrap.Modal(document.getElementById('active_modal'));
        modal.show();
    } catch (error) {

    }
}

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

const getBusinessDetails = async () => {
    try {
        const response = (await axios.get(route("configuration.get"))).data;
        business_details.value = response.data;

        if (business_details.value.currency_id) {
            currency.value = business_details.value.currency_name;
        }

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
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

function clearFields() {
    payment.value = {};
}

const savePayment = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (payment_date.value) {
            payment.value.date = payment_date.value;
        }
        if (select_customer.value != null) {
            const response = (await axios.post(route("payment.credit.bill", orderId.value), payment.value)).data;
            successMessage('Payment successfully!');

            $('#paymentModal').modal('hide');
            calculateTotals();
        } else {
            errorMessage('Please select a customer');
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

const updatePayment = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {

        if (edit_payment_date.value) {
            billData.value.date = edit_payment_date.value;
        }
        const response = (await axios.post(route("payment.bill.update"), billData.value)).data;
        successMessage('Bill updated successfully!');

        $('#editBillModal').modal('hide');
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

            if (select_unit.value != null) {
                product.value.unit = select_unit.value.id;
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
                getSavedProduct();

                product.value = {};
                product_image.value = null;
                select_category.value = [];
                select_unit.value = [];

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

const openImageFile = async () => {

    const fileInput = document.getElementById('product_image');
    fileInput.click();
};

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

const getSavedProduct = async () => {
    try {
        getLimitedProducts();
        const response = await axios.get(route('products.saved.get'));
        const latestProduct = response.data;
        const selectedProduct = {
            id: latestProduct.id,
            searchableText: `${latestProduct.code} : ${truncateText(latestProduct.name)}`,
            name: latestProduct.name
        };
        select_product.value = selectedProduct;
    } catch (error) {
        errorMessages(error);
    }
};

function openNewModal() {

    nextTick(() => {
        loading_bar.value.start();
    });
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
    product_tab_index.value = 1;
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

const truncateText = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
    }
    return '';
};

const truncateDescription = (text) => {
    if (text && typeof text === 'string') {
        return text.length > 55 ? text.substring(0, 55) + '...' : text;
    }
    return '';
};

const getFooterCustomField = async (id) => {

    try {
        const parameterFieldData = await axios.get(route('get.quotation.parameter.footer.field', id));
        quotation_footer_parameter_field.value = parameterFieldData.data;
        parameter_footer_field.value = quotation_footer_parameter_field.value.name;
        resetValidationErrors();
        $('#editFooterParameterModal').modal('show');
    } catch (error) {

    }

};

const stripHtmlTags = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || "";
};

const getQuotationFooterParameters = async () => {

    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        const footerParameterData = await axios.get(route('quotation.parameters.footer.get', page_props.quotation_id));
        quotation_footer_parameters.value = footerParameterData.data;
        await nextTick();
        quotation_footer_parameters.value.forEach((parameter, index) => {
            if (parameter.description) {
                const editorContainer = document.querySelector(`#quillEditor_${index} .ql-editor`);
                editorContainer.innerHTML = parameter.description;
            }
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

nextTick(getQuotationFooterParameters);

const openNewFooterParameterModel = async () => {

    try {
        parameter_footer_title.value = {};
        resetValidationErrors();
        $('#newFooterParameterModal').modal('show');
    } catch (error) {

    }

};

const saveFooterParameter = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        if (parameter_footer_title.value.title != null) {
            parameter_footer_title.value.title.toUpperCase();
        } else {
            parameter_footer_title.value.title = null;
        }

        await axios.post(route('store.quotation.footer.parameter', page_props.quotation_id), parameter_footer_title.value);
        $('#newFooterParameterModal').modal('hide');
        successMessage('Custom field created successfully!');
        getQuotationFooterParameters();


        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);;
    }
};

const updateFooterParameter = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const editFooterParameterData = {
            id: id,
            title: parameter_footer_field.value,
        };

        await axios.post(route('update.quotation.footer.parameter', page_props.quotation_id), editFooterParameterData);
        $('#editFooterParameterModal').modal('hide');
        successMessage('Custom field updated successfully!');
        getQuotationFooterParameters();


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

const deleteFooterParameter = async () => {
    try {

        $('#editFooterParameterModal').modal('hide');
        $('#deleteFooterParameterModal').modal('show');


    } catch (error) {
    }
};

const confirmDeleteFooterParameter = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        await axios.delete(route("delete.quotation.footer.parameter", quotation_footer_parameter_field.value.id));

        successMessage('Custom field deleted successfully!');
        getQuotationFooterParameters();

        $('#deleteFooterParameterModal').modal('hide');
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

const updateDescription = (value, parameter) => {

    parameter.description = value;

};

const onFooterDescriptionChange = (id, description) => {
    saveFooterParameterDescription(id, description);
};

const saveFooterParameterDescription = async (id, description) => {
    resetValidationErrors();
    try {

        const footerParameterData = {
            id: id,
            description: description,
        };

        await axios.post(route('set.quotation.parameter.footer.description'), footerParameterData);


    } catch (error) {
        convertValidationNotification(error);
    }
};

// Header Custom Fields
const openNewParameterModel = async () => {

    try {
        resetValidationErrors();
        parameter_title.value = {};
        $('#newParameterModal').modal('show');
    } catch (error) {

    }

};

const getCustomField = async (id) => {

    try {
        const fieldData = await axios.get(route('get.quotation.parameter.field', id));
        quotation_parameter_field.value = fieldData.data;
        parameter_field.value = quotation_parameter_field.value.name;
        resetValidationErrors();
        $('#editParameterModal').modal('show');
    } catch (error) {

    }

};

const saveParameter = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        await axios.post(route('store.quotation.parameter', page_props.quotation_id), parameter_title.value);
        $('#newParameterModal').modal('hide');
        successMessage('Custom field created successfully!');
        getQuotationParameters();


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

const updateParameter = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    resetValidationErrors();
    try {


        const editParameterData = {
            id: id,
            title: parameter_field.value,
        };
        await axios.post(route('update.quotation.parameter', page_props.quotation_id), editParameterData);
        $('#editParameterModal').modal('hide');
        successMessage('Custom field updated successfully!');
        getQuotationParameters();


        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);;
    }
};

const deleteParameter = async () => {
    try {

        $('#editParameterModal').modal('hide');
        $('#deleteParameterModal').modal('show');


    } catch (error) {
    }
};

const confirmDeleteParameter = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {


        await axios.delete(route("delete.quotation.parameter", quotation_parameter_field.value.id));

        successMessage('Custom field deleted successfully!');
        getQuotationParameters();

        $('#deleteParameterModal').modal('hide');
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

const saveParameterDescription = async (id, quotation_parameter) => {
    resetValidationErrors();
    try {

        if (quotation_parameter.description.length > 120) {
            getQuotationParameters();
            errorMessage("Description must be less than 120 characters");
        } else {
            const parameterData = {
                id: id,
                description: quotation_parameter.description,
            };
            await axios.post(route('set.quotation.parameter.description'), parameterData);
        }
        // getQuotationParameters();


    } catch (error) {
        convertValidationNotification(error);;
    }
};

const getQuotationParameters = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });

    try {
        const parameterData = await axios.get(
            route("quotation.parameters.get", page_props.quotation_id)
        );
        quotation_parameters.value = parameterData.data;
        nextTick(() => {
            loading_bar.value.finish();
        });
    } catch (error) { }
};

// Show Add/Edit Customer Email Modal
const showCustomerEmailModal = async () => {

    resetValidationErrors();
    if (quotation.value.customer_id == null) {
        setWalkingCustomerEmailDetails();
    } else {
        setCustomerEmailDetails();
    }
    $("#send_mail").modal("show");

};

const setCustomerEmailDetails = async () => {
    try {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric', };
        const formattedDate = today.toLocaleDateString('en-US', options).replace(',', '').split(' ').join('-');

        const configuration_details = (await axios.get(route("configuration.get"))).data.data;
        const customer = (await axios.get(route('customer.get', quotation.value.customer_id))).data;
        selectedCustomerData.value = customer;
        customer_email.value.to = selectedCustomerData.value.email;
        customer_email.value.cc = configuration_details.email;
        customer_email.value.subject = ref_no.value ? ref_no.value + ' Quotation Number: ' + quotation.value.code : 'Quotation Number: ' + quotation.value.code;

        customer_email.value.message =
            "<p>Hi " + selectedCustomerData.value.name +
            ",</p><p>I hope you’re doing well! Please see attached quotation number " + quotation.value.code +
            ".</p><p>Don’t hesitate to reach out if you have any questions.</p><p>Kind regards!</p>";

        const editorContainer = document.querySelector('#email-message .ql-editor');
        editorContainer.innerHTML = customer_email.value.message;
    } catch (error) {
        errorMessage('failed to set customer data');
    }
};

const setWalkingCustomerEmailDetails = async () => {
    try {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric', };
        const formattedDate = today.toLocaleDateString('en-US', options).replace(',', '').split(' ').join('-');

        const configuration_details = (await axios.get(route("configuration.get"))).data.data;
        customer_email.value.to = '';
        customer_email.value.cc = configuration_details.email;
        customer_email.value.subject = ref_no.value ? ref_no.value + ' Quotation Number: ' + quotation.value.code : 'Quotation Number: ' + quotation.value.code;

        customer_email.value.message =
            "<p>Hi Customer" +
            ",</p><p>I hope you’re doing well! Please see attached quotation number " + quotation.value.code +
            ".</p><p>Don’t hesitate to reach out if you have any questions.</p><p>Kind regards!</p>";

        const editorContainer = document.querySelector('#email-message .ql-editor');
        editorContainer.innerHTML = customer_email.value.message;
    } catch (error) {
        errorMessage('failed to set customer data');
    }
};

const sendQuotationEmail = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        resetValidationErrors();

        const editorContainer = document.querySelector('#email-message .ql-editor');
        customer_email.value.message = editorContainer.innerHTML;
        const response = await axios.post(route("customer.quotation.mail.send", { customer_id: selectedCustomerData.value.id, quotation_id: quotation.value.id }), customer_email.value);
        if (response.data.message == "Please enter business email") {
            errorMessage(response.data.message);
        } else {
            successMessage(response.data.message);
        }
        closeCustomerEmailModal();
        reload();

        nextTick(() => {
            loading_bar.value.finish();
        });

    } catch (error) {
        nextTick(() => {
            loading_bar.value.finish();
        });
        convertValidationError(error);
        errorMessage(error.response.data.message);
    }
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

const getTaxes = async () => {
    try {
        const response = (await axios.get(route('tax.list'))).data;
        taxes.value = response.data;
    } catch (error) {
        convertValidationError(error);
    }
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
        select_category.value = response.data;



    } catch (error) {
        convertValidationNotification(error);
    }
};

const closeCustomerEmailModal = () => {
    resetValidationErrors();
    $("#send_mail").modal("hide");
};


onMounted(() => {
    scrollContainer.value = document.querySelector('.select_product_scroller');
    getCategories();
    getUnits();
    truncateText();
    getLimitedProducts();
    getOderProduct();
    calculateTotals();
    getCurrency();
    getBusinessDetails();
    getLoyaltyCustomer();
    getCustomer();
    getQuotation();
    getQuotationParameters();
    getQuotationFooterParameters();
    getTaxes();
});

</script>

<style lang="css" scoped>
/* .quo-padding {
    padding-left: 0px;
} */

.cart-search:focus {
    box-shadow: none;
    outline: none;
}

.error-border {
    border-radius: 5px;
    border: 1px solid rgb(255, 158, 158);
}

.plus-btn {
    color: #1464a4;
}

.add-new-text-format {
    color: #015896;
}

.multiselect-add-new-button {
    width: 100%;
    border-radius: 0%;
}

.custom-text-area {
    resize: none;
    font-size: 12px;
}

.custom-title {
    color: #071437;
}

.label-container {
    display: flex;
    align-items: center;
}

.form-label {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cancel-btn {
    background-color: rgba(128, 128, 128, 0.192);
    color: black;
}

.note-field-style {
    min-height: 300px;
}
</style>
