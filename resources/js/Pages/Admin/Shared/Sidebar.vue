<template>
    <nav id="sidebar" :class="`${!isSidebarOpen ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li class="logo-container"><div class="logo"></div></li>
                
                <li v-for="(navigation, index) in navigations" :key="index">
                    <div>
                        <Link :href="navigation.href">
                            <icon-sidebar :name="navigation.icon"></icon-sidebar>
                            <span class="link-name">{{ navigation.label }}</span>
                        </Link>
                    </div>
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
    data() {
        return {
            navigations: [
                {href: '/admin/dashboard', label: 'Dashboard', icon:'home-smile-line', isActive: this.$page.url.startsWith('/lawyer')},
                {href: '/admin/company', label: 'Company Profile', icon:'donut-chart-line', isActive: this.$page.url.startsWith('/admin/company')},
                {href: '/admin/users', label: 'Employees', icon:'group-line', isActive: this.$page.url.startsWith('/admin/users')},
                {href: '/admin/bank-accounts', label: 'Bank Accounts', icon:'bank-card-line', isActive: this.$page.url.startsWith('/admin/bank-accounts')},
                {href: '/admin/voucherapprovals', label: 'Voucher Requests', icon:'inbox-line', isActive: this.$page.url.startsWith('/admin/voucherapprovals')},
            ],
        }
    },
    components: {
        IconSidebar,
    },
};
</script>