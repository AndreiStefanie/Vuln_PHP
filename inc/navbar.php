<nav class="navbar navbar-<?php if($_SESSION['type']) { echo 'inverse'; } else { echo 'default'; } ?>">
    <div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" 
                data-toggle="collapse" 
                data-target="#navbar" 
                aria-expanded="false" 
                aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">SRC_Vuln</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="<?php echo ROOT_URL; ?>">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo ROOT_URL; ?>/logout.php">Logout</a></li>
        </ul>
    </div>
    </div>
</nav>
