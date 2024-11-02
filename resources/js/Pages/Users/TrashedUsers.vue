<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import DataTable from "@/Components/DataTable.vue";
import {h, toRaw} from "vue";
import UserColumns from "@/Pages/Users/user-columns.js";
import {Button} from '@/Components/ui/button/index.js'
import {Link} from '@inertiajs/vue3'
import TrashedUserColumns from "@/Pages/Users/trashed-user-columns.js";

const props = defineProps({
                              users       : {
                                  type    : Object,
                                  required: true,
                              },
                              alertMessage: {
                                  type    : String,
                                  required: false
                              }
                          })

</script>

<template>
    <Head title="Users"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Users (Deleted)
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <DataTable :columns="TrashedUserColumns" :data="users"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
