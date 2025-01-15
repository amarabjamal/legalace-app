<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <slot name="header"></slot>
                <button
                    @click="$emit('close')"
                    class="text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
            </div>
            <div class="mb-4">
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AddCostModal",
};
</script>
