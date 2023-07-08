<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <form @submit.prevent="store">
            <div class="p-8 space-y-12">
                <div class="grid md:grid-cols-2 gap-4">
                    <text-input v-model="form.ticket_number" :error="form.errors.ticket_number" label="Ticket Number" required/>
                    <select-input v-model="form.approver_user_id" :error="form.errors.approver_user_id" label="Approver" required>
                        <option selected disabled>Select Approver</option>
                        <option v-for="approver in approvers" :value="approver.id">{{ approver.name }}</option>
                    </select-input>
                </div>

                <div class="w-full overflow-x-auto overflow-y-hidden">
                    <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                        <thead class="border border-gray-300 text-gray-900 bg-gray-100 select-none">
                            <tr>
                                <th class="w-12 px-4 py-2">No.</th>
                                <th>Item</th>
                                <th class="w-40 text-right">Amount</th>
                                <th class="w-16"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(added_item, index) in added_items" :key="index" class="border border-gray-300">
                                <td class="max-w-0 px-4 py-2 align-top">{{ index + 1 }}</td>
                                <td class="max-w-0 px-4 py-2 align-top">
                                    <div class="truncate font-medium text-gray-900">
                                        {{ added_item.name }}
                                    </div>
                                    <div class="truncate text-gray-500">
                                        {{ added_item.description }}
                                    </div>
                                </td>
                                <td class="px-0 py-2 align-top text-right tabular-nums">
                                    {{ added_item.amount_display }}
                                </td>
                                <td class="max-w-0 px-0 py-2 align-top">
                                    <div class="flex justify-center items-center">
                                        <button type="button" @click="removeItem(index)" tabindex="-1"><icon name="close-circle-fill" class="block w-5 h-5 fill-gray-400" /></button>    
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="added_items.length === 0" class="border border-gray-300 bg-gray-50">
                                <td class="text-center py-4 text-gray-500" colspan="100">
                                    <div v-if="$page.props.errors.item_id" class="form-error">{{ $page.props.errors.item_id }}</div>
                                    <div v-else> No items in the list</div>
                                </td>
                            </tr>
                            <tr class="border border-gray-300">
                                <td colspan="100">
                                    <div class="w-100 flex justify-center px-4 py-2">
                                        <button type="button" class="select-none text-gray-700 text-sm font-medium" @click="openModal">+ Add Item</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row" colspan="2" class="py-2 sm:text-right font-semibold text-gray-900 border-l border-b border-gray-300 bg-gray-100">Total</th>
                                <td colspan="1" class="py-2 text-right font-semibold tabular-nums text-gray-900 border-b border-gray-300 bg-gray-100">{{ $filters.currency(total) }}</td>
                                <td class="border-b border-r border-gray-300 bg-gray-100"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Voucher</loading-button>
                <Link href="/lawyer/claim-vouchers" as="button" class="btn-cancel" :disabled="form.processing">
                    Cancel
                </Link>
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