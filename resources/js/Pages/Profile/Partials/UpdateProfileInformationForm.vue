<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/MyInputFile.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

import { Avatar, AvatarImage } from "@/Components/ui/avatar";
import { computed, ref } from 'vue';
const fileInput = ref();
const url = ref();

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    picture: user.picture || "/user.svg",
});

const imageSrc = computed(() => user.picture || "/user.svg");
const isLoaded = ref(false);
const handleFileChange = (file) => {
    if (file && file.size > 0) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                url.value = event.target.result;
                user.picture = url.value;
                isLoaded.value = true;
            };
            img.src = event.target.result;
        };

        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's avatar, profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.update'), {
            onSuccess: (response) => {
                user.picture = response.props.auth.user.picture;
                fileInput.clearInput();
            }
        })" class="mt-6 space-y-6">
            <div>
                <Avatar class="h-36 w-36 rounded-sm">
                    <AvatarImage :src="url || imageSrc" alt="avatar"
                        class="object-contain transition-opacity duration-300 ease-in-out"
                        :class="{ 'opacity-0': !isLoaded, 'opacity-100': isLoaded }" @load="isLoaded = true" />
                </Avatar>

                <InputFile type="file" ref="fileInput" class="mt-1 block w-full" v-model="form.picture"
                    @update:model-value="handleFileChange" required autofocus />

                <InputError class="mt-2" :message="form.errors.picture" />
            </div>

            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="props.status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
