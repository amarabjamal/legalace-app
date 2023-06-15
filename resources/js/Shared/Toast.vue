<template>
    <div v-if="show" :class="containerClass">
        <div class="shrink-0">
            <component :is="iconComponent" :class="iconClass"/>
        </div>
        <div class="flex-1 space-y-1">
            <h2 :class="titleClass">{{ title }}</h2>
            <div :class="contentClass"><slot /></div>
        </div>
        <div class="shrink-0">
            <button @click="dismissToast" :class="buttonClass">
                <x-icon class="w-6 h-6"/>
            </button>
        </div>
    </div>
</template>

<script>
import { InformationCircleIcon, CheckCircleIcon, ExclamationCircleIcon, XCircleIcon, XIcon } from '@heroicons/vue/solid';
import { cva } from 'class-variance-authority';

export default {
    components: { 
        InformationCircleIcon, 
        CheckCircleIcon,
        ExclamationCircleIcon,
        XCircleIcon,
        XIcon,
    },
    props: {
        intent: {
            type: String,
            validator(value) {
                return ['info', 'success', 'warning', 'danger'].includes(value);
            },
            default: 'info',
        },
        title: String,
        show: Boolean,
    },
    computed: {
        containerClass() {
            return cva("flex p-4 rounded-lg space-x-3 ring-black/[.05] shadow-lg", {
                variants: {
                    intent: {
                        info:"bg-blue-100",
                        success:"bg-green-100",
                        warning:"bg-orange-100",
                        danger:"bg-red-100",
                    }
                }
            }) ({
                intent: this.intent
            })
        },
        iconClass() {
            return cva("w-6 h-6", {
                variants: {
                    intent: {
                        info:"text-blue-700",
                        success:"text-green-700",
                        warning:"text-orange-700",
                        danger:"text-red-700",
                    }
                }
            }) ({
                intent: this.intent
            })
        },
        titleClass() {
            return cva("font-medium", {
                variants: {
                    intent: {
                        info:"text-blue-900",
                        success:"text-green-900",
                        warning:"text-orange-900",
                        danger:"text-red-900",
                    }
                }
            }) ({
                intent: this.intent
            })
        },
        contentClass() {
            return cva("text-sm", {
                variants: {
                    intent: {
                        info:"text-blue-800",
                        success:"text-green-800",
                        warning:"text-orange-800",
                        danger:"text-red-800",
                    }
                }
            }) ({
                intent: this.intent
            })
        },
        buttonClass() {
            return cva("p-0.5 rounded-md -m-1", {
                variants: {
                    intent: {
                        info:"text-blue-900/70 hover:text-blue-900 hover:bg-blue-200",
                        success:"text-green-900/70 hover:text-green-900 hover:bg-green-200",
                        warning:"text-orange-900/70 hover:text-orange-900 hover:bg-orange-200",
                        danger:"text-red-900/70 hover:text-red-900 hover:bg-red-200",
                    }
                }
            }) ({
                intent: this.intent
            })
        },
        iconComponent() {
            const icons = {
                info: InformationCircleIcon,
                success: CheckCircleIcon,
                warning: ExclamationCircleIcon,
                danger: XCircleIcon,
            };

            return icons[this.intent];
        }
    },
    methods: {
        dismissToast() {
            this.$emit('dismiss-toast');
        },
    },
    mounted() {
        setTimeout(this.dismissToast, 8000);
    },
}
</script>