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
                        Item Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Provide an identifiable name.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2"
                    >
                        <select-input
                            v-model="form.record_type_id"
                            :error="form.errors.record_type_id"
                            label="Record Type"
                            required
                        >
                            <option disabled value="">
                                Select record type
                            </option>
                            <option
                                v-for="record_type in record_types"
                                :value="record_type.id"
                            >
                                {{ record_type.name }}
                            </option>
                        </select-input>

                        <text-input
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Name"
                            required
                        />
                        <text-input
                            v-model="form.description"
                            :error="form.errors.description"
                            label="Description (Optional)"
                        />
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Payment Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Only the creater of the item can reimburse the amount
                        give the fund type is paid by lawyer.
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
                            v-model="form.fund_type"
                            :error="form.errors.fund_type"
                            label="Fund Type"
                            required
                        >
                            <option :value="null" disabled></option>
                            <option
                                v-for="fund_type in fund_types"
                                :value="fund_type.value"
                                required
                            >
                                {{ fund_type.label }}
                            </option>
                        </select-input>
                        <money-input
                            v-model.lazy="form.amount"
                            :error="form.errors.amount"
                            label="Amount"
                            required
                        />
                        <file-input
                            v-model="form.receipt"
                            :error="form.errors.receipt"
                            label="Receipt"
                            accept=".jpg,.png,.pdf,.doc,.docx"
                            required
                        />
                        <div v-if="disbursement_item.receipt">
                            <label class="form-label">Uploaded File</label>
                            <div class="form-input">
                                <div class="flex">
                                    <icon
                                        name="file-fill"
                                        class="block w-6 h-6 fill-blue-500"
                                    ></icon>
                                    <div
                                        class="ml-2 whitespace-nowrap overflow-ellipsis overflow-hidden"
                                    >
                                        {{ disbursement_item.receipt }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-row-reverse items-center justify-between px-8 py-4 bg-gray-50 border-t border-gray-100"
            >
                <div class="flex flex-row-reverse space-x-2 space-x-reverse">
                    <loading-button
                        :loading="form.processing"
                        :disabled="!form.isDirty"
                        class="btn-primary"
                        type="submit"
                        >Saves Changes</loading-button
                    >
                    <Link
                        :href="`/lawyer/case-files/${this.case_file.id}/disbursement-items/${disbursement_item.id}`"
                        as="button"
                        class="btn-cancel"
                        :disabled="form.processing"
                    >
                        Cancel
                    </Link>
                </div>
                <button
                    class="text-red-600 hover:underline"
                    tabindex="-1"
                    type="button"
                    @click="destroy"
                >
                    Delete Item
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from "../../../Shared/TextInput";
import SelectInput from "../../../Shared/SelectInput";
import DateInput from "../../../Shared/DateInput";
import FileInput from "../../../Shared/FileInput";
import MoneyInput from "../../../Shared/MoneyInput";
import LoadingButton from "../../../Shared/LoadingButton";
import Icon from "../../../Shared/Icon";
import { unmaskMoneyToNumeric } from "../../../Stores/Utils";

export default {
    components: {
        TextInput,
        SelectInput,
        DateInput,
        FileInput,
        MoneyInput,
        LoadingButton,
        Icon,
    },
    layout: Layout,
    props: {
        case_file: Object,
        disbursement_item: Object,
        fund_types: Object,
        record_types: Object,
    },
    remember: "form",
    data() {
        return {
            form: this.$inertia.form({
                _method: "put",
                date: this.disbursement_item.date,
                name: this.disbursement_item.name,
                description: this.disbursement_item.description,
                amount: this.disbursement_item.amount.amount,
                receipt: null,
                record_type_id: this.disbursement_item.record_type_id,
                fund_type: this.disbursement_item.fund_type,
            }),
            page_title: this.disbursement_item.name,
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: "/lawyer/case-files/", label: "My Cases" },
                {
                    link: `/lawyer/case-files/${this.case_file.id}`,
                    label: this.case_file.file_number,
                },
                {
                    link: `/lawyer/case-files/${this.case_file.id}/disbursement-items`,
                    label: "Items",
                },
                {
                    link: `/lawyer/case-files/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}`,
                    label: this.disbursement_item.name,
                },
                { link: null, label: "Edit" },
            ],
        };
    },
    methods: {
        update() {
            if (this.form.isDirty) {
                this.form.amount = unmaskMoneyToNumeric(this.form.amount);
                this.form.post(
                    `/lawyer/case-files/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}`,
                    {
                        onSuccess: () => this.form.reset("receipt"),
                    },
                );
            } else {
                alert("No changes to save.");
            }
        },
        destroy() {
            if (confirm("Are you sure you want to delete this item?")) {
                this.$inertia.delete(
                    `/lawyer/case-files/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}`,
                );
            }
        },
    },
};
</script>
