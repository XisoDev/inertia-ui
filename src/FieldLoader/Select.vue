<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    field: Object,
});

const input = ref(props.modelValue);
const emit = defineEmits(['update:modelValue']);

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
            <select
                :id="field.id"
                :name="field.id"
                :class="[field.class !== '' ? field.class : 'mt-1 block w-full', 'disabled:cursor-not-allowed disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500']"
                :disabled="field.disabled === true"
                v-model="input"
                >
                <option
                    v-for="optionGroup in field.options"
                    :value="optionGroup.value"
                >
                    {{optionGroup.text}}
                </option>
            </select>

        </div>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content }}</span>
        </template>
    </div>
</template>
