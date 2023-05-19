<template>
    <nav id="sidebar" :class="`${!isSidebarOpen ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li>
                    <div>
                        <Link href="/lawyer">
                            <icon-sidebar name="home-smile-line" class="mr-2 w-5 h-5"></icon-sidebar>
                            <span class="link-name">Dashboard</span>
                        </Link>
                    </div>
                </li>
                <li>
                    <Link href="/lawyer/clients" :class="{'color-blue-500' : $page.url.startsWith('/lawyer/clients')}">
                        <icon-sidebar name="group-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Client Profiles</span>
                    </Link>
                </li>
                <li>
                    <Link href="/lawyer/casefiles">
                        <icon-sidebar name="folder-5-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Case Files</span>
                    </Link>
                </li>
                <li>
                    <Link href="/lawyer">
                        <icon-sidebar name="increase-decrease-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Firm Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/lawyer">
                        <icon-sidebar name="booklet-line" class="mr-2 w-5 h-5"></icon-sidebar>
                        <span class="link-name">Client Accounts</span>
                    </Link>
                </li>
                <li>
                    <Link href="/lawyer">
                        <icon-sidebar name="ticket-line"></icon-sidebar>
                        <span class="link-name">Claim Vouchers</span>
                    </Link>
                </li>
            </ul>
            
            <ul v-if="$page.props.auth.user.roles.indexOf('admin') !== -1" class="bottom-container">
                <li>
                    <Link href="/admin" class="bg-blue-400 shadow">
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
    components: {
        IconSidebar,
    },
};
</script>