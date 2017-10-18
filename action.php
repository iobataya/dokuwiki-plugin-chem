<?php
if(!defined('DOKU_INC')) die();
class action_plugin_chem extends DokuWiki_Action_Plugin {
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handle_toolbar', array ());
    }

    function handle_toolbar(&$event, $param) {
        $event->data[] = array (
                'type' => 'format',
                'title' => 'Chem plugin',
                'icon' => '../../plugins/chem/img/pl_chem.png',
                'open'   => '<chem>',
                'close'  => '</chem>',
        );

    }
}
