<template>
    <Head title="Dashboard" />
    <Header title="Dashboard" />
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "./Shared/Layout";
import Header from "./Shared/Header";

export default { 
    components: { Head, Header },
    layout: Layout,
};
</script>