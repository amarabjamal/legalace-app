<template>
    <Head title="Manage users" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="" />
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Manage User Accounts
                </h2>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-blue-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-blue-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-blue-900">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-blue-100 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                
                <div class="flex justify-between items-center mb-3">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            v-model="searchUsers"
                            type="text" 
                            class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-800 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Search">
                    </div>
                    
                    <Link 
                        href="/users/create" 
                        class="h-min text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
                        >
                        Register New User
                    </Link>
                </div>

                <div class="relative overflow-x-auto shadow-md">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Identification Number
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Employee ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white  dark:bg-gray-800">
                            <tr 
                                v-for="user in users.data"
                                :key="user.id"
                                class="border-b dark:border-b-gray-700 text-gray-700 dark:text-gray-400"
                            >
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ user.name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ user.id_num }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ user.employee_id }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ user.email }}
                                </th>
                                <td class="px-6 py-4">
                                    <Link :href="`/users/${ user.id }/edit`" class="font-medium hover:text-blue-600"><PencilIcon class="inline-block h-5 w-5"/></Link>
                                    <Link @click="deleteUser(user)" as="button" class="ml-3 font-medium hover:text-red-600"><TrashIcon class="inline-block h-5 w-5"/></Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginator -->
                <Pagination :links="users.links" :total="users.total" :from="users.from" :to="users.to"/>
                <!-- Main Content End -->
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Header from "../Shared/Header";
import Sidebar from "../Shared/Sidebar";
import Pagination from "../Shared/Pagination";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import { PencilIcon, TrashIcon } from "@heroicons/vue/outline";

export default { 
    setup(props) {
        let searchUsers = ref(props.filters.search);

        watch(searchUsers, throttle(value => {
            Inertia.get('/users', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchUsers };
    },
    props: { 
        users: Object,
        filters: Object,
        message: String,
     },
    methods: {
        deleteUser(user) {
            if (confirm('Are you sure you want to delete this user account?')) {
                Inertia.delete(`/users/${ user.id }`);
            }
        }
    },
    components: { Head, Header, Sidebar, Pagination, ref, PencilIcon, TrashIcon },
    layout: Layout,
};
</script>