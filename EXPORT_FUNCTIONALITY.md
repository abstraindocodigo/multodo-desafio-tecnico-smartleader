# Funcionalidade de Exportação CSV

## Como funciona

A funcionalidade de exportação CSV permite que os usuários exportem suas tarefas filtradas para um arquivo CSV que pode ser baixado diretamente no navegador.

## Implementação

### Backend (Laravel)

1. **ExportService** (`backend/app/Services/ExportService.php`)
   - Método `exportTasksToCsv()`: Gera o arquivo CSV com as tarefas filtradas
   - Método `getExportFilePath()`: Retorna o caminho do arquivo para download
   - Método `deleteExportFile()`: Remove arquivos temporários

2. **ExportController** (`backend/app/Http/Controllers/Api/ExportController.php`)
   - Endpoint `POST /api/export/tasks`: Gera o arquivo CSV
   - Endpoint `GET /api/export/download/{filename}`: Faz download do arquivo

3. **Rotas** (`backend/routes/api.php`)
   ```php
   Route::prefix('export')->group(function () {
       Route::post('tasks', [ExportController::class, 'exportTasks']);
       Route::get('download/{filename}', [ExportController::class, 'downloadExport']);
   });
   ```

### Frontend (Vue.js)

1. **API Client** (`frontend/src/api/tasks.js`)
   - `exportTasks(filters)`: Chama o endpoint de exportação
   - `downloadExport(filename)`: Faz download do arquivo

2. **Service** (`frontend/src/services/taskService.js`)
   - Métodos que encapsulam as chamadas da API

3. **Store** (`frontend/src/stores/taskStore.js`)
   - Método `exportTasks()`: Gerencia todo o fluxo de exportação
   - Aplica os filtros atuais
   - Faz download automático do arquivo

4. **Interface** (`frontend/src/views/tasks/TaskListView.vue`)
   - Botão "Exportar CSV" no header da lista de tarefas
   - Usa os filtros aplicados para exportar apenas as tarefas filtradas

## Como usar

1. **Na interface web:**
   - Acesse a lista de tarefas
   - Aplique filtros se desejar (status, prioridade, usuário)
   - Clique no botão "Exportar CSV"
   - O arquivo será baixado automaticamente

2. **Programaticamente:**
   ```javascript
   // Exportar com filtros específicos
   const result = await taskStore.exportTasks()
   
   // Exportar com filtros customizados
   taskStore.setFilters({ status: 'concluida', priority: 'alta' })
   const result = await taskStore.exportTasks()
   ```

## Estrutura do CSV

O arquivo CSV exportado contém as seguintes colunas:
- ID
- Título
- Descrição
- Status
- Prioridade
- Data de Vencimento
- Usuário
- Empresa
- Data de Criação
- Data de Atualização

## Filtros aplicados

A exportação respeita os filtros atualmente aplicados na interface:
- **Status**: pendente, em_andamento, concluida
- **Prioridade**: baixa, media, alta
- **Usuário**: ID do usuário específico

## Segurança

- Apenas usuários autenticados podem exportar
- Usuários só podem exportar tarefas de sua própria empresa
- Arquivos temporários são criados no storage local do Laravel
- Downloads são validados antes de serem servidos

## Limitações

- Arquivos são temporários e podem ser removidos pelo sistema
- Não há limite de tamanho implementado (depende da configuração do servidor)
- Formato fixo em CSV (não configurável pelo usuário)
