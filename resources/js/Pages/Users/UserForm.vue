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

const props = defineProps({
                              userPrefixes    : {
                                  type    : Array,
                                  required: true,
                              },
                              form            : {
                                  type    : Object,
                                  required: false
                              },
                              editable        : {
                                  type   : Boolean,
                                  default: false
                              },
                              handleFileUpload: {
                                  type   : Function,
                                  default: () => console.log('Attaching file')
                              },
                              profilePicture  : {
                                  type    : String,
                                  required: false
                              }
                          })

</script>

<template>
    <div class="mb-4">
        <Label for="prefixname">Prefix Name</Label>

        <Select :disabled="!editable" v-model="form.prefixname">
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

        <Label v-if="form.errors.prefixname" class="mt-3 text-red-500">{{ form.errors.prefixname }}</Label>
    </div>
    <div class="mb-4">
        <Label for="firstname">First Name</Label>
        <Input :disabled="!editable" v-model="form.firstname" id="firstname"
               placeholder="Enter first name" required/>
        <Label v-if="form.errors.firstname" class="mt-3 text-red-500">{{ form.errors.firstname }}</Label>
    </div>
    <div class="mb-4">
        <Label for="middlename">Middle Name</Label>
        <Input :disabled="!editable" v-model="form.middlename" id="middlename" placeholder="Enter middle name"/>
        <Label v-if="form.errors.middlename" class="mt-3 text-red-500">{{ form.errors.middlename }}</Label>
    </div>
    <div class="mb-4">
        <Label for="lastname">Last Name</Label>
        <Input :disabled="!editable" v-model="form.lastname" id="lastname" placeholder="Enter last name" required/>
        <Label v-if="form.errors.lastname" class="mt-3 text-red-500">{{ form.errors.lastname }}</Label>
    </div>
    <div class="mb-4">
        <Label for="suffixname">Suffix Name</Label>
        <Input :disabled="!editable" v-model="form.suffixname" id="suffixname" placeholder="Enter suffix name"/>
        <Label v-if="form.errors.suffixname" class="mt-3 text-red-500">{{ form.errors.suffixname }}</Label>
    </div>
    <div class="mb-4">
        <Label for="username">Username</Label>
        <Input :disabled="!editable" v-model="form.username" id="username" placeholder="Enter username" required/>
        <Label v-if="form.errors.username" class="mt-3 text-red-500">{{ form.errors.username }}</Label>
    </div>
    <div class="mb-4">
        <Label for="email">Email</Label>
        <Input :disabled="!editable" type="email" v-model="form.email" id="email" placeholder="Enter email" required/>
        <Label v-if="form.errors.email" class="mt-3 text-red-500">{{ form.errors.email }}</Label>
    </div>
    <div v-if="editable" class="mb-4">
        <Label for="password">Password</Label>
        <Input :disabled="!editable" type="password" v-model="form.password" id="password"
               placeholder="Enter password"/>
        <Label v-if="form.errors.password" class="mt-3 text-red-500">{{ form.errors.password }}</Label>
    </div>
    <div v-if="editable" class="mb-4">
        <Label for="password_confirmation">Confirm Password</Label>
        <Input type="password" v-model="form.password_confirmation" id="password_confirmation"
               placeholder="Confirm password"/>
        <Label v-if="form.errors.password_confirmation"
               class="mt-3 text-red-500">{{ form.errors.password_confirmation }}</Label>
    </div>
    <div v-if="editable" class="mb-4">
        <Label for="photo">Photo</Label>
        <PhotoUpload :previewUrl="profilePicture" :handleFileChange="handleFileUpload"/>
        <Label v-if="form.errors.prefixname" class="mt-3 text-red-500">{{ form.errors.photo }}</Label>
    </div>
    <div v-else-if="!editable && profilePicture">
        <Label for="photo">Photo</Label>
        <label
            class="relative block cursor-pointer border-dashed border-2 border-gray-300 rounded-md p-4 hover:border-gray-400 transition">
            <img
                :src="profilePicture"
                alt="Preview"
                class="w-full h-48 object-cover rounded-md"
            />
        </label>
    </div>
    <div v-else>
        <Label for="photo">Photo</Label>
        <br>
        <Label for="photo">No Profile Picture</Label>
    </div>
</template>
