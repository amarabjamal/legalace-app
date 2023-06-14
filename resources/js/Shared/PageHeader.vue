<template>
    <div class="top">
        <div class="flex items-center">
            <icon v-if="!expendSidebar" @click="toggleSidebar" name="menu-line" class="sidebar-toggle-button"></icon>
            <icon v-else @click="toggleSidebar" name="menu-fold-fill" class="sidebar-toggle-button"></icon>
            
            <div class="company-name">{{ auth.company.name }}</div>
        </div>

        <ul class="flex items-center flex-shrink-0 space-x-6">
            <!-- Notifications tray -->
            <notification-tray />

            <!-- Profile menu -->
            <profile-menu />
        </ul>
    </div>
</template>

<script>
import NotificationTray from './NotificationTray';
import ProfileMenu from './ProfileMenu';

export default {
    components: {
        NotificationTray,
        ProfileMenu,
    },
    props: {
        expendSidebar: Boolean,
    },
    data() {
        return {
            auth: this.$page.props.auth,
        }
    },
    methods: {
        toggleSidebar() {
            this.$emit('toggle-sidebar');
        }
    },
}
</script>