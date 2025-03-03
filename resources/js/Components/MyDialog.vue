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
import { Label } from "@/Components/ui/label";
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const props = defineProps({
    user: {
        type: Object,
        default: {},
        required: false,
    },
    operation: {
        type: String,
    },
    isDialogOpen: {
        type: Boolean,
    },
});

const emit = defineEmits(["onCreate", "onEdit", "onDialogClose"]);

const user = ref({ ...props.user });
const isDialogOpen = ref(props.isDialogOpen);

onMounted(() => { });

watch(isDialogOpen, (current, previous) => {
    if (current != previous) {
        emit("onDialogClose", current);
    }
});

watch(() => props.user, (newUser) => {
    user.value = { ...newUser };
});

const onSubmitForm = () => {
    axios({
        method: props.operation == "Create" ? "post" : "put",
        url: `/users${props.operation == "Create" ? "" : "/" + user.value.id}`,
        data: user.value,
        responseType: "json",
    }).then(function (response) {
        if (response.status === 201) {
            emit("onCreate", response.data.data)
        } else {
            emit("onEdit", response.data.data)
        }
        isDialogOpen.value = false;
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogContent class="sm:max-w-[425px]">
            <form @submit.prevent="onSubmitForm">
                <DialogHeader>
                    <DialogTitle>{{ props.operation }}</DialogTitle>
                    <DialogDescription>
                        Make changes to your profile here. Click save when
                        you're done.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4 py-4">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="name" class="text-right"> Name </Label>
                        <Input id="name" name="name" :default-value="user.name" v-model:model-value="user.name"
                            class="col-span-3" />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="email" class="text-right"> Email </Label>
                        <Input id="email" name="email" :default-value="user.email" v-model:model-value="user.email"
                            class="col-span-3" />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4" v-if="props.operation == 'Create'">
                        <Label for="password" class="text-right"> Password </Label>
                        <Input id="password" type="password" name="password" v-model:model-value="user.password"
                            class="col-span-3" />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4" v-if="props.operation == 'Create'">
                        <Label for="password_confirmation" class="text-right"> Confirm Password </Label>
                        <Input id="password_confirmation" type="password" name="password_confirmation"
                            v-model:model-value="user.password_confirmation" class="col-span-3" />
                    </div>
                </div>
                <DialogFooter>
                    <Button type="submit"> Save changes </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
