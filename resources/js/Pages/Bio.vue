<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import InputFile from "@/Components/InputFile.vue";

const props = defineProps({
    bio: {
        type: Object,
    },
});
</script>

<template>

    <Head title="Bio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ props.bio.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Bio
                </h3>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <Avatar class="h-36 w-36 rounded-sm">
                            <AvatarImage src="/user.svg" alt="@unovue" class="object-none" />
                            <AvatarFallback>
                                CN
                            </AvatarFallback>
                        </Avatar>
                        <InputFile></InputFile>
                        <div>
                            <h3>{{ bio.name }}</h3>
                            <p>Email: {{ bio.email }}</p>
                            <p v-if="bio.email_verified_at">Email Verified</p>
                            <p v-else>Email not verified</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
