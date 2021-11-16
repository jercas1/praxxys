<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="form-group">
          <label for="datetime">Date Time</label>
          <input
            class="form-control"
            id="datetime"
            type="datetime-local"
            v-model="item.datetime"
            v-bind:class="error.datetime.length ? 'is-invalid' : ''"
          />
          <div
            class="invalid-feedback mb-3"
            v-if="error.datetime.length"
            v-bind:class="error.datetime.length ? 'd-block' : ''"
          >
            {{ error.datetime }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
