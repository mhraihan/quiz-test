import {
    mdiMonitor,
    mdiTrashCan,
    mdiTable,
    mdiViewList,
    mdiMarker,
    mdiBookHeartOutline,
    mdiTableQuestion,
} from "@mdi/js";
import {userAdmin, userAdminStudent, UserEnum} from "@/config";

export default [
    {
        route: "dashboard",
        icon: mdiMonitor,
        label: "Dashboard",
        roles: userAdmin(),
    },
    {
        icon: mdiTable,
        label: "Schools",
        roles: userAdmin(),
        menu: [
            {
                route: "admin.schools.index",
                icon: mdiTable,
                label: "All Schools",
            },
            {
                route: "admin.schools.create",
                icon: mdiTable,
                label: "Create School",
            }
        ],
    },
    {
        label: "Questions",
        icon: mdiViewList,
        roles: userAdmin(),
        menu: [
            {
                route: "admin.questions.index",
                icon: mdiTable,
                label: "All Questions",
            },
            {
                route: "admin.questions.create",
                icon: mdiTable,
                label: "Create Question",
            },
            {
                route: "admin.questions.trash",
                icon: mdiTrashCan,
                label: "Trash",
            },
        ],
    },
    {
        label: "Topics",
        icon: mdiMarker,
        roles: userAdmin(),
        menu: [
            {
                route: "admin.topics.index",
                icon: mdiTable,
                label: "All Topics",
            },
            {
                route: "admin.topics.create",
                icon: mdiTable,
                label: "Create Topic",
            },
        ],
    },
    {
        label: "Students",
        icon: mdiViewList,
        roles: userAdmin(),
        menu: [
            {
                route: "admin.students.index",
                icon: mdiTable,
                label: "All Students",
            },
            {
                route: "admin.students.create",
                icon: mdiTable,
                label: "Create Student",
            },
            // {
            //     route: "admin.students.trash",
            //     icon: mdiTrashCan,
            //     label: "Trash",
            // },
        ],
    },
    {
        route: "quiz.index",
        icon: mdiTableQuestion,
        label: "Quiz",
        roles: userAdminStudent(),
    },
    {
        route: "results.index",
        icon: mdiBookHeartOutline,
        label: "Result",
        roles: userAdminStudent(),
    },
    {
        route: "teacher.index",
        icon: mdiTableQuestion,
        label: "Dashboard",
        roles: [UserEnum.TEACHER],
    },
];
