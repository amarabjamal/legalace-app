<template>
    <Head title="Settings" />

    <main class="mt-10">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="flex justify-between items-center px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Profile</h3>
                    </div>

                    <div>
                        <Link
                            href="#"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            >
                            Update
                        </Link>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            
                        </dd>
                    </div>
                </dl>
                </div>
            </div>

        </div>
    </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "./Shared/Layout";

export default { 
    components: { Head },
    layout: Layout,
};
</script>