INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES
('currency.view', 'web', NOW(), NOW()),
('currency.create', 'web', NOW(), NOW()),
('currency.edit', 'web', NOW(), NOW()),
('currency.delete', 'web', NOW(), NOW());

INSERT IGNORE INTO role_has_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.name = 'superadmin'
AND p.name IN ('currency.view', 'currency.create', 'currency.edit', 'currency.delete');
