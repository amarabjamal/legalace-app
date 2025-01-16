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
                        The personal information of the employee.
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
                            v-model="form.contact_number"
                            :error="form.errors.contact_number"
                            label="Contact Number"
                            required
                        />
                        <date-input
                            v-model="form.birthdate"
                            :error="form.errors.birthdate"
                            label="Birthdate"
                            required
                        />
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Identity Details
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information is only visible to admin.
                    </p>

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
                            <option
                                v-for="id_type in idTypes"
                                :value="id_type.id"
                            >
                                {{ id_type.name }}
                            </option>
                        </select-input>
                        <text-input
                            v-model="form.id_number"
                            :error="form.errors.id_number"
                            label="Identification Number"
                            required
                        />
                        <text-input
                            v-model="form.employee_id"
                            :error="form.errors.employee_id"
                            label="Employee ID"
                            required
                        />
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Assign Role
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        The role will determine the employee's permissions.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <div class="md:col-span-2">
                            <label for="role" class="form-label">Roles</label>

                            <ul class="grid w-1/2 gap-2 md:grid-cols-2">
                                <li>
                                    <input
                                        v-model="form.isAdmin"
                                        type="checkbox"
                                        id="is_admin"
                                        value="admin"
                                        class="hidden peer"
                                    />
                                    <label
                                        for="is_admin"
                                        class="inline-flex items-center justify-between w-40 p-3 text-gray-300 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 hover:text-gray-600 peer-checked:text-gray-600 hover:bg-gray-50"
                                    >
                                        <div
                                            class="block w-full text-sm text-center font-semibold"
                                        >
                                            Administrator
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <input
                                        v-model="form.isLawyer"
                                        type="checkbox"
                                        id="is_lawyer"
                                        value="lawyer"
                                        class="hidden peer"
                                    />
                                    <label
                                        for="is_lawyer"
                                        class="inline-flex items-center justify-between w-40 p-3 text-gray-300 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 hover:text-gray-600 peer-checked:text-gray-600 hover:bg-gray-50"
                                    >
                                        <div
                                            class="block w-full text-sm text-center font-semibold"
                                        >
                                            Lawyer
                                        </div>
                                    </label>
                                </li>
                            </ul>

                            <p
                                v-if="form.errors.role"
                                v-text="form.errors.role"
                                class="mt-2 text-sm text-red-600"
                            ></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Danger Zone
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Turn on the access toggle to allow the employee to
                        access their account.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <div>
                            <label for="is_active" class="form-label"
                                >Enabled</label
                            >
                            <Switch
                                v-model="form.is_active"
                                :class="
                                    form.is_active
                                        ? 'bg-green-400'
                                        : 'bg-gray-200'
                                "
                                class="relative inline-flex h-[38px] w-[74px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                            >
                                <span class="sr-only">Enabled</span>
                                <span
                                    aria-hidden="true"
                                    :class="
                                        form.is_active
                                            ? 'translate-x-9'
                                            : 'translate-x-0'
                                    "
                                    class="pointer-events-none inline-block h-[34px] w-[34px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                            <p
                                v-if="form.errors.is_active"
                                v-text="form.errors.is_active"
                                class="mt-2 text-sm text-red-600"
                            ></p>
                        </div>
                        <date-input
                            v-model="form.access_expiry_date"
                            :error="form.errors.access_expiry_date"
                            label="Access Expiry Date"
                        />
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
                    >Update Employee</loading-button
                >
                <Link
                    :href="`/admin/users/`"
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
        user: Object,
        idTypes: Object,
    },
    data() {
        return {
            page_title: this.user.name,
            breadcrumbs: [
                { link: "/admin/dashboard", label: "Admin" },
                { link: "/admin/users", label: "Employees" },
                { link: null, label: this.user.name },
                { link: null, label: "Edit" },
            ],
            form: this.$inertia.form({
                id: this.user.id,
                name: this.user.name,
                email: this.user.email,
                id_type_id: this.user.id_type_id,
                id_number: this.user.id_number,
                employee_id: this.user.employee_id,
                isAdmin: this.user.isAdmin,
                isLawyer: this.user.isLawyer,
                contact_number: this.user.contact_number,
                birthdate: this.user.birthdate.substring(0, 10),
                is_active: this.user.is_active,
                access_expiry_date:
                    this.user.access_expiry_date != null
                        ? this.user.access_expiry_date.substring(0, 10)
                        : "",
            }),
        };
    },
    methods: {
        update() {
            if (this.form.isDirty) {
                this.form.put(`/admin/users/${this.user.id}`);
            } else {
                alert("No changes to be saved");
            }
        },
    },
};
</script>
