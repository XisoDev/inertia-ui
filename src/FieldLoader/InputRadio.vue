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
    <div class="mt-1 rounded-md shadow-sm">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content[$page.props.locale] }}</span>
        </template>
        <div v-for="optionGroup in field.options" class="block">
            <label :for="optionGroup.value">{{optionGroup.value}}</label>
            <input
                :type="field.type"
                :id="optionGroup.value"
                :name="field.id"
                :autocomplete="field.id"

                :value="optionGroup.value"
                v-model="modelValue"
                :class="field.class !== '' ? field.class : 'mt-1 block w-full'"
            />
        </div>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content[$page.props.locale] }}</span>
        </template>
    </div>
</template>
