<?php
include_once 'session.php';
include_once 'inc/util.php';

/**
 * Echo the command form.
 *
 * @return void
 */
function echoCommandForm()
{
    echo '<form method="GET" class="form nocsrf" action="">' .
            '<div class="form-group">' .
                '<label for="args">Command arguments</label>' .
                '<input type="text" name="args" name class="form-control">' .
            '</div>' .
            '<input type="submit" value="Execute" class="btn btn-warning">' .
         '</form>';
}

/**
 * Execute the ping command with the provided arguments
 * and echo the result.
 * @return void
 */
function executePing()
{
    if (!isset($_GET['args'])) {
        return;
    }

    $command = 'ping ' . $_GET['args'];
    $command = escapeshellcmd($command);
    
    echo '<div class="command-result">';
    echo '<div class="well">';
    echo '<p>';
    xecho(shell_exec($command));
    echo '</p>';
    echo '</div>';
}
