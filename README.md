# AgriMarketplace - Plataforma de Venda de Produtos Agrícolas

Plataforma web desenvolvida em Laravel + Livewire para conectar fornecedores e compradores de produtos agrícolas.

## Características Principais

- ✅ Sistema multi-tenant com 3 níveis de acesso (Admin, Fornecedor, Cliente)
- ✅ Gestão completa de produtos agrícolas
- ✅ Sistema inteligente que exibe produtos com maior preço quando múltiplos fornecedores oferecem o mesmo item
- ✅ Gestão de stock por fornecedor
- ✅ Sistema de cotações
- ✅ Interface responsiva com Tailwind CSS
- ✅ Integração com WhatsApp e chamadas telefônicas

## Tecnologias Utilizadas

- Laravel 12.x
- Livewire 3.x
- Laravel Breeze (autenticação)
- Tailwind CSS
- SQLite (banco de dados)

## Instalação

1. **Clone o repositório ou acesse a pasta do projeto**
```bash
cd /Users/salumsaidsalum/Desktop/agrimarketplace
```

2. **Instalar dependências**
```bash
composer install
npm install
```

3. **Configurar ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Executar migrations e seeders**
```bash
php artisan migrate:fresh --seed
```

5. **Compilar assets**
```bash
npm run dev
```

6. **Iniciar servidor**
```bash
php artisan serve
```

O sistema estará disponível em: `http://localhost:8000`

## Credenciais de Teste

### Administrador
- Email: `admin@agri.com`
- Senha: `password`

### Fornecedores
- Email: `fornecedor1@agri.com` / `fornecedor2@agri.com` / `fornecedor3@agri.com`
- Senha: `password`

### Cliente
- Email: `cliente@agri.com`
- Senha: `password`

## Estrutura do Sistema

### Níveis de Acesso

#### 👨‍💼 Admin (`/admin/*`)
- Dashboard com estatísticas
- Gestão de categorias
- Aprovação/rejeição de fornecedores
- Gestão de produtos (todos)
- Visualização de cotações

#### 🏢 Fornecedor (`/fornecedor/*`)
- Dashboard pessoal
- Gestão dos seus produtos
- Gestão de stock
- Visualização de estatísticas

#### 👤 Cliente (Área Pública)
- Visualização de produtos
- Filtro por categoria
- Busca de produtos
- Solicitação de cotações
- Contato via WhatsApp/Telefone

## Lógica de Exibição de Produtos

Quando múltiplos fornecedores cadastram o mesmo produto (ex: Gergelim), o sistema:

1. Agrupa produtos pelo nome
2. Retorna apenas o produto com **maior preço** de cada grupo
3. Exibe informações do fornecedor correspondente

Exemplo:
- Fornecedor 1: Gergelim - 200 MT/kg
- Fornecedor 2: Gergelim - 250 MT/kg
- Fornecedor 3: Gergelim - 300 MT/kg

**Resultado:** Sistema exibe apenas o Gergelim do Fornecedor 3 (300 MT/kg)

Esta lógica está implementada em:
- `app/Livewire/Public/Products.php` (linha 37-52)
- `app/Models/Product.php` método `getHighestPriceProduct()`

## Modelos e Relacionamentos

### User
- `role`: admin | supplier | customer
- Relacionamento: `hasOne` Supplier

### Supplier
- `status`: pending | approved | rejected
- Relacionamento: `belongsTo` User, `hasMany` Products

### Category
- Relacionamento: `hasMany` Products

### Product
- Relacionamento: `belongsTo` Supplier, `belongsTo` Category, `hasMany` Stocks

### Stock
- Relacionamento: `belongsTo` Product

### QuoteRequest
- Pedidos de cotação de clientes
- Relacionamento: `belongsTo` Product

## Rotas Principais

### Públicas
- `/` - Página inicial
- `/produtos` - Lista de produtos
- `/cotacao` - Formulário de cotação

### Admin
- `/admin/dashboard` - Dashboard admin
- `/admin/categorias` - Gestão de categorias
- `/admin/fornecedores` - Gestão de fornecedores
- `/admin/produtos` - Gestão de produtos
- `/admin/cotacoes` - Cotações recebidas

### Fornecedor
- `/fornecedor/dashboard` - Dashboard fornecedor
- `/fornecedor/meus-produtos` - Gestão de produtos
- `/fornecedor/stock` - Gestão de stock

## Dados de Demonstração

O seeder cria:
- 4 categorias (Cereais, Oleaginosas, Leguminosas, Tubérculos)
- 3 fornecedores
- 9 produtos (3 por fornecedor)
- Exemplos de produtos com mesmo nome e preços diferentes (Gergelim: 200, 250, 300 MT)

## Próximos Passos (Sugestões)

- [ ] Sistema de pedidos/compras
- [ ] Notificações por email
- [ ] Sistema de avaliações
- [ ] Chat entre compradores e fornecedores
- [ ] Relatórios e analytics avançados
- [ ] Upload de imagens de produtos
- [ ] Sistema de pagamentos online

## Suporte

Para dúvidas ou problemas, entre em contato através de:
- WhatsApp: +258 84 000 0000
- Email: info@agrimarketplace.co.mz

---

Desenvolvido com ❤️ usando Laravel + Livewire
