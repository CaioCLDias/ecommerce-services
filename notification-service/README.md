# 📧 Notification Service

Serviço responsável por enviar e-mails com atualizações de status do pedido.

## 🚀 Tecnologias

- Node.js (Express)
- RabbitMQ
- Nodemailer + Mailtrap

## 📌 Recursos

- Consome fila `notifications`
- Envia e-mails com atualizações de pedidos
- Publica erros na fila de erros

## 📦 Filas

- notifications (consome)

## ⚙️ Configuração necessária

Para o envio de e-mails é necessário configurar um serviço SMTP no `.env`.  
Você pode utilizar serviços como:

- [Mailtrap](https://mailtrap.io/) (recomendado para testes)
- Gmail (com senha de app e configurações específicas)
- Outro serviço SMTP de sua preferência

SMTP_HOST=sandbox.smtp.mailtrap.io
SMTP_PORT=2525
SMTP_USER=usuario_mailtrap
SMTP_PASS=senha_mailtrap
SMTP_FROM=seu_email@exemplo.com
SMTP_TO=email_destinatario@exemplo.com
Exemplo de configuração no `.env`:

## 📦 Como rodar

```bash
cp .env.example .env
docker compose up -d

```
### URL Acesso: 
localhost:3000

