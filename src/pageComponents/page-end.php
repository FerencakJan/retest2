<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"

        crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script src="/build/js/scripts.js?v=<?php echo $portal->getAssetsVersion(); ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpTp1IVCeLoaIGRqEB_py4FvdBeZcL2so&libraries=places&callback=gooogleMapsInit" async defer></script>
<?php //if(!$portal->getMobileDetect()->isMobile()){ ?>
<!--  <script data-cookiecategory="analytics" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51d9325511ad6d91"></script>-->
<?php //} ?>

	</body>
</html>

