<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import {Head} from "@inertiajs/inertia-vue3";
import {
    mdiTableBorder,
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import SectionMain from "@/Components/SectionMain.vue";
import Multiselect from '@vueform/multiselect'
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";

import {ref, watch, computed} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {debounce, pickBy} from "lodash";
import UserTable from "@/Pages/User/UserTable.vue";
import {isAdmin, isTeacher} from "@/config";

const props = defineProps({
    Users: Object,
    title: String,
    Role: {
        type: String,
        default: "Student"
    },
    filters: Object,
});
const options = computed(() => [
    {
        "label": `Active ${props.Role}`,
        "value": "without"
    },
    {
        "label": `All ${props.Role}`,
        "value": "with"
    },
    {
        "label": `Removed ${props.Role}`,
        "value": "only"
    }
]);
const url = computed(() => {
    if (props.Role === 'User') return 'admin.users.index';
    if (props.Role === 'Teacher') return 'admin.teachers.index';
    if(isTeacher()) return 'teacher.student';
    return 'admin.students.index';
});

const search = ref(props.filters?.search);
const column = ref(props.filters?.column || null);
const direction = ref(props.filters?.direction || null);
const trashed = ref(props.filters.trashed || options.value[0].value);
const sort = (name) => {
    direction.value = column.value === name && direction.value === 'ASC' ? 'DESC' : 'ASC';
    column.value = name;
};
watch([search, trashed, column, direction], debounce(() => {
    const params = pickBy({
        search: search.value,
        trashed: trashed.value,
        column: column.value,
        direction: direction.value
    });
    Inertia.get(route(url.value), params, {
        preserveState: true,
        replace: true
    })
}, 300));

const reset = () => {
    search.value = null;
    trashed.value = options.value[0].value;
    column.value = null;
    direction.value = null;
}
</script>

<template>
    <Head :title="props.title"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiTableBorder" :title="title" main/>
            <div class="flex flex-wrap items-center justify-between mb-6 sm:flex-row flex-col">
                <div class="flex flex-col-reverse sm:flex-row items-center mb-4 sm:mb-0 sm:w-3/5 w-full">
                    <div class="w-full sm:w-2/5 mt-4 sm:mt-0">
                        <Multiselect
                            class="border-red-500 w-48"
                            name="trashed"
                            v-model="trashed"
                            placeholder="Filters"
                            :options="options"></Multiselect>
                    </div>
                    <div class="flex ml-0 sm:ml-4 w-full sm:w-3/5">
                        <div class="relative text-gray-600 w-full sm:w-auto">
                            <input v-model="search" type="search" name="search" placeholder="Search"
                                   class="bg-white w-full h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                            <button type="submit" class="absolute right-0 top-0 mt-3 mr-4 pointer-events-none">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px"
                                     y="0px" viewBox="0 0 56.966 56.966"
                                     style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" width="512px"
                                     height="512px">
                              <path
                                  d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
                            </svg>
                            </button>
                        </div>
                        <button @click="reset()"
                                class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm"
                                type="button">Reset
                        </button>
                    </div>
                </div>
                <BaseButton v-if="isAdmin() && route().current('admin.students.index')" color="info" label="Create a new Student"
                            routeName="admin.students.create"/>
            </div>
            <CardBox v-if="props.Users?.data?.length > 0" class="mb-6" has-table>
                <UserTable :Users="props.Users" :Role="props.Role" @sort="sort"
                           :Query="{search, trashed, column, direction }"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty/>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
