<script setup>
import { onMounted, defineProps } from 'vue';
import Image from '@fieldLoader/Image.vue';
import File from '@fieldLoader/File.vue';

const props = defineProps({
    field: Object,
    modelValue: Object,
});

onMounted(() => {
    console.log("v-model value in loader : " + props.modelValue);
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <template v-if="field.type === 'image'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <Image :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.files)" />
    </template>
    <template v-else-if="field.type === 'file'">
        <label :for="field.id" class="block text-sm font-medium text-gray-700"> {{ field.title }} </label>
        <File :field="field" v-model="props.modelValue" @input="$emit('update:modelValue', $event.target.files)" />
    </template>
    <template v-else>
        {{ field.type }}은 파일 로더에서 지원하지 않는 형식입니다.
    </template>
</template>
