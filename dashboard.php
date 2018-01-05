<?php
require_once 'config/config.php';
require_once 'config/db.php';
include_once 'inc/log.php';
require_once 'comment.php';
require_once 'session.php';
require_once 'user_file.php';
require_once 'command.php';

session_start();
protect_route('dashboard');

insertComment($mysqli);

csrfguard_start();
?>

<?php include_once 'inc/header.php'; ?>

<!-- <section class="comment-section"> -->
<div class="col-lg-6 col-sm-12 left-panel">
    <div class="user-input">
        <?php echoNewCommentForm() ?>
    </div>
    <?php getComments($mysqli); ?>
</div>
<!-- </section> -->

<div class="col-lg-6 col-sm-12 right-panel">
<div class="command-section">
    <div class="user-input">
        <?php echoCommandForm() ?>
    </div>
    <div class="userOutput">
        <?php executePing(); ?>
    </div>
</div>
<br>
<div class="file-section">
    <div class="user-input">
        <?php echoFileForm() ?>
    </div>
    <div class="userOutput">
        <?php getFileContent() ?>
    </div>
</div>
</div>

<?php include_once 'inc/footer.php'; ?>
