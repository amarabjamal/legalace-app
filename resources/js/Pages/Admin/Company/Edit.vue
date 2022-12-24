<template>
    <Head title="Update Company Profile" />

    <main class="mt-10">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-blue-100 rounded-lg" role="alert">
                <svg class="flex-shrink-0 w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="ml-3 text-sm font-medium text-blue-700">
                    {{ $page.props.flash.message }}
                </div>
                <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-blue-100 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <form @submit.prevent="submit">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="flex justify-between items-center px-4 py-5 sm:px-6">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Company Profile</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Company details and information.</p>
                        </div>

                        <div>
                            <Link 
                                href="/company-profile"
                                as="button"  
                                class="mr-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                :disabled="form.processing"
                                >
                                Cancel
                            </Link>

                            <button 
                                type="submit" 
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                                :disabled="form.processing"
                                >
                                Save
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <!-- Input -->
                                <div>
                                    <input 
                                        v-model="form.name"
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        placeholder="" 
                                        required
                                    />
                                    <p v-if="form.errors.name" v-text="form.errors.name" class="mt-2 text-sm text-red-600"></p>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <!-- Input -->
                                <div>
                                    <input 
                                        v-model="form.registration_num"
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        placeholder="" 
                                        required
                                    />
                                    <p v-if="form.errors.registration_num" v-text="form.errors.registration_num" class="mt-2 text-sm text-red-600"></p>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <!-- Input -->
                                <div>
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        placeholder="" 
                                        required
                                    />
                                    <p v-if="form.errors.email" v-text="form.errors.email" class="mt-2 text-sm text-red-600"></p>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Website URL</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <!-- Input -->
                                <div>
                                    <input 
                                        v-model="form.website"
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        placeholder="" 
                                        required
                                    />
                                    <p v-if="form.errors.website" v-text="form.errors.website" class="mt-2 text-sm text-red-600"></p>
                                </div>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Office Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <!-- Input -->
                                <div>
                                    <input 
                                        v-model="form.address"
                                        type="text" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        placeholder="" 
                                        required
                                    />
                                    <p v-if="form.errors.address" v-text="form.errors.address" class="mt-2 text-sm text-red-600"></p>
                                </div>
                            </dd>
                        </div>
                    </dl>
                    </div>
                </div>
            </form>

        </div>
    </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup (props) {
        let form = useForm({
            name: props.company_profile.name,
            registration_num: props.company_profile.registration_no,
            email: props.company_profile.email,
            website: props.company_profile.website,
            address: props.company_profile.address,
        });

        let submit = () => {
            form.put(`/company-profile/${props.company_profile.id}`);
        };

        return { form, submit }
    },
    props: {
        company_profile: Object,
    },
    components: { Head },
    layout: Layout,
};
</script>