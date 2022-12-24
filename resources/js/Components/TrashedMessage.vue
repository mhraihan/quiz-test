<script setup>
import { mdiTrashCanOutline } from "@mdi/js";
import BaseIcon from "@/Components/BaseIcon.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";

import {ref} from "vue";
const emit = defineEmits(["restore"]);
const props = defineProps({
    restore: {
        type: String,
        default: "Are you sure you want to restore this Question?"
    }
})
const isModalRestoreActive = ref(false);

const restoreModal = () => {
    emit('restore');
}
</script>
<template>
    <transition name="fade">
        <div class="flex items-center justify-between p-4 max-w-3xl bg-yellow-400 rounded">
            <div class="flex items-center">
                <BaseIcon  :path="mdiTrashCanOutline" class="flex-shrink-0 mr-2 w-4 h-4 fill-yellow-800" size="20" />
                <div class="text-yellow-800 text-sm font-medium">
                    <slot />
                </div>
            </div>
            <button class="text-yellow-800 hover:underline text-sm" @click="isModalRestoreActive=true" tabindex="-1" type="button">Restore</button>
            <CardBoxModal
                v-model="isModalRestoreActive"
                title="Please confirm"
                button="danger"
                has-cancel
                @confirm="restoreModal"
            >
                <p>{{ props.restore }}</p>
            </CardBoxModal>
        </div>
    </transition>
</template>
