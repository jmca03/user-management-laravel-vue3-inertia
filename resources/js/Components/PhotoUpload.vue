<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps({
                              handleFileChange: {
                                  type    : Function,
                                  required: false,
                              },
                              previewUrl: {
                                  type: String,
                                  required: false,
                              }
                          })

</script>

<template>
    <div class="flex flex-col items-center">
        <label class="relative block cursor-pointer border-dashed border-2 border-gray-300 rounded-md p-4 hover:border-gray-400 transition">
            <input
                type="file"
                accept="image/*"
                @change="handleFileChange"
                class="absolute inset-0 opacity-0 cursor-pointer"
            />
            <div v-if="previewUrl" class="relative">
                <img
                    :src="previewUrl"
                    alt="Preview"
                    class="w-full h-48 object-cover rounded-md"
                />
            </div>
            <span v-else class="text-gray-500">Drag and drop a file here, or click to select</span>
        </label>
    </div>
</template>
