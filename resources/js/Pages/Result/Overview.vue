<script setup>
const props = defineProps({
    label: String,
    prefix:{
        type: String,
        default: null,
    },
    value: [String, Number]
})

</script>
<template>
  <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
    <dt class="text-sm font-medium text-gray-700">
        {{ props.label }}
    </dt>
    <dd :class="props.prefix  && (props.value >= 50 ? ' bg-green-300' : 'bg-red-300')" class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
        {{ props.value }} {{ props.prefix}}
    </dd>
  </div>
</template>
