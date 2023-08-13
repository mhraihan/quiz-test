<script setup>

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {mdiAccountMultiple} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import UserTable from "@/Shared/UserTable.vue";
import {UserEnum} from "@/config";
const props = defineProps({
    students: {
        type: Object,
        required: true,
    },
    Role: {
        type: String,
        default: UserEnum.STUDENT
    },
});


</script>

<template>
    <SectionTitleLineWithButton
        :icon="mdiAccountMultiple"
        title="Student Overview"
        main
    ></SectionTitleLineWithButton>
    <CardBox v-if="props?.students?.data?.length > 0" class="mb-6" has-table>
        <UserTable :Users="props.students" :Role="props.Role"
                   :Query="{}"/>
    </CardBox>
    <CardBox v-else>
        <CardBoxComponentEmpty/>
    </CardBox>
</template>

