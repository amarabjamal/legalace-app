<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <form @submit.prevent="update">
            <div class="p-8 space-y-12">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Account Information</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">The account information will be used in the quotation, and other records that you issue.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">
                        <text-input v-model="form.label" :error="form.errors.label" label="Label" required/>
                        <select-input v-model="form.bank_account_type_id" :error="form.errors.bank_account_type_id" label="Account Type" required>
                            <option disabled value="">Please select the account type</option>
                            <option value="1">Client Account</option>
                            <option value="2">Firm Account</option>
                        </select-input>
                        <text-input v-model="form.account_name" :error="form.errors.account_name" label="Account Name" required/>
                        <text-input v-model="form.bank_name" :error="form.errors.bank_name" label="Bank Name" required/>
                        <text-input v-model="form.account_number" :error="form.errors.account_number" label="Account Number" required/>
                        <text-input v-model="form.swift_code" :error="form.errors.swift_code" label="SWIFT Code" required/>
                        <textarea-input v-model="form.bank_address" :error="form.errors.bank_address" label="Bank Address" rows="4" class="col-span-1 md:col-span-2" required/>
                    </div>
                </div>
            </div>

            <div class="flex flex-row-reverse space-x-2 space-x-reverse  items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" :disabled="!form.isDirty" class="btn-primary" type="submit">Save Changes</loading-button>
                <Link :href="`/admin/bank-accounts/`" as="button" class="btn-cancel" :disabled="form.processing">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from '../../../Shared/TextInput';
import TextareaInput from '../../../Shared/TextareaInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';

export default { 
    components: { 
        TextInput,
        TextareaInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        bankAccount: Object,
        accountType: Object,
    },
    data() {
        return {
            page_title: this.bankAccount.label,
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/bank-accounts', label: 'Bank Accounts'},
                { link: null, label: this.bankAccount.label},
                { link: null, label: 'Edit'},
            ],
            form: this.$inertia.form({
                label: this.bankAccount.label,
                account_name: this.bankAccount.account_name,
                bank_name: this.bankAccount.bank_name,
                account_number: this.bankAccount.account_number,
                bank_address: this.bankAccount.bank_address,
                swift_code: this.bankAccount.swift_code,
                bank_account_type_id: this.bankAccount.bank_account_type_id,
            }),
        }
    },
    methods: {
        update() {
            if(this.form.isDirty) {
                this.form.put(`/admin/bank-accounts/${this.bankAccount.id}`);
            } else {
                alert('No changes to be saved.');
            }
        }
    },
};
</script>