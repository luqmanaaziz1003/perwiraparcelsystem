-- Template for seed data. Copy to seed.local.sql and fill in real values.
--   seed.local.sql is gitignored. This file is committed — keep it fake.
--
-- Do not put real IC numbers or real passwords in THIS file.

INSERT INTO "receiver" ("ICNo", "username", "phone_number", "password") VALUES
  ('000000000000', 'Test Receiver', '0100000000', 'REPLACE_WITH_HASH');

INSERT INTO "staff" ("staffID", "username", "password") VALUES
  ('S001', 'Test Staff', 'REPLACE_WITH_HASH');

INSERT INTO "parcel" ("trackingNumber", "ICNo", "date_received", "time", "name", "weight", "deliveryLocation", "size", "status") VALUES
  ('TEST00000001', '000000000000', '2025-01-01', '09:00:00', 'Test Receiver', 1.00, 'PPC', 'M', 'pending');

-- Generate a hash to paste above with:
--   php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
