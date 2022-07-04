<template>
    <Nav />
    <slot />
</template>

<script>
import Nav from "./Nav";

export default {
    components: { Nav },
};
</script>