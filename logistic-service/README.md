# 🚚 Logistics Service

Serviço responsável pela logística e entrega.

## 🚀 Tecnologias

- PHP (Laravel)
- RabbitMQ

## 📌 Recursos

- Atualizar status de entrega dos pedidos

## 📦 Fila

- Consome mensagens do Order Serive
- Publica mensagens sobre ataulização dos status do Pedido

## 🚀 Como rodar

```bash
cp .env.example .env
docker compose up -d

```
### URL Acesso: 
localhost:8005
