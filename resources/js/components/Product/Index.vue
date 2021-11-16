<template>
  <div>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Search product"
        v-model="filters.search"
        v-on:keyup.enter="refresh()"
      />

      <select class="custom-select" v-model="filters.category">
        <option selected value="">All categories</option>
        <option>Convenience goods</option>
        <option>Shopping goods</option>
        <option>Specialty goods</option>
      </select>

      <div class="input-group-append">
        <button class="btn btn-outline-success" type="button" @click="refresh()">
          Search
        </button>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Category</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in products" :key="index">
          <th scope="row">{{ index + 1 }}</th>
          <td>{{ product.name }}</td>
          <td>{{ product.category }}</td>
          <td>{{ product.description }}</td>
        </tr>
        <tr @click="refresh()">
          <td colspan="4">
            <infinite-loading
              ref="infiniteLoading"
              @distance="100"
              @infinite="index"
              force-use-infinite-wrapper
            >
              <span slot="no-more"
                >There are no more products. Click to refresh.</span
              >
              <span slot="no-results"
                >There are no more products. Click to refresh.</span
              >
            </infinite-loading>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";

export default {
  components: {
    InfiniteLoading
  },

  data() {
    return {
      products: [],
      page: 1,

      filters: {
        search: "",
        category: "",
      },
    };
  },

  created() {
    this.index();
  },

  methods: {
    refresh() {
      this.products = [];
      this.page = 1;
      this.$refs.infiniteLoading.stateChanger.reset();
    },

    index($state) {
      this.axios
        .get(
          `/products?page=${this.page}&search=${this.filters.search}&category=${this.filters.category}`
        )
        .then((res) => {
          console.log(res);

          if (res.data.success) {
            if (res.data.products.data.length) {
              this.products = this.products.concat(res.data.products.data);
              $state.loaded();
            } else {
              $state.complete();
            }
          }
        })
        .catch((err) => {
          console.log(err);
        });

      this.page++;
    },
  },
};
</script>

<style>
</style>