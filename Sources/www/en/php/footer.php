<?php
    include_once("./../config/configLanguage.php");

    $footerTextLoad = loadFooterText();

    $langArray = [
        ['0', 'en', 'EN'],
        ['1', 'fr', 'FR'],
        ['2', 'es', 'ES'],
        ['3', 'jp', 'JP']
    ];
?>

<footer id="footer">
    <select id="lang" name="lang" onchange="modifyLang();">
        <?php
            foreach($langArray as $lang)
            {
                echo "<option value='" . $lang[0] . "' data-img_src='./../img/ico/" . $lang[1] . ".svg' ";
                if(!strcmp($lang[0], $_COOKIE['language']))
                    echo "selected";
                echo ">" . $lang[2] . "</option>";
            }
        ?>
    </select>
    <div>© <?php printf($footerTextLoad['by'][$_COOKIE['language']]); ?> HERVÉ Théo DE FARIA LEITE Armand DOAN Hoai-Viet Luc</div>
</footer>

<script type="text/javascript">
    function modifyLang()
    {
        let selectLang = document.getElementById('lang').value;
        let requestModifyLang = new XMLHttpRequest();

        requestModifyLang.open('GET', './../../config/changeLang?lang='+ selectLang);
        requestModifyLang.send();

        setTimeout(function()
        {
            document.location.reload(true)
        }, 500);
    }
</script>
