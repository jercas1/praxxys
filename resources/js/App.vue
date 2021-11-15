<template>
  <div>
    <!-- <div v-if="user"> -->
    <div>
    </div>

    <router-view class="mt-5 mb-2" />
  </div>
</template>
<script>
// import { mapState } from "vuex";
export default {
  data() {
    return {
      active: "",
      activeSidebar: false,
    };
  },

  computed: {
    // ...mapState("auth", ["user"]),
  },

  methods: {
    pushRouter(route_name) {
      if (this.$route.name != route_name) {
        this.activeSidebar = false;
        this.$router.push({
          name: route_name,
        });
      }
    },

    logout() {
      this.$store.dispatch("auth/logout");
    },
  },
};
</script>