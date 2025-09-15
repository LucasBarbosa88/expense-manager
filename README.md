# API - Sistema de Gerenciamento de Despesas Pessoais

## Base URL
```
http://localhost:8000
```

## Autenticação
- **Registro:** `POST /users/register`  
- **Login:** `POST /users/login` (retorna token JWT)  
- Para acessar endpoints protegidos, adicione o header:  
  ```
  Authorization: Bearer {accessToken}
  ```

---

## Categorias
- **Listar:** `GET /categories`  
- **Criar:** `POST /categories`  
- **Atualizar:** `PUT /categories/{id}`  
- **Deletar:** `DELETE /categories/{id}`  

## Despesas
- **Listar:** `GET /expenses`  
  - Filtros opcionais: `category_id`, `start_date`, `end_date`, `page`, `limit`  
- **Criar:** `POST /expenses`  
- **Visualizar:** `GET /expenses/{id}`  
- **Atualizar:** `PUT /expenses/{id}`  
- **Deletar:** `DELETE /expenses/{id}`  

---

## Observações
- Endpoints de categorias e despesas exigem autenticação JWT.  
- Cada usuário só pode gerenciar suas próprias despesas.  
- Datas no formato: `YYYY-MM-DD`.  
- Paginação padrão: `page=1`, `limit=10`.  
- Erros comuns:  
  - `401 Unauthorized` → token inválido/ausente  
  - `404 Not Found` → recurso não existe  

---

## Instalação e Execução

1. **Clone o repositório**  
   ```bash
   git clone https://github.com/LucasBarbosa88/expense-manager.git
   cd expense-manager
   ```

2. **Configure variáveis de ambiente**  
   Crie um arquivo `.env` (ou edite `config/params.php`) com:  
   - Dados do banco de dados  
   - JWT_SECRET  

3. **Instale dependências**  
   ```bash
   composer install
   ```

4. **Crie o banco e rode migrations**  
   ```bash
   php yii migrate
   ```

5. **Suba o servidor**  
   ```bash
   php yii serve
   ```
   Ou via Docker:  
   ```bash
   docker-compose up -d
   ```

---

## Decisões Técnicas

- **Yii2 Framework** estrutura MVC.  
- **JWT (JSON Web Token)** para autenticação stateless e segura.  
- **Migrations** para versionamento do banco de dados.  
- **Arquitetura RESTful** para manter endpoints padronizados e independentes.  
- **Validações no backend** para garantir integridade dos dados antes do salvamento.  

---

## Rodando os Testes  

```bash
php vendor/bin/codecept run
```