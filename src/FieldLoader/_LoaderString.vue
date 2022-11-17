<script setup>
import { onMounted, defineProps } from 'vue';
import InputText from '@fieldLoader/InputText.vue';
import TextArea from '@fieldLoader/Textarea.vue';
import ListItem from '@fieldLoader/ListItem.vue';
import RadioGroups from '@fieldLoader/RadioGroups.vue';
import InputRadio from '@fieldLoader/InputRadio.vue';
import Select from '@fieldLoader/Select.vue'

const props = defineProps({
    field: Object,
    modelValue: String,
});

onMounted(() => {
    console.log("v-model value in loader : " + props.modelValue);
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <template v-if="field.type === 'text' || field.type === 'number' || field.type === 'email' || field.type === 'url'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <InputText :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
    <template v-else-if="field.type === 'textarea'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <TextArea :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
    <template v-else-if="field.type === 'list-item'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <ListItem :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
    <template v-else-if="field.type === 'radio-group'">
        <h4 class="block text-md font-medium text-gray-700 mb-4"> {{ field.title }} </h4>
        <RadioGroups :field="field" v-model="props.modelValue"
                     :modelValue="props.modelValue"
                     @update:modelValue="value => $emit('update:modelValue', value)" />
        <p v-if="field.description" class="text-sm text-gray-500"> {{ field.description }} </p>
    </template>
    <template v-else-if="field.type === 'radio'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <InputRadio :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
    <template v-else-if="field.type === 'select'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <Select :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
    <template v-else>
        {{field.type}}은 지원하지 않는 형식입니다.
    </template>
</template>
