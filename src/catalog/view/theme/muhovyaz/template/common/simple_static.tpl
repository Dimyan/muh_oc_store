    <!-- Simple v <?php echo Simple::$version ?> -->
    <script type="text/javascript">
    var simple_route = '<?php echo $simple->tpl_joomla_route(); ?>'; 
    var simple_path = '<?php echo $simple->tpl_joomla_path(); ?>'; 
    var simple_asap = <?php echo $simple->get_checkout_asap(); ?>; 
    var simple_googleapi = <?php echo $simple->check_googleapi_enabled(); ?>; 
    </script>
