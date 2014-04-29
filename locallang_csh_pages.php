<?php
/**
* Default  TCA_DESCR for my new field in the "pages" table
*/

$LOCAL_LANG = Array (
    'default' => Array (
        'tx_dp_macinabanners_geotarget.description' => 'enable geotargeting in this content element',
        'tx_dp_macinabanners_geotarget.details' => 'This checkbox enables this content element to show banners for the users specific point of origin',
		'tx_dp_macinabanners_show_untargeted_banners.description' => 'show untargeted elements',
        'tx_dp_macinabanners_show_untargeted_banners.details' => 'This checkbox enables this content element to show banners with no geotarget as well.',
		'tx_dp_macinabanners_geotargets.description' => 'choose target!',
        'tx_dp_macinabanners_geotargets.details' => 'pick some geotartets',
		
    ),
	'de' => Array (
		'tx_dp_macinabanners_geotarget.description' => 'aktiviere geotargeting',
        'tx_dp_macinabanners_geotarget.details' => 'diese checkbox macht geotargets',
		'tx_dp_macinabanners_show_untargeted_banners.description' => 'zeige elemente ohne Target',
        'tx_dp_macinabanners_show_untargeted_banners.details' => 'diese checkbox aktiviert auch banner ohne target f�r dieses element.',
		'tx_dp_macinabanners_geotargets.description' => 'W�hlen Sie ein Target!',
        'tx_dp_macinabanners_geotargets.details' => 'W�hlen Sie f�r den Banner ein Target aus der Liste:<br>
		z.B: AT f�r ganz �sterreich, oder AT:Wien und AT:Burgenland f�r diese beiden Bundesl�nder. Wenn Sie keine Einschr�nkung ausw�hlen, dann wird der Banner ohne Ber�cksichtigung der Geo-Targets angezeigt.',
	)
);
?>