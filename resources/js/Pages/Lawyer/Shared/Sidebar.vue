<template>
    <nav id="sidebar" :class="`${!expendSidebar ? 'close' : ''}`">
        <div class="menu-items">
            <ul class="nav-links">
                <li class="logo-container"><div class="logo"></div></li>

                <li v-for="(navigation, index) in navigations" :key="index">
                    <div>
                        <Link :href="navigation.href">
                            <icon-sidebar
                                :name="navigation.icon"
                            ></icon-sidebar>
                            <span class="link-name">{{
                                navigation.label
                            }}</span>
                        </Link>
                    </div>
                </li>
            </ul>

            <ul
                v-if="$page.props.auth.user.roles.indexOf('admin') !== -1"
                class="bottom-container"
            >
                <li>
                    <Link href="/admin">
                        <icon-sidebar
                            name="user-shared-fill"
                            class="w-7 h-7"
                        ></icon-sidebar>
                        <span class="link-name color-slate-700 uppercase mr-2"
                            >Switch to Admin</span
                        >
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import IconSidebar from "../../../Shared/IconSidebar";

export default {
    props: {
        expendSidebar: Boolean,
    },
    data() {
        return {
            navigations: [
                {
                    href: "/lawyer/dashboard",
                    label: "Dashboard",
                    icon: "home-smile-line",
                    isActive: this.$page.url.startsWith("/lawyer"),
                },
                {
                    href: "/lawyer/client",
                    label: "Client Profiles",
                    icon: "group-line",
                    isActive: this.$page.url.startsWith("/lawyer/client"),
                },
                {
                    href: "/lawyer/case-files",
                    label: "My Cases",
                    icon: "folder-5-line",
                    isActive: this.$page.url.startsWith("/lawyer/case-files"),
                },
                {
                    href: "/lawyer/firm-accounts",
                    label: "Firm Accounts",
                    icon: "increase-decrease-line",
                    isActive: this.$page.url.startsWith(
                        "/lawyer/firm-accounts",
                    ),
                },
                {
                    href: "/lawyer/client-accounts",
                    label: "Client Accounts",
                    icon: "increase-decrease-line",
                    isActive: this.$page.url.startsWith(
                        "/lawyer/client-accounts",
                    ),
                },
                {
                    href: "/lawyer/claim-vouchers",
                    label: "Claim Vouchers",
                    icon: "ticket-line",
                    isActive: this.$page.url.startsWith(
                        "/lawyer/claim-vouchers",
                    ),
                },
                {
                    href: "/lawyer/operational-cost",
                    label: "Operational Cost",
                    icon: "operational-cost",
                    isActive: this.$page.url.startsWith(
                        "/lawyer/operational-cost",
                    ),
                },
                {
                    href: "/lawyer/accounting-management",
                    label: "Accounting Management",
                    icon: "booklet-line",
                    isActive: this.$page.url.startsWith(
                        "/lawyer/accounting-management",
                    ),
                },
            ],
        };
    },
    components: {
        IconSidebar,
    },
};
</script>
