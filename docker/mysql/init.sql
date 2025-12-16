-- スキマパーク データベース初期化スクリプト
-- Docker起動時に自動実行されます

-- =============================================
-- 1. 会員テーブル (members)
-- =============================================
CREATE TABLE IF NOT EXISTS members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'メールアドレス',
    password VARCHAR(255) NOT NULL COMMENT 'パスワード',
    tel VARCHAR(64) NOT NULL COMMENT '電話番号',
    birth DATE NOT NULL COMMENT '生年月日',
    show_birth TINYINT(1) NOT NULL DEFAULT 0 COMMENT '生年月日公開設定',
    gender TINYINT NOT NULL COMMENT '性別: 0:男性, 1:女性, 2:その他',
    show_gender TINYINT(1) NOT NULL DEFAULT 0 COMMENT '性別公開設定',
    identity VARCHAR(1024) NOT NULL COMMENT '本人確認書類パス',
    username VARCHAR(128) NOT NULL COMMENT 'ユーザ名',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員テーブル';

-- =============================================
-- 2. 土地テーブル (lands)
-- =============================================
CREATE TABLE IF NOT EXISTS lands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prefectures TINYINT NOT NULL COMMENT '都道府県: 0:北海道～',
    city VARCHAR(256) NOT NULL COMMENT '市区町村',
    street_address VARCHAR(256) NOT NULL COMMENT '番地',
    area DECIMAL(5,2) NOT NULL COMMENT '面積',
    user_id BIGINT UNSIGNED NOT NULL COMMENT '所有者ID',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='土地テーブル';

-- =============================================
-- 3. 貸出記録テーブル (rental_records)
-- =============================================
CREATE TABLE IF NOT EXISTS rental_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    price INT NOT NULL COMMENT '単価',
    price_unit TINYINT NOT NULL COMMENT '単価単位: 0:日, 1:時間, 2:15分',
    rental_start_date DATE NOT NULL COMMENT '貸出開始日',
    rental_end_date DATE NOT NULL COMMENT '貸出終了日',
    rental_start_time TIME NOT NULL COMMENT '貸出開始時間',
    rental_end_time TIME NOT NULL COMMENT '貸出終了時間',
    land_id BIGINT UNSIGNED NOT NULL COMMENT '土地ID',
    user_id BIGINT UNSIGNED NOT NULL COMMENT '借り手ID',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (land_id) REFERENCES lands(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='貸出記録テーブル';

-- =============================================
-- 4. レビュー・コメントテーブル (review_comments)
-- =============================================
CREATE TABLE IF NOT EXISTS review_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    land_review TINYINT NOT NULL COMMENT '土地レビュー: 星1~5',
    land_comment VARCHAR(512) NULL COMMENT '土地コメント',
    user_review TINYINT NOT NULL COMMENT 'ユーザレビュー: 星1~5',
    user_comment VARCHAR(512) NULL COMMENT 'ユーザコメント',
    date DATE NOT NULL COMMENT '日付',
    user_id BIGINT UNSIGNED NOT NULL COMMENT '投稿者ID',
    land_id BIGINT UNSIGNED NOT NULL COMMENT '土地ID',
    record_id BIGINT UNSIGNED NOT NULL COMMENT '貸出記録ID',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (land_id) REFERENCES lands(id) ON DELETE CASCADE,
    FOREIGN KEY (record_id) REFERENCES rental_records(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='レビューテーブル';

-- =============================================
-- 5. 問い合わせテーブル (contacts)
-- =============================================
CREATE TABLE IF NOT EXISTS contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) NOT NULL COMMENT '主題',
    message VARCHAR(1024) NOT NULL COMMENT '本文',
    user_id BIGINT UNSIGNED NOT NULL COMMENT '送信者ID',
    date DATE NOT NULL COMMENT '日付',
    status TINYINT NOT NULL DEFAULT 0 COMMENT 'ステータス: 0:未対応, 1:対応中, 2:対応済み',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='問い合わせテーブル';

-- =============================================
-- 6. 返信テーブル (replies)
-- =============================================
CREATE TABLE IF NOT EXISTS replies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL COMMENT '問い合わせID',
    user_id BIGINT UNSIGNED NOT NULL COMMENT '送信者ID',
    message VARCHAR(1024) NOT NULL COMMENT 'メッセージ',
    date DATE NOT NULL COMMENT '日付',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='返信テーブル';

-- =============================================
-- 7. チャットテーブル (chats)
-- =============================================
CREATE TABLE IF NOT EXISTS chats (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id_from BIGINT UNSIGNED NOT NULL COMMENT '送信者ID',
    user_id_to BIGINT UNSIGNED NOT NULL COMMENT '受信者ID',
    message VARCHAR(512) NOT NULL COMMENT 'メッセージ',
    image VARCHAR(2048) NULL COMMENT '画像URL',
    sent_date DATE NOT NULL COMMENT '送信日付',
    sent_time TIME NOT NULL COMMENT '送信時間',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id_from) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id_to) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='チャットテーブル';

-- =============================================
-- Laravel用テーブル
-- =============================================
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
