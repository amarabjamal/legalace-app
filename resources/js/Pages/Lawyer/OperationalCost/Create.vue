<template>
    <Head title="Add new operational cost" />

    <page-heading :breadcrumbs="breadcrumbs" />

    <div
        class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden"
    >
        <form @submit.prevent="store">
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
                        <select-input
                            v-model="form.description"
                            :error="form.errors.description"
                            label="Description"
                            required
                        >
                            <option disabled value="">
                                Select Description
                            </option>
                            <option value="insurance">Insurance</option>
                            <option value="photocopy">Photocopy</option>
                            <option value="rental">Rental</option>
                            <option value="electric">Electric</option>
                            <option value="membership_bar_council">
                                Membership Bar Council
                            </option>
                            <option value="audit_fee">Audit Fee</option>
                            <option value="employee_salary">
                                Employee Salary
                            </option>
                            <option value="subscripton_fee">
                                Subscription Fee
                            </option>
                            <option value="other">others</option>
                        </select-input>
                        <select-input
                            v-model="form.account"
                            :error="form.errors.account"
                            label="Account Type"
                            required
                        >
                            <option disabled value="">
                                Please select account type
                            </option>
                            <option value="1">Client</option>
                            <option value="2">Firm</option>
                        </select-input>

                        <text-input
                            v-model="form.document_number"
                            :error="form.errors.document_number"
                            label="Document Number"
                            required
                        />
                        <file-input
                            v-model="form.upload"
                            :errors="form.errors.upload"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            label="Upload Document"
                            accept=".jpg,.png,.pdf,.doc,.docx"
                        />

                        <text-input
                            v-model="form.amount"
                            :error="form.errors.company_name"
                            label="Amount"
                            required
                        />
                        <select-input
                            v-model="form.payment_method"
                            :error="form.errors.payment_method"
                            label="Payment Menthod"
                            required
                        >
                            <option disabled value="">
                                Please payment method
                            </option>
                            <option value="bank_remittance">
                                Bank Remittance
                            </option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select-input>
                        <select-input
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
                        </select-input>

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
                    >Create</loading-button
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
import { Switch } from "@headlessui/vue";

export default {
    props: {
        firmAccounts: Object,
    },
    components: {
        Head,
        TextInput,
        SelectInput,
        DateInput,
        LoadingButton,
        FileInput,
        Switch,
    },
    layout: Layout,
    data() {
        return {
            breadcrumbs: [
                { link: "/lawyer/operational-cost", label: "Operational Cost" },
                { link: null, label: "Create" },
            ],
            form: this.$inertia.form({
                date: "",
                description: "",
                account: "",
                is_recurring: "",
                document_number: "",
                upload: null,
                amount: "",
                payment_method: "",
                is_recurring: "",
                first_payment_date: "",
                frequency: "",
                no_of_payment: "",
            }),
        };
    },
    methods: {
        store() {
            if (this.form.isDirty) {
                this.form.post(`/lawyer/operational-cost`);
            } else {
                alert("You need to fill in the form first.");
            }
        },
    },
};
</script>
