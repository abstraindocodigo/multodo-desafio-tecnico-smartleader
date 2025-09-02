# Frontend - MultiTodo

Interface do usuário do sistema MultiTodo - Gerenciador de Tarefas desenvolvido com Vue.js 2 e Vite.

## Tecnologias

- **Vue.js 2** - Framework JavaScript
- **Vite** - Build tool e dev server
- **Vue Router** - Roteamento
- **Vuex** - Gerenciamento de estado
- **Tailwind CSS** - Framework CSS
- **Axios** - Cliente HTTP
- **Iconify** - Ícones

## Requisitos

- Node.js 16+
- Yarn

## Instalação

1. Clone o repositório
2. Instale as dependências:
```bash
yarn install
```

## Execução

### Desenvolvimento
```bash
yarn dev
```
A aplicação estará disponível em `http://localhost:3000`

### Build para produção
```bash
yarn build
```

### Preview da build
```bash
yarn preview
```

### Docker
```bash
docker build -t multodo-frontend .
docker run -p 3000:3000 multodo-frontend
```

## Estrutura do Projeto

- `src/components/` - Componentes Vue reutilizáveis
- `src/views/` - Páginas da aplicação
- `src/router/` - Configuração de rotas
- `src/stores/` - Gerenciamento de estado (Vuex)
- `src/services/` - Serviços e utilitários
- `src/api/` - Configuração da API