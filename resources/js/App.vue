<template>
  <div v-if="loading">Loading...</div>
  <div v-else>
    <nav>
      <template v-if="user">
        <span>Welcome, {{ user.name }}</span> |
        <router-link to="/dashboard">Dashboard</router-link> |
        <button @click="logout">Logout</button>
      </template>
      <template v-else>
        <router-link to="/login">Login</router-link> |
        <router-link to="/register">Register</router-link>
      </template>
    </nav>

    <router-view />
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      user: null,
      loading: true
    };
  },

  async created() {
    await this.fetchAuthUser(); // ✅ Ensures authentication before rendering
  },

  methods: {
    async fetchAuthUser() {
      this.loading = true;
      const token = localStorage.getItem("token");

      if (!token) {
        console.log("No token found, user is not logged in.");
        this.user = null;
        this.loading = false;
        return;
      }

      try {
        const response = await axios.get("/api/authUser", {
          withCredentials: true,
          headers: { Authorization: `Bearer ${token}` }
        });

        this.user = response.data;
        console.log("Authenticated User:", this.user);
      } catch (error) {
        console.error("Authentication failed:", error);
        this.user = null;
        localStorage.removeItem("token"); // Remove invalid token
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post("/api/logout", {}, {
          withCredentials: true,
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
        });

        localStorage.removeItem("token");
        this.user = null;
        this.$router.push("/login");
      } catch (error) {
        console.error("Logout failed:", error);
      }
    }
  }
};
</script>
