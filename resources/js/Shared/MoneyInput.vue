<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" v-money="money" class="form-input text-right tabular-nums" :class="{ error: error }" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid';
import { VMoney } from 'v-money';
import MoneyConfig from '../Stores/MoneyConfig';

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
        money: MoneyConfig,
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