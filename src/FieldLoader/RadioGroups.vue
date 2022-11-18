<script setup>
import { ref,watch } from 'vue';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/outline'

const props = defineProps({
    modelValue: String,
    field: Object,
});

const input = ref(props.modelValue);
const emit = defineEmits(['update:modelValue'])
//
watch(input, (x) => {
    // props.modelValue = x;
    emit('update:modelValue', x);
})
</script>

<template>
    <RadioGroup v-model="input">
        <template v-for="optionGroup in field.groups">
            <div class="mb-6">
                <div class="flex items-center justify-between my-3">
                    <h2 class="text-sm font-medium text-gray-900">{{ optionGroup.title }}</h2>
                    <a v-if="optionGroup.link_url !== ''" :href="optionGroup.link_url" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        {{ optionGroup.link_title }}
                    </a>
                </div>
                <RadioGroupLabel class="sr-only"> {{ optionGroup.title }} </RadioGroupLabel>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-4">
                    <template v-for="(option,index) in optionGroup.options"
                        :key="index">
                        <RadioGroupOption
                            :value="option.value.toString()"
                            v-slot="{ checked }">
                            <div :class="[checked ? 'bg-indigo-600 border-transparent text-white hover:bg-indigo-700' : 'bg-white border-gray-200 text-gray-900 hover:bg-gray-50', 'border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium uppercase sm:flex-1']">
                                <CheckIcon v-show="checked" class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500 mr-1" />
                                <RadioGroupLabel as="span">{{ option.text }}</RadioGroupLabel>
                            </div>
                        </RadioGroupOption>
                    </template>
                </div>
            </div>
        </template>
    </RadioGroup>
</template>
