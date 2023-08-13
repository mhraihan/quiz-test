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
const handleRequest = (data, url, method, successCallback, errorCallback) => {
    data[method.toLowerCase()](route(url, data.id), {
        onSuccess: (response) => {
            successCallback(response);
            showNotification(response?.props?.flash);
        },
        onError: (errorResponse) => {
            // console.log(errorResponse)

            errorCallback(errorResponse);
            const errorMessage = errorResponse?.response?.data?.message || 'Something went wrong';
            const updatedError = { ...error, text: errorMessage };
            notify(updatedError, 4000);
        }
    });
};
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
        handleRequest(data, url, 'POST', () => {}, () => {});
    },
    createSync(data, url) {
        return new Promise((resolve, reject) => {
            handleRequest(data, url, 'POST', resolve, reject);
        });
    },
    update(data, url, method = 'POST') {
        handleRequest(data, url, method, () => {}, () => {});
    },
    updateSync(data, url, method = 'POST') {
        return new Promise((resolve, reject) => {
            handleRequest(data, url, method, resolve, reject);
        });
    },
    destroy(data, url) {
        handleRequest(data, url, 'DELETE', () => {
            data.deleted_at = data.deleted_at || new Date();
        }, () => {});
    },
    destroySync(data, url) {
        return new Promise((resolve, reject) => {
            handleRequest(data, url, 'DELETE', (response) => {
                data.deleted_at = data.deleted_at || new Date();
                resolve(response);
            }, reject);
        });
    },
    restore(data, url) {
        handleRequest(data, url, 'PUT', () => {
            data.deleted_at = null;
        }, () => {});
    },
    restoreSync(data, url) {
        return new Promise((resolve, reject) => {
            handleRequest(data, url, 'PUT', (response) => {
                data.deleted_at = null;
                resolve(response);
            }, reject);
        });
    }
},


});
