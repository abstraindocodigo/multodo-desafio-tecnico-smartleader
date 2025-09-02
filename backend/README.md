# Backend - MultiTodo API

API REST desenvolvida em Laravel 8 para o sistema MultiTodo.

## Tecnologias

- **Laravel 8** - Framework PHP
- **JWT Auth** - Autenticação via tokens
- **Laravel Sanctum** - Autenticação de API
- **CORS** - Cross-Origin Resource Sharing

## Requisitos

- PHP 7.3+ ou 8.0+
- Composer
- MySQL/PostgreSQL/SQLite

## Instalação

1. Clone o repositório
2. Instale as dependências:
```bash
composer install
```

3. Configure o arquivo `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no `.env`

5. Execute as migrações:
```bash
php artisan migrate
```

## Execução

### Desenvolvimento
```bash
php artisan serve
```

### Docker
```bash
docker build -t multodo-backend .
docker run -p 8000:8000 multodo-backend
```

## Endpoints

A API estará disponível em `http://localhost:8000/api`

## Testes

```bash
php artisan test
```