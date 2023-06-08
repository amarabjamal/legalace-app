<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid';
import { VMoney } from 'v-money';

export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${uuid()}`
      },
    },
    error: String,
    label: String,
    modelValue: String,
  },
  data() {
    return {
        money: {
            // The character used to show the decimal place.
            decimal: '.',
            // The character used to separate numbers in groups of three.
            thousands: ',',
            // The currency name or symbol followed by a space.
            prefix: 'RM ',
            // The suffix (If a suffix is used by the target currency.)
            suffix: '',
            // Level of decimal precision. REQUIRED
            precision: 2,
            // If mask is false, outputs the number to the model. Otherwise outputs the masked string.
            masked: false
        },
    }
  },
  directives: {money: VMoney},
  emits: ['update:modelValue'],
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>