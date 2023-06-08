<template>
    <nav id="sidebar" :class="`${!isSidebarOpen ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li v-for="(navigation, index) in navigations" :key="index">
                    <div>
                        <Link :href="navigation.href">
                            <icon-sidebar :name="navigation.icon"></icon-sidebar>
                            <span class="link-name">{{ navigation.label }}</span>
                        </Link>
                    </div>
                </li>
            </ul>
            
            <ul v-if="$page.props.auth.user.roles.indexOf('admin') !== -1" class="bottom-container">
                <li>
                    <Link href="/admin">
                        <icon-sidebar name="user-shared-fill" class="w-7 h-7"></icon-sidebar>
                        <span class="link-name color-slate-700  uppercase mr-2">Switch to Admin</span>
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
                {href: '/lawyer', label: 'Dashboard', icon:'home-smile-line', isActive: this.$page.url.startsWith('/lawyer')},
                {href: '/lawyer/clients', label: 'Client Profiles', icon:'group-line', isActive: this.$page.url.startsWith('/lawyer/clients')},
                {href: '/lawyer/case-files', label: 'Case Files', icon:'folder-5-line', isActive: this.$page.url.startsWith('/lawyer/case-files')},
                {href: '/lawyer', label: 'Firm Accounts', icon:'increase-decrease-line', isActive: this.$page.url.startsWith('/lawyer/firm-accounts')},
                {href: '/lawyer', label: 'Client Accounts', icon:'booklet-line', isActive: this.$page.url.startsWith('/lawyer/client-accounts')},
                {href: '/lawyer/claim-vouchers', label: 'Claim Vouchers', icon:'ticket-line', isActive: this.$page.url.startsWith('/lawyer/claim-vouchers')},
            ],
        }
    },
    components: {
        IconSidebar,
    },
};
</script>