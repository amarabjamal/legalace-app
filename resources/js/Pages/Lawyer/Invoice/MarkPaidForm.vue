<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <date-input v-model="form.date" :error="form.errors.date" class="pb-8 pr-6 w-full lg:w-1/2" label="Date of Transaction" required/>

                <select-input v-model="form.client_bank_account_id" :error="form.errors.client_bank_account_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Account" required>
                    <option :value="null" disabled>Select account</option>
                    <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{ client_bank_account.label }}</option>
                </select-input>

                <select-input v-model="form.payment_method_id" :error="form.errors.payment_method_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Payment Method" required>
                    <option :value="null" disabled>Select payment method</option>
                    <option v-for="payment_method in payment_methods" :value="payment_method.value">{{ payment_method.label }}</option>
                </select-input>

                <file-input v-model="form.receipt" :errors="form.errors.receipt" class="pb-8 pr-6 w-full lg:w-1/2" label="Receipt" accept=".jpg,.png,.pdf,.doc,.docx"/>
                <textarea-input v-model="form.description" rows="4"  class="pb-8 pr-6 w-full" label="Description" required/>
            </div>
            <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Item</loading-button>
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
import TextareaInput from '../../../Shared/TextareaInput';
import LoadingButton from '../../../Shared/LoadingButton';

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        FileInput,
        TextareaInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file: Object,
        invoice: Object,
        payment_methods: Object,
        client_bank_accounts: Object,
        suggested_description: String,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                date: null,
                client_bank_account_id: null,
                payment_method_id: null,
                receipt: null,
                description: this.suggested_description,
            }),
            page_title: 'Upload Proof of Payment',
            breadcrumbs: [
                { link: '/lawyer', label: 'Dashboard'},
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}`, label: this.invoice.number},
                { link: null, label: 'Mark Paid'},
            ],
        }
    },
    methods: {
        store() {
            this.form.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/mark-paid`, {forceFormData: true,});
        },
    },
}

</script>