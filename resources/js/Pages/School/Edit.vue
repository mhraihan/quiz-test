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
    School: Object,
});
const hasTable = true;

const School = useValidatedForm({
    id: [props.School.id],
    name: [props.School.name || null,[isRequired()]],
    short_name: [props.School.short_name || null,[isRequired()]],
    deleted_at: [props.School.deleted_at || null],
});
const updateSchool = () => {
    useMainStore().update(School, 'admin.schools.update', 'PUT');
}
const destroySchool = () => {
    useMainStore().destroy(School, 'admin.schools.destroy');
}
const restoreSchool = () => {
    useMainStore().restore(School, 'admin.schools.update');
}
</script>

<template>
    <Head title="Update School"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.schools.index')" title="Schools" location="Update School"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Update School"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="updateSchool"
                    :hasTable="hasTable"
                >
                    <Form :School="School" @destroy="destroySchool" @restore="restoreSchool"
                          :buttonText="`Update School`" />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
