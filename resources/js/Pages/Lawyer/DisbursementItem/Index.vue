<template>
    <Head title="Disbursement Item" />

    <h1 class="mb-6 text-xl font-bold">
        <Link class="text-blue-500 hover:text-blue-600" href="/lawyer/casefiles/">Case Files</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}`">{{ case_file.file_number }}</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <span class="font-medium">Disbursement Items</span>
    </h1>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-indigo" :href="`/lawyer/casefiles/${case_file.id}/disbursement-items/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Item</span>
        </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
          <tr class="text-left text-sm tracking-wide font-semibold">
            <th class="w-24 py-4 px-6">Date</th>
            <th class="w-32 py-4 px-6">Record Type</th>
            <th class="py-4 px-6">Name</th>
            <th class="py-4 px-6">Desc</th>
            <th class="w-32 py-4 px-6">Amount</th>
            <th class="w-24 py-4 px-6">Status</th>
            <th class="w-24 py-4 px-6">Fund Type</th>
            <th class="py-4 px-6"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in disbursement_items.data" :key="item.id" class="text-sm tetx-gray-700 hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ item.date }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              {{ item.record_type.name }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap ">
              {{ item.name }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              <span v-if="item.desc == null" class="text-gray-400">None</span>
              {{ item.desc }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap text-right">
              {{ item.amount }}
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              <span class="p-1.5 text-xs font-medium uppercase tracking-wider text-gray-800 bg-gray-200 rounded-lg bg-opacity-50">
                {{ item.status }}
              </span>
            </td>
            <td class="border-t px-6 py-4 whitespace-nowrap">
              <span class="text-xs font-medium uppercase tracking-wide">
                {{ item.fund_type }}
              </span>
            </td>
            <td class="w-px border-t px-6 py-4 whitespace-nowrap">
              <Link class="flex items-center px-2" :href="`/lawyer/casefiles/${case_file.id}/disbursement-items/${item.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-5 h-5 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr>
            <td v-if="disbursement_items.data.length === 0" class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No items found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- {{ disbursement_items }} -->
</template>

<script>
import Layout from '../Shared/Layout';
import SearchFilter from '../../../Shared/SearchFilter';
import Icon from '../../../Shared/Icon';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues';


export default {
    components: {
        SearchFilter,
        Icon
    },
    layout: Layout,
    props: {
        filters: Object,
        case_file: Object,
        disbursement_items: Object,
    },
    data() {
      return {
        form: {
            search: this.filters.search,
        },
      }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            this.$inertia.get(`/lawyer/casefiles/${this.case_file.id}/disbursement-items`, pickBy(this.form), {preserveState: true})
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