<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps({
                              onChange: {
                                  type    : Function,
                                  required: false,
                              }
                          })

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        type="file"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
        ref="input"
        @change="onChange"
    />
</template>
