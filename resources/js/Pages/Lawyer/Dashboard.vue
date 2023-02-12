<template>
    <Head title="Dashboard" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Lawyer Dashboard
                </h2>
    
                {{ $page.props.company }} Employee, <br/><br/>
                Hello, {{ $page.props.user.name }} (Role: {{  $page.props.role[0] }}).
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "./Shared/Layout";

export default { 
    components: { Head },
    layout: Layout,
};
</script>