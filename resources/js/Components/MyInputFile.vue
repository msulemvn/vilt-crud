<script setup lang="ts">
import { ref } from 'vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

defineProps<{ modelValue?: File | null }>();

const emit = defineEmits(['update:modelValue']); 0

const fileInput = ref<HTMLInputElement | null>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    emit('update:modelValue', file);
};
</script>

<template>
    <div class="grid w-full">
        <Label for="file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Photo</Label>
        <Input id="file" type="file" ref="fileInput" @change="handleFileChange"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 !rounded-md shadow-sm mt-1 block w-full text-[1rem] h-[2.625rem] mt-[4px] py-[8px] px-[12px]" />
    </div>
</template>
