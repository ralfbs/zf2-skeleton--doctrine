<?php
/**
 * ZF2 Training.
 *
 * @author    Ralf Schneider <ralf@hr-interactive.de>
 */

/**
 * @see http://framework.zend.com/manual/current/en/modules/zend.navigation.quick-start.html
 *
 * @var array
 */
return array(
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Dashboard',
                'route' => 'home',
            ),
            array(
                'label'      => 'Benutzer',
                'route'      => 'application/default',
                'controller' => 'auth',
                'action'     => 'index',
                'pages'      => array(
                    array(
                        'label'      => 'Login',
                        'route'      => 'applicaton/default',
                        'controller' => 'auth',
                        'action'     => 'login',
                        'class'      => 'disabled',
                    ),
                    array(
                        'label'      => 'Login',
                        'route'      => 'applicaton/default',
                        'controller' => 'auth',
                        'action'     => 'logout',
                        'class'      => 'disabled',
                    ),
                ),
            ),

        )
    )
);