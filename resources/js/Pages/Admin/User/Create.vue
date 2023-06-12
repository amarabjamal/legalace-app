<template>
    <Head title="Create Employee" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <!-- <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input v-model="form.file_number" :error="form.errors.file_number" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" required/>
                <text-input v-model="form.matter" :error="form.errors.matter" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" required/>
                <select-input v-model="form.client_id" :error="form.errors.client_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Identification Type" required>
                    <option disabled :value="null">Please select identification type</option>
                    <option v-for="idType in idTypes" :value="idType.id">{{idType.name}}</option>
                </select-input>
                <text-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full lg:w-1/2" label="Identification Number" required/>
                <text-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full lg:w-1/2" label="Employee ID" required/>
            </div>
        </form>
    </div> -->
    <div class="max-w-3xl px-6 py-8 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="submit">
            <div class="mb-6">
                <label 
                    for="name" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Name
                </label>
                <input 
                    v-model="form.name"
                    type="text" 
                    id="name" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.name" v-text="form.errors.name" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="email" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Email Address
                </label>
                <input 
                    v-model="form.email"
                    type="email" 
                    id="email" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.email" v-text="form.errors.email" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="id_type_id" 
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                    Identification Type
                </label>
                <select 
                    v-model="form.id_type_id"
                    id="id_type_id" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    required
                >
                    <option disabled value="">Please select identification type</option>
                    <option v-for="idType in idTypes" :value="idType.id">{{idType.name}}</option>
                </select>
                <p v-if="form.errors.id_type_id" v-text="form.errors.id_type_id" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="id_number" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Identification Number
                </label>
                <input 
                    v-model="form.id_number"
                    type="text" 
                    id="id_number" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.id_number" v-text="form.errors.id_number" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="employee_id" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Employee ID
                </label>
                <input 
                    v-model="form.employee_id"
                    type="text" 
                    id="employee_id" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.employee_id" v-text="form.errors.employee_id" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="role" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Role
                </label>

                <input v-model="form.isAdmin" type="checkbox" id="isAdmin" value="admin">
                <label for="isAdmin"> Administrator</label><br/>
                <input v-model="form.isLawyer" type="checkbox" id="isLawyer" value="lawyer">
                <label for="isLawyer"> Lawyer</label><br/>
                <p v-if="form.errors.role" v-text="form.errors.role" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="contact_number" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Contact Number
                </label>
                <input 
                    v-model="form.contact_number"
                    type="text" 
                    id="contact_number" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.contact_number" v-text="form.errors.contact_number" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="birthdate" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Birthdate
                </label>
                <input 
                    v-model="form.birthdate"
                    type="date" 
                    id="birthdate" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.birthdate" v-text="form.errors.birthdate" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="is_active" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Enable Access
                </label>

                <input v-model="form.is_active" type="checkbox" id="is_active" value="is_active">
                <label for="is_active"> Grant Access</label><br/>
                <p v-if="form.errors.is_active" v-text="form.errors.is_active" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label 
                    for="access_expiry_date" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Access Expiry Date
                </label>
                <input 
                    v-model="form.access_expiry_date"
                    type="date" 
                    id="access_expiry_date" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder=""
                />
                <p v-if="form.errors.access_expiry_date" v-text="form.errors.access_expiry_date" class="mt-2 text-sm text-red-600"></p>
            </div>

            <button 
                type="submit" 
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Submit
            </button>

            <Link 
                href="/admin/users"
                as="button"  
                class="ml-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Cancel
            </Link>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup () {
        let form = useForm({
            name: '',
            email: '',
            id_type_id: '',
            id_number: '',
            employee_id: '',
            isAdmin: '',
            isLawyer: '',
            contact_number: '',
            birthdate: '',
            is_active: '',
            access_expiry_date: '',
        });

        let submit = () => {
            form.post('/admin/users');
        };

        return { form, submit };
    },
    components: { 
        TextInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
      idTypes : Object
    },
    data() {
        return {
            page_title: 'Create Employee',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/users', label: 'Employees'},
                { link: null, label: 'Create'},
            ],
        }
    },
};
</script>