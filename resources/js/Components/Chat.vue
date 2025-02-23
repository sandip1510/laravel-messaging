<template>
    <div>
        <h2>Chat with {{ receiver.name }}</h2>
        <div class="chat-box">
            <div v-for="message in messages" :key="message.id" 
                 :class="{ 'sent': message.sender_id === user.id, 'received': message.sender_id !== user.id }">
                {{ message.message }}
            </div>
        </div>
        <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
        <button @click="sendMessage">Send</button>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["user", "receiver"],
    data() {
        return {
            messages: [],
            newMessage: "",
        };
    },
    mounted() {
        this.fetchMessages();
    },
    methods: {
        async fetchMessages() {
            const response = await axios.get(`/api/messages/${this.receiver.id}`);
            this.messages = response.data;
        },
        async sendMessage() {
            if (!this.newMessage.trim()) return;
            
            const response = await axios.post("/api/messages", {
                receiver_id: this.receiver.id,
                message: this.newMessage,
            });

            this.messages.push(response.data);
            this.newMessage = "";
        },
    },
};
</script>

<style>
.chat-box {
    max-height: 400px;
    overflow-y: auto;
}
.sent {
    text-align: right;
    color: blue;
}
.received {
    text-align: left;
    color: green;
}
</style>
