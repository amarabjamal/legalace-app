<template>
    <Head title="Invoices" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-primary" :href="`/lawyer/case-files/${case_file.id}/invoices/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Invoice</span>
        </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
          <tr class="text-left text-sm tracking-wide font-semibold">
              <th class="w-32 py-4 px-6">Created At</th>
            <th class="w-32 py-4 px-6">Invoice Number</th>
            <th class="w-32 py-4 px-6">Invoice Date</th>
            <th class="w-32 py-4 px-6">Due Date</th>
            <th class="w-24 py-4 px-6">Status</th>
            <th class="w-24 py-4 px-6 text-right">Amount</th>
            <th class="w-24 py-4 px-6">Creator</th>
            <th class="py-4 px-6"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices.data" :key="invoice.id" class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ invoice.created_at }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ invoice.invoice_number }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ invoice.issued_at }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ invoice.due_at }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap ">
              <span v-if="invoice.status === 'Draft'" class="p-1.5 text-xs font-medium uppercase tracking-wider text-gray-800 bg-gray-200 rounded-lg bg-opacity-50">
                {{ invoice.status }}
              </span>
              <span v-else-if="invoice.status === 'Open'" class="p-1.5 text-xs font-medium uppercase tracking-wider text-blue-800 bg-blue-200 rounded-lg bg-opacity-50">
                {{ invoice.status }}
              </span>
              <span  v-else-if="invoice.status === 'Paid'" class="p-1.5 text-xs font-medium uppercase tracking-wider text-green-800 bg-green-200 rounded-lg bg-opacity-50">
                {{ invoice.status }}
              </span>
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap text-right tabular-nums">
              {{ invoice.total }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap ">
              {{ invoice.created_by.name }}
            </td>
            <td class="w-px border-t px-6 py-4 whitespace-nowrap">
              <Link class="flex items-center px-2" :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}`" tabindex="-1">
                <icon name="cheveron-right" class="block w-5 h-5 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr>
            <td v-if="invoices.data.length === 0" class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No records found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <pagination :links="invoices.links" :from="invoices.from" :to="invoices.to" :total="invoices.total"/>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
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
        Pagination
    },
    layout: Layout,
    props: {
        filters: Object,
        case_file: Object,
        invoices: Object,
    },
    data() {
      return {
        form: {
            search: this.filters.search,
        },
        page_title: 'Invoices',
        breadcrumbs: [
            { link: '/lawyer/dashboard', label: 'Lawyer'},
            { link: '/lawyer/case-files/', label: 'My Cases'},
            { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
            { link: null, label: 'Invoices'},
        ],
      }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            this.$inertia.get(`/lawyer/case-files/${this.case_file.id}/invoices`, pickBy(this.form), {preserveState: true})
          }, 150),
        },
    },
    methods: {
        reset() {
          this.form = mapValues(this.form, () => null);
        },
    },
}

</script>