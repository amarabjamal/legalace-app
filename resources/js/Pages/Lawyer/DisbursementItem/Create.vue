<template>
    <Head title="Disbursement Item" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <date-input v-model="form.date" :error="form.errors.date" class="pb-8 pr-6 w-full lg:w-1/2" label="Date" required/>

                <select-input v-model="form.record_type_id" :error="form.errors.record_type_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Record Type" required>
                    <option :value="null" />
                    <option v-for="record_type in record_types" :value="record_type.id">{{ record_type.name }}</option>
                </select-input>

                <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" required/>
                <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2" label="Description (Optional)"/>
                
                <div class="pb-8 pr-6 w-full lg:w-1/2">
                    <label class="form-label">Amount</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-r-0 border-gray-200 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            RM
                        </span>
                        <input v-model.lazy="form.amount" v-money="moneyConfig" class="form-input-money text-right" :class="{ error: form.errors.amount }" required/>
                    </div>
                    <div v-if="form.errors.amount" class="form-error">{{ form.errors.amount }}</div>
                </div>
                
                <select-input v-model="form.fund_type" :error="form.errors.fund_type" class="pb-8 pr-6 w-full lg:w-1/2" label="Fund Type">
                    <option :value="null" />
                    <option v-for="fund_type in fund_types" :value="fund_type.value" required>{{ fund_type.label }}</option>
                </select-input>

                <file-input v-model="form.receipt" :error="form.errors.receipt" class="pb-8 pr-6 w-full lg:w-1/2" label="Receipt" accept=".jpg,.png,.pdf,.doc,.docx"/>
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
import DateInput from '../../../Shared/DateInput';
import FileInput from '../../../Shared/FileInput'
import LoadingButton from '../../../Shared/LoadingButton';
import { VMoney } from 'v-money';

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        FileInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file: Object,
        fund_types: Object,
        record_types: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                date: null,
                name: null,
                description: null,
                amount: null,
                receipt: null,
                record_type_id: null,
                fund_type: null,
            }),
            moneyConfig: {
                // The character used to show the decimal place.
                decimal: '.',
                // The character used to separate numbers in groups of three.
                thousands: ',',
                // The currency name or symbol followed by a space.
                prefix: '',
                // The suffix (If a suffix is used by the target currency.)
                suffix: '',
                // Level of decimal precision. REQUIRED
                precision: 2,
                // If mask is false, outputs the number to the model. Otherwise outputs the masked string.
                masked: false
            },
            page_title: 'Create Item',
            breadcrumbs: [
                { link: '/lawyer', label: 'Dashboard'},
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/disbursement-items`, label: 'Items'},
                { link: null, label: 'Create'},
            ],
        }
    },
    directives: {money: VMoney},
    methods: {
        store() {
            this.form.amount = this.form.amount.replace(/^\W|,/g,"");
            this.form.post(`/lawyer/case-files/${this.case_file.id}/disbursement-items`, {forceFormData: true,});
        },
    },
}

</script>