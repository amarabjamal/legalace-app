<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <dl>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ company.name }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Office Address</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ company.address }}</dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ company.email }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Website URL</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a class="text-blue-700 hover:underline" target="_blank" :href="company.website">
                        {{ company.website }}
                    </a>
                </dd>
            </div>
        </dl>

        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <Link as="button" href="/admin/company/edit" class="btn-primary">
                Edit
            </Link> 
        </div>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";

export default { 
    layout: Layout,
    props: { 
        company: Object,
    },
    data() {
        return {
            page_title: 'Company',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Company'},
            ],
        }
    },
};
</script>