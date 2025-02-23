<template>
    <div class="chat-container">
      <div class="users">
        <h3>Users</h3>
        <ul>
          <li v-for="user in users" :key="user.id" @click="selectUser(user)">
            {{ user.name }}
          </li>
        </ul>
      </div>
  
      <div class="chat-box" v-if="selectedUser">
        <h3>Chat with {{ selectedUser.name }}</h3>
        <div class="messages">
          <div v-for="message in messages" :key="message.id" :class="{'sent': message.sender_id === currentUser.id, 'received': message.sender_id !== currentUser.id}">
            {{ message.message }}
          </div>
        </div>
        <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
        <button @click="sendMessage">Send</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        users: [],
        messages: [],
        selectedUser: null,
        newMessage: '',
        currentUser: {},
      };
    },
    async created() {
      await this.fetchUsers();
      await this.fetchCurrentUser();
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get('/api/users');
          this.users = response.data;
        } catch (error) {
          console.error('Error fetching users:', error);
        }
      },
      async fetchCurrentUser() {
        try {
          const response = await axios.get('/api/user');
          this.currentUser = response.data;
        } catch (error) {
          console.error('Error fetching current user:', error);
        }
      },
      async fetchMessages() {
        if (!this.selectedUser) return;
        try {
          const response = await axios.get(`/api/messages/${this.selectedUser.id}`);
          this.messages = response.data;
        } catch (error) {
          console.error('Error fetching messages:', error);
        }
      },
      async sendMessage() {
        if (!this.newMessage.trim()) return;
  
        try {
          const response = await axios.post('/api/messages', {
            receiver_id: this.selectedUser.id,
            message: this.newMessage,
          });
  
          this.messages.push(response.data);
          this.newMessage = '';
        } catch (error) {
          console.error('Error sending message:', error);
        }
      },
      selectUser(user) {
        this.selectedUser = user;
        this.fetchMessages();
      },
    },
  };
  </script>
  
  <style scoped>
  .chat-container {
    display: flex;
  }
  
  .users {
    width: 30%;
    border-right: 1px solid #ccc;
    padding: 10px;
  }
  
  .chat-box {
    flex-grow: 1;
    padding: 10px;
  }
  
  .messages {
    height: 300px;
    overflow-y: auto;
    margin-bottom: 10px;
  }
  
  .sent {
    text-align: right;
    background-color: #d1e7dd;
    padding: 5px;
    margin: 5px;
    border-radius: 5px;
  }
  
  .received {
    text-align: left;
    background-color: #f8d7da;
    padding: 5px;
    margin: 5px;
    border-radius: 5px;
  }
  </style>
  