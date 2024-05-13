<script setup>
import { computed, ref } from 'vue';

const emit = defineEmits(['update:checked', 'filter']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
    },
    values: {
        type: Object
    },
});

const extractedValue = ref(props.values.value)

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});

const filter = (event) => {
    emit('filter', event.target.value, props.values.column)
}

</script>

<template>
    <div v-for="value in extractedValue">
        <label class="flex mr-4 items-center flex-nowrap">
            <input
                type="checkbox"
                :value="value"
                v-model="proxyChecked"
                class="rounded border-gray-500 text-indigo-600 shadow-sm"
                @change="filter"
            />
            <span class="whitespace-nowrap ml-2">{{ value }}</span>
        </label>
    </div>
</template>
