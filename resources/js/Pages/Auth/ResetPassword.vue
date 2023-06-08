<template>

    <Head title="Reset Password"/>

    <div class="auth-layout flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
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
                Reset Password
              </h1>

              <div v-if="$page.props.flash.infoMessage" class="flex p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Info</span>
                <div>
                  {{ $page.props.flash.infoMessage }}
                </div>
              </div>

              <div v-if="$page.props.flash.errorMessage" class="flex p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Error</span>
                <div>
                  {{ $page.props.flash.errorMessage }}
                </div>
              </div>

              <div v-if="$page.props.flash.successMessage" class="flex p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Success</span>
                <div>
                  {{ $page.props.flash.successMessage }}
                </div>
              </div>
              
              <form @submit.prevent="submit">

                <div class="mb-6">
                    <label 
                        for="password" 
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                        Password
                    </label>
                    <input 
                        v-model="form.password"
                        type="password" 
                        id="password" 
                        class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        placeholder="" 
                        required
                    />
                    <p v-if="form.errors.password" v-text="form.errors.password" class="mt-2 text-sm text-red-600"></p>
                </div>

                <div class="mb-6">
                    <label 
                        for="password_confirmation" 
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                        Confirm Password
                    </label>
                    <input 
                        v-model="form.password_confirmation"
                        type="password" 
                        id="password_confirmation" 
                        class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        placeholder="" 
                        required
                    />
                    <p v-if="form.errors.password_confirmation" v-text="form.errors.password_confirmation" class="mt-2 text-sm text-red-600"></p>
                </div>

                <button 
                    type="submit" 
                    class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-700 border border-transparent rounded-lg active:bg-blue-800 hover:bg-blue-900 focus:outline-none focus:shadow-outline-purple"
                    :disabled="form.processing"
                    >
                    Reset Password
                </button>
            </form>

            <p class="mt-4">
                <Link
                    class="text-sm font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900"
                    href="/login"
                >
                    <ArrowCircleLeftIcon class="inline-block h-4 w-4"/> Back to login
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
import { ArrowCircleLeftIcon } from "@heroicons/vue/outline";

export default {
    setup (props) {
        let form = useForm({
            token: props.token,
            password: '',
            password_confirmation: ''
        });

        let submit = () => {
            form.post('/resetpassword');
        };

        return { form, submit };
    },
    props: {
        'token': String,
    },
    components: { ArrowCircleLeftIcon },
};
</script>