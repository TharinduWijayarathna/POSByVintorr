<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="bg-gray-100">
        <div class="m-5">
            <slot name="content" />
        </div>

        <slot name="modal" />
    </div>
</template>
