<template>
    <div class="row">
        <div class="py-0 col-12 col-md-4 col-xxl-3">
            <div class="">
                <div class="text-sm card-body">
                    <div class="row">
                        <div class="pt-0 text-gray-600 col-12 col-form-label">
                            Logo on the Invoice
                        </div>
                        <div class="mb-5 col-md-12 d-flex">
                            <div class="p-2 image-input image-chooser-border" data-kt-image-input="true">
                                <div class="image-input-wrapper w-200px h-200px"
                                    style="overflow: hidden; position: relative">
                                    <img v-if="edit_business.invoice_logo_url" :src="edit_business.invoice_logo_url"
                                        class="mb-2 img-fluid" style="
                                            max-width: 100%;
                                            max-height: 100%;
                                            position: absolute;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                        " />
                                    <img v-else src="../../../../../src/logo/logo.webp" class="mb-2 img-fluid" style="
                                            max-width: 100%;
                                            max-height: 100%;
                                            position: absolute;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                        " />
                                </div>
                                <button v-if="!edit_business.invoice_logo_url" type="button"
                                    class="product-image-toggle" @click="openEditImageFile">
                                    <i
                                        class="text-white bi bi-pencil-square text-dark-fill fs-3hx logo-edit-pencil"></i>
                                </button>
                                <button v-else type="button" class="product-image-toggle" data-bs-toggle="dropdown"
                                    data-kt-menu-placement="bottom-end">
                                    <i
                                        class="text-white bi bi-pencil-square text-dark-fill fs-3hx logo-edit-pencil"></i>
                                </button>
                                <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                                    data-kt-menu="true" id="edit-toggle-box">
                                    <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                        @click="openEditImageFile">
                                        <i class="text-gray-700 bi bi-image-fill"></i>
                                        <span class="text-gray-700 ms-2">Change Logo</span>
                                    </div>
                                    <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover" @click.prevent="
                                        removeLogo(edit_business.id)
                                        ">
                                        <i class="bi bi-trash text-danger"></i>
                                        <span class="text-danger ms-2">Remove Logo</span>
                                    </div>
                                </div>

                                <input type="file" ref="fileInput" accept="image/jpg, image/png" id="edit_business_logo"
                                    name="avatar" hidden @change="onFileChangeEdit" />
                                <input type="hidden" name="avatar_remove" />
                            </div>
                        </div>
                        <div class="mb-5 text-gray-400 col-12" v-if="edit_business.invoice_logo_url == null">
                            Image should be less than 5MB
                        </div>
                        <div v-if="edit_business.length <= 0" class="mt-2 col-12">
                            <form @submit.prevent="saveDetails" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                                        <div class="text-gray-600 col-form-label">
                                            Business Logo
                                        </div>
                                    </div>
                                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9 d-flex">
                                        <input type="file" ref="fileInput" accept="image/jpg, image/png"
                                            id="business_logo_change" class="form-control form-control-sm"
                                            @change="onFileChange" />
                                        <input type="color" class="p-1 form-control form-control-color form-control-sm"
                                            v-model="color_code" id="exampleColorInput" title="Choose your color" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-4 col-12 text-end">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold">
                                            SAVE
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div v-else class="mt-4 col-12">
                            <form @submit.prevent="updateDetails" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="mt-3 col-12">
                                        <div class="text-gray-600 col-form-label">
                                            Theme Color
                                        </div>
                                    </div>
                                    <div class="col-12mt-3 d-flex">
                                        <input type="color"
                                            class="p-1 form-control form-control-color form-control-sm w-220px"
                                            v-model="color_code" id="exampleColorInput" title="Choose your color" />
                                    </div>

                                    <div class="mt-3 col-12">
                                        <div class="text-gray-600 col-form-label">
                                            Line Item
                                        </div>
                                    </div>
                                    <div class="mt-2 col-12">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" v-model="is_product_code" />
                                            <label class="form-check-label ms-3 fs-5">
                                                Product Code
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-2 col-12">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox"
                                                v-model="is_product_description" />
                                            <label class="form-check-label ms-3 fs-5">
                                                Product Description
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-2 col-12">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" v-model="is_line_number" />
                                            <label class="form-check-label ms-3 fs-5">
                                                Line Number
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-10 mb-3 col-12">
                                        <button type="submit" class="btn btn-info" style="font-weight: bold"
                                            data-toggle="tooltip" data-placement="bottom" title="Save changes">
                                            SAVE
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 pb-6 mt-6 col-12 col-md-8 col-xxl-9 mt-md-0 d-flex justify-content-center">
            <div class="shadow card h-100 invoice-card">
                <div class="py-3 text-sm card-body fixed-width">
                    <div class="py-5 row px-lg-2 px-xxl-3">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="ps-0" width="80%" style="
                                            text-align: left;
                                            vertical-align: text-top;
                                        ">
                                        <div class="company_data">
                                            <div class="text-head" style="
                                                    margin-bottom: 0px;
                                                    padding-bottom: 0px;
                                                ">
                                                <b>Business Name</b>
                                            </div>
                                            <div style="
                                                    margin-bottom: 3px;
                                                    margin-top: 3px;
                                                ">
                                                Business Address
                                            </div>
                                            <div style="
                                                    margin-bottom: 3px;
                                                    margin-top: 3px;
                                                ">
                                                Business email
                                            </div>
                                            <div style="
                                                    margin-bottom: 3px;
                                                    margin-top: 3px;
                                                ">
                                                Contact Number
                                            </div>
                                        </div>
                                    </th>
                                    <th class="logo-area" align="right" style="vertical-align: top">
                                        <img v-if="
                                            edit_business.invoice_logo_url
                                        " :src="edit_business.invoice_logo_url
                                            " alt="POSByVintorr" class="brand-logo me-2" style="width: 100%" />
                                        <img v-if="
                                            !edit_business.invoice_logo_url
                                        " src="logo/logo.webp" alt="POSByVintorr" class="brand-logo me-2"
                                            style="width: 100%" />
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td style="padding-left: 0">
                                        <div class="text-head" style="margin-bottom: 10px" :style="{
                                            color: edit_business.color_code,
                                        }">
                                            <b>INVOICE #INV00000 </b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" class="td-customer-style" style="
                                            vertical-align: text-top;
                                            padding-left: 0;
                                        ">
                                        <div style="
                                                opacity: 0.5;
                                                padding-left: 0;
                                            ">
                                            BILLED TO
                                        </div>
                                        <div style="
                                                margin-bottom: 4px;
                                                margin-top: 4px;
                                            ">
                                            Customer Name
                                        </div>
                                        <div style="
                                                margin-bottom: 4px;
                                                margin-top: 4px;
                                            ">
                                            0700000000
                                        </div>
                                        <div style="
                                                margin-bottom: 4px;
                                                margin-top: 4px;
                                            ">
                                            customer@gmail.com
                                        </div>
                                        <div style="
                                                margin-bottom: 4px;
                                                margin-top: 4px;
                                            ">
                                            Customer Address
                                        </div>
                                    </td>

                                    <td align="right" width="30%" class="td-customer-style customer-section-name">
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            INVOICE NO
                                        </div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            DATE OF ISSUE
                                        </div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            INVOICE DATE
                                        </div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            <b>DUE DATE</b>
                                        </div>
                                    </td>

                                    <td align="right" width="22%" class="td-customer-style" style="
                                            padding-right: 10;
                                            vertical-align: text-top;
                                        ">
                                        <div>INV00000</div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            02/07/2024
                                        </div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            02/07/2024
                                        </div>
                                        <div style="
                                                margin-bottom: 3px;
                                                margin-top: 3px;
                                            ">
                                            31/07/2024
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td align="left" class="td-customer-style nowrap" style="
                                            vertical-align: top;
                                            padding: 0;
                                            width: 14%;
                                        ">
                                        <div style="
                                                padding-left: 0;
                                                word-wrap: break-word;
                                                word-break: break-all;
                                            ">
                                            <div style="
                                                    margin-bottom: 2px;
                                                    margin-top: 6px;
                                                ">
                                                <span style="opacity: 0.5">PROJECT /
                                                    REF:&nbsp;&nbsp;</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="left" class="td-customer-style nowrap" style="
                                            vertical-align: top;
                                            padding: 0;
                                            width: 40%;
                                        ">
                                        <div style="
                                                padding-left: 0;
                                                word-wrap: break-word;
                                                word-break: break-all;
                                                margin-top: 6px;
                                            ">
                                            Ref 1234
                                        </div>
                                    </td>
                                    <td class="td-customer-style customer-section-name"
                                        style="vertical-align: top; width: 38%">
                                        <table>
                                            <tr>
                                                <td align="right" class="td-customer-style customer-section-name">
                                                    <div style="
                                                            margin-top: 0px;
                                                            width: 120px;
                                                            margin-right: -11px;
                                                        ">
                                                        Custom Field 01
                                                    </div>
                                                </td>
                                                <td align="right" style="padding-right: 0px"
                                                    class="td-customer-style customer-section-description">
                                                    <div style="width: 118px">
                                                        xxxx
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="td-customer-style customer-section-name">
                                                    <div style="
                                                            margin-top: 0px;
                                                            width: 120px;
                                                            margin-right: -11px;
                                                        ">
                                                        Custom Field 02
                                                    </div>
                                                </td>
                                                <td align="right" style="padding-right: 0px"
                                                    class="td-customer-style customer-section-description">
                                                    <div style="width: 118px">
                                                        zzzz
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table">
                            <thead class="invoice_table_head" style="">
                                <tr class="row-bg-head" style="line-height: 1; white-space: nowrap" :style="{
                                    color: edit_business.color_code,
                                    backgroundColor:
                                        edit_business.color_code + '20',
                                }">
                                    <th v-if="is_line_number == true" width="5%"
                                        class="text-center table_head_data ms-5" style="
                                            font-size: 11px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                        ">
                                        #
                                    </th>
                                    <th width="45%" class="table_head_data ms-5" style="
                                            font-size: 11px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                            padding-left: 10px;
                                        ">
                                        PRODUCT
                                    </th>
                                    <th width="15%" class="text-right table_head_data" style="
                                            font-size: 11px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                            padding-right: 10px;
                                        ">
                                        PRICE
                                    </th>
                                    <th width="7%" class="text-right table_head_data" style="
                                            font-size: 11px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                            padding-right: 10px;
                                        ">
                                        QTY
                                    </th>
                                    <th width="22%" class="text-right table_head_data" style="
                                            font-size: 11px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                            padding-right: 10px;
                                        ">
                                        LINE TOTAL
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true" align="center" class="td-style">
                                        <p style="margin: 0px">1</p>
                                    </td>
                                    <td align="left" class="td-style">
                                        <p style="margin: 0px">
                                            <span v-if="is_product_code == true" style="margin: 0px">
                                                P00000&nbsp;-
                                            </span>
                                            Product 01
                                        </p>
                                        <p style="margin: 0px; font-size: 8px" v-if="
                                            is_product_description == true
                                        ">
                                            Product Description
                                        </p>
                                    </td>
                                    <td class="td-style right-text">500.00</td>
                                    <td width="10%" class="td-style right-text">
                                        2
                                    </td>
                                    <td width="22%" class="td-style right-text">
                                        {{ edit_business.currency_name }}
                                        1,000.00
                                    </td>
                                </tr>
                                <tr class="row-bg" style="border-top: 2px dotted #eee">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" class="td-style right-text" style="
                                            color: #808080;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        SUB TOTAL
                                    </td>
                                    <td class="td-style right-text" style="
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        1,000.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            border-bottom: 2px dotted #eee;
                                            padding-bottom: 2px;
                                            padding-top: 5px;
                                        ">
                                        DISCOUNT(5%)
                                    </td>
                                    <td class="td-style right-text" style="
                                            border-bottom: 2px dotted #eee;
                                            white-space: nowrap;
                                            padding-bottom: 2px;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        50.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        INVOICE TOTAL
                                    </td>
                                    <td class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            white-space: nowrap;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        950.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        PAID AMOUNT
                                    </td>
                                    <td class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            white-space: nowrap;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        1,000.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        CHANGE
                                    </td>
                                    <td class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            white-space: nowrap;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        50.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        ">
                                        PAID AMOUNT
                                    </td>
                                    <td class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            white-space: nowrap;
                                            padding-top: 5px;
                                        ">
                                        {{ edit_business.currency_name }}
                                        950.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td v-if="is_line_number == true"></td>
                                    <td width="10%" class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                        "></td>
                                    <td colspan="2" align="right" class="td-style left-text" style="
                                            color: #808080;
                                            white-space: nowrap;
                                            padding-bottom: 0px;
                                            padding-top: 5px;
                                            font-weight: bold;
                                        ">
                                        DUE AMOUNT
                                    </td>
                                    <td class="td-style right-text" style="
                                            padding-bottom: 0px;
                                            white-space: nowrap;
                                            padding-top: 5px;
                                            font-weight: bold;
                                        ">
                                        {{ edit_business.currency_name }}
                                        0.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr class="row-bg">
                                    <div class="remark-content" style="
                                            opacity: 0.5;
                                            white-space: nowrap;
                                            font-weight: bold;
                                        ">
                                        NOTE
                                    </div>
                                    <div class="remark-content" style="margin-top: 5px; opacity: 0.5">
                                        Invoice Note
                                    </div>
                                </tr>

                                <tr class="row-bg">
                                    <div class="" style="
                                            opacity: 0.5;
                                            white-space: pre-line;
                                            text-transform: uppercase;
                                            margin-top: 10px;
                                            margin-bottom: 5px;
                                            font-weight: bold;
                                            font-size: 11px;
                                        ">
                                        Bank Details
                                    </div>
                                    <div class="remark-content" style="opacity: 0.5">
                                        ABC Bank
                                    </div>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-10 remark-note">
                            <span style="font-size: 11px">*This is a computer generated invoice. No
                                signature required*</span>
                        </div>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="system-note">
                            <tbody>
                                <tr class="row-bg">
                                    <td>
                                        <span class="note" style="margin-top: 25%">Created at 01/07/2024 | by
                                            <b>POSByVintorr</b>
                                        </span>
                                    </td>
                                    <td align="right">
                                        <img :src="barcodeDataURL" height="40" width="150" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="invoiceImageCropperModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="invoiceImageCropperModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceImageCropperModalLabel">
                        Crop Image
                    </h5>
                    <button type="button" class="btn-close" @click="closeCrop"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img ref="cropperImage" :src="cropperImageSrc" alt="Crop Image"
                            class="img-fluid cropper-image" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeCrop">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" @click="cropImage">
                        Ok
                    </button>
                </div>
            </div>
        </div>
    </div>

    <Loader ref="loading_bar" />
</template>

<script setup>
import { ref, reactive, onMounted, nextTick, watch, computed } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import Loader from "@/Components/Basic/LoadingBar.vue";
import bwipjs from "bwip-js";
import { usePage } from "@inertiajs/vue3";
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

const { props } = usePage();

const isSwitchOn = ref(false);
const importSwitch = ref(false);
const import_switch = ref(0);
const is_product_code = ref(false);
const is_product_description = ref(false);
const is_line_number = ref(false);

const business = ref({});
// const edit_business = ref({
//     footer: 'Your footer text here...'
// });
const web_details_banner = ref(null);
const business_logo_change = ref(null);
const edit_web_details_banner = ref(null);

const code = ref("C00000");
const barcodeDataURL = ref("");
const itemCount = ref(null);

const currencies = ref([]);
const select_currency = ref(null);
const edit_select_currency = ref({});

const color_code = ref("#00bf77");

const loading_bar = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const edit_business = reactive({
    id: null,
    color_code: null,
    invoice_logo_url: null,
    // other properties of edit_business
});
const edit_invoice_logo_change = ref(null);
const cropperImageSrc = ref("");
const cropper = ref(null);

const onFileChange = (e) => {
    // business.value.image = e.target.files[0];
    business_logo_change.value = e.target.files[0];
};

// const openEditImageFile = async () => {
//         const fileInput = document.getElementById('edit_business_logo');
//         fileInput.click();
// };

// const onFileChangeEdit = (e) => {

//     const fileInputs = document.getElementById('edit_business_logo');
//     if (fileInputs.files[0]) {
//         const file = fileInputs.files[0];
//         if (file.size < 5 * 1024 * 1024) {
//             edit_invoice_logo_change.value = e.target.files[0];

//             const file = e.target.files[0];
//             if (file) {
//                 const reader = new FileReader();
//                 reader.onload = (e) => {
//                     edit_business.value.invoice_logo_url = e.target.result;
//                 };
//                 reader.readAsDataURL(file);
//             }
//         } else {
//             errorMessage('Image size should be less than 5MB');
//         }
//     }
// };

const openEditImageFile = () => {
    document.getElementById("edit_business_logo").click();
};

const onFileChangeEdit = (e) => {
    const fileInput = document.getElementById("edit_business_logo");
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
            errorMessage("Image size should be less than 5MB");
            const fileInput = document.getElementById("edit_business_logo");
            fileInput.value = null;
        }
    }
};

const showCropperModal = () => {
    const modal = new bootstrap.Modal(
        document.getElementById("invoiceImageCropperModal")
    );
    modal.show();
    nextTick(() => {
        const image = document.querySelector("#invoiceImageCropperModal img");
        cropper.value = new Cropper(image, {
            aspectRatio: NaN, // Adjust as necessary
            viewMode: 1, // Adjust as necessary
            autoCropArea: 1, // Ensures the whole image is visible in the cropper
            responsive: true,
            background: false,
            cropBoxResizable: true, // Allow resizable crop box
            minContainerWidth: 400, // Minimum width of the container
            minContainerHeight: 400,
            ready() {
                const containerData = cropper.value.getContainerData();
                const cropBoxWidth = 400; // Adjust crop box width as necessary
                const cropBoxHeight = 400; // Adjust crop box height as necessary

                const left = (containerData.width - cropBoxWidth) / 2;
                const top = (containerData.height - cropBoxHeight) / 2;

                cropper.value.setCropBoxData({
                    width: cropBoxWidth,
                    height: cropBoxHeight,
                    left: left,
                    top: top,
                });
            },
        });
    });
};

const cropImage = () => {
    const canvas = cropper.value.getCroppedCanvas({
        width: 200,
        height: 200,
    });
    canvas.toBlob((blob) => {
        const file = new File([blob], "cropped-image.png", {
            type: "image/png",
        });
        edit_invoice_logo_change.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_business.invoice_logo_url = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("invoiceImageCropperModal")
    );
    modal.hide();
    const fileInput = document.getElementById("edit_business_logo");
    fileInput.value = null;
};

const closeCrop = () => {
    const fileInput = document.getElementById("edit_business_logo");
    fileInput.value = null;
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("invoiceImageCropperModal")
    );
    modal.hide();
};

const resetValidationErrors = () => {
    validationErrors.value = {};
    validationMessage.value = null;
};

const convertValidationNotification = (error) => {
    resetValidationErrors();
    if (!(error.response && error.response.data)) return;
    let { message } = error.response.data;

    const fileInputs = document.getElementById("edit_business_logo");
    if (fileInputs.files[0]) {
        const file = fileInputs.files[0];
        if (file.size > 5 * 1024 * 1024) {
            message = "Image size should be less than 5MB";
            errorMessage(message);
        }
    }

    errorMessage(message);
};

const getBusinessDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        const response = (await axios.get(route("configuration.get"))).data.data;
        edit_business.id = response.id;
        edit_business.name = response.name;
        edit_business.address = response.address;
        edit_business.mobile = response.mobile;
        edit_business.email = response.email;
        edit_business.invoice_logo_url = response.invoice_logo_url;
        edit_business.color_code = response.color_code;
        edit_business.currency_name = response.currency_name;
        edit_business.value = response;
        if (edit_business.value.status == 1) {
            isSwitchOn.value = true;
        }
        if (edit_business.value.color_code) {
            color_code.value = edit_business.value.color_code;
        }

        if (edit_business.value.is_product_code == 1) {
            is_product_code.value = true;
        } else {
            is_product_code.value = false;
        }

        if (edit_business.value.is_product_description == 1) {
            is_product_description.value = true;
        } else {
            is_product_description.value = false;
        }

        if (edit_business.value.is_line_number == 1) {
            is_line_number.value = true;
        } else {
            is_line_number.value = false;
        }

        if (edit_business.value.currency_id) {
            edit_select_currency.value.id = edit_business.value.currency_id;
            edit_select_currency.value.code = edit_business.value.currency_name;
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

const saveDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (business_logo_change.value != null) {
            business.value.image = business_logo_change.value;
        } else {
            business.value.image = null;
        }

        if (web_details_banner.value != null) {
            business.value.banner_image = web_details_banner.value;
        } else {
            business.value.banner_image = null;
        }

        if (select_currency.value != null) {
            business.value.currency_id = select_currency.value.id;
        }

        if (color_code.value) {
            business.value.color_code = color_code.value;
        }

        await axios.post(route("configuration.store"), business.value, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        successMessage("Your Business Details Saved successfully!");

        const fileInput = document.getElementById("edit_business_logo");
        fileInput.value = null;

        getBusinessDetails();

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

const updateDetails = async () => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        if (edit_invoice_logo_change.value != null) {
            edit_business.value.invoice_logo = edit_invoice_logo_change.value;
        } else {
            edit_business.value.invoice_logo = null;
        }
        edit_business.value.image = null;
        edit_business.value.bill_logo = null;
        if (edit_web_details_banner.value != null) {
            edit_business.value.banner_image = edit_web_details_banner.value;
        } else {
            edit_business.value.banner_image = null;
        }

        if (edit_select_currency.value != null) {
            edit_business.value.currency_id = edit_select_currency.value.id;
        }

        if (color_code.value) {
            edit_business.value.color_code = color_code.value;
        }

        if (is_product_code.value == true) {
            edit_business.value.is_product_code = 1;
        } else {
            edit_business.value.is_product_code = 0;
        }

        if (is_product_description.value == true) {
            edit_business.value.is_product_description = 1;
        } else {
            edit_business.value.is_product_description = 0;
        }

        if (is_line_number.value == true) {
            edit_business.value.is_line_number = 1;
        } else {
            edit_business.value.is_line_number = 0;
        }

        await axios.post(
            route("configuration.update", edit_business.value.id),
            edit_business.value,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );
        successMessage("Business details updated successfully!");

        getBusinessDetails();

        window.location.href = route("configuration.index");

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

const removeLogo = async (id) => {
    nextTick(() => {
        loading_bar.value.start();
    });
    try {
        await axios
            .get(route("configuration.invoice.logo.remove", id))
            .then((response) => {
                const fileInput = document.getElementById("edit_business_logo");
                fileInput.value = null;

                getBusinessDetails();
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

const generateBarcode = async () => {
    const canvas = document.createElement("canvas");
    bwipjs.toCanvas(canvas, {
        bcid: "code128",
        text: code.value,
        scale: 3,
        includetext: false,
        textxalign: "center",
        textsize: 16,
    });

    barcodeDataURL.value = canvas.toDataURL();
};

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

const getProductCount = async () => {
    try {
        const productCount = await axios.get(route("product.count.get"));
        itemCount.value = productCount.data;
    } catch (error) {
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

const splitFooter = (footer) => {
    const lines = [];
    const maxLength = 35;
    let startIndex = 0;

    while (startIndex < footer.length) {
        let endIndex = startIndex + maxLength;

        while (
            endIndex > startIndex &&
            footer[endIndex] !== " " &&
            footer[endIndex] !== undefined
        ) {
            endIndex--;
        }

        if (endIndex === startIndex) {
            endIndex = startIndex + maxLength;
        }

        lines.push(footer.substring(startIndex, endIndex).trim());
        startIndex = endIndex + 1;
    }

    return lines;
};

onMounted(() => {
    getProductCount();
    getBusinessDetails();
    generateBarcode();
    getCurrency();


});
</script>

<style lang="css" scoped>
.fixed-width {
    min-width: none;
}

.invoice-card {
    max-width: 550px;
    overflow-x: auto;
}

.item-margin {
    margin-bottom: 5px;
}

.top-margin {
    margin-top: 10px;
}

.page_break {
    page-break-before: always;
}

.right-text {
    font-family: "Consolas", monospace;
    text-align: right;
    padding-right: 5px;
    font-size: 0.7rem;
}

.right-text-qty {
    font-family: "Consolas", monospace;
    text-align: right;
    padding-right: 4px;
    font-size: 0.5rem;
}

.bold-text {
    font-weight: bold;
}

.row-style {
    padding-left: 0px;
    padding-right: 0px;
    padding-top: 0px;
    padding-bottom: 0px;
}

.row-bg {
    background-color: #ffffff;
}

.row-bg-subtotal {
    background-color: #e8e8e8c4;
}

.row-bg-head {
    background-color: #dcdcdcb1;
}

.row-white {
    background-color: #ffffff;
}

.td-style-gt {
    font-family: "Consolas", monospace;
    font-size: 14px;
    font-weight: 400;
    line-height: 17px;
    padding-left: 10px;
    padding-bottom: 6px;
    padding-top: 6px;
}

.signature {
    text-align: center;
    line-height: 10px;
}

.signature-section {
    margin-top: 50px;
}

.material-img {
    height: 120px;
    position: fixed;
    right: 150px;
    top: 160px;
    z-index: 999999;
    padding: 5px 0px 5px 0px;
}

.border-mb {
    border-bottom: #000000 solid 1px;
}

.border-mt {
    border-top: #000000 solid 1px;
}

.border-b {
    border-bottom: #000000 solid 1px;
}

.border-t {
    border-top: #000000 solid 1px;
}

.border-l {
    border-left: #000000 solid 1px;
}

.border-r {
    border-right: #000000 solid 1px;
}

.invoice-head {
    font-family: "Consolas", monospace !important;
    font-size: 1.5rem;
}

.company-title {
    font-family: "Consolas", monospace !important;
    font-size: 1rem;
    font-weight: 400;
}

.sub-title {
    font-family: "Consolas", monospace !important;
    font-size: 0.6rem;
}

.header-title {
    font-family: "Liberation Mono" !important;
    font-size: 1.3rem;
    font-weight: bold;
}

.header-sub-title {
    font-family: "Consolas", monospace !important;
    font-size: 0.8rem;
}

.text-left {
    text-align: left;
}

.company-address {
    font-family: "Consolas", monospace !important;
    font-size: 0.8rem;
}

.company-tel {
    font-family: "Consolas", monospace !important;
    font-size: 0.6rem;
}

.company-mail {
    font-family: "Consolas", monospace !important;
    font-size: 0.6rem;
}

.invoice-item-text {
    font-family: "Consolas", monospace;
    font-size: 0.7rem;
    /* font-weight: 300; */
}

.invoice-item-text-qty {
    font-family: "Consolas", monospace;
    font-size: 0.7rem;
    /* font-weight: 300; */
}

.total-text {
    font-family: "Consolas", monospace !important;
    font-size: 0.6rem;
    /* font-weight: 300; */
}

.heading-bg {
    background-color: #e8e8e8c4;
}

.heading-bg-po {
    font-family: "Consolas", monospace;
    background-color: #ffffff7d;
    color: #2b2b2b;
}

.total-bg {
    background-color: #e8e8e8c4;
    padding-right: 10px;
    font-family: "Consolas", monospace;
    font-size: 10px;
    font-weight: 400;
    line-height: 20px;
    padding-left: 10px;
    padding-bottom: 5px;
}

.total-txt {
    text-align: left;
    padding-left: 10px;
    font-family: "Consolas", monospace;
    font-size: 10px;
    font-weight: 400;
    line-height: 20px;
    font-weight: bold;
}

.total-value {
    text-align: right;
    font-family: "Consolas", monospace;
    font-size: 10px;
    font-weight: 400;
    line-height: 20px;
    font-weight: bold;
}

.table-heading {
    font-family: "Consolas", monospace !important;
    padding-left: 15px;
    font-size: 12px;
}

.footer-content {
    font-family: "Consolas", monospace !important;
    text-align: center;
    font-size: 8px;
}

.section-table {
    margin-bottom: 20px;
}

.section-footer {
    margin-top: 20px;
    margin-bottom: 20px;
}

.section-table {
    margin-bottom: 20px;
}

.section-table {
    margin-top: 20px;
}

.note-area {
    border-bottom: #c8c8c8ab solid 1px;
    border-top: #c8c8c8ab solid 1px;
    border-left: #c8c8c8ab solid 1px;
    border-right: #c8c8c8ab solid 1px;
    border-radius: 5px;
    margin-top: 50px;
}

.text {
    text-align: left;
    margin-top: 20px;
    padding-bottom: 20px;
    margin-left: 20px;
}

.text-body {
    font-family: "Consolas", monospace;
    font-size: 15px;
}

.text-tc {
    font-family: "Consolas", monospace !important;
    font-size: 12px;
    line-height: 20px;
}

.vendor-info {
    font-family: "Consolas", monospace !important;
    font-size: 10px;
    line-height: 5px;
}

.item-section {
    margin-top: 5px;
}

.terms {
    font-family: "Consolas", monospace;
    font-size: 0.6rem;
    text-align: center;
}

.invoice-order-item {
    border-bottom: #000000 solid 1px;
}

.line-hr {
    border-bottom: #000000 solid 1px;
}

.dotted-line-hr {
    border-bottom: #000000 dotted 1px;
}

.update-btn {
    background-color: #6e42c163;
    color: #5120addc;
}

.update-btn:hover {
    background-color: #6e42c1;
    color: #ffffff;
}

.custom-business-image-header {
    width: 200px;
    height: 100px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.custom-business-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
}

.default-business-image-header {
    width: 200px;
    height: 100px;
    overflow: hidden;
}

.default-business-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
}

.brand-logo {
    width: 150px;
    padding-bottom: 20px;
    padding-top: 2px;
}

.text {
    text-align: left;
    margin-top: 20px;
    padding-bottom: 20px;
    margin-left: 20px;
}

.text-head {
    font-size: 17px;
}

.td-customer-style {
    font-size: 11px;
    line-height: 1;
    padding-bottom: 3px;
    padding-top: 3px;
}

.customer-section-name {
    opacity: 0.7;
    vertical-align: text-top;
}

.customer-section-description {
    padding-right: 10;
    vertical-align: text-top;
}

.td-style {
    font-size: 11px;
    line-height: 1;
    margin: 10px;
    padding: 10px;
    white-space: normal;
    vertical-align: top;
}

.td-style-head {
    font-size: 11px;
    opacity: 0.5;
}

.remark-note {
    margin-top: 25px;
    text-align: center;
}

.company_data {
    font-size: 0.8rem;
    font-weight: 400;
}

.note {
    margin-right: 150px;
    text-align: left;
    vertical-align: middle;
}

.system-note {
    font-size: 8px;
    width: 100%;
    font-family: Arial, Helvetica, sans-serif;
    margin-right: 50px;
}

.footer-note {
    font-size: 8px;
    width: 100%;
    text-align: left;
    text-align: justify;
}

.system-note {
    bottom: 20px;
}

@media (max-width: 1000px) {
    .fixed-width {
        min-width: 600px;
    }
}
</style>
