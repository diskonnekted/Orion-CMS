<?php
/**
 * The Plugin API is located in this file, which allows for creating actions
 * and filters and hooking into the core function without modifying the core files.
 */

$orion_filter = array();
$orion_actions = array();
$orion_current_filter = array();

/**
 * Hook a function or method to a specific filter action.
 */
function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
    global $orion_filter;
    $idx = _orion_filter_build_unique_id( $tag, $function_to_add, $priority );
    $orion_filter[$tag][$priority][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
    return true;
}

/**
 * Execute functions hooked on a specific filter hook.
 */
function apply_filters( $tag, $value ) {
    global $orion_filter, $orion_current_filter;

    $args = func_get_args();

    // Do 'all' actions first
    if ( isset($orion_filter['all']) ) {
        $orion_current_filter[] = $tag;
        _orion_call_all_hook($args);
    }

    if ( !isset($orion_filter[$tag]) ) {
        if ( isset($orion_filter['all']) )
            array_pop($orion_current_filter);
        return $value;
    }

    if ( !isset($orion_filter['all']) )
        $orion_current_filter[] = $tag;

    // Sort
    ksort($orion_filter[$tag]);

    reset( $orion_filter[$tag] );

    do {
        foreach ( (array) current($orion_filter[$tag]) as $the_ ) {
            if ( !is_null($the_['function']) ){
                $args[1] = $value;
                $value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
            }
        }
    } while ( next($orion_filter[$tag]) !== false );

    array_pop( $orion_current_filter );

    return $value;
}

/**
 * Hooks a function on to a specific action.
 */
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
    return add_filter($tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Execute functions hooked on a specific action hook.
 */
function do_action($tag, $arg = '') {
    global $orion_filter, $orion_actions, $orion_current_filter;

    if ( !isset($orion_actions[$tag]) )
        $orion_actions[$tag] = 1;
    else
        ++$orion_actions[$tag];

    // Do 'all' actions first
    if ( isset($orion_filter['all']) ) {
        $orion_current_filter[] = $tag;
        $all_args = func_get_args();
        _orion_call_all_hook($all_args);
    }

    if ( !isset($orion_filter[$tag]) ) {
        if ( isset($orion_filter['all']) )
            array_pop($orion_current_filter);
        return;
    }

    if ( !isset($orion_filter['all']) )
        $orion_current_filter[] = $tag;

    $args = array();
    if ( is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0]) ) // array(&$this)
        $args[] =& $arg[0];
    else
        $args[] = $arg;

    for ( $a = 2; $a < func_num_args(); $a++ )
        $args[] = func_get_arg($a);

    // Sort
    ksort($orion_filter[$tag]);

    reset( $orion_filter[$tag] );

    do {
        foreach ( (array) current($orion_filter[$tag]) as $the_ ) {
            if ( !is_null($the_['function']) )
                call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));
        }
    } while ( next($orion_filter[$tag]) !== false );

    array_pop($orion_current_filter);
}

function _orion_filter_build_unique_id($tag, $function, $priority) {
    static $filter_id_count = 0;

    if ( is_string($function) )
        return $function;

    if ( is_object($function) ) {
        // Closures are currently implemented as objects
        $function = array( $function, '' );
    } else {
        $function = (array) $function;
    }

    if (is_object($function[0]) ) {
        return spl_object_hash($function[0]) . $function[1];
    } else if ( is_string($function[0]) ) {
        return $function[0] . $function[1];
    }
}

function _orion_call_all_hook($args) {
    global $orion_filter;

    reset( $orion_filter['all'] );
    do {
        foreach ( (array) current($orion_filter['all']) as $the_ ) {
            if ( !is_null($the_['function']) )
                call_user_func_array($the_['function'], $args);
        }
    } while ( next($orion_filter['all']) !== false );
}
