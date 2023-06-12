<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap p-8 py-12">
                <text-input v-model="form.ticket_number" :error="form.errors.ticket_number" class="pb-8 lg:pr-3 w-full lg:w-1/2" label="Ticket Number" required/>
                <select-input v-model="form.approver_user_id" :error="form.errors.approver_user_id" class="pb-8 lg:pl-3 w-full lg:w-1/2" label="Approver" required>
                    <option :value="null" disabled>Select Approver</option>
                    <option v-for="approver in approvers" :value="approver.id">{{ approver.name }}</option>
                </select-input>

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
                                <td class="pr-0 pl-8 py-5 align-top text-right tabular-nums">
                                    {{ added_item.amount_display }}
                                </td>
                                <td class="max-w-0 px-0 py-5 align-top">
                                    <div class="flex justify-center">
                                        <button type="button" @click="removeItem(index)"><icon name="close-circle-fill" class="block w-5 h-5 fill-gray-400" /></button>    
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="added_items.length === 0" class="border-b border-gray-100/100 bg-gray-100">
                                <td class="text-center py-4 text-gray-500" colspan="100">
                                    <div v-if="$page.props.errors.item_id" class="form-error">{{ $page.props.errors.item_id }}</div>
                                    <div v-else> No items in the list</div>
                                </td>
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
                                <th scope="row" colspan="2" class="pt-4 sm:text-right font-semibold text-gray-900">Total</th>
                                <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">{{ $filters.currency(total) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
            </div>
            <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                <Link 
                    href="/lawyer/claim-vouchers"
                    as="button"  
                    class="mr-2 text-gray-500 focus:outline-none hover:text-blue-700 hover:underline focus:z-10 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    :disabled="form.processing"
                    >
                    Cancel
                </Link>
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Voucher</loading-button>
            </div>
        </form>
    </div>

    <select-item-modal :isOpen="isModalOpen" @close-modal="closeModal"  @add-item="addItem" :items="reimbursable_items" :addedItems="added_items"/>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';
import SelectItemModal from "./Components/SelectItemModal";

export default { 
    components: { 
        TextInput,
        SelectInput,
        LoadingButton,
        SelectItemModal,
    },
    layout: Layout,
    props: {
        ticket_number: String,
        approvers: Object,
        reimbursable_items: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                ticket_number: this.ticket_number,
                approver_user_id: null,
                item_id: [],
            }),
            page_title: 'Create Voucher',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/claim-vouchers/', label: 'Claim Vouchers'},
                { link: null, label: 'Create'},
            ],
            isModalOpen: false,
            added_items: [],
        }
    },
    methods: {
        store() {
            this.form.post(`/lawyer/claim-vouchers`);
        },
        openModal() {
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
        },
        addItem(id) {
            if(this.isAdded(id)) {
                alert('Item already added to the list.');
            } else if(this.reimbursable_items.find((item) => item.id === id) != undefined) {
                const new_item = this.reimbursable_items.find((item) => item.id === id);
                this.added_items.push(new_item);
            }
        },
        removeItem(index) {
            this.added_items.splice(index, 1);
        },
        isAdded(id) {
            return this.added_items.find((item) => item.id === id);
        },
    },
    watch: {
        added_items: {
            handler() {
                this.form.item_id = this.added_items.map(({id}) => id);
            },
            deep: true,
        },
    },
    computed: {
        total() {
            let total = 0;
            this.added_items.forEach((item) => {
                total += item.amount_numeric;
            });

            return total;
        },
    },
    beforeMount() {
        if(this.form.item_id.length !== 0) {
            this.form.item_id.forEach(id => {
                this.addItem(id);
            });
        }
    }
};
</script>