<template>
    <Head title="Dashboard" />
    <Header title="Dashboard" />

    <main >
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        Lawyer Dashboard
      </div>
    </main>
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