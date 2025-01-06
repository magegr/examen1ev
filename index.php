<?php
    session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ex1aEval</title>
</head>
<body>
    <header>
        <a href="index.php">
            <h1>MICROROBOTS<img id='robo' src="./robot.png"></h1>
        </a>
        <nav>
            <form action="index.php" method="POST">
                <label for="tablero">Nuevo tablero:</label>   
                <input type="submit" id=vtablero value="Generar" name="submit_genero">
            </form>
            
            <a href="quienes.html">¿Quienes somos?</a>
            
        </nav>
    </header>
    <section>
        <article class="normas">
            <h2>Bienvenido a microroborts</h1>
            <h3>Instrucciones:</h3>
            <p> 
                1. El objetivo es llegar a la casilla final.<br>
                2. Para moverte, elige una casilla válida que esté en la misma fila o columna y que coincida en número o color con tu casilla actual.<br>
                3. La casilla mas a la izquierda es la (0,0).<br>
                4. Si llegas a la casilla final, ¡has ganado!<br>
                5. Para moverte por el juego tendrás que indicar primero la casilla en la que estass y a la que te quieras mover.
            </p>
        </article>
        <br><br>
        <article>
        <form action="index.php" method="POST">
            <label>¿En que casilla estas?
            <input type="number"  value="0" name="ninfila">
            <input type="number"  value="0" name="nincol">
            </label>
            <br>
            <label>¿A que casilla quieres moverte?
            <input type="number" value="0" name="nfifila">
            <input type="number" value="0" name="nficol">
            </label>
            <br>
            <input type="submit" id="colfil" value="Prueba" name="submit_datos">
        </form>
        </article>
    </section>
</body>
</html>
<?php
    //CODIGO PRINCIPAL
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['submit_genero'])) {//si le da al boton
            $longitud=6;
            $combinaciones=36;
            $combinacionesTotal=generarCombinaciones($longitud,$combinaciones);
            $tablero=creaTablero($combinacionesTotal,$longitud);
            $_SESSION['tablero'] = $tablero;
        }
        if (isset($_SESSION['tablero'])) {//mira si la sesion esta bien
            $tablero = $_SESSION['tablero'];
        } else {
            $tablero = []; // Si no hay tablero, se crea
        }
        echo"<br>";
        if ($tablero){
            echo "<h2>Tablero</h2>";
            $printablero=dibujarTablero($tablero);
            
        }
        if(isset($_POST['submit_datos'])){
            $inicioFila = $_POST['ninfila'];
            $inicioCol = $_POST["nincol"];
            $finFila = $_POST['nfifila'];
            $finCol = $_POST["nficol"];
            $inicio = $tablero[$inicioFila][$inicioCol];
            $fin = $tablero[$finFila][$finCol];
            tiradaPermitida($inicioFila,$inicioCol,$fin,$tablero,$inicio);
           
        }
    }
    
    
    //FUNCIONES 
    function generarCombinaciones ($longitud,$combi){
        $numeros=range(1,$longitud);
        
        $colores=["rojo","azul","blanco","verde","rosa","amarillo"];
        $combinaciones=[];
        
        for ($i=1; $i<=$combi; $i++) {
            do {
                $numero= $numeros[array_rand($numeros)];
                $color= $colores[array_rand($colores)];
            } while (in_array(([$numero,$color]),$combinaciones));
            $combinaciones[]=[$numero,$color]; 
        }
        
        return $combinaciones;
    }
    function creaTablero ($combinacionesTotales,$longitud){
        $tablero=[];
        for ($i=0; $i<$longitud; $i++) { 
            for ($j=0; $j < $longitud; $j++) {  
                $tablero[$i][]=$combinacionesTotales[$j];
            }
            array_splice($combinacionesTotales,0,6);
        }
       
        return $tablero;
        
    }
    
    function dibujarTablero ($tablero) {
        echo "<table>";
        for($i=0;$i<count($tablero);$i++){
            echo "<tr>";
            for($j=0;$j<count($tablero[$i]);$j++){
                echo "<td>";
                for ($k=0; $k <count($tablero[$i][$j]) ; $k++) { 
                    $muestra=$tablero[$i][$j][$k];
                    echo $muestra;
                }
                echo " "."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    function tiradaPermitida($inicio_fila,$inicio_columna,$fin,$tablero,$casilla_inicio){
        for ($i = 0; $i < 6; $i++) {
        if (($tablero[$inicio_fila][$i] == $fin && $casilla_inicio[0] == $fin[0]) || 
            ($tablero[$i][$inicio_columna] == $fin && $casilla_inicio[1] == $fin[1])) {
            echo "<p>¡Puedes moverte!</p>";
            exit;
        }
    }
    echo "<p>No puedes moverte a esa casilla.</p>";
}
?>