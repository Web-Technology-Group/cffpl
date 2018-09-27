<?php

namespace AdminArea;

use Com\PlayerArea;

require_once('..\classes\com\playerarea\CalculationEngine.php');

PlayerArea\CalculationEngine::generateAndInsertAllUserWeeklyScores();
//generateAllUserWeeklyScores