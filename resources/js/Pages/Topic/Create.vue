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

const hasTable = true;

const Topic = useValidatedForm({
    title: ["",[isRequired()]],
    deleted_at: [null],
});
const createUser = () => {
    useMainStore().createSync(Topic, 'admin.topics.store');
}
</script>

<template>
    <Head title="Create new Topic"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.topics.index')" title="Topics" location="Create New Topics"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Create new Topics"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="createUser"
                    :hasTable="hasTable"
                >
                    <Form :Topic="Topic" />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
