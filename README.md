# 📋 Organizador de Tarefas

Aplicação web para gerenciamento de tarefas, desenvolvida com **PHP, MySQL e Bootstrap 5.3**. O sistema permite que cada usuário crie sua própria conta, faça login e gerencie suas tarefas de forma individual e segura, com funcionalidades completas de CRUD (*Create, Read, Update e Delete*).

## 🚀 Funcionalidades

- Cadastro e autenticação de usuários.
- Armazenamento seguro de senhas com `password_hash()`.
- Criação, listagem, edição e exclusão de tarefas (CRUD completo).
- Gerenciamento individual: cada usuário acessa e gerencia apenas suas próprias tarefas.
- Definição de título, descrição, data, hora, prioridade e status das tarefas.
- Níveis de prioridade: baixa, média e alta.
- Status disponíveis: pendente, em andamento, concluída e cancelada.
- Validação de dados no servidor e exibição de mensagens de erro.
- Interface responsiva com tema escuro.
- Identificação visual das prioridades por cores.

## 🔒 Segurança

O projeto utiliza mecanismos de segurança para proteger os dados dos usuários e reduzir vulnerabilidades comuns em aplicações web:

- **Proteção contra SQL Injection:** consultas preparadas (*prepared statements*) com MySQLi.
- **Proteção contra XSS:** escape de saída com `htmlspecialchars()`.
- **Proteção contra CSRF:** tokens de validação nos formulários enviados via POST.
- **Gerenciamento de sessões:** regeneração do ID da sessão durante o login.
- **Controle de acesso:** verificação de usuário para restringir o acesso e as operações sobre as próprias tarefas.
- **Criptografia de senhas:** utilização de `password_hash()` para armazenar senhas de forma segura.

## 🛠️ Tecnologias utilizadas

- **PHP 8+** — lógica de back-end e processamento das requisições.
- **MySQL / MariaDB** — armazenamento e gerenciamento dos dados.
- **MySQLi** — conexão e interação com o banco de dados.
- **HTML5** — estrutura das páginas.
- **CSS3** — estilização e responsividade.
- **Bootstrap 5.3** — componentes e recursos de interface.

## ⚙️ Como executar o projeto

### Pré-requisitos

- PHP 8 ou superior.
- MySQL ou MariaDB.
- Servidor web Apache.
- XAMPP ou ambiente equivalente.

### 1. Clone o repositório

Abra o terminal na pasta `htdocs` do XAMPP e execute:

```bash
git clone https://github.com/arthurr126/Crud_Tarefas.git
```

Entre na pasta do projeto:

```bash
cd Crud_Tarefas
```

### 2. Configure o banco de dados

Inicie o Apache e o MySQL pelo painel do XAMPP.

Se o arquivo `banco.sql` estiver disponível na raiz do projeto, importe-o pelo phpMyAdmin ou pelo terminal:

```bash
mysql -u root -p < banco.sql
```

**Observação:** o comando acima pressupõe que o cliente MySQL esteja disponível no terminal e que o banco de dados esteja configurado para aceitar essa conexão. No XAMPP, você também pode importar o arquivo pelo phpMyAdmin.

### 3. Configure a conexão

Verifique as credenciais de conexão no arquivo `conexao.php`.

Configure o host, o usuário, a senha e o nome do banco de dados de acordo com seu ambiente local.

**Importante:** não publique senhas reais ou outras credenciais no repositório.

### 4. Execute a aplicação

Com o Apache e o MySQL em funcionamento, acesse no navegador:

```text
http://localhost/Crud_Tarefas/registro.php
```

Crie uma conta para começar a utilizar o sistema.

## 📁 Estrutura do projeto

```text
CRUD_TAREFAS/
├── _form_tarefa.php    # Formulário compartilhado de tarefas
├── conexao.php         # Conexão com o banco de dados
├── criar_t.php         # Criação de tarefas
├── deletar.php         # Exclusão de tarefas
├── editar_tarefa.php   # Edição de tarefas
├── funcoes.php         # Funções auxiliares, validações e CSRF
├── index.php           # Listagem e gerenciamento de tarefas
├── login.php           # Autenticação de usuários
├── logout.php          # Encerramento da sessão
├── registro.php        # Cadastro de usuários
├── style.css           # Estilos de login e cadastro
└── styleindex.css      # Estilos da interface de tarefas
```

> **Nota:** se o arquivo `banco.sql` estiver presente no repositório, adicione-o à estrutura acima.

## 🔮 Próximas melhorias

- [ ] Implementar filtros e busca de tarefas.
- [ ] Permitir marcar tarefas como concluídas com um clique.
- [ ] Destacar tarefas atrasadas.
- [ ] Adicionar paginação à listagem de tarefas.
- [ ] Implementar recuperação de senha.

## 👨‍💻 Autor

**Arthur**

- GitHub: [@arthurr126](https://github.com/arthurr126)

---

Desenvolvido como projeto de estudo para praticar desenvolvimento web, lógica de programação, operações CRUD, autenticação e integração com banco de dados.
