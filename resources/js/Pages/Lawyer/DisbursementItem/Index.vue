<template>
    <Head title="Disbursement Item" />

    <h1 class="mb-6 text-xl font-bold">
        <Link class="text-blue-500 hover:text-blue-600" href="/lawyer/casefiles/">Case Files</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/casefiles/${case_file.id}`">{{ case_file.file_number }}</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <span class="font-medium">Disbursement Items</span>
    </h1>
</template>

<script>
import Layout from '../Shared/Layout';

export default {
    components: {

    },
    layout: Layout,
    props: {
        case_file: Object,
    },
    data() {
        return {

        }
    },
    methods: {

    },
}

</script>