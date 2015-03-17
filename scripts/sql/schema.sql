--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

DROP INDEX public.unique_ip;
DROP INDEX public.unique_email;
ALTER TABLE ONLY public."user" DROP CONSTRAINT user_pkey;
ALTER TABLE ONLY public.login_attempt DROP CONSTRAINT login_attempt_pkey;
ALTER TABLE public."user" ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.login_attempt ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE public.user_id_seq;
DROP TABLE public."user";
DROP SEQUENCE public.login_attempt_id_seq;
DROP TABLE public.login_attempt;
DROP EXTENSION plpgsql;
DROP SCHEMA public;
--
-- Name: postgres; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE postgres IS 'default administrative connection database';


--
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: login_attempt; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE login_attempt (
    id integer NOT NULL,
    attempt integer NOT NULL,
    ip inet NOT NULL,
    date timestamp without time zone NOT NULL,
    active boolean NOT NULL,
    "createdAt" timestamp without time zone,
    "updatedAt" timestamp without time zone
);


ALTER TABLE login_attempt OWNER TO postgres;

--
-- Name: login_attempt_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE login_attempt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE login_attempt_id_seq OWNER TO postgres;

--
-- Name: login_attempt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE login_attempt_id_seq OWNED BY login_attempt.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "user" (
    id integer NOT NULL,
    name character varying(100),
    roles character varying(100)[] NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    "lastLogin" timestamp without time zone,
    cookie character varying(100),
    "cookieIp" inet,
    active boolean NOT NULL,
    "createdAt" timestamp without time zone,
    "updatedAt" timestamp without time zone
);


ALTER TABLE "user" OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY login_attempt ALTER COLUMN id SET DEFAULT nextval('login_attempt_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- Name: login_attempt_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY login_attempt
    ADD CONSTRAINT login_attempt_pkey PRIMARY KEY (id);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: unique_email; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX unique_email ON "user" USING btree (email);


--
-- Name: unique_ip; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX unique_ip ON login_attempt USING btree (ip);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

