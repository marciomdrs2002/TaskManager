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

## **Arquitetura**

O sistema segue uma arquitetura de camadas bem definida, baseada em boas práticas do Laravel:

### **Backend**

1. **Camada de Controladores (Controllers)**
    - Implementação de controladores RESTful
    - Controlador base abstrato (`BaseController`) que implementa operações CRUD genéricas
    - Controladores específicos que extendem a funcionalidade base (ex: `TaskController`)
2. **Camada de Modelos (Models)**
    - Modelo base (`BaseModel`) com funcionalidades compartilhadas
    - Modelos específicos como `Task` com relacionamentos e lógica de negócio
    - Uso de global scopes para aplicar filtros de segurança automaticamente
3. **Camada de Repositórios**
    - Implementação do padrão Repository para abstrair a lógica de acesso a dados
    - `TaskRepository` para operações específicas de tarefas
4. **Camada de Resources**
    - API Resources para transformar modelos em representações JSON
    - `TaskResource` para exposição controlada de atributos
5. **Rotas e Middleware**
    - Organização modular de rotas (ex: tasks.php)
    - Middleware de autenticação para proteger recursos

### **Frontend**

1. **Framework Vue.js com Inertia.js**
    - Abordagem SPA (Single Page Application) com navegação sem recarregamento
    - Componentes reutilizáveis
2. **Design Responsivo**
    - Interface adaptável a diferentes tamanhos de tela
    - Implementação usando Tailwind CSS
3. **Temas Claro/Escuro**
    - Suporte completo a modo claro e escuro

## **Principais Características Implementadas**

1. **Autenticação e Autorização**
    - Sistema de login/registro
    - Exposição de permissões para o frontend via resources
2. **Gerenciamento de Tarefas**
    - CRUD completo de tarefas
    - Atribuição de tarefas a usuários
    - Priorização (baixa, média, alta)
    - Definição de prazos com validação de datas
    - Marcação de tarefas como concluídas
3. **Validação e Segurança**
    - Validação de entradas do usuário
    - Global scopes para filtrar automaticamente tarefas por propriedade/atribuição
    - Mensagens de validação personalizadas
4. **Interface de Usuário**
    - Página de boas-vindas personalizada
    - Dashboard interativo
    - Listas de tarefas com filtragem e ordenação
    - Formulários dinâmicos baseados em configuração

## **Melhorias e Alterações Principais**

1. **Arquitetura de Formulários Dinâmicos**
    - Implementação de um sistema de formulários baseado em configuração
    - Armazenamento de definições de formulário em forms
2. **Aprimoramento do Sistema de Validação**
    - Adição de validação para evitar datas no passado (`after_or_equal:today`)
    - Implementação de mensagens de validação personalizadas
3. **Segurança e Escopo de Dados**
    - Global scope que limita o acesso a tarefas baseado em propriedade e atribuição
4. **API Resources Aprimorados**
    - Exposição seletiva de atributos e permissões para o frontend
    - Atributos virtuais para simplificar a interface
5. **UI/UX Modernizado**
    - Redesign da página de boas-vindas
    - Implementação de tema neutro compatível com modo claro/escuro
    - Melhorias na visualização de imagens e elementos visuais