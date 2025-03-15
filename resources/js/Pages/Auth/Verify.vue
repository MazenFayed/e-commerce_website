<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
const emailverify = () => {
    form.get(route('verification.notice'));
};
const phoneverify = () => {
    form.get(route('verification.phone'));
};
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>

        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Thanks for signing up! Before getting started, Please Choose A way to verify your account.
        </div>
        <div class="flex justify-around">
            <button @click="emailverify()"
                class="text-white bg-slate-700 p-3  focus:outline-none hover:bg-indigo-300 rounded-full m-3 ">Verify Email
            </button>
            <button @click="phoneverify()"
                class="text-white bg-slate-700 p-3  focus:outline-none hover:bg-indigo-300 rounded-full m-3 ">Verify Phone
            </button>
        </div>
        <Link :href="route('user.home')"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800  ">
        Return To Home
        </Link>

    </GuestLayout>
</template>
