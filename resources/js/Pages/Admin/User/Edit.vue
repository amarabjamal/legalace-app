<template>
    <Head :title="user.name" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
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
                    id="identification_num" 
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

                <input v-model="form.isAdmin" :checked="form.isAdmin" type="checkbox" id="isAdmin" value="admin" >
                <label for="isAdmin"> Administrator</label><br/>
                <input v-model="form.isLawyer" :checked="form.isLawyer" type="checkbox" id="isLawyer" value="lawyer">
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

                <input v-model="form.is_active" type="checkbox" :checked="form.is_active" id="is_active" value="is_active">
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
                Save
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
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup(props) {
        let form = useForm({
            id: props.user.id,
            name: props.user.name,
            email: props.user.email,
            id_type_id: props.user.id_type_id,
            id_number: props.user.id_number,
            employee_id: props.user.employee_id,
            isAdmin: props.user.isAdmin,
            isLawyer: props.user.isLawyer,
            contact_number: props.user.contact_number,
            birthdate: props.user.birthdate.substring(0, 10),
            is_active: props.user.is_active,
            access_expiry_date: props.user.access_expiry_date != null ? props.user.access_expiry_date.substring(0, 10) : '',
        });

        let submit = () => {
            form.put(`/admin/users/${props.user.id}`);
        };

        return { form, submit };
    },
    components: { Head },
    layout: Layout,
    props: { 
        user: Object,
        idTypes : Object,
    },
    data() {
        return {
            page_title: this.user.name,
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/users', label: 'Employees'},
                { link: null, label: this.user.name },
                { link: null, label: 'Edit' },
            ],
        }
    },
};
</script>