<script type="text/javascript">


$(window).load(function() {
map.addLayer(Ushahidi.GEOJSON, {
                name: "ships",
                url: '<?php echo url::base(); ?>aislayers/get_ship_json',
                transform: false
        }, true, true);

});
</script>