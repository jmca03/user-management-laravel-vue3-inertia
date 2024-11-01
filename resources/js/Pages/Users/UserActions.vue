<script setup lang="ts">
import {Button} from '@/Components/ui/button'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger
} from '@/Components/ui/dropdown-menu'
import {MoreHorizontal} from 'lucide-vue-next'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEye, faPencil, faTimes, faTrash} from "@fortawesome/free-solid-svg-icons";
import {ref, defineEmits} from "vue";
import Modal from "@/Components/Modal.vue";
import {Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle} from "@/Components/ui/card";
import {router, useForm} from "@inertiajs/vue3";
import {toast} from "vue3-toastify";
import 'vue3-toastify/dist/index.css';
import {Separator} from "radix-vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const showDeleteModal = ref(false)

const handleDelete = (id) => {
    const form = useForm({})

    form.delete(route('users.destroy', {
        user: id
    }), {
        onSuccess: () => {
            toast('User deleted', {
                autoClose: 2000, type: 'success'
            })
            router.visit(route('users.index'), {
                only: ['users'],
            })
        },
        onError: () => {
            toast('Unable to delete user', {
                autoClose: 2000, type: 'error'
            })
        },
        onFinish: () => {
            showDeleteModal.value = false
        }
    })
}

const handleView = (id) => {
    router.visit(route('users.show', {
        user: id
    }), {
        only: ['users'],
    })
}

const handleEdit = (id) => {
    router.visit(route('users.edit', {
        user: id
    }), {
        only: ['users'],
    })
}

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4"/>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem class="cursor-pointer" @click="() => handleView(user?.id)">
                <FontAwesomeIcon :icon="faEye"/>
                View User
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer" @click="() => handleEdit(user?.id)">
                <FontAwesomeIcon :icon="faPencil"/>
                Edit User
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer" @click="showDeleteModal=true">
                <FontAwesomeIcon :icon="faTrash" class="text-red-500"/>
                Delete User
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <Modal :show="showDeleteModal">
        <Card class="p-3">
            <CardHeader>
                <CardTitle class="w-100 flex justify-between">
                    Delete User
                    <FontAwesomeIcon :icon="faTimes" @click="showDeleteModal=false" class="block cursor-pointer"/>
                </CardTitle>
                <CardDescription>Are you sure you want to delete this user?</CardDescription>
            </CardHeader>
            <CardFooter class="flex justify-end w-100 px-6 pb-6">
                <Button @click="showDeleteModal=false" class="me-3" variant="outline">
                    Cancel
                </Button>
                <Button @click="() => handleDelete(props?.user?.id)" variant="destructive">Submit</Button>
            </CardFooter>
        </Card>
    </Modal>
</template>
