<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    field: Object,
    options : Object,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    console.log("list item getting props this : ",props);
    if(typeof props.options === 'undefined'){
        props.options = props.field.options;
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="mt-1 rounded-md shadow-sm flex">
        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
            <button
                v-for="(option, i) in props.options"
                :key="option.value"
                type="button"
                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200"
                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(field.options).length - 1}"
                @click="modelValue = option.value"
            >
                <div :class="{'opacity-50': modelValue && modelValue !== option.value}">
                    <!-- Role Name -->
                    <div class="flex items-center">
                        <div class="text-sm text-gray-600" :class="{'font-semibold': modelValue === option.value}">
                            {{ option.text[$page.props.locale] }}
                        </div>

                        <svg
                            v-if="modelValue === option.value"
                            class="ml-2 h-5 w-5 text-green-400"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>

                    <!-- Role Description -->
                    <div class="mt-2 text-xs text-gray-600 text-left" v-if="option.description">
                        {{ option.description[$page.props.locale] }}
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>
