const RabbitMQService = require('../services/rabbitmq.service');
const NotificationService = require('../services/notification.service');

class NotificationConsumer {

    async start() {

        const rabbit = new RabbitMQService();

        await rabbit.connect();
        await rabbit.setQueue('notifications');

        console.log('[Notification Consumer] Waiting for messages in notification_queue...');

        await rabbit.consume(async (msg) => {

            console.log('[Notification Consumer] Received message:', msg);
            try {
                await NotificationService.sendNotification(msg);
                console.log('[Notification Consumer] Message processed successfully:', msg);
            } catch (error) {
                console.error('[Notification Consumer] Error processing message:', error);
            }
        });
    }
}

module.exports = new NotificationConsumer();