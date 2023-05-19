import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { Inertia } from '@inertiajs/inertia'
import Icon from './Shared/Icon';

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
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component("Link", Link)
      .component("Head", Head)
      .component("Icon", Icon)
      .mount(el)
  },
  title: (title) => title ? `${title} - Legal Ace` : 'Legal Ace',
})

InertiaProgress.init({
    color: 'red',
    showSpinner: true,
})