<script setup>
defineProps({
    modelValue: String,
    field: Object,
});
defineEmits(['update:modelValue']);
</script>

<template>
    <div class="mt-1 rounded-md shadow-sm flex">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content[$page.props.locale] }}</span>
        </template>
        <input
            :type="field.type"
            :id="field.id"
            :name="field.id"
            :autocomplete="field.id"

            :aria-placeholder="field.placeholder[$page.props.locale]"
            :placeholder="field.placeholder[$page.props.locale]"
            v-model="modelValue"
            :class="[field.class !== '' ? field.class : 'mt-1 block w-full', 'disabled:cursor-not-allowed disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500']"
            :disabled="field.disabled === true"
        />
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content[$page.props.locale] }}</span>
        </template>
    </div>
</template>
