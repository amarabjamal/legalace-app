<template>
    <Head title="Create Case File" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Create Case File
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
                                for="matter" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                Matter
                            </label>
                            <input 
                                v-model="form.matter"
                                type="text" 
                                id="matter" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.matter" v-text="form.errors.matter" class="mt-2 text-sm text-red-600"></p>
                        </div>

                        <div class="mb-6">
                            <label 
                                for="type" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                Type
                            </label>
                            <input 
                                v-model="form.type"
                                type="text" 
                                id="type" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.type" v-text="form.errors.type" class="mt-2 text-sm text-red-600"></p>
                        </div>

                        <div class="mb-6">
                            <label 
                                for="file_number" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                File No.
                            </label>
                            <input 
                                v-model="form.file_number"
                                type="text" 
                                id="file_number" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.file_number" v-text="form.errors.file_number" class="mt-2 text-sm text-red-600"></p>
                        </div>

                        <div class="mb-6">
                            <label 
                                for="client_id" 
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                                >
                                Client
                            </label>
                            <select 
                                v-model="form.client_id"
                                id="client_id" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                required
                            >
                                <option disabled value="">Please select your client</option>
                                <option v-for="client in clients" :value="client.id">{{client.name}}</option>
                            </select>
                            <p v-if="form.errors.client_id" v-text="form.errors.client_id" class="mt-2 text-sm text-red-600"></p>
                        </div>

                        <!-- <div class="mb-6">
                            <label 
                                for="lawyer_ids" 
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                                >
                                Lawyer
                            </label>
                            <select 
                                v-model="form.lawyer_ids"
                                id="lawyer_ids" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                multiple
                            >
                                <option disabled value="">Add lawyer</option>
                                <option v-for="lawyer in lawyers" :value="lawyer.id">{{lawyer.name}}</option>
                            </select>
                            <p v-if="form.errors.lawyer_ids" v-text="form.errors.lawyer_ids" class="mt-2 text-sm text-red-600"></p>
                        </div> -->

                        <!-- <div>
                            <label class="typo__label">Tagging</label>
                            <multiselect v-model="value" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="name" track-by="code" :options="options" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                            <pre class="language-json"><code>{{ value  }}</code></pre>
                        </div>    -->

                        <button 
                            type="submit" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            :disabled="form.processing"
                            >
                            Submit
                        </button>

                        <Link 
                            href="/lawyer/casefiles"
                            as="button"  
                            class="ml-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            :disabled="form.processing"
                            >
                            Cancel
                        </Link>
                    </form>
                </div>
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../Shared/Pagination";
import { useForm } from "@inertiajs/inertia-vue3";
import Multiselect from "vue-multiselect";

export default { 
    setup () {
        let form = useForm({
            matter: '',
            type: '',
            file_number: '',
            client_id: '',
        });

        let submit = () => {
            form.post('/lawyer/casefiles');
        };

        return { form, submit };
    },
    components: { Head, Pagination, Multiselect },
    props: {
        clients: Object,
        lawyers: Object,
    },
    data () {
        return {
            value: [
                { name: 'Javascript', code: 'js' }
            ],
            options: [
                { name: 'Vue.js', code: 'vu' },
                { name: 'Javascript', code: 'js' },
                { name: 'Open Source', code: 'os' }
            ]
        }
    },
    methods: {
        addTag (newTag) {
        const tag = {
            name: newTag,
            code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
        }
        this.options.push(tag)
        this.value.push(tag)
        }
    },
    layout: Layout,
};
</script>