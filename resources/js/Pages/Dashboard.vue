<template>
  <div>
      <h2>Chat App</h2>
      
      <!-- User List -->
      <div class="user-list">
          <h3>Users</h3>
          <ul>
              <li v-for="user in users" :key="user.id" @click="selectUser(user)">
                  {{ user.name }}
              </li>
          </ul>
      </div>

      <!-- Chat Box -->
      <div v-if="selectedUser" class="chat-box">
          <h3>Chat with {{ selectedUser.name }}</h3>
          
          <div v-if="messages.length === 0">No messages yet</div>
          <div v-else>
              <div v-for="msg in messages" :key="msg.id"
                   :class="{'sent': msg.sender_id === authUserId, 'received': msg.sender_id !== authUserId}">
                  <strong v-if="msg.sender_id === authUserId">You:</strong>
                  <strong v-else>{{ selectedUser.name }}:</strong>
                  {{ msg.message }}
              </div>
          </div>

          <!-- Message Input -->
          <input v-model="newMessage" placeholder="Type a message..." />
          <button @click="sendMessage">Send</button>
      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['authUserId'],
  data() {
      return {
          users: [],
          messages: [],
          newMessage: "",
          selectedUser: null, // The user who is clicked
      };
  },
  mounted() {
      this.fetchUsers();
  },
  methods: {
      async fetchUsers() {
          try {
              const response = await axios.get('/api/users', {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });
              this.users = response.data;
          } catch (error) {
              console.error("Error fetching users:", error);
          }
      },
      async fetchMessages() {
          if (!this.selectedUser) return;

          try {
              const response = await axios.get(`/api/messages/${this.selectedUser.id}`, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });
              this.messages = response.data;
          } catch (error) {
              console.error("Error fetching messages:", error);
          }
      },
      async sendMessage() {
          if (!this.newMessage.trim() || !this.selectedUser) return;

          try {
              const response = await axios.post('/api/messages', {
                  receiver_id: this.selectedUser.id,
                  message: this.newMessage
              }, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });

              this.messages.push(response.data);
              this.newMessage = "";
          } catch (error) {
              console.error("Error sending message:", error);
          }
      },
      selectUser(user) {
          this.selectedUser = user;
          this.messages = []; // Clear previous messages
          this.fetchMessages(); // Fetch new messages
      }
  }
};
</script>

<style>
.user-list {
  max-width: 200px;
  float: left;
  margin-right: 20px;
  padding: 10px;
  border: 1px solid #ddd;
}
.user-list ul {
  list-style-type: none;
  padding: 0;
}
.user-list li {
  cursor: pointer;
  padding: 5px;
  border-bottom: 1px solid #eee;
}
.user-list li:hover {
  background-color: #f0f0f0;
}
.chat-box {
  max-width: 400px;
  border: 1px solid #ddd;
  padding: 10px;
  float: left;
}
.sent {
  text-align: right;
  background-color: #e1ffc7;
  padding: 5px;
  margin: 5px;
}
.received {
  text-align: left;
  background-color: #f1f1f1;
  padding: 5px;
  margin: 5px;
}
</style>
