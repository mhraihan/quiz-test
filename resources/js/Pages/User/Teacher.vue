<script setup>
import {onMounted} from "vue";

import {mdiChartTimelineVariant, mdiAccountMultiple} from "@mdi/js";

import SectionMain from "@/Components/SectionMain.vue";
import CardBoxWidget from "@/Components/CardBoxWidget.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import Placeholder from "@/Components/Placeholder.vue";
import UserTable from "@/Pages/User/UserTable.vue";
const props = defineProps({
    data: {
        type: Object,
        default: {
            loading: true,
        },
    },
    filters: Object,
});




</script>

<template>
    <LayoutAuthenticated>
        <SectionMain v-if="data?.loading">
            <Placeholder/>
        </SectionMain>
        <SectionMain v-else>
            <SectionTitleLineWithButton
                :icon="mdiChartTimelineVariant"
                title="Quiz Application Overview"
                main
            ></SectionTitleLineWithButton>


            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">

                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-emerald-500"
                    :number="props?.data?.totalStudent"
                    label="Number of Student"
                />
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-emerald-500"
                    :number="props?.data?.examTakenCount"
                    label="Total Exam Taken"
                />
            </div>

            <SectionTitleLineWithButton
                :icon="mdiAccountMultiple"
                title="Student Overview"
                main
            ></SectionTitleLineWithButton>
            <CardBox v-if="props.data?.students?.data?.length > 0" class="mb-6" has-table>
                <UserTable :Users="props.data?.students" :Role="props.Role"
                           :Query="{}"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty/>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
