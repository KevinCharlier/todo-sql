<?php
    try
    {
        // Connexion à MySql
        $db = new PDO('mysql:host=localhost;dbname=todo;charset=utf8', 'root', 'user');
    }
    catch(Exception $error)
    {
        // Si erreur
        die('Erreur : '.$error->getMessage());
    }
    // bouton ajouter
    if (isset($_POST['submit']) AND end($receipt)['taskname'] != $_POST['newtask'] ){ 
        $add_task = $_POST['newtask']; //Récupération de la valeur
        $array_task = array("taskname" => $add_task, "Terminer" => false);
        $receipt[] = $array_task;
        $dbadd = "INSERT INTO tasks (task, archive) VALUES ('".$add_task."', 'false')"; // Ajout de la tache (valeur = false) sur la db \\
        $result = $db->exec($dbadd); // requête envoyée à la db \\
    }
    // bouton enregistrer
    if (isset($_POST['save'])){
        $choice=$_POST['newtask'];
            foreach ($choice as $key){
                $dbup = "UPDATE tasks SET archive = 'true' WHERE task='".$key."'"; // Remplace 'false' par 'true' \\
                $result = $db->exec($dbup);
            }
    }
    // bouton retirer
    if (isset($_POST['unsave'])){
        $choice=$_POST['removetask'];
            foreach ($choice as $key){
                $dbup = "DELETE FROM tasks WHERE archive = 'true'"; // Suppression des archives dans la db
                $result = $db->exec($dbup);
            }
    }   
    
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <script type="text/javascript">
            function ShowHideDiv(chkTask) {
                var dvTask = document.getElementById("dvTask");
                dvTask.style.display = chkTask.checked ? "block" : "none";
            }
        </script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css" type="text/css" charset="utf-8" />
        <title>To do</title>
    </head>

    <body>
        <div class="container">
            <fieldset>
                <form action="index.php" method="POST">

                    <h3>A faire</h3>
                    <ul id="incomplete-tasks">
                        <?php 
                                $result = $db->query('SELECT * FROM tasks WHERE archive="false"'); // appel des données de la table tasks qui ont 'false' comme valeur
                                while ($data = $result->fetch())
                                {
                                    echo "<li><input onclick='ShowHideDiv(this)' type='checkbox' name='newtask[]' value='".($data['task'])."'/>
                                        <label for='choice'>".($data['task'])."</label></li><br />"; 
                                } 
                            ?>
                    </ul>
                    <div id="dvTask" style="display: none">
                        <button class="save" name="save" type="submit">Enregistrer</button>
                    </div>
                </form>

                <form action="index.php" method="POST">

                    <h3>Archive(s)</h3>
                    <ul id="completed-tasks">
                        <?php 
                                $result = $db->query('SELECT * FROM tasks WHERE archive="true"'); // appel des données de la table tasks qui ont 'true' comme valeur
                                while ($data = $result->fetch())
                                {
                                    echo "<li><input type='checkbox' name='removetask[]' value='".($data['task'])."'checked/>
                                        <label for='choice'>".($data['task'])."</label></li><br />"; 
                                } 
                            ?>
                    </ul>
                    <button class="unsave" name="unsave" type="submit">Retirer</button>
                </form>
            </fieldset>
        </div>

        <div class="container">
            <fieldset>
                <form method="post" action="index.php">
                    <p>
                        <h3>Ajouter une nouvelle tache</h3>
                    </p>
                    <p>
                        <textarea name="newtask" placeholder="(Ajouter votre tâche ici)" class="expanding" autofocus></textarea>
                    </p>
                    <p>
                        <button type="submit" name="submit">Ajouter</button>
                    </p>
                </form>
            </fieldset>
        </div>
        </fieldset>
    </body>

    </html>