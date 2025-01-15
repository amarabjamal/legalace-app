<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div
        class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden"
    >
        <form @submit.prevent="update">
            <div class="p-8 space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Personal Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        The personal information of the client.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <text-input
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Name"
                            required
                        />
                        <text-input
                            v-model="form.email"
                            type="email"
                            :error="form.errors.email"
                            label="Email"
                            required
                        />
                        <text-input
                            v-model="form.phone_number"
                            :error="form.errors.phone_number"
                            label="Phone Number"
                            required
                        />
                        <text-input
                            v-model="form.company_name"
                            :error="form.errors.company_name"
                            label="Company Name"
                            required
                        />
                        <text-input
                            v-model="form.company_address"
                            :error="form.errors.company_address"
                            label="Company Address"
                            required
                        />
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Identity Details
                    </h2>
                    <!-- <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information is only visible to admin.
                    </p> -->

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <select-input
                            v-model="form.id_type_id"
                            :error="form.errors.id_type_id"
                            label="Identification Type"
                            required
                        >
                            <option disabled value="">
                                Please select identification type
                            </option>
                            <option value="1">IC</option>
                            <option value="2">Passport</option>
                        </select-input>
                        <text-input
                            v-model="form.id_number"
                            :error="form.errors.id_number"
                            label="ID Number"
                            required
                        />
                        <select-input
                            v-model="form.linked_client_account"
                            :error="form.errors.id_type_id"
                            label="Linked Client Account"
                            required
                        >
                            <option disabled value="">
                                Linked Client Account
                            </option>
                            <option
                                v-for="client in clients"
                                :value="client.account_name"
                            >
                                {{ client.account_name }}
                            </option>
                        </select-input>
                        <!-- <text-input
                            v-model="form.outstanding_balance"
                            :error="form.errors.outstanding_balance"
                            label="Outstanding Balance"
                            required
                        /> -->
                    </div>
                </div>
            </div>

            <div
                class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100"
            >
                <loading-button
                    :loading="form.processing"
                    :disabled="!form.isDirty"
                    class="btn-primary"
                    type="submit"
                    >Update Clients</loading-button
                >
                <Link
                    :href="`/lawyer/client`"
                    as="button"
                    class="btn-cancel"
                    :disabled="form.processing"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from "../../../Shared/TextInput";
import SelectInput from "../../../Shared/SelectInput";
import DateInput from "../../../Shared/DateInput";
import LoadingButton from "../../../Shared/LoadingButton";
import { Switch } from "@headlessui/vue";

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        LoadingButton,
        Switch,
    },
    layout: Layout,
    props: {
        clientProfile: Object,
        clients: Object,
    },
    data() {
        return {
            page_title: this.clientProfile.name,
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: "/lawyer/client", label: "Client" },
                { link: null, label: this.clientProfile.name },
                { link: null, label: "Edit" },
            ],
            form: this.$inertia.form({
                id: this.clientProfile.id,
                name: this.clientProfile.name,
                email: this.clientProfile.email,
                id_number: this.clientProfile.id_number,
                id_type_id: this.clientProfile.id_type_id,
                phone_number: this.clientProfile.phone_number,
                linked_client_account: this.clientProfile.linked_client_account,
                company_name: this.clientProfile.company_name,
                company_address: this.clientProfile.company_address,
                outstanding_balance: this.clientProfile.outstanding_balance,
                access_expiry_date:
                    this.clientProfile.access_expiry_date != null
                        ? this.clientProfile.access_expiry_date.substring(0, 10)
                        : "",
            }),
        };
    },
    methods: {
        update() {
            if (this.form.isDirty) {
                this.form.put(`/lawyer/client/update`);
            } else {
                alert("No changes to be saved");
            }
        },
    },
};
</script>
