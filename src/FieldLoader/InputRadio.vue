<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
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
    <div class="mt-1 rounded-md shadow-sm">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content }}</span>
        </template>
        <div v-for="optionGroup in field.options" class="block">
            <label :for="optionGroup.value">{{optionGroup.value}}</label>
            <input
                :type="field.type"
                :id="optionGroup.value"
                :name="field.id"
                :autocomplete="field.id"

                :value="optionGroup.value"
                v-model="props.modelValue"
                :class="field.class !== '' ? field.class : 'mt-1 block w-full'"
            />
        </div>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content }}</span>
        </template>
    </div>
</template>
