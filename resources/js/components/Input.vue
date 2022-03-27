<template>
    <div>
        <label v-if="label">{{ label }} <span v-if="required" class="text-danger"> *</span></label>
        <div class="input-group" :class="{'input-group-sm' : small}" v-if="group_text">
            <input
                :disabled="disabled"
                :type="type"
                :value="value"
                @input="changed($event.target.value)"
                class="form-control"
                :placeholder="placeholder ? placeholder : label"
                :class="{'is-invalid':error, 'form-control-sm': small}"
            >
            <div class="input-group-append"><span class="input-group-text">{{ group_text }}</span></div>
        </div>
        <input
            :disabled="disabled"
            :type="type"
            :value="value"
            @input="changed($event.target.value)"
            class="form-control"
            :placeholder="placeholder ? placeholder : label"
            :class="{'is-invalid':error, 'form-control-sm': small}"
            v-else
        >
        <div v-if="error" class="error invalid-feedback">{{ error }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            label: String,
            placeholder: String,
            type: {
                type: String,
                default: 'text'
            },
            value: [String, Number],
            name: String,
            required: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
            group_text: String,
            small: {
                type: Boolean,
                default: false
            },
        },

        methods: {
            changed(value) {
                this.$emit('input', value);
            },
        },

        computed: {
            error() {
                if(this.$parent.requestForm.validations && this.$parent.requestForm.validations[`${this.name}`]) {
                    return this.$parent.requestForm.validations[this.name][0];
                }
                return false;
            }
        }
    }
</script>
