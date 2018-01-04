<?php

require_once 'inc/util.php';
require_once 'session.php';

/**
 * Retrieve all comments from the database and echos
 * them as html
 * @return void
 */
function getComments($mysqli)
{
    $sql = 'SELECT * FROM post ORDER BY created_at DESC';
    $result = $mysqli->real_query($sql);
    $posts = $mysqli->store_result();
    $mysqli->close();

    echo '<div class="comments">' . 
         '<h2>Posts</h2>';
    foreach($posts as $post) {
        echo '<div class="well">' . 
                '<small><b>' . sanitizeString($post['author']) . '</b> ' . 
                sanitizeString($post['created_at']) . '</small>' .
                '<p>' . nl2br(sanitizeString($post['comment'])) . '</p>' .
             '</div>';
    }

    echo '</div>';
}

/**
 * Insert a new comment in the database.
 *
 * @return void
 */
function insertComment($mysqli)
{
    if(!isset($_POST['commentSubmit'])) {
        return;
    }

    $sql = "INSERT INTO post(author, comment) VALUES(?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $param_author, $param_comment);

    $param_author = get_from_session('username');
    $param_comment = $_POST['comment'];

    if($stmt->execute()) {
        header('location: dashboard.php');
    } else {
        echo 'Error: ' . $mysqli->error;
    }

    $stmt->close();
    $mysqli->close();
}

/**
 * Echos the new comment form
 *
 * @return void
 */
function echoNewCommentForm()
{
    echo '<form method="POST" action="">' .
            '<div class="form-group">' .
                '<label>New comment</label>' .
                '<textarea name="comment" class="form-control"></textarea>' .
            '</div>' .
            '<input type="submit" name="commentSubmit" value="Submit" class="btn btn-primary">' .
        '</form>';
}
