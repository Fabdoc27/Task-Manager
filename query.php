<?php

require_once './config.php';

$connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

if ( !$connection ) {
    throw new Exception( "Cannot connect to database: " . mysqli_connect_error() );
} else {
    $action = $_POST['action'] ?? "";

    if ( !$action ) {
        header( "Location: index.php" );
        die();
    } else {
        // insert
        if ( $action == "add" ) {
            $task = filter_input( INPUT_POST, 'task', FILTER_SANITIZE_SPECIAL_CHARS );
            $date = filter_input( INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS );

            if ( $task && $date ) {
                $task = mysqli_real_escape_string( $connection, $task );
                $date = mysqli_real_escape_string( $connection, $date );

                $query = "INSERT INTO tasks (task, date) VALUES ('{$task}', '{$date}')";
                mysqli_query( $connection, $query );

                header( 'Location: index.php?added=true' );
            }
        }
        // update or mark as complete
        elseif ( $action == "complete" ) {
            $taskId = filter_input( INPUT_POST, 'ctask', FILTER_VALIDATE_INT );

            if ( $taskId !== false ) {
                $taskId = mysqli_real_escape_string( $connection, $taskId );
                $query = "UPDATE tasks SET complete=1 WHERE id={$taskId} LIMIT 1";
                mysqli_query( $connection, $query );
            }

            header( 'Location: index.php' );
        }
        // delete
        elseif ( $action == "delete" ) {
            $dtaskId = filter_input( INPUT_POST, 'dtask', FILTER_VALIDATE_INT );

            if ( $dtaskId !== false ) {
                $dtaskId = mysqli_real_escape_string( $connection, $dtaskId );
                $query = "DELETE FROM tasks WHERE id={$dtaskId} LIMIT 1";
                mysqli_query( $connection, $query );
            }

            header( 'Location: index.php' );
        }
        // update or mark as incomplete
        elseif ( $action == "incomplete" ) {
            $itaskId = filter_input( INPUT_POST, 'itask', FILTER_VALIDATE_INT );

            if ( $itaskId !== false ) {
                $itaskId = mysqli_real_escape_string( $connection, $itaskId );
                $query = "UPDATE tasks SET complete=0 WHERE id={$itaskId} LIMIT 1";
                mysqli_query( $connection, $query );
            }

            header( 'Location: index.php' );
        }
        // mark as complete from bulk select
        elseif ( $action == "bulkcomplete" ) {
            $bulkId = filter_input( INPUT_POST, 'bulkid', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

            if ( $bulkId !== false ) {
                $_bulkId = join( ',', $bulkId );
                $query = "UPDATE tasks SET complete=1 WHERE id IN ({$_bulkId})";
                mysqli_query( $connection, $query );
            }

            header( 'Location: index.php' );
        }
        // delete from bulk select
        elseif ( $action == "bulkdelete" ) {
            $bulkId = filter_input( INPUT_POST, 'bulkid', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

            if ( $bulkId !== false ) {
                $_bulkId = join( ',', $bulkId );
                $query = "DELETE FROM tasks WHERE id IN ({$_bulkId})";
                mysqli_query( $connection, $query );
            }

            header( 'Location: index.php' );
        }
    }
}

mysqli_close( $connection );