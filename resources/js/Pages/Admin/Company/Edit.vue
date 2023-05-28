<template>
    <Head title="Edit company profile" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white shadow-md overflow-hidden rounded-md">
        <form @submit.prevent="update">
            <div class="flex justify-between items-center px-4 py-5 sm:px-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Company Profile</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Company details and information.</p>
                </div>

                <div>
                    <Link 
                        href="/admin/company"
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
                                v-model="form.reg_number"
                                type="text" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.reg_number" v-text="form.errors.reg_number" class="mt-2 text-sm text-red-600"></p>
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
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
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
            </dl>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import PageHeading from "../../../Shared/PageHeading";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup (props) {
        let form = useForm({
            name: props.company.name,
            reg_number: props.company.reg_number,
            address: props.company.address,
            email: props.company.email,
            website: props.company.website,
        });

        let update = () => {
            form.put(`/admin/company/${props.company.id}`);
        };

        return { form, update }
    },
    components: { 
        PageHeading,
    },
    layout: Layout,
    props: {
        company: Object,
    },
    data() {
        return {
            page_title: 'Edit Company Profile',
            breadcrumbs: [
                { link: '/admin', label: 'Dashboard'},
                { link: '/admin/company', label: 'Company Profile'},
                { link: null, label: 'Edit'},
            ],
        }
    },
};
</script>