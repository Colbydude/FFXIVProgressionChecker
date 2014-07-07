<?php
	// Force turn off any error reporting (cause this is kind of sloppy).
	error_reporting(0);

	// Require API.
	require_once("lodestone-api.php");

	// Initialize a LodestoneAPI Obkect
	$API = new LodestoneAPI();

	// Variables.
	$name = urldecode($_GET["name"]);
	$server = urldecode($_GET["server"]);

	// Parse the character.
	$Character = $API->get(array(
		"name" => $name, 
		"server" => $server
	));

	if ($Character)
	{
		$CharID = $Character->getID();

		// Parse achievements
		// Kind of sketchy on using all these absolute numbers, need to find a better way to do this if possible.
		$API->parseAchievementsByCategory(1, $CharID);
		$BattleAchievements = $API->getAchievements()[1]->get();
		$API->parseAchievementsByCategory(11, $CharID);					// Why is this 11 and not 7?
		$ExplorationAchievements = $API->getAchievements()[11]->get();
		
		if ($BattleAchievements && $ExplorationAchievements)
		{
			$Progression = array();
			$Raids = array("Binding Coil of Bahamut" => array("cleared" => FALSE), "Labyrinth of the Ancients" => array("cleared" => FALSE), "Second Coil of Bahamut" => array("cleared" => FALSE));
				$BCoBTurns = array("Turn 1" => array("explored" => FALSE), "Turn 2" => array("explored" => FALSE), "Turn 3" => array("explored" => FALSE), "Turn 4" => array("explored" => "Unknown"), "Turn 5" => array("explored" => FALSE));
				$SCoBTurns = array("Turn 1" => array("explored" => FALSE), "Turn 2" => array("explored" => FALSE), "Turn 3" => array("explored" => FALSE), "Turn 4" => array("explored" => "Unknown"));
			$EXPrimals = array("Bowl of Embers" => array("cleared" => FALSE), "Howling Eye" => array("cleared" => FALSE), "Navel" => array("cleared" => FALSE), "Whorleater" => array("cleared" => FALSE), "Thornmarch" => array("cleared" => FALSE), "Striking Tree" => array("cleared" => FALSE));

			// ----- RAIDS ----- \\
			// Binding Coil of Bahamut.

			// --- Individual Turns --- \\
			if ($ExplorationAchievements[680]["obtained"])	// Mapping the Realm: The Binding Coil of Bahamut I
			{
				$BCoBTurns["Turn 1"]["explored"] = TRUE;
				$BCoBTurns["Turn 1"]["date"] = $ExplorationAchievements[680]["date"];
			}
			if ($ExplorationAchievements[681]["obtained"])	// Mapping the Realm: The Binding Coil of Bahamut II
			{
				$BCoBTurns["Turn 2"]["explored"] = TRUE;
				$BCoBTurns["Turn 2"]["date"] = $ExplorationAchievements[681]["date"];
			}
			if ($ExplorationAchievements[682]["obtained"])	// Mapping the Realm: The Binding Coil of Bahamut III
			{
				$BCoBTurns["Turn 3"]["explored"] = TRUE;
				$BCoBTurns["Turn 3"]["date"] = $ExplorationAchievements[682]["date"];
			}
			if ($ExplorationAchievements[684]["obtained"])	// Mapping the Realm: The Binding Coil of Bahamut V
			{
				$BCoBTurns["Turn 5"]["explored"] = TRUE;
				$BCoBTurns["Turn 5"]["date"] = $ExplorationAchievements[684]["date"];
			}

			// --- Full Clears --- \\
			if ($BattleAchievements[747]["obtained"])	// The Binds that Tie I
			{
				$Raids["Binding Coil of Bahamut"]["cleared"] = TRUE;
				$Raids["Binding Coil of Bahamut"]["date"] = $BattleAchievements[747]["date"];
				$Raids["Binding Coil of Bahamut"]["times"] = 1;
			}
			if ($BattleAchievements[748]["obtained"])	// The Binds that Tie II
			{
				$Raids["Binding Coil of Bahamut"]["first"] = $BattleAchievements[747]["date"];
				$Raids["Binding Coil of Bahamut"]["date"] = $BattleAchievements[748]["date"];
				$Raids["Binding Coil of Bahamut"]["times"] = 5;
			}
			if ($BattleAchievements[749]["obtained"])	// The Binds that Tie III
			{
				$Raids["Binding Coil of Bahamut"]["date"] = $BattleAchievements[749]["date"];
				$Raids["Binding Coil of Bahamut"]["times"] = 10;
			}

			$Raids["Binding Coil of Bahamut"]["turns"] = $BCoBTurns;

			// Labyrinth of the Ancients.
			if ($BattleAchievements[883]["obtained"])	// You Call That a Labyrinth
				$Raids["Labyrinth of the Ancients"] = array("cleared" => TRUE, "date" => $BattleAchievements[883]["date"]);

			// Second Coil of Bahamut.

			// --- Individual Turns --- \\
			if ($ExplorationAchievements[890]["obtained"])	// Mapping the Realm: The Second Coil of Bahamut I
			{
				$SCoBTurns["Turn 1"]["explored"] = TRUE;
				$SCoBTurns["Turn 1"]["date"] = $ExplorationAchievements[890]["date"];
			}
			if ($ExplorationAchievements[891]["obtained"])	// Mapping the Realm: The Second Coil of Bahamut II
			{
				$SCoBTurns["Turn 2"]["explored"] = TRUE;
				$SCoBTurns["Turn 2"]["date"] = $ExplorationAchievements[891]["date"];
			}
			if ($ExplorationAchievements[892]["obtained"])	// Mapping the Realm: The Second Coil of Bahamut III
			{
				$SCoBTurns["Turn 3"]["explored"] = TRUE;
				$SCoBTurns["Turn 3"]["date"] = $ExplorationAchievements[892]["date"];
			}

			// --- Full Clears --- \\
			if ($BattleAchievements[887]["obtained"])	// In Another Bind I
				$Raids["Second Coil of Bahamut"] = array("cleared" => TRUE, "date" => $BattleAchievements[887]["date"], "times" => 1);
			if ($BattleAchievements[888]["obtained"])	// In Another Bind II
			{
				$Raids["Second Coil of Bahamut"]["first"] = $BattleAchievements[887]["date"];
				$Raids["Second Coil of Bahamut"]["date"] = $BattleAchievements[888]["date"];
				$Raids["Second Coil of Bahamut"]["times"] = 5;
			}
			if ($BattleAchievements[889]["obtained"])	// In Another Bind III
			{
				$Raids["Second Coil of Bahamut"]["date"] = $BattleAchievements[889]["date"];
				$Raids["Second Coil of Bahamut"]["times"] = 10;
			}

			$Raids["Second Coil of Bahamut"]["turns"] = $SCoBTurns;

			// ----- EX Primals ----- \\
			// Ifrit EX.
			if ($BattleAchievements[855]["obtained"])	// Going Up in Flames
				$EXPrimals["Bowl of Embers"] = array("cleared" => TRUE, "date" => $BattleAchievements[855]["date"]);
			// Garuda EX.
			if ($BattleAchievements[856]["obtained"])	// Gone with the Wind
				$EXPrimals["Howling Eye"] = array("cleared" => TRUE, "date" => $BattleAchievements[856]["date"]);
			// Titan EX.
			if ($BattleAchievements[857]["obtained"])	// Earth to Earth
				$EXPrimals["Navel"] = array("cleared" => TRUE, "date" => $BattleAchievements[857]["date"]);
			// Leviathan EX.
			if ($BattleAchievements[893]["obtained"])	// I Eat Whorls for Breakfast
				$EXPrimals["Whorleater"] = array("cleared" => TRUE, "date" => $BattleAchievements[893]["date"]);
			// Good King Mog EX.
			if ($BattleAchievements[894]["obtained"])	// Good Kingslayer
				$EXPrimals["Thornmarch"] = array("cleared" => TRUE, "date" => $BattleAchievements[894]["date"]);

			$Progression["Raids"] = $Raids;
			$Progression["EX Primals"] = $EXPrimals;
		}
		else
		{
			$Progression = array("Error" => "This character does not have public achievement viewing enabled.");
		}
	}
	else
	{
		$Progression = array("Error" => "Character not found.");
	}

	// Display in JSON.
	echo json_encode($Progression);
?>