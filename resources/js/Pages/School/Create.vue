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

const School = useValidatedForm({
    name: ["",[isRequired()]],
    short_name: ["",[isRequired()]],
    deleted_at: [null],
});
const createUser = () => {
    useMainStore().create(School, 'admin.schools.store');
}
</script>

<template>
    <Head title="Create new School"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.schools.index')" title="Schools" location="Create New Schools"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Create new Schools"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="createUser"
                    :hasTable="hasTable"
                >
                    <Form :School="School" />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
