<template>
  <div>
    <!-- <div v-if="user"> -->
    <div>
      <!-- Navbar -->
      <nav
        class="
          main-header
          navbar navbar-expand
          bg-white
          navbar-light
          border-bottom
        "
      >
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"
              ><i class="fa fa-bars"></i
            ></a>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <router-link to="/dashboard" class="brand-link">
          <img
            src="https://media.istockphoto.com/photos/group-of-unrecognisable-international-students-having-online-meeting-picture-id1300822108"
            alt="The Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.8"
          />
          <span class="brand-text font-weight-light">PRAXXYS</span>
        </router-link>

        <!-- Sidebar -->
        <div class="sidebar">
          <router-link to="/profile">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img
                  src="https://media.istockphoto.com/photos/group-of-unrecognisable-international-students-having-online-meeting-picture-id1300822108"
                  class="img-circle elevation-2"
                  alt="User Image"
                />
              </div>
              <div class="info">
                Jerry
                <span class="d-block text-muted"> jercas21@gmail.com </span>
              </div>
            </div>
          </router-link>

          <nav class="mt-2">
            <ul
              class="nav nav-pills nav-sidebar flex-column"
              data-widget="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item">
                <router-link to="/dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt blue"></i>
                  <p>Dashboard</p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="/product" class="nav-link">
                  <i class="nav-icon fas fa-list orange"></i>
                  <p>Product</p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="/product-form" class="nav-link">
                  <i class="nav-icon fas fa-list orange"></i>
                  <p>Create Product</p>
                </router-link>
              </li>
            </ul>
          </nav>
        </div>
        <!-- /.sidebar -->
      </aside>
    </div>

    <div class="content-wrapper">
      <router-view class="my-2" />
    </div>
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