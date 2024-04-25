import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import chat from './components/Chat.vue';

// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

app.component('chat', chat);

app.mount('#app');
