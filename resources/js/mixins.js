export default {
    data() {
        return {
            data: null,
            requestForm: {
                error: false,
                validations: [],
            },
            CURRENT_USER: CURRENT_USER,
            BASE_URL: BASE_URL,
            validation_message: '',
            disabledButtons: false,
            SWALSuccess: true,
        }
    },

    methods: {
        saveForm(url, method, redirect = null, data = {}) {
            this.disabledButtons = true;
            this.requestForm.validations = [];
            this.showLoading();
            let returned_data = null;
            axios({ method: method, url: url, data})
                .then(response => {
                    this.hideLoading();
                    this.$emit('formSaved', response.data);
                    console.log(213);
                    if(this.SWALSuccess) {
                        this.notify_success(response.data.message);
                    }

                    setTimeout(() => {
                        if (redirect !== null) {
                            window.location.href = BASE_URL + redirect
                        } else {
                            this.requestForm.disabled = false;
                        }
                    }, 2000);
                    this.requestForm.validations = [];
                    this.disabledButtons = false;
                    this.whenFormSuccess(response); // new

                    returned_data = response;
                })
                .catch(error => {
                    this.handleError(error);
                });

            return returned_data;
        },

        handleError(error) {
            this.hideLoading();
            this.disabledButtons = false;
            if (error.response.data.errors) {
                this.requestForm.validations = error.response.data.errors;
            } else if (error.response.data.error_message) {
                this.requestForm.validations = [];
                this.validation_message = error.response.data.error_message;
                this.notify_error(error.response.data.error_message, 4000);
            } else if (error.response.data.message_error) {
                this.notify_error(error.response.data.message_error, 4000);
            }

            if(this.SWALError) {
                var period_time = 4000;
                if(typeof(error) == 'string') {
                    this.notify_error(error, period_time);
                } else {
                    this.notify_error(this.convertCustomErrorObjectForSwalToString(error.response.message), period_time);
                }

            }

            document.body.scrollTop = 0; // For Chrome, Safari and Opera
            document.documentElement.scrollTop = 0; // For IE and Firefox
        },

        whenFormSuccess(response = null) {

        },

        showLoading() {
            $('#loading-div').show();
        },

        hideLoading() {
            $('#loading-div').hide();
        },


        notify_error(message, duration = 3000) {
            this.$message({
                showClose: true,
                duration: duration,
                message: message,
                type: 'error'
            });
        },

        notify_success(message, duration = 4000) {
            this.$message({
                showClose: true,
                duration: duration,
                message: message,
                type: 'success'
            });
        },

        deleteItem(url, event) {
            axios.delete(url).then(response => {

                if (response.data.deleted) {
                    if (event != null) {
                        this.$emit(event);
                    }
                }
            });
        }

    },

    computed: {

    }
}
