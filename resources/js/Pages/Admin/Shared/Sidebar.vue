<template>
    <nav id="sidebar" :class="`${!isSidebarOpen ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li>
                    <Link href="/admin">
                        <icon-sidebar name="home-smile-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin/company">
                        <icon-sidebar name="donut-chart-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Company Profile</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin/users">
                        <icon-sidebar name="group-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">User Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin/bankaccounts">
                        <icon-sidebar name="bank-card-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Bank Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin/voucherapprovals">
                        <icon-sidebar name="inbox-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Voucher Requests</span>
                    </Link>
                </li>
            </ul>
            
            <ul v-if="$page.props.auth.user.roles.indexOf('lawyer') !== -1" class="bottom-container">
                <li>
                    <Link href="/lawyer" class="bg-blue-400 shadow">
                        <icon-sidebar name="user-shared-fill" class="w-7 h-7"></icon-sidebar>
                        <span class="link-name color-slate-700  uppercase mr-2">Switch to Lawyer</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import IconSidebar from '../../../Shared/IconSidebar';

export default {
    props: {
        isSidebarOpen: Boolean,
    },
    components: {
        IconSidebar,
    },
};
</script>