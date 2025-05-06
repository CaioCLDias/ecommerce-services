require('dotenv').config();
const express = require('express');
const NotificationConsumer = require('./consumers/notification.consumer');

const app = express();
const port = 3000;

// Rota simples para health check
app.get('/', (req, res) => {
    res.send('Notification Service is running!');
});

// Inicia servidor express
app.listen(port, () => {
    console.log(`Notification service running on port ${port}`);

    // Inicia o consumer para escutar a fila
    NotificationConsumer.start();
});