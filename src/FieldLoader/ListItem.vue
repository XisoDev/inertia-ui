<script setup>
import {onMounted, ref, watch} from 'vue';

const props = defineProps({
    modelValue: String,
    field: Object,
});

const input = ref(props.modelValue);
const emit = defineEmits(['update:modelValue']);


watch(input, (x) => {
    // props.modelValue = x;
    emit('update:modelValue', x);
})

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="mt-1 rounded-md flex mb-2">
        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
            <button
                v-for="(optionGroup, i) in field.options"
                :key="i"
                type="button"
                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none"
                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(field.options).length - 1}"
                @click="input = optionGroup.value"
            >
                <div :class="{'opacity-50': input && input !== optionGroup.value}">
                    <!-- Role Name -->
                    <div class="flex items-center">
                        <div class="text-sm text-gray-600" :class="{'font-semibold': input === optionGroup.value}">
                            {{ optionGroup.text }}
                        </div>

                        <svg
                            v-if="input === optionGroup.value"
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
                    <div class="mt-2 text-xs text-gray-600 text-left" v-if="optionGroup.description">
                        {{ optionGroup.description }}
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>
