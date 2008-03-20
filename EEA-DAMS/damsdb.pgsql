--
-- PostgreSQL database dump
--

SET client_encoding = 'SQL_ASCII';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


SET search_path = public, pg_catalog;

--
-- Name: plpgsql_call_handler(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION plpgsql_call_handler() RETURNS language_handler
    AS '$libdir/plpgsql', 'plpgsql_call_handler'
    LANGUAGE c;


ALTER FUNCTION public.plpgsql_call_handler() OWNER TO postgres;

--
-- Name: plpgsql_validator(oid); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION plpgsql_validator(oid) RETURNS void
    AS '$libdir/plpgsql', 'plpgsql_validator'
    LANGUAGE c;


ALTER FUNCTION public.plpgsql_validator(oid) OWNER TO postgres;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: public; Owner: 
--

CREATE TRUSTED PROCEDURAL LANGUAGE plpgsql HANDLER plpgsql_call_handler VALIDATOR plpgsql_validator;


--
-- Name: addgeometrycolumn(character varying, character varying, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, character varying, character varying, integer, character varying, integer) RETURNS text
    AS $_$
DECLARE
	catalog_name alias for $1;
	schema_name alias for $2;
	table_name alias for $3;
	column_name alias for $4;
	new_srid alias for $5;
	new_type alias for $6;
	new_dim alias for $7;

	rec RECORD;
	schema_ok bool;
	real_schema name;

	fixgeomres text;

BEGIN

	IF ( not ( (new_type ='GEOMETRY') or
		   (new_type ='GEOMETRYCOLLECTION') or
		   (new_type ='POINT') or 
		   (new_type ='MULTIPOINT') or
		   (new_type ='POLYGON') or
		   (new_type ='MULTIPOLYGON') or
		   (new_type ='LINESTRING') or
		   (new_type ='MULTILINESTRING')) )
	THEN
		RAISE EXCEPTION 'Invalid type name - valid ones are: 
			GEOMETRY, GEOMETRYCOLLECTION, POINT, 
			MULTIPOINT, POLYGON, MULTIPOLYGON, 
			LINESTRING, or MULTILINESTRING ';
		return 'fail';
	END IF;

	IF ( (new_dim >3) or (new_dim <0) ) THEN
		RAISE EXCEPTION 'invalid dimension';
		return 'fail';
	END IF;


	IF ( schema_name != '' ) THEN
		schema_ok = 'f';
		FOR rec IN SELECT nspname FROM pg_namespace WHERE text(nspname) = schema_name LOOP
			schema_ok := 't';
		END LOOP;

		if ( schema_ok <> 't' ) THEN
			RAISE NOTICE 'Invalid schema name - using current_schema()';
			SELECT current_schema() into real_schema;
		ELSE
			real_schema = schema_name;
		END IF;

	ELSE
		SELECT current_schema() into real_schema;
	END IF;



	-- Add geometry column

	EXECUTE 'ALTER TABLE ' ||

		quote_ident(real_schema) || '.' || quote_ident(table_name)



		|| ' ADD COLUMN ' || quote_ident(column_name) || 
		' geometry ';


	-- Delete stale record in geometry_column (if any)

	EXECUTE 'DELETE FROM geometry_columns WHERE
		f_table_catalog = ' || quote_literal('') || 
		' AND f_table_schema = ' ||

		quote_literal(real_schema) || 



		' AND f_table_name = ' || quote_literal(table_name) ||
		' AND f_geometry_column = ' || quote_literal(column_name);


	-- Add record in geometry_column 

	EXECUTE 'INSERT INTO geometry_columns VALUES (' ||
		quote_literal('') || ',' ||

		quote_literal(real_schema) || ',' ||



		quote_literal(table_name) || ',' ||
		quote_literal(column_name) || ',' ||
		new_dim || ',' || new_srid || ',' ||
		quote_literal(new_type) || ')';

	-- Add table checks

	EXECUTE 'ALTER TABLE ' || 

		quote_ident(real_schema) || '.' || quote_ident(table_name)



		|| ' ADD CONSTRAINT "enforce_srid_' || 
		column_name || '" CHECK (SRID(' || quote_ident(column_name) ||
		') = ' || new_srid || ')' ;

	IF (not(new_type = 'GEOMETRY')) THEN
		EXECUTE 'ALTER TABLE ' || 

		quote_ident(real_schema) || '.' || quote_ident(table_name)



		|| ' ADD CONSTRAINT "enforce_geotype_' ||
		column_name || '" CHECK (geometrytype(' ||
		quote_ident(column_name) || ')=' ||
		quote_literal(new_type) || ' OR (' ||
		quote_ident(column_name) || ') is null)';
	END IF;

	SELECT fix_geometry_columns() INTO fixgeomres;

	return 

		real_schema || '.' || 

		table_name || '.' || column_name ||
		' SRID:' || new_srid ||
		' TYPE:' || new_type || '
 ' ||
		'geometry_column ' || fixgeomres;
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.addgeometrycolumn(character varying, character varying, character varying, character varying, integer, character varying, integer) OWNER TO postgres;

--
-- Name: addgeometrycolumn(character varying, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, character varying, integer, character varying, integer) RETURNS text
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT AddGeometryColumn('',$1,$2,$3,$4,$5,$6) into ret;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.addgeometrycolumn(character varying, character varying, character varying, integer, character varying, integer) OWNER TO postgres;

--
-- Name: addgeometrycolumn(character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, integer, character varying, integer) RETURNS text
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT AddGeometryColumn('','',$1,$2,$3,$4,$5) into ret;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.addgeometrycolumn(character varying, character varying, integer, character varying, integer) OWNER TO postgres;

--
-- Name: dropgeometrycolumn(character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying, character varying, character varying) RETURNS text
    AS $_$
DECLARE
	catalog_name alias for $1; 
	schema_name alias for $2;
	table_name alias for $3;
	column_name alias for $4;
	myrec RECORD;
	okay boolean;
	real_schema name;

BEGIN



	-- Find, check or fix schema_name
	IF ( schema_name != '' ) THEN
		okay = 'f';

		FOR myrec IN SELECT nspname FROM pg_namespace WHERE text(nspname) = schema_name LOOP
			okay := 't';
		END LOOP;

		IF ( okay <> 't' ) THEN
			RAISE NOTICE 'Invalid schema name - using current_schema()';
			SELECT current_schema() into real_schema;
		ELSE
			real_schema = schema_name;
		END IF;
	ELSE
		SELECT current_schema() into real_schema;
	END IF;




 	-- Find out if the column is in the geometry_columns table
	okay = 'f';
	FOR myrec IN SELECT * from geometry_columns where f_table_schema = text(real_schema) and f_table_name = table_name and f_geometry_column = column_name LOOP
		okay := 't';
	END LOOP; 
	IF (okay <> 't') THEN 
		RAISE EXCEPTION 'column not found in geometry_columns table';
		RETURN 'f';
	END IF;

	-- Remove ref from geometry_columns table
	EXECUTE 'delete from geometry_columns where f_table_schema = ' ||
		quote_literal(real_schema) || ' and f_table_name = ' ||
		quote_literal(table_name)  || ' and f_geometry_column = ' ||
		quote_literal(column_name);
	
	-- Remove table column
	EXECUTE 'ALTER TABLE ' || quote_ident(real_schema) || '.' ||
		quote_ident(table_name) || ' DROP COLUMN ' ||
		quote_ident(column_name);



	RETURN real_schema || '.' || table_name || '.' || column_name ||' effectively removed.';
	
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.dropgeometrycolumn(character varying, character varying, character varying, character varying) OWNER TO postgres;

--
-- Name: dropgeometrycolumn(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying, character varying) RETURNS text
    AS $_$
DECLARE
	ret text;
BEGIN
	SELECT DropGeometryColumn('',$1,$2,$3) into ret;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.dropgeometrycolumn(character varying, character varying, character varying) OWNER TO postgres;

--
-- Name: dropgeometrycolumn(character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying) RETURNS text
    AS $_$
DECLARE
	ret text;
BEGIN
	SELECT DropGeometryColumn('','',$1,$2) into ret;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.dropgeometrycolumn(character varying, character varying) OWNER TO postgres;

--
-- Name: dropgeometrytable(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrytable(character varying, character varying, character varying) RETURNS text
    AS $_$
DECLARE
	catalog_name alias for $1; 
	schema_name alias for $2;
	table_name alias for $3;
	real_schema name;

BEGIN


	IF ( schema_name = '' ) THEN
		SELECT current_schema() into real_schema;
	ELSE
		real_schema = schema_name;
	END IF;


	-- Remove refs from geometry_columns table
	EXECUTE 'DELETE FROM geometry_columns WHERE ' ||

		'f_table_schema = ' || quote_literal(real_schema) ||
		' AND ' ||

		' f_table_name = ' || quote_literal(table_name);
	
	-- Remove table 
	EXECUTE 'DROP TABLE '

		|| quote_ident(real_schema) || '.' ||

		quote_ident(table_name);

	RETURN

		real_schema || '.' ||

		table_name ||' dropped.';
	
END;
$_$
    LANGUAGE plpgsql STRICT;


ALTER FUNCTION public.dropgeometrytable(character varying, character varying, character varying) OWNER TO postgres;

--
-- Name: dropgeometrytable(character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrytable(character varying, character varying) RETURNS text
    AS $_$SELECT DropGeometryTable('',$1,$2)$_$
    LANGUAGE sql STRICT;


ALTER FUNCTION public.dropgeometrytable(character varying, character varying) OWNER TO postgres;

--
-- Name: dropgeometrytable(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dropgeometrytable(character varying) RETURNS text
    AS $_$SELECT DropGeometryTable('','',$1)$_$
    LANGUAGE sql STRICT;


ALTER FUNCTION public.dropgeometrytable(character varying) OWNER TO postgres;

--
-- Name: find_srid(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION find_srid(character varying, character varying, character varying) RETURNS integer
    AS $_$DECLARE
   schem text;
   tabl text;
   sr int4;
BEGIN
   IF $1 IS NULL THEN
      RAISE EXCEPTION 'find_srid() - schema is NULL!';
   END IF;
   IF $2 IS NULL THEN
      RAISE EXCEPTION 'find_srid() - table name is NULL!';
   END IF;
   IF $3 IS NULL THEN
      RAISE EXCEPTION 'find_srid() - column name is NULL!';
   END IF;
   schem = $1;
   tabl = $2;
-- if the table contains a . and the schema is empty
-- split the table into a schema and a table
-- otherwise drop through to default behavior
   IF ( schem = '' and tabl LIKE '%.%' ) THEN
     schem = substr(tabl,1,strpos(tabl,'.')-1);
     tabl = substr(tabl,length(schem)+2);
   ELSE
     schem = schem || '%';
   END IF;

   select SRID into sr from geometry_columns where f_table_schema like schem and f_table_name = tabl and f_geometry_column = $3;
   IF NOT FOUND THEN
       RAISE EXCEPTION 'find_srid() - couldnt find the corresponding SRID - is the geometry registered in the GEOMETRY_COLUMNS table?  Is there an uppercase/lowercase missmatch?';
   END IF;
  return sr;
END;
$_$
    LANGUAGE plpgsql IMMUTABLE;


ALTER FUNCTION public.find_srid(character varying, character varying, character varying) OWNER TO postgres;

--
-- Name: fix_geometry_columns(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION fix_geometry_columns() RETURNS text
    AS $_$
DECLARE
	mislinked record;
	result text;
	linked integer;
	deleted integer;

	foundschema integer;

BEGIN


	-- Since 7.3 schema support has been added.
	-- Previous postgis versions used to put the database name in
	-- the schema column. This needs to be fixed, so we try to 
	-- set the correct schema for each geometry_colums record
	-- looking at table, column, type and srid.
	UPDATE geometry_columns SET f_table_schema = n.nspname
		FROM pg_namespace n, pg_class c, pg_attribute a,
			pg_constraint sridcheck, pg_constraint typecheck
                WHERE ( f_table_schema is NULL
		OR f_table_schema = ''
                OR f_table_schema NOT IN (
                        SELECT nspname::varchar
                        FROM pg_namespace nn, pg_class cc, pg_attribute aa
                        WHERE cc.relnamespace = nn.oid
                        AND cc.relname = f_table_name::name
                        AND aa.attrelid = cc.oid
                        AND aa.attname = f_geometry_column::name))
                AND f_table_name::name = c.relname
                AND c.oid = a.attrelid
                AND c.relnamespace = n.oid
                AND f_geometry_column::name = a.attname
                AND sridcheck.conrelid = c.oid
                --AND sridcheck.conname = '$1'
		AND sridcheck.consrc LIKE '(srid(% = %)'
                AND typecheck.conrelid = c.oid
                --AND typecheck.conname = '$2'
		AND typecheck.consrc LIKE
	'((geometrytype(%) = ''%''::text) OR (% IS NULL))'
                AND sridcheck.consrc ~ textcat(' = ', srid::text)
                AND typecheck.consrc ~ textcat(' = ''', type::text)
                AND NOT EXISTS (
                        SELECT oid FROM geometry_columns gc
                        WHERE c.relname::varchar = gc.f_table_name

                        AND n.nspname::varchar = gc.f_table_schema

                        AND a.attname::varchar = gc.f_geometry_column
                );

	GET DIAGNOSTICS foundschema = ROW_COUNT;



	-- no linkage to system table needed
	return 'fixed:'||foundschema::text;


	-- fix linking to system tables
	SELECT 0 INTO linked;
	FOR mislinked in
		SELECT gc.oid as gcrec,
			a.attrelid as attrelid, a.attnum as attnum
                FROM geometry_columns gc, pg_class c,

		pg_namespace n, pg_attribute a



                WHERE ( gc.attrelid IS NULL OR gc.attrelid != a.attrelid 
			OR gc.varattnum IS NULL OR gc.varattnum != a.attnum)

                AND n.nspname = gc.f_table_schema::name
                AND c.relnamespace = n.oid

                AND c.relname = gc.f_table_name::name
                AND a.attname = f_geometry_column::name
                AND a.attrelid = c.oid
	LOOP
		UPDATE geometry_columns SET
			attrelid = mislinked.attrelid,
			varattnum = mislinked.attnum,
			stats = NULL
			WHERE geometry_columns.oid = mislinked.gcrec;
		SELECT linked+1 INTO linked;
	END LOOP; 


	-- remove stale records
	DELETE FROM geometry_columns WHERE attrelid IS NULL;

	GET DIAGNOSTICS deleted = ROW_COUNT;

	result = 

		'fixed:' || foundschema::text ||

		' linked:' || linked::text || 
		' deleted:' || deleted::text;

	return result;

END;
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION public.fix_geometry_columns() OWNER TO postgres;

--
-- Name: get_proj4_from_srid(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_proj4_from_srid(integer) RETURNS text
    AS $_$SELECT proj4text::text FROM spatial_ref_sys WHERE srid= $1$_$
    LANGUAGE sql IMMUTABLE STRICT;


ALTER FUNCTION public.get_proj4_from_srid(integer) OWNER TO postgres;

--
-- Name: pg_file_length(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION pg_file_length(text) RETURNS bigint
    AS $_$SELECT len FROM pg_file_stat($1) AS s(len int8, c timestamp, a timestamp, m timestamp, i bool)$_$
    LANGUAGE sql STRICT;


ALTER FUNCTION public.pg_file_length(text) OWNER TO postgres;

--
-- Name: pg_file_rename(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION pg_file_rename(text, text) RETURNS boolean
    AS $_$SELECT pg_file_rename($1, $2, NULL); $_$
    LANGUAGE sql STRICT;


ALTER FUNCTION public.pg_file_rename(text, text) OWNER TO postgres;

--
-- Name: postgis_full_version(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION postgis_full_version() RETURNS text
    AS $$
DECLARE
	libver text;
	projver text;
	geosver text;
	usestats bool;
	dbproc text;
	relproc text;
	fullver text;
BEGIN
	SELECT postgis_lib_version() INTO libver;
	SELECT postgis_proj_version() INTO projver;
	SELECT postgis_geos_version() INTO geosver;
	SELECT postgis_uses_stats() INTO usestats;
	SELECT postgis_scripts_installed() INTO dbproc;
	SELECT postgis_scripts_released() INTO relproc;

	fullver = 'POSTGIS="' || libver || '"';

	IF  geosver IS NOT NULL THEN
		fullver = fullver || ' GEOS="' || geosver || '"';
	END IF;

	IF  projver IS NOT NULL THEN
		fullver = fullver || ' PROJ="' || projver || '"';
	END IF;

	IF usestats THEN
		fullver = fullver || ' USE_STATS';
	END IF;

	fullver = fullver || ' DBPROC="' || dbproc || '"';
	fullver = fullver || ' RELPROC="' || relproc || '"';

	IF dbproc != relproc THEN
		fullver = fullver || ' (needs proc upgrade)';
	END IF;

	RETURN fullver;
END
$$
    LANGUAGE plpgsql;


ALTER FUNCTION public.postgis_full_version() OWNER TO postgres;

--
-- Name: postgis_scripts_installed(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION postgis_scripts_installed() RETURNS text
    AS $$SELECT '0.0.1'::text AS version$$
    LANGUAGE sql;


ALTER FUNCTION public.postgis_scripts_installed() OWNER TO postgres;

--
-- Name: postgis_version(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION postgis_version() RETURNS text
    AS $$SELECT '0.9 USE_GEOS=1 USE_PROJ=1 USE_STATS=1'::text AS version$$
    LANGUAGE sql;


ALTER FUNCTION public.postgis_version() OWNER TO postgres;

--
-- Name: probe_geometry_columns(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION probe_geometry_columns() RETURNS text
    AS $_$
DECLARE
	inserted integer;
	oldcount integer;
	probed integer;
	stale integer;
BEGIN

	SELECT count(*) INTO oldcount FROM geometry_columns;

	SELECT count(*) INTO probed
		FROM pg_class c, pg_attribute a, pg_type t, 

			pg_namespace n,

			pg_constraint sridcheck, pg_constraint typecheck
		WHERE t.typname = 'geometry'
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid

		AND c.relnamespace = n.oid
		AND sridcheck.connamespace = n.oid
		AND typecheck.connamespace = n.oid

		AND sridcheck.conrelid = c.oid
		--AND sridcheck.conname = '$1'
		AND sridcheck.consrc LIKE '(srid(% = %)'
		AND typecheck.conrelid = c.oid
		--AND typecheck.conname = '$2';
		AND typecheck.consrc LIKE
	'((geometrytype(%) = ''%''::text) OR (% IS NULL))'
		;

	INSERT INTO geometry_columns SELECT
		''::varchar as f_table_catalogue,

		n.nspname::varchar as f_table_schema,



		c.relname::varchar as f_table_name,
		a.attname::varchar as f_geometry_column,
		2 as coord_dimension,
		trim(both  ' =)' from substr(sridcheck.consrc,
			strpos(sridcheck.consrc, '=')))::integer as srid,
		trim(both ' =)''' from substr(typecheck.consrc, 
			strpos(typecheck.consrc, '='),
			strpos(typecheck.consrc, '::')-
			strpos(typecheck.consrc, '=')
			))::varchar as type





		FROM pg_class c, pg_attribute a, pg_type t, 

			pg_namespace n,

			pg_constraint sridcheck, pg_constraint typecheck
		WHERE t.typname = 'geometry'
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid

		AND c.relnamespace = n.oid
		AND sridcheck.connamespace = n.oid
		AND typecheck.connamespace = n.oid

		AND sridcheck.conrelid = c.oid
		--AND sridcheck.conname = '$1'
		AND sridcheck.consrc LIKE '(srid(% = %)'
		AND typecheck.conrelid = c.oid
		--AND typecheck.conname = '$2'
		AND typecheck.consrc LIKE
	'((geometrytype(%) = ''%''::text) OR (% IS NULL))'

                AND NOT EXISTS (
                        SELECT oid FROM geometry_columns gc
                        WHERE c.relname::varchar = gc.f_table_name

                        AND n.nspname::varchar = gc.f_table_schema

                        AND a.attname::varchar = gc.f_geometry_column
                );

	GET DIAGNOSTICS inserted = ROW_COUNT;

	IF oldcount > probed THEN
		stale = oldcount-probed;
	ELSE
		stale = 0;
	END IF;

        RETURN 'probed:'||probed||
		' inserted:'||inserted||
		' conflicts:'||probed-inserted||
		' stale:'||stale;
END

$_$
    LANGUAGE plpgsql;


ALTER FUNCTION public.probe_geometry_columns() OWNER TO postgres;

--
-- Name: rename_geometry_table_constraints(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION rename_geometry_table_constraints() RETURNS text
    AS $$
UPDATE pg_constraint 
	SET conname = textcat('enforce_geotype_', a.attname)
	FROM pg_attribute a
	WHERE
		a.attrelid = conrelid
		AND a.attnum = conkey[1]
		AND consrc LIKE '((geometrytype(%) = %';

UPDATE pg_constraint
	SET conname = textcat('enforce_srid_', a.attname)
	FROM pg_attribute a
	WHERE
		a.attrelid = conrelid
		AND a.attnum = conkey[1]
		AND consrc LIKE '(srid(% = %)';

SELECT 'spatial table constraints renamed'::text;

$$
    LANGUAGE sql;


ALTER FUNCTION public.rename_geometry_table_constraints() OWNER TO postgres;

--
-- Name: update_geometry_stats(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_geometry_stats() RETURNS text
    AS $$ SELECT 'update_geometry_stats() has been obsoleted. Statistics are automatically built running the ANALYZE command'::text$$
    LANGUAGE sql;


ALTER FUNCTION public.update_geometry_stats() OWNER TO postgres;

--
-- Name: update_geometry_stats(character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_geometry_stats(character varying, character varying) RETURNS text
    AS $$SELECT update_geometry_stats();$$
    LANGUAGE sql;


ALTER FUNCTION public.update_geometry_stats(character varying, character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: country; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE country (
    code character varying(2) NOT NULL,
    name character varying(254),
    "type" character varying(3),
    lang character varying(120)
);


ALTER TABLE public.country OWNER TO postgres;

--
-- Name: TABLE country; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE country IS 'EU25 countries';


--
-- Name: COLUMN country.code; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN country.code IS 'ISO 2 digits code';


--
-- Name: COLUMN country."type"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN country."type" IS 'MS : Member States';


SET default_with_oids = true;

--
-- Name: dams; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE dams (
    noeea character varying(8) NOT NULL,
    name character varying(254),
    x_icold double precision,
    y_icold double precision,
    score smallint,
    x_prop double precision,
    y_prop double precision,
    x_val double precision,
    y_val double precision,
    area double precision,
    cap_total double precision,
    ic_city character varying(254),
    country character varying(2),
    lake_name character varying(254),
    river_id character varying(254),
    river_name character varying(254),
    year_opp character varying(20),
    year_dead character varying(20),
    comments text,
    "valid" boolean,
    ic_continent character varying(254),
    is_main boolean,
    noeea_m character varying(8),
    is_icold boolean,
    alias character varying(254),
    ic_year character varying(4),
    ic_state character varying(254),
    ic_high double precision,
    ic_high_guessed boolean,
    ic_length double precision,
    ic_length_guessed boolean,
    ic_vol double precision,
    ic_purpose character varying(254),
    ic_owner character varying(254),
    ic_note text,
    ic_particular character varying(254),
    ic_international character varying(254),
    ic_sealing character varying(254),
    ic_foundation character varying(254),
    ic_capacity double precision,
    ic_area double precision,
    ic_spill double precision,
    ic_type_spill character varying(254),
    ic_engineer character varying(254),
    ic_contractor character varying(254),
    ic_p_mw character varying(254),
    ic_e_whpyear character varying(254),
    ic_irrigation character varying(254),
    ic_floodstock character varying(254),
    ic_settlement character varying(254),
    is_oncanal boolean DEFAULT false,
    is_dyke boolean DEFAULT false
);


ALTER TABLE public.dams OWNER TO postgres;

--
-- Name: COLUMN dams.noeea; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.noeea IS 'NOEEA is dam identifyer';


--
-- Name: COLUMN dams.name; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.name IS 'Name of the dam';


--
-- Name: COLUMN dams.x_icold; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.x_icold IS 'Longitude in decimal degrees from ICOLD';


--
-- Name: COLUMN dams.y_icold; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.y_icold IS 'Latitude in decimal degrees from ICOLD';


--
-- Name: COLUMN dams.x_prop; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.x_prop IS 'Longitude in decimal degrees proposed';


--
-- Name: COLUMN dams.y_prop; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.y_prop IS 'Latitude in decimal degrees proposed';


--
-- Name: COLUMN dams.x_val; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.x_val IS 'Longitude in decimal degrees validated by users';


--
-- Name: COLUMN dams.y_val; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.y_val IS 'Latitude in decimal degrees validated by users';


--
-- Name: COLUMN dams.country; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.country IS 'is country ISO (first 2 chars of NOEEA)';


--
-- Name: COLUMN dams.river_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.river_id IS 'River_ID is pointer to river name and characteristics';


--
-- Name: COLUMN dams.year_opp; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.year_opp IS 'Year of first commissionning';


--
-- Name: COLUMN dams.year_dead; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.year_dead IS 'Year of decommissioning or blank';


--
-- Name: COLUMN dams.ic_continent; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_continent IS 'CONTINENT (for dams in overseas possessions)';


--
-- Name: COLUMN dams.is_main; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.is_main IS 'TRUE if is main dam (NOEEA_M is blank)';


--
-- Name: COLUMN dams.noeea_m; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.noeea_m IS 'NOEEA_M is Identifier of main dam';


--
-- Name: COLUMN dams.is_icold; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.is_icold IS 'TRUE if dams comes from Icold Register';


--
-- Name: COLUMN dams.alias; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.alias IS 'Alias for dam name';


--
-- Name: COLUMN dams.ic_year; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_year IS 'Year of last data update';


--
-- Name: COLUMN dams.ic_high; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_high IS 'Dam heigh in m';


--
-- Name: COLUMN dams.ic_high_guessed; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_high_guessed IS 'True if ic_high is not original';


--
-- Name: COLUMN dams.ic_length; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_length IS 'Dam length in m';


--
-- Name: COLUMN dams.ic_length_guessed; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_length_guessed IS 'TRUE if ic_length is not original';


--
-- Name: COLUMN dams.ic_vol; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_vol IS 'Dam wall vol in 1000m3';


--
-- Name: COLUMN dams.ic_area; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_area IS '1000m2';


--
-- Name: COLUMN dams.ic_spill; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_spill IS 'in m3 /s';


--
-- Name: COLUMN dams.ic_irrigation; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_irrigation IS 'km2';


--
-- Name: COLUMN dams.ic_floodstock; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN dams.ic_floodstock IS 'hm3';


--
-- Name: i18n; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE i18n (
    id text NOT NULL,
    page_id character varying(100),
    en text,
    fr text,
    it text,
    et text,
    da text,
    cz text,
    de text,
    pl text,
    es text,
    el text,
    lv text,
    cs text,
    lt text,
    hu text,
    mt text,
    nl text,
    pt text,
    sk text,
    sl text,
    fi text,
    sv text,
    bg text,
    "no" text,
    ro text,
    tr text,
    ss text
);


ALTER TABLE public.i18n OWNER TO postgres;

--
-- Name: langs_avail; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE langs_avail (
    id character varying(10) NOT NULL,
    name character varying(200),
    meta text,
    error_text character varying(250),
    "encoding" character varying(16) DEFAULT 'iso-8859-1'::character varying NOT NULL
);


ALTER TABLE public.langs_avail OWNER TO postgres;

--
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    user_id bigint NOT NULL,
    dam_id character varying NOT NULL,
    log_type bigint NOT NULL,
    date date NOT NULL,
    "comment" character varying(254)
);


ALTER TABLE public.log OWNER TO postgres;

--
-- Name: TABLE log; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE log IS 'Application Log';


--
-- Name: COLUMN log.log_type; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.log_type IS 'Type of log action define in logtype table';


--
-- Name: metadata; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE metadata (
    code character varying(5) NOT NULL,
    value character varying(15)
);


ALTER TABLE public.metadata OWNER TO postgres;

SET default_with_oids = false;

--
-- Name: user_dams; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE user_dams (
    cd_user bigint NOT NULL,
    cd_dam character varying(8) NOT NULL
);


ALTER TABLE public.user_dams OWNER TO postgres;

--
-- Name: TABLE user_dams; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE user_dams IS 'List all dams link to a user';


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer DEFAULT nextval('public.users_uid_seq'::text) NOT NULL,
    firstname character varying(120),
    surname character varying(120),
    login character varying(20) NOT NULL,
    "password" character varying(120) NOT NULL,
    email character varying(254),
    roleadm boolean DEFAULT false,
    rolelang boolean DEFAULT true,
    roledam boolean DEFAULT false,
    address text,
    phone character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: TABLE users; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE users IS 'Users of the application';


--
-- Data for Name: country; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO country (code, name, "type", lang) VALUES ('AT', 'Austria', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('BE', 'Belgium', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('CY', 'Cyprus', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('CZ', 'Czech Republic', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('DK', 'Denmark', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('EE', 'Estonia', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('FI', 'Finland', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('FR', 'France', 'MS', 'French');
INSERT INTO country (code, name, "type", lang) VALUES ('DE', 'Germany', 'MS', 'German');
INSERT INTO country (code, name, "type", lang) VALUES ('GR', 'Greece', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('HU', 'Hungary', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('IE', 'Ireland', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('IT', 'Italy', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('LV', 'Latvia', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('LT', 'Lithuania', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('LU', 'Luxembourg', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('MT', 'Malta', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('PL', 'Poland', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('PT', 'Portugal', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('SK', 'Slovakia', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('SI', 'Slovenia', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('SP', 'Spain', 'MS', 'Spanish');
INSERT INTO country (code, name, "type", lang) VALUES ('SE', 'Sweden', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('NE', 'Netherlands', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('GB', 'United Kingdom', 'MS', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('BG', 'Bulgaria', 'AC', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('HR', 'Croatia', 'AC', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('RO', 'Romania', 'AC', 'English');
INSERT INTO country (code, name, "type", lang) VALUES ('TR', 'Turkey', 'AC', 'English');


--
-- Data for Name: dams; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO dams (noeea, name, x_icold, y_icold, score, x_prop, y_prop, x_val, y_val, area, cap_total, ic_city, country, lake_name, river_id, river_name, year_opp, year_dead, comments, "valid", ic_continent, is_main, noeea_m, is_icold, alias, ic_year, ic_state, ic_high, ic_high_guessed, ic_length, ic_length_guessed, ic_vol, ic_purpose, ic_owner, ic_note, ic_particular, ic_international, ic_sealing, ic_foundation, ic_capacity, ic_area, ic_spill, ic_type_spill, ic_engineer, ic_contractor, ic_p_mw, ic_e_whpyear, ic_irrigation, ic_floodstock, ic_settlement, is_oncanal, is_dyke) VALUES ('DFR00259', 'LANGLERET', 1.598889, 45.796390000000002, 1, 1.59756660461426, 45.796853031836399, 1.59756660461426, 45.796853031836399, 175, 1700, 'Limoges', 'FR', NULL, '10335', 'Maulde', '1962', '-32768', 'Commune Bujaleuf', true, 'EUROPE', true, NULL, true, '', '1962', 'Vienne (Haute)', 18, false, 100, false, 7, 'H', 'Edf Production Centre', NULL, 'U', NULL, NULL, 'R', 1150, 175, 375, 'L/V', 'Edf Equipement', 'Stribick', NULL, NULL, NULL, NULL, NULL, false, false);


--
-- Data for Name: i18n; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s1', 'dams', 'Coordinates ok (S1)', 'Coordinates ok (S1)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s2', 'dams', 'Adjust slightly (S2)', 'Adjust slightly (S2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s3', 'dams', 'Move place (S3)', 'Move place (S3)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s4', 'dams', 'Not identified (S4)', 'Not identified (S4)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s5', 'dams', 'Not verified (S5)', 'Not verified (S5)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s6', 'dams', 'Unsuited photo (S6)', 'Unsuited photo (S6)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('scoreinfo', 'dams', 'dam(s) with score ', 'dam(s) with score ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('topographic', 'dam', 'Topographic', 'Carte topo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('topoinfo', 'dam', 'Topographic map is a large scale and is provided from member states. It could provide you more details than the Google map above', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('im20002info', 'dam', 'Data used : Image 2000, CCM, ... / Scale : 100000 ?', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('floodstock', 'dam', 'Floodstock', 'Floodstock', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('yearopp', 'dam', 'Year operational', 'Date de mise en service', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('yeardead', 'dam', 'Year dead', 'Date de fin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('main', 'dam', 'Main', 'Principal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('fromICOLD', 'dam', 'From ICOLD', 'Origine ICOLD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('damMap', 'home', 'Make dams map for a country:', 'Voir la carte des barrages pour un pays :', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('inchargeof', 'all', 'You are in charge of', 'Vous gérez actuellement ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('helptext', 'all', 'Help text ...', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('yearic', 'all', 'Year of data', 'Année des données', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('home', 'all', 'Home', 'Accueil', 'Domestico', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Domů', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('profilManage', 'all', 'Manage your profile', 'Gérer votre compte', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Upravit profil', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('code', 'all', 'Code', 'Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kód', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('name', 'all', 'Name', 'Libellé', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jméno', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('country', 'all', 'Country', 'Pays', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Stát', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('applyfilter', 'all', 'Apply filter', 'Appliquer le filtre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Použít filtr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('addFilter', 'all', 'Add filter', 'Ajouter un filtre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přidat filtr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('statistics', 'all', 'Statistics', 'Statistiques', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Statistika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('welcome', 'all', 'Welcome', 'Bienvennue', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vítejte', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('loginin', 'all', 'Login', 'Identification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přihlásit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('logout', 'all', 'Logout', 'Déconnexion', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Odhlásit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('userAdm', 'all', 'User administration', 'Gestion des utilisateurs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Správa uživatelů', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('translationManage', 'all', 'Translation manager', 'Gestion des traductions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Řízení jazykových verzí', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('profile', 'user', 'Profile', 'Informations personnelles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profil', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('address', 'user', 'Address', 'Adresse', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Adresa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('adduser', 'user', 'Add user', 'Ajouter un utilisateur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přidat uživatele', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('alias', 'dam', 'Alias ', 'Alias ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alias', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('all', 'insert', 'Insert', 'Insérer', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vložit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('area', 'dam', 'Area (1000m2)', 'Superficie (1000m2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Plocha (v 1000m2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('availableDams', 'user', 'Available dams', 'Barrages disponibles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dostupné přehradní nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('characteristics', 'dam', 'Characteristics', 'Caractéristiques', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Popis', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('city', 'dam', 'City', 'Ville', NULL, NULL, NULL, NULL, NULL, NULL, 'Ciudad', NULL, NULL, 'Město', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('confirm', 'user', 'Confirm', 'Confirmez', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Potvrdit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('continent', 'dam', 'Continent', 'Continent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kontinent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('comments', 'dam', 'Comments', 'Commentaires', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Poznámka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('coordinates', 'dam', 'Coordinates (WGS84, decimal degrees)', 'Coordonnées (WGS84,  degrés décimaux)', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Souřadnice (WGS84, decimal degrees)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('password', 'all', 'Password', 'Mot de passe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Heslo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('lake', 'dam', 'Lake', 'Lac', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jezero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('firstname', 'user', 'Firstname', 'Prénom', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Křestní jméno', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('hydrocode', 'dam', 'Hydrographic code', 'Code hydrographique', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hydrografický kód', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('hydroname', 'dam', 'River', 'Cours d\\''eau ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Řeka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('delete', 'all', 'Delete', 'Supprimer', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vymazat', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('dams', 'all', 'Dams', 'Barrages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přehradní nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('im20001', 'dam', 'Image2000(1)', 'Image2000(1)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Image2000(1)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('im20002', 'dam', 'Image2000(2)', 'Image2000(2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Image2000(2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('capacity', 'dam', 'Capacity (in 1000 m3)', 'Capacité (en m3 /s)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Objem nádrže (v 1000 m3)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('volume', 'dam', 'Volume (in 1000m3)', 'Volume (en 1000m3)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Objem hmoty hráze (v 1000 m3)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('length', 'dam', 'Length', 'Longueur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Délka hráze', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('height', 'dam', 'Height', 'Hauteur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Výška hráze', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('engineer', 'dam', 'Engineer', 'Ingénieur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nádrž projektoval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('is_dyke', 'all', 'Dam is dyke', 'Barrage = digue', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dam is dyke', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('is_oncanal', 'all', 'On canal', 'Sur canal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nádrž postavena na umělém kanále', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('localisation', 'dam', 'Localisation', 'Positionnement', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lokalizace', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('metadata', 'dam', 'Metadata', 'Métadonnées', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Metadata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('insert', 'all', 'Insert', 'Insérer', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vložit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('irrigation', 'dam', 'Irrigation', 'Irrigation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Zavlažování', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('list', 'user', 'All users', 'Tous les utilisateurs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Všichni uživatelé', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('notauthenticated', 'all', 'Wrong identification', 'Identification incorrecte', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chybná identifikace', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('phone', 'user', 'Phone', 'Tél', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Telefon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('rolelang', 'user', 'Language administrator', 'Gestion des traductions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admnistrátor jazykových verzí', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('saveupdatecomments', 'dam', 'Update comment', 'Mettre à jour le commentaire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktualizovat poznámku', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('saveuserprofile', 'user', 'save user profile', 'Sauver le profile utilisateur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Uložit uživatelský profil', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('update', 'all', 'Update', 'Mettre à jour', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktualizovat', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('updatecomments', 'dam', 'Update comment', 'Mettre à jour le commentaire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktualizovat poznámku', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('saveSelectedDams', 'user', 'Save selected dams', 'Enregistrer les barrages sélectionnés', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Uložit vybrané nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('s0', 'dams', 'Suppress filter (S0)', 'Suppress filter (S0)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Potlačit filtr (S0)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('score', 'dam', 'Score', 'Classement', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Skóre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('note', 'dam', 'Note', 'Note', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Poznámka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('selectedDams', 'user', 'Selected Dams', 'Barrages sélectionnés', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vybrané nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('surname', 'user', 'Surname', 'Surname', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Příjmení', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('username', 'all', 'Login', 'Nom', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přihlašovací jméno', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('mail', 'user', 'Mail', 'Mél', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'E-mail', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('roles', 'user', 'Roles', 'Rôles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Role', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('roleadm', 'user', 'Administrator', 'Administrateur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Administrátor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('roledam', 'user', 'Dam validator', 'Validation des barrages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ověřování pozice nádrží', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('action', 'user', 'Action', 'Action', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Akce', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('addnewterm', 'all', 'Add new term', 'Ajouter un terme', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Přidat nový pojem', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('title', 'all', 'Data service - DAMS', 'Service - Barrages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Datové služby - Přehradní nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('clickinfo', 'dam', 'Click on the map to set the new position', 'Cliquer sur la carte pour définir la nouvelle position validée', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kliknutím na mapu zadáte novou polohu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('eeaposition', 'all', 'EEA position proposed', 'Position proposée par EEA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Poloha doporučená EEA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('damValidation', 'all', 'Dam Validation', 'Validation des barrages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ověřit polohu nádrže', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('icoldposition', 'all', 'ICOLD position', 'Position ICOLD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Poloha podle ICOLD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('eeaistrue', 'dam', 'Set EEA position as valid position', 'Définir la position EEA comme valide', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nastavit polohu doporučenou EEA jako platnou', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('icoldistrue', 'dam', 'Set ICOLD position as valid position', 'Définir la position ICOLD comme valide', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nastavit polohu podle ICOLD jako platnou', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('im20001info', 'dam', 'Data used : Image 2000, CCM, ... / Scale : 50000 ?', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Použitá data: Image 2000, CCM, ... / Měřítko: 50000 ?', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('help', 'all', 'Help', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Help', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('valposition', 'all', 'Validated position', 'Position validée', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ověřená poloha', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('saveandvalid', 'dam', 'Save & Validate position', 'Enregistrer la position validée', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Uložit a ověřit polohu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('settlement', 'dam', 'Settlement', 'Implantation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sídlo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('owner', 'dam', 'Owner', 'Owner', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vlastník', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('purpose', 'dam', 'Purpose', 'Objectifs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Účel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('contractor', 'dam', 'Contractor', 'Contractant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nádrž postavil', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('legend', 'all', 'Caption', 'Légende', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vysvětlivky', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO i18n (id, page_id, en, fr, it, et, da, cz, de, pl, es, el, lv, cs, lt, hu, mt, nl, pt, sk, sl, fi, sv, bg, "no", ro, tr, ss) VALUES ('desc', 'home', 'This service helps finding the accurate position of any dam recorded in the EEA database that comes from a processing on the Icold "Register on large dams".
As registered user, you have been given a login and a password that enables you to edit the position of dams in your circumscription.<br/><br/>
When you are logged in, the number of dams you are in charge of appears in the Statistics box and the map of these dams is displayed. Then you can either: <br/>
	Click on a dam on the map to zoom on this dam<br/>
	Click on "dam validation" on the top menu line. In this case, the list of the dams you are in charge of is displayed along with their validation status<br/><br/>
The second case is the general way of correcting dam position. Select a dam by clicking on its name, and a window with the guessed position of dam is displayed. You can add administrative and / or CCM river on the map or replace the satellite image by the Image2000 image (I2K button) or display the dam over the cartographic layer of Europe (EEA button). <br/><br/>
Position the mouse cursor at the correct place of the dam, helping with the zoom (+/-= and navigation (arrows or dragging the background with mouse left button kept down). <br/><br/>
Once happy with the new position, validate it and if necessary, insert comments.
The captions can be displayed in any listed language after the ad hoc field has been populated with its translation. <br/>
', 'Ce service aide à trouver la position précise de n''importe quel barrage enregistré dans la base de données de l''AEE en provenance d''un traitement sur le "registre des grands barrages" de la CIGB. En tant qu''utilisateur enregistré, vous avez une compte et un mot de passe qui vous permettent d''éditer la position des barrages dans votre domaine d''autorisation. A votre entrée dans le service, le nombre de barrages qui vous ont été affectés apparaît dans la boîte de dialogue et la carte de ces barrages est affichée. A ce stade vous pouvez soit double-cliquer sur un barrage sur la carte, soit cliquer sur la barre"validation de barrage" sur la ligne supérieure du menu. Dans ce cas-ci, la liste des barrages que vous avez en charge est montrée avec leur statut de validation. Ce deuxième cas est la manière générale de corriger la position des barrages, le premier étant plutôt réservé à une entrée individuelle. Choisissez un barrage en cliquant sur son nom, et une fenêtre avec la position suggérée du barrage est montrée. Vous pouvez superposer des couches administratives et/ou CCM fleuve sur la carte ou remplacer l''image satellite par l''Image2000 (bouton I2K) ou montrer le barrage au-dessus de la couche cartographique de l''Europe (bouton EEA). Placez le curseur de souris à l''endroit correct du barrage, aidant avec le zoom (+/-= et la navigation (des flèches ou déplacer le fond avec le bouton gauche de souris maintenu enfonçé). Une fois la nouvelle position acceptée, validez-la et, au besoin, insérez les commentaires. Les légendes peuvent être affichée en n''importe laquelle des langues énumérées, pourvu que le champ ad hoc ait été renseigné avec sa traduction.', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: langs_avail; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('da', 'dansk (da)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('et', 'eesti keel (et)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('en', 'english (en)', 'charset: utf8', 'not available in English', 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('hu', 'magyar (hu)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('pl', 'polski (pl)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('fi', 'suomi (fi)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('sv', 'svenska (sv)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('mt', 'malti (mt)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('nl', 'nederlands (nl)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('de', 'deutsch (de)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('fr', 'français (fr)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('it', 'italiano (it)', NULL, NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('el', 'ελληνικά (el)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('es', 'español (es)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('cs', 'čeština (cs)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('lv', 'latviešu valoda (lv)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('lt', 'lietuvių kalba (lt)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('pt', 'português (pt)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('sk', 'slovenčina (sk)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('sl', 'slovenščina (sl)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('bg', 'Български (bg)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('no', 'Norsk (no)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('ro', 'Română (ro)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('tr', 'Türkçe (tr)', 'charset: utf8', NULL, 'utf8');
INSERT INTO langs_avail (id, name, meta, error_text, "encoding") VALUES ('ss', 'Íslenska (is)', 'charset: utf8', NULL, 'utf8');


--
-- Data for Name: log; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: metadata; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO metadata (code, value) VALUES ('lock', 'true');


--
-- Data for Name: user_dams; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO user_dams (cd_user, cd_dam) VALUES (1, 'DFR00259');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users (id, firstname, surname, login, "password", email, roleadm, rolelang, roledam, address, phone) VALUES (1, 'Dams', 'Administrator', 'dams', '4ee2cf2c13ce32ff35a20010fbba866e', 'dams@nowhere.eu', true, true, true, 'No address', '555-1212');


--
-- Name: PK_COUNTRY; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY country
    ADD CONSTRAINT "PK_COUNTRY" PRIMARY KEY (code);


ALTER INDEX public."PK_COUNTRY" OWNER TO postgres;

--
-- Name: PK_DAMS; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY dams
    ADD CONSTRAINT "PK_DAMS" PRIMARY KEY (noeea);


ALTER INDEX public."PK_DAMS" OWNER TO postgres;

--
-- Name: PK_USERS; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT "PK_USERS" PRIMARY KEY (id);


ALTER INDEX public."PK_USERS" OWNER TO postgres;

--
-- Name: i18n_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY i18n
    ADD CONSTRAINT i18n_pk PRIMARY KEY (id);


ALTER INDEX public.i18n_pk OWNER TO postgres;

--
-- Name: langs_avail_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY langs_avail
    ADD CONSTRAINT langs_avail_pk PRIMARY KEY (id);


ALTER INDEX public.langs_avail_pk OWNER TO postgres;

--
-- Name: pk_user_dams; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY user_dams
    ADD CONSTRAINT pk_user_dams PRIMARY KEY (cd_user, cd_dam);


ALTER INDEX public.pk_user_dams OWNER TO postgres;

--
-- Name: pk_users_login; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_users_login UNIQUE (login);


ALTER INDEX public.pk_users_login OWNER TO postgres;

--
-- Name: CNN_DAMS; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX "CNN_DAMS" ON dams USING btree (country, noeea, name);


ALTER INDEX public."CNN_DAMS" OWNER TO postgres;

--
-- Name: CT_DAMS; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX "CT_DAMS" ON dams USING btree (country);


ALTER INDEX public."CT_DAMS" OWNER TO postgres;

--
-- Name: FK_DAM; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_dams
    ADD CONSTRAINT "FK_DAM" FOREIGN KEY (cd_dam) REFERENCES dams(noeea) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: FR_USER; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_dams
    ADD CONSTRAINT "FR_USER" FOREIGN KEY (cd_user) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: log_dam_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_dam_fk FOREIGN KEY (dam_id) REFERENCES dams(noeea);


--
-- Name: log_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_user_fk FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: dams; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE dams FROM PUBLIC;
REVOKE ALL ON TABLE dams FROM postgres;
GRANT ALL ON TABLE dams TO postgres;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE dams TO crouzet;


--
-- Name: metadata; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE metadata FROM PUBLIC;
REVOKE ALL ON TABLE metadata FROM postgres;
GRANT ALL ON TABLE metadata TO postgres;
GRANT ALL ON TABLE metadata TO crouzet;


--
-- Name: user_dams; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE user_dams FROM PUBLIC;
REVOKE ALL ON TABLE user_dams FROM postgres;
GRANT ALL ON TABLE user_dams TO postgres;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE user_dams TO crouzet;


--
-- Name: users; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE users FROM PUBLIC;
REVOKE ALL ON TABLE users FROM postgres;
GRANT ALL ON TABLE users TO postgres;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE users TO crouzet;

-- Create views
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

--
-- PostgreSQL database dump complete
--

