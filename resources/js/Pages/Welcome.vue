<template>
    <h1>Hello, {{ name }}</h1>
</template>

<script>
    export default {
        props: {
            name: String,
        }
    };
</script>

