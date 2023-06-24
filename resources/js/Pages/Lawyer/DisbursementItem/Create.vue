<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="p-8 space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Item Information</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Provide an identifiable name.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">
                        <select-input v-model="form.record_type_id" :error="form.errors.record_type_id" label="Record Type" required>
                            <option disabled value="">Select record type</option>
                            <option v-for="record_type in record_types" :value="record_type.id">{{ record_type.name }}</option>
                        </select-input>

                        <text-input v-model="form.name" :error="form.errors.name" label="Name" required/>
                        <text-input v-model="form.description" :error="form.errors.description" label="Description (Optional)"/>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Payment Information</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Only the creater of the item can reimburse the amount give the fund type is paid by lawyer.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">
                        <date-input v-model="form.date" :error="form.errors.date" label="Date" required/>
                        <select-input v-model="form.fund_type" :error="form.errors.fund_type" label="Fund Type" required>
                            <option :value="null" disabled></option>
                            <option v-for="fund_type in fund_types" :value="fund_type.value" required>{{ fund_type.label }}</option>
                        </select-input>
                        <money-input v-model.lazy="form.amount" :error="form.errors.amount" label="Amount"  required/>
                        <file-input v-model="form.receipt" :error="form.errors.receipt" label="Receipt" accept=".jpg,.png,.pdf,.doc,.docx" required/>
                    </div>
                </div>
            </div>
            <div class="flex flex-row-reverse space-x-2 space-x-reverse  items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" :disabled="form.processing || !form.isDirty" class="btn-primary" type="submit">Create Item</loading-button>
                <Link :href="`/lawyer/case-files/${this.case_file.id}/disbursement-items`" as="button" class="btn-cancel" :disabled="form.processing">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import DateInput from '../../../Shared/DateInput';
import FileInput from '../../../Shared/FileInput';
import MoneyInput from '../../../Shared/MoneyInput';
import LoadingButton from '../../../Shared/LoadingButton';
import { unmaskMoneyToNumeric } from '../../../Stores/Utils';

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        FileInput,
        MoneyInput,
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
            page_title: 'Create Item',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/disbursement-items`, label: 'Items'},
                { link: null, label: 'Create'},
            ],
            form: this.$inertia.form({
                date: null,
                name: null,
                description: null,
                amount: null,
                receipt: null,
                record_type_id: null,
                fund_type: null,
            }),
        }
    },
    methods: {
        store() {
            if(this.form.isDirty) {
                this.form.amount = unmaskMoneyToNumeric(this.form.amount);
                this.form.post(`/lawyer/case-files/${this.case_file.id}/disbursement-items`, {forceFormData: true,});
            } else {
                alert('You need to fill in the form first.');
            }
        },
    },
}

</script>