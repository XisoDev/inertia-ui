<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    field: Object,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="mt-1 rounded-md shadow-sm flex">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content }}</span>
        </template>
        <textarea
            :id="field.id"
            :name="field.id"
            :autocomplete="field.id"
            :rows="field.attributes.rows"
            :aria-placeholder="field.placeholder"
            :placeholder="field.placeholder"
            v-model="modelValue"
            :class="field.class !== '' ? field.class : 'mt-1 block w-full'"
        ></textarea>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content }}</span>
        </template>
    </div>
</template>
