lawyer/client
<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div class="flex items-center justify-between mb-6">
        <search-filter
            v-model="form.search"
            class="mr-4 w-full max-w-md"
            @reset="reset"
        ></search-filter>
        <Link class="btn-primary" :href="`/lawyer/client/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Client</span>
        </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead class="table-head">
                <tr class="table-head-row">
                    <th scope="col" class="table-head-column">Name</th>
                    <th scope="col" class="table-head-column">Email</th>
                    <th scope="col" class="table-head-column">Account</th>
                    <th scope="col" class="table-head-column">
                        Outstanding balance
                    </th>
                    <th scope="col" class="table-head-column">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="user in clients.data"
                    :key="user.id"
                    class="table-body-row"
                >
                    <td scope="row" class="table-body-data">
                        {{ user.name }}
                    </td>
                    <td scope="row" class="table-body-data">
                        {{ user.email }}
                    </td>
                    <td scope="row" class="table-body-data">
                        {{ user.account }}
                    </td>
                    <td scope="row" class="table-body-data">
                        {{ user.outstanding_balance }}
                    </td>
                    <td class="table-body-data text-right">
                        <Link
                            :href="`/clients/${user.id}/edit`"
                            class="font-medium text-blue-600 hover:underline"
                            >Edit</Link
                        >
                        <Link
                            @click="deleteUser(user)"
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
        :links="clients.links"
        :total="clients.total"
        :from="clients.from"
        :to="clients.to"
    />
</template>

<script>
import Layout from "../Shared/Layout";
import SearchFilter from "../../../Shared/SearchFilter";
import Icon from "../../../Shared/Icon";
import Pagination from "../../../Shared/Pagination";
import throttle from "lodash/throttle";
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";
import { Inertia } from "@inertiajs/inertia";
import { ref, watch } from "vue";

export default {
    setup(props) {
        let searchClients = ref(props.filters.search);

        watch(
            searchClients,
            throttle((value) => {
                Inertia.get(
                    "/clients",
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
    components: {
        SearchFilter,
        Icon,
        Pagination,
        ref,
    },
    layout: Layout,
    props: {
        clients: Object,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: "Clients",
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: null, label: "Clients" },
            ],
        };
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(`/lawyer/client`, pickBy(this.form), {
                    preserveState: true,
                });
            }, 150),
        },
    },
    methods: {
        deleteUser(client) {
            if (confirm("Are you sure you want to delete this client?")) {
                Inertia.delete(`/clients/${client.id}`);
            }
        },
        reset() {
            this.form = mapValues(this.form, () => null);
        },
    },
};
</script>
