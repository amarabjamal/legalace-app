import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { Inertia } from '@inertiajs/inertia'
import Icon from './Shared/Icon';
import PageHeading from './Shared/PageHeading'

Inertia.on('success', (event) => {
  let isAuthenticated = event.detail.page.props.auth !== null;
  window.localStorage.setItem('isAuthenticated', isAuthenticated);
})

window.addEventListener('popstate', (event) => {
  if(window.localStorage.getItem('isAuthenticated') === 'false') {
    event.stopImmediatePropagation();
    window.location.replace('/login');
  }
})

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
      const app = createApp({ render: () => h(App, props) })
        .use(plugin)
        .component("Link", Link)
        .component("Head", Head)
        .component("Icon", Icon)
        .component("PageHeading", PageHeading);

      app.config.globalProperties.$filters = {
        currency(value) {
          if (typeof value !== "number") {
            return value;
          }
          var formatter = new Intl.NumberFormat('en-MY', {
              style: 'currency',
              currency: 'MYR'
          });
          return formatter.format(value);
          },
      }

      app.mount(el)
  },
  title: (title) => title ? `${title} | Legal Ace - Easy Legal Firm Accounting` : 'Legal Ace',
})

InertiaProgress.init({
    color: 'red',
    showSpinner: false,
})