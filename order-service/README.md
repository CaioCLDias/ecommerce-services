
# 📦 Order Service

Serviço responsável por processar pedidos.

## 🚀 Tecnologias

- PHP (Laravel)
- MySQL
- RabbitMQ

## 📌 Recursos

- Receber pedidos do carrinho
- Atualizar status dos pedidos
- Publicar eventos para outros serviços (ex: Pagamento Logistica e Notificação)

## 📦 Fila

- Consome mensagens do Cart, Status da entrega e Status do pagamento
- Publica mensagens para o Payment, Logistic e  Notification

## 🚀 Como rodar

```bash
cp .env.example .env
docker compose up -d

```
### URL Acesso: 
localhost:8003
