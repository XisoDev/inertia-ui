<script setup>
import { onMounted, ref } from 'vue';
import DropImages from "@fieldLoader/DropImages.vue";

const props = defineProps({
    modelValue: Object,
    field: Object,
});

const emit = defineEmits(['changed']);

onMounted(() => {

});

//methods
function handleImages(files){
    emit("changed", files);
}

defineExpose({ focus: () => input.value.focus() });
</script>

<style>
.beforeUpload .icon{
    width:60px !important;
    padding:30px 0;
}
.beforeUpload *{
    stroke: #8e9ca2 !important;
}
.imgsPreview .imageHolder img{
    border-radius:10px;
}
.imgsPreview .imageHolder .delete{
    background:transparent !important;
    color:#d34242 !important;
    top:0 !important;
    right:0 !important;
    left:0 !important;
    bottom:0 !important;
    width:100% !important;
    height: 100% !important;
}
.imgsPreview .imageHolder .delete svg{
    width:20px !important;
    height: 20px !important;
    margin: 10px 10px 0 auto !important;
}
button.clearButton{
    font-size:12px;
    padding:5px 15px;
}
</style>

<template>
    <div class="mt-1 rounded-md shadow-sm">
        <template v-for="prepend in field['prepends']">
            <span :class="prepend.class">{{ prepend.content }}</span>
        </template>
        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <div class="mt-1 sm:mt-0 sm:col-span-4">
                <DropImages @changed="handleImages" :uploaded="field.originUploaded" maxError="이미지를 더 추가할 수 없습니다." :uploadMsg="field.description" fileError="허용되지 않은 파일을 업로드 시도 했습니다." clearAll="비우기" :max="field.attributes.max" />
            </div>
        </div>
        <template v-for="append in field['appends']">
            <span :class="append.class">{{ append.content }}</span>
        </template>
    </div>
</template>
