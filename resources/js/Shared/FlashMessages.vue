<template>
    <div class="fixed top-20 right-4 z-50 space-y-4 w-full max-w-sm">
        <toast v-show="$page.props.flash.successMessage && show" @dismiss-toast="show = false" :show="show" title="Success" intent="success">
          {{ $page.props.flash.successMessage }}
        </toast>
        <toast v-show="$page.props.flash.infoMessage && show" @dismiss-toast="show = false" :show="show" title="Info" intent="info">
          {{ $page.props.flash.infoMessage }}
        </toast>
        <toast v-show="$page.props.flash.warningMessage && show" @dismiss-toast="show = false" :show="show" title="Warning" intent="warning">
          {{ $page.props.flash.warningMessage }}
        </toast>
        <toast v-show="($page.props.flash.errorMessage || Object.keys($page.props.errors).length > 0) && show" @dismiss-toast="show = false" :show="show" title="Error" intent="danger">
          <div v-if="$page.props.flash.errorMessage">
            {{ $page.props.flash.errorMessage }}
          </div>
          <div v-else>
            <span v-if="Object.keys($page.props.errors).length === 1">There is one form error.</span>
            <span v-else>There are {{ Object.keys($page.props.errors).length }} form errors.</span>
          </div>
        </toast>
    </div>
</template>

<script>
import Toast from './Toast';

export default {
  components: {
    Toast,
  },
  data() {
    return {
      show: true,
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
}
</script>