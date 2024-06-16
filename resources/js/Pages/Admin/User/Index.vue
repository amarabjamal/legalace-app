<template>
    <Head title="Employees" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" placeholder="Search employee" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-primary" :href="`/admin/users/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Employee</span>
        </Link>
    </div>

    <!-- Employee Table -->
    <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
        <thead class="table-head">
            <tr class="table-head-row">
                <th class="table-head-column">Employee</th>
                <th class="table-head-column w-36">Employee ID</th>
                <th class="table-head-column w-24">Access</th>
                <th class="table-head-column w-40">Email</th>
                <th class="table-head-column w-24">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users.data" :key="user.id" class="table-body-row">
                <td class="table-body-data">
                    <div class="flex items-center text-sm">
                    <!-- Avatar with inset shadow -->
                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                        <img class="object-cover w-full h-full rounded-full" :src="'/images/profileImage/default.png'" alt="" loading="lazy">
                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                    </div>
                    <div>
                        <p class="font-semibold">{{ user.name }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                         {{ user.roles }}
                        </p>
                    </div>
                    </div>
                </td>
                <td class="table-body-data">
                    {{ user.employee_id }}
                </td>
                <td class="table-body-data">
                    <span v-if="user.enabled" class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-green-800 bg-green-200">
                        Enabled
                    </span>
                    <span v-else class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-red-800 bg-red-200">
                        Disabled
                    </span>
                </td>
                <td class="table-body-data">
                    {{ user.email }}
                </td>
                <td class="table-body-data">
                    <div class="flex items-center space-x-4 text-sm">
                        <Link :href="`/admin/users/${ user.id }/edit`" class="font-medium hover:text-blue-600"><PencilIcon class="inline-block h-5 w-5"/></Link>
                        <Link @click="deleteUser(user)" as="button" class="ml-3 font-medium hover:text-red-600"><TrashIcon class="inline-block h-5 w-5"/></Link>
                    </div>
                </td>
            </tr>
            <tr v-if="users.data.length === 0">
                    <td class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No records found.</td>
                </tr>
        </tbody>
        </table>
    </div>

    <pagination v-if="users.data.length > 0"  :links="users.links" :total="users.total" :from="users.from" :to="users.to"/>
</template>

<script>
import Layout from "../Shared/Layout";
import Pagination from '../../../Shared/Pagination';
import { PencilIcon, TrashIcon } from "@heroicons/vue/outline";
import SearchFilter from '../../../Shared/SearchFilter';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues';

export default { 
    components: { 
        Pagination, 
        PencilIcon, 
        TrashIcon,
        SearchFilter,
    },
    layout: Layout,
    props: { 
        filters: Object,
        users: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: 'Employees',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Employees'},
            ],
        }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            this.$inertia.get(`/admin/users`, pickBy(this.form), {preserveState: true})
          }, 150),
        },
    },
    methods: {
        deleteUser(user) {
            if (confirm('Are you sure you want to delete this user account?')) {
                this.$inertia.delete(`/admin/users/${ user.id }`);
            }
        },
        reset() {
          this.form = mapValues(this.form, () => null);
        },
    },
};
</script>