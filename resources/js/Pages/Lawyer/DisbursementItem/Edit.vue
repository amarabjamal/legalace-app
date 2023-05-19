<template>
    <Head title="Disbursement Item" />

    <h1 class="mb-6 text-xl font-bold">
        <Link class="text-blue-500 hover:text-blue-600" href="/lawyer/casefiles/">Case Files</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}`">{{ case_file.file_number }}</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}/disbursement-items`">Disbursement Items</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <span class="font-medium">{{ disbursement_item. name }}</span>
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="update">
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

                <div v-if="disbursement_item.receipt" class="pb-8 pr-6 w-full lg:w-1/2">
                    <label class="form-label">Uploaded File</label>
                    <div class="form-input">
                        <div class="flex">
                            <icon name="solid-document" class="block w-5 h-5 fill-blue-500"></icon>
                            <div class="ml-2 whitespace-nowrap overflow-ellipsis overflow-hidden ">{{ disbursement_item.receipt }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Item</button>
                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Item</loading-button>
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
import Icon from '../../../Shared/Icon';
import { VMoney } from 'v-money';

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        FileInput,
        LoadingButton,
        Icon,
    },
    layout: Layout,
    props: {
        case_file: Object,
        disbursement_item: Object,
        fund_types: Object,
        record_types: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                date: this.disbursement_item.date,
                name: this.disbursement_item.name,
                description: this.disbursement_item.description,
                amount: this.disbursement_item.amount.amount,
                receipt: null,
                record_type_id: this.disbursement_item.record_type_id,
                fund_type: this.disbursement_item.fund_type,
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
        }
    },
    directives: {money: VMoney},
    methods: {
        update() {
            this.form.amount = this.form.amount.replace(/^\W|,/g,"");
            this.form.post(
                `/lawyer/casefiles/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}`, 
                {
                    onSuccess: () => this.form.reset('receipt'),
            });
        },
        destroy() {
            if(confirm('Are you sure you want to delete this item?')) {
                this.$inertia.delete(`/lawyer/casefiles/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}`);
            }
        }
    },
}

</script>