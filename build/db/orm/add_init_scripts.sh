#!bin/bash

export $(cat .env | xargs)

scripts_dir="build/db/orm/init-scripts"

mkdir -p $scripts_dir

cat > $scripts_dir/01-init-databases.sql <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME}_test;
GRANT ALL PRIVILEGES ON ${DB_NAME}_test.* TO '${DB_USER}_test'@'%';
FLUSH PRIVILEGES;

echo "database ini-scripts created!"