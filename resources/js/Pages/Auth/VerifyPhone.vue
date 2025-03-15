<script setup>
import { computed, ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    code: '',
});
const submit = () => {
    form.post(route('verification.verify-mobile'));
};
const sendsmsagain = () => {
    form.post(route('send.sms'));
};
const auth = computed(() => usePage().props.auth);
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>

        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Please enter the OTP sent to your number: {{ auth.user.phone }}
        </div>

        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400" v-if="verificationLinkSent">
            A new verification code has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <label for="code" :value="code" />

                    <input v-model="form.code" id="code" class="block mt-1 w-full" type="text" name="code" required
                        autofocus />
                </div>
                <button @click="submit()"
                    class="text-white bg-slate-700 p-3  focus:outline-none hover:bg-indigo-300 rounded-full ">Verify
                </button>
                
                <Link :href="route('logout')" method="post" as="button"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Log Out</Link>
            </div>
        </form>
        <button @click="sendsmsagain()"
            class="text-white bg-slate-700 p-3  focus:outline-none hover:bg-indigo-300 rounded-full m-3 ">Send SmS
        </button>
        <Link :href="route('user.home')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800  ">
                Return To Home
                </Link>
    </GuestLayout>
</template>
