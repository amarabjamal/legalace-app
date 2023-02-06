<template>
    <Head :title="'Edit user | '+$props.user.name" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Edit user - {{ $props.user.name }}
                </h2>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-green-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                
                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
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
                                for="id_num" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                Identification Number
                            </label>
                            <input 
                                v-model="form.id_num"
                                type="text" 
                                id="identification_num" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.id_num" v-text="form.errors.id_num" class="mt-2 text-sm text-red-600"></p>
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
                                for="contact_num" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                Contact Number
                            </label>
                            <input 
                                v-model="form.contact_num"
                                type="text" 
                                id="contact_num" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.contact_num" v-text="form.errors.contact_num" class="mt-2 text-sm text-red-600"></p>
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
    
                        <button 
                            type="submit" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            :disabled="form.processing"
                            >
                            Save
                        </button>
                        <Link 
                            href="/users"
                            as="button"  
                            class="ml-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            :disabled="form.processing"
                            >
                            Cancel
                        </Link>
                    </form>
                </div>
                <!-- Main Content End -->
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup(props) {
        let form = useForm({
            name: props.user.name,
            email: props.user.email,
            id_num: props.user.id_num,
            employee_id: props.user.employee_id,
            isAdmin: props.user.isAdmin,
            isLawyer: props.user.isLawyer,
            contact_num: props.user.contact_num,
            birthdate: props.user.birthdate.substring(0, 10),
        });

        let submit = () => {
            form.put(`/users/${props.user.id}`);
        };

        return { form, submit };
    },
    props: { 
        user: Object,
     },
    components: { Head },
    layout: Layout,
};
</script>