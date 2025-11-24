<!DOCTYPE html>
<!--[if lt IE 8 ]><html lang="cs" class="no-js no-columns oldie ie7 no-svg no-transform no-boxshadow no-css-animations"><![endif]-->
<!--[if IE 8 ]><html lang="cs" class="no-js no-columns oldie no-svg no-transform no-boxshadow no-css-animations"><![endif]-->
<!--[if IE 9 ]><html lang="cs" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="cs" class="no-js"><!--<![endif]-->
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta content="noindex,nofollow" name="robots">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="title" content="<?php if(isSet($data['html']['title'])) { echo strip_tags($data['html']['title']); } ?><?php if(isSet($data['getData']['html']['title'])) { echo strip_tags($data['getData']['html']['title']); } ?>">
    <meta name="description" content="<?php if(isSet($data['html']['description'])) { echo strip_tags($data['html']['description']); } ?><?php if(isSet($data['getData']['html']['description'])) { echo strip_tags($data['getData']['html']['description']); } ?>">
    <meta name="keywords" content="<?php if(isSet($data['html']['keywords'])) { echo $data['html']['keywords']; } ?><?php if(isSet($data['getData']['html']['keywords'])) { echo $data['getData']['html']['keywords']; } ?>">
    <meta property="og:title" content="<?php if(isSet($data['html']['og:title'])) { echo strip_tags($data['html']['og:title']); } ?><?php if(isSet($data['getData']['html']['og:title'])) { echo strip_tags($data['getData']['html']['og:title']); } ?>" />
    <meta property="og:description" content="<?php if(isSet($data['html']['og:description'])) { echo strip_tags($data['html']['og:description']); } ?><?php if(isSet($data['getData']['html']['og:description'])) { echo strip_tags($data['getData']['html']['og:description']); } ?>" />
    <meta property="og:image" content="<?php if(isSet($data['html']['og:image'])) { echo $data['html']['og:image']; } ?><?php if(isSet($data['getData']['html']['og:image'])) { echo $data['getData']['html']['og:image']; } ?>" />

		<link rel="stylesheet" href="/build/css/styles.css?v=<?php echo $portal->getAssetsVersion(); ?>" type="text/css">
    <link rel="stylesheet" href="/build/css/stylesCorrected.css?v=<?php echo $portal->getAssetsVersion(); ?>" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
    <script type="text/javascript">var language = '<?php echo strtolower($portal->getLanguage()); ?>';</script>
    <title id="pageTitle"><?php if (isSet($data['getData']['html']['title'])){
        echo $data['getData']['html']['title'];
      } elseif ((isSet($data['html']['title']))) {
        echo $data['html']['title'];
        } else {
        echo 'Eurobydleni.cz';
      }; ?></title>
    <script>
      (function() {
        // už máme GEO cookie? -> nič nerob
        if (document.cookie.includes('geo_lat=')) return;
        if (!('geolocation' in navigator)) return;

        // rešpektuj banner: očakávame cookie "location_allowed=true"
        var hasConsent = document.cookie.split(';').map(c => c.trim()).some(c => c === 'location_allowed=true');
        if (!hasConsent) return;

        navigator.geolocation.getCurrentPosition(function(pos) {
          var lat  = pos.coords.latitude.toFixed(6);
          var lng  = pos.coords.longitude.toFixed(6);
          var prec = pos.coords.accuracy || '';
          var ts   = Date.now();

          function setCookie(n, v, days) {
            var d = new Date();
            d.setTime(d.getTime() + days*24*60*60*1000);
            document.cookie = n + "=" + encodeURIComponent(v) + "; expires=" + d.toUTCString() + "; path=/; SameSite=Lax";
          }

          setCookie('geo_lat', lat, 30);
          setCookie('geo_lng', lng, 30);
          setCookie('geo_precision', prec, 30);
          setCookie('geo_ts', ts, 30);
        }, function(err){
          console.warn('geolocation denied', err);
        }, { enableHighAccuracy:true, timeout:5000, maximumAge:600000 });
      })();
    </script>


  </head>

	<body>
