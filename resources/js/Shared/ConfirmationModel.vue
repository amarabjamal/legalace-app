<template>
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold mb-4">
                Are you sure you want to delete this record?
            </h3>
            <div class="flex justify-end space-x-4">
                <button
                    @click="confirmDelete"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    Yes, Delete
                </button>
                <button
                    @click="cancelDelete"
                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showModal: Boolean,
    },
    methods: {
        confirmDelete() {
            this.$emit("confirm");
        },
        cancelDelete() {
            this.$emit("cancel");
        },
    },
};
</script>
