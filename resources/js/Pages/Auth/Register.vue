<template>

    <Head title="Register"/>

    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div
        class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
      >
        <div class="flex flex-col overflow-y-auto md:flex-row">
          <div class="h-32 md:h-auto md:w-1/2">
            <img
              aria-hidden="true"
              class="object-cover w-full h-full dark:hidden"
              :src="'/images/app/login.png'"
              alt="Legal Ace Logo"
            />
            <img
              aria-hidden="true"
              class="hidden object-cover w-full h-full dark:block"
              :src="'/images/app/login.png'"
              alt="Legal Ace Logo"
            />
          </div>
          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <h1
                class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
              >
                Create account
              </h1>
              
              <form @submit.prevent="submit">
                <div class="mb-6">
                    <label 
                        for="name" 
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                        Name
                    </label>
                    <input 
                        v-model="form.name"
                        type="text" 
                        id="name" 
                        class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        placeholder="" 
                        required
                    />
                    <p v-if="form.errors.name" v-text="form.errors.name" class="mt-2 text-sm text-red-600"></p>
                </div>

                <div class="mb-6">
                    <label 
                        for="email" 
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                        Email Address
                    </label>
                    <input 
                        v-model="form.email"
                        type="email" 
                        id="email" 
                        class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        placeholder="" 
                        required
                    />
                    <p v-if="form.errors.email" v-text="form.errors.email" class="mt-2 text-sm text-red-600"></p>
                </div>

                <div class="mb-6">
                    <label 
                        for="password" 
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200"
                        >
                        Password
                    </label>
                    <input 
                        v-model="form.password"
                        type="password" 
                        id="password" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        required  
                    />
                    <p v-if="form.errors.password" v-text="form.errors.password" class="mt-2 text-sm text-red-600"></p>
                </div>

                <div class="mb-6">
                    <label 
                        for="confirmPassword" 
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200"
                        >
                        Confirm Password
                    </label>
                    <input 
                        v-model="form.confirmPassword"
                        type="password" 
                        id="confirmPassword" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        required  
                    />
                    <p v-if="form.errors.confirmPassword" v-text="form.errors.confirmPassword" class="mt-2 text-sm text-red-600"></p>
                </div>

                <button 
                    type="submit" 
                    class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-700 border border-transparent rounded-lg active:bg-blue-800 hover:bg-blue-900 focus:outline-none focus:shadow-outline-purple"
                    :disabled="form.processing"
                    >
                    Create Account
                </button>
            </form>

              <p class="mt-4">
                <Link
                  class="text-sm font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900"
                  href="/login"
                >
                  Already have an account? Login
              </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup () {
        let form = useForm({
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
        });

        let submit = () => {
            form.post('/register');
        };

        return { form, submit };
    },
};
</script>