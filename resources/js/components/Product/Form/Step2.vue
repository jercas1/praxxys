<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="row">
          <div class="col">
            <label for="images">Product Image/s</label>
            <div class="custom-file">
              <input
                type="file"
                class="custom-file-input"
                id="images"
                accept="image/png, image/jpeg, image/jpg"
                multiple
                v-bind:class="error.length ? 'is-invalid' : ''"
                @change="onFileChange"
              />
              <label class="custom-file-label" for="images"
                >Choose image/s</label
              >

              <div
                class="invalid-feedback mb-3"
                v-if="error.length"
                v-bind:class="error.length ? 'd-block' : ''"
              >
                Image/s is required.
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-1 mt-4" v-for="(item, index) in old_files" :key="index">
            <button
              class="btn btn-danger btn-block mt-2"
              @click="removeOldFile(index)"
            >
              Remove
            </button>
            <img class="img-thumbnail" :src="item.file_url" />
          </div>

          <div class="col-1 mt-4" v-for="(item, index) in files" :key="index">
            <button
              class="btn btn-danger btn-block mt-2"
              @click="removeFile(index)"
            >
              Remove
            </button>
            <img class="img-thumbnail" :src="item" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
  components: {
    VueEditor,
  },

  props: {
    items: {
      type: Array,
      required: true,
    },
    old_files: {
      type: Array,
      required: true,
    },
    error: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      files: [],
    };
  },

  methods: {
    removeOldFile(index) {
      this.old_files.splice(index, 1);
    },

    onFileChange(e) {
      console.log(e);
      for (let index = 0; index < e.target.files.length; index++) {
        this.files.push(URL.createObjectURL(e.target.files[index]));
        this.items.push(e.target.files[index]);
      }
    },

    removeFile(index) {
      this.files.splice(index, 1);
      this.items.splice(index, 1);
    },
  },
};
</script>
