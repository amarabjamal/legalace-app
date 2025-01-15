<template>
    <Head title="Edit operational cost" />

    <page-heading :breadcrumbs="breadcrumbs" />

    <div
        class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden"
    >
        <form @submit.prevent="update">
            <div class="p-8 space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Operational Cost
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        The operational cost.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <date-input
                            v-model="form.date"
                            :error="form.errors.date"
                            label="Date"
                            required
                        />
                        <div class="flex flex-col">
                            <select-input
                                v-model="form.details"
                                :error="form.errors.details"
                                label="Description"
                                required
                            >
                                <option disabled value="">
                                    Select Description
                                </option>
                                <option
                                    v-for="cost_type in cost_types"
                                    :key="cost_type.id"
                                    :value="cost_type.name"
                                >
                                    {{ formatString(cost_type.name) }}
                                </option>
                            </select-input>
                            <a
                                href="#"
                                @click.prevent="showModal = true"
                                class="text-sm text-blue-500 hover:text-blue-700 mt-2"
                            >
                                Add Cost Type
                            </a>
                        </div>
                        <select-input
                            v-model="form.account"
                            :error="form.errors.account"
                            label="Account Type"
                            required
                        >
                            <option disabled value="">
                                Please select account type
                            </option>
                            <!-- <option value="1">Client</option> -->
                            <option value="2">Firm Account</option>
                        </select-input>

                        <text-input
                            v-model="form.document_number"
                            :error="form.errors.document_number"
                            label="Document Number"
                            required
                        />
                        <div
                            v-if="
                                this.costs_item.upload !== null &&
                                this.costs_item.upload !== ''
                            "
                        >
                            <text-input
                                v-model="this.costs_item.upload"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                value="test"
                                label="Upload Document"
                                accept=".jpg,.png,.pdf,.doc,.docx"
                                disabled
                            />
                            <Button
                                v-on:click="remove()"
                                as="button"
                                class="font-medium text-red-600 hover:underline"
                                >Remove</Button
                            >
                        </div>
                        <file-input
                            v-else
                            v-model="form.upload"
                            :errors="form.errors.upload"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            label="Upload Document"
                            accept=".jpg,.png,.pdf,.doc,.docx"
                            disabled
                        />
                        <text-input
                            v-model="form.amount"
                            :error="form.errors.company_name"
                            :type="'number'"
                            label="Amount"
                            step="0.01"
                            min="0"
                            required
                        />
                        <select-input
                            v-model="form.payment_method"
                            :error="form.errors.payment_method"
                            label="Payment Method"
                            required
                        >
                            <option disabled value="">
                                Please payment method
                            </option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="credit_card">Credit Card</option>
                        </select-input>
                        <!-- <select-input
                            v-model="form.is_recurring"
                            :error="form.errors.is_recurring"
                            label="Recurring Transaction"
                            required
                        >
                            <option disabled value="">
                                Please select recurring transaction
                            </option>
                            <option value="1">Yes</option>
                            <option value="0">no</option>
                        </select-input> -->

                        <div v-if="form.is_recurring == 1">
                            <date-input
                                v-model="form.first_payment_date"
                                :error="form.errors.first_payment_date"
                                label="First Payment Date"
                            />
                        </div>

                        <div v-if="form.is_recurring == 1">
                            <select-input
                                v-model="form.frequency"
                                :error="form.errors.frequency"
                                label="Frequency"
                            >
                                <option disabled value="">
                                    Please select frequency
                                </option>
                                <option value="single">Single</option>
                                <option value="weekly">Weekly</option>
                                <option value="2weeks">Every 2 Weeks</option>
                                <option value="month">Every Month</option>
                                <option value="quarter">Every Quarter</option>
                                <option value="halfYearly">Half Yearly</option>
                                <option value="yearly">Yearly</option>
                            </select-input>
                        </div>

                        <div v-if="form.is_recurring == 1">
                            <select-input
                                v-model="form.no_of_payment"
                                :error="form.errors.no_of_payment"
                                label="No. of payment"
                            >
                                <option disabled value="">
                                    Please select frequency
                                </option>
                                <option value="unlimited">Unlimited</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select-input>
                        </div>
                        <!-- <div v-if="errors">
                            <ul>
                                <li v-for="(error, key) in errors" :key="key">
                                    {{ error }}
                                </li>
                            </ul>
                        </div> -->
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
                    >Update</loading-button
                >
                <Link
                    :href="`/lawyer/operational-cost/`"
                    as="button"
                    class="btn-cancel"
                    :disabled="form.processing"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
    <!-- Modal for adding new cost type -->
    <AddCostModal v-if="showModal" @close="showModal = false">
        <template #header>
            <h2>Add New Cost Type</h2>
        </template>
        <template #body>
            <form @submit.prevent="addCostType">
                <text-input
                    v-model="newCostType.name"
                    label="Name"
                    required
                    :error="form.errors.name"
                />
                <text-input
                    v-model="newCostType.description"
                    label="Description"
                    :error="form.errors.description"
                />
                <div class="mt-4">
                    <button type="submit" class="btn-primary">Save</button>
                    <button
                        type="button"
                        class="btn-cancel"
                        @click="showModal = false"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </template>
    </AddCostModal>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";
import TextInput from "../../../Shared/TextInput";
import SelectInput from "../../../Shared/SelectInput";
import DateInput from "../../../Shared/DateInput";
import LoadingButton from "../../../Shared/LoadingButton";
import FileInput from "../../../Shared/FileInput";
import AddCostModal from "../../../Shared/AddCostModal.vue";
import { Switch } from "@headlessui/vue";

export default {
    props: {
        costs_item: Object,
        cost_types: Object,
        errors: Object,
    },
    components: {
        Head,
        TextInput,
        SelectInput,
        DateInput,
        LoadingButton,
        FileInput,
        Switch,
        AddCostModal,
    },
    layout: Layout,
    data() {
        return {
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: "/lawyer/operational-cost", label: "Operational Cost" },
                {
                    link: null,
                    label: this.formatString(this.costs_item.details),
                },
                { link: null, label: "Edit" },
            ],
            form: this.$inertia.form({
                id: this.costs_item.id,
                date: this.costs_item.date,
                details: this.costs_item.details,
                account: this.costs_item.bank_account_id,
                is_recurring: this.costs_item.is_recurring,
                document_number: this.costs_item.document_number,
                upload: null,
                amount: this.costs_item.amount,
                payment_method: this.costs_item.payment_method,
                first_payment_date: this.costs_item.first_payment_date,
                frequency: this.costs_item.recurring_period,
                no_of_payment: this.costs_item.no_of_payment,
                transaction_id: this.costs_item.transaction_id,
                existingDocument: this.costs_item.upload,
            }),
            showModal: false, // Control modal visibility
            newCostType: {
                name: "",
                description: "",
            },
        };
    },
    methods: {
        update() {
            if (this.form.isDirty) {
                if (
                    this.costs_item.upload == null &&
                    this.form.upload == null
                ) {
                    alert("You need to attach the document first.");
                } else {
                    this.form.post("/lawyer/operational-cost/update");
                }
            } else {
                alert("You need to fill in the form first.");
            }
        },
        remove() {
            this.costs_item.upload = null;
        },
        formatString(str) {
            return str
                .split("_") // Split by underscores
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(" "); // Join the words with spaces
        },
        async addCostType() {
            try {
                const response = await this.$inertia.post(
                    "/lawyer/operational-cost-types",
                    this.newCostType,
                );
                this.showModal = false; // Close the modal
                this.newCostType = { name: "", description: "" }; // Reset the form
                console.log(response);
                if (response.flash.successMessage) {
                    console.log("Cost type added successfully!");
                }
                this.$inertia.reload(); // Reload the page to fetch the updated cost types
            } catch (error) {
                console.error("Error adding cost type:", error);
                // Handle validation errors (if any)
                if (error.response && error.response.data.errors) {
                    this.form.errors = error.response.data.errors;
                }
            }
        },
    },
};
</script>
