<template>
    <Head title="Dashboard" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="" />
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Account Overview
                </h2>

                <table>
                    <tr>
                        <td>Name</td>
                        <td class="font-bold">{{ $props.user.name }}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td class="font-bold">{{ $props.user.email }}</td>
                    </tr>
                    <tr>
                        <td>Identification No.</td>
                        <td class="font-bold">{{ $props.user.id_num }}</td>
                    </tr>
                    <tr>
                        <td>Employee ID</td>
                        <td class="font-bold">{{ $props.user.employee_id }}</td>
                    </tr>
                    <tr>
                        <td>Contact No.</td>
                        <td class="font-bold">{{ $props.user.contact_num }}</td>
                    </tr>
                    <tr>
                        <td>Birthdate</td>
                        <td class="font-bold">{{ $props.user.birthdate }}</td>
                    </tr>
                    <tr>
                        <td>Roles</td>
                        <td>
                            <div v-for="user_role in user.user_roles">
                                {{ user_role.role.name }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td class="font-bold">{{ $props.user.company.name }}</td>
                    </tr>
                </table>
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "./Shared/Layout";
import Header from "./Shared/Header";
import Sidebar from "./Shared/Sidebar";

export default { 
    props: {
        user: Object,
    },
    components: { Head, Header, Sidebar },
    layout: Layout,
};
</script>