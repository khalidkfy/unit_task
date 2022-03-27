<template>
    <div class="form-group">
        <label v-if="label">{{ label }} <span v-if="required" class="text-danger"> *</span></label>
        <textarea
            :disabled="disabled"
            :rows="rows"
            :cols="cols"
            :value="value"
            @input="changed($event.target.value)"
            class="form-control"
            :placeholder="label"
            :class="{'is-invalid': error}">
        </textarea>
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
            rows: {
                type: Number,
                default: 3
            },
            cols: {
                type: Number,
                default: 40
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },

        methods: {
            changed(value) {
                this.$emit('input', value);
            }
        },

        computed: {
            error() {
                if(this.$parent.requestForm.validations && this.$parent.requestForm.validations[this.name]) {
                    return this.$parent.requestForm.validations[this.name][0];
                }
                return false;
            }
        }
    }
</script>
