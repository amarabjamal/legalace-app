<template>
    <Head title="Edit Clerk" />
    <Header title="Edit New Clerk" />

    <main >
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
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
                    for="identification_num" 
                    class="block mb-2 text-sm font-medium text-gray-900"
                    >
                    Identification Number
                </label>
                <input 
                    v-model="form.identification_num"
                    type="text" 
                    id="identification_num" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.identification_num" v-text="form.errors.identification_num" class="mt-2 text-sm text-red-600"></p>
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

            <button 
                type="submit" 
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Save
            </button>
            <Link 
                href="/clerks"
                as="button"  
                class="ml-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Cancel
            </Link>
        </form>
      </div>
    </main>  
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Header from "../Shared/Header.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup (props) {
        let form = useForm({
            name: props.clerk.name,
            identification_num: props.clerk.identification_num,
            employee_id: props.clerk.employee_id,
            email: props.clerk.email,
        });

        let submit = () => {
            form.put(`/clerks/${props.clerk.id}`);
        };

        return { form, submit };
    },
    props: {
        clerk: Object,
    },
    components: { Head, Header },
    layout: Layout,
};
</script>