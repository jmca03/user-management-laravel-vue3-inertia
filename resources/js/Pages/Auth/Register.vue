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

            <div class="flex flex-col">
                <Label class="mb-3" for="prefixname">Prefix</Label>

                <Select v-model="form.prefixname">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select a prefix"/>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem
                                v-for="option in userPrefixes"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>

                <Label v-if="form?.errors?.prefixname" class="mt-3 text-red-500">{{ form?.errors?.prefixname }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="firstname">First Name</Label>

                <Input v-model="form.firstname" placeholder="Please input your first name." required/>

                <Label v-if="form?.errors?.firstname" class="mt-3 text-red-500">{{ form?.errors?.firstname }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="middlename">Middle Name</Label>
                <Input v-model="form.middlename" placeholder="Please input your middle name."/>
                <Label v-if="form.errors.middlename" class="mt-3 text-red-500">{{ form.errors.middlename }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="lastname">Last Name</Label>
                <Input v-model="form.lastname" placeholder="Please input your last name." required/>
                <Label v-if="form.errors.lastname" class="mt-3 text-red-500">{{ form.errors.lastname }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="suffixname">Suffix Name</Label>
                <Input v-model="form.suffixname" placeholder="Please input your suffix name."/>
                <Label v-if="form.errors.suffixname" class="mt-3 text-red-500">{{ form.errors.suffixname }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="username">Username</Label>
                <Input v-model="form.username" placeholder="Please input your username." required/>
                <Label v-if="form.errors.username" class="mt-3 text-red-500">{{ form.errors.username }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="email">Email</Label>
                <Input v-model="form.email" type="email" placeholder="Please input your email." required/>
                <Label v-if="form.errors.email" class="mt-3 text-red-500">{{ form.errors.email }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="password">Password</Label>
                <Input v-model="form.password" type="password" placeholder="Please input your password." required/>
                <Label v-if="form.errors.password" class="mt-3 text-red-500">{{ form.errors.password }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="password_confirmation">Confirm Password</Label>
                <Input v-model="form.password_confirmation" type="password" placeholder="Please confirm your password."
                       required/>
                <Label v-if="form.errors.password_confirmation"
                       class="mt-3 text-red-500">{{ form.errors.password_confirmation }}</Label>
            </div>

            <div class="flex flex-col">
                <Label class="mb-3" for="photo">Upload Photo</Label>
                <Input v-model="form.photo" type="file" @change="handleFileUpload"/>
                <Label v-if="form.errors.photo" class="mt-3 text-red-500">{{ form.errors.photo }}</Label>
            </div>

            <div class="flex items-center justify-end">
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
