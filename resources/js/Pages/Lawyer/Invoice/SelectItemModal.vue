<template>
    <TransitionRoot :show="isOpen" as="template">
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
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                                <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Invoice Items</DialogTitle>
                        
                                    <p class="mt-2 text-sm text-gray-500">Please select the items to be added to this invoice.</p>

                                    <div class="mt-2 overflow-hidden overflow-y-scroll max-h-80">
                                        <table class="w-full my-4 whitespace-nowrap text-left text-sm leading-6 mr-6">
                                            <thead class="border-b border-gray-200/100 text-gray-900/100">
                                                <tr>
                                                    <th class="w-12">No.</th>
                                                    <th>Item</th>
                                                    <th class="w-40 text-right">Amount</th>
                                                    <th class="w-24"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in items" :key="item.id" class="border-b border-gray-100/100">
                                                    <td class="max-w-0 px-0 py-5 align-top">{{ index + 1 }}</td>
                                                    <td class="max-w-0 px-0 py-5 align-top">
                                                        <div class="truncate font-medium text-gray-900/100">
                                                            {{ item.name }}
                                                        </div>
                                                        <div class="truncate text-gray-500/100">
                                                            {{ item.description }}
                                                        </div>
                                                    </td>
                                                    <td class="pr-0 pl-8 py-5 align-top text-right">
                                                        {{ item.amount }}
                                                    </td>
                                                    <td class="flex justify-center py-5 align-top">
                                                        <button v-if="isNotAdded(item.id)" type="button" @click="addItem(item.id)">Add</button>   
                                                        <span v-else class="italic text-sm text-gray-400 mt-1">Added</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="closeModal">Close</button>
                                </div>
                            </DialogPanel>
                        </div>
                    </div>
                </div>
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
    DialogDescription,
} from '@headlessui/vue'

export default {
    props: {
        isOpen: Boolean,
        items: Array,
        addedItems: Array,
    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
        },
        addItem(id) {
            this.$emit('add-item', id);
        },
        isNotAdded(id) {
            return !this.addedItems.find((item) => item.id === id);
        }
    },
}
</script>