<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head} from "@inertiajs/inertia-vue3";
import useValidatedForm from "@/useValidatorForm";
import {isRequired} from "intus/rules";
import Form from "./Form.vue";
import {useMainStore} from "@/Stores/main";
const props = defineProps({
    Topic: Object,
});
const hasTable = true;

const Topic = useValidatedForm({
    id: [props.Topic.id],
    title: [props.Topic.title || null,[isRequired()]],
    deleted_at: [props.Topic.deleted_at || null],
});
const updateTopic = () => {
    useMainStore().updateSync(Topic, 'admin.topics.update', 'PUT');
}
const destroyTopic = () => {
    useMainStore().destroy(Topic, 'admin.topics.destroy');
}
const restoreTopic = () => {
    useMainStore().restore(Topic, 'admin.topics.update');
}
</script>

<template>
    <Head title="Update Topic"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.topics.index')" title="Topics" location="Update Topic"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Update Topic"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="updateTopic"
                    :hasTable="hasTable"
                >
                    <Form :Topic="Topic" @destroy="destroyTopic" @restore="restoreTopic"
                          :buttonText="`Update Topic`" />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
