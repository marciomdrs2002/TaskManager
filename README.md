# 📋 TaskManager

Um sistema moderno de gerenciamento de tarefas construído com Laravel, Inertia.js e Vue.js.

## ✅ Requisitos

- 🐘 PHP 8.1 ou superior
- 🎼 Composer
- 📦 Node.js 16 ou superior
- 📦 NPM ou Yarn
- 💾 MySQL ou PostgreSQL

## 🚀 Instalação e Configuração

1. Clone o repositório:
   ```sh
   git clone https://github.com/seu-usuario/taskmanager.git
   cd taskmanager
   ```

2. Instale as dependências PHP:
   ```sh
   composer install
   ```

3. Instale as dependências JavaScript:
   ```sh
   npm install
   ```

4. Configure o ambiente:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure o banco de dados:
   - Edite o arquivo `.env` com suas configurações de banco de dados.

6. Execute as migrações e seeders:
   ```sh
   php artisan migrate --seed
   ```

## 🔌 Iniciando a aplicação

1. Inicie o servidor backend:
   ```sh
   php artisan serve
   ```
   O servidor Laravel estará disponível em [http://localhost:8000](http://localhost:8000) 🌐

2. Inicie o servidor de desenvolvimento frontend:
   ```sh
   npm run dev
   ```
   O Vite compilará os assets e estará disponível para hot reload. 🔄

## 🔑 Acesso

Utilize as seguintes credenciais para acessar o sistema:

- **Email:** 📧 `admin@example.com`
- **Senha:** 🔒 `password`

## ✨ Funcionalidades

- 📝 Gerenciamento de tarefas
- 👥 Atribuição de tarefas a usuários
- 🔴 Definição de prioridades e prazos
- ✓ Marcação de tarefas como concluídas

## 🛠️ Tecnologias

- ⚙️ Laravel 10
- 🔄 Inertia.js
- ⚡ Vue 3
- 🎨 Tailwind CSS
- 💅 PrimeVue

