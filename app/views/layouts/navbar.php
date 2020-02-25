<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="javascript:void(0)">Logo</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navb">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= URL . 'home'; ?>">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL . 'about'; ?>">
                    About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL . 'posts'; ?>">
                    Posts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL . 'contact'; ?>">
                    Contact
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL . 'posts/create'; ?>">
                    Create New Post
                </a>
            </li>
        </ul>
        <div class="navbar-right">
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search">
                <button class="btn btn-default my-2 btn-primary my-sm-0" type="button">Search</button>
            </form>
        </div>
    </div>
</nav>