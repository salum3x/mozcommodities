CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "role" varchar check("role" in('admin', 'supplier', 'customer')) not null default 'customer',
  "phone" varchar,
  "address" text,
  latitude REAL,
  longitude REAL,
  "profile_photo" varchar
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "suppliers"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "company_name" varchar not null,
  "business_license" varchar,
  "description" text,
  "status" varchar check("status" in('pending', 'approved', 'rejected')) not null default 'pending',
  "created_at" datetime,
  "updated_at" datetime,
  "document_number" varchar,
  "whatsapp" varchar,
  "address" text,
  "latitude" varchar,
  "longitude" varchar,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "description" text,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "categories_slug_unique" on "categories"("slug");
CREATE TABLE IF NOT EXISTS "products"(
  "id" integer primary key autoincrement not null,
  "supplier_id" integer not null,
  "category_id" integer not null,
  "name" varchar not null,
  "slug" varchar not null,
  "description" text,
  "price_per_kg" numeric not null,
  "unit" varchar not null default 'kg',
  "image" varchar,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "stock_kg" integer not null default '0',
  "cost_price" numeric,
  "platform_margin" numeric not null default '0',
  "approval_status" varchar check("approval_status" in('pending', 'approved', 'rejected')) not null default 'pending',
  "rejection_reason" text,
  "is_company_product" tinyint(1) not null default '0',
  foreign key("supplier_id") references "suppliers"("id") on delete cascade,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE UNIQUE INDEX "products_slug_unique" on "products"("slug");
CREATE TABLE IF NOT EXISTS "stocks"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "quantity" numeric not null,
  "unit" varchar not null default 'kg',
  "expiry_date" date,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "quote_requests"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "phone" varchar not null,
  "company_name" varchar,
  "product_id" integer,
  "message" text not null,
  "quantity" numeric,
  "status" varchar check("status" in('pending', 'responded', 'closed')) not null default 'pending',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "announcements"(
  "id" integer primary key autoincrement not null,
  "message" text not null,
  "is_active" tinyint(1) not null default '1',
  "order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "orders"(
  "id" integer primary key autoincrement not null,
  "order_number" varchar not null,
  "user_id" integer,
  "customer_name" varchar not null,
  "customer_email" varchar not null,
  "customer_phone" varchar not null,
  "customer_address" text,
  "subtotal" numeric not null,
  "total" numeric not null,
  "payment_method" varchar check("payment_method" in('mpesa', 'bank_transfer')) not null default 'mpesa',
  "payment_status" varchar check("payment_status" in('pending', 'paid', 'failed')) not null default 'pending',
  "status" varchar check("status" in('pending', 'processing', 'completed', 'cancelled')) not null default 'pending',
  "payment_proof" text,
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete set null
);
CREATE UNIQUE INDEX "orders_order_number_unique" on "orders"("order_number");
CREATE TABLE IF NOT EXISTS "order_items"(
  "id" integer primary key autoincrement not null,
  "order_id" integer not null,
  "product_id" integer not null,
  "product_name" varchar not null,
  "price" numeric not null,
  "quantity" integer not null,
  "subtotal" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "settings_key_unique" on "settings"("key");
CREATE TABLE IF NOT EXISTS "cart_items"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "session_id" varchar,
  "product_id" integer not null,
  "quantity" integer not null default '1',
  "price_per_kg" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "cart_items_user_id_product_id_index" on "cart_items"(
  "user_id",
  "product_id"
);
CREATE INDEX "cart_items_session_id_index" on "cart_items"("session_id");
CREATE TABLE IF NOT EXISTS "product_requests"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "phone" varchar not null,
  "product_name" varchar not null,
  "description" text not null,
  "quantity_kg" integer,
  "status" varchar check("status" in('pending', 'processing', 'quoted', 'completed', 'cancelled')) not null default 'pending',
  "admin_notes" text,
  "created_at" datetime,
  "updated_at" datetime
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_11_05_111935_add_role_to_users_table',1);
INSERT INTO migrations VALUES(5,'2025_11_05_111939_create_suppliers_table',1);
INSERT INTO migrations VALUES(6,'2025_11_05_111942_create_categories_table',1);
INSERT INTO migrations VALUES(7,'2025_11_05_111942_create_products_table',1);
INSERT INTO migrations VALUES(8,'2025_11_05_111942_create_stocks_table',1);
INSERT INTO migrations VALUES(9,'2025_11_05_112100_create_quote_requests_table',1);
INSERT INTO migrations VALUES(10,'2025_11_05_195439_create_announcements_table',2);
INSERT INTO migrations VALUES(11,'2025_11_05_195853_create_orders_table',3);
INSERT INTO migrations VALUES(12,'2025_11_05_195854_create_order_items_table',3);
INSERT INTO migrations VALUES(13,'2025_11_05_201144_create_settings_table',4);
INSERT INTO migrations VALUES(14,'2025_11_05_202250_create_cart_items_table',5);
INSERT INTO migrations VALUES(15,'2025_11_05_203056_add_fields_to_products_and_suppliers_tables',6);
INSERT INTO migrations VALUES(16,'2025_11_05_205849_create_product_requests_table',7);
INSERT INTO migrations VALUES(17,'2025_11_05_214500_add_location_fields_to_users_table',8);
INSERT INTO migrations VALUES(18,'2025_12_29_202024_add_profile_photo_to_users_table',8);
INSERT INTO migrations VALUES(19,'2025_12_29_212827_add_about_page_settings',9);
