<template>
    <Head title="Dashboard" />
    <Header title="Dashboard" />

    <main >
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div v-if="!$page.props.isCompanyProfileConfigured" class="flex p-4 mb-4 bg-blue-100 rounded-lg" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="ml-3 text-sm font-medium text-blue-700">
                You have not configured your Company Profile yet. Click on your Profile Picture > Company Profile to insert your company info.
            </div>
            <button type="button" @click="$page.props.isCompanyProfileConfigured = true" class="ml-auto -mx-1.5 -my-1.5 bg-blue-100 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        <div class="flex justify-start">
            <Link href="/lawyers" class="block m-4 p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Lawyers</p>
                <h5 class="mb-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $page.props.total_users }}</h5>
            </Link> 

            <Link href="/clerks" class="block m-4 p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Clerks</p>
                <h5 class="mb-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $page.props.total_clerks }}</h5>
            </Link>
        </div>
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