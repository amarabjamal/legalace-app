<template>

    <Head title="Register"/>

    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden">
        <div class="flex items-center justify-center p-6 sm:p-12">
          <div class="w-full">
            <div style="background-color:#3c3d5b" class="flex justify-center mb-6 rounded-lg">
              <img
                aria-hidden="true"
                class="object-cover w-1/2 h-1/2 dark:hidden"
                :src="'/images/app/logo.png'"
                alt="Legal Ace Logo"
              />
            </div>
            <h1
              class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Create account
            </h1>
        
            <form @submit.prevent="submit">

              <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                  Personal Information
                </h4>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
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
                  </div>
                  <div class="md:w-1/2 px-3">
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
                  </div>
                </div>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="id_type" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Identification Type
                      </label>
                      <select 
                        v-model="form.id_type"
                        id="id_type" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        required
                      >
                          <option disabled value="">Please select your identification type</option>
                          <option v-for="idType in idTypes" :value="idType.id">{{idType.name}}</option>
                      </select>
                      <p v-if="form.errors.id_type" v-text="form.errors.id_type" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="id_num" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Identification Number
                      </label>
                      <input 
                          v-model="form.id_num"
                          type="text" 
                          id="id_num" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.id_num" v-text="form.errors.id_num" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="employee_id" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Employee ID
                      </label>
                      <input 
                          v-model="form.employee_id"
                          type="text" 
                          id="employee_id" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.employee_id" v-text="form.errors.employee_id" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="contact_num" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Contact Number
                      </label>
                      <input 
                          v-model="form.contact_num"
                          type="tel" 
                          id="contact_num" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.contact_num" v-text="form.errors.contact_num" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="birthdate" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Birthdate
                      </label>
                      <input 
                          v-model="form.birthdate"
                          type="date" 
                          id="birthdate" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.birthdate" v-text="form.errors.birthdate" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                </div>
              </div>


              <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                  Company Information
                </h4>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="company_name" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Name
                      </label>
                      <input 
                          v-model="form.company_name"
                          type="text" 
                          id="company_name" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.company_name" v-text="form.errors.company_name" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="reg_no" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Registration Number
                      </label>
                      <input 
                          v-model="form.reg_no"
                          type="text" 
                          id="reg_no" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.reg_no" v-text="form.errors.reg_no" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                </div>

                <div class="px-3">
                  <div class="mb-6">
                    <label 
                        for="address" 
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                        Address
                    </label>
                    <input 
                        v-model="form.address"
                        type="text" 
                        id="address" 
                        class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                        placeholder="" 
                        required
                    />
                    <p v-if="form.errors.address" v-text="form.errors.address" class="mt-2 text-sm text-red-600"></p>
                  </div>
                </div>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="company_email" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Email
                      </label>
                      <input 
                          v-model="form.company_email"
                          type="email" 
                          id="company_email" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                          required
                      />
                      <p v-if="form.errors.company_email" v-text="form.errors.company_email" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                  <div class="md:w-1/2 px-3">
                    <div class="mb-6">
                      <label 
                          for="website" 
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                          >
                          Website
                      </label>
                      <input 
                          v-model="form.website"
                          type="text" 
                          id="website" 
                          class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:shadow-outline-gray" 
                          placeholder="" 
                      />
                      <p v-if="form.errors.website" v-text="form.errors.website" class="mt-2 text-sm text-red-600"></p>
                    </div>
                  </div>
                </div>                
              </div>
              
              <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                  Account Security
                </h4>

                <div class="flex flex-col overflow-y-auto md:flex-row">
                  <div class="md:w-1/2 px-3">
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
                  </div>
                  <div class="md:w-1/2 px-3">
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
                  </div>
                </div>    
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
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup () {
        let form = useForm({
            name: '',
            email: '',
            id_type: '',
            id_num: '',
            employee_id: '',
            contact_num: '',
            birthdate: '',
            company_name: '',
            reg_no: '',
            address: '',
            company_email: '',
            website: '',
            password: '',
            confirmPassword: '',
        });

        let submit = () => {
            form.post('/register');
        };

        return { form, submit };
    },
    props: {
      idTypes : Object
    },
};
</script>