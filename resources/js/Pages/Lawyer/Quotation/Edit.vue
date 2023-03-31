<template>
    <Head title="Generate Quotation" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">          
            <div class="container px-6 mx-auto grid">
                <Link class="my-6" :href="'/lawyer/casefiles/' + quotation.case_file.id "><i class="uil uil-arrow-left"></i> Back to Case File {{ quotation.case_file.file_number }}</Link>
    
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

                <div v-if="$page.props.flash.successMessage" class="flex p-4 mb-4 bg-green-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        {{ $page.props.flash.successMessage }}
                    </div>
                    <button type="button" @click="$page.props.flash.successMessage = ''" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            Quotation
                        </div>
                    </div>

                    <div class="card-body">
                        <div>
                            File Number: {{ quotation.case_file.file_number }}
                        </div>
                        <!-- <div class="client-info">
                            <div class="client-name">
                                {{  }},
                            </div>
                            <div class="client-address">
                                {{  }}
                            </div>
                        </div> -->

                        <div class="mt-4">
                            Dear sirs,
                            <div class="text-center border-b">
                                Matter: {{  quotation.case_file.matter }}
                            </div>
                        </div>

                        <div>
                            <div>
                                <p>
                                    We refer to the above matter.<br/><br/>

                                    Thank you for considering us for the solicitor works in the above matter. We are pleased to provide our fees structure as follows:-

                                </p>
                            </div>
                        </div>

                        <div class="quotation-section">
                            <h3>1. SCOPE OF SERVICES & LEGAL FEES</h3>

                            <table class="w-full border-collapse border border-slate-400">
                                <thead>
                                    <th class="border border-slate-300 px-6 py-3"></th>
                                    <th class="border border-slate-300 px-6 py-3">Work Descriptions</th>
                                    <th class="border border-slate-300 px-6 py-3">Fee (RM)</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-slate-300 px-6 py-3">1</td>
                                        <td class="border border-slate-300 px-6 py-3"></td>
                                        <td class="border border-slate-300 px-6 py-3"></td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-300 px-6 py-3">2</td>
                                        <td class="border border-slate-300 px-6 py-3"></td>
                                        <td class="border border-slate-300 px-6 py-3"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <p>
                                The aboce fees structure is quoted based on our current understanding of the case. Final fees charged are subjected to the actual type and amount of work done.
                            </p>
                        </div>

                        <div class="quotation-section">
                            <h3>2. DISBURSEMENTS</h3>

                            <p>
                                The above quotation does not include any disbursements. Disbursements will be charged on incurred basis.
                            </p>
                        </div>

                        <div class="quotation-section">
                            <h3>3. TAX</h3>

                            <p>
                                As of the date of this latter, no service tax will be imposed on the legal fees.
                            </p>
                        </div>

                        <div class="quotation-section">
                            <h3>4. INITIAL DEPOSIT</h3>

                            <p>
                                As a policy of our firm, we will request a sum of deposit towards our fees, and disbursements prior to the commencment of work. For this matter, kindly remit a sum of AMOUNT BELOW  as inital deposit to our clients' accounts as follows:-
                            </p> 
                            
                            <form @submit.prevent="submitdepositAmount">
                                <div class="flex">
                                    <div class="my-6 flex items-center w-6/12">
                                        <span class="mr-4 text-lg font-bold">RM</span>
                                        <input 
                                            v-model="depositAmountForm.deposit_amount"
                                            type="number"
                                            step="0.01" 
                                            class="mr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                            placeholder="" 
                                            required
                                        > 
                                    </div>
    
                                    <div class="my-6 flex items-center w-6/12">
                                        <select 
                                            v-model="depositAmountForm.bank_account_id"
                                            id="bank_account_id" 
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                            required
                                        >
                                            <option disabled value="">Please select client account</option>
                                            <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{client_bank_account.label}}</option>
                                        </select>
                                        <p v-if="depositAmountForm.errors.bank_account_id" v-text="depositAmountForm.errors.bank_account_id" class="mt-2 text-sm text-red-600"></p>
    
                                        <button 
                                            class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue"
                                            :disabled="depositAmountForm.processing"
                                            type="submit"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div>
                            <p>
                                Feel free to contact us should you have any queries.<br/><br/>

                                Thank you.<br/><br/>

                                Yours faithfully,
                            </p>
                        </div>
                    </div>

                    <!-- <div class="text-center d-block p-3 card-footer">
                        Footer
                    </div> -->
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

export default { 
    setup (props) {
        let depositAmountForm = useForm({
            'deposit_amount': props.quotation.deposit_amount,
            'bank_account_id' : props.quotation.bank_account_id,
        });

        let addWorkDescriptionForm = useForm({
            
        });

        let submitdepositAmount = () => {
            depositAmountForm.put(`/lawyer/quotations/${props.quotation.id}`);
        };
        
        let submitaddWorkDescription = () => {
            addWorkDescriptionForm.put(`/lawyer${props}`);
        };

        return { depositAmountForm, addWorkDescriptionForm, submitdepositAmount, submitaddWorkDescription };
    },
    components: { Head, Pagination},
    props: {
        quotation : Object,
        client_bank_accounts : Object,
    },
    layout: Layout,
};
</script>

<style scoped>
.client-info {
    margin: 20px 0px;
    padding: 20px 0px;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}

.quotation-section {
    margin: 30px 0px;
    padding: 0px 30px
}

.quotation-section h3 {
    font-weight: bold;
    margin-bottom: 10px;
}
</style>