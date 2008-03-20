-- View: stat_country_dams_valid
-- DROP VIEW stat_country_dams_valid;
CREATE OR REPLACE VIEW stat_country_dams_valid AS 
 SELECT dams.country AS country_code, count(dams.country) AS dams_count, dams.valid
   FROM dams
  GROUP BY dams.country, dams.valid
  ORDER BY dams.country;

ALTER TABLE stat_country_dams_valid OWNER TO postgres;
COMMENT ON VIEW stat_country_dams_valid IS 'Show statistics about dams/country/valid';

-- View: stat_country_dams_valid_user
-- DROP VIEW stat_country_dams_valid_user;
CREATE OR REPLACE VIEW stat_country_dams_valid_user AS 
 SELECT b.country AS country_code, count(b.country) AS count, b.valid, a.cd_user
   FROM user_dams a
   JOIN dams b ON a.cd_dam::text = b.noeea::text
  GROUP BY b.country, b.valid, a.cd_user
  ORDER BY b.country;

ALTER TABLE stat_country_dams_valid_user OWNER TO postgres;
COMMENT ON VIEW stat_country_dams_valid_user IS 'Statistics with each validated/invalidated dams per country - for regular users';

-- View: user_dams_assigned
-- DROP VIEW user_dams_assigned;
CREATE OR REPLACE VIEW user_dams_assigned AS 
 SELECT a.cd_user, a.cd_dam, b.name, b.valid, b.country
   FROM user_dams a
   JOIN dams b ON a.cd_dam::text = b.noeea::text;
ALTER TABLE user_dams_assigned OWNER TO postgres;
