const amqp = require('amqplib');

class RabbitMQService {

    constructor() {
        this.connection = null;
        this.channel = null;
        this.queue = null;
    }

    async connect() {
        if (!this.connection) {
            this.connection = await amqp.connect(process.env.RABBITMQ_URL);
            this.channel = await this.connection.createChannel();
        }
    }

    async setQueue(queueName) {
       
        this.queue = queueName;

        const dlqQueue = `${this.queue}_dlq`;

        await this.channel.assertQueue(dlqQueue, {
            durable: true,
        });

        await this.channel.assertQueue(this.queue, {
            durable: true,
            arguments: {
                'x-dead-letter-exchange': '',
                'x-dead-letter-routing-key': dlqQueue
            }
        });
    }

    async publish(message) {

        if (!this.channel) {
            throw new Error('Channel not initialized. Call connect() first.');
        }

        const options = {
            persistent: true,
        };

        this.channel.sendToQueue(this.queue, Buffer.from(JSON.stringify(message)), options);
    }

    async consume(callback) {
        if (!this.channel) {
            throw new Error('Channel not initialized. Call connect() first.');
        }

        await this.channel.consume(this.queue, async (msg) => {
            if (msg !== null) {
                const message = JSON.parse(msg.content.toString());
                try {
                    await callback(message);
                    this.channel.ack(msg);
                } catch (error) {
                    console.error('Error processing message:', error);
                    const dlqQueue = `${this.queue}.dlq`;
                    this.channel.sendToQueue(dlqQueue, Buffer.from(msg.content.toString()));
                    this.channel.nack(msg, false, false);
                }
            }
        });
    }
}

module.exports = RabbitMQService