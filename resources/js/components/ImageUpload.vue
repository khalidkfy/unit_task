<template>
    <div>
        <label class="font-weight-bold">{{label ? label : name}} <span class="text-danger d-inline-block mx-2" v-if="required">*</span> <span class="text-info">{{"Preferably the photo should be in size" + `(${img_width} * ${img_height})` }}</span></label>
        <el-upload
            :accept="fileType"
            :before-upload="beforeAvatarUpload"
            :headers="headers_crsf"
            :on-error="handleError"
            :on-success="handleAvatarSuccess"
            :show-file-list="false"
            action="/upload-img"
            class="avatar-uploader">
            <img :src="imageUrl || old" class="avatar" v-if="imageUrl || old">
            <i class="el-icon-plus avatar-uploader-icon" v-else></i>
        </el-upload>
        <div :style="{'display': 'block !important'}" class="error invalid-feedback" v-if="error">{{ error }}</div>
        <div :style="{'display': 'block !important'}" class="error invalid-feedback" v-if="file_error">{{ file_error }}</div>
    </div>
</template>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>

<script>
    export default {
        props: {
            label: {
                String,
                required: false,
                default: '',
            },
            old: {
                required: false,
                default: null,
            },
            name: {
                required: false,
                default: '',
            },
            required: {
                required: false,
                type: Boolean,
                default: false
            },
            img_width : {
              required: false,
              default: 300
            },
            img_height : {
              required: false,
              default: 300
            }
        },
        data() {
            return {
                imageUrl: null,
                headers_crsf: {
                    'X-CSRF-TOKEN': c_token,
                },
                fileType: ".jpg,.jpeg,.png",
                file_error: null
            };
        },
        methods: {
            handleAvatarSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
                this.$emit('input', res);

                this.$parent.uploaded_img = res;
            },
            beforeAvatarUpload(file) {
                // console.log(file)
            },
            handleError(err, file, fileList) {
                this.file_error = "File must be from type: .jpg, .jpeg, .png"
                // console.log(file);
                // console.log(fileList);
            }
        },
        computed: {
            error() {
                if (this.$parent.requestForm.validations && this.$parent.requestForm.validations[this.name]) {
                    return this.$parent.requestForm.validations[this.name][0];
                }
                return false;
            }
        }
    }
</script>
