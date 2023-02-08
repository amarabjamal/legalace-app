import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
// import Datepicker from "vuejs-datepicker";
// import VueChartsCSS from "vue.charts.css";

createInertiaApp({
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link)
            .component("Head", Head)
            // .component("Datepicker", Datepicker)
            .mount(el);
    },
    title: (title) => `Legalace - ${title}`,
});

InertiaProgress.init({
    color: "green",
    showSpinner: true,
});
