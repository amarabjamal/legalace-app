<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="flex justify-between items-end mb-3">
        <div class="relative">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input 
                v-model="searchClients"
                type="text" 
                class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Search Clients...">
        </div>
        
        <Link 
            href="/clients/create" 
            class="h-min text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
            >
            New client
        </Link>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ID Num
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Phone num
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    v-for="user in clients.data"
                    :key="user.id"
                    class="bg-white border-b"
                >
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ user.name }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ user.id_num }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ user.email }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ user.phone_num }}
                    </th>
                    <td class="px-6 py-4 text-right">
                        <Link :href="`/clients/${ user.id }/edit`" class="font-medium text-blue-600 hover:underline">Edit</Link>
                        <Link @click="deleteUser(user)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Paginator -->
    <Pagination :links="clients.links" :total="clients.total" :from="clients.from" :to="clients.to"/>
</template>

<script>
import Layout from "../Shared/Layout";
import Pagination from "../../../Shared/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import { ref, watch } from "vue";


export default { 
    setup(props) {
        let searchClients = ref(props.filters.search);

        watch(searchClients, throttle(value => {
            Inertia.get('/clients', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchClients };
    },
    components: { 
        Pagination, 
        ref,
    },
    layout: Layout,
    props: { 
        clients: Object,
        filters: Object
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: 'Clients',
            breadcrumbs: [
                { link: '/lawyer', label: 'Dashboard'},
                { link: null, label: 'Clients'},
            ],
        }
    },
    methods: {
        deleteUser(client) {
            if (confirm('Are you sure you want to delete this client?')) {
                Inertia.delete(`/clients/${ client.id }`);
            }
        }
    },
};
</script>