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
                <form @submit.prevent="reportConflict">
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                                    <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900 mb-6">Send Conflict Report</DialogTitle>
                            
                                        <textarea-input v-model="form.content" rows="5"/>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <button type="submit" :disabled="!form.isDirty" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:bg-red-300">Report</button>
                                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="closeModal">Close</button>
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
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import TextareaInput from '../../../../Shared/TextareaInput';

export default {
    components: {
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogPanel,
        DialogTitle,
        TextareaInput,
    },
    props: {
        isOpen: Boolean,
        caseFileId: Number,
    },
    data() {
        return {
            form: this.$inertia.form({
                content: null,
            }),
        }
    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
        },
        reportConflict()
        {
            this.form.post(`/lawyer/case-files/${this.caseFileId}/conflict-reports`);
        }
    },
}
</script>