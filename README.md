# API - Sistema de Gerenciamento de Despesas Pessoais (Resumo)

## Base URL
```
http://localhost:8000
```

## Autenticação
- **Registro:** POST /users/register
- **Login:** POST /users/login (recebe token JWT)
- Adicione o header `Authorization: Bearer {accessToken}` para acessar endpoints protegidos.

## Categorias
- **Listar:** GET /categories
- **Criar:** POST /categories
- **Atualizar:** PUT /categories/{id}
- **Deletar:** DELETE /categories/{id}

## Despesas
- **Listar:** GET /expenses (com filtros opcionais: `category_id`, `start_date`, `end_date`, `page`, `limit`)
- **Criar:** POST /expenses
- **Visualizar:** GET /expenses/{id}
- **Atualizar:** PUT /expenses/{id}
- **Deletar:** DELETE /expenses/{id}

## Observações
- Endpoints de categorias e despesas exigem autenticação JWT.
- Cada usuário gerencia apenas suas próprias despesas.
- Datas: `YYYY-MM-DD`.
- Paginação padrão: `page=1`, `limit=10`.
- Erro de autenticação retorna 401, recurso não encontrado retorna 404.

## Rodando o Projeto
1. Clone o repositório.
2. Configure `.env` ou `config/params.php` com dados do banco e JWT secret.
3. Rode `composer install`.
4. Crie o banco de dados e rode migrations.
5. Inicie o servidor: `php yii serve` ou via Docker.