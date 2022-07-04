<template>
    <Link 
        class="px-3 py-2 rounded-md text-sm font-medium"
        :class="{ 'bg-gray-900 text-white' : active, 'text-gray-300 hover:bg-gray-700 hover:text-white' : !active }"
        >
        <slot />
    </Link>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    props: {
        active: Boolean,
    },
    components: {
        Link,
    }
}
</script>