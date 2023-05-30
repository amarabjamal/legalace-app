<template>
    <Dialog as="div" :open="isOpen" @close="closeModal" class="relative z-50 bg-transparent">
        <div class="fixed inset-0 bg-black/30" aria-hidden="true" />

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form @submit.prevent="uploadProof">
                            <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Proof of Payment</DialogTitle>
                    
                                <p class="my-2 text-sm text-gray-500">Please upload the proof of payment from the client.</p>
    
                                <div>
                                    <file-input v-model="proof_form.file" :error="proof_form.errors.file" class="pb-2" accept=".jpg,.png,.pdf,.doc,.docx"/>
                                </div>
                            </div>
    
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 sm:ml-3 sm:w-auto">Upload</button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="closeModal">Close</button>
                            </div>
                        </form>
                    </DialogPanel>
                </div>
            </div>
        </div>
    </Dialog>
</template>
  
<script>
import {
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import FileInput from '../../../Shared/FileInput';

export default {
    components: {
        FileInput,
    },
    props: {
        isOpen: Boolean,
    },
    data() {
        return {
            proof_form: this.$inertia.form({
                file: null,
            }),
        }
    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
        },
        uploadProof() {
            
        }
    },
}
</script>