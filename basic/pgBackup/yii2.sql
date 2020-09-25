--
-- PostgreSQL database dump
--

-- Dumped from database version 11.5
-- Dumped by pg_dump version 11.5

-- Started on 2020-09-25 05:28:03

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 16384)
-- Name: adminpack; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS adminpack WITH SCHEMA pg_catalog;


--
-- TOC entry 2663 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION adminpack; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION adminpack IS 'administrative functions for PostgreSQL';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 202 (class 1259 OID 490769)
-- Name: bought_courses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bought_courses (
    id bigint NOT NULL,
    "userId" bigint,
    "courseId" bigint,
    "monthId" bigint
);


ALTER TABLE public.bought_courses OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 490780)
-- Name: bought_courses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.bought_courses ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.bought_courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 197 (class 1259 OID 490729)
-- Name: courses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    name character varying,
    "shortDescription" character varying,
    description text,
    "dateFrom" date,
    "dateTo" date,
    "teacherId" bigint,
    subject character varying,
    "examType" character varying,
    price double precision
);


ALTER TABLE public.courses OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 490778)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.courses ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 199 (class 1259 OID 490745)
-- Name: lessons; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lessons (
    id bigint NOT NULL,
    name character varying,
    "shortDescription" character varying,
    description text,
    video character varying,
    "lessonDate" timestamp without time zone,
    "homeworkDate" timestamp without time zone,
    "monthId" bigint,
    "courseId" bigint
);


ALTER TABLE public.lessons OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 490784)
-- Name: lessons_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.lessons ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.lessons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 198 (class 1259 OID 490737)
-- Name: months; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.months (
    id bigint NOT NULL,
    name character varying,
    "dateFrom" character varying,
    "dateTo" character varying,
    "courseId" bigint
);


ALTER TABLE public.months OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 490782)
-- Name: months_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.months ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.months_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 200 (class 1259 OID 490753)
-- Name: teachers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.teachers (
    id bigint NOT NULL,
    name character varying,
    subject character varying,
    description text,
    contact character varying
);


ALTER TABLE public.teachers OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 490776)
-- Name: teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.teachers ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 201 (class 1259 OID 490761)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    login character varying,
    name character varying,
    email character varying,
    vk character varying,
    description character varying,
    role character varying,
    "teacherId" bigint,
    "authKey" character varying,
    password character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 490774)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.users ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 2651 (class 0 OID 490769)
-- Dependencies: 202
-- Data for Name: bought_courses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bought_courses (id, "userId", "courseId", "monthId") FROM stdin;
2	1	3	1
\.


--
-- TOC entry 2646 (class 0 OID 490729)
-- Dependencies: 197
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.courses (id, name, "shortDescription", description, "dateFrom", "dateTo", "teacherId", subject, "examType", price) FROM stdin;
3	Супер курс	Его краткое описание	Мастер группа - это платный годовой курс онлайн-подготовки к ЕГЭ, преимуществами которой являются цена и удобство обучения	2020-09-01	2020-09-30	1	Матан	ОГЭ	2000
\.


--
-- TOC entry 2648 (class 0 OID 490745)
-- Dependencies: 199
-- Data for Name: lessons; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lessons (id, name, "shortDescription", description, video, "lessonDate", "homeworkDate", "monthId", "courseId") FROM stdin;
1	Урок 1. Тест №1	Д. И. Фонвизин "Недоросль"	&&	https://www.youtube.com/embed/tgbNymZ7vqY	2020-09-25 06:30:00	2020-09-27 11:55:00	1	3
\.


--
-- TOC entry 2647 (class 0 OID 490737)
-- Dependencies: 198
-- Data for Name: months; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.months (id, name, "dateFrom", "dateTo", "courseId") FROM stdin;
1	Сентябрь	2020-09-01	2020-09-30	3
\.


--
-- TOC entry 2649 (class 0 OID 490753)
-- Dependencies: 200
-- Data for Name: teachers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.teachers (id, name, subject, description, contact) FROM stdin;
1	Вася	Матан	Васян крутой математик	vk.com/id1
\.


--
-- TOC entry 2650 (class 0 OID 490761)
-- Dependencies: 201
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, name, email, vk, description, role, "teacherId", "authKey", password) FROM stdin;
1	Админ	Админ	screedwall@gmail.com	vk.com/id1	Ну что-то	Администратор	1	vQNuTZPB6WfglKIsI5z0HCI04AfkLea1	$2y$13$rCrbxaPyr0Ym4JMKa5w1QuH0xLERQw.PSrbjhqv.dCvDezzJ3bAW.
\.


--
-- TOC entry 2664 (class 0 OID 0)
-- Dependencies: 206
-- Name: bought_courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bought_courses_id_seq', 2, true);


--
-- TOC entry 2665 (class 0 OID 0)
-- Dependencies: 205
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.courses_id_seq', 3, true);


--
-- TOC entry 2666 (class 0 OID 0)
-- Dependencies: 208
-- Name: lessons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lessons_id_seq', 1, true);


--
-- TOC entry 2667 (class 0 OID 0)
-- Dependencies: 207
-- Name: months_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.months_id_seq', 1, true);


--
-- TOC entry 2668 (class 0 OID 0)
-- Dependencies: 204
-- Name: teachers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.teachers_id_seq', 1, true);


--
-- TOC entry 2669 (class 0 OID 0)
-- Dependencies: 203
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- TOC entry 2524 (class 2606 OID 490773)
-- Name: bought_courses bought_courses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bought_courses
    ADD CONSTRAINT bought_courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2514 (class 2606 OID 490736)
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2518 (class 2606 OID 490752)
-- Name: lessons lessons_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lessons
    ADD CONSTRAINT lessons_pkey PRIMARY KEY (id);


--
-- TOC entry 2516 (class 2606 OID 490744)
-- Name: months months_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.months
    ADD CONSTRAINT months_pkey PRIMARY KEY (id);


--
-- TOC entry 2520 (class 2606 OID 490760)
-- Name: teachers teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pkey PRIMARY KEY (id);


--
-- TOC entry 2522 (class 2606 OID 490768)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


-- Completed on 2020-09-25 05:28:03

--
-- PostgreSQL database dump complete
--

