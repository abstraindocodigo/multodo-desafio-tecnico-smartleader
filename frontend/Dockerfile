FROM node:16-alpine

# Diretório de trabalho
WORKDIR /app

# Copiar package.json e yarn.lock
COPY package.json yarn.lock ./

# Instalar dependências com yarn
RUN yarn install

# Criar diretório .vite
RUN mkdir -p /app/node_modules/.vite && \
    chown -R node:node /app && \
    chmod -R 755 /app

# Código fonte
COPY . .

# Permissões e diretório .vite
RUN chown -R node:node /app && \
    mkdir -p /app/node_modules/.vite && \
    chown -R node:node /app/node_modules/.vite

# Usuário node
USER node

# Porta
EXPOSE 3000

# Comando
CMD ["yarn", "dev", "--host", "0.0.0.0", "--port", "3000"]
