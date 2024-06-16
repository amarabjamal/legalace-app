<template>
    <Head :title="page_title" />
    
    <page-heading :page_title="page_title" :page_subtitle="page_subtitle" :breadcrumbs="breadcrumbs"/>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-primary" :href="`/lawyer/case-files/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;File</span>
        </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr class="text-left text-sm tracking-wide font-semibold">
                    <th scope="col" class="py-4 px-6">
                        File Number
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Matter
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Type
                    </th>
                    <!-- <th scope="col" class="py-4 px-6 w-24">
                        Status
                    </th> -->
                    <th scope="col" class="py-4 px-6 w-24">
                        Conflict Check
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Client
                    </th>
                    <th scope="col" class="py-4 px-6 w-px"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="case_file in case_files.data" class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ case_file.file_number }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ case_file.matter }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ case_file.type }}
                    </td>
                    <!-- <td class="border-t px-6 py-4 whitespace-nowrap">
                        None
                    </td> -->
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        <span v-if="case_file.no_conflict_checked" class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-green-800 bg-green-200">
                            Resolved
                        </span>
                        <span v-else class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-red-800 bg-red-200"> 
                            Pending
                        </span>
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ case_file.client }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        <Link class="flex items-center px-2" :href="`/lawyer/case-files/${case_file.id}`" tabindex="-1">
                            <icon name="cheveron-right" class="block w-5 h-5 fill-gray-400" />
                        </Link>
                    </td>
                </tr>
                <tr v-if="case_files.data.length === 0">
                    <td class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No case files found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <pagination :links="case_files.links" :from="case_files.from" :to="case_files.to" :total="case_files.total"/>
</template>

<script>
import Layout from "../Shared/Layout";
import SearchFilter from '../../../Shared/SearchFilter';
import Icon from '../../../Shared/Icon';
import Pagination from '../../../Shared/Pagination';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues';

export default { 
    components: {
        SearchFilter,
        Icon,
        Pagination,
    },
    layout: Layout,
    props: {
        filters: Object,
        case_files: Object,
    },
    data() {
        return {
            page_title: 'My Cases',
            page_subtitle: 'Manage your case files.',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: null, label: 'My Cases'},
            ],
            form: {
                search: this.filters.search,
            },
        }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            this.$inertia.get(`/lawyer/case-files`, pickBy(this.form), {preserveState: true})
          }, 150),
        },
    },
    methods: {
        reset() {
          this.form = mapValues(this.form, () => null);
        },
    },
};
</script>