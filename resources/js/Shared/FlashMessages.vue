<template>
  <!-- 
    enter-from-class="translate-x-full opacity-0"
    enter-active-class="duration-500"
    leave-active-class="duration-500"
    leave-to-class="translate-x-full opacity-0"
    appear 

    @before-enter="onBeforeEnter"
    @enter="onEnter"
    @leave="onLeave"
  -->
  <transition-group
        tag="div"
        @before-enter="onBeforeEnter"
        @enter="onEnter"
        @leave="onLeave"
        appear
        class="fixed top-20 right-4 z-50 space-y-4 w-full max-w-sm max-h-[89%] overflow-y-auto rounded-md"
  > 
    <!-- Notification items -->
    <notification-item v-for="(notification, index) in notifications" :key="notification.id" :data-index="index" :notification="notification" @remove-notification="removeNotification(index)" />

    <!-- Toast item -->
    <toast key="success" :data-index="notifications.length" v-if="$page.props.flash.successMessage && show" @dismiss-toast="show = false" :show="show" title="Success" intent="success">
      {{ $page.props.flash.successMessage }}
    </toast>
    <toast key="info" :data-index="notifications.length" v-if="$page.props.flash.infoMessage && show" @dismiss-toast="show = false" :show="show" title="Info" intent="info">
      {{ $page.props.flash.infoMessage }}
    </toast>
    <toast key="warning" :data-index="notifications.length" v-if="$page.props.flash.warningMessage && show" @dismiss-toast="show = false" :show="show" title="Warning" intent="warning">
      {{ $page.props.flash.warningMessage }}
    </toast>
    <toast key="error" :data-index="notifications.length" v-if="($page.props.flash.errorMessage || Object.keys($page.props.errors).length > 0) && show" @dismiss-toast="show = false" :show="show" title="Error" intent="danger">
      <div v-if="$page.props.flash.errorMessage">
        {{ $page.props.flash.errorMessage }}
      </div>
      <div v-else>
        <span v-if="Object.keys($page.props.errors).length === 1">There is one form error.</span>
        <span v-else>There are {{ Object.keys($page.props.errors).length }} form errors.</span>
      </div>
    </toast>
  </transition-group>
</template>

<script>
import NotificationItem from './NotificationItem';
import Toast from './Toast';
import gsap from 'gsap';

export default {
  components: {
        NotificationItem,
        Toast,
  },
  data() {
        return {
            show: true,
            notifications: [],
        }
  },
  watch: {
    '$page.props.flash': {
        handler() {
            this.show = true
        },
        deep: true,
    },
  },
  methods: {
    onBeforeEnter(element) {
        element.style.height = 0;
        element.style.transform = 'translateX(150%)';
        element.style.opacity = 0;
    },
    onEnter(element, done) {
        gsap.to(element, {
            height: '100%',
            transform: 'translateX(0)',
            opacity: 1,
            delay: element.dataset.index * .15,
            onComplete: done,
        });
    },
    onLeave(element, done) {
        gsap.to(element, {
            height: 0,
            transform: 'translateX(150%)',
            opacity: 0,
            delay: (1 - (element.dataset.index/10)) * .15,
            onComplete: done,
        });
    },
    removeNotification(index) {
        this.notifications.splice(index, 1);
    }
  },
  mounted() {
        window.Echo.private(`App.Models.User.${this.$page.props.auth.user.id}`).notification((notification) => {
            this.notifications.unshift(notification);
            this.$page.props.auth.notifications.unreadCount++;
        })
    },
}
</script>