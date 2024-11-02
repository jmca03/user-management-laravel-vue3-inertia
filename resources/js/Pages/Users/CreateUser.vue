<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import DataTable from "@/Components/DataTable.vue";
import {h, ref, toRaw} from "vue";
import UserColumns from "@/Pages/Users/user-columns.js";
import {Label} from "@/Components/ui/label/index.js";
import {Input} from "@/Components/ui/input/index.js";
import {Button} from '@/Components/ui/button/index.js'
import PhotoUpload from "@/Components/PhotoUpload.vue";
import {toast} from "vue3-toastify";
import 'vue3-toastify/dist/index.css';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue
} from "@/Components/ui/select/index.js";
import UserForm from "@/Pages/Users/UserForm.vue";

const props = defineProps({
                              userPrefixes: {
                                  type    : Array,
                                  required: true,
                              },
                            user: {
                                  type: Object,
                                  required: true
                            }
                          })

const form = useForm({
                         prefixname           : '',
                         firstname            : '',
                         middlename           : '',
                         lastname             : '',
                         suffixname           : '',
                         username             : '',
                         email                : '',
                         password             : '',
                         password_confirmation: '',
                         photo                : '',
                     });

const profilePicture = ref(props?.user?.avatar || null);
const formEditable = ref(true)

const handleFileUpload = (e) => {

    profilePicture.value = URL.createObjectURL(e.target.files[0])

    form.photo = e.target.files[0];
}

const handleSubmit = () => {

    form.post(route('users.store'), {
        onSuccess: () => {
            toast.success('User successfully created');
        },
        onError: () => {
          toast.error('Unable to created user')
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <Head title="Edit User"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Create Users
            </h2>
        </template>

        <div class="max-w-md mx-auto p-4 border rounded-lg shadow-lg">
            <form @submit.prevent="handleSubmit">
                <UserForm :user-prefixes="userPrefixes" :form="form" :editable="formEditable" :profile-picture="profilePicture" :handle-file-upload="handleFileUpload" />

                <div class="mt-3 flex justify-end">
                    <Button @click="" class="me-2" variant="outline">
                        <Link :href="route('users.index')">
                            Cancel
                        </Link>
                    </Button>
                    <Button type="submit">Submit</Button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
