<template>
    <Head title="Operational Cost" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div
        class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden"
    >
        <dl>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ costs_item.date }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Details</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ formatString(costs_item.details) }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">
                    Document Number
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ costs_item.document_number }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Document</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <Link
                        v-if="
                            costs_item.upload != null && costs_item.upload != ''
                        "
                        class="text-blue-700 btn btn-blue-500 hover:btn-blue-700 transition duration-300 ease-in-out hover:shadow-md hover:shadow-blue-500/50"
                        :href="`/lawyer/costs_item/download/${costs_item.id}`"
                        @click.prevent="downloadFile(costs_item.id)"
                        >Download Document</Link
                    >
                    <p v-else>No Document was uploaded</p>
                    <!-- {{ firmAccounts.upload }} -->
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    RM {{ formatToTwoDecimal(costs_item.amount) }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">
                    Payment Method
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ formatString(costs_item.payment_method) }}
                </dd>
            </div>
            <!-- <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ costs_item.company_name }}
                </dd>
            </div>
            <div
                class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
            >
                <dt class="text-sm font-medium text-gray-500">
                    Client Account
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ costs_item.linked_client_account }}
                </dd>
            </div> -->
        </dl>

        <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
            <Link v-on:click="goBack()" as="button" class="btn-cancel">
                Back
            </Link>
            <Link
                :href="`/lawyer/operational-cost/${costs_item.id}/edit`"
                class="font-medium text-blue-600 hover:underline btn-primary"
                >Edit</Link
            >
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
import axios from "axios";

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
        costs_item: Object,
    },
    data() {
        return {
            page_title: "Operational Cost",
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                {
                    link: "/lawyer/operational-cost",
                    label: "Operational Costs",
                },
                {
                    link: null,
                    label: this.formatString(this.costs_item.details),
                },
                { link: null, label: "View" },
            ],
        };
    },
    methods: {
        goBack() {
            // window.history.go(-1);
            this.$inertia.visit("/lawyer/operational-cost");
        },
        formatString(str) {
            return str
                .split("_") // Split by underscores
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(" "); // Join the words with spaces
        },
        formatToTwoDecimal(num) {
            if (num == null) {
                return "0.00";
            } else {
                return num.toFixed(2); // Formats the number to 2 decimal places
            }
        },
        downloadFile(id) {
            axios
                .get(`/lawyer/costs_item/download/${id}`, {
                    responseType: "blob",
                })
                .then((response) => {
                    const file = new Blob([response.data], {
                        type: response.headers["content-type"],
                    });
                    const fileUrl = URL.createObjectURL(file);
                    const a = document.createElement("a");
                    a.href = fileUrl;
                    a.download = response.headers["content-disposition"]
                        .split("filename=")[1]
                        .trim('"');
                    a.click();
                    URL.revokeObjectURL(fileUrl);
                })
                .catch((error) => {
                    console.error(`Error downloading file: ${error.message}`);
                    // Prevent the redirect
                    window.history.go(-1);
                    alert(`Error downloading file: ${error.message}`);
                });
        },
    },
};
</script>
