<template>
    <Head title="Create Invoice" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="flex flex-col xl:flex-row">
        <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="store">
                <div class="flex flex-wrap p-8 py-12">
                    <div class="w-full">
                        <text-input v-model="form.invoice_number" :error="form.errors.invoice_number" class="pb-8 lg:pr-6 w-full lg:w-1/2" label="Invoice Number" required/>
                    </div>
                    <date-input v-model="form.issued_at" :error="form.errors.issued_at" class="pb-8 lg:pr-6 w-full lg:w-1/2" label="Invoice Date" required/>
                    <date-input v-model="form.due_at" :error="form.errors.due_at" class="pb-8 w-full lg:w-1/2" label="Due Date" required/>

                    <div class="flex flex-wrap w-full mt-4 mb-6 text-sm">
                        <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                            <div class="font-semibold text-gray-900">
                                From
                            </div>
                            <div class="flex flex-col mt-3 text-gray-500">
                                <div class="font-medium text-gray-900 mb-2">
                                    {{ invoice.company.name }}
                                </div>
                                <div class="whitespace-pre-wrap">{{ invoice.company.address }}</div>
                            </div>  
                        </div>
                        <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                            <div class="font-semibold text-gray-900">
                                To
                            </div>
                            <div class="flex flex-col mt-3 text-gray-500">
                                <div class="font-medium text-gray-900 mb-2">
                                    {{ invoice.client.name }}
                                </div>
                                <div class="whitespace-pre-wrap">{{ invoice.client.address }}</div>
                            </div>  
                        </div>
                    </div>
    
                    <table class="w-full my-4 whitespace-nowrap text-left text-sm leading-6">
                        <thead class="border-b border-gray-200/100 text-gray-900 select-none">
                            <tr>
                                <th class="w-12">No.</th>
                                <th>Item</th>
                                <th class="w-40 text-right">Amount</th>
                                <th class="w-16"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(added_item, index) in added_items" :key="index" class="border-b border-gray-100/100">
                                <td class="max-w-0 px-0 py-5 align-top">{{ index + 1 }}</td>
                                <td class="max-w-0 px-0 py-5 align-top">
                                    <div class="truncate font-medium text-gray-900/100">
                                        {{ added_item.name }}
                                    </div>
                                    <div class="truncate text-gray-500/100">
                                        {{ added_item.description }}
                                    </div>
                                </td>
                                <td class="pr-0 pl-8 py-5 align-top text-right tabular-nums">{{ added_item.amount }}</td>
                                <td class="max-w-0 px-0 py-5 align-top">
                                    <div class="flex justify-center">
                                        <button type="button" @click="removeItem(index)"><icon name="close-circle-fill" class="block w-5 h-5 fill-gray-400" /></button>    
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="added_items.length === 0" class="border-b border-gray-100/100 bg-gray-100">
                                <td class="text-center py-4 text-gray-500" colspan="100">No items in the list</td>
                            </tr>
                            <tr>
                                <td class="py-5" colspan="100">
                                   <div class="w-100 flex justify-center">
                                        <button type="button" class="select-none" @click="openModal">Add Item</button>
                                   </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row" colspan="2" class="px-0 pb-0 sm:pt-6 sm:text-right font-normal text-gray-700">Subtotal</th>
                                <td colspan="1" class="pb-0 pl-8 pr-0 pt-6 text-right text-gray-900 tabular-nums">{{ $filters.currency(subtotal) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="2" class="pt-4 sm:text-right font-normal text-gray-700">Tax (0%)</th>
                                <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right tabular-nums text-gray-900">{{ $filters.currency(tax) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="2" class="pt-4 sm:text-right font-semibold text-gray-900">Total</th>
                                <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">{{ $filters.currency(grandTotal) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
    
                    <textarea-input v-model="form.notes" :error="form.errors.notes" rows="4" class="w-full" label="Notes"/>
                </div>
                <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Invoice</loading-button>
                    <Link :href="`/lawyer/case-files/${this.case_file.id}/invoices`" as="button" class="btn-cancel" :disabled="form.processing">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
        
        <div class="hidden w-full max-w-3xl h-fit xl:w-1/3 bg-gray-100 ring-gray-900 rounded-md shadow overflow-hidden mt-5 xl:mt-0 xl:ml-5 p-8">
            Total RM 9,999.99
        </div>
    </div>

    <select-item-modal :isOpen="isOpen" @close-modal="closeModal" @add-item="addItem" :items="items" :addedItems="added_items"/>
</template>

<script>
import Layout from '../Shared/Layout';
import TextInput from '../../../Shared/TextInput';
import DateInput from '../../../Shared/DateInput';
import TextareaInput from '../../../Shared/TextareaInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';
import SelectItemModal from './Components/SelectItemModal';

export default { 
    components: { 
        TextInput,
        DateInput,
        TextareaInput,
        SelectInput,
        LoadingButton,
        SelectItemModal,
    },
    layout: Layout,
    props: {
        case_file: Object,
        invoice: Object,
        items: Array,
    },
    remember: 'form',
    data() {
        return {
            page_title: 'Create Invoice',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: null, label: 'Create'},
            ],
            form: this.$inertia.form({
                invoice_number: this.invoice.number,
                issued_at: null,
                due_at: null,
                notes: null,
                items_id: [],
            }),
            isOpen: false,
            added_items: [],
        }
    },
    methods: {
        store() {
            this.form.post(`/lawyer/case-files/${this.case_file.id}/invoices`);
        },
        openModal() {
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        },
        addItem(id) {
            if(this.added_items.find((item) => item.id === id)) {
                alert('Item already added to the list.');
            } else if(this.items.find((item) => item.id === id) != undefined) {
                const new_item = this.items.find((item) => item.id === id);
                this.added_items.push(new_item);
            }
        },
        removeItem(index) {
            this.added_items.splice(index, 1);
        }
    },
    watch: {
        added_items: {
            handler() {
                this.form.items_id = this.added_items.map(({id}) => id);
            },
            deep: true,
        },
    },
    computed: {
        subtotal() {
            let total = 0;
            this.added_items.forEach((item) => {
                total += item.amount_numeric;
            });

            return total;
        },
        tax() {
            return 0;
        },
        grandTotal() {
            return this.subtotal + this.tax;
        },
    },
    beforeMount() {
        if(this.form.items_id.length !== 0) {
            this.form.items_id.forEach(id => {
                this.addItem(id);
            });
        }
    }
};
</script>