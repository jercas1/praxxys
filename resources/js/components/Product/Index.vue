<template>
  <div>
    <div class="row">
      <div class="col-auto">
        <router-link to="/product-form" class="btn btn-success">
          Create New Product
        </router-link>
      </div>

      <div class="col">
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
            <button
              class="btn btn-outline-success"
              type="button"
              @click="refresh()"
            >
              Search
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th class="d-flex justify-content-end" scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(product, index) in products" :key="index">
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ product.name }}</td>
                <td>{{ product.category }}</td>
                <td class="d-flex justify-content-end">
                  <div class="btn-group" role="group">
                    <button class="btn btn-primary" @click="show(product.id)">
                      Edit
                    </button>
                    <button
                      class="btn btn-danger"
                      @click="deleteConfirmation(product, index)"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr class="cursor-pointer" @click="refresh()">
                <td colspan="4">
                  <infinite-loading
                    ref="infiniteLoading"
                    @distance="100"
                    @infinite="index"
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
      </div>
    </div>
  </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";

export default {
  components: {
    InfiniteLoading,
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
    deleteConfirmation(product, index) {
      this.$swal({
        icon: "question",
        title: "Delete Confirmation",
        text: `Are you sure you want to delete "${product.name}"?`,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          this.delete(product.id, index);
        }
      });
    },

    delete(product_id, index) {
      this.axios
        .delete(`/products/${product_id}`)
        .then((res) => {
          if (res.data.success) {
            this.$vs.notification({
              color: "success",
              title: res.data.title,
              text: res.data.message,
            });

            this.products.splice(index, 1);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    show(product_id) {
      this.$router.push({
        name: "Product Form",
        query: {
          product_id: product_id,
        },
      });
    },

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

<style scoped>
.table-responsive {
  max-height: 600px;
  overflow: auto;
  display:inline-block;
}
</style>