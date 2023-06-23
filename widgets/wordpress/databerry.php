<?php
/**
 * @package Roxie
 * @version 0.21
 * Plugin Name: Roxie
 * Plugin URI: http://wordpress.org/plugins/roxie/
 * Description: Roxie.ai is a Livechat plugin
 * Author: Roxie.ai
 * Version: 0.21
 * Author URI: https://roxie.ai
 *
 * Text Domain: roxie
 * Domain Path: /languages/
*/

add_action('admin_menu', 'roxie_create_menu');

function roxie_create_menu() {
  add_menu_page(__('Roxie Settings', 'roxie'), __('Roxie.ai Settings', 'roxie'), 'administrator', __FILE__, 'roxie_plugin_settings_page' , 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjE3IiBoZWlnaHQ9IjE5NSIgdmlld0JveD0iMCAwIDIxNyAxOTUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIGNsaXAtcGF0aD0idXJsKCNjbGlwMF83Nl8xMDkpIj4KPHBhdGggZD0iTTIxNyAxMzAuMDY3QzIxNyAxNjUuODA2IDE4Ny44NDIgMTk1IDE1MS44OTcgMTk1SDBWNjQuOTMzNEMwIDI5LjE5MzUgMjkuMTU4MiAwIDY1LjEwMyAwSDE1MS44OTdDMTUxLjk0NyAwIDE1MS45OTcgMCAxNTIuMDQ3IDBDMTg3Ljk0MiAwLjAxOTk4ODcgMjE3LjAyIDI5LjA5MzYgMjE2Ljk5IDY0LjkzMzRWMTMwLjA2N0gyMTdaTTY1LjEwMyA4Ni42NzExVjEwOC4zMzlIODYuODA0Vjg2LjY3MTFINjUuMTAzWk0xMzAuMjA2IDg2LjY3MTFWMTA4LjMzOUgxNTEuOTA3Vjg2LjY3MTFIMTMwLjIwNloiIGZpbGw9InVybCgjcGFpbnQwX2xpbmVhcl83Nl8xMDkpIi8+CjwvZz4KPGRlZnM+CjxsaW5lYXJHcmFkaWVudCBpZD0icGFpbnQwX2xpbmVhcl83Nl8xMDkiIHgxPSItMjIuMDQxMyIgeTE9Ijk3LjQ5NSIgeDI9IjIwMi41NjYiIHkyPSI5Ny40OTUiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KPHN0b3Agb2Zmc2V0PSIwLjI0IiBzdG9wLWNvbG9yPSIjNjc2NUU5Ii8+CjxzdG9wIG9mZnNldD0iMC4zNSIgc3RvcC1jb2xvcj0iIzc5NjJEQyIvPgo8c3RvcCBvZmZzZXQ9IjAuNzIiIHN0b3AtY29sb3I9IiNCQTU4QjAiLz4KPHN0b3Agb2Zmc2V0PSIwLjkiIHN0b3AtY29sb3I9IiNENDU1OUYiLz4KPC9saW5lYXJHcmFkaWVudD4KPGNsaXBQYXRoIGlkPSJjbGlwMF83Nl8xMDkiPgo8cmVjdCB3aWR0aD0iMjE3IiBoZWlnaHQ9IjE5NSIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4K');
  add_action('admin_init', 'register_roxie_plugin_settings' );
  add_action('admin_init', 'register_roxie_plugin_onboarding');
}

function register_roxie_plugin_onboarding() {
  $onboarding = get_option('roxie_onboarding');
  $agent_id = get_option('agent_id');

  if (empty($agent_id) && (empty($onboarding) || !$onboarding)) {
    update_option("roxie_onboarding", true);
    wp_redirect(admin_url('admin.php?page='.plugin_basename(__FILE__)));
  }
}

function register_roxie_plugin_settings() {
  register_setting( 'roxie-plugin-settings-group', 'agent_id' );
  add_option('roxie_onboarding', false);
}

function roxie_plugin_settings_page() {
  if (isset($_GET["agentId"]) && !empty($_GET["agentId"])) {
    update_option("agent_id", $_GET["agentId"]);
  }

  if (isset($_GET["roxie_verify"]) && !empty($_GET["roxie_verify"])) {
    update_option("website_verify", $_GET["roxie_verify"]);
  }

  $agent_id = get_option('agent_id');
  // echo("-------------------------->");
  // echo(get_option('siteurl'));
  // update_option("agent_id", null);

  $is_roxie_working = isset($agent_id) && !empty($agent_id);
  $http_callback = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  // $base_url = "http://localhost:3000";
  $base_url = "https://app.roxie.ai";
  $add_to_roxie_link = $base_url."/integrations/wordpress/config?callback=$http_callback&siteurl=".get_option('siteurl')."&agentId=".$agent_id;
?>

<link rel="stylesheet" href="<?php echo plugins_url("assets/style.css", __FILE__ );?>">
  <?php
  if ($is_roxie_working) {
  ?>

  <div class="wrap roxie-wrap">
    <div class="roxie-modal">
      <h2 class="roxie-title"><?php _e('Connected with Roxie.', 'roxie'); ?></h2>
      <p class="roxie-subtitle"><?php _e('You can now use Roxie from your homepage.', 'roxie'); ?></p>
      <!-- <a class="roxie-button roxie-neutral" href="https://app.crisp.chat/settings/website/<?php echo $agent_id ?>"><?php _e('Go to my Roxie Settings', 'roxie'); ?></a> -->

      <!-- <a class="roxie-button roxie" href="https://app.crisp.chat/website/<?php echo $agent_id ?>/inbox/"><?php _e('Go to my Inbox', 'roxie'); ?></a> -->

      <a class="roxie-button roxie-neutral" href="<?php echo $add_to_roxie_link; ?>"><?php _e('Reconfigure', 'roxie'); ?></a>

      
    </div>

    <!-- <p class="roxie-notice"><?php _e('Loving Roxie <b style="color:red">♥</b> ? Rate us on the <a target="_blank" href="https://wordpress.org/support/plugin/roxie/reviews/?filter=5">Wordpress Plugin Directory</a>', 'roxie'); ?></p> -->
  </div>

  <?php
  } else {
  ?>
  <div class="wrap roxie-wrap">
    <div class="roxie-modal">
      <h2 class="roxie-title"><?php _e('Connect with Roxie.ai', 'roxie'); ?></h2>
      <p class="roxie-subtitle"><?php _e('This link will redirect you to Roxie and configure your Wordpress.', 'roxie'); ?></p>
      <a class="roxie-button roxie" href="<?php echo $add_to_roxie_link; ?>"><?php _e('Connect with Roxie', 'roxie'); ?></a>
    </div>
  </div>
  <?php
  }
}

add_action('wp_head', 'roxie_hook_head', 1);

function roxie_hook_head() {
  $agent_id = get_option('agent_id');
  $locale = str_replace("_", "-", strtolower(get_locale()));

  if (!in_array($locale, array("pt-br", "pt-pr"))) {
    $locale = substr($locale, 0, 2);
  }

  if (!isset($agent_id) || empty($agent_id)) {
    return;
  }

  $output="<script 
    data-cfasync='false' 
    data-name='roxie-chat-bubble'
    id='$agent_id'
    src='https://cdn.jsdelivr.net/npm/@roxie/chat-bubble@latest'
  >";

  $output .= "</script>";
  
  echo $output;
}
