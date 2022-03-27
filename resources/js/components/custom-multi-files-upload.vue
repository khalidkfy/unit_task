<template>
  <div>
    <label> {{ label }} <span v-if="required" class="text-danger"> *</span></label>
    <div v-if="oldFiles && oldFiles.length">
      <span class="text-info font-weight-bold">imgs</span>
      >
      <ol class="my-4">
        <li v-for="file in oldFiles" :key="file">
          <a :href="`${file.path}`" target="_blank">{{ file.name }}</a>
        </li>
      </ol>
    </div>
    <el-upload
      class="upload-demo"
      action="/upload-multi-file"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :on-error="handleError"
      show-file-list
      :on-success="handleSuccess"
      :accept="fileType"
      :headers="headers_crsf"
      :file-list="fileList"
    >
      <el-button size="large" type="primary">click</el-button>
    </el-upload>
    <ol v-if="fileList.length">
      <li v-for="file in fileList" :key="file">
        <span>{{ file.name }}</span>
        <span @click="handelDelete(file)" style="cursor: pointer"
          ><i class="fa fa-trash text-danger fa-fw"></i
        ></span>
      </li>
    </ol>
    <div
      v-if="file_error"
      class="error invalid-feedback"
      :style="{ display: 'block !important' }"
    >
      {{ file_error }}
    </div>
    <div v-if="error" class="text-danger">{{ error }}</div>
  </div>
</template>
<script>
export default {
  props: {
    label: {
      String,
      required: false
    },
    old: {
      Array,
      required: false
    },
    name: String,
    required: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      fileList: [],
      headers_crsf: {
        "X-CSRF-TOKEN": c_token
      },
      oldFiles: [],
      fileType: ".pdf,.jpg,.jpeg,.png,.gif,.xls,.xlsx,.txt,.doc,.docm,.docx",
      file_error: false
    };
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
      console.log(123);
    },
    handleError(err) {
      this.file_error = "File must be from type: .pdf,.jpg,.jpeg,.png,.gif,.xls,.xlsx,.txt,.doc,.docm,.docx";
    },
    handleSuccess(res) {
      this.fileList.push(res);
      this.$emit("input", this.fileList);
    },
    handelDelete(file) {
      let index = this.fileList.indexOf(file);

      if (index > -1) {
        this.fileList.splice(index, 1);
      }
    }
  },
  mounted() {
    if (this.old && this.old.length) {
      let files = [];
      this.old.forEach(file => {
        let fileName = file.replace(/^.*[\\\/]/, ""),
          fileObj = {};

        fileObj.name = fileName;
        fileObj.path = file;

        files.push(fileObj);
      });
      this.oldFiles = files;
    }
  },
  computed: {
    error() {
      if (
        this.$parent.requestForm.validations &&
        this.$parent.requestForm.validations[`${this.name}`]
      ) {
        return this.$parent.requestForm.validations[this.name][0];
      }
      return false;
    }
  }
};
</script>
