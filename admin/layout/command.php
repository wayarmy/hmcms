<?php
hm_admin_js('command/js/jquery.mousewheel-min.js');
hm_admin_js('command/js/jquery.terminal-min.js');
hm_admin_css('command/css/jquery.terminal.css');
?>
<script>
jQuery(document).ready(function($) {
    $('#command-box').terminal("?run=command_ajax.php", {
			login: false,
			greetings: "<?php echo hm_lang('use_the_help_command_to_view_supported_commands'); ?>"});
});

</script>

<div id="command-box" class="terminal"></div>