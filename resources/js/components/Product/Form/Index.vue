<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col pr-2">
        <vs-button class="w-100" :disabled="step == 1" @click="step--"
          >Previous</vs-button
        >
      </div>

      <div class="col pl-2">
        <vs-button
          class="w-100"
          :loading="loading"
          id="btnNext"
          @click="nextForm()"
          >{{ step === 3 ? "Save Product" : "Next" }}</vs-button
        >
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <Step1 :item="step1_form" :error="step1_error" v-show="step === 1" />

        <Step2
          :items="step2_form"
          :old_files="old_files"
          :error="step2_error"
          v-show="step === 2"
        />

        <Step3 :item="step3_form" :error="step3_error" v-show="step === 3" />
      </div>
    </div>
  </div>
</template>

<script>
import Step1 from "./Step1";
import Step2 from "./Step2";
import Step3 from "./Step3";

export default {
  components: {
    Step1,
    Step2,
    Step3,
  },

  data() {
    return {
      step1_form: {
        name: "",
        category: "",
        description: "",
      },
      step1_error: {
        name: "",
        category: "",
        description: "",
      },
      step2_form: [],
      old_files: [],
      step2_error: "",
      step3_form: {
        datetime: "",
      },
      step3_error: {
        datetime: "",
      },

      step: 1,
      loading: false,
    };
  },

  created() {
    if (this.$route.query.product_id) {
      this.show();
    }
  },

  methods: {
    show() {
      const loading = this.$vs.loading({
        text: "Getting product details...",
      });

      this.axios
        .get(`/products/${this.$route.query.product_id}`)
        .then((res) => {
          if (res.data.success) {
            for (const [key] of Object.entries(this.step1_form)) {
              this.step1_form[key] = res.data.product[key];
            }

            this.old_files = res.data.product.product_files;

            if (res.data.product.datetime) {
              const datetime = new Date(res.data.product.datetime);

              let month = datetime.getMonth() + 1;
              if (month < 10) {
                month = `0${month}`;
              }

              let date = datetime.getDate();
              if (date < 10) {
                date = `0${date}`;
              }

              let hours = datetime.getHours();
              if (hours < 10) {
                hours = `0${hours}`;
              }

              let minutes = datetime.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }

              this.step3_form.datetime = `${datetime.getFullYear()}-${month}-${date}T${hours}:${minutes}`;
            }
          }
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(function () {
          loading.close();
        });
    },

    nextForm() {
      const vm = this;
      this.loading = true;

      this.resetErrors();

      let url = `/products/validate/${this.step}`;
      const formData = new FormData();
      if (this.step === 1) {
        for (const key in this.step1_form) {
          formData.append(key, this.step1_form[key]);
        }
      } else if (this.step === 2) {
        if (this.$route.query.product_id) {
          url = "/products/validate/2.2";
          for (let index = 0; index < this.old_files.length; index++) {
            formData.append("old_files[]", this.old_files[index].id);
          }
        }

        this.step2_form.forEach((file) => {
          formData.append("images[]", file, file.name);
        });
      } else if (this.step === 3) {
        url = "/products";

        for (const key in this.step1_form) {
          formData.append(key, this.step1_form[key]);
        }

        if (this.$route.query.product_id) {
          url += `/${this.$route.query.product_id}`;
          for (let index = 0; index < this.old_files.length; index++) {
            formData.append("old_files[]", this.old_files[index].id);
          }
        }

        this.step2_form.forEach((file) => {
          formData.append("images[]", file, file.name);
        });

        if (this.step3_form.datetime) {
          formData.append("datetime", this.step3_form.datetime);
        }
      }

      this.axios
        .post(url, formData)
        .then((res) => {
          if (res.data.success) {
            if (this.step !== 3) {
              this.step++;
            } else {
              this.$vs.notification({
                color: "success",
                title: res.data.title,
                text: res.data.message,
              });

              this.$router.push({ name: "Product" });
            }
          }
        })
        .catch((err) => {
          console.log(err);
          if (this.step === 1) {
            for (const key in err.response.data.errors) {
              this.step1_error[key] = err.response.data.errors[key][0];
            }
          } else if (this.step === 2) {
            for (const key in err.response.data.errors) {
              this.step2_error = err.response.data.errors[key][0];
            }
          } else if (this.step === 3) {
            this.step3_error["datetime"] =
              err.response.data.errors["datetime"][0];
          }
        })
        .finally(function () {
          vm.loading = false;
        });
    },

    resetData() {
      Object.assign(this.$data.step1_form, this.$options.data().step1_form);
      this.step2_form = [];
      this.old_files = [];
      Object.assign(this.$data.step3_form, this.$options.data().step3_form);

      this.resetErrors();

      this.step = 1;
    },

    resetErrors() {
      Object.assign(this.$data.step1_error, this.$options.data().step1_error);
      this.step2_error = "";
      Object.assign(this.$data.step3_error, this.$options.data().step3_error);
    },
  },
};
</script>