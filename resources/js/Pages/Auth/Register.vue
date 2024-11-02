<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import SelectOption from "@/Components/SelectOption.vue";
import FileUpload from "@/Components/FileUpload.vue";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger, SelectValue
} from "@/Components/ui/select/index.js";
import {Label} from "@/Components/ui/label/index.js";
import {Input} from "@/Components/ui/input/index.js";
import {Alert, AlertTitle} from "@/Components/ui/alert/index.js";
import {ref} from "vue";
import UserForm from "@/Pages/Users/UserForm.vue";

const props = defineProps({
                              userPrefixes: {
                                  type    : Array,
                                  required: true,
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

const profilePicture = ref(null);
const formEditable = ref(true)

const handleFileUpload = (e) => {
    console.log(e.target.files[0]);
    form.photo = e.target.files[0];
}

const submit = () => {

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register"/>

        <form class="flex flex-col space-y-3" @submit.prevent="submit" enctype="multipart/form-data">

            <UserForm :user-prefixes="userPrefixes" :form="form" :editable="formEditable" :profile-picture="profilePicture" :handle-file-upload="handleFileUpload" />

            <div class="mt-3 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
