# BRQ - Sistema de Transações

> Desafio técnico para gerenciamento de transações financeiras com análise de risco

## 📋 Sobre o Projeto

Este projeto é uma API RESTful desenvolvida em Laravel para gerenciar transações financeiras com sistema de autenticação e análise de risco. O sistema permite criar, visualizar, atualizar e excluir transações, além de fornecer autenticação via API tokens.

## 🚀 Tecnologias Utilizadas

- **PHP 8.2+**
- **Laravel 12.x**
- **MySQL** (banco de dados principal)
- **Laravel Sanctum** (autenticação API)
- **Pest PHP** (testes)
- **Laravel Pint** (code style)
- **PHP CS Fixer** (formatação de código)
- **Docker & Docker Compose** (containerização)

## 📁 Estrutura do Projeto

```
brq/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── LoginController.php      # Autenticação
│   │   └── TransactionController.php # CRUD de transações
│   ├── Models/
│   │   ├── User.php                 # Model do usuário
│   │   └── Transaction.php          # Model da transação
│   └── Enums/
│       └── TransactionStatus.php    # Status das transações
├── database/
│   ├── migrations/                  # Migrações do banco
│   ├── factories/                   # Factories para testes
│   └── seeders/                     # Seeders
├── tests/                          # Testes automatizados
├── docker/                         # Configurações Docker
└── routes/api.php                  # Rotas da API
```

## 🔗 Endpoints da API

### 🔐 Autenticação

| Método | Endpoint      | Descrição           | Middleware |
|--------|---------------|---------------------|------------|
| POST   | `/api/login`  | Fazer login         | guest      |
| POST   | `/api/logout` | Fazer logout        | guest      |
| GET    | `/api/user`   | Dados do usuário    | auth       |

### 💰 Transações

| Método | Endpoint                    | Descrição                | Middleware |
|--------|-----------------------------|--------------------------|------------|
| GET    | `/api/transactions`         | Listar transações        | auth       |
| POST   | `/api/transactions`         | Criar transação          | auth       |
| GET    | `/api/transactions/{id}`    | Visualizar transação     | auth       |
| PUT    | `/api/transactions/{id}`    | Atualizar transação      | auth       |
| DELETE | `/api/transactions/{id}`    | Excluir transação        | auth       |

## 🐳 Executando com Docker

### Pré-requisitos

- Docker
- Docker Compose

### 1. Clone o repositório

```bash
git clone <repository-url>
cd brq
```

### 2. Configure o ambiente

```bash
cp .env.example .env
```

### 3. Construa e execute os containers

```bash
# Construir as imagens
docker-compose build

# Executar os containers
docker-compose up -d
```

### 4. Configure a aplicação

```bash
# Atualizando as dependências
docker-compose exec app composer install

# Gerar chave da aplicação
docker-compose exec app php artisan key:generate

# Executar migrações
docker-compose exec app php artisan migrate

# Executar seeders (opcional)
docker-compose exec app php artisan db:seed
```

### 5. Acesse a aplicação

- **API**: http://localhost:8000
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## 💻 Executando Localmente (sem Docker)

### Pré-requisitos

- PHP 8.2+
- Composer
- MySQL

### 1. Instale as dependências

```bash
composer install
```

### 2. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure o banco de dados

```bash

# Executar migrações
php artisan migrate

# Executar seeders (opcional)
php artisan db:seed
```

### 4. Execute a aplicação

```bash
# Servidor de desenvolvimento
php artisan serve

# Ou usando o script customizado
composer run dev
```

## 🧪 Executando Testes

### Com Docker

```bash
# Executar todos os testes
docker-compose exec app php artisan test

# Executar testes com Pest
docker-compose exec app ./vendor/bin/pest
```

### Localmente

```bash
# Executar todos os testes
php artisan test

# Executar testes com Pest
./vendor/bin/pest

# Executar com o script do composer
composer test
```

## 📝 Exemplos de Uso da API

### 1. Fazer Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

**Resposta:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com"
  }
}
```

### 2. Criar Transação

```bash
curl -X POST http://localhost:8000/api/transactions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|abc123..." \
  -d '{
    "amount": 100.50,
    "description": "Compra online",
    "document": "11.222.333/0001-44",
    "location": "São Paulo, SP"
  }'
```

### 3. Listar Transações

```bash
curl -X GET http://localhost:8000/api/transactions \
  -H "Authorization: Bearer 1|abc123..."
```

## 🔧 Scripts Disponíveis

```bash
# Desenvolvimento com hot-reload
composer run dev

# Executar testes
composer test

# Formatação de código
./vendor/bin/pint

# Análise de código
./vendor/bin/rector
```

## 🌐 Variáveis de Ambiente

Principais variáveis de ambiente:

```env
APP_NAME=BRQ
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=brq
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=brq-redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 📊 Análise de Risco

O sistema inclui análise de risco automática para transações, considerando:

- Valor da transação
- Localização geográfica
- Histórico do usuário
- Padrões de comportamento

## 🔒 Autenticação

O sistema utiliza Laravel Sanctum para autenticação via API tokens:

- Tokens são gerados no login
- Tokens são revogados no logout
- Middleware `auth:sanctum` protege rotas sensíveis

## 🐛 Debug e Logs

```bash
# Visualizar logs em tempo real (com Docker)
docker-compose exec app php artisan pail

# Limpar caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
```

## 📈 Performance

- Cache Redis configurado
- Otimização de queries com Eloquent
- Background jobs para processamento assíncrono
- Indexação adequada no banco de dados

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feat/nova-feature`)
3. Commit suas mudanças (`git commit -m 'feat(api): add nova feature'`)
4. Push para a branch (`git push origin feat/nova-feature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para dúvidas ou suporte, entre em contato:

- **Email**: lucas.heber07@gmail.com
- **GitHub**: @lucasheber

---

⚡ **Desenvolvido com Laravel + Docker para máxima performance e escalabilidade**
