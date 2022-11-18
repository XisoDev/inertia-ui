<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: Object,
    field: Object,
});

defineEmits(['update:modelValue']);

const input = ref(props.modelValue);

onMounted(() => {

});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="mt-1 rounded-md shadow-sm">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content }}</span>
        </template>
        <div class="block">
            <input
                :type="field.type"
                :id="field.id"
                :name="field.id"
                :class="field.class !== '' ? field.class : 'mt-1 block w-full'"
                v-model="input"
            >
        </div>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content }}</span>
        </template>
    </div>
</template>
