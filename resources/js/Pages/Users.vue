<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import MyTable from "@/Components/MyTable.vue";
import { ref } from "vue";
import MyDialog from "@/Components/MyDialog.vue";
import { useToast } from '@/Components/ui/toast/use-toast';
import MyPagination from "@/Components/MyPagination.vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
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
            users.value.data = users.value.data.filter((user) => user.id !== data.id);
            toast({
                description: "User deleted successfully",
                variant: "destructive"
            });
        }
    }).catch((error) => {
        const myErrors = error.response.data.errors;
        const errorKey = Object.keys(myErrors)[0];
        const errors = myErrors[errorKey];

        if (errors) {
            Object.entries(errors).forEach(([key, messages]) => {
                toast({
                    title: key,
                    description: messages[0]
                });
            });
        }
        console.error("Errors:", JSON.stringify(myErrors));
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
        <MyDialog v-if="isDialogOpen" :operation="operation" :isDialogOpen="isDialogOpen" @onCreate="createRecord"
            @onEdit="editRecord" @onDialogClose="setDialog" :user="user">
        </MyDialog>
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
                        <MyTable :data="users.data" :actions="[handleEditClick, deleteRecord]" />
                    </CardContent>
                    <CardFooter class="flex justify-center p-4">
                        <MyPagination :meta="users.meta"></MyPagination>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
