# Database schema (goldenz_hr)

Schema lives in `schema/goldenz_hr.sql`. It is applied automatically when the MySQL container is first created (empty volume), via `mysql-init.sql` in the project root.

## If the database already existed (empty) before adding the schema

Run the schema once manually:

```bash
# From project root (goldenz_hr_system)
docker exec -i hr_db mysql -uroot -pSuomynona027 goldenz_hr < src/database/schema/goldenz_hr.sql
```

Or from inside the web container:

```bash
docker exec -it hr_web bash
mysql -h db -uroot -pSuomynona027 goldenz_hr < /var/www/html/database/schema/goldenz_hr.sql
```

## Default login

After the schema is applied, you can log in with:

- **Username:** `admin`
- **Password:** `password`

Change this password after first login.
