<template>
    <Head title="Operational Cost" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            <div class="container px-6 mx-auto grid">
                <h2 class="my-6 text-2xl font-semibold">
                    Operational Cost/Bills
                </h2>

                <!-- <h4 class="my-6 text-2xl font-semibold">Recurring</h4>

                <div class="flex items-center mb-4">
                    <Link href="/lawyer/operational-cost/create">
                        <button
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue"
                        >
                            Add Expense
                        </button>
                    </Link>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">DATE</th>
                                <th scope="col" class="px-6 py-3">
                                    DESCRIPTION
                                </th>
                                <th scope="col" class="px-6 py-3">ACCOUNT</th>
                                <th scope="col" class="px-6 py-3">AMOUNT</th>
                                <th scope="col" class="px-6 py-3">
                                    Balance
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    ACTION
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="cost in recurring.data"
                                :key="cost.id"
                                class="bg-white border-b"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.date }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.details }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.label }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.amount }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ cost.balance }}
                                </th>
                                <td class="px-6 py-4 text-left">
                                    <Link
                                        :href="`/lawyer/operational-cost/${cost.id}/edit`"
                                        class="font-medium text-blue-600 hover:underline"
                                        >Edit</Link
                                    >
                                    <Link
                                        @click="deleteAcc(cost)"
                                        as="button"
                                        class="ml-3 font-medium text-red-600 hover:underline"
                                        >Delete</Link
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                Paginator
                <Pagination
                    :links="recurring.links"
                    :total="recurring.total"
                    :from="recurring.from"
                    :to="recurring.to"
                /> -->
            </div>

            <div class="container px-6 mx-auto grid">
                <!-- <h4 class="my-6 text-2xl font-semibold">Non-Recurring</h4> -->

                <div class="flex items-center mb-4">
                    <Link href="/lawyer/operational-cost/create">
                        <button
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue"
                        >
                            Add Expense
                        </button>
                    </Link>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">DATE</th>
                                <th scope="col" class="px-6 py-3">
                                    DESCRIPTION
                                </th>
                                <th scope="col" class="px-6 py-3">ACCOUNT</th>
                                <th scope="col" class="px-6 py-3">AMOUNT</th>
                                <!-- <th scope="col" class="px-6 py-3">
                                    Balance
                                </th> -->
                                <th scope="col" class="px-6 py-3">
                                    ACTION
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="cost in non_recurring.data"
                                :key="cost.id"
                                class="bg-white border-b"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.date }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.details }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ cost.label }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ formatToTwoDecimal(cost.amount) }}
                                </th>
                                <!-- <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ cost.balance }}
                                </th> -->
                                <td class="px-6 py-4 text-left">
                                    <Link
                                        :href="`/lawyer/operational-cost/${cost.id}/edit`"
                                        class="font-medium text-blue-600 hover:underline"
                                        >Edit</Link
                                    >
                                    <Link
                                        @click="deleteAcc(cost)"
                                        as="button"
                                        class="ml-3 font-medium text-red-600 hover:underline"
                                        >Delete</Link
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Paginator -->
                <Pagination
                    :links="non_recurring.links"
                    :total="non_recurring.total"
                    :from="non_recurring.from"
                    :to="non_recurring.to"
                />
            </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../../../Shared/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from "lodash/throttle";
import { ref, watch } from "vue";

export default {
    setup(props) {
        let searchClients = ref(props.filters.search);

        watch(
            searchClients,
            throttle((value) => {
                Inertia.get(
                    "/operational-cost",
                    { search: value },
                    {
                        preserveState: true,
                        replace: true,
                    },
                );
            }, 500),
        );

        return { searchClients };
    },
    props: {
        recurring: Object,
        non_recurring: Object,
        filters: Object,
    },
    components: { Head, Pagination, ref },
    layout: Layout,
    methods: {
        deleteAcc(acc) {
            if (confirm("Are you sure you want to delete this cost?")) {
                Inertia.delete(`/lawyer/operational-cost/${acc.id}`);
            }
        },
        formatToTwoDecimal(num) {
            if (num == null) {
                return "0.00";
            } else {
                return num.toFixed(2); // Formats the number to 2 decimal places
            }
        },
    },
};
</script>
