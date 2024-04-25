<template>
	<div class="chat card">
		<div class="scrollable card-body" ref="hasScrolledToBottom">
	        <template v-for="message in messages">
	            <div class="message message-receive" v-if="user.id != message.user.id">
	                <p>
	                    <strong class="primary-font">
	                        {{ message.user.name }} :
	                    </strong>
	                    {{ message.message }}
	                </p>
	            </div>
	            <div class="message message-send" v-else>
	                <p>
	                    <strong class="primary-font">
	                        {{ message.user.name }} :
	                    </strong>
	                    {{ message.message }}
	                </p>
	            </div>
	        </template>
	    </div>

	    <div class="chat-form input-group">
	        <input id="btn-input" type="text" name="message" class="form-control input-sm message-" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="addMessage">

	        <span class="input-group-btn">
	            <button class="btn btn-primary" id="btn-chat" @click="addMessage">
	                Send
	            </button>
	        </span>
	    </div>

	</div>
</template>
<script>
	import { reactive, inject,ref, onMounted,onUpdated } from 'vue';
	import axios from 'axios';
	export default{
		props:['user'],
	    setup(props){
	    	let messages = ref([])
	    	let newMessage = ref('')
	    	let hasScrolledToBottom = ref('')

	    	onMounted(() =>{
	    		fetchMessages()
	    	})

	    	onUpdated(() => {
	    		scrollBottom()
	    	})

	    	Echo.private('chat-channel')
	          .listen('ChannelMessages', (e) => {
	            messages.value.push({
	              message: e.message.message,
	              user: e.user
	            });
	        })



	    	const fetchMessages = async()=> {
	            axios.get('/messages').then(response => {
	                messages.value = response.data;
	            });
	        }

	        const addMessage = async()=> {
	        	let user_message = {
                    user: props.user,
                    message: newMessage.value
                };
	            messages.value.push(user_message);
	            axios.post('/messages', user_message).then(response => {
	              console.log(response.data);
	            });
                newMessage.value = ''
	        }

	        const scrollBottom = () =>{
	        	if(messages.value.length > 1){
		        	let el = hasScrolledToBottom.value;
	      			el.scrollTop = el.scrollHeight;
	        	}        	
	        }

	        return {
	        	messages,
	        	newMessage,
	        	addMessage,
	        	fetchMessages,
	        	hasScrolledToBottom
	        }
	    }
	}
</script>

<style type="text/css" scoped>
        .message p{
            border-radius: 10px;
            padding: 10px 20px 10px 8px;
            margin-top: 5px; 
            display: inline-block;
            width: auto;
            margin: 0px;  
        }
        .message-send p{ 
            background: #e0e3e6;
            color: #2f2d2d;
        }
        .message-send{
            text-align: right;
            margin-top: 5px;
        }
        .message-receive p{
            background: #435f7a;
            color: #f5f5f5;
        }
        .message-receive{
            margin-top: 5px;
        }

        .scrollable {
            overflow: hidden;
            overflow-y: scroll;
            height: calc(100vh - 25vh);
        }
        .message-input{
            border: none;
            border-radius: 0px;
            background: #f2f2f2;
        }
        
    </style>