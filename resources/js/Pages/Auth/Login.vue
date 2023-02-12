<template>

    <Head title="Sign In"/>

    <div 
      class="auth-layout flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900"
      >
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
                Sign in to your account
              </h1>

              <div v-if="$page.props.flash.infoMessage" class="flex p-4 mb-4 bg-blue-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-blue-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-blue-900">
                        {{ $page.props.flash.infoMessage }}
                    </div>
                    <button type="button" @click="$page.props.flash.infoMessage = ''" class="ml-auto -mx-1.5 -my-1.5 bg-blue-100 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
              
              <form @submit.prevent="submit">
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

                <button 
                    type="submit" 
                    class="standard-button block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none"
                    :disabled="form.processing"
                    >
                    Sign In
                </button>
            </form>

              <p class="mt-4">
                <Link
                  class="text-sm font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900"
                  href="/forgotpassword"
                >
                  Forgot your password?
              </Link>
              </p>
              <p class="mt-1">
                <Link
                  class="text-sm font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900"
                  href="/register"
                >
                  Create account
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