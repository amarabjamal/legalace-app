<template>
    <TransitionRoot :show="isOpen" as="template" appear>
        <Dialog as="div" :open="isOpen" @close="closeModal" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30" aria-hidden="true" />
            </TransitionChild>

            <TransitionChild
                as="template"
                enter="ease-out duration-300" 
                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                enter-to="opacity-100 translate-y-0 sm:scale-100" 
                leave="ease-in duration-200" 
                leave-from="opacity-100 translate-y-0 sm:scale-100" 
                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <form @submit.prevent="savePayment">
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                    <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                        <DialogTitle as="h2" class="text-base font-semibold leading-7 text-gray-900 mb-6 pb-4 border-b">New Payment</DialogTitle>
                            
                                        <TabGroup>
                                            <TabList class="flex border-b">
                                                <Tab as="template" v-slot="{ selected }">
                                                    <button
                                                        :class="[
                                                        'w-full py-2.5 text-sm leading-5 border-b-2',
                                                        'focus:outline-none',
                                                        selected
                                                            ? 'border-blue-700'
                                                            : 'text-gray-400 border-transparent hover:border-blue-700',
                                                        ]"
                                                    >
                                                        General
                                                    </button>
                                                </Tab>
                                                <Tab as="template" v-slot="{ selected }">
                                                    <button
                                                        :class="[
                                                        'w-full py-2.5 text-sm leading-5 border-b-2',
                                                        'focus:outline-none',
                                                        selected
                                                            ? 'border-blue-700'
                                                            : 'text-gray-500 border-transparent hover:border-blue-700',
                                                        ]"
                                                    >
                                                        Other
                                                    </button>
                                                </Tab>
                                            </TabList>
                                            <TabPanels>
                                                <TabPanel class="pt-6 space-y-4">
                                                    <date-input v-model="form.date" :error="form.errors.date" label="Date" required/>
                                                    <select-input v-model="form.payment_method_code" :error="form.errors.payment_method_code" label="Payment Method" required>
                                                        <option v-for="payment_method in modalProps.payment_methods" :value="payment_method.value">{{ payment_method.label }}</option>
                                                    </select-input>
                                                    <select-input v-model="form.client_bank_account_id" :error="form.errors.client_bank_account_id" label="Account" required>
                                                        <option v-for="client_bank_account in modalProps.client_bank_accounts" :value="client_bank_account.id">{{ client_bank_account.label }}</option>
                                                    </select-input>
                                                    <file-input v-model="form.receipt" :error="form.errors.receipt" label="Payment Proof" accept=".jpg,.png,.pdf,.doc,.docx" required/>
                                                </TabPanel>
                                                <TabPanel class="pt-6 space-y-4">
                                                   <textarea-input v-model="form.description" :error="form.errors.description" label="Description (Optional)" rows="4"></textarea-input>
                                                </TabPanel>
                                            </TabPanels>
                                        </TabGroup>
                                    </div>

                                    <div class="px-4 py-6 flex flex-row-reverse space-x-2 space-x-reverse  sm:px-6">
                                        <loading-button type="submit" :loading="form.processing" :disabled="!form.isDirty" class="btn-primary">Save Payment</loading-button>
                                        <button type="button" class="btn-close" @click="closeModal">Cancel</button>
                                    </div>
                                </DialogPanel>
                            </div>
                        </div>
                    </div>
                </form>
            </TransitionChild>
        </Dialog>
    </TransitionRoot>
</template>
  
<script>
import { 
    TransitionRoot,TransitionChild, Dialog, DialogPanel, DialogTitle,
    TabGroup, TabList, Tab, TabPanels, TabPanel
} from '@headlessui/vue'
import DateInput from '../../../../Shared/DateInput';
import SelectInput from '../../../../Shared/SelectInput';
import FileInput from '../../../../Shared/FileInput';
import TextareaInput from '../../../../Shared/TextareaInput';
import LoadingButton from '../../../../Shared/LoadingButton';

export default {
    components: {
        TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle,
        TabGroup, TabList, Tab, TabPanels, TabPanel,
        DateInput, SelectInput, FileInput, TextareaInput, LoadingButton,
    },
    props: {
        isOpen: Boolean,
        caseFileId: Number,
        modalProps: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                date: null,
                amount: null,
                payment_method_code: null,
                client_bank_account_id: null,
                receipt: null,
                description: null,
            }),
        }
    },
    methods: {
        closeModal() {
            this.form.reset();
            this.$emit('close-modal');
        },
        savePayment()
        {
            this.form.post(`/lawyer/case-files/${this.caseFileId}/quotation/save-payment`);
        }
    },
}
</script>