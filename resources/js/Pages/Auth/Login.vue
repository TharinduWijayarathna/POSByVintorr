<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <template #content>
            <div class="d-flex align-items-center justify-content-center min-vh-100 overflow-hidden">
                <div class="col-md-5 col-lg-5 col-12 text-center">
                    <div class="card main-card w-100">
                        <div class="text-center inside-section">

                            <Head title="Log In" />

                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                {{ status }}
                            </div>

                            <div class="">
                                <div class="text-center mt-2">
                                    <img src="../../../src/logo/logo.webp" alt="Logo B" class="sparkpos-image">
                                </div>
                                <div class="mt-6">
                                    <p class="text-center text-gray-600 fs-3"> Access your POS account to begin your
                                        shift smoothly.</p>
                                </div>
                            </div>

                            <div class="main-form">
                                <form @submit.prevent="submit">
                                    <div class="mt-10">
                                        <TextInput id="email" type="email"
                                            class="mt-1 block form-control main-input input-content green-border"
                                            v-model="form.email" required autofocus autocomplete="username"
                                            placeholder="Email" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>

                                    <div class="mt-5">
                                        <TextInput id="password" type="password"
                                            class="mt-1 block form-control main-input input-content green-border"
                                            v-model="form.password" required autocomplete="current-password"
                                            placeholder="Password" />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>


                                    <div class="block mt-7">
                                        <label class="flex items-center input-content">
                                            <Checkbox name="remember" v-model:checked="form.remember"
                                                onmouseover="this.style.cursor='pointer';" />
                                            <span class="ml-1 text-gray-600 fs-5">Remember Me</span>
                                        </label>
                                    </div>

                                    <div class="flex block items-center mt-7">
                                        <PrimaryButton class="text-center primary-button"
                                            onmouseover="this.style.backgroundColor='#000000'; this.style.cursor='pointer';"
                                            onmouseout="this.style.backgroundColor='#091f3e';"
                                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                            Login
                                        </PrimaryButton>
                                    </div>
                                </form>

                                <div class="mt-20 cursor-pointer d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#contactModal" id="kt_activities_toggle">
                                    <i class="bi bi-question-circle me-2 text-gray-600 fs-2"></i>
                                    <span class="text-gray-600 fs-5">Looking for assistance?</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 d-none d-md-block text-center">
                    <img src="../../../src/images/system/login.svg" alt="Login Image" width="70%">
                </div>

            </div>
        </template>

        <!-- modals -->
        <template #modal>
            <!-- Contact Modal -->
            <div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <!-- Close Button -->
                            <div class="col-12 mb-4 text-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <!-- Modal Title -->
                            <div class="col-12 text-center mb-3">
                                <span class="fs-2 text-gray-700">Need Help?</span>
                            </div>

                            <!-- Short Information -->
                            <div class="col-12 text-center mb-4">
                                <span class="fs-5 text-gray-700">SparkPOS is built by EmergentSpark. If you need
                                    assistance, we’re here to help!</span>
                            </div>

                            <!-- Contact Buttons -->
                            <div class="col-12 text-center">
                                <button type="button" class="btn contact-btn-whatsapp mb-3"
                                    onclick="window.open('https://wa.me/94702470664', '_blank')">
                                    Contact via WhatsApp
                                </button>
                            </div>

                            <div class="col-12 text-center">
                                <button type="button" class="btn contact-btn-email"
                                    onclick="window.location.href='mailto:support@emergentspark.com?subject=Support Request from SparkPOS User'">
                                    Contact via Email
                                </button>
                            </div>

                            <!-- Closing Sentence -->
                            <div class="col-12 text-center mt-4">
                                <span class="fs-5 text-gray-700">Our team at EmergentSpark is always ready to assist
                                    you!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </GuestLayout>
</template>

<style>
.welcome-text {
    font-size: xx-large;
}

@media (max-width: 767px) {
    .d-none {
        display: none !important;
        /* Hide the specified element on mobile screens */
    }
}

@media (min-width: 768px) {
    .d-flex {
        display: flex;
    }
}

.main-card {
    margin-top: 0rem;
    margin-right: 1rem;
    margin-left: 2rem;
    margin-bottom: 0rem;
    padding-top: 2rem;
    padding-bottom: 2rem;
}

.main-form {
    margin-top: 50px;
    margin-right: 50px;
    margin-left: 50px;
}

.sparkpos-image {
    max-width: 65%;
    height: auto;
}

.primary-button {
    background-color: #091f3e;
    padding: 20px;
    width: 80%;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.inside-section {
    max-width: fit-content;
}

.input-content {
    width: 80%;
    margin: 0 auto;
}

.contact-btn-whatsapp {
    background-color: #091f3e;
    border-color: #091f3e;
    color: white;
}

.contact-btn-email {
    background-color: black;
    border-color: black;
    color: white;
}

.contact-btn-whatsapp:hover {
    background-color: black;
    color: white;
}

.contact-btn-email:hover {
    background-color: #091f3e;
    color: rgb(255, 255, 255);
}

@media (max-width: 767px) {
    .main-card {
        padding: 4rem 0rem;
        margin: 0rem 0rem;
    }

    .main-form {
        margin: 0 1rem;
    }
}
</style>
