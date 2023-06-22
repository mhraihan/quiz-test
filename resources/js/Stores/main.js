import {defineStore} from "pinia";
import {notify} from "notiwind";

const success = {
    group: "notification",
    type: "success",
    title: "Success"
};

const error = {
    group: "notification",
    type: "error",
    title: "Error",
    text: 'Something went wrong'
};
const notifications = {
    success,
    error
}
const showNotification = notification => {
    for (const property in notification) {
        if (notification[property]) {
            notify(notifications[property], 4000)
        }
    }
    history.pushState(null, null, window.location.pathname);
}
export const useMainStore = defineStore("main", {
    state: () => ({
        /* User */
        userName: null,
        userEmail: null,
        userAvatar: null,

        /* Field focus with ctrl+k (to register only once) */
        isFieldFocusRegistered: false,

        /* Sample data (commonly used) */
        clients: [],
        history: [],
    }),
    actions: {
        setUser(payload) {
            if (payload.name) {
                this.userName = payload.name;
            }
            if (payload.email) {
                this.userEmail = payload.email;
            }
            if (payload.avatar) {
                this.userAvatar = payload.avatar;
            }
        },
        create(data, url) {
            data
                .post(route(url), {
                    onSuccess: (data) => {
                        showNotification(data?.props?.flash)
                    },
                    onError: () => {
                        notify(error, 4000)
                    }
                });
        },
        update(data, url, method = 'POST') {
            data[method.toLowerCase()](route(url, data.id), {
                onSuccess: (data) => {
                    showNotification(data?.props?.flash)
                },
                onError: () => {
                    notify(error, 4000)
                }
            })
            ;
        },
        destroy(data, url) {
            data
                .delete(route(url, data.id), {
                    onSuccess: (response) => {
                        data.deleted_at = data.deleted_at || new Date();
                        showNotification(response?.props?.flash)
                    },
                    onError: () => {
                        notify(error, 4000)
                    }
                });
        },
        restore(data, url) {
            data.put(route(url, data.id), {
                onSuccess: (response) => {
                    data.deleted_at = null;
                    showNotification(response?.props?.flash)
                },
                onError: () => {
                    notify(error, 4000)
                }
            })
        }
    },
});
