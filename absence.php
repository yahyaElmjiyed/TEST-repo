<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database1234";
$port = 3307;

$conn = mysqli_connect($servername.":".$port, $username, $password, $dbname);

// on verifie si la cnx marche ou pas sinon un message d'ereure
if (!$conn) {
    die("Connexion échouée: " . mysqli_connect_error());
}

// on recupere les absence de la base de donne en utilisant des requetes sql simple
$sql = "SELECT id, name, start_date, end_date, reason FROM absences";
$result = $conn->query($sql);     // $conn : heya wa7d variable li drna lfog bach ndiro conection to data base
                                  // query(): 3tinaha l parametre $sql li deja feh une requete sql donc le but c'est d'executer la requete
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yahya ELmjiyed</title>
  <style>
   body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, black, grey,darkblue);
            color: white;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px  rgba(0,0,0,0.7) ;
        }

        form {
            max-width: 500px;
            margin:0 auto 30px;
            padding: 20px;
            background-color: rgba(255, 255, 255 , 0.2);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: white;
        }

        input[type="password"],
        input[type="text"],
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.5);
            color: grey;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            display: block; /*pour resserver tout l'espace a submit sans avoir le desordre*/
            width: 100%;
            padding: 10px;
            background-color: rgba(255,255,255,0.4);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.8s ;/*comme cinematique*/
        }

        button[type="submit"]:hover {
            background-color: grey ;
        }

        table {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        th, td {
            padding: 10px;
            text-align: left;
            color:white;

        }
        th, td:hover{
          background-color: rgba(0,0,0,0.3);
          cursor: pointer;
        }
        

        th {
            background-color: rgba(256, 256, 256, 0.4);
            color: whitesmoke;
            font-size:25px;
        }

      
  </style>
  
</head>
   <body >  

  <h1><stron>Gestionnaire d'absences</strong></h1>


 
  <form id="absence-form" method="post"> 
    <label for="id">Identifiant :</label>
    <input type="password" id="id" name="id" required>  

    <label for="name">Nom :</label>
    <input type="text" id="name" name="name" required>   

    <label for="start-date">Date de début :</label>
    <input type="datetime-local" id="start-date" name="start_date" required>

    <label for="end-date">Date de fin :</label>
    <input type="datetime-local" id="end-date" name="end_date" required>

    <label for="reason">Raison :</label>
   <textarea id="reason" name="reason" required></textarea> 

    <button type="submit" name="submit">Soumettre</button>
  </form>

 <!-- now we gonne creat our table tada -->
  <h2>Absences planifiées</h2>
  <table id="absence-table">
    <thead>
      <tr>
        <th>Identifiant</th>
        <th>Nom</th>
        <th>Date de début</th>
        <th>Raison</th>
        <th>Date de fin</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) { //num_rows est une propriété dans PHP utilisée pour récupérer le nombre de lignes de résultat retournées
                                   // par une requête SELECT. Elle est automatiquement définie après l'exécution de la requête avec MySQLi ou PDO.
                                   //En résumé, -> sert à appeler les méthodes ou accéder aux propriétés d'un objet en programmation orientée objet.


             while ($row = $result->fetch_assoc()) { //we declared and use the variable row at the same time the result of a sql query ( requete)
                                                     //La variable `$row` stocke temporairement les données extraites d'une ligne de résultat retournée par une requête SQL, permettant leur accès et leur manipulation dans le programme.
                                                     //The fetch_assoc() / mysqli_fetch_assoc() function fetches a result row as an associative array
                                                     // 
              

              echo "<tr>";
              echo "<td>" .($row['id']) . "</td>";
              echo "<td>" .($row['name']) . "</td>";
              echo "<td>" .($row['start_date']) . "</td>";
              echo "<td>" . ($row['end_date']) . "</td>";
              echo "<td>" . ($row['reason']) . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>Aucune absence enregistrer</td></tr>";//colspan='5':  est un attribut de la balise <td> qui signifie que cette cellule prendra la largeur de 5 colonnes dans le tableau.
      }

      // Traitement du formulaire
      if (isset($_POST['submit'])) {
          $id = mysqli_real_escape_string($conn, $_POST['id']);
          $name = mysqli_real_escape_string($conn, $_POST['name']);
          $startDate = date('Y-m-d H:i:s', strtotime($_POST['start_date']));
          $endDate = date('Y-m-d H:i:s', strtotime($_POST['end_date']));
          $reason = mysqli_real_escape_string($conn, $_POST['reason']);

          // executer la requet sql avec (mysqli_query) pour inserer nouveau absence
          $sql = "INSERT INTO absences ( id, name, start_date, end_date, reason) VALUES ('$id', '$name', '$startDate', '$endDate', '$reason')";
          if (mysqli_query($conn, $sql)) {
              //on refraichi la page apres l'insertion pour afficher les nouvelles donnees
              echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";

              exit();
          } else {
              echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
          }
      }

      // Fermer la connexion .
      $conn->close();
      ?>
    </tbody>
  </table>
</body>
</html>