<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="name">Product Name</label>
              <input
                class="form-control"
                id="name"
                v-model="item.name"
                v-bind:class="error.name.length ? 'is-invalid' : ''"
              />
              <div
                class="invalid-feedback mb-3"
                v-if="error.name.length"
                v-bind:class="error.name.length ? 'd-block' : ''"
              >
                {{ error.name }}
              </div>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="category">Product Category</label>
              <select
                class="custom-select"
                id="category"
                v-model="item.category"
                v-bind:class="error.category.length ? 'is-invalid' : ''"
              >
                <option selected hidden value="">Select category</option>
                <option>Convenience goods</option>
                <option>Shopping goods</option>
                <option>Specialty goods</option>
              </select>
              <div
                class="invalid-feedback mb-3"
                v-if="error.category.length"
                v-bind:class="error.category.length ? 'd-block' : ''"
              >
                {{ error.category }}
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Product Description</label>
              <vue-editor v-model="item.description"></vue-editor>
              <div
                class="invalid-feedback mb-3"
                v-if="error.description.length"
                v-bind:class="error.description.length ? 'd-block' : ''"
              >
                {{ error.description }}
              </div>
            </div>
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
    item: {
      type: Object,
      required: true,
    },
    error: {
      type: Object,
      required: true,
    },
  },
};
</script>
