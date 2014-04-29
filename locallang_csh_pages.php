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
        'tx_dp_macinabanners_show_untargeted_banners.details' => 'diese checkbox aktiviert auch banner ohne target für dieses element.',
		'tx_dp_macinabanners_geotargets.description' => 'Wählen Sie ein Target!',
        'tx_dp_macinabanners_geotargets.details' => 'Wählen Sie für den Banner ein Target aus der Liste:<br>
		z.B: AT für ganz Österreich, oder AT:Wien und AT:Burgenland für diese beiden Bundesländer. Wenn Sie keine Einschränkung auswählen, dann wird der Banner ohne Berücksichtigung der Geo-Targets angezeigt.',
	)
);
?>