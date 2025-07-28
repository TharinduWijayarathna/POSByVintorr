<template>
    <div class="row px-md-7 px-xl-10">
        <div class="mt-5 mb-5 col-md-12 d-flex justify-content-center">

            <div class="p-2 image-input image-chooser-border" data-kt-image-input="true">
                <div class="image-input-wrapper w-200px h-200px" style="overflow: hidden; position: relative;"
                    data-toggle="tooltip" data-placement="bottom" title="Change business logo">
                    <img v-if="edit_business.image_url" :src="edit_business.image_url" class="mb-2 img-fluid"
                        style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                    <img v-else src="../../../../../src/logo/logo.webp" class="mb-2 img-fluid"
                        style="max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
                </div>
                <button v-if="!edit_business.image_url" type="button" class="product-image-toggle"
                    @click="openEditImageFile">
                    <i class="text-white bi bi-pencil-square text-dark-fill fs-3hx logo-edit-pencil"></i>
                </button>
                <button v-else type="button" class="product-image-toggle" data-bs-toggle="dropdown"
                    data-kt-menu-placement="bottom-end">
                    <i class="text-white bi bi-pencil-square text-dark-fill fs-3hx logo-edit-pencil"></i>
                </button>
                <div class="py-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-175px"
                    data-kt-menu="true" id="edit-toggle-box">
                    <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover" @click="openEditImageFile">
                        <i class="text-gray-700 bi bi-image-fill"></i> <span class="text-gray-700 ms-2">Change
                            Logo</span>
                    </div>
                    <div class="px-5 py-2 cursor-pointer menu-item menu-item-hover"
                        @click.prevent="removeLogo(edit_business.id)">
                        <i class="bi bi-trash text-danger"></i> <span class="text-danger ms-2">Remove Logo</span>
                    </div>
                </div>
                <input type="file" ref="fileInput" accept="image/jpg, image/png" id="edit_logo_image" name="avatar"
                    hidden @change="onFileChangeEdit">
                <input type="hidden" name="avatar_remove">
            </div>
        </div>
        <div class="mb-5 text-center text-gray-400 col-12" v-if="edit_business.image_url == null">
            Image should be less than 5MB
        </div>
        <div v-if="edit_business.length <= 0" class="col-12">
            <form @submit.prevent="saveDetails" enctype="multipart/form-data">
                <div class="row">
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Business Logo
                        </div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9 d-flex">
                        <input type="file" ref="fileInput" accept="image/jpg, image/png" id="business_logo"
                            class="form-control form-control-sm" @change="onFileChange" />
                        <input type="color" class="p-1 form-control form-control-color form-control-sm"
                            v-model="color_code" id="exampleColorInput" title="Choose your color">
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Business Name
                        </div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.name" type="text" class="form-control form-control-sm"
                            placeholder="Business name" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Address</div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.address" type="text" class="form-control form-control-sm"
                            placeholder="Business address" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Phone Number</div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.mobile" type="text" class="form-control form-control-sm"
                            placeholder="Phone number" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Email</div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.email" type="email" class="form-control form-control-sm"
                            placeholder="Email" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Currency</div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <Multiselect v-model="select_currency" :options="currencies" class="z__index"
                            data-toggle="tooltip" data-placement="bottom" title="Select currency" :showLabels="false"
                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                            placeholder="Select Currency" label="code" track-by="id" max-height="200" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Footer</div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.footer" type="text" class="form-control form-control-sm"
                            placeholder="Footer text" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Account API Key
                        </div>
                    </div>
                    <div class="mt-3 col-12 col-md-7 col-lg-8 col-xl-9">
                        <input v-model="business.account_api_key" type="text" class="form-control form-control-sm"
                            placeholder="Account API Key" />
                    </div>
                </div>
                <div class="row">
                    <div class="mt-4 col-12 text-end">
                        <button type="submit" class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                            data-placement="bottom" title="Save business details">
                            SAVE
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div v-else class="col-12">
            <form @submit.prevent="updateDetails" enctype="multipart/form-data">
                <div class="row">
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Business Name
                        </div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 col-xl-9 mt-md-3">
                        <input v-model="edit_business.name" type="text" class="form-control form-control-sm"
                            placeholder="Business name" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Address</div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 col-xl-9 mt-md-3">
                        <input v-model="edit_business.address" type="text" class="form-control form-control-sm"
                            placeholder="Business address" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Phone Number</div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 col-xl-9 mt-md-3">
                        <input v-model="edit_business.mobile" type="text" class="form-control form-control-sm"
                            placeholder="Phone number" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Email</div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 col-xl-9 mt-md-3">
                        <input v-model="edit_business.email" type="email" class="form-control form-control-sm"
                            placeholder="Email" />
                    </div>
                    <div class="mt-3 col-12 col-md-5 col-lg-4 col-xl-3">
                        <div class="text-gray-600 col-form-label">Currency</div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-8 col-xl-9 mt-md-3">
                        <Multiselect v-model="edit_select_currency" :options="currencies" class="z__index"
                            data-toggle="tooltip" data-placement="bottom" title="Select currency" :showLabels="false"
                            :close-on-select="true" :clear-on-select="false" :searchable="true"
                            placeholder="Select Currency" label="code" track-by="id" max-height="200" />
                    </div>


                </div>
                <div class="row">
                    <div class="mt-4 mb-3 col-12 text-end">
                        <button type="submit" class="btn btn-info" style="font-weight: bold" data-toggle="tooltip"
                            data-placement="bottom" title="Save changes">
                            SAVE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="imageCropperModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="imageCropperModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageCropperModalLabel">Crop Image</h5>
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
import { ref, reactive, onMounted, nextTick } from "vue";
import axios from 'axios';
import Swal from 'sweetalert2';
import Loader from '@/Components/Basic/LoadingBar.vue';
import bwipjs from 'bwip-js';
import Multiselect from 'vue-multiselect';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const isSwitchOn = ref(false);

const business = ref({});
const web_details_banner = ref(null);
const business_logo = ref(null);
const edit_web_details_banner = ref(null);

const currencies = ref([]);
const select_currency = ref(null);
const edit_select_currency = ref({});

const color_code = ref('#00bf77');

const loading_bar = ref(null);

const validationErrors = ref({});
const validationMessage = ref(null);

const edit_business = reactive({
    id: null,
    name: null,
    address: null,
    email: null,
    mobile: null,
    account_api_key: null,
    image_url: null,
    status: null,
    color_code: null,
    currency_id: null,
    currency_name: null,
    // other properties of edit_business
});
const edit_business_logo = ref(null);
const cropperImageSrc = ref('');
const cropper = ref(null);

const onFileChange = (e) => {
    // business.value.image = e.target.files[0];
    business_logo.value = e.target.files[0];
};

const openEditImageFile = () => {
    const fileInput = document.getElementById('edit_logo_image');
    fileInput.click();

};

const onFileChangeEdit = (e) => {
    const fileInput = document.getElementById('edit_logo_image');
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
            const fileInput = document.getElementById('edit_logo_image');
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
        edit_business_logo.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            edit_business.image_url = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(document.getElementById('imageCropperModal'));
    modal.hide();
    const fileInput = document.getElementById('edit_logo_image');
    fileInput.value = null;
};

const closeCrop = () => {
    const fileInput = document.getElementById('edit_logo_image');
    fileInput.value = null;
    cropper.value.destroy();
    const modal = bootstrap.Modal.getInstance(document.getElementById('imageCropperModal'));
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

    const fileInputs = document.getElementById('business_logo');
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
        edit_business.account_api_key = response.account_api_key;
        edit_business.image_url = response.image_url;
        edit_business.status = response.status;
        edit_business.color_code = response.color_code;
        edit_business.currency_id = response.currency_id;
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


        if (business_logo.value != null) {
            business.value.image = business_logo.value;
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


        const fileInput = document.getElementById('business_logo');
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

        if (edit_business_logo.value != null) {
            edit_business.value.image = edit_business_logo.value;
        } else {
            edit_business.value.image = null;
        }
        edit_business.value.bill_logo = null;
        edit_business.value.invoice_logo = null;

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
        edit_business.value.name = edit_business.name;
        edit_business.value.address = edit_business.address;
        edit_business.value.mobile = edit_business.mobile;
        edit_business.value.email = edit_business.email;
        edit_business.value.account_api_key = edit_business.account_api_key;

        await axios.post(route("configuration.update", edit_business.id), edit_business.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        getBusinessDetails();

        nextTick(() => {
            loading_bar.value.finish();
        });
        successMessage('Business details updated successfully!');

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

        await axios.get(route("configuration.logo.remove", id)).then((response) => {
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

onMounted(() => {
    getBusinessDetails();
    getCurrency();
});

</script>

<style lang="css" scoped>

</style>
