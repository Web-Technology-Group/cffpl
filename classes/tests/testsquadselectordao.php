<?php

require_once('..\playerarea\DBConnection.php');
require_once('..\playerarea\SquadSelectorDAO.php');

$squadSelector = new SquadSelectorDAO();
$squadGKs = $squadSelector->getAllSquadPlayersByPosition('G');
print_r($squadGKs);