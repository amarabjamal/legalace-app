<template>
    <Head title="Create Case File" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <select-input v-model="form.client_id" :error="form.errors.client_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Client" required>
                    <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
                </select-input>
                <text-input v-model="form.file_number" :error="form.errors.file_number" class="pb-8 pr-6 w-full lg:w-1/2" label="File Number" required/>
                <text-input v-model="form.matter" :error="form.errors.matter" class="pb-8 pr-6 w-full lg:w-1/2" label="Matter" required/>
                <text-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full lg:w-1/2" label="File Type" required/>
            </div>
            <div class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create File</loading-button>
                <Link href="/lawyer/case-files" as="button" class="btn-cancel" :disabled="form.processing">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';

export default { 
    components: { 
        TextInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        clients: Object,
        lawyers: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                matter: null,
                type: null,
                file_number: null,
                client_id: null,
                lawyer_id: null,
            }),
            page_title: 'Create File',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: null, label: 'Create'},
            ],
        }
    },
    methods: {
        store() {
            this.form.post(`/lawyer/case-files`);
        }
    },
};
</script>