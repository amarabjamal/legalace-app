<template>
    <span class="bg-blue-400 relative shrink-0 overflow-hidden block rounded-full text-lg w-8 h-8" :title="name">
        <img
        v-if="avatarUrl"
        :src="avatarUrl"
        :alt="name"
        class="object-cover h-full w-full"
        />
        <span
        v-else
        class="font-bold text-white inline-grid place-content-center h-full w-full uppercase"
        >
        {{ name.charAt(0) }}
        </span>
    </span>
</template>

<script>

export default {
    data() {
        return {
            name: this.$page.props.auth.user.name,
            imageSrc: null, //'/images/profileImage/default.png'
            avatarUrl: null,
        }
    },
    methods: {
        verifyImage() {
            if(this.imageSrc === null) {
                return
            }
            const image = new Image();
            image.src =  this.imageSrc;
            image.decode().then(() => (this.avatarUrl = this.imageSrc));
        },
    },
    beforeMount() {
        this.verifyImage();
    }
}
</script>