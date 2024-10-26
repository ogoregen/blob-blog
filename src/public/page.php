<?php

// displays a page created on /admin

require __DIR__."/../database.php";
require __DIR__."/../flighty.php";
require __DIR__."/../vendor/parsedown-1.7.4/Parsedown.php";

session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $preview = true;
    $Parsedown = new Parsedown();
    $page = [
        "title" => addslashes($_POST["title"]),
        "content" => $Parsedown->text(addslashes($_POST['content_raw'])),
    ];
}
else{

    $page = query("SELECT id, title, content FROM pages WHERE id = {$_GET["id"]};")[0] ?? false;
    if(!$page) throw_404();
}

include_component("page_head.php", [
    "title" => $page["title"],
]);

?>

<div class="fly-content-wrapper">
    <div>
        <?php include_component("nav.php") ?>
        <article>
            <h1 class="fly-margin-small-bottom"><?= $page["title"] ?></h1>
            <?php if(isset($_SESSION["is_authenticated"])): ?>
                <div class="fly-margin-horizontal">
                    <?php 
                    if(isset($preview)) echo "preview";
                    else echo "<a href='/admin?page=pages&id={$page['id']}'>edit</a>";
                    ?>
                </div>
            <?php endif ?>
            <div class="fly-width-2-3 fly-dropcap fly-margin-auto-horizontal fly-article" style="margin-top: 32px">
                <?= $page["content"] ?>
            </div>
        </article>
    </div>
    <div>
        <?php include_component("footer.php") ?>
    </div>
</div>

<?php include_component("page_end.php") ?>