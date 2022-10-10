import { mdiAccount, mdiLogout } from "@mdi/js";

export default [
    {
        isCurrentUser: true,
        menu: [
            {
                icon: mdiAccount,
                label: "My Profile",
                route: "profile",
            },

            {
                isDivider: true,
            },
            {
                icon: mdiLogout,
                label: "Log Out",
                isLogout: true,
            },
        ],
    },
];
