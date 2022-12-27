<template>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen}">
        <slot />
    </div>
</template>

<script>

export default {
    components: {  },
};
</script>