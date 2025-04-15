# Projeto Innyx - Laravel + Vue + Docker

Este projeto é composto por um backend em Laravel e um frontend em Vue.js, totalmente containerizado com Docker.

---

## 🐳 Rodando com Docker Compose

### Pré-requisitos
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### Passos:

1. Clone o projeto (caso ainda não tenha).
2. Navegue até a raiz `Teste_Innyx/`.
3. Execute:

```bash
docker-compose up --build
```

Isso irá:
- Criar os containers `mysql`, `laravel`, `vue`.
- Rodar as **migrations e seeders** automaticamente.
- Backend disponível em: http://localhost:8000
- Frontend disponível em: http://localhost:5173

---

## ☕ Caso queira rodar manualmente ou dê algum problema no Docker

### Backend (Laravel)

1. Navegue até `innyx_back/`:

```bash
cd innyx_back
```

2. Instale as dependências:

```bash
composer install
```

3. Copie e edite o `.env`:

```bash
cp .env.example .env
```

4. Atualize variáveis de banco no `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```
# para subir o mysql localmente,rodar  o seguinte comando
  docker-compose up -d

5. Gere a chave:

```bash
php artisan key:generate
```
5. Gere a chave JWT:
```bash
php artisan jwt:secret
```

6. Execute as migrations e seeders:

```bash
php artisan migrate:fresh --seed
```

7. Inicie o servidor:

```bash
php artisan serve
```

### Frontend (Vue.js)

1. Navegue até `innyx_front/`:

```bash
cd innyx_front
```

2. Instale as dependências:

```bash
npm install
```

3. Inicie o servidor de desenvolvimento:

```bash
npm run dev
```

---

## 🧪 Acesso rápido

- Frontend: http://localhost:5173
- Backend: http://localhost:8000/api
