<script setup lang="ts">
import { Button } from "@/Components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { ref, watch, computed } from "vue";
import axios from "axios";
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/Components/ui/form';
import { User } from "lucide-vue-next";

const formSchema = toTypedSchema(z.object({
    name: z.string().min(2).max(50),
    email: z.string().email(),
    password: z.string().min(6).optional(),
    password_confirmation: z.string().min(6).optional(),
}));

const props = defineProps({
    user: Object,
    operation: String,
    isDialogOpen: Boolean,
});

const emit = defineEmits(["onCreate", "onEdit", "onDialogClose"]);

const user = ref({ ...props.user });

const form = useForm({
    validationSchema: formSchema,
    initialValues: props.user || {},
});

const isDialogOpen = computed({
    get: () => props.isDialogOpen,
    set: (value) => emit("onDialogClose", value),
});

watch(() => props.user, (newUser) => {
    form.setValues(newUser || {});
});

const onSubmit = form.handleSubmit(async (values) => {
    try {
        const response = await axios({
            method: props.operation === "Create" ? "post" : "put",
            data: values,
            url: `/users${props.operation === "Create" ? "" : "/" + user.value.id}`,
            responseType: "json",
        });

        if (response.status === 201) {
            emit("onCreate", response.data.data);
        } else {
            emit("onEdit", response.data.data);
        }
        isDialogOpen.value = false;
        form.resetForm();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
});
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogContent class="sm:max-w-[425px]">
            <form @submit.prevent="onSubmit">
                <DialogHeader>
                    <DialogTitle>{{ props.operation }}</DialogTitle>
                    <DialogDescription>
                        Make changes to your profile here. Click save when you're done.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4 py-4">
                    <FormField v-slot="{ componentField }" name="name">
                        <FormItem>
                            <FormLabel>Name</FormLabel>
                            <FormControl>
                                <Input type="text" placeholder="Enter your name" v-bind="componentField" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="email">
                        <FormItem>
                            <FormLabel>Email</FormLabel>
                            <FormControl>
                                <Input type="email" placeholder="Enter your email" v-bind="componentField" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-if="operation === 'Create'" v-slot="{ componentField }" name="password">
                        <FormItem>
                            <FormLabel>Password</FormLabel>
                            <FormControl>
                                <Input type="password" placeholder="Enter your password" v-bind="componentField" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-if="operation === 'Create'" v-slot="{ componentField }" name="password_confirmation">
                        <FormItem>
                            <FormLabel>Confirm Password</FormLabel>
                            <FormControl>
                                <Input type="password" placeholder="Confirm your password" v-bind="componentField" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                </div>
                <DialogFooter>
                    <Button type="submit"> Save changes </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
