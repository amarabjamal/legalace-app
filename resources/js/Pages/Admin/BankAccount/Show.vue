<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <dl>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Label</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.label }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.account_type }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.account_name }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.bank_name }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.account_number }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Opening Balance</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.opening_balance }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">SWIFT Code</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.swift_code }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Bank Address</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ bank_account.bank_address }}</dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Creator</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ bank_account.created_by }}
                </dd>
            </div>
        </dl>

        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <Link as="button" :href="`/admin/bank-accounts/${bank_account.id}/edit`" class="btn-primary">
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
        bank_account: Object,
    },
    data() {
        return {
            page_title: this.bank_account.label,
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/bank-accounts', label: 'Bank Accounts'},
                { link: null, label: this.bank_account.label},
            ],
        }
    },
};
</script>