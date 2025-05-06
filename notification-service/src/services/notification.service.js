const nodemailer = require('nodemailer');

class NotificationService {

    constructor() {
        this.transporter = nodemailer.createTransport({
            host: process.env.SMTP_HOST,
            port: process.env.SMTP_PORT,
            secure: false,
            auth: {
                user: process.env.SMTP_USER,
                pass: process.env.SMTP_PASS,
            },
        });
    }

    async sendNotification($notification) {

        const { order_id, status, message } = $notification;

        const emailMessage = `
            
            Olá 
            
            Seu pedido de númerop ${order_id} teve uma atualização
            
            Status: ${status}
            Message: ${message}
            
            Obrigado por escolher nossa loja!
            `;

        const emailOptions = {
            from: process.env.SMTP_FROM,
            to: `${process.env.SMTP_TO}`,
            subject: `Atualização do pedido ${order_id}`,
            text: emailMessage,
        };

        try {
            const info = await this.transporter.sendMail(emailOptions);
            console.log('Email sent: ', info.response);
            return true;
        }catch (error) {
            console.error('Error sending email: ', error);
            return false;
        }
    }
}

module.exports = new NotificationService();