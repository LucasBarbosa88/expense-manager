# API - Gerenciamento de Despesas Pessoais (Versão Estendida)

## 1. Autenticação e Autorização
A API utiliza JWT para autenticação. Todos os endpoints de gerenciamento de despesas exigem que o usuário esteja autenticado.

### 1.1 Registro de Usuário
**POST** `/user/register`

**Parâmetros (JSON)**
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Resposta de Sucesso (201)**
```json
{
  "message": "Usuário registrado com sucesso",
  "access_token": "<JWT_TOKEN>"
}
```

### 1.2 Login de Usuário
**POST** `/user/login`

**Parâmetros (JSON)**
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Resposta de Sucesso (200)**
```json
{
  "access_token": "<JWT_TOKEN>"
}
```

**Observação:** Este token deve ser enviado no header Authorization de todos os endpoints protegidos como:
```
Authorization: Bearer <JWT_TOKEN>
```

---

## 2. Gerenciamento de Despesas
Todos os endpoints abaixo exigem autenticação.

### 2.1 Criar Despesa
**POST** `/expenses`

**Parâmetros (JSON)**
```json
{
  "description": "Almoço",
  "category_id": 1,
  "amount": 45.50,
  "date": "2025-09-11"
}
```

**Resposta de Sucesso (201)**
```json
{
  "id": 10,
  "description": "Almoço",
  "category_id": 1,
  "amount": 45.50,
  "date": "2025-09-11"
}
```

### 2.2 Listar Despesas
**GET** `/expenses`

**Parâmetros (query string)**
- `page` (opcional, default=1)
- `limit` (opcional, default=10)
- `category_id` (opcional)
- `start_date` (opcional)
- `end_date` (opcional)

**Exemplo:** `/expenses?page=1&limit=10&category_id=2&start_date=2025-09-01&end_date=2025-09-30`

**Resposta de Sucesso (200)**
```json
{
  "data": [
    {
      "id": 1,
      "description": "Almoço",
      "category_id": 1,
      "amount": 45.50,
      "date": "2025-09-11"
    }
  ],
  "pagination": {
    "total": 1,
    "page": 1
  }
}
```

### 2.3 Detalhes da Despesa
**GET** `/expenses/{id}`

**Resposta de Sucesso (200)**
```json
{
  "id": 1,
  "description": "Almoço",
  "category_id": 1,
  "amount": 45.50,
  "date": "2025-09-11"
}
```

### 2.4 Atualizar Despesa
**PUT** `/expenses/{id}`

**Parâmetros (JSON)**
```json
{
  "description": "Almoço atualizado",
  "category_id": 2,
  "amount": 50.00,
  "date": "2025-09-11"
}
```

**Resposta de Sucesso (200)**
```json
{
  "id": 1,
  "description": "Almoço atualizado",
  "category_id": 2,
  "amount": 50.00,
  "date": "2025-09-11"
}
```

### 2.5 Deletar Despesa
**DELETE** `/expenses/{id}`

**Resposta de Sucesso (204)**
```
Sem conteúdo
```

---

## 3. Gerenciamento de Categorias
### 3.1 Listar Categorias
**GET** `/categories`

**Resposta de Sucesso (200)**
```json
[
  {"id": 1, "name": "Alimentação"},
  {"id": 2, "name": "Transporte"},
  {"id": 3, "name": "Lazer"}
]
```

### 3.2 Criar Categoria
**POST** `/categories`

**Parâmetros (JSON)**
```json
{
  "name": "Educação"
}
```

**Resposta de Sucesso (201)**
```json
{
  "id": 4,
  "name": "Educação"
}
```

### 3.3 Atualizar Categoria
**PUT** `/categories/{id}`

**Parâmetros (JSON)**
```json
{
  "name": "Educação e Cursos"
}
```

### 3.4 Deletar Categoria
**DELETE** `/categories/{id}`

**Resposta de Sucesso (204)**
```
Sem conteúdo
```

---

## 4. Observações Técnicas
- Autenticação: JWT (`Authorization: Bearer <token>`)
- Banco de dados: MySQL
- Framework: Yii2, arquitetura MVC
- Todas as operações de despesas são restritas ao usuário logado.
- Filtros de listagem por categoria e período são opcionais.
- Paginação implementada em `/expenses` com `page` e `limit`.

---

## 5. Requisitos Extras
- Testes unitários podem ser implementados via Codeception.
- Ambiente pode ser executado via Docker com `docker-compose.yml`.
- Código estruturado seguindo princípios SOLID e boas práticas de Yii2.

