<?php
require_once 'config/config.php';
require_once 'config/db.php';
include_once 'inc/log.php';
require_once 'comment.php';
require_once 'session.php';
require_once 'user_file.php';

session_start();
protect_route('dashboard');

insertComment($mysqli);

csrfguard_start();
?>

<?php include_once 'inc/header.php'; ?>

<section class="comment-section">
<div class="col-lg-6 col-sm-12 left-panel">
    <div class="new-comment">
        <?php echoNewCommentForm() ?>
    </div>
    <?php getComments($mysqli); ?>
</div>
</section>

<div class="col-lg-6 col-sm-12 right-panel">
<div class="file-section">
    <div class="new-comment">
        <?php echoFileForm() ?>
    </div>
    <div class="comments">
        <?php getFileContent() ?>
    </div>
</div>
<div class="command-section">
    <div class="">
        test2
    </div>
</div>
</div>

<?php include_once 'inc/footer.php'; ?>
