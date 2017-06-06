<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $link= mysqli_connect($host, $user, $pass, 'ticket');
        $sql = "SELECT * FROM movie";
        $theloai = "select title.theloai from movie,theloai where theloai.idtheloai=movie.idtheloai";
        $result = mysqli_query($link,$sql);
        ?>
    <table>
    	<tr>
    		<td>a</td>
    		<td>b</td>
    		<td>c</td>
    		<td>d</td>
    		<td>e</td>
    		<td>f</td>
    		<td>g</td>
    	</tr>
    	<?php 
    	    while ($row=mysqli_fetch_assoc($result)){
    	?>
    	    <tr>
    	    	<td><?php echo $row['id']; ?></td>
    	    	<td><?php echo $row['title']; ?></td>
    	    	<td><?php echo $row['duration']; ?></td>
    	    	<td><?php echo $row['director']; ?></td>
    	    	<td><?php echo $row['actor']; ?></td>
    	    	<td><?php echo $row['language']; ?></td>
    	    	<td><?php echo $row['country']; ?></td>
    	    </tr>
    	<?php 
    	    }
    	?>
    </table>	
</body>
</html>