# Sistema de Tarefas

Sistema de gerenciamento de tarefas com autenticação JWT e suporte a múltiplas empresas.

## 🚀 Execução Rápida com Docker

```bash
docker compose up --build -d
```

**Acessar a aplicação**

- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api

## 🧪 Dados de Teste

O sistema vem com dados de teste pré-configurados para facilitar o desenvolvimento e testes:

### 📊 Estrutura dos Dados
- **2 Empresas**: "Empresa Teste 1" e "Empresa Teste 2"
- **6 Usuários**: 3 usuários por empresa
- **30 Tarefas**: 5 tarefas por usuário

### 🔐 Credenciais de Teste

**Empresa 1:**
- `usuario1@empresa1.com` | Senha: `password`
- `usuario2@empresa1.com` | Senha: `password`
- `usuario3@empresa1.com` | Senha: `password`

**Empresa 2:**
- `usuario4@empresa2.com` | Senha: `password`
- `usuario5@empresa2.com` | Senha: `password`
- `usuario6@empresa2.com` | Senha: `password`

### 🔄 Recriar Dados de Teste

```bash
# Recriar banco com dados de teste
docker compose exec backend php artisan migrate:fresh --seed

# Transformar usuários em admins
docker compose exec backend php artisan company:update-admins
```

### Comandos úteis para gerenciar containers

**Parar todos os containers:**

```bash
docker stop multodo_postgres multodo_backend multodo_frontend multodo_queue
```

**Remover containers:**

```bash
docker rm multodo_postgres multodo_backend multodo_frontend multodo_queue
```

**Ver logs:**

```bash
docker logs multodo_backend
docker logs multodo_frontend
```

## 🗄️ Acesso ao Banco de Dados

### Conectar via psql (PostgreSQL)

```bash
# Acessar o container do PostgreSQL
docker compose exec postgres psql -U multodo_user -d multodo

# Ou conectar diretamente do host (se psql estiver instalado)
psql -h localhost -p 5432 -U multodo_user -d multodo
```

### Comandos úteis do PostgreSQL

```sql
-- Listar todas as tabelas
\dt

-- Descrever estrutura de uma tabela
\d users
\d companies
\d tasks

-- Ver dados de uma tabela
SELECT * FROM users LIMIT 10;
SELECT * FROM companies LIMIT 10;
SELECT * FROM tasks LIMIT 10;

-- Contar registros
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM companies;
SELECT COUNT(*) FROM tasks;

-- Sair do psql
\q
```

### Acessar via interface gráfica

**DBeaver, pgAdmin ou similar:**
- Host: `localhost`
- Port: `5432`
- Database: `multodo`
- Username: `multodo_user`
- Password: `multodo_password`

### Executar comandos Laravel no banco

```bash
# Acessar o container do backend
docker compose exec backend bash

# Executar comandos artisan
php artisan tinker
php artisan migrate:status
php artisan db:seed
```

## 🛠️ Instalação Manual

### Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
# Configure o banco de dados no arquivo .env
php artisan migrate
php artisan jwt:secret
php artisan serve
```

Em outro terminal:

```bash
cd backend
php artisan queue:work
```

### Frontend (Vue.js)

```bash
cd frontend
npm install
npm run serve
```

## 🎯 Como Usar

1. **Login**: Acesse o frontend e faça login com qualquer uma das credenciais de teste
2. **Gerenciar tarefas**: Criar, editar, excluir e filtrar tarefas
3. **Isolamento por empresa**: Cada usuário vê apenas as tarefas da sua empresa

## ⚡ Funcionalidades Principais

- **Autenticação JWT**: Sistema seguro de login
- **Multi-empresa**: Isolamento completo de dados por empresa
- **Gestão de Tarefas**: CRUD completo com status e prioridades
- **Filtros**: Por status, prioridade e usuário
- **Interface Responsiva**: Frontend em Vue.js
- **API RESTful**: Backend em Laravel

## 🔧 Principais Endpoints da API

- `POST /api/auth/login` - Login
- `GET /api/tasks` - Listar tarefas
- `POST /api/tasks` - Criar tarefa
- `PUT /api/tasks/{id}` - Atualizar tarefa
- `DELETE /api/tasks/{id}` - Excluir tarefa
