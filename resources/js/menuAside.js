import {
    mdiMonitor,
    mdiTrashCan,
    mdiTable,
    mdiViewList,
    mdiMarker,
} from "@mdi/js";

export default [
    {
        route: "dashboard",
        icon: mdiMonitor,
        label: "Dashboard",
    },
    {
        label: "Questions",
        icon: mdiViewList,
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
        menu: [
            {
                route: "admin.questions.index",
                icon: mdiTable,
                label: "All Topics",
            },
            {
                route: "admin.questions.create",
                icon: mdiTable,
                label: "Create Topic",
            },
        ],
    },

];
