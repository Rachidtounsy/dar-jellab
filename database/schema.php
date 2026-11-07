<?php
/** Instructions CREATE TABLE compatibles MySQL (production) et SQLite (tests locaux). */
function schema_sql(string $driver): array {
    $mysql = $driver === 'mysql';
    $id  = $mysql ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $int = $mysql ? 'INT UNSIGNED' : 'INTEGER';
    $eng = $mysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';
    return [
"CREATE TABLE IF NOT EXISTS settings (k VARCHAR(80) PRIMARY KEY, v TEXT)$eng",
"CREATE TABLE IF NOT EXISTS admins (id $id, email VARCHAR(190) NOT NULL UNIQUE, pass VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)$eng",
"CREATE TABLE IF NOT EXISTS categories (id $id, segment VARCHAR(12) NOT NULL, slug VARCHAR(60) NOT NULL, name_ar VARCHAR(120) NOT NULL, name_fr VARCHAR(120) NOT NULL DEFAULT '',
  accessory TINYINT NOT NULL DEFAULT 0, sort $int NOT NULL DEFAULT 0, active TINYINT NOT NULL DEFAULT 1, UNIQUE (segment, slug))$eng",
"CREATE TABLE IF NOT EXISTS terms (id $id, type VARCHAR(20) NOT NULL, slug VARCHAR(60) NOT NULL, name_ar VARCHAR(120) NOT NULL, name_fr VARCHAR(120) NOT NULL DEFAULT '',
  hex VARCHAR(9) NULL, sort $int NOT NULL DEFAULT 0, active TINYINT NOT NULL DEFAULT 1, UNIQUE (type, slug))$eng",
"CREATE TABLE IF NOT EXISTS products (id $id, slug VARCHAR(160) NOT NULL UNIQUE, segment VARCHAR(12) NOT NULL, category_id $int NOT NULL,
  name_ar VARCHAR(190) NOT NULL, name_fr VARCHAR(190) NOT NULL DEFAULT '', desc_ar TEXT, desc_fr TEXT,
  price $int NOT NULL DEFAULT 0, old_price $int NULL, fabric_id $int NULL, stitch_id $int NULL,
  made_to_measure TINYINT NOT NULL DEFAULT 0, badge VARCHAR(20) NULL, featured TINYINT NOT NULL DEFAULT 0, active TINYINT NOT NULL DEFAULT 1,
  views $int NOT NULL DEFAULT 0, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)$eng",
"CREATE INDEX idx_products_listing ON products (active, segment, category_id)",
"CREATE TABLE IF NOT EXISTS product_terms (product_id $int NOT NULL, term_id $int NOT NULL, PRIMARY KEY (product_id, term_id))$eng",
"CREATE TABLE IF NOT EXISTS variants (id $id, product_id $int NOT NULL, color_id $int NULL, size VARCHAR(12) NOT NULL DEFAULT '', stock $int NOT NULL DEFAULT 0)$eng",
"CREATE INDEX idx_variants_product ON variants (product_id)",
"CREATE TABLE IF NOT EXISTS images (id $id, product_id $int NOT NULL, path VARCHAR(190) NOT NULL, color_id $int NULL, sort $int NOT NULL DEFAULT 0)$eng",
"CREATE INDEX idx_images_product ON images (product_id)",
    ];
}
function schema_apply(PDO $pdo, string $driver): void {
    foreach (schema_sql($driver) as $sql) {
        try { $pdo->exec($sql); } catch (PDOException $e) {
            if (stripos($sql, 'CREATE INDEX') === 0) continue; // index déjà présent
            throw $e;
        }
    }
}
