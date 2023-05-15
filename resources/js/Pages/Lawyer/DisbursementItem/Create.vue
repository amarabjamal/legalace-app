<template>
    <Head title="Disbursement Item" />

    <h1 class="mb-6 text-xl font-bold">
        <Link class="text-blue-500 hover:text-blue-600" href="/lawyer/casefiles/">Case Files</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}`">{{ case_file.file_number }}</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}/disbursement-items`">Disbursement Items</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <span class="font-medium">Create</span>
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="Date"/>
                <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="Name"/>
                <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="Description"/>
                <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="Amount"/>
                <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="Receipt"/>
                <select-input class="pb-8 pr-6 w-full lg:w-1/2" label="Record Type">
                    <option :value="null" />
                    <option value="CA">Canada</option>
                    <option value="US">United States</option>
                </select-input>
                <select-input class="pb-8 pr-6 w-full lg:w-1/2" label="Fund Type">
                    <option :value="null" />
                    <option value="CA">Canada</option>
                    <option value="US">United States</option>
                </select-input>

                <div class="pb-8 pr-6 w-full lg:w-1/2">
                    <label class="form-label">Amount</label>
                    <input v-model.lazy="form.amount" v-money="moneyConfig" class="form-input text-right"/>
                </div>
            </div>
            <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Item</loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';
import { VMoney } from 'v-money';

export default {
    components: {
        TextInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                date: null,
                name: null,
                description: null,
                amount: 54.50,
                receipt: null,
                record_type: null,
                fund_type: null,
            }),
            moneyConfig: {
                // The character used to show the decimal place.
                decimal: '.',
                // The character used to separate numbers in groups of three.
                thousands: ',',
                // The currency name or symbol followed by a space.
                prefix: 'RM ',
                // The suffix (If a suffix is used by the target currency.)
                suffix: '',
                // Level of decimal precision. REQUIRED
                precision: 2,
                // If mask is false, outputs the number to the model. Otherwise outputs the masked string.
                masked: false
            },
        }
    },
    directives: {money: VMoney},
    methods: {

    },
}

</script>