<script setup>
import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Placeholder from "@/Components/Placeholder.vue";
import OverviewSection from "@/Shared/Teacher/OverviewSection.vue";
import StudentOverviewSection from "@/Shared/Teacher/StudentOverviewSection.vue";
import {isAdmin, UserEnum} from "@/config";
import {useMainStore} from "@/Stores/main";
import {computed, ref} from "vue";
import {useForm, Head} from "@inertiajs/inertia-vue3";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import {generateRouterConfigByRole} from "@/Pages/User/userFormConfig";
import CardBox from "@/Components/CardBox.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";

const props = defineProps({
    User: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        default: {
            loading: true,
        },
    },
    Role:{
        type:String,
        default: UserEnum.TEACHER
    },
    filters: Object,
});
const routerConfig = computed(() => {
    return generateRouterConfigByRole(props.Role); // Use the utility function
});
const isModalDangerActive = ref(false);

const form = useForm({
    id: props?.User.id,
});
const restore = () => {
    useMainStore().restore(form, 'admin.users.restore');
}

const destroy = () => {
    useMainStore().destroy(form, 'admin.users.destroy');
}


</script>

<template>
    <Head title="Teacher Profile's"/>
    <LayoutAuthenticated>
        <SectionMain v-if="data?.loading">
            <Placeholder/>
        </SectionMain>
        <SectionMain v-else>
             <trashed-message  v-if="props.User.deleted_at" @restore="restore" class="mb-6" :restore="`Are you sure you want to restore this ${props.Role}?`"> This {{ props.Role }} has been
                deleted.
            </trashed-message>
            <CardBoxModal
                v-if="isAdmin()"
                v-model="isModalDangerActive"
                title="Please confirm"
                button="danger"
                has-cancel
                @confirm="destroy"
            >
                <p>Are you sure you want to Delete the Teacher?</p>
            </CardBoxModal>

            <OverviewSection :students="props?.data?.totalStudent" :exam="props?.data?.examTakenCount"/>
            <CardBox class="my-4">
                <div class="flex">
                    <BaseButtons class="mr-10" v-if="!props?.User?.deleted_at">
                        <BaseButton
                            color="info"
                            type="button"
                            class="capitalize"
                            :label="`Edit ${props.Role}`"
                            :route-name="routerConfig.edit"
                            :route-params="props?.User.id"

                        />
                    </BaseButtons>

                    <BaseButtons v-if="isAdmin() && route().current(routerConfig.show)">
                        <BaseButton

                            color="danger"
                            type="button"
                            class="capitalize"
                            :label="`Delete ${props.Role}`"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="isModalDangerActive = true;"
                        />
                    </BaseButtons>

                </div>
            </CardBox>

            <StudentOverviewSection :students="props.data?.students" :Role="UserEnum.STUDENT"/>
        </SectionMain>
    </LayoutAuthenticated>
</template>
