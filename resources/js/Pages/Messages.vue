<template>
    <div>
      <h1>Messages</h1>
      <ul>
        <li v-for="message in messages" :key="message.id">
          {{ message.content }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        messages: [],
      };
    },
    async created() {
      await this.fetchMessages();
    },
    methods: {
      async fetchMessages() {
        try {
          const response = await axios.get('/api/messages/1'); // Replace with dynamic ID
          this.messages = response.data;
        } catch (error) {
          console.error('Error fetching messages:', error);
        }
      },
    },
  };
  </script>
  