<template>
    <div>
      <H1 class="text-6xl font-semibold text-gray-700 dark:text-gray-200">{{ title }}</H1>
      <div class="text-gray-700 dark:text-gray-300">{{ description }}</div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      status: Number,
    },
    computed: {
      title() {
        return {
          503: '503: Service Unavailable',
          500: '500: Server Error',
          404: '404: Page Not Found',
          403: '403: Forbidden',
        }[this.status]
      },
      description() {
        return {
          503: 'Sorry, we are doing some maintenance. Please check back soon.',
          500: 'Whoops, something went wrong on our servers.',
          404: 'Sorry, the page you are looking for could not be found.',
          403: 'Sorry, you are forbidden from accessing this page.',
        }[this.status]
      },
    },
  }
  </script>