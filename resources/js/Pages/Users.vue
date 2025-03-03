<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";
import MyTable from "@/Components/MyTable.vue";
import { ref } from "vue";
import MyDialog from "@/Components/MyDialog.vue";

let operation = ref("Create");
let isDialogOpen = ref(false);

const props = defineProps({
    users: {
        type: Object,
    },
});

const users = ref(props.users.data);
const user = ref({});

function setDialog() {
    isDialogOpen.value = false;
}

function createRecord(data) {
    users.value.push(data);
}

function editRecord(data) {
    let user = users.value.find((user) => user.id === data.id);
    Object.assign(user, data);
}

const deleteRecord = (data) => {
    axios({
        method: "delete",
        url: `/users/${data.id}`,
        responseType: "json",
    }).then(function (response) {
        if (response.status === 204) {
            users.value = users.value.filter((user) => user.id !== data.id);
        }
    });
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
    <MyDialog v-if="isDialogOpen" :operation="operation" :isDialogOpen="isDialogOpen" @onCreate="createRecord"
        @onEdit="editRecord" @onDialogClose="setDialog" :user="user">
    </MyDialog>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Users
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Users
                </h3>
                <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <Button class="float-right" @click="handleCreateClick" variant="outline">
                        <font-awesome-icon icon="user" class="pr-2" /> Add User
                    </Button>
                </div>
                <MyTable :users="users" :actions="[handleEditClick, deleteRecord]" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
