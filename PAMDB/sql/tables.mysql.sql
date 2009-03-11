-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 26. Februar 2009 um 15:32
-- Server Version: 5.0.45
-- PHP-Version: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `pam`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam`
--

CREATE TABLE IF NOT EXISTS `pam` (
  `id` bigint(20) NOT NULL auto_increment,
  `pam_identifier` text collate latin1_general_ci NOT NULL,
  `cluster` text collate latin1_general_ci,
  `pam_no` text collate latin1_general_ci,
  `name_pam` text collate latin1_general_ci,
  `objective_of_measure` text collate latin1_general_ci,
  `description_pam` text collate latin1_general_ci,
  `start` text collate latin1_general_ci,
  `ende` text collate latin1_general_ci,
  `red_2005_val` text collate latin1_general_ci,
  `red_2005_text` text collate latin1_general_ci,
  `red_2010_val` text collate latin1_general_ci,
  `red_2010_text` text collate latin1_general_ci,
  `red_2015_val` text collate latin1_general_ci,
  `red_2015_text` text collate latin1_general_ci,
  `red_2020_val` text collate latin1_general_ci,
  `red_2020_text` text collate latin1_general_ci,
  `cumulative_2008_2012` text collate latin1_general_ci,
  `explanation_basis_of_mitigation_estimates` text collate latin1_general_ci,
  `factors_resulting_in_emission_reduction` text collate latin1_general_ci,
  `include_common_reduction` text collate latin1_general_ci,
  `documention_source` text collate latin1_general_ci,
  `indicator_monitor_implementation` text collate latin1_general_ci,
  `general_comment` text collate latin1_general_ci,
  `reference` text collate latin1_general_ci,
  `description_impact_on_non_ghg` text collate latin1_general_ci,
  `costs_per_tonne` text collate latin1_general_ci,
  `costs_per_year` text collate latin1_general_ci,
  `costs_description` text collate latin1_general_ci,
  `costs_documention_source` text collate latin1_general_ci,
  `remarks` text collate latin1_general_ci,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `any_word` (`name_pam`,`objective_of_measure`,`description_pam`,`explanation_basis_of_mitigation_estimates`,`factors_resulting_in_emission_reduction`,`documention_source`,`indicator_monitor_implementation`,`general_comment`,`reference`,`description_impact_on_non_ghg`,`costs_description`,`costs_documention_source`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1321 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_category`
--

CREATE TABLE IF NOT EXISTS `pam_category` (
  `id` bigint(20) NOT NULL,
  `id_category` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_ghg`
--

CREATE TABLE IF NOT EXISTS `pam_ghg` (
  `id` bigint(20) NOT NULL,
  `id_ghg` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_implementing_entity`
--

CREATE TABLE IF NOT EXISTS `pam_implementing_entity` (
  `id` bigint(20) NOT NULL,
  `id_implementing_entity` bigint(20) NOT NULL,
  `specification` text character set latin1 collate latin1_german1_ci
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_keywords`
--

CREATE TABLE IF NOT EXISTS `pam_keywords` (
  `id` bigint(20) NOT NULL,
  `id_keywords` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_member_state`
--

CREATE TABLE IF NOT EXISTS `pam_member_state` (
  `id` bigint(20) NOT NULL,
  `id_member_state` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_reduces_non_ghg`
--

CREATE TABLE IF NOT EXISTS `pam_reduces_non_ghg` (
  `id` bigint(20) NOT NULL,
  `id_reduces_non_ghg` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_related_ccpm`
--

CREATE TABLE IF NOT EXISTS `pam_related_ccpm` (
  `id` bigint(20) NOT NULL,
  `id_related_ccpm` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_sector`
--

CREATE TABLE IF NOT EXISTS `pam_sector` (
  `id` bigint(20) NOT NULL,
  `id_sector` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_status`
--

CREATE TABLE IF NOT EXISTS `pam_status` (
  `id` bigint(20) NOT NULL,
  `id_status` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_type`
--

CREATE TABLE IF NOT EXISTS `pam_type` (
  `id` bigint(20) NOT NULL,
  `id_type` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pam_with_or_with_additional_measure`
--

CREATE TABLE IF NOT EXISTS `pam_with_or_with_additional_measure` (
  `id` bigint(20) NOT NULL,
  `id_with_or_with_additional_measure` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_category`
--

CREATE TABLE IF NOT EXISTS `val_category` (
  `id_category` bigint(20) NOT NULL auto_increment,
  `category` text collate latin1_general_ci NOT NULL,
  `id_sector` bigint(20) NOT NULL,
  PRIMARY KEY  (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_ghg`
--

CREATE TABLE IF NOT EXISTS `val_ghg` (
  `id_ghg` bigint(20) NOT NULL auto_increment,
  `ghg` text collate latin1_general_ci NOT NULL,
  `ghg_output` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_ghg`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_implementing_entity`
--

CREATE TABLE IF NOT EXISTS `val_implementing_entity` (
  `id_implementing_entity` bigint(20) NOT NULL auto_increment,
  `implementing_entity` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_implementing_entity`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_keywords`
--

CREATE TABLE IF NOT EXISTS `val_keywords` (
  `id_keywords` bigint(20) NOT NULL auto_increment,
  `keywords` text collate latin1_general_ci NOT NULL,
  `id_sector` bigint(20) default NULL,
  PRIMARY KEY  (`id_keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_member_state`
--

CREATE TABLE IF NOT EXISTS `val_member_state` (
  `id_member_state` bigint(20) NOT NULL auto_increment,
  `member_state` text collate latin1_general_ci NOT NULL,
  `eu_10` binary(1) default NULL,
  `eu_15` binary(1) default NULL,
  `ms` text collate latin1_general_ci,
  PRIMARY KEY  (`id_member_state`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_reduces_non_ghg`
--

CREATE TABLE IF NOT EXISTS `val_reduces_non_ghg` (
  `id_reduces_non_ghg` bigint(20) NOT NULL auto_increment,
  `reduces_non_ghg` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_reduces_non_ghg`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_related_ccpm`
--

CREATE TABLE IF NOT EXISTS `val_related_ccpm` (
  `id_related_ccpm` bigint(20) NOT NULL auto_increment,
  `related_ccpm` text collate latin1_general_ci NOT NULL,
  `id_sector` bigint(20) default NULL,
  PRIMARY KEY  (`id_related_ccpm`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_sector`
--

CREATE TABLE IF NOT EXISTS `val_sector` (
  `id_sector` bigint(20) NOT NULL auto_increment,
  `sector` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_sector`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_status`
--

CREATE TABLE IF NOT EXISTS `val_status` (
  `id_status` bigint(20) NOT NULL auto_increment,
  `status` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_type`
--

CREATE TABLE IF NOT EXISTS `val_type` (
  `id_type` bigint(20) NOT NULL auto_increment,
  `type` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `val_with_or_with_additional_measure`
--

CREATE TABLE IF NOT EXISTS `val_with_or_with_additional_measure` (
  `id_with_or_with_additional_measure` bigint(20) NOT NULL auto_increment,
  `with_or_with_additional_measure` text collate latin1_general_ci NOT NULL,
  `with_or_with_additional_measure_output` longtext collate latin1_general_ci,
  PRIMARY KEY  (`id_with_or_with_additional_measure`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;
