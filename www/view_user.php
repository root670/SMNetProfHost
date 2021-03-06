<?php

require_once "src/models/biscuit.php";
require_once "src/models/user.php";

$primary_key = $_GET["id"];
$user = NULL;
$biscuits = NULL;

try {
    $user = new User(array("id" => $primary_key));
} catch (Exception $e) {
    error_log(sprintf("User %d does not exist", $primary_key));
}

if (!is_null($user)) {
    $biscuits = $user->get_biscuits();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?=isset($user) ? $user->username : "N/A"?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<a href="user_management.php">&lt; Users</a>

<?php if ($user == NULL): ?>
    <div class="error">
        No such user exists for id <?=$primary_key?>
    </div>
<?php else: ?>
    <h1><?=$user->username?></h1>

    <?=$user->property_list_markup();?>

    <?php if ($user->pass_filename): ?>
        <div id="passbook-container">
            <a href="api/download_pass.php?user_id=<?=$user->primary_key?>">
                <img src="img/passbook.png" />
            </a>

            <span>Download Passbook Pass</span>
        </div>
    <?php else: ?>
        <div id="passbook-container">
            <form id="create-pass-form" method="POST" action="api/create_pass.php">
                <input type="hidden" name="user_id" value="<?=$user->primary_key?>" />
                <a onclick="document.getElementById('create-pass-form').submit();">
                    <img src="img/green-plus.png" />
                </a>
            </form>
            <span>Create New Passbook Pass</span>
        </div>
    <?php endif; ?>

    <h2>Biscuits</h2>
    <div id="user-biscuits">
        <?php if (count($biscuits) == 0): ?>
            <div class="no-data">
                No biscuits.
            </div>
        <?php else: ?>
            <?php foreach ($biscuits as $idx => $biscuit): ?>
                <div class="biscuit">
                    <h3>Biscuit <?=intval($idx)+1?></h3>
                    <table class="property-table">
                        <tr>
                            <td class="property-table-key">
                                file_path
                            </td>
                            <td class="property-list-value">
                                <?=$biscuit->get_biscuit_path()?>
                            </td>
                        </tr>
                    </table>
                    <?=$biscuit->property_list_markup()?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="spaced-container">
            <form method="POST" action="api/create_biscuit.php">
                <input type="hidden" name="user_id" value="<?=$user->primary_key?>" />
                <input id="add-biscuit" type="submit" value="Create New Biscuit" />
            </form>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
