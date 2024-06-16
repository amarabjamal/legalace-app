<template>
    <Sidebar :expendSidebar="expendSidebar"/>

    <section class="dashboard">
        <!-- Header -->
        <page-header :expendSidebar="expendSidebar" @toggle-sidebar="toggleSidebar" />

        <!-- Page Content -->
        <div class="dash-content">
            <div class="flex flex-col flex-1">
                <main class="h-full pb-16 overflow-y-auto">          
                    <div class="container px-6 mx-auto grid pt-6">
                        <flash-messages/>
                        <slot />
                    </div>
                </main>
            </div>
        </div>

        <layout-footer />
    </section>
</template>

<script>
import Sidebar from './Sidebar';
import PageHeader from '../../../Shared/PageHeader';
import FlashMessages from '../../../Shared/FlashMessages';
import LayoutFooter from '../../../Shared/LayoutFooter';

export default {
    components: { 
        Sidebar, 
        PageHeader,
        FlashMessages,
        LayoutFooter,
     },
    data() {
        return {
            expendSidebar: false,
        }
    },
    methods: {
        toggleSidebar() {
            this.expendSidebar = !this.expendSidebar;
        },
    }
};
</script>