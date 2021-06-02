import { createRouter } from "vue-router";

// Define the routes to be used by the router
const routes = [{
        path: "/chat",
        name: "chat",
        component: () =>
            import ( /* webpackChunkName : "chat" */ "../views/chat_page.vue")
    },

]

//create the router instance
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    mode: "history",
    routes, // short for `routes: routes`
})

export default router;