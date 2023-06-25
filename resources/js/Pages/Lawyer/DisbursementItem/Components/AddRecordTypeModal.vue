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
                <form @submit.prevent="createRecordType">
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                    <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                        <DialogTitle as="h2" class="text-base font-semibold leading-7 text-gray-900 mb-6 pb-4 border-b">New Record Type</DialogTitle>
                            
                                        <div class="space-y-4">
                                            <text-input v-model="form.name" :error="form.errors.name" label="Name" required/>
                                            <text-input v-model="form.description" :error="form.errors.description" label="Description (Optional)" />
                                        </div>
                                    </div>

                                    <div class="px-4 py-6 flex flex-row-reverse space-x-2 space-x-reverse  sm:px-6">
                                        <button type="submit" :disabled="form.processing || !form.isDirty" class="btn-primary">Create Record Type</button>
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
} from '@headlessui/vue'
import TextInput from '../../../../Shared/TextInput';

export default {
    components: {
        TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle,
        TextInput,
    },
    props: {
        isOpen: Boolean,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                description: null,
            }),
        }
    },
    methods: {
        closeModal() {
            // this.form.reset();
            this.$emit('close-modal');
        },
        createRecordType()
        {
            this.form.post(`/lawyer/disbursement-item-type`);
            this.closeModal();
        }
    },
}
</script>