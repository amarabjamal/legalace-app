<template>
    <Head title="Company profile" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-4 py-5 sm:px-6 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-500 dark:text-gray-400 ">Company Profile</h3>
            </div>

            <div>
                <Link
                    href="/admin/company/edit"
                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    >
                    Update
                </Link>
            </div>
        </div>
        <div class="border-t border-gray-200">
        <dl>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ company.name }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ company.reg_number }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Office Address</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ company.address }}
                </dd>
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
                    {{ company.website }}
                </dd>
            </div>
        </dl>
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
            page_title: 'Company Profile',
            breadcrumbs: [
                { link: '/admin', label: 'Dashboard'},
                { link: null, label: 'Company Profile'},
            ],
        }
    },
};
</script>