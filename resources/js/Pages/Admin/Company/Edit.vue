<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <form @submit.prevent="update">
            <div class="p-8">
                <div class="mb-8 border-b-2 pb-4">
                    <h3 class="text-xl text-gray-700 font-bold mb-2">General</h3>
                    <p class="text-sm text-gray-500">This information is visible in the records you create.</p>
                </div>
                <div class="flex space-x-6 mb-10">
                    <div class="w-1/2 flex flex-col space-y-8">
                        <text-input v-model="form.name" :error="form.errors.name" class="w-full" label="Company Name" required/>
                        <text-input v-model="form.email" type="email" :error="form.errors.email" class="w-full" label="Email" required/>
                        <text-input v-model="form.website" :error="form.errors.website" class="w-full" label="Website URL"/>
                    </div>
                    <div class="w-1/2">
                        <div class="w-full">
                            <label class="form-label">Logo</label>
                            <div class="flex items-center justify-start w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-36 h-36 border border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50">
                                    <div class="flex flex-col items-center justify-center px-3 pt-5 pb-6">
                                        <p class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    </div>
                                    <input id="dropzone-file" type="file" accept="image/png, image/gif, image/jpeg" class="hidden" />
                                </label>
                            </div> 
                            <div v-if="error" class="form-error">{{ error }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-8 border-b-2 pb-4">
                    <h3 class="text-xl text-gray-700 font-bold mb-2">Address</h3>
                    <p class="text-sm text-gray-500">The address will be used in the quotations, invoices, receipts, and other records that you issue.</p>
                </div>
                <textarea-input v-model="form.address" :error="form.errors.address" class="w-full" rows="4" label="Address" required></textarea-input>
            </div>
            <div class="flex flex-row-reverse space-x-2 space-x-reverse  items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" :disabled="!form.isDirty" class="btn-primary" type="submit">Save Changes</loading-button>
                <Link 
                    :href="`/admin/company/`"
                    as="button"  
                    class="text-gray-500 focus:outline-none hover:text-blue-700 hover:underline focus:z-10 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    :disabled="form.processing"
                    >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from "../../../Shared/TextInput";
import TextareaInput from "../../../Shared/TextareaInput";
import LoadingButton from '../../../Shared/LoadingButton';

export default { 
    components: { 
        TextInput,
        TextareaInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        company: Object,
    },
    remember: 'form',
    data() {
        return {
            page_title: 'Company',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/company', label: 'Company'},
                { link: null, label: 'Edit'},
            ],
            form: this.$inertia.form({
                name: this.company.name,
                reg_number: this.company.reg_number,
                address: this.company.address,
                email: this.company.email,
                website: this.company.website,
            }),
        }
    },
    methods: {
        update() {
            if(this.form.isDirty) {
                this.form.put(`/admin/company`);
            } else {
                alert('No changes to be saved.');
            }
        }
    }
};
</script>