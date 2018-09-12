<?php

namespace Tests;

use Com\PlayerArea\Database;

require_once('..\playerarea\database\DBConnection.php');

$obj1 = Database\DBConnection::getPDOInstance();

$statement = $obj1->query('Select username from users');
while ($row = $statement->fetch())
{
    echo $row['username'] . "\n";
}
