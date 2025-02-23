<template>
  <div>
      <h2>Chat with User {{ receiverId }}</h2>
      
      <!-- Message History -->
      <div v-if="messages.length === 0">No messages yet</div>
      <div v-else class="chat-box">
          <div v-for="msg in messages" :key="msg.id" 
               :class="{'sent': msg.sender_id === authUserId, 'received': msg.sender_id !== authUserId}">
              <strong v-if="msg.sender_id === authUserId">You:</strong>
              <strong v-else>User {{ msg.sender_id }}:</strong>
              {{ msg.message }}
          </div>
      </div>

      <!-- Message Input -->
      <input v-model="newMessage" placeholder="Type a message..." />
      <button @click="sendMessage">Send</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['authUserId'],
  data() {
      return {
          messages: [],
          newMessage: "",
          receiverId: 2, // Change dynamically later
      };
  },
  mounted() {
      this.fetchMessages();
  },
  methods: {
      async fetchMessages() {
          try {
              const response = await axios.get(`/api/messages/${this.receiverId}`, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });
              this.messages = response.data;
          } catch (error) {
              console.error("Error fetching messages:", error);
          }
      },
      async sendMessage() {
          if (!this.newMessage.trim()) return;

          try {
              const response = await axios.post('/api/messages', {
                  receiver_id: this.receiverId,
                  message: this.newMessage
              }, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });

              this.messages.push(response.data);
              this.newMessage = "";
          } catch (error) {
              console.error("Error sending message:", error);
          }
      }
  }
};
</script>

<style>
.chat-box {
  max-width: 400px;
  border: 1px solid #ddd;
  padding: 10px;
  margin-bottom: 10px;
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
