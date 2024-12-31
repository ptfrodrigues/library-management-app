# Biblioteca - Aplicação de Gestão

Este projeto é uma aplicação para gerir uma biblioteca, permitindo organizar um grande volume de livros e escolher quais serão exibidos. A aplicação está conectada a uma API para simular livros e as suas respetivas imagens.

## Como começar após clonar o projeto

1. **Instalar dependências**:
   Após clonar o repositório, instale todas as dependências do projeto executando:
   ```bash
   composer install
   npm install
   npm run dev
   ```

2. **Configuração do ficheiro `.env`**:
   - Copie o ficheiro `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Atualize as configurações no ficheiro `.env` conforme necessário. Principais diferenças para o `.env.example`:
     - **DB_DATABASE, DB_USERNAME, DB_PASSWORD**: Configure os dados do banco de dados para corresponder ao seu ambiente.
     - **APP_URL**: Certifique-se de que reflete a URL correta da aplicação.

3. **Migrar e popular a base de dados**:
   - Execute as migrações e seeders:
     ```bash
     php artisan migrate --seed
     ```

4. **Iniciar a app**:
   Inicie o servidor local:
   ```bash
   composer run dev
   ```

## Funções Disponíveis

A aplicação define quatro funções principais com permissões específicas, utilizando o pacote [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission):

1. **Admin**:
   - Acesso total a todas as funcionalidades.
   - Permissões: Criar, editar, eliminar livros e autores; visualizar, criar, editar e eliminar utilizadores; gerir o dashboard; entre outras.

2. **Manager**:
   - Gerir livros, autores e utilizadores.
   - Permissões: Criar, editar e eliminar livros e autores; visualizar e gerir utilizadores; gerir o catálogo; aceder ao dashboard.

3. **Librarian**:
   - Foco na gestão básica.
   - Permissões: Criar e editar livros e autores; visualizar utilizadores; aceder ao catálogo e ao dashboard.

4. **Member**:
   - Acesso limitado ao catálogo.
   - Permissões: Apenas visualizar o catálogo.

## Dependências do Projeto

- **Backend**:
  - Laravel (framework principal).
  - Spatie Laravel Permission (gestão de permissões).
  - Faker (para gerar dados fictícios).

- **Frontend**:
  - Tailwind CSS (para estilos).
  - Livewire (componentes dinâmicos em Laravel).

- **Outras**:
  - API para simulação de livros e imagens.

## Estrutura de Permissões e Seeders

### Permissões Criadas

- `create_books`, `edit_books`, `delete_books`
- `create_authors`, `edit_authors`, `delete_authors`
- `view_users`, `create_users`, `edit_users`, `delete_users`
- `access_dashboard`, `force_delete`
- `view_catalog`, `create_catalog`, `edit_catalog`, `delete_catalog`

### Seeders

O seeder `RolePermissionSeeder` cria as permissões e atribui-as aos respetivos papéis:

- **Admin**: Todas as permissões.
- **Manager**: Permissões relacionadas à gestão de livros, autores e utilizadores.
- **Librarian**: Permissões de gestão básica e visualização.
- **Member**: Apenas visualização do catálogo.

## Considerações Finais

Com esta aplicação, é possível gerir eficientemente uma biblioteca, selecionar os livros a serem exibidos, e gerir utilizadores com diferentes níveis de acesso. A integração com uma API externa permite obter dados fictícios para testes e simulação de funcionalidades.

