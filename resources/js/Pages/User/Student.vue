<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import {Head, useForm} from "@inertiajs/inertia-vue3";
import Overview from "@/Pages/Result/Overview.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import ResultTable from "@/Pages/Result/ResultTable.vue";
import {computed, ref} from "vue";
import TrashedMessage from '@/Components/TrashedMessage.vue'
import {useMainStore} from "@/Stores/main";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
const props = defineProps({
    User: Object,
    results: Object,
    Role: {
        type: String,
        default: "Student"
    },
});


const profile = computed(() => `${props.User.first_name} ${props.User.last_name}`);
const score = parseFloat(((parseInt(props.User.results_sum_correct_answered)/parseInt(props.User.results_sum_total_questions)) * 100).toFixed(2));
const isModalDangerActive = ref(false);

const form = useForm({
    id: props.User.id,
});
const restore = () => {
    useMainStore().restore(form, 'admin.users.restore');
}

const destroy = () => {
    useMainStore().destroy(form, 'admin.users.destroy');
}
</script>

<template>
    <Head title="Create new Question"/>
    <LayoutAuthenticated>
        <CardBoxModal
            v-model="isModalDangerActive"
            title="Please confirm"
            button="danger"
            has-cancel
            @confirm="destroy"
        >
            <p>Are you sure you want to Delete the Student?</p>
        </CardBoxModal>
        <SectionMain>
            <Breadcrumbs :href="route('admin.students.index')" title="Student" :location="profile"/>
            <trashed-message v-if="props.User.deleted_at" @restore="restore" class="mb-6" :restore="`Are you sure you want to restore this ${props.Role}?`"> This {{ props.Role }} has been
                deleted.
            </trashed-message>
            <CardBox class=" overflow-hidden sm:rounded-lg mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        {{ profile }} Details
                    </h1>
                </div>
                <div class="border-t border-gray-300">
                    <dl>
                        <Overview label="First Name" :value="props.User.first_name"/>
                        <Overview label="Last Name" :value="props.User.last_name"/>
                        <Overview label="Email" :value="props.User.email"/>
                        <Overview v-if="props.User?.phone" label="Phone" :value="props.User?.phone"/>
                        <Overview  v-if="props.User?.gender" label="Gender" :value="props.User?.gender"/>
                        <Overview  v-if="props.User?.city" label="City" :value="props.User?.city" class="capitalize"/>
                        <Overview  v-if="props.User?.state" label="State" :value="props.User?.state"/>
                        <Overview  v-if="props.User?.postcode" label="Postcode" :value="props.User?.postcode"/>
                        <Overview  v-if="props.User?.country" label="Country" :value="props.User?.country"/>
                        <Overview  v-if="props.User?.address" label="Address" :value="props.User?.address"/>

                        <Overview  v-if="props.User?.results_sum_correct_answered" class="border-t border-gray-300 mt-4" label="Total Answered Questions" :value="props.User.results_sum_total_questions"/>
                        <Overview  v-if="props.User?.results_sum_correct_answered" label="Correct Answered" :value="props.User.results_sum_correct_answered"/>
                        <Overview  v-if="props.User?.results_sum_correct_answered"  label="Avg. Score" :value="score + '%'"/>
                    </dl>
                    <BaseDivider/>

                    <div class="flex">
                        <BaseButtons class="mr-10" v-if="!props.User.deleted_at">
                            <BaseButton
                                color="info"
                                type="button"
                                label="Edit Student"
                                route-name="admin.students.edit"
                                :route-params="props.User.id"

                            />
                        </BaseButtons>

                        <BaseButtons v-if="route().current('admin.students.show')">
                            <BaseButton

                                color="danger"
                                type="button"
                                label="Delete Student"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="isModalDangerActive = true;"
                            />
                        </BaseButtons>
                    </div>

                </div>
            </CardBox>
            <CardBox v-if="props.results.data.length > 0" class="mb-6" has-table>
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        {{ profile }} Latest Quiz Results
                    </h1>
                </div>
                <div class="border-t border-gray-100">
                    <ResultTable :results="props.results"/>
                </div>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty text="No Results found!" />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
