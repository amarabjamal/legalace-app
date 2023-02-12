<template>
    <Head title="Edit Client" />
    
    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Name
                        </label>
                        <input 
                            v-model="form.name"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.name" v-text="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Identification Type
                        </label>
                        <!-- <input 
                            v-model="form.id_types_id"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        /> -->
                        <select id="id_type" name="identification_type" autocomplete="identification-type" v-model="form.id_types_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option>1</option>
                                <option>2</option>
                            </select>
                        <p v-if="form.errors.id_type" v-text="form.errors.id_type" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Identification Number
                        </label>
                        <input 
                            v-model="form.id_num"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.id_num" v-text="form.errors.id_num" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <div class="mb-6">
                        <label 
                            for="email" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Email Address
                        </label>
                        <input 
                            v-model="form.email"
                            type="email" 
                            id="email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.email" v-text="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <div class="mb-6">
                        <label 
                            for="phone_number" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Contact Number
                        </label>
                        <input 
                            v-model="form.phone_number"
                            type="text" 
                            id="phone_number" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            required  
                        />
                        <p v-if="form.errors.phone_number" v-text="form.errors.phone_number" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <div class="mb-6">
                        <label 
                            for="address" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Address
                        </label>
                        <input 
                            v-model="form.address"
                            type="text" 
                            id="address" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            required  
                        />
                        <p v-if="form.errors.address" v-text="form.errors.address" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <button 
                        type="submit" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        :disabled="form.processing"
                        >
                        Submit
                    </button>
                </form>
            </div>
        </main>  
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup (props) {
        let form = useForm({
            name: props.client.name,
            id_types_id: props.client.id_types_id,
            id_num:props.client.id_num,
            email: props.client.email,
            phone_number: props.client.phone_number,
            address: props.client.address,
        });

        let submit = () => {
            form.post('/clients');
        };

        return { form, submit };
    },
    props:{
        client: Object,
    },
    components: { Head },
    layout: Layout,
};
</script>