# 📦 Catalog Service

Serviço responsável por gerenciar produtos e endereços dos usuários.

## 🚀 Tecnologias

- PHP (Laravel)
- MySQL
- RabbitMQ

## 📌 Recursos

- CRUD de produtos
- CRUD de endereços
- CRUD de usuários

## 📦 Fila

- Não consome mensagens, apenas expõe dados.

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
localhost:8001
