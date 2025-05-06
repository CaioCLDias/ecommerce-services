
# 🛒 Cart Service

Serviço responsável pelo carrinho de compras.

## 🚀 Tecnologias

- PHP (Laravel)
- MySQL
- RabbitMQ

## 📌 Recursos

- CRUD do carrinho
- Associa usuários e produtos

## 📦 Fila

- Não consome nem publica mensagens.

## 📦 Como rodar

### Pré-requisitos

- Docker + Docker Compose
- Rede criada: `ecommerce-net`

### Subir serviço

```bash
cp .env.example .env
docker compose up -d

```
### URL Acesso: 
localhost:8002
