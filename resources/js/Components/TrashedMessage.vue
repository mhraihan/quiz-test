<script setup>
import { mdiTrashCanOutline } from "@mdi/js";
import BaseIcon from "@/Components/BaseIcon.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";

import {ref} from "vue";
const emit = defineEmits(["restore"]);

const isModalRestoreActive = ref(false);

const restoreModal = () => {
    console.log('restore');
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
                <p>Are you sure you want to restore this Question?</p>
            </CardBoxModal>
        </div>
    </transition>
</template>


<style scoped>
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.fade-enter-to {
    opacity: 0;
}
.fade-enter-active,
.fade-leave-active {
   /*transition: opacity 2s ease-in-out;*/
}
</style>
