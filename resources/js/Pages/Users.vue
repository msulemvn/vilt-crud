<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import Table from "@/Components/Table.vue";
import { ref } from "vue";
import Dialog from "@/Components/Dialog.vue";
import { useToast } from '@/Components/ui/toast/use-toast';
import Pagination from "@/Components/Pagination.vue";
import {
    Card,
    CardContent,
    CardFooter,
    CardTitle,
} from '@/Components/ui/card'

const { toast } = useToast()

let operation = ref("Create");
let isDialogOpen = ref(false);

const props = defineProps({
    users: {
        type: Object,
    },
});

const users = ref(props.users);
const user = ref({});

function setDialog() {
    isDialogOpen.value = false;
}

function createRecord(data) {
    users.value.data.unshift(data);
}

function editRecord(data) {
    let user = users.value.data.find((user) => user.id === data.id);
    Object.assign(user, data);
}

const deleteRecord = (data) => {
    axios({
        method: "delete",
        url: `/users/${data.id}`,
        responseType: "json",
    }).then(function (response) {
        if (response.status === 204) {
            Object.assign(users.value.data, users.value.data.filter((user) => user.id !== data.id));
            toast({
                description: "User deleted successfully",
                variant: "destructive"
            });
        }
    }).catch((error) => {
        const errors = error.response.data.errors;
        if (errors && 'validation' in errors) {
            const validationErrors = errors['validation'];
            const firstErrorKey = Object.keys(errors)[0];
            const firstErrorMessages = errors[firstErrorKey];
            if (validationErrors) {
                form.setErrors(firstErrorMessages);
            } else if (firstErrorMessages) {
                const [firstField, messages] = Object.entries(firstErrorMessages)[0] || [];
                if (firstField && messages?.[0]) {
                    toast({
                        title: firstField,
                        description: messages[0],
                    });
                }
            }
        }
        console.error("Errors:", JSON.stringify(dataErrors));
    })
};

const handleEditClick = (data) => {
    operation.value = "Edit";
    isDialogOpen.value = true;
    user.value = data;
};

const handleCreateClick = () => {
    operation.value = 'Create';
    isDialogOpen.value = true;
    user.value = {};
};
</script>

<template>

    <Head title="Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Users
            </h2>
        </template>
        <Dialog v-if="isDialogOpen" :operation="operation" :isDialogOpen="isDialogOpen" @onCreate="createRecord"
            @onEdit="editRecord" @onDialogClose="setDialog" :user="user">
        </Dialog>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Users
                </h3>
                <Card class="shadow-lg rounded-lg">
                    <CardTitle>
                        <div class="flex justify-end m-4">
                            <Button @click="handleCreateClick" variant="ghost">
                                <font-awesome-icon icon="user" class="pr-2" /> Add User
                            </Button>
                        </div>
                    </CardTitle>
                    <CardContent class="max-h-[60vh]">
                        <Table :data="users.data" :actions="[handleEditClick, deleteRecord]" />
                    </CardContent>
                    <CardFooter class="flex justify-center p-4">
                        <Pagination :meta="users.meta"></Pagination>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
