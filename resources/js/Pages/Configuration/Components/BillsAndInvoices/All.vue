<template>
    <div class="row">
        <div class="py-0 col-12 col-md-6 col-lg-7">
            <div class="">
                <div class="text-sm card-body">
                    <div class="row ">
                        <div class="text-gray-600 col-12 col-form-label">Logo on the Bill
                        </div>
                        <div class="mb-5 col-md-12 d-flex">
                            <div class="p-2 image-input image-chooser-border" data-kt-image-input="true">
                                <div class="image-input-wrapper w-200px h-200px"
                                    style="overflow: hidden; position: relative;">
                                    <img v-if="edit_business.bill_logo_url" :src="edit_business.bill_logo_url"
                                        class="mb-2 img-fluid"
                                        style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                    <img v-else src="../../../../../src/logo/logo.webp" class="mb-2 img-fluid"
                                        style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                                </div>
                                <button v-if="!edit_business.bill_logo_url" type="button" class="product-image-toggle"
                                    @click="openEditImageFile">
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
                                        <i class="text-gray-700 bi bi-image-fill"></i> <span
                                            class="text-gray-700 ms-2">Change
                                            Logo</span>
                                    </div>
                                    <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                                        @click.prevent="removeLogo(edit_business.id)">
                                        <i class="bi bi-trash text-danger"></i> <span class="text-danger ms-2">Remove
                                            Logo</span>
                                    </div>

                                </div>

                                <input type="file" ref="fileInput" accept="image/jpg, image/png" id="bill_template_logo"
                                    name="avatar" hidden @change="onFileChangeEdit">
                                <input type="hidden" name="avatar_remove">

                            </div>
                        </div>
                        <div class="mb-5 text-gray-400 col-12" v-if="edit_business.bill_logo_url == null">
                            Image should be less than 5MB
                        </div>
                        <div v-if="edit_business.length <= 0" class="mt-2 col-12">
                            <form @submit.prevent="saveDetails" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                                        <div class="text-gray-600 col-form-label">Business Logo
                                        </div>
                                    </div>
                                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9 d-flex">
                                        <input type="file" ref="fileInput" accept="image/jpg, image/png"
                                            id="business_logo_change" class="form-control form-control-sm"
                                            @change="onFileChange" />
                                        <input type="color" class="p-1 form-control form-control-color form-control-sm"
                                            v-model="color_code" id="exampleColorInput" title="Choose your color">
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
                                        <div class="text-gray-600 col-form-label">Footer Text</div>
                                    </div>
                                    <div class="col-12">
                                        <textarea v-model="edit_business.footer" class="form-control form-control-solid"
                                            :rows="3" placeholder="Footer text" style="resize: none;"></textarea>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-4 mb-3 col-12 text-end">
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

        <div class="pt-6 pb-6 mt-6 col-12 col-md-6 col-lg-5 mt-md-0 d-flex justify-content-center">
            <div class="shadow card h-100 bill-card">
                <div class="py-3 text-sm card-body">
                    <div class="px-3 py-5 row">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="25%">
                                        <img v-if="edit_business.bill_logo_url" :src="edit_business.bill_logo_url"
                                            class="brand-logo" />
                                        <img v-if="!edit_business.bill_logo_url" src="logo/logo.webp"
                                            class="brand-logo" />
                                    </th>
                                    <th width="75%">
                                        <div class="text-center header-title">
                                            Business Name</div>

                                        <div class="text-center header-sub-title" style="font-size: 13px">
                                            Business Address
                                        </div>

                                        <div class="text-center header-sub-title" style="font-size: 13px">
                                            Business email
                                        </div>

                                        <div class="mb-3 text-center header-sub-title" style="font-size: 13px">
                                            Contact Number
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="top-margin">
                            <thead>
                                <tr class="heading-bg-po">
                                    <th width="40%">
                                        <div class="text-left sub-title" style="font-size: 12px">
                                            Invoice
                                            No : #C00000</div>
                                        <div class="text-left sub-title" style="font-size: 12px">
                                            Cashier
                                            : Fernando</div>
                                    </th>
                                    <th>
                                        <div class="text-right sub-title" style="font-size: 12px">
                                            Customer : Walking<br />Customer</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="item-section">
                            <thead>
                                <tr class="row-bg" style="height: 50px;">
                                    <td width="58%" colspan="2" align="center" class="td-style">
                                        <b>Code</b>
                                        <br />
                                        <b>Product Name</b>
                                    </td>
                                    <td width="17%" align="end" class="td-style ">
                                        <b>Qty</b>
                                    </td>
                                    <td width="25%" align="end" class="td-style ">
                                        <b>Rate ({{ edit_business.currency_name }})</b>
                                        <br />
                                        <b>Total</b>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row-bg">
                                    <td width="58%" colspan="2" align="left">
                                        <div class="invoice-item-text item-margin" style="font-size: 13px">
                                            <b>P00000</b>
                                            <div class="dotted-line-hr"></div>
                                            <p style="word-wrap: break-word;"><b>Product 01</b></p>
                                        </div>
                                    </td>
                                    <td width="17%" align="end" class="right-text-qty pe-0">
                                        <div class="invoice-item-text-qty item-margin" style="font-size: 13px">
                                            <b>2</b>
                                        </div>
                                    </td>
                                    <td width="25%" align="left" class="right-text invoice-item-text pe-0">
                                        <div class="invoice-item-text item-margin" style="font-size: 13px">
                                            <b>500.00</b>
                                            <br />
                                            <b>1,000.00</b>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="item-section">
                            <tbody class=" total-text top-margin">
                                <tr class="row-bg">
                                    <td width="60%" class="right-text bold-text"
                                        style="text-align: right; font-size: 13px;">
                                        Sub Total
                                        <br />
                                        Discount
                                    </td>
                                    <td width="40%" class="right-text bold-text pe-0"
                                        style="text-align: right; font-size: 13px;">
                                        1,000.00
                                        <br />
                                        100.00
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="2">
                                        <div class="line-hr"></div>
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td width="60%" class="right-text bold-text"
                                        style="text-align: right; font-size: 13px;">
                                        Total
                                    </td>
                                    <td width="40%" class="right-text bold-text pe-0"
                                        style="text-align: right; font-size: 13px;">
                                        900.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td width="60%" class="right-text bold-text"
                                        style="text-align: right; font-size: 13px;">
                                        Paid Amount
                                    </td>
                                    <td width="40%" class="right-text bold-text pe-0"
                                        style="text-align: right; font-size: 13px;">
                                        1,000.00
                                    </td>
                                </tr>
                                <tr class="row-bg">
                                    <td width="60%" class="right-text bold-text"
                                        style="text-align: right; font-size: 13px;">
                                        Balance
                                    </td>
                                    <td width="40%" class="right-text bold-text pe-0"
                                        style="text-align: right; font-size: 13px;">
                                        100.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="section-table">
                            <tbody>
                                <tr class="row-bg">
                                    <td align="center">
                                        <img :src="barcodeDataURL" height="50" width="180" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="section-footer">
                            <thead>
                                <tr>
                                    <td class="footer-content" style="font-size: 12px">
                                        <b> Date & Time 10/08/2024 02:53:43 AM </b>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="">
                            <thead>
                                <tr>
                                    <td class="terms" style="font-size: 15px">
                                        <b>
                                            <div v-if="edit_business.footer" style="text-align: center;">
                                                <template v-for="(line, index) in splitFooter(edit_business.footer)"
                                                    :key="index">
                                                    <span v-if="index > 0"><br></span>
                                                    <span>{{ line }}</span>
                                                </template>
                                            </div>
                                            <div v-else>
                                                *Thank you for your visit*
                                            </div>
                                        </b>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="billImageCropperModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="billImageCropperModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="billImageCropperModalLabel">Crop Image</h5>
                    <button type="button" class="btn-close" @click="closeCrop"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img ref="cropperImage" :src="cropperImageSrc" alt="Crop Image" class="img-fluid cropper-image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeCrop">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="cropImage">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <Loader ref="loading_bar" />
</template>

<script setup>
import { ref, reactive, onMounted, nextTick, watch, computed } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import bwipjs from 'bwip-js';
import { usePage } from '@inertiajs/vue3';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const { props } = usePage();

const isSwitchOn = ref(false);

const business = ref({});
const web_details_banner = ref(null);
const business_logo_change = ref(null);
const edit_web_details_banner = ref(null);

const code = ref('C00000');
const barcodeDataURL = ref('');
const itemCount = ref(null);

const currencies = ref([]);
const select_currency = ref(null);
const edit_select_currency = ref({});

const color_code = ref('#00bf77');

const loading_bar = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const edit_business = reactive({
    id: null,
    footer: null,
    bill_logo_url: null,
});
const edit_bill_logo_change = ref(null);
const cropperImageSrc = ref('');
const cropper = ref(null);

const onFileChange = (e) => {
    // business.value.image = e.target.files[0];
    business_logo_change.value = e.target.files[0];
};


const openEditImageFile = () => {
    document.getElementById('bill_template_logo').click();
};

const onFileChangeEdit = (e) => {
    const fileInput = document.getElementById('bill_template_logo');
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
            const fileInput = document.getElementById('bill_template_logo');
            fileInput.value = null;
        }
    }
};

const showCropperModal = () => {
    const modal = new bootstrap.Modal(document.getElementById('billImageCropperModal'));
    modal.show();
    nextTick(() => {
        const image = document.querySelector('#billImageCropperModal img');
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
        edit_bill_logo_change.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_business.bill_logo_url = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(document.getElementById('billImageCropperModal'));
    modal.hide();
    const fileInput = document.getElementById('bill_template_logo');
    fileInput.value = null;
};

const closeCrop = () => {
    const fileInput = document.getElementById('bill_template_logo');
    fileInput.value = null;
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(document.getElementById('billImageCropperModal'));
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

    const fileInputs = document.getElementById('bill_template_logo');
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
        edit_business.bill_logo_url = response.bill_logo_url;
        edit_business.footer = response.footer;
        edit_business.currency_name = response.currency_name;
        edit_business.value = response;
        if (edit_business.value.status == 1) {
            isSwitchOn.value = true;
        }
        if (edit_business.value.color_code) {
            color_code.value = edit_business.value.color_code;
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

        await axios.post(route('configuration.store'), business.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        successMessage('Your Business Details Saved successfully!');


        const fileInput = document.getElementById('bill_template_logo');
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

        if (edit_bill_logo_change.value != null) {
            edit_business.value.bill_logo = edit_bill_logo_change.value;
        } else {
            edit_business.value.bill_logo = null;
        }
        edit_business.value.image = null;
        edit_business.value.invoice_logo = null;
        if (edit_select_currency.value != null) {
            edit_business.value.currency_id = edit_select_currency.value.id;
        }

        if (color_code.value) {
            edit_business.value.color_code = color_code.value;
        }
        edit_business.value.footer = edit_business.footer;

        await axios.post(route("configuration.update", edit_business.value.id), edit_business.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        successMessage('Business details updated successfully!');

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

        await axios.get(route("configuration.bill.logo.remove", id)).then((response) => {
            const fileInput = document.getElementById('bill_template_logo');
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
    const canvas = document.createElement('canvas');
    bwipjs.toCanvas(canvas, {
        bcid: 'code128',
        text: code.value,
        scale: 3,
        includetext: false,
        textxalign: 'center',
        textsize: 16
    });

    barcodeDataURL.value = canvas.toDataURL();
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

const getProductCount = async () => {
    try {
        const productCount = await axios.get(route('product.count.get'));
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

        while (endIndex > startIndex && footer[endIndex] !== ' ' && footer[endIndex] !== undefined) {
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
.bill-card {
    max-width: 360px;
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
    font-family: 'Consolas', monospace;
    text-align: right;
    padding-right: 5px;
    font-size: .7rem;
}

.right-text-qty {
    font-family: 'Consolas', monospace;
    text-align: right;
    padding-right: 4px;
    font-size: .5rem;
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

.td-style {
    font-family: 'Consolas', monospace;
    font-size: 12px;
    font-weight: 400;
    line-height: 17px;
    padding-left: 10px;
    padding-bottom: 3px;
    padding-top: 3px;
}

.td-style-head {
    font-family: 'Consolas', monospace;
    font-size: 14px;
    font-weight: 400;
    line-height: 20px;
    padding-left: 10px;
    padding-bottom: 1px;
    padding-top: 1px;
}

.td-style-gt {
    font-family: 'Consolas', monospace;
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

.brand-logo {
    width: 70px;
    padding-bottom: 5px;
    padding-top: 2px;
}

.invoice-head {
    font-family: 'Consolas', monospace !important;
    font-size: 1.5rem;
}

.company-title {
    font-family: 'Consolas', monospace !important;
    font-size: 1rem;
    font-weight: 400;
}

.sub-title {
    font-family: 'Consolas', monospace !important;
    font-size: 0.6rem;
}

.header-title {
    font-family: 'Liberation Mono' !important;
    font-size: 1.3rem;
    font-weight: bold;
}

.header-sub-title {
    font-family: 'Consolas', monospace !important;
    font-size: 0.8rem;
}

.text-left {
    text-align: left;
}

.company-address {
    font-family: 'Consolas', monospace !important;
    font-size: 0.8rem;
}

.company-tel {
    font-family: 'Consolas', monospace !important;
    font-size: 0.6rem;
}

.company-mail {
    font-family: 'Consolas', monospace !important;
    font-size: 0.6rem;
}

.invoice-item-text {
    font-family: 'Consolas', monospace;
    font-size: 0.7rem;
    /* font-weight: 300; */
}

.invoice-item-text-qty {
    font-family: 'Consolas', monospace;
    font-size: 0.7rem;
    /* font-weight: 300; */
}

.total-text {
    font-family: 'Consolas', monospace !important;
    font-size: 0.6rem;
    /* font-weight: 300; */
}


.heading-bg {
    background-color: #e8e8e8c4;
}

.heading-bg-po {
    font-family: 'Consolas', monospace;
    background-color: #ffffff7d;
    color: #2b2b2b;
}

.total-bg {
    background-color: #e8e8e8c4;
    padding-right: 10px;
    font-family: 'Consolas', monospace;
    font-size: 10px;
    font-weight: 400;
    line-height: 20px;
    padding-left: 10px;
    padding-bottom: 5px;
}

.total-txt {
    text-align: left;
    padding-left: 10px;
    font-family: 'Consolas', monospace;
    font-size: 10px;
    font-weight: 400;
    line-height: 20px;
    font-weight: bold;
}


.footer-content {
    font-family: 'Consolas', monospace !important;
    text-align: center;
    font-size: 8px;
}

.section-footer {
    margin-top: 20px;
    margin-bottom: 20px;
}

.section-table {
    margin-bottom: 20px;
    margin-top: 20px;
}

.text {
    text-align: left;
    margin-top: 20px;
    padding-bottom: 20px;
    margin-left: 20px;
}

.item-section {
    margin-top: 5px;
}

.terms {
    font-family: 'Consolas', monospace;
    font-size: 0.6rem;
    text-align: center;
}

</style>
