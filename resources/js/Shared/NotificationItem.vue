<template>
  <div class="w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-0 ring-opacity-[.05] p-4">
        <div class="flex items-start">
            <div class="shrink-0">
                <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.478 5.559A1.5 1.5 0 016.912 4.5H9A.75.75 0 009 3H6.912a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H15a.75.75 0 000 1.5h2.088a1.5 1.5 0 011.434 1.059l2.213 7.191H17.89a3 3 0 00-2.684 1.658l-.256.513a1.5 1.5 0 01-1.342.829h-3.218a1.5 1.5 0 01-1.342-.83l-.256-.512a3 3 0 00-2.684-1.658H3.265l2.213-7.191z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v6.44l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 011.06-1.06l1.72 1.72V3a.75.75 0 01.75-.75z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ notification.body }}</p>
                <div v-if="notification.action_link" class="mt-3 flex">
                    <Link :href="notification.action_link" as="button" class="text-sm font-medium text-blue-600">View</Link>
                </div>
            </div>
            <div class="ml-4 flex shrink-0">
                <button @click="removeNotification()" class="inline-flex text-gray-400">
                    <span class="sr-only">
                    Close
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"></path></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  props: {
    notification: Object,
  },
  methods: {
    removeNotification() {
      this.$emit('remove-notification');
    }
  },
  mounted() {
    setTimeout(this.removeNotification, 4000);
  }
}
</script>