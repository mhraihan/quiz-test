import { defineStore } from "pinia";
import axios from "axios";
import {notify} from "notiwind";

const success = {
    group: "notification",
    type: "success",
    title: "Success"
};

const errors = {
    group: "notification",
    type: "error",
    title: "Error",
    text: 'Something went wrong'
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
      create(data, url)  {
          data
              .post(route(url), {
                  onSuccess: () => {
                      notify(success, 4000)
                  },
                  onError: () => {
                      notify(errors, 4000)
                  }
              });
      },
      update(data, url)  {
          data
              .post(route(url, data.id), {
                  onSuccess: () => {
                      notify(success, 4000)
                  },
                  onError: () => {
                      notify(errors, 4000)
                  }
              });
      },
      destroy(data, url)  {
          data
              .delete(route(url, data.id), {
                  onSuccess: () => {
                      data.deleted_at = data.deleted_at || new Date();
                      notify(success, 4000)
                  },
                  onError: () => {
                      notify(errors, 4000)
                  }
              });
      },
      restore(data, url) {
          data.put(route(url, data.id), {
              onSuccess: () => {
                  data.deleted_at = null;
                  notify(success, 4000)
              },
              onError: () => {
                  notify(errors, 4000)
              }
          })
      }
  },
});
