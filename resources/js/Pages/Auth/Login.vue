<template>

    <Head title="Sign In"/>

  <div class="min-h-screen bg-gray-200 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow" />
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
            </div>
        
            <form @submit.prevent="submit">

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
                        for="password" 
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >
                        Password
                    </label>
                    <input 
                        v-model="form.password"
                        type="password" 
                        id="password" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                        required  
                    />
                    <p v-if="form.errors.password" v-text="form.errors.password" class="mt-2 text-sm text-red-600"></p>
                </div>

                <button 
                    type="submit" 
                    class="min-w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    :disabled="form.processing"
                    >
                    Login
                </button>
            </form>
        </div>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup () {
        let form = useForm({
            email: '',
            password: ''
        });

        let submit = () => {
            form.post('/login');
        };

        return { form, submit };
    },
};
</script>