-- Perwira Parcel System — PostgreSQL schema for Supabase
-- Converted from the phpMyAdmin MySQL dump (project_db).
--
-- Run this in the Supabase SQL Editor. Safe to re-run: it drops and recreates.
--
-- Identifiers are quoted to preserve the exact camelCase the PHP code uses
-- ("ICNo", "trackingNumber"). Postgres folds unquoted names to lowercase, which
-- would turn $row['ICNo'] into $row['icno'] and break the existing queries.

BEGIN;

DROP TABLE IF EXISTS "retrievedparcelrecord" CASCADE;
DROP TABLE IF EXISTS "parcel" CASCADE;
DROP TABLE IF EXISTS "receiver" CASCADE;
DROP TABLE IF EXISTS "staff" CASCADE;
DROP TYPE  IF EXISTS parcel_status CASCADE;

-- MySQL allowed an inline enum. Postgres needs a named type.
-- The original enum('pending','retrieved','','') had two empty values, which
-- were almost certainly accidental — dropped here.
CREATE TYPE parcel_status AS ENUM ('pending', 'retrieved');

CREATE TABLE "receiver" (
  "ICNo"         varchar(12) PRIMARY KEY,
  "username"     varchar(50) NOT NULL,
  -- Was int(11). Phone numbers are not integers: a leading 0 is silently lost,
  -- which is why the dump shows 108097703 instead of 0108097703.
  "phone_number" varchar(20) NOT NULL UNIQUE,
  -- Widened from varchar(30) to fit a bcrypt/argon2 hash. See the note below —
  -- the existing values are plaintext and must be rehashed.
  "password"     varchar(255) NOT NULL
);

CREATE TABLE "staff" (
  "staffID"  varchar(12) PRIMARY KEY,
  "username" varchar(50) NOT NULL,
  "password" varchar(255) NOT NULL
);

CREATE TABLE "parcel" (
  "trackingNumber"   varchar(50) PRIMARY KEY,
  "ICNo"             varchar(12) REFERENCES "receiver"("ICNo"),
  "date_received"    date,
  "time"             time,
  "name"             varchar(100),
  "weight"           numeric(10,2),
  "deliveryLocation" varchar(255),
  "size"             varchar(50),
  "status"           parcel_status NOT NULL
);

CREATE INDEX ON "parcel" ("ICNo");

CREATE TABLE "retrievedparcelrecord" (
  "ReceiveID"      varchar(50) PRIMARY KEY,
  "trackingNumber" varchar(50) REFERENCES "parcel"("trackingNumber"),
  "ICNo"           varchar(12) REFERENCES "receiver"("ICNo"),
  "staffID"        varchar(20) REFERENCES "staff"("staffID"),
  "receiveDate"    date,
  "status"         varchar(50),
  "receiveTime"    time
);

CREATE INDEX ON "retrievedparcelrecord" ("trackingNumber");
CREATE INDEX ON "retrievedparcelrecord" ("ICNo");
CREATE INDEX ON "retrievedparcelrecord" ("staffID");

-- Row Level Security -------------------------------------------------------
-- Supabase publishes every table in the `public` schema through its REST API.
-- Without RLS, anyone holding the anon key — which ships in client-side code —
-- can read these tables outright. RLS with no policy denies all API access.
-- Your PHP connects as the `postgres` role, which bypasses RLS, so the app
-- keeps working. Add policies deliberately if you later need API reads.

ALTER TABLE "receiver"              ENABLE ROW LEVEL SECURITY;
ALTER TABLE "staff"                 ENABLE ROW LEVEL SECURITY;
ALTER TABLE "parcel"                ENABLE ROW LEVEL SECURITY;
ALTER TABLE "retrievedparcelrecord" ENABLE ROW LEVEL SECURITY;

COMMIT;

-- Seed data ----------------------------------------------------------------
-- Deliberately NOT in this file. This repository is public, and the rows from
-- the original MySQL dump contain real IC numbers (which encode date of birth)
-- alongside plaintext passwords.
--
-- Keep seed rows in supabase/seed.local.sql, which .gitignore excludes.
-- See supabase/seed.example.sql for the shape.
