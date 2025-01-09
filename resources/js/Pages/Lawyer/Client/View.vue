<template>
    <Head title="Client Profile" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div
        class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden"
    >
        <dl>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.name }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.id_number }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.email }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.phone_number }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">
                    Company Address
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.company_address }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.company_name }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">
                    Client Account
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ clientProfile.linked_client_account }}
                </dd>
            </div>
        </dl>

        <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
            <!-- <Link as="button" :href="`/admin/bank-accounts/${bank_account.id}/edit`" class="btn-primary">
                Edit
            </Link>  -->
            <Link v-on:click="goBack()" as="button" class="btn-cancel">
                Back
            </Link>
        </div>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from "../../../Shared/TextInput";
import SelectInput from "../../../Shared/SelectInput";
import DateInput from "../../../Shared/DateInput";
import LoadingButton from "../../../Shared/LoadingButton";
import FileInput from "../../../Shared/FileInput";
import { Switch } from "@headlessui/vue";
import { Head } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        LoadingButton,
        FileInput,
        Switch,
        Head,
    },
    layout: Layout,
    props: {
        clientProfile: Object,
    },
    data() {
        return {
            page_title: "Client Profile",
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: "/lawyer/client", label: "Clients" },
                { link: null, label: "View" },
            ],
        };
    },
    methods: {
        store() {
            if (this.form.isDirty) {
                this.form.post("/lawyer/firm-accounts");
            } else {
                alert("You need to fill in the form first.");
            }
        },
        goBack() {
            window.history.go(-1);
        },
    },
};
</script>
