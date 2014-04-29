CREATE TABLE tt_content (
	tx_dp_macinabanners_geotarget tinyint(4) NOT NULL default '0',
	tx_dp_macinabanners_show_untargeted_banners tinyint(4) NOT NULL default '0'
);

CREATE TABLE tx_macinabanners_banners (
	tx_dp_macinabanners_geotargets varchar(255) DEFAULT '' NOT NULL
);