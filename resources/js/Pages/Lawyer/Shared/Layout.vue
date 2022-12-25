<template>
    <div class="bg-gray-200 min-h-screen">
        <Nav />
        <slot />
    </div>
</template>

<script>
import Nav from "./Nav";

export default {
    components: { Nav },
};
</script>