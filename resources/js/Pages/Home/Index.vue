<script setup>
import {onMounted} from "vue";

import {mdiChartTimelineVariant, mdiAccountMultiple} from "@mdi/js";

import SectionMain from "@/Components/SectionMain.vue";
import CardBoxWidget from "@/Components/CardBoxWidget.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import Table from "@/Pages/Home/Table.vue";
import TitleWithButton from "@/Components/TitleWithButton.vue";
import Grid from "@/Components/Grid.vue";
import Placeholder from "@/Components/Placeholder.vue";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";

const props = defineProps({
    data: {
        type: Object,
        default: {
            loading: true,
        },
    },
});


// mounted() in VueJS / useEffect() in React
onMounted(() => {
    console.log('mounted')
     Inertia.reload({
        only: ['data']
    });
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


            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-emerald-500"
                    :number="props?.data?.totalSchools"
                    label="Number of School"
                />
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-emerald-500"
                    :number="props?.data?.totalTeacher"
                    label="Number of Teacher"
                />
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
                title="User Overview"
                main
            ></SectionTitleLineWithButton>
            <Grid class="pb-6" v-if="usePage().props.value.auth.roles === 'super-admin'">
                <CardBox v-if="props?.data?.superAdmin.data?.length" class="mb-6" has-table>
                    <TitleWithButton
                        class="px-3"
                        :icon="mdiAccountMultiple"
                        label="View All"
                        routeName="admin.students.index"
                        title="Recent Super Admin"
                    ></TitleWithButton>
                    <Table :Users="props?.data?.superAdmin"/>
                </CardBox>
                <CardBoxComponentEmpty
                    v-else
                    text="Sorry, No Super Admin found"
                    routeName="admin.students.create"
                    label="Create new Super Admin"
                />
                <CardBox v-if="props?.data?.admin.data?.length" class="mb-6" has-table>
                    <TitleWithButton
                        class="px-3"
                        :icon="mdiAccountMultiple"
                        label="View All"
                        routeName="admin.students.index"
                        title="Recent Admin"
                    ></TitleWithButton>
                    <Table :Users="props?.data?.admin"/>
                </CardBox>
                <CardBoxComponentEmpty
                    v-else
                    text="Sorry, No Admin found"
                    routeName="admin.students.create"
                    label="Create new Super Admin"
                />
            </Grid>
            <Grid>
                <CardBox v-if="props?.data?.student.data?.length" class="mb-6" has-table>
                    <TitleWithButton
                        class="px-3"
                        :icon="mdiAccountMultiple"
                        label="View All Students"
                        routeName="admin.students.index"
                        title="Recent Students"
                    ></TitleWithButton>
                    <Table :Users="props?.data?.student"/>
                </CardBox>
                <CardBoxComponentEmpty
                    v-else
                    text="Sorry, No Student found"
                    routeName="admin.students.create"
                    label="Create new Student"
                />
                <CardBox v-if="props?.data?.teacher.data?.length" class="mb-6" has-table>
                    <TitleWithButton
                        class="px-3"
                        :icon="mdiAccountMultiple"
                        label="View All Teacher"
                        routeName="admin.teachers.index"
                        title="Recent Teacher"
                    ></TitleWithButton>
                    <Table :Users="props?.data?.teacher"/>
                </CardBox>

                <CardBoxComponentEmpty
                    v-else
                    text="Sorry, No Teacher found"
                    routeName="admin.teachers.create"
                    label="Create new Teacher"
                />
            </Grid>
        </SectionMain>
    </LayoutAuthenticated>
</template>
