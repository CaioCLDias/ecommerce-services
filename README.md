# 🛒 E-commerce Microservices with Laravel + Node.js + RabbitMQ + Docker

Projeto de exemplo para demonstrar uma arquitetura simples de **microsserviços** usando:

- Laravel (PHP) para serviços de negócio (catálogo, carrinho, pedidos, pagamentos e logística)
- Node.js (Express + Nodemailer) para serviço de notificações
- RabbitMQ para mensageria
- Docker para orquestração

## 🚀 Serviços

| Serviço           | Linguagem  | Descrição |
|-------------------|------------|-----------|
| catalog-service   | Laravel    | Gerencia produtos e endereços |
| cart-service      | Laravel    | Gerencia o carrinho de compras |
| order-service     | Laravel    | Cria e gerencia pedidos |
| payment-service   | Laravel    | Processa pagamentos (simulado) |
| logistics-service | Laravel    | Gerencia o envio e entrega |
| notification-service | Node.js | Consome eventos e envia e-mails |

## 🧹 Infraestrutura

| Serviço | Descrição |
|---------|-----------|
| RabbitMQ | Mensageria (Fila) |
| MySQL    | Base de dados para os serviços |

## 📦 Como rodar

### Pré-requisitos

- Docker
- Docker Compose

### Subir a infraestrutura

```bash
docker network create ecommerce-net

cd configs

docker compose up -d

```
## 📑 Objetivo
Este projeto faz parte de um tutorial e demonstração para aprender:

- Microsserviços com Laravel + Node.js
- Comunicação assíncrona com RabbitMQ
- Orquestração com Docker Compose
- Processamento de eventos e filas

Integração entre serviços (Exemplo: pedido -> pagamento -> logística -> notificação)

## 👨‍💻 Autor
 <a href="https://github.com/CaioCLDias">
    <img src="https://avatars.githubusercontent.com/u/23087077?v=4" width="100px;" alt="Foto do Caio"/><br>
        <sub>
          <b>Caio Dias</b>
        </sub>
 </a>
