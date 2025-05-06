# Usa a imagem oficial do Node.js
FROM node:20

# Define o diretório de trabalho dentro do container
WORKDIR /app

# Copia os arquivos de dependências
COPY package*.json ./

# Instala as dependências
RUN npm install --production

# Copia o restante da aplicação
COPY . .

# Expõe a porta que o app vai rodar
EXPOSE 3000

# Comando para iniciar o app
CMD ["npm", "start"]
