<?php
/**
 * ga-script.php
 *
 * The html for the GA script tag.
 *
 * @since 1.1.1
 */
?>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', '<?php echo esc_attr( $this->trackingId ); ?>', 'auto');
    ga('send', 'pageview');

</script>