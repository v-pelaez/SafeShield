<aside>
<div>
    <h2>Información Adicional</h2>
    <?php
    if ($request === "/info") {
        // Contenido especial para la página "info"
        echo "<h3>Lista de tarifas</h3>";
        $log = readJsons("../data/altas.json");
        printPlanList($log);
    
    } else {
        echo "<p>Para más detalles, visita nuestra <a href='/info'>página de información</a>.</p>";
    }
    ?>
    
</div>
</aside>