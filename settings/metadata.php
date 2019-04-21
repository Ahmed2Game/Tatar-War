<?php
$speed = ($gameConfig['settings']['speed']);
$map = ($gameConfig['settings']['map']);
$attack = ($gameConfig['settings']['attack']);
$Market = ($gameConfig['settings']['market']);
$capacity = ($gameConfig['settings']['capacity']);
$cranny = ($gameConfig['settings']['cranny']);
$protection = ($gameConfig['settings']['protection'] * 3600 * $speed);
$cp = ($gameConfig['settings']['cp']);
$plus1 = ($gameConfig['plus']['plus1']) * $speed;
$plus2 = ($gameConfig['plus']['plus2']) * $speed;
$plus3 = ($gameConfig['plus']['plus3']);
$plus4 = ($gameConfig['plus']['plus4']);
$plus5 = ($gameConfig['plus']['plus5']);
$plus6 = ($gameConfig['plus']['plus6']);
$plus7 = ($gameConfig['plus']['plus7']);
$plus8 = ($gameConfig['plus']['plus8']);
$plus9 = ($gameConfig['plus']['plus9']);
$plus10 = ($gameConfig['plus']['plus10'] * $speed);
$piyadeh = ($gameConfig['settings']['piyadeh']);
$savareh = ($gameConfig['settings']['savareh']);
$shovalieh = ($gameConfig['settings']['shovalieh']);
$osiss1 = ($gameConfig['settings']['osiss1']);
$osiss2 = ($gameConfig['settings']['osiss2']);
$moared = $gameConfig['settings']['moared'];

$SetupMetadata = array(

    /**
     * the game map size
     */

    'map_size' => $map,            // ranged from 400 to -400


    /**
     *  the maps for the village field
     *    key is the name of the image for the field
     *    the array value carry the items id only for the resources
     *    1 = wood, 2 = clay, 3 = iron, 4 = crop
     */

    'field_maps_summary' => array(

        '1' => '3-3-3-9',

        '2' => '3-4-5-6',

        '3' => '4-4-4-6',

        '4' => '4-5-3-6',

        '5' => '5-3-4-6',

        '6' => '1-1-1-15',

        '7' => '4-4-3-7',

        '8' => '3-4-4-7',

        '9' => '4-3-4-7',

        '10' => '3-5-4-6',

        '11' => '4-3-5-6',

        '12' => '5-4-3-6'

    ),

    'field_maps' => array(

        '1' => array(4, 4, 1, 4, 4, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '2' => array(3, 4, 1, 3, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '3' => array(1, 4, 1, 3, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),            // registable village ( default village )

        '4' => array(1, 4, 1, 2, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '5' => array(1, 4, 1, 3, 1, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '6' => array(4, 4, 1, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 2, 4, 4),            // crop village

        '7' => array(1, 4, 4, 1, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '8' => array(3, 4, 4, 1, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '9' => array(3, 4, 4, 1, 1, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '10' => array(3, 4, 1, 2, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2),

        '11' => array(3, 1, 1, 3, 1, 4, 4, 3, 3, 2, 2, 3, 1, 4, 4, 2, 4, 4),

        '12' => array(1, 4, 1, 1, 2, 2, 3, 4, 4, 3, 3, 4, 4, 1, 4, 2, 1, 2)

    ),


    /**
     * the type of oases
     *   ex: 1 => 25 mean, Increase +25% lumber per hour
     */

    'oasis' => array(

        '1' => array('1' => $osiss1),

        '2' => array('1' => $osiss1),

        '3' => array('1' => $osiss1, '4' => $osiss1),

        '4' => array('2' => $osiss1),

        '5' => array('2' => $osiss1),

        '6' => array('2' => $osiss1, '4' => $osiss1),

        '7' => array('3' => $osiss1),

        '8' => array('3' => $osiss1),

        '9' => array('3' => $osiss1, '4' => $osiss1),

        '10' => array('4' => $osiss1),

        '11' => array('4' => $osiss1),

        '12' => array('4' => $osiss2)

    ),

);


$GameMetadata = array(

    /**
     * the speed of the game
     *   this speed value affect the consumed time for building, troop training and troop researching
     *   the default value is 1,
     *   the value 3 mean the consumped time will be less than the default time by ratio 1/3
     */

    'game_speed' => $speed,


    /**
     * the needed civil points (depend upon the game speed)
     *   for each new village
     */

    'cp_for_new_village' => $cp * $speed,


    /**
     * the player protection period (depend upon the game speed)
     *  this is the normal protection period for the player after registration
     *  unit in seconds
     */

    'player_protection_period' => $protection,            // 3 days = (3 * 24 * 60 * 60) = 259200


    /**
     * the player session time out
     * unit in minute
     */

    'session_timeout' => 1440,


    /**
     *  tribes of the game
     *    key is the id for the tribe
     *    dual_build: can build or upgrade by default a field and building at the same time
     *    merchants_capacity: the default capacity for the merchants
     *    merchants_velocity: the default velocity for the merchants
     *    crannyFactor: the enemy can only save this factor from its total cranny size
     *    wall_css: the css name for the wall that used in the village2.php page
     */

    'tribes' => array(

        '1' => array('dual_build' => TRUE, 'merchants_capacity' => ($Market * 500) / $speed, 'merchants_velocity' => ($attack * 46) / $speed, 'crannyFactor' => 1, 'wall_css' => 'd2_11'),            // الرومان

        '2' => array('dual_build' => TRUE, 'merchants_capacity' => ($Market * 2000) / $speed, 'merchants_velocity' => ($attack * 42) / $speed, 'crannyFactor' => 2 / 3, 'wall_css' => 'd2_12'),            // الجرمان

        '3' => array('dual_build' => TRUE, 'merchants_capacity' => ($Market * 1750) / $speed, 'merchants_velocity' => ($attack * 44) / $speed, 'crannyFactor' => 1, 'wall_css' => 'd2_1'),            // الإغريق

        '4' => array('dual_build' => FALSE, 'merchants_capacity' => 0, 'merchants_velocity' => 0, 'crannyFactor' => 1, 'wall_css' => ''),            // الوحوش

        '5' => array('dual_build' => FALSE, 'merchants_capacity' => 0, 'merchants_velocity' => 0, 'crannyFactor' => 1, 'wall_css' => ''),            // التتار

        '7' => array('dual_build' => TRUE, 'merchants_capacity' => ($Market * 2200) / $speed, 'merchants_velocity' => ($attack * 50) / $speed, 'crannyFactor' => 1, 'wall_css' => 'd2_11'),            // العرب

    ),


    /**
     * the time and gold number need for plus feature
     */

    'plusTable' => array(

        array('time' => ($plus1), 'cost' => $plus3),

        array('time' => ($plus2), 'cost' => $plus4),

        array('time' => ($plus2), 'cost' => $plus4),

        array('time' => ($plus2), 'cost' => $plus4),

        array('time' => ($plus2), 'cost' => $plus4),

        array('time' => 0, 'cost' => $plus5),

        array('time' => 0, 'cost' => $plus6),

        array('time' => 0, 'cost' => $plus7),

        array('time' => 0, 'cost' => $plus8),

        array('time' => $plus10, 'cost' => $plus9)

    ),


    /**
     * the type of medals
     *   ex: bbcode = 0 , then it write like that [#0]
     */

    'medals' => array(

        array('textIndex' => 0, 'BBCode' => '0', 'cssClass' => 'tn'),


        // for players

        array('textIndex' => 1, 'BBCode' => '10000', 'cssClass' => 't1'),            // مطوروا الأسبوع

        array('textIndex' => 2, 'BBCode' => '20000', 'cssClass' => 't2'),            // مهاجموا الأسبوع

        array('textIndex' => 3, 'BBCode' => '30000', 'cssClass' => 't3'),            // مدافعوا الأسبوع

        array('textIndex' => 4, 'BBCode' => '40000', 'cssClass' => 't4'),            // سارقوا الأسبوع


        // for alliance

        array('textIndex' => 1, 'BBCode' => '50000', 'cssClass' => 'a1'),        // مطوروا الأسبوع

        array('textIndex' => 2, 'BBCode' => '60000', 'cssClass' => 'a2'),        // مهاجموا الأسبوع

        array('textIndex' => 3, 'BBCode' => '70000', 'cssClass' => 'a3'),        // مدافعوا الأسبوع

        array('textIndex' => 4, 'BBCode' => '80000', 'cssClass' => 'a4'),        // سارقوا الأسبوع

    ),


    /**
     *  the troops for all tribes
     *    key is the id of the troop
     *    for_tribe_id: = the tribe id, so this troop only available for this tribe
     *    velocity: the default speed for the troop
     *    carry_load: the value that troop can harvest from the enemy resources at attack
     *    crop_consumption: consumption of crop from the village crop production rate
     *    attack_value: the strength of attack
     *    defense_infantry: the defense against the non cavalry troops
     *    defense_cavalry: the defense against the cavalry troops
     *    is_cavalry: is a cavalry troop
     *    gold_needed: the need golds to buy only one troop
     *    research_time_consume: the time in seconds, that consumed to do researching for that troop in academy building
     *    training_time_consume: the time in seconds, that consumed to do training for that troop in war building
     *    trainer_building: the item(s) id for building that do training for that troop
     *    research_resources: the resources that needed for researching, 1=wood 2=clay 3=iron 4=crop
     *    training_resources: the resources that needed for training, 1=wood 2=clay 3=iron 4=crop
     *    pre_requests: the pre-requests to do researching for that troop ( item_id => item_level )
     */

    'troops' => array(
        '1' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 6) / $speed, 'carry_load' => 40, 'crop_consumption' => 1, 'attack_value' => 40, 'defense_infantry' => 35, 'defense_cavalry' => 50, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 0,

            'training_time_consume' => 2000,

            'trainer_building' => array(19, 29),

            'research_resources' => array(),

            'training_resources' => array('1' => 120, '2' => 100, '3' => 180, '4' => 40),

            'pre_requests' => array()

        ),


        '2' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 20, 'crop_consumption' => 1, 'attack_value' => 30, 'defense_infantry' => 65, 'defense_cavalry' => 35, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 8400,

            'training_time_consume' => 2200,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 700, '2' => 620, '3' => 1480, '4' => 580),

            'training_resources' => array('1' => 100, '2' => 130, '3' => 160, '4' => 70),

            'pre_requests' => array('22' => 1, '13' => 1)

        ),


        '3' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 7) / $speed, 'carry_load' => 50, 'crop_consumption' => 1, 'attack_value' => 70, 'defense_infantry' => 40, 'defense_cavalry' => 25, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 9000,

            'training_time_consume' => 2400,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 1000, '2' => 740, '3' => 1880, '4' => 640),

            'training_resources' => array('1' => 150, '2' => 160, '3' => 210, '4' => 80),

            'pre_requests' => array('22' => 5, '12' => 1)

        ),


        '4' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 16) / $speed, 'carry_load' => 0, 'crop_consumption' => 2, 'attack_value' => 0, 'defense_infantry' => 20, 'defense_cavalry' => 10, 'is_cavalry' => TRUE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6900,

            'training_time_consume' => 1700,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 940, '2' => 740, '3' => 360, '4' => 400),

            'training_resources' => array('1' => 140, '2' => 160, '3' => 20, '4' => 40),

            'pre_requests' => array('20' => 1, '22' => 5)

        ),


        '5' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 14) / $speed, 'carry_load' => 100, 'crop_consumption' => 3, 'attack_value' => 120, 'defense_infantry' => 65, 'defense_cavalry' => 50, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 11700,

            'training_time_consume' => 3300,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 3400, '2' => 1860, '3' => 2760, '4' => 760),

            'training_resources' => array('1' => 550, '2' => 440, '3' => 320, '4' => 100),

            'pre_requests' => array('20' => 5, '22' => 5)

        ),


        '6' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 10) / $speed, 'carry_load' => 70, 'crop_consumption' => 4, 'attack_value' => 180, 'defense_infantry' => 80, 'defense_cavalry' => 105, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 15000,

            'training_time_consume' => 4400,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 3400, '2' => 2660, '3' => 6600, '4' => 1240),

            'training_resources' => array('1' => 550, '2' => 640, '3' => 800, '4' => 180),

            'pre_requests' => array('20' => 10, '22' => 5)

        ),


        '7' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 60, 'defense_infantry' => 30, 'defense_cavalry' => 75, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 15600,

            'training_time_consume' => 4600,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5500, '2' => 1540, '3' => 4200, '4' => 580),

            'training_resources' => array('1' => 900, '2' => 360, '3' => 500, '4' => 70),

            'pre_requests' => array('22' => 10, '21' => 1)

        ),


        '8' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 3) / $speed, 'carry_load' => 0, 'crop_consumption' => 6, 'attack_value' => 75, 'defense_infantry' => 60, 'defense_cavalry' => 10, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 28800,

            'training_time_consume' => 9000,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5800, '2' => 5500, '3' => 5000, '4' => 700),

            'training_resources' => array('1' => 950, '2' => 1350, '3' => 600, '4' => 90),

            'pre_requests' => array('21' => 10, '22' => 15)

        ),


        '9' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 5, 'attack_value' => 50, 'defense_infantry' => 40, 'defense_cavalry' => 30, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 24475,

            'training_time_consume' => 90700,

            'trainer_building' => array(25, 26),

            'research_resources' => array('1' => 15880, '2' => 13800, '3' => 36400, '4' => 22660),

            'training_resources' => array('1' => 30750, '2' => 27200, '3' => 45000, '4' => 37500),

            'pre_requests' => array('16' => 10, '22' => 20)

        ),


        '10' => array(

            'for_tribe_id' => 1, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 3000, 'crop_consumption' => 1, 'attack_value' => 0, 'defense_infantry' => 80, 'defense_cavalry' => 80, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 26900,

            'trainer_building' => array(25, 26),

            'research_resources' => array(),

            'training_resources' => array('1' => 5800, '2' => 5300, '3' => 7200, '4' => 5500),

            'pre_requests' => array(),

            'help_pre_requests' => array('26|25' => 10)

        ),


        '11' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 7) / $speed, 'carry_load' => 60, 'crop_consumption' => 1, 'attack_value' => 40, 'defense_infantry' => 20, 'defense_cavalry' => 5, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 0,

            'training_time_consume' => 900,

            'trainer_building' => array(19, 29),

            'research_resources' => array(),

            'training_resources' => array('1' => 95, '2' => 75, '3' => 40, '4' => 40),

            'pre_requests' => array()

        ),


        '12' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 7) / $speed, 'carry_load' => 40, 'crop_consumption' => 1, 'attack_value' => 10, 'defense_infantry' => 35, 'defense_cavalry' => 60, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6000,

            'training_time_consume' => 1400,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 970, '2' => 380, '3' => 880, '4' => 400),

            'training_resources' => array('1' => 145, '2' => 70, '3' => 85, '4' => 40),

            'pre_requests' => array('22' => 1)

        ),


        '13' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 6) / $speed, 'carry_load' => 50, 'crop_consumption' => 1, 'attack_value' => 60, 'defense_infantry' => 30, 'defense_cavalry' => 30, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6300,

            'training_time_consume' => 1500,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 880, '2' => 580, '3' => 1560, '4' => 580),

            'training_resources' => array('1' => 130, '2' => 120, '3' => 170, '4' => 70),

            'pre_requests' => array('22' => 3, '12' => 1)

        ),


        '14' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 9) / $speed, 'carry_load' => 0, 'crop_consumption' => 1, 'attack_value' => 0, 'defense_infantry' => 10, 'defense_cavalry' => 5, 'is_cavalry' => TRUE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6000,

            'training_time_consume' => 1400,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 1060, '2' => 500, '3' => 600, '4' => 460),

            'training_resources' => array('1' => 160, '2' => 100, '3' => 50, '4' => 50),

            'pre_requests' => array('22' => 1, '15' => 5)

        ),


        '15' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 10) / $speed, 'carry_load' => 110, 'crop_consumption' => 2, 'attack_value' => 55, 'defense_infantry' => 100, 'defense_cavalry' => 40, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 10800,

            'training_time_consume' => 3000,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2320, '2' => 1180, '3' => 2520, '4' => 610),

            'training_resources' => array('1' => 370, '2' => 270, '3' => 290, '4' => 75),

            'pre_requests' => array('22' => 5, '20' => 3)

        ),


        '16' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 9) / $speed, 'carry_load' => 80, 'crop_consumption' => 3, 'attack_value' => 150, 'defense_infantry' => 50, 'defense_cavalry' => 75, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 12900,

            'training_time_consume' => 3700,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2800, '2' => 2160, '3' => 4040, '4' => 640),

            'training_resources' => array('1' => 450, '2' => 515, '3' => 480, '4' => 80),

            'pre_requests' => array('20' => 10, '22' => 15)

        ),


        '17' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 65, 'defense_infantry' => 30, 'defense_cavalry' => 80, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 14400,

            'training_time_consume' => 4200,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 6100, '2' => 1300, '3' => 3000, '4' => 580),

            'training_resources' => array('1' => 1000, '2' => 300, '3' => 350, '4' => 70),

            'pre_requests' => array('22' => 10, '21' => 1)

        ),


        '18' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 3) / $speed, 'carry_load' => 0, 'crop_consumption' => 6, 'attack_value' => 50, 'defense_infantry' => 60, 'defense_cavalry' => 10, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 28800,

            'training_time_consume' => 9000,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5500, '2' => 4900, '3' => 5000, '4' => 520),

            'training_resources' => array('1' => 900, '2' => 1200, '3' => 600, '4' => 60),

            'pre_requests' => array('21' => 10, '22' => 15)

        ),


        '19' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 4, 'attack_value' => 40, 'defense_infantry' => 60, 'defense_cavalry' => 40, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 19425,

            'training_time_consume' => 70500,

            'trainer_building' => array(25, 26),

            'research_resources' => array('1' => 18250, '2' => 13500, '3' => 20400, '4' => 16480),

            'training_resources' => array('1' => 35500, '2' => 26600, '3' => 25000, '4' => 27200),

            'pre_requests' => array('16' => 5, '22' => 20)

        ),

        '20' => array(

            'for_tribe_id' => 2, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 3000, 'crop_consumption' => 1, 'attack_value' => 10, 'defense_infantry' => 80, 'defense_cavalry' => 80, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 31000,

            'trainer_building' => array(25, 26),

            'research_resources' => array(),

            'training_resources' => array('1' => 7200, '2' => 5500, '3' => 5800, '4' => 6500),

            'pre_requests' => array(),

            'help_pre_requests' => array('26|25' => 10)

        ),


        '21' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 7) / $speed, 'carry_load' => 30, 'crop_consumption' => 1, 'attack_value' => 15, 'defense_infantry' => 40, 'defense_cavalry' => 50, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 0,

            'training_time_consume' => 1300,

            'trainer_building' => array(19, 29),

            'research_resources' => array(),

            'training_resources' => array('1' => 100, '2' => 130, '3' => 55, '4' => 30),

            'pre_requests' => array()

        ),


        '22' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 6) / $speed, 'carry_load' => 45, 'crop_consumption' => 1, 'attack_value' => 65, 'defense_infantry' => 35, 'defense_cavalry' => 20, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 7200,

            'training_time_consume' => 1800,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 940, '2' => 700, '3' => 1680, '4' => 520),

            'training_resources' => array('1' => 140, '2' => 150, '3' => 185, '4' => 60),

            'pre_requests' => array('22' => 1, '12' => 1)

        ),


        '23' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 17) / $speed, 'carry_load' => 0, 'crop_consumption' => 2, 'attack_value' => 0, 'defense_infantry' => 20, 'defense_cavalry' => 10, 'is_cavalry' => TRUE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6900,

            'training_time_consume' => 1700,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 1120, '2' => 700, '3' => 360, '4' => 400),

            'training_resources' => array('1' => 170, '2' => 150, '3' => 20, '4' => 40),

            'pre_requests' => array('22' => 5, '20' => 1)

        ),


        '24' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 19) / $speed, 'carry_load' => 75, 'crop_consumption' => 2, 'attack_value' => 90, 'defense_infantry' => 25, 'defense_cavalry' => 40, 'is_cavalry' => TRUE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 11100,

            'training_time_consume' => 3100,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2200, '2' => 1900, '3' => 2040, '4' => 520),

            'training_resources' => array('1' => 350, '2' => 450, '3' => 230, '4' => 60),

            'pre_requests' => array('20' => 3, '22' => 5)

        ),


        '25' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 16) / $speed, 'carry_load' => 35, 'crop_consumption' => 2, 'attack_value' => 45, 'defense_infantry' => 115, 'defense_cavalry' => 55, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 11400,

            'training_time_consume' => 3200,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2260, '2' => 1420, '3' => 2440, '4' => 880),

            'training_resources' => array('1' => 360, '2' => 330, '3' => 280, '4' => 120),

            'pre_requests' => array('20' => 5, '22' => 5)

        ),


        '26' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 13) / $speed, 'carry_load' => 65, 'crop_consumption' => 3, 'attack_value' => 140, 'defense_infantry' => 50, 'defense_cavalry' => 165, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 13500,

            'training_time_consume' => 3900,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 3100, '2' => 2580, '3' => 5600, '4' => 1180),

            'training_resources' => array('1' => 500, '2' => 620, '3' => 675, '4' => 170),

            'pre_requests' => array('20' => 10, '22' => 15)

        ),


        '27' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 50, 'defense_infantry' => 30, 'defense_cavalry' => 105, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 16800,

            'training_time_consume' => 5000,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5800, '2' => 2320, '3' => 2840, '4' => 610),

            'training_resources' => array('1' => 950, '2' => 555, '3' => 330, '4' => 75),

            'pre_requests' => array('22' => 10, '21' => 1)

        ),


        '28' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 3) / $speed, 'carry_load' => 0, 'crop_consumption' => 6, 'attack_value' => 70, 'defense_infantry' => 45, 'defense_cavalry' => 10, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 28800,

            'training_time_consume' => 9000,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5860, '2' => 5900, '3' => 5240, '4' => 700),

            'training_resources' => array('1' => 960, '2' => 1450, '3' => 630, '4' => 90),

            'pre_requests' => array('21' => 10, '22' => 15)

        ),


        '29' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 0, 'crop_consumption' => 4, 'attack_value' => 40, 'defense_infantry' => 50, 'defense_cavalry' => 50, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 24475,

            'training_time_consume' => 90700,

            'trainer_building' => array(25, 26),

            'research_resources' => array('1' => 15880, '2' => 22900, '3' => 25200, '4' => 22660),

            'training_resources' => array('1' => 30750, '2' => 45400, '3' => 31000, '4' => 37500),

            'pre_requests' => array('16' => 10, '22' => 20)

        ),


        '30' => array(

            'for_tribe_id' => 3, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 3000, 'crop_consumption' => 1, 'attack_value' => 0, 'defense_infantry' => 80, 'defense_cavalry' => 80, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 22700,

            'trainer_building' => array(25, 26),

            'research_resources' => array(),

            'training_resources' => array('1' => 5500, '2' => 7000, '3' => 5300, '4' => 4900),

            'pre_requests' => array(),

            'help_pre_requests' => array('26|25' => 10)

        ),


        '31' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 1, 'attack_value' => 10, 'defense_infantry' => 25, 'defense_cavalry' => 20, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '32' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 1, 'attack_value' => 20, 'defense_infantry' => 35, 'defense_cavalry' => 40, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '33' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 1, 'attack_value' => 60, 'defense_infantry' => 40, 'defense_cavalry' => 60, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '34' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 1, 'attack_value' => 80, 'defense_infantry' => 66, 'defense_cavalry' => 50, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '35' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 2, 'attack_value' => 50, 'defense_infantry' => 70, 'defense_cavalry' => 33, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '36' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 2, 'attack_value' => 100, 'defense_infantry' => 80, 'defense_cavalry' => 70, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '37' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 250, 'defense_infantry' => 140, 'defense_cavalry' => 200, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '38' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 450, 'defense_infantry' => 380, 'defense_cavalry' => 240, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '39' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 200, 'defense_infantry' => 170, 'defense_cavalry' => 250, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '40' => array(

            'for_tribe_id' => 4, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 5, 'attack_value' => 600, 'defense_infantry' => 440, 'defense_cavalry' => 520, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '41' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 30) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 100, 'defense_infantry' => 110, 'defense_cavalry' => 310, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '42' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 40) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 120, 'defense_infantry' => 220, 'defense_cavalry' => 330, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '43' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 13) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 100, 'defense_infantry' => 310, 'defense_cavalry' => 170, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '44' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 60) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 200, 'defense_infantry' => 200, 'defense_cavalry' => 200, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '45' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 70) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 300, 'defense_infantry' => 430, 'defense_cavalry' => 210, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '46' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 30) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 120, 'defense_infantry' => 210, 'defense_cavalry' => 300, 'is_cavalry' => TRUE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '47' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 30) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 190, 'defense_infantry' => 210, 'defense_cavalry' => 100, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '48' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 25) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 100, 'defense_infantry' => 70, 'defense_cavalry' => 80, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '49' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 13) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 90, 'defense_infantry' => 120, 'defense_cavalry' => 130, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '50' => array(

            'for_tribe_id' => 5, 'velocity' => ($attack * 40) / $speed, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 70, 'defense_infantry' => 100, 'defense_cavalry' => 120, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 0,

            'trainer_building' => array(),

            'research_resources' => array(),

            'training_resources' => array(),

            'pre_requests' => array()

        ),


        '99' => array(

            'for_tribe_id' => -1 /* for all tribes */, 'velocity' => 0, 'carry_load' => 0, 'crop_consumption' => 0, 'attack_value' => 0, 'defense_infantry' => 0, 'defense_cavalry' => 0, 'is_cavalry' => NULL,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 210,

            'trainer_building' => array(36),

            'research_resources' => array(),

            'training_resources' => array('1' => 20, '2' => 30, '3' => 10, '4' => 20),

            'pre_requests' => array()

        ),


        '100' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 6) / $speed, 'carry_load' => 50, 'crop_consumption' => 1, 'attack_value' => 20, 'defense_infantry' => 35, 'defense_cavalry' => 50, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 0,

            'training_time_consume' => 1824,

            'trainer_building' => array(19, 29),

            'research_resources' => array(),

            'training_resources' => array('1' => 180, '2' => 150, '3' => 225, '4' => 45),

            'pre_requests' => array()

        ),

        '101' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 45, 'crop_consumption' => 1, 'attack_value' => 65, 'defense_infantry' => 30, 'defense_cavalry' => 10, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6000,

            'training_time_consume' => 2000,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 880, '2' => 580, '3' => 1560, '4' => 580),

            'training_resources' => array('1' => 150, '2' => 195, '3' => 240, '4' => 105),

            'pre_requests' => array('22' => 3, '12' => 1)

        ),

        '102' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 7) / $speed, 'carry_load' => 50, 'crop_consumption' => 1, 'attack_value' => 90, 'defense_infantry' => 40, 'defense_cavalry' => 30, 'is_cavalry' => FALSE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 6300,

            'training_time_consume' => 2160,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 880, '2' => 580, '3' => 1560, '4' => 580),

            'training_resources' => array('1' => 225, '2' => 240, '3' => 315, '4' => 120),

            'pre_requests' => array('22' => 10, '12' => 1)

        ),

        '103' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 20) / $speed, 'carry_load' => 0, 'crop_consumption' => 2, 'attack_value' => 0, 'defense_infantry' => 10, 'defense_cavalry' => 0, 'is_cavalry' => TRUE,

            'gold_needed' => $piyadeh,

            'research_time_consume' => 7000,

            'training_time_consume' => 1360,

            'trainer_building' => array(19, 29),

            'research_resources' => array('1' => 1060, '2' => 500, '3' => 600, '4' => 460),

            'training_resources' => array('1' => 210, '2' => 240, '3' => 30, '4' => 60),

            'pre_requests' => array('22' => 1, '15' => 5)

        ),

        '104' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 14) / $speed, 'carry_load' => 100, 'crop_consumption' => 3, 'attack_value' => 155, 'defense_infantry' => 80, 'defense_cavalry' => 50, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 10800,

            'training_time_consume' => 3240,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2320, '2' => 1180, '3' => 2520, '4' => 610),

            'training_resources' => array('1' => 825, '2' => 660, '3' => 480, '4' => 150),

            'pre_requests' => array('22' => 5, '20' => 5)

        ),

        '105' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 10) / $speed, 'carry_load' => 65, 'crop_consumption' => 4, 'attack_value' => 170, 'defense_infantry' => 140, 'defense_cavalry' => 80, 'is_cavalry' => TRUE,

            'gold_needed' => $savareh,

            'research_time_consume' => 12900,

            'training_time_consume' => 4120,

            'trainer_building' => array(20, 30),

            'research_resources' => array('1' => 2800, '2' => 2160, '3' => 4040, '4' => 640),

            'training_resources' => array('1' => 825, '2' => 960, '3' => 1200, '4' => 270),

            'pre_requests' => array('22' => 15, '20' => 10)

        ),

        '106' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 3, 'attack_value' => 60, 'defense_infantry' => 30, 'defense_cavalry' => 75, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 14400,

            'training_time_consume' => 6400,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 6100, '2' => 1300, '3' => 3000, '4' => 580),

            'training_resources' => array('1' => 1350, '2' => 540, '3' => 750, '4' => 105),

            'pre_requests' => array('22' => 10, '21' => 1)

        ),

        '107' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 3) / $speed, 'carry_load' => 0, 'crop_consumption' => 6, 'attack_value' => 60, 'defense_infantry' => 45, 'defense_cavalry' => 10, 'is_cavalry' => FALSE,

            'gold_needed' => $shovalieh,

            'research_time_consume' => 28800,

            'training_time_consume' => 10800,

            'trainer_building' => array(21),

            'research_resources' => array('1' => 5500, '2' => 4900, '3' => 5000, '4' => 520),

            'training_resources' => array('1' => 1425, '2' => 2025, '3' => 900, '4' => 135),

            'pre_requests' => array('21' => 10, '22' => 15)

        ),

        '108' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 4) / $speed, 'carry_load' => 0, 'crop_consumption' => 5, 'attack_value' => 80, 'defense_infantry' => 50, 'defense_cavalry' => 50, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 19425,

            'training_time_consume' => 94300,

            'trainer_building' => array(25, 26),

            'research_resources' => array('1' => 18250, '2' => 13500, '3' => 20400, '4' => 16480),

            'training_resources' => array('1' => 46125, '2' => 40800, '3' => 67500, '4' => 65250),

            'pre_requests' => array('16' => 10, '22' => 20)

        ),

        '109' => array(

            'for_tribe_id' => 7, 'velocity' => ($attack * 5) / $speed, 'carry_load' => 3000, 'crop_consumption' => 1, 'attack_value' => 30, 'defense_infantry' => 40, 'defense_cavalry' => 40, 'is_cavalry' => FALSE,

            'gold_needed' => 0,

            'research_time_consume' => 0,

            'training_time_consume' => 30500,

            'trainer_building' => array(25, 26),

            'research_resources' => array(),

            'training_resources' => array('1' => 8700, '2' => 7950, '3' => 10800, '4' => 8250),

            'pre_requests' => array(),

            'help_pre_requests' => array('26|25' => 10)

        )

    ),


    /**
     *  the game items ( buildings and fields )
     *    key is the id of the item
     *    support_multiple: can build extra-items of it, only when building at least one item and reaching it to its maximum level
     *    built_in_capital: can build into capital village
     *    built_in_non_capital: can build into none capital village
     *    built_in_special_only: buld only into the special village
     *    max_lvl_in_non_capital: maximum level can be reached into the non capital village, if equal NULL this mean that maximum level in non capital as in the capital village
     *    for_tribe_id: array for tribes ( tribe_id => value factor )
     *    pre_requests: array for ( item_id => item level ), if item level is equal NULL then to build this item the null item must not be exists
     *    levels:
     *        array for item levels
     */

    'items' => array(

        '1' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => 10,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 5 * $moared, 'time_consume' => 260, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 40, '2' => 100, '3' => 50, '4' => 60)

                ),


                array(

                    'value' => 9 * $moared, 'time_consume' => 1020, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 65, '2' => 165, '3' => 85, '4' => 100)

                ),


                array(

                    'value' => 15 * $moared, 'time_consume' => 3190, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 110, '2' => 280, '3' => 140, '4' => 165)

                ),


                array(

                    'value' => 22 * $moared, 'time_consume' => 7100, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 185, '2' => 465, '3' => 235, '4' => 280)

                ),


                array(

                    'value' => 33 * $moared, 'time_consume' => 15560, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 310, '2' => 780, '3' => 390, '4' => 465)

                ),


                array(

                    'value' => 50 * $moared, 'time_consume' => 25890, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 520, '2' => 1300, '3' => 650, '4' => 780)

                ),


                array(

                    'value' => 70 * $moared, 'time_consume' => 40620, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 870, '2' => 2170, '3' => 1085, '4' => 1300)

                ),


                array(

                    'value' => 100 * $moared, 'time_consume' => 55590, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 1450, '2' => 3625, '3' => 1810, '4' => 2175)

                ),


                array(

                    'value' => 145 * $moared, 'time_consume' => 70150, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 2420, '2' => 6050, '3' => 3025, '4' => 3630)

                ),


                array(

                    'value' => 200 * $moared, 'time_consume' => 100440, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 4040, '2' => 10105, '3' => 5050, '4' => 6060)

                ),


                array(

                    'value' => 280 * $moared, 'time_consume' => 130900, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 6750, '2' => 16870, '3' => 8435, '4' => 10125)

                ),


                array(

                    'value' => 375 * $moared, 'time_consume' => 164050, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 11270, '2' => 28175, '3' => 14090, '4' => 16905)

                ),


                array(

                    'value' => 495 * $moared, 'time_consume' => 206680, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 18820, '2' => 47055, '3' => 23525, '4' => 28230)

                ),


                array(

                    'value' => 635 * $moared, 'time_consume' => 266880, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 31430, '2' => 78580, '3' => 39290, '4' => 47150)

                ),


                array(

                    'value' => 800 * $moared, 'time_consume' => 427210, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 52490, '2' => 131230, '3' => 65615, '4' => 78740)

                ),


                array(

                    'value' => 1000 * $moared, 'time_consume' => 683730, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 87660, '2' => 219155, '3' => 109575, '4' => 131490)

                ),


                array(

                    'value' => 1300 * $moared, 'time_consume' => 1094170, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 146395, '2' => 365985, '3' => 182995, '4' => 219590)

                ),


                array(

                    'value' => 1600 * $moared, 'time_consume' => 1750880, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 244480, '2' => 611195, '3' => 305600, '4' => 366715)

                ),


                array(

                    'value' => 2000 * $moared, 'time_consume' => 2801600, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 408280, '2' => 1020695, '3' => 510350, '4' => 612420)

                ),


                array(

                    'value' => 2450 * $moared, 'time_consume' => 4482770, 'cp' => 38, 'people_inc' => 3,

                    'resources' => array('1' => 681825, '2' => 1704565, '3' => 852280, '4' => 1022740)

                ),


            )

        ),


        '2' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => 10,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 5 * $moared, 'time_consume' => 220, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 80, '2' => 40, '3' => 80, '4' => 50)

                ),


                array(

                    'value' => 9 * $moared, 'time_consume' => 1050, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 135, '2' => 65, '3' => 135, '4' => 85)

                ),


                array(

                    'value' => 15 * $moared, 'time_consume' => 3980, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 225, '2' => 110, '3' => 225, '4' => 140)

                ),


                array(

                    'value' => 22 * $moared, 'time_consume' => 6030, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 375, '2' => 185, '3' => 375, '4' => 235)

                ),


                array(

                    'value' => 33 * $moared, 'time_consume' => 9290, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 620, '2' => 310, '3' => 620, '4' => 390)

                ),


                array(

                    'value' => 50 * $moared, 'time_consume' => 20470, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 1040, '2' => 520, '3' => 1040, '4' => 650)

                ),


                array(

                    'value' => 70 * $moared, 'time_consume' => 45950, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 1735, '2' => 870, '3' => 1735, '4' => 1085)

                ),


                array(

                    'value' => 100 * $moared, 'time_consume' => 85520, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 2900, '2' => 1450, '3' => 2900, '4' => 1810)

                ),


                array(

                    'value' => 145 * $moared, 'time_consume' => 100430, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 4840, '2' => 2420, '3' => 4840, '4' => 3025)

                ),


                array(

                    'value' => 200 * $moared, 'time_consume' => 130690, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 8080, '2' => 4040, '3' => 8080, '4' => 5050)

                ),


                array(

                    'value' => 280 * $moared, 'time_consume' => 160510, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 13500, '2' => 6750, '3' => 13500, '4' => 8435)

                ),


                array(

                    'value' => 375 * $moared, 'time_consume' => 190010, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 22540, '2' => 11270, '3' => 22540, '4' => 14090)

                ),


                array(

                    'value' => 495 * $moared, 'time_consume' => 215420, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 37645, '2' => 18820, '3' => 37645, '4' => 23525)

                ),


                array(

                    'value' => 635 * $moared, 'time_consume' => 248870, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 62865, '2' => 31430, '3' => 62865, '4' => 39290)

                ),


                array(

                    'value' => 800 * $moared, 'time_consume' => 398390, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 104985, '2' => 52490, '3' => 104985, '4' => 65615)

                ),


                array(

                    'value' => 1000 * $moared, 'time_consume' => 637620, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 175320, '2' => 87660, '3' => 175320, '4' => 109575)

                ),


                array(

                    'value' => 1300 * $moared, 'time_consume' => 1020390, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 292790, '2' => 146395, '3' => 292790, '4' => 182995)

                ),


                array(

                    'value' => 1600 * $moared, 'time_consume' => 1632820, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 488955, '2' => 244480, '3' => 488955, '4' => 305600)

                ),


                array(

                    'value' => 2000 * $moared, 'time_consume' => 2612710, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 816555, '2' => 408280, '3' => 816555, '4' => 510350)

                ),


                array(

                    'value' => 2450 * $moared, 'time_consume' => 4180540, 'cp' => 38, 'people_inc' => 3,

                    'resources' => array('1' => 1363650, '2' => 681825, '3' => 1363650, '4' => 852280)

                ),


            )

        ),


        '3' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => 10,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 5 * $moared, 'time_consume' => 250, 'cp' => 1, 'people_inc' => 3,

                    'resources' => array('1' => 100, '2' => 80, '3' => 30, '4' => 60)

                ),


                array(

                    'value' => 9 * $moared, 'time_consume' => 1220, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 165, '2' => 135, '3' => 50, '4' => 100)

                ),


                array(

                    'value' => 15 * $moared, 'time_consume' => 3670, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 280, '2' => 225, '3' => 85, '4' => 165)

                ),


                array(

                    'value' => 22 * $moared, 'time_consume' => 6880, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 465, '2' => 375, '3' => 140, '4' => 280)

                ),


                array(

                    'value' => 33 * $moared, 'time_consume' => 12800, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 780, '2' => 620, '3' => 235, '4' => 465)

                ),


                array(

                    'value' => 50 * $moared, 'time_consume' => 20880, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 1300, '2' => 1040, '3' => 390, '4' => 780)

                ),


                array(

                    'value' => 70 * $moared, 'time_consume' => 27810, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 2170, '2' => 1735, '3' => 650, '4' => 1300)

                ),


                array(

                    'value' => 100 * $moared, 'time_consume' => 35690, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 3625, '2' => 2900, '3' => 1085, '4' => 2175)

                ),


                array(

                    'value' => 145 * $moared, 'time_consume' => 43310, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 6050, '2' => 4840, '3' => 1815, '4' => 3630)

                ),


                array(

                    'value' => 200 * $moared, 'time_consume' => 63500, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 10105, '2' => 8080, '3' => 3030, '4' => 6060)

                ),


                array(

                    'value' => 280 * $moared, 'time_consume' => 85800, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 16870, '2' => 13500, '3' => 5060, '4' => 10125)

                ),


                array(

                    'value' => 375 * $moared, 'time_consume' => 137470, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 28175, '2' => 22540, '3' => 8455, '4' => 16905)

                ),


                array(

                    'value' => 495 * $moared, 'time_consume' => 220160, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 47055, '2' => 37645, '3' => 14115, '4' => 28230)

                ),


                array(

                    'value' => 635 * $moared, 'time_consume' => 352450, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 78580, '2' => 62865, '3' => 23575, '4' => 47150)

                ),


                array(

                    'value' => 800 * $moared, 'time_consume' => 564120, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 131230, '2' => 104985, '3' => 39370, '4' => 78740)

                ),


                array(

                    'value' => 1000 * $moared, 'time_consume' => 902790, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 219155, '2' => 175320, '3' => 65745, '4' => 131490)

                ),


                array(

                    'value' => 1300 * $moared, 'time_consume' => 1444660, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 365985, '2' => 292790, '3' => 109795, '4' => 219590)

                ),


                array(

                    'value' => 1600 * $moared, 'time_consume' => 2311660, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 611195, '2' => 488955, '3' => 183360, '4' => 366715)

                ),


                array(

                    'value' => 2000 * $moared, 'time_consume' => 3698850, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 1020695, '2' => 816555, '3' => 306210, '4' => 612420)

                ),


                array(

                    'value' => 2450 * $moared, 'time_consume' => 5918370, 'cp' => 38, 'people_inc' => 3,

                    'resources' => array('1' => 1704565, '2' => 1363650, '3' => 511370, '4' => 1022740)

                ),


            )

        ),


        '4' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => 10,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 5 * $moared, 'time_consume' => 150, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 70, '2' => 90, '3' => 70, '4' => 20)

                ),


                array(

                    'value' => 9 * $moared, 'time_consume' => 1040, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 115, '2' => 150, '3' => 115, '4' => 35)

                ),


                array(

                    'value' => 15 * $moared, 'time_consume' => 3000, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 195, '2' => 250, '3' => 195, '4' => 55)

                ),


                array(

                    'value' => 22 * $moared, 'time_consume' => 5650, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 325, '2' => 420, '3' => 325, '4' => 95)

                ),


                array(

                    'value' => 33 * $moared, 'time_consume' => 10830, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 545, '2' => 700, '3' => 545, '4' => 155)

                ),


                array(

                    'value' => 50 * $moared, 'time_consume' => 15730, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 910, '2' => 1170, '3' => 910, '4' => 260)

                ),


                array(

                    'value' => 70 * $moared, 'time_consume' => 25780, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 1520, '2' => 1950, '3' => 1520, '4' => 435)

                ),


                array(

                    'value' => 100 * $moared, 'time_consume' => 32640, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 2535, '2' => 3260, '3' => 2535, '4' => 725)

                ),


                array(

                    'value' => 145 * $moared, 'time_consume' => 40430, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 4235, '2' => 5445, '3' => 4235, '4' => 1210)

                ),


                array(

                    'value' => 200 * $moared, 'time_consume' => 52880, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 7070, '2' => 9095, '3' => 7070, '4' => 2020)

                ),


                array(

                    'value' => 280 * $moared, 'time_consume' => 62810, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 11810, '2' => 15185, '3' => 11810, '4' => 3375)

                ),


                array(

                    'value' => 375 * $moared, 'time_consume' => 94700, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 19725, '2' => 25360, '3' => 19725, '4' => 5635)

                ),


                array(

                    'value' => 495 * $moared, 'time_consume' => 135710, 'cp' => 11, 'people_inc' => 1,

                    'resources' => array('1' => 32940, '2' => 42350, '3' => 32940, '4' => 9410)

                ),


                array(

                    'value' => 635 * $moared, 'time_consume' => 217340, 'cp' => 13, 'people_inc' => 1,

                    'resources' => array('1' => 55005, '2' => 70720, '3' => 55005, '4' => 15715)

                ),


                array(

                    'value' => 800 * $moared, 'time_consume' => 347950, 'cp' => 15, 'people_inc' => 1,

                    'resources' => array('1' => 91860, '2' => 118105, '3' => 91860, '4' => 26245)

                ),


                array(

                    'value' => 1000 * $moared, 'time_consume' => 556910, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 153405, '2' => 197240, '3' => 153405, '4' => 43830)

                ),


                array(

                    'value' => 1300 * $moared, 'time_consume' => 891260, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 256190, '2' => 329385, '3' => 256190, '4' => 73195)

                ),


                array(

                    'value' => 1600 * $moared, 'time_consume' => 1426210, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 427835, '2' => 550075, '3' => 427835, '4' => 122240)

                ),


                array(

                    'value' => 2000 * $moared, 'time_consume' => 2282140, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 714485, '2' => 918625, '3' => 714485, '4' => 204140)

                ),


                array(

                    'value' => 2450 * $moared, 'time_consume' => 3651630, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 1193195, '2' => 1534105, '3' => 1193195, '4' => 340915)

                ),


            )

        ),


        '5' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('1' => 10, '15' => 5),

            'levels' => array(


                array(

                    'value' => 5, 'time_consume' => 3000, 'cp' => 1, 'people_inc' => 4,

                    'resources' => array('1' => 520, '2' => 380, '3' => 290, '4' => 90)

                ),


                array(

                    'value' => 10, 'time_consume' => 5700, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 935, '2' => 685, '3' => 520, '4' => 160)

                ),


                array(

                    'value' => 15, 'time_consume' => 9750, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1685, '2' => 1230, '3' => 940, '4' => 290)

                ),


                array(

                    'value' => 20, 'time_consume' => 15830, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 3035, '2' => 2215, '3' => 1690, '4' => 525)

                ),


                array(

                    'value' => 25, 'time_consume' => 24940, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 5460, '2' => 3990, '3' => 3045, '4' => 945)

                ),


            )

        ),


        '6' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('2' => 10, '15' => 5),

            'levels' => array(


                array(

                    'value' => 5, 'time_consume' => 2240, 'cp' => 1, 'people_inc' => 3,

                    'resources' => array('1' => 440, '2' => 480, '3' => 320, '4' => 50)

                ),


                array(

                    'value' => 10, 'time_consume' => 4560, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 790, '2' => 865, '3' => 575, '4' => 90)

                ),


                array(

                    'value' => 15, 'time_consume' => 8040, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1425, '2' => 1555, '3' => 1035, '4' => 160)

                ),


                array(

                    'value' => 20, 'time_consume' => 13260, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 2565, '2' => 2800, '3' => 1865, '4' => 290)

                ),


                array(

                    'value' => 25, 'time_consume' => 21090, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 4620, '2' => 5040, '3' => 3360, '4' => 525)

                ),


            )

        ),


        '7' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('3' => 10, '15' => 5),

            'levels' => array(


                array(

                    'value' => 5, 'time_consume' => 4080, 'cp' => 1, 'people_inc' => 6,

                    'resources' => array('1' => 200, '2' => 450, '3' => 510, '4' => 120)

                ),


                array(

                    'value' => 10, 'time_consume' => 7320, 'cp' => 1, 'people_inc' => 3,

                    'resources' => array('1' => 360, '2' => 810, '3' => 920, '4' => 215)

                ),


                array(

                    'value' => 15, 'time_consume' => 12180, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 650, '2' => 1460, '3' => 1650, '4' => 390)

                ),


                array(

                    'value' => 20, 'time_consume' => 19470, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 1165, '2' => 2625, '3' => 2975, '4' => 700)

                ),


                array(

                    'value' => 25, 'time_consume' => 30410, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 2100, '2' => 4725, '3' => 5355, '4' => 1260)

                ),


            )

        ),


        '8' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('4' => 5),

            'levels' => array(


                array(

                    'value' => 5, 'time_consume' => 1840, 'cp' => 1, 'people_inc' => 3,

                    'resources' => array('1' => 500, '2' => 440, '3' => 380, '4' => 1240)

                ),


                array(

                    'value' => 10, 'time_consume' => 3960, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 900, '2' => 790, '3' => 685, '4' => 2230)

                ),


                array(

                    'value' => 15, 'time_consume' => 7140, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1620, '2' => 1425, '3' => 1230, '4' => 4020)

                ),


                array(

                    'value' => 20, 'time_consume' => 11910, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 2915, '2' => 2565, '3' => 2215, '4' => 7230)

                ),


                array(

                    'value' => 25, 'time_consume' => 19070, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 5250, '2' => 4620, '3' => 3990, '4' => 13015)

                ),


            )

        ),


        '9' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('4' => 10, '15' => 5, '8' => 5),

            'levels' => array(


                array(

                    'value' => 5, 'time_consume' => 3680, 'cp' => 1, 'people_inc' => 4,

                    'resources' => array('1' => 1200, '2' => 1480, '3' => 870, '4' => 1600)

                ),


                array(

                    'value' => 10, 'time_consume' => 6720, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 2160, '2' => 2665, '3' => 1565, '4' => 2880)

                ),


                array(

                    'value' => 15, 'time_consume' => 11280, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 3890, '2' => 4795, '3' => 2820, '4' => 5185)

                ),


                array(

                    'value' => 20, 'time_consume' => 18120, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 7000, '2' => 8630, '3' => 5075, '4' => 9330)

                ),


                array(

                    'value' => 25, 'time_consume' => 28380, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 12595, '2' => 15535, '3' => 9135, '4' => 16795)

                ),


            )

        ),


        '10' => array(

            'support_multiple' => TRUE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 1),

            'levels' => array(


                array(

                    'value' => $capacity * 1700, 'time_consume' => 200, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 130, '2' => 160, '3' => 90, '4' => 40)

                ),


                array(

                    'value' => $capacity * 2000, 'time_consume' => 700, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 165, '2' => 205, '3' => 115, '4' => 50)

                ),


                array(

                    'value' => $capacity * 2500, 'time_consume' => 3040, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 215, '2' => 260, '3' => 145, '4' => 65)

                ),


                array(

                    'value' => $capacity * 3100, 'time_consume' => 6170, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 275, '2' => 335, '3' => 190, '4' => 85)

                ),


                array(

                    'value' => $capacity * 4000, 'time_consume' => 10140, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 350, '2' => 430, '3' => 240, '4' => 105)

                ),


                array(

                    'value' => $capacity * 5000, 'time_consume' => 15260, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 445, '2' => 550, '3' => 310, '4' => 135)

                ),


                array(

                    'value' => $capacity * 6300, 'time_consume' => 20570, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 570, '2' => 705, '3' => 395, '4' => 175)

                ),


                array(

                    'value' => $capacity * 7800, 'time_consume' => 25080, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 730, '2' => 900, '3' => 505, '4' => 225)

                ),


                array(

                    'value' => $capacity * 9600, 'time_consume' => 30830, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 935, '2' => 1155, '3' => 650, '4' => 290)

                ),


                array(

                    'value' => $capacity * 11800, 'time_consume' => 35860, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 1200, '2' => 1475, '3' => 830, '4' => 370)

                ),


                array(

                    'value' => $capacity * 14400, 'time_consume' => 40220, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 1535, '2' => 1890, '3' => 1065, '4' => 470)

                ),


                array(

                    'value' => $capacity * 17600, 'time_consume' => 45950, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 1965, '2' => 2420, '3' => 1360, '4' => 605)

                ),


                array(

                    'value' => $capacity * 21400, 'time_consume' => 50130, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 2515, '2' => 3095, '3' => 1740, '4' => 775)

                ),


                array(

                    'value' => $capacity * 25900, 'time_consume' => 55810, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 3220, '2' => 3960, '3' => 2230, '4' => 990)

                ),


                array(

                    'value' => $capacity * 31300, 'time_consume' => 60080, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 4120, '2' => 5070, '3' => 2850, '4' => 1270)

                ),


                array(

                    'value' => $capacity * 37900, 'time_consume' => 65030, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 5275, '2' => 6490, '3' => 3650, '4' => 1625)

                ),


                array(

                    'value' => $capacity * 45700, 'time_consume' => 70770, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 6750, '2' => 8310, '3' => 4675, '4' => 2075)

                ),


                array(

                    'value' => $capacity * 55100, 'time_consume' => 75440, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 8640, '2' => 10635, '3' => 5980, '4' => 2660)

                ),


                array(

                    'value' => $capacity * 66400, 'time_consume' => 80170, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 11060, '2' => 13610, '3' => 7655, '4' => 3405)

                ),


                array(

                    'value' => $capacity * 80000, 'time_consume' => 85130, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 14155, '2' => 17420, '3' => 9800, '4' => 4355)

                ),


            )

        ),


        '11' => array(

            'support_multiple' => TRUE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 1),

            'levels' => array(


                array(

                    'value' => $capacity * 1700, 'time_consume' => 200, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 80, '2' => 100, '3' => 70, '4' => 20)

                ),


                array(

                    'value' => $capacity * 2000, 'time_consume' => 1000, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 100, '2' => 130, '3' => 90, '4' => 25)

                ),


                array(

                    'value' => $capacity * 2500, 'time_consume' => 2800, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 130, '2' => 165, '3' => 115, '4' => 35)

                ),


                array(

                    'value' => $capacity * 3100, 'time_consume' => 5550, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 170, '2' => 210, '3' => 145, '4' => 40)

                ),


                array(

                    'value' => $capacity * 4000, 'time_consume' => 7420, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 215, '2' => 270, '3' => 190, '4' => 55)

                ),


                array(

                    'value' => $capacity * 5000, 'time_consume' => 9420, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 275, '2' => 345, '3' => 240, '4' => 70)

                ),


                array(

                    'value' => $capacity * 6300, 'time_consume' => 12590, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 350, '2' => 440, '3' => 310, '4' => 90)

                ),


                array(

                    'value' => $capacity * 7800, 'time_consume' => 16950, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 450, '2' => 565, '3' => 395, '4' => 115)

                ),


                array(

                    'value' => $capacity * 9600, 'time_consume' => 20520, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 575, '2' => 720, '3' => 505, '4' => 145)

                ),


                array(

                    'value' => $capacity * 11800, 'time_consume' => 23340, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 740, '2' => 920, '3' => 645, '4' => 185)

                ),


                array(

                    'value' => $capacity * 14400, 'time_consume' => 27450, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 945, '2' => 1180, '3' => 825, '4' => 235)

                ),


                array(

                    'value' => $capacity * 17600, 'time_consume' => 30910, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 1210, '2' => 1510, '3' => 1060, '4' => 300)

                ),


                array(

                    'value' => $capacity * 21400, 'time_consume' => 35750, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 1545, '2' => 1935, '3' => 1355, '4' => 385)

                ),


                array(

                    'value' => $capacity * 25900, 'time_consume' => 40050, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 1980, '2' => 2475, '3' => 1735, '4' => 495)

                ),


                array(

                    'value' => $capacity * 31300, 'time_consume' => 45880, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 2535, '2' => 3170, '3' => 2220, '4' => 635)

                ),


                array(

                    'value' => $capacity * 37900, 'time_consume' => 50320, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 3245, '2' => 4055, '3' => 2840, '4' => 810)

                ),


                array(

                    'value' => $capacity * 45700, 'time_consume' => 55470, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 4155, '2' => 5190, '3' => 3635, '4' => 1040)

                ),


                array(

                    'value' => $capacity * 55100, 'time_consume' => 61450, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 5315, '2' => 6645, '3' => 4650, '4' => 1330)

                ),


                array(

                    'value' => $capacity * 66400, 'time_consume' => 68380, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 6805, '2' => 8505, '3' => 5955, '4' => 1700)

                ),


                array(

                    'value' => $capacity * 80000, 'time_consume' => 76420, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 8710, '2' => 10890, '3' => 7620, '4' => 2180)

                ),


            )

        ),


        '12' => array(

            'troop_upgrades' => array(

                '1' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 940, '2' => 800, '3' => 1250, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1635, '2' => 1395, '3' => 2175, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2265, '2' => 1925, '3' => 3010, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2850, '2' => 2425, '3' => 3790, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3405, '2' => 2900, '3' => 4530, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3940, '2' => 3355, '3' => 5240, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4460, '2' => 3795, '3' => 5930, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4960, '2' => 4220, '3' => 6600, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5450, '2' => 4640, '3' => 7250, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5930, '2' => 5050, '3' => 7885, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6400, '2' => 5450, '3' => 8510, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 6860, '2' => 5840, '3' => 9125, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7315, '2' => 6225, '3' => 9730, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 7765, '2' => 6605, '3' => 10325, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8205, '2' => 6980, '3' => 10910, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 8640, '2' => 7350, '3' => 11485, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9065, '2' => 7715, '3' => 12060, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9490, '2' => 8080, '3' => 12620, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 9910, '2' => 8435, '3' => 13180, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10325, '2' => 8790, '3' => 13730, '4' => 4065)),

                ),

                '2' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 1320, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 2300, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 3180, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 4000, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 4785, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 5535, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 6260, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 6965, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 7655, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 8330, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 8990, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 9635, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 10275, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 10900, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 11520, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 12130, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 12735, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 13330, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 13920, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 14500, '4' => 7140)),

                ),

                '3' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1150, '2' => 1220, '3' => 1670, '4' => 720)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2000, '2' => 2125, '3' => 2910, '4' => 1255)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2770, '2' => 2940, '3' => 4020, '4' => 1735)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3485, '2' => 3700, '3' => 5060, '4' => 2185)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4165, '2' => 4420, '3' => 6050, '4' => 2610)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4820, '2' => 5115, '3' => 7000, '4' => 3020)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5455, '2' => 5785, '3' => 7920, '4' => 3415)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6070, '2' => 6440, '3' => 8815, '4' => 3800)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6670, '2' => 7075, '3' => 9685, '4' => 4175)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7255, '2' => 7700, '3' => 10535, '4' => 4545)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7830, '2' => 8310, '3' => 11370, '4' => 4905)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8395, '2' => 8905, '3' => 12190, '4' => 5255)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8950, '2' => 9495, '3' => 13000, '4' => 5605)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9495, '2' => 10075, '3' => 13790, '4' => 5945)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10035, '2' => 10645, '3' => 14575, '4' => 6285)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10570, '2' => 11210, '3' => 15345, '4' => 6615)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11095, '2' => 11770, '3' => 16110, '4' => 6945)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11610, '2' => 12320, '3' => 16865, '4' => 7270)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12125, '2' => 12865, '3' => 17610, '4' => 7590)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12635, '2' => 13400, '3' => 18345, '4' => 7910)),

                ),

                '4' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 540, '2' => 610, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 940, '2' => 1060, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1300, '2' => 1470, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1635, '2' => 1850, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 1955, '2' => 2210, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2265, '2' => 2560, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 2560, '2' => 2895, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 2850, '2' => 3220, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3130, '2' => 3540, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 3405, '2' => 3850, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 3675, '2' => 4155, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 3940, '2' => 4455, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 4205, '2' => 4750, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 4460, '2' => 5040, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 4715, '2' => 5325, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 4960, '2' => 5605, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 5210, '2' => 5885, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 5455, '2' => 6160, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 5695, '2' => 6430, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 5930, '2' => 6700, '3' => 1870, '4' => 2415)),

                ),

                '5' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1315, '2' => 1060, '3' => 815, '4' => 285)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2290, '2' => 1845, '3' => 1415, '4' => 500)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3170, '2' => 2555, '3' => 1960, '4' => 690)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3990, '2' => 3215, '3' => 2465, '4' => 870)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4770, '2' => 3840, '3' => 2945, '4' => 1040)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5520, '2' => 4445, '3' => 3410, '4' => 1200)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6245, '2' => 5030, '3' => 3860, '4' => 1360)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6950, '2' => 5595, '3' => 4295, '4' => 1515)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7635, '2' => 6150, '3' => 4715, '4' => 1665)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8310, '2' => 6690, '3' => 5130, '4' => 1810)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8965, '2' => 7220, '3' => 5540, '4' => 1950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9610, '2' => 7740, '3' => 5940, '4' => 2095)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10250, '2' => 8250, '3' => 6330, '4' => 2230)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10875, '2' => 8755, '3' => 6715, '4' => 2365)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11490, '2' => 9250, '3' => 7100, '4' => 2500)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12100, '2' => 9740, '3' => 7475, '4' => 2635)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12700, '2' => 10225, '3' => 7845, '4' => 2765)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13295, '2' => 10705, '3' => 8215, '4' => 2895)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13885, '2' => 11175, '3' => 8575, '4' => 3025)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14465, '2' => 11645, '3' => 8935, '4' => 3150)),

                ),

                '6' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 990, '2' => 1145, '3' => 1450, '4' => 355)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1720, '2' => 1995, '3' => 2525, '4' => 620)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2380, '2' => 2755, '3' => 3490, '4' => 855)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2995, '2' => 3470, '3' => 4395, '4' => 1075)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3580, '2' => 4150, '3' => 5255, '4' => 1285)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4140, '2' => 4800, '3' => 6080, '4' => 1490)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4685, '2' => 5430, '3' => 6880, '4' => 1685)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5210, '2' => 6045, '3' => 7655, '4' => 1875)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5725, '2' => 6640, '3' => 8410, '4' => 2060)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6230, '2' => 7225, '3' => 9150, '4' => 2240)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6725, '2' => 7795, '3' => 9875, '4' => 2415)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7210, '2' => 8360, '3' => 10585, '4' => 2590)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7685, '2' => 8910, '3' => 11285, '4' => 2765)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8155, '2' => 9455, '3' => 11975, '4' => 2930)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8620, '2' => 9995, '3' => 12655, '4' => 3100)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9075, '2' => 10520, '3' => 13325, '4' => 3260)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9525, '2' => 11045, '3' => 13985, '4' => 3425)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9970, '2' => 11560, '3' => 14640, '4' => 3585)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10410, '2' => 12075, '3' => 15290, '4' => 3745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10850, '2' => 12580, '3' => 15930, '4' => 3900)),

                ),

                '7' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2135, '2' => 875, '3' => 1235, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3715, '2' => 1520, '3' => 2145, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5140, '2' => 2105, '3' => 2970, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6465, '2' => 2645, '3' => 3740, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 7730, '2' => 3165, '3' => 4470, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 8945, '2' => 3660, '3' => 5170, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10120, '2' => 4140, '3' => 5850, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11260, '2' => 4610, '3' => 6510, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 12370, '2' => 5065, '3' => 7155, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 13460, '2' => 5510, '3' => 7780, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 14525, '2' => 5945, '3' => 8400, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 15575, '2' => 6375, '3' => 9005, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 16605, '2' => 6795, '3' => 9600, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 17620, '2' => 7210, '3' => 10185, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 18620, '2' => 7620, '3' => 10765, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 19605, '2' => 8025, '3' => 11335, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 20580, '2' => 8425, '3' => 11895, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 21540, '2' => 8820, '3' => 12455, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 22495, '2' => 9210, '3' => 13005, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 23435, '2' => 9595, '3' => 13550, '4' => 2380)),

                ),

                '8' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1125, '2' => 1590, '3' => 735, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1960, '2' => 2770, '3' => 1275, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2710, '2' => 3835, '3' => 1765, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3410, '2' => 4825, '3' => 2225, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4075, '2' => 5770, '3' => 2660, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4715, '2' => 6675, '3' => 3075, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5335, '2' => 7550, '3' => 3480, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5940, '2' => 8400, '3' => 3870, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6525, '2' => 9230, '3' => 4255, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7100, '2' => 10045, '3' => 4625, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7660, '2' => 10840, '3' => 4995, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8215, '2' => 11620, '3' => 5355, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8755, '2' => 12390, '3' => 5710, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9290, '2' => 13145, '3' => 6055, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9820, '2' => 13890, '3' => 6400, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10340, '2' => 14625, '3' => 6740, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10850, '2' => 15355, '3' => 7075, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11360, '2' => 16070, '3' => 7405, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11860, '2' => 16780, '3' => 7730, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12360, '2' => 17485, '3' => 8055, '4' => 1445)),

                ),


                '11' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 765, '2' => 625, '3' => 480, '4' => 440)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1330, '2' => 1090, '3' => 835, '4' => 765)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1840, '2' => 1505, '3' => 1155, '4' => 1060)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2320, '2' => 1895, '3' => 1455, '4' => 1335)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2770, '2' => 2265, '3' => 1740, '4' => 1595)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3210, '2' => 2620, '3' => 2015, '4' => 1845)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3630, '2' => 2965, '3' => 2275, '4' => 2085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4040, '2' => 3300, '3' => 2535, '4' => 2320)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4435, '2' => 3625, '3' => 2785, '4' => 2550)),

                    array('time_consume' => 74880, 'resources' => array('1' => 4825, '2' => 3945, '3' => 3030, '4' => 2775)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5210, '2' => 4255, '3' => 3270, '4' => 2995)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5585, '2' => 4565, '3' => 3505, '4' => 3210)),

                    array('time_consume' => 96480, 'resources' => array('1' => 5955, '2' => 4865, '3' => 3735, '4' => 3425)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6320, '2' => 5160, '3' => 3965, '4' => 3635)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6675, '2' => 5455, '3' => 4190, '4' => 3840)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7030, '2' => 5745, '3' => 4410, '4' => 4045)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7380, '2' => 6030, '3' => 4630, '4' => 4245)),

                    array('time_consume' => 132480, 'resources' => array('1' => 7725, '2' => 6310, '3' => 4845, '4' => 4445)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8065, '2' => 6590, '3' => 5060, '4' => 4640)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8405, '2' => 6865, '3' => 5275, '4' => 4835)),

                ),

                '12' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1115, '2' => 590, '3' => 795, '4' => 440)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1940, '2' => 1025, '3' => 1385, '4' => 765)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2685, '2' => 1420, '3' => 1915, '4' => 1060)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3380, '2' => 1790, '3' => 2410, '4' => 1335)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4040, '2' => 2140, '3' => 2880, '4' => 1595)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4675, '2' => 2475, '3' => 3335, '4' => 1845)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5290, '2' => 2800, '3' => 3770, '4' => 2085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5885, '2' => 3115, '3' => 4195, '4' => 2320)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6465, '2' => 3420, '3' => 4610, '4' => 2550)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7035, '2' => 3725, '3' => 5015, '4' => 2775)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7595, '2' => 4020, '3' => 5415, '4' => 2995)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8140, '2' => 4305, '3' => 5805, '4' => 3210)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8680, '2' => 4590, '3' => 6190, '4' => 3425)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9210, '2' => 4875, '3' => 6565, '4' => 3635)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9730, '2' => 5150, '3' => 6940, '4' => 3840)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10245, '2' => 5420, '3' => 7305, '4' => 4045)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10755, '2' => 5690, '3' => 7670, '4' => 4245)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11260, '2' => 5960, '3' => 8030, '4' => 4445)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11755, '2' => 6220, '3' => 8380, '4' => 4640)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12250, '2' => 6480, '3' => 8735, '4' => 4835)),

                ),

                '13' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1010, '2' => 940, '3' => 1390, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1760, '2' => 1635, '3' => 2420, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2430, '2' => 2265, '3' => 3345, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3060, '2' => 2850, '3' => 4215, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3660, '2' => 3405, '3' => 5035, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4235, '2' => 3940, '3' => 5830, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4790, '2' => 4460, '3' => 6595, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5330, '2' => 4960, '3' => 7335, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5860, '2' => 5450, '3' => 8060, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6375, '2' => 5930, '3' => 8770, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6880, '2' => 6400, '3' => 9465, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7375, '2' => 6860, '3' => 10150, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7860, '2' => 7315, '3' => 10820, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8340, '2' => 7765, '3' => 11480, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8815, '2' => 8205, '3' => 12130, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9280, '2' => 8640, '3' => 12775, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9745, '2' => 9065, '3' => 13410, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10200, '2' => 9490, '3' => 14035, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10650, '2' => 9910, '3' => 14655, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11095, '2' => 10325, '3' => 15270, '4' => 7140)),

                ),

                '14' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1220, '2' => 800, '3' => 550, '4' => 510)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2125, '2' => 1395, '3' => 960, '4' => 890)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2940, '2' => 1925, '3' => 1325, '4' => 1230)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3700, '2' => 2425, '3' => 1665, '4' => 1545)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4420, '2' => 2900, '3' => 1995, '4' => 1850)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5115, '2' => 3355, '3' => 2305, '4' => 2140)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5785, '2' => 3795, '3' => 2610, '4' => 2420)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6440, '2' => 4220, '3' => 2905, '4' => 2690)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7075, '2' => 4640, '3' => 3190, '4' => 2960)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7700, '2' => 5050, '3' => 3470, '4' => 3220)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8310, '2' => 5450, '3' => 3745, '4' => 3475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8905, '2' => 5840, '3' => 4015, '4' => 3725)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9495, '2' => 6225, '3' => 4280, '4' => 3970)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10075, '2' => 6605, '3' => 4540, '4' => 4210)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10645, '2' => 6980, '3' => 4800, '4' => 4450)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11210, '2' => 7350, '3' => 5055, '4' => 4685)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11770, '2' => 7715, '3' => 5305, '4' => 4920)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12320, '2' => 8080, '3' => 5555, '4' => 5150)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12865, '2' => 8435, '3' => 5800, '4' => 5375)),

                    array('time_consume' => 146880, 'resources' => array('1' => 13400, '2' => 8790, '3' => 6040, '4' => 5605)),

                ),

                '15' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1345, '2' => 995, '3' => 1115, '4' => 345)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2340, '2' => 1730, '3' => 1940, '4' => 595)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3240, '2' => 2395, '3' => 2685, '4' => 825)),

                    array('time_consume' => 31680, 'resources' => array('1' => 4075, '2' => 3015, '3' => 3380, '4' => 1040)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4875, '2' => 3605, '3' => 4040, '4' => 1240)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5640, '2' => 4170, '3' => 4675, '4' => 1435)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6380, '2' => 4720, '3' => 5290, '4' => 1625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 7100, '2' => 5250, '3' => 5885, '4' => 1810)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7800, '2' => 5770, '3' => 6465, '4' => 1985)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8485, '2' => 6280, '3' => 7035, '4' => 2160)),

                    array('time_consume' => 82080, 'resources' => array('1' => 9160, '2' => 6775, '3' => 7595, '4' => 2330)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9820, '2' => 7265, '3' => 8140, '4' => 2500)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10470, '2' => 7745, '3' => 8680, '4' => 2665)),

                    array('time_consume' => 103680, 'resources' => array('1' => 11110, '2' => 8215, '3' => 9210, '4' => 2830)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11740, '2' => 8685, '3' => 9730, '4' => 2990)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12360, '2' => 9145, '3' => 10245, '4' => 3145)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12975, '2' => 9600, '3' => 10755, '4' => 3305)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13580, '2' => 10045, '3' => 11260, '4' => 3460)),

                    array('time_consume' => 139680, 'resources' => array('1' => 14180, '2' => 10490, '3' => 11755, '4' => 3610)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14775, '2' => 10930, '3' => 12250, '4' => 3765)),

                ),

                '16' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1085, '2' => 1235, '3' => 1185, '4' => 240)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1885, '2' => 2150, '3' => 2065, '4' => 420)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2610, '2' => 2975, '3' => 2860, '4' => 580)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3285, '2' => 3745, '3' => 3595, '4' => 730)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3925, '2' => 4475, '3' => 4300, '4' => 870)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4540, '2' => 5180, '3' => 4975, '4' => 1005)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5140, '2' => 5860, '3' => 5630, '4' => 1140)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5720, '2' => 6520, '3' => 6265, '4' => 1265)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6285, '2' => 7160, '3' => 6880, '4' => 1390)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6835, '2' => 7790, '3' => 7485, '4' => 1515)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7375, '2' => 8410, '3' => 8080, '4' => 1635)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7910, '2' => 9015, '3' => 8665, '4' => 1750)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8430, '2' => 9610, '3' => 9235, '4' => 1870)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8945, '2' => 10200, '3' => 9800, '4' => 1980)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9455, '2' => 10780, '3' => 10355, '4' => 2095)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9955, '2' => 11350, '3' => 10905, '4' => 2205)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10450, '2' => 11915, '3' => 11445, '4' => 2315)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10940, '2' => 12470, '3' => 11980, '4' => 2425)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11425, '2' => 13020, '3' => 12510, '4' => 2530)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11900, '2' => 13565, '3' => 13035, '4' => 2635)),

                ),

                '17' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2365, '2' => 735, '3' => 885, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 4120, '2' => 1275, '3' => 1540, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5700, '2' => 1765, '3' => 2125, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 7175, '2' => 2225, '3' => 2680, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 8575, '2' => 2660, '3' => 3200, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 9925, '2' => 3075, '3' => 3705, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 11225, '2' => 3480, '3' => 4190, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 12490, '2' => 3870, '3' => 4660, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 13725, '2' => 4255, '3' => 5125, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 14935, '2' => 4625, '3' => 5575, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 16115, '2' => 4995, '3' => 6015, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 17280, '2' => 5355, '3' => 6450, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 18420, '2' => 5710, '3' => 6875, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 19545, '2' => 6055, '3' => 7295, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 20655, '2' => 6400, '3' => 7710, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 21750, '2' => 6740, '3' => 8115, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 22830, '2' => 7075, '3' => 8520, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 23900, '2' => 7405, '3' => 8920, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 24955, '2' => 7730, '3' => 9315, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 26000, '2' => 8055, '3' => 9705, '4' => 2380)),

                ),

                '18' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1065, '2' => 1415, '3' => 735, '4' => 95)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1855, '2' => 2465, '3' => 1275, '4' => 170)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2570, '2' => 3410, '3' => 1765, '4' => 235)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3235, '2' => 4295, '3' => 2225, '4' => 295)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3865, '2' => 5135, '3' => 2660, '4' => 350)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4470, '2' => 5940, '3' => 3075, '4' => 405)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5060, '2' => 6720, '3' => 3480, '4' => 460)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5630, '2' => 7475, '3' => 3870, '4' => 510)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6185, '2' => 8215, '3' => 4255, '4' => 560)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6730, '2' => 8940, '3' => 4625, '4' => 610)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7265, '2' => 9645, '3' => 4995, '4' => 660)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7785, '2' => 10340, '3' => 5355, '4' => 705)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8300, '2' => 11025, '3' => 5710, '4' => 750)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8810, '2' => 11700, '3' => 6055, '4' => 800)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9310, '2' => 12365, '3' => 6400, '4' => 845)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9800, '2' => 13020, '3' => 6740, '4' => 890)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10290, '2' => 13665, '3' => 7075, '4' => 930)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10770, '2' => 14305, '3' => 7405, '4' => 975)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11245, '2' => 14935, '3' => 7730, '4' => 1020)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11720, '2' => 15565, '3' => 8055, '4' => 1060)),

                ),


                '21' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 585, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 1020, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 1410, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 1775, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 2120, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 2455, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 2775, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 3090, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 3395, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 3690, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 3985, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 4270, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 4555, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 4830, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 5105, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 5375, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 5645, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 5905, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 6170, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 6425, '4' => 4065)),

                ),

                '22' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1080, '2' => 1150, '3' => 1495, '4' => 580)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1880, '2' => 2000, '3' => 2605, '4' => 1010)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2600, '2' => 2770, '3' => 3600, '4' => 1395)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3275, '2' => 3485, '3' => 4530, '4' => 1760)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3915, '2' => 4165, '3' => 5420, '4' => 2100)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4530, '2' => 4820, '3' => 6270, '4' => 2430)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5125, '2' => 5455, '3' => 7090, '4' => 2750)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5700, '2' => 6070, '3' => 7890, '4' => 3060)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6265, '2' => 6670, '3' => 8670, '4' => 3365)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6815, '2' => 7255, '3' => 9435, '4' => 3660)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7355, '2' => 7830, '3' => 10180, '4' => 3950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7885, '2' => 8395, '3' => 10915, '4' => 4235)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8405, '2' => 8950, '3' => 11635, '4' => 4515)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8920, '2' => 9495, '3' => 12345, '4' => 4790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9425, '2' => 10035, '3' => 13045, '4' => 5060)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9925, '2' => 10570, '3' => 13740, '4' => 5330)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10420, '2' => 11095, '3' => 14420, '4' => 5595)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10905, '2' => 11610, '3' => 15095, '4' => 5855)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11385, '2' => 12125, '3' => 15765, '4' => 6115)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11865, '2' => 12635, '3' => 16425, '4' => 6370)),

                ),

                '23' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 645, '2' => 575, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1125, '2' => 1000, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1555, '2' => 1385, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1955, '2' => 1745, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2335, '2' => 2085, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2705, '2' => 2410, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3060, '2' => 2725, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 3405, '2' => 3035, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3740, '2' => 3335, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 4070, '2' => 3630, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 4390, '2' => 3915, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 4710, '2' => 4200, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 5020, '2' => 4475, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 5325, '2' => 4750, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 5630, '2' => 5020, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 5925, '2' => 5285, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 6220, '2' => 5545, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 6515, '2' => 5805, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 6800, '2' => 6065, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 7085, '2' => 6315, '3' => 1870, '4' => 2415)),

                ),

                '24' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1275, '2' => 1625, '3' => 905, '4' => 290)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2220, '2' => 2830, '3' => 1575, '4' => 505)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3070, '2' => 3915, '3' => 2180, '4' => 700)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3865, '2' => 4925, '3' => 2745, '4' => 880)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4620, '2' => 5890, '3' => 3280, '4' => 1050)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5345, '2' => 6815, '3' => 3795, '4' => 1215)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6050, '2' => 7710, '3' => 4295, '4' => 1375)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6730, '2' => 8575, '3' => 4775, '4' => 1530)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7395, '2' => 9425, '3' => 5250, '4' => 1680)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8045, '2' => 10255, '3' => 5710, '4' => 1830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8680, '2' => 11065, '3' => 6165, '4' => 1975)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9310, '2' => 11865, '3' => 6605, '4' => 2115)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9925, '2' => 12650, '3' => 7045, '4' => 2255)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10530, '2' => 13420, '3' => 7475, '4' => 2395)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11125, '2' => 14180, '3' => 7900, '4' => 2530)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11715, '2' => 14935, '3' => 8315, '4' => 2665)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12300, '2' => 15675, '3' => 8730, '4' => 2795)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12875, '2' => 16410, '3' => 9140, '4' => 2930)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13445, '2' => 17135, '3' => 9540, '4' => 3060)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14005, '2' => 17850, '3' => 9940, '4' => 3185)),

                ),

                '25' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1310, '2' => 1205, '3' => 1080, '4' => 500)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2280, '2' => 2100, '3' => 1880, '4' => 870)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3155, '2' => 2900, '3' => 2600, '4' => 1205)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3970, '2' => 3655, '3' => 3275, '4' => 1515)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4745, '2' => 4365, '3' => 3915, '4' => 1810)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5495, '2' => 5055, '3' => 4530, '4' => 2095)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6215, '2' => 5715, '3' => 5125, '4' => 2370)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6915, '2' => 6360, '3' => 5700, '4' => 2640)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7595, '2' => 6990, '3' => 6265, '4' => 2900)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8265, '2' => 7605, '3' => 6815, '4' => 3155)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8920, '2' => 8205, '3' => 7355, '4' => 3405)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9565, '2' => 8795, '3' => 7885, '4' => 3650)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10195, '2' => 9380, '3' => 8405, '4' => 3890)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10820, '2' => 9950, '3' => 8920, '4' => 4130)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11435, '2' => 10515, '3' => 9425, '4' => 4365)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12040, '2' => 11075, '3' => 9925, '4' => 4595)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12635, '2' => 11625, '3' => 10420, '4' => 4825)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13230, '2' => 12170, '3' => 10905, '4' => 5050)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13815, '2' => 12705, '3' => 11385, '4' => 5270)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14390, '2' => 13240, '3' => 11865, '4' => 5495)),

                ),

                '26' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1200, '2' => 1480, '3' => 1640, '4' => 450)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2090, '2' => 2575, '3' => 2860, '4' => 785)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2890, '2' => 3565, '3' => 3955, '4' => 1085)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3640, '2' => 4485, '3' => 4975, '4' => 1365)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4350, '2' => 5365, '3' => 5950, '4' => 1630)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5030, '2' => 6205, '3' => 6885, '4' => 1885)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5690, '2' => 7020, '3' => 7785, '4' => 2135)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6335, '2' => 7810, '3' => 8665, '4' => 2375)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6960, '2' => 8585, '3' => 9520, '4' => 2610)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7570, '2' => 9340, '3' => 10360, '4' => 2840)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8170, '2' => 10080, '3' => 11180, '4' => 3065)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8760, '2' => 10805, '3' => 11985, '4' => 3285)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9340, '2' => 11520, '3' => 12775, '4' => 3500)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9910, '2' => 12225, '3' => 13560, '4' => 3715)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10475, '2' => 12915, '3' => 14325, '4' => 3925)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11030, '2' => 13600, '3' => 15085, '4' => 4135)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11575, '2' => 14275, '3' => 15835, '4' => 4340)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12115, '2' => 14945, '3' => 16575, '4' => 4545)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12655, '2' => 15605, '3' => 17310, '4' => 4745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 13185, '2' => 16260, '3' => 18035, '4' => 4945)),

                ),

                '27' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2250, '2' => 1330, '3' => 835, '4' => 230)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3915, '2' => 2315, '3' => 1455, '4' => 400)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5420, '2' => 3200, '3' => 2015, '4' => 550)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6820, '2' => 4025, '3' => 2535, '4' => 690)),

                    array('time_consume' => 38880, 'resources' => array('1' => 8155, '2' => 4815, '3' => 3030, '4' => 825)),

                    array('time_consume' => 46080, 'resources' => array('1' => 9435, '2' => 5570, '3' => 3510, '4' => 955)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10670, '2' => 6300, '3' => 3970, '4' => 1085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11875, '2' => 7010, '3' => 4415, '4' => 1205)),

                    array('time_consume' => 67680, 'resources' => array('1' => 13050, '2' => 7705, '3' => 4850, '4' => 1325)),

                    array('time_consume' => 74880, 'resources' => array('1' => 14195, '2' => 8380, '3' => 5280, '4' => 1440)),

                    array('time_consume' => 82080, 'resources' => array('1' => 15320, '2' => 9045, '3' => 5695, '4' => 1555)),

                    array('time_consume' => 89280, 'resources' => array('1' => 16425, '2' => 9695, '3' => 6110, '4' => 1665)),

                    array('time_consume' => 96480, 'resources' => array('1' => 17510, '2' => 10340, '3' => 6510, '4' => 1775)),

                    array('time_consume' => 103680, 'resources' => array('1' => 18580, '2' => 10970, '3' => 6910, '4' => 1885)),

                    array('time_consume' => 110880, 'resources' => array('1' => 19635, '2' => 11595, '3' => 7300, '4' => 1995)),

                    array('time_consume' => 118080, 'resources' => array('1' => 20675, '2' => 12205, '3' => 7690, '4' => 2100)),

                    array('time_consume' => 125280, 'resources' => array('1' => 21705, '2' => 12815, '3' => 8070, '4' => 2205)),

                    array('time_consume' => 132480, 'resources' => array('1' => 22720, '2' => 13415, '3' => 8450, '4' => 2305)),

                    array('time_consume' => 139680, 'resources' => array('1' => 23725, '2' => 14005, '3' => 8820, '4' => 2410)),

                    array('time_consume' => 146880, 'resources' => array('1' => 24720, '2' => 14595, '3' => 9190, '4' => 2510)),

                ),

                '28' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1135, '2' => 1710, '3' => 770, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1980, '2' => 2975, '3' => 1340, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2735, '2' => 4115, '3' => 1850, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3445, '2' => 5180, '3' => 2330, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4120, '2' => 6190, '3' => 2785, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4765, '2' => 7165, '3' => 3220, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5390, '2' => 8105, '3' => 3645, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6000, '2' => 9015, '3' => 4055, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6590, '2' => 9910, '3' => 4455, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7170, '2' => 10780, '3' => 4850, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7740, '2' => 11635, '3' => 5230, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8300, '2' => 12470, '3' => 5610, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8845, '2' => 13295, '3' => 5980, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9385, '2' => 14110, '3' => 6345, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9920, '2' => 14910, '3' => 6705, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10445, '2' => 15700, '3' => 7060, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10965, '2' => 16480, '3' => 7410, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11480, '2' => 17250, '3' => 7760, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11985, '2' => 18015, '3' => 8100, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12485, '2' => 18765, '3' => 8440, '4' => 1445)),

                ),


                '100' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 940, '2' => 800, '3' => 1250, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1635, '2' => 1395, '3' => 2175, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2265, '2' => 1925, '3' => 3010, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2850, '2' => 2425, '3' => 3790, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3405, '2' => 2900, '3' => 4530, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3940, '2' => 3355, '3' => 5240, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4460, '2' => 3795, '3' => 5930, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4960, '2' => 4220, '3' => 6600, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5450, '2' => 4640, '3' => 7250, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5930, '2' => 5050, '3' => 7885, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6400, '2' => 5450, '3' => 8510, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 6860, '2' => 5840, '3' => 9125, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7315, '2' => 6225, '3' => 9730, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 7765, '2' => 6605, '3' => 10325, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8205, '2' => 6980, '3' => 10910, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 8640, '2' => 7350, '3' => 11485, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9065, '2' => 7715, '3' => 12060, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9490, '2' => 8080, '3' => 12620, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 9910, '2' => 8435, '3' => 13180, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10325, '2' => 8790, '3' => 13730, '4' => 4065)),

                ),

                '101' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 1320, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 2300, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 3180, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 4000, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 4785, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 5535, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 6260, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 6965, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 7655, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 8330, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 8990, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 9635, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 10275, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 10900, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 11520, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 12130, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 12735, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 13330, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 13920, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 14500, '4' => 7140)),

                ),

                '102' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1150, '2' => 1220, '3' => 1670, '4' => 720)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2000, '2' => 2125, '3' => 2910, '4' => 1255)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2770, '2' => 2940, '3' => 4020, '4' => 1735)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3485, '2' => 3700, '3' => 5060, '4' => 2185)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4165, '2' => 4420, '3' => 6050, '4' => 2610)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4820, '2' => 5115, '3' => 7000, '4' => 3020)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5455, '2' => 5785, '3' => 7920, '4' => 3415)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6070, '2' => 6440, '3' => 8815, '4' => 3800)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6670, '2' => 7075, '3' => 9685, '4' => 4175)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7255, '2' => 7700, '3' => 10535, '4' => 4545)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7830, '2' => 8310, '3' => 11370, '4' => 4905)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8395, '2' => 8905, '3' => 12190, '4' => 5255)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8950, '2' => 9495, '3' => 13000, '4' => 5605)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9495, '2' => 10075, '3' => 13790, '4' => 5945)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10035, '2' => 10645, '3' => 14575, '4' => 6285)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10570, '2' => 11210, '3' => 15345, '4' => 6615)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11095, '2' => 11770, '3' => 16110, '4' => 6945)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11610, '2' => 12320, '3' => 16865, '4' => 7270)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12125, '2' => 12865, '3' => 17610, '4' => 7590)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12635, '2' => 13400, '3' => 18345, '4' => 7910)),

                ),

                '103' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 540, '2' => 610, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 940, '2' => 1060, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1300, '2' => 1470, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1635, '2' => 1850, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 1955, '2' => 2210, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2265, '2' => 2560, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 2560, '2' => 2895, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 2850, '2' => 3220, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3130, '2' => 3540, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 3405, '2' => 3850, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 3675, '2' => 4155, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 3940, '2' => 4455, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 4205, '2' => 4750, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 4460, '2' => 5040, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 4715, '2' => 5325, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 4960, '2' => 5605, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 5210, '2' => 5885, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 5455, '2' => 6160, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 5695, '2' => 6430, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 5930, '2' => 6700, '3' => 1870, '4' => 2415)),

                ),

                '104' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1315, '2' => 1060, '3' => 815, '4' => 285)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2290, '2' => 1845, '3' => 1415, '4' => 500)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3170, '2' => 2555, '3' => 1960, '4' => 690)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3990, '2' => 3215, '3' => 2465, '4' => 870)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4770, '2' => 3840, '3' => 2945, '4' => 1040)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5520, '2' => 4445, '3' => 3410, '4' => 1200)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6245, '2' => 5030, '3' => 3860, '4' => 1360)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6950, '2' => 5595, '3' => 4295, '4' => 1515)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7635, '2' => 6150, '3' => 4715, '4' => 1665)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8310, '2' => 6690, '3' => 5130, '4' => 1810)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8965, '2' => 7220, '3' => 5540, '4' => 1950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9610, '2' => 7740, '3' => 5940, '4' => 2095)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10250, '2' => 8250, '3' => 6330, '4' => 2230)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10875, '2' => 8755, '3' => 6715, '4' => 2365)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11490, '2' => 9250, '3' => 7100, '4' => 2500)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12100, '2' => 9740, '3' => 7475, '4' => 2635)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12700, '2' => 10225, '3' => 7845, '4' => 2765)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13295, '2' => 10705, '3' => 8215, '4' => 2895)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13885, '2' => 11175, '3' => 8575, '4' => 3025)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14465, '2' => 11645, '3' => 8935, '4' => 3150)),

                ),

                '105' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 990, '2' => 1145, '3' => 1450, '4' => 355)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1720, '2' => 1995, '3' => 2525, '4' => 620)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2380, '2' => 2755, '3' => 3490, '4' => 855)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2995, '2' => 3470, '3' => 4395, '4' => 1075)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3580, '2' => 4150, '3' => 5255, '4' => 1285)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4140, '2' => 4800, '3' => 6080, '4' => 1490)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4685, '2' => 5430, '3' => 6880, '4' => 1685)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5210, '2' => 6045, '3' => 7655, '4' => 1875)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5725, '2' => 6640, '3' => 8410, '4' => 2060)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6230, '2' => 7225, '3' => 9150, '4' => 2240)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6725, '2' => 7795, '3' => 9875, '4' => 2415)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7210, '2' => 8360, '3' => 10585, '4' => 2590)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7685, '2' => 8910, '3' => 11285, '4' => 2765)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8155, '2' => 9455, '3' => 11975, '4' => 2930)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8620, '2' => 9995, '3' => 12655, '4' => 3100)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9075, '2' => 10520, '3' => 13325, '4' => 3260)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9525, '2' => 11045, '3' => 13985, '4' => 3425)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9970, '2' => 11560, '3' => 14640, '4' => 3585)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10410, '2' => 12075, '3' => 15290, '4' => 3745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10850, '2' => 12580, '3' => 15930, '4' => 3900)),

                ),

                '106' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2135, '2' => 875, '3' => 1235, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3715, '2' => 1520, '3' => 2145, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5140, '2' => 2105, '3' => 2970, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6465, '2' => 2645, '3' => 3740, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 7730, '2' => 3165, '3' => 4470, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 8945, '2' => 3660, '3' => 5170, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10120, '2' => 4140, '3' => 5850, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11260, '2' => 4610, '3' => 6510, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 12370, '2' => 5065, '3' => 7155, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 13460, '2' => 5510, '3' => 7780, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 14525, '2' => 5945, '3' => 8400, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 15575, '2' => 6375, '3' => 9005, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 16605, '2' => 6795, '3' => 9600, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 17620, '2' => 7210, '3' => 10185, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 18620, '2' => 7620, '3' => 10765, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 19605, '2' => 8025, '3' => 11335, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 20580, '2' => 8425, '3' => 11895, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 21540, '2' => 8820, '3' => 12455, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 22495, '2' => 9210, '3' => 13005, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 23435, '2' => 9595, '3' => 13550, '4' => 2380)),

                ),

                '107' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1125, '2' => 1590, '3' => 735, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1960, '2' => 2770, '3' => 1275, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2710, '2' => 3835, '3' => 1765, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3410, '2' => 4825, '3' => 2225, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4075, '2' => 5770, '3' => 2660, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4715, '2' => 6675, '3' => 3075, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5335, '2' => 7550, '3' => 3480, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5940, '2' => 8400, '3' => 3870, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6525, '2' => 9230, '3' => 4255, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7100, '2' => 10045, '3' => 4625, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7660, '2' => 10840, '3' => 4995, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8215, '2' => 11620, '3' => 5355, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8755, '2' => 12390, '3' => 5710, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9290, '2' => 13145, '3' => 6055, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9820, '2' => 13890, '3' => 6400, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10340, '2' => 14625, '3' => 6740, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10850, '2' => 15355, '3' => 7075, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11360, '2' => 16070, '3' => 7405, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11860, '2' => 16780, '3' => 7730, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12360, '2' => 17485, '3' => 8055, '4' => 1445)),

                ),

            ),


            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 3, '22' => 3),

            'levels' => array(


                array(

                    'value' => 102, 'time_consume' => 2000, 'cp' => 2, 'people_inc' => 4,

                    'resources' => array('1' => 340, '2' => 400, '3' => 760, '4' => 260)

                ),


                array(

                    'value' => 104, 'time_consume' => 2620, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 440, '2' => 510, '3' => 970, '4' => 330)

                ),


                array(

                    'value' => 106, 'time_consume' => 3340, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 560, '2' => 660, '3' => 1250, '4' => 430)

                ),


                array(

                    'value' => 108, 'time_consume' => 4170, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 710, '2' => 840, '3' => 1590, '4' => 550)

                ),


                array(

                    'value' => 110, 'time_consume' => 5140, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 910, '2' => 1070, '3' => 2040, '4' => 700)

                ),


                array(

                    'value' => 112, 'time_consume' => 6260, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 1170, '2' => 1370, '3' => 2610, '4' => 890)

                ),


                array(

                    'value' => 114, 'time_consume' => 7570, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 1500, '2' => 1760, '3' => 3340, '4' => 1140)

                ),


                array(

                    'value' => 116, 'time_consume' => 9080, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 1910, '2' => 2250, '3' => 4280, '4' => 1460)

                ),


                array(

                    'value' => 118, 'time_consume' => 10830, 'cp' => 10, 'people_inc' => 3,

                    'resources' => array('1' => 2450, '2' => 2880, '3' => 5480, '4' => 1870)

                ),


                array(

                    'value' => 120, 'time_consume' => 12860, 'cp' => 12, 'people_inc' => 3,

                    'resources' => array('1' => 3140, '2' => 3690, '3' => 7010, '4' => 2400)

                ),


                array(

                    'value' => 122, 'time_consume' => 15220, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 4010, '2' => 4720, '3' => 8970, '4' => 3070)

                ),


                array(

                    'value' => 124, 'time_consume' => 17950, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 5140, '2' => 6040, '3' => 11480, '4' => 3930)

                ),


                array(

                    'value' => 126, 'time_consume' => 21130, 'cp' => 21, 'people_inc' => 3,

                    'resources' => array('1' => 6580, '2' => 7740, '3' => 14700, '4' => 5030)

                ),


                array(

                    'value' => 128, 'time_consume' => 24810, 'cp' => 26, 'people_inc' => 3,

                    'resources' => array('1' => 8420, '2' => 9900, '3' => 18820, '4' => 6440)

                ),


                array(

                    'value' => 130, 'time_consume' => 29080, 'cp' => 31, 'people_inc' => 3,

                    'resources' => array('1' => 10780, '2' => 12680, '3' => 24090, '4' => 8240)

                ),


                array(

                    'value' => 132, 'time_consume' => 34030, 'cp' => 37, 'people_inc' => 4,

                    'resources' => array('1' => 13790, '2' => 16230, '3' => 30830, '4' => 10550)

                ),


                array(

                    'value' => 134, 'time_consume' => 39770, 'cp' => 44, 'people_inc' => 4,

                    'resources' => array('1' => 17650, '2' => 20770, '3' => 39460, '4' => 13500)

                ),


                array(

                    'value' => 136, 'time_consume' => 46440, 'cp' => 53, 'people_inc' => 4,

                    'resources' => array('1' => 22600, '2' => 26580, '3' => 50510, '4' => 17280)

                ),


                array(

                    'value' => 138, 'time_consume' => 54170, 'cp' => 64, 'people_inc' => 4,

                    'resources' => array('1' => 28920, '2' => 34030, '3' => 64650, '4' => 22120)

                ),


                array(

                    'value' => 140, 'time_consume' => 63130, 'cp' => 77, 'people_inc' => 4,

                    'resources' => array('1' => 37020, '2' => 43560, '3' => 82760, '4' => 28310)

                ),


            )

        ),


        '13' => array(

            'troop_upgrades' => array(

                '1' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 940, '2' => 800, '3' => 1250, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1635, '2' => 1395, '3' => 2175, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2265, '2' => 1925, '3' => 3010, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2850, '2' => 2425, '3' => 3790, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3405, '2' => 2900, '3' => 4530, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3940, '2' => 3355, '3' => 5240, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4460, '2' => 3795, '3' => 5930, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4960, '2' => 4220, '3' => 6600, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5450, '2' => 4640, '3' => 7250, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5930, '2' => 5050, '3' => 7885, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6400, '2' => 5450, '3' => 8510, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 6860, '2' => 5840, '3' => 9125, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7315, '2' => 6225, '3' => 9730, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 7765, '2' => 6605, '3' => 10325, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8205, '2' => 6980, '3' => 10910, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 8640, '2' => 7350, '3' => 11485, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9065, '2' => 7715, '3' => 12060, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9490, '2' => 8080, '3' => 12620, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 9910, '2' => 8435, '3' => 13180, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10325, '2' => 8790, '3' => 13730, '4' => 4065)),

                ),

                '2' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 1320, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 2300, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 3180, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 4000, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 4785, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 5535, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 6260, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 6965, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 7655, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 8330, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 8990, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 9635, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 10275, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 10900, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 11520, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 12130, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 12735, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 13330, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 13920, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 14500, '4' => 7140)),

                ),

                '3' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1150, '2' => 1220, '3' => 1670, '4' => 720)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2000, '2' => 2125, '3' => 2910, '4' => 1255)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2770, '2' => 2940, '3' => 4020, '4' => 1735)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3485, '2' => 3700, '3' => 5060, '4' => 2185)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4165, '2' => 4420, '3' => 6050, '4' => 2610)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4820, '2' => 5115, '3' => 7000, '4' => 3020)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5455, '2' => 5785, '3' => 7920, '4' => 3415)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6070, '2' => 6440, '3' => 8815, '4' => 3800)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6670, '2' => 7075, '3' => 9685, '4' => 4175)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7255, '2' => 7700, '3' => 10535, '4' => 4545)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7830, '2' => 8310, '3' => 11370, '4' => 4905)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8395, '2' => 8905, '3' => 12190, '4' => 5255)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8950, '2' => 9495, '3' => 13000, '4' => 5605)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9495, '2' => 10075, '3' => 13790, '4' => 5945)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10035, '2' => 10645, '3' => 14575, '4' => 6285)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10570, '2' => 11210, '3' => 15345, '4' => 6615)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11095, '2' => 11770, '3' => 16110, '4' => 6945)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11610, '2' => 12320, '3' => 16865, '4' => 7270)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12125, '2' => 12865, '3' => 17610, '4' => 7590)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12635, '2' => 13400, '3' => 18345, '4' => 7910)),

                ),

                '4' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 540, '2' => 610, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 940, '2' => 1060, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1300, '2' => 1470, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1635, '2' => 1850, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 1955, '2' => 2210, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2265, '2' => 2560, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 2560, '2' => 2895, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 2850, '2' => 3220, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3130, '2' => 3540, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 3405, '2' => 3850, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 3675, '2' => 4155, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 3940, '2' => 4455, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 4205, '2' => 4750, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 4460, '2' => 5040, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 4715, '2' => 5325, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 4960, '2' => 5605, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 5210, '2' => 5885, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 5455, '2' => 6160, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 5695, '2' => 6430, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 5930, '2' => 6700, '3' => 1870, '4' => 2415)),

                ),

                '5' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1315, '2' => 1060, '3' => 815, '4' => 285)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2290, '2' => 1845, '3' => 1415, '4' => 500)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3170, '2' => 2555, '3' => 1960, '4' => 690)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3990, '2' => 3215, '3' => 2465, '4' => 870)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4770, '2' => 3840, '3' => 2945, '4' => 1040)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5520, '2' => 4445, '3' => 3410, '4' => 1200)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6245, '2' => 5030, '3' => 3860, '4' => 1360)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6950, '2' => 5595, '3' => 4295, '4' => 1515)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7635, '2' => 6150, '3' => 4715, '4' => 1665)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8310, '2' => 6690, '3' => 5130, '4' => 1810)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8965, '2' => 7220, '3' => 5540, '4' => 1950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9610, '2' => 7740, '3' => 5940, '4' => 2095)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10250, '2' => 8250, '3' => 6330, '4' => 2230)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10875, '2' => 8755, '3' => 6715, '4' => 2365)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11490, '2' => 9250, '3' => 7100, '4' => 2500)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12100, '2' => 9740, '3' => 7475, '4' => 2635)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12700, '2' => 10225, '3' => 7845, '4' => 2765)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13295, '2' => 10705, '3' => 8215, '4' => 2895)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13885, '2' => 11175, '3' => 8575, '4' => 3025)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14465, '2' => 11645, '3' => 8935, '4' => 3150)),

                ),

                '6' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 990, '2' => 1145, '3' => 1450, '4' => 355)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1720, '2' => 1995, '3' => 2525, '4' => 620)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2380, '2' => 2755, '3' => 3490, '4' => 855)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2995, '2' => 3470, '3' => 4395, '4' => 1075)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3580, '2' => 4150, '3' => 5255, '4' => 1285)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4140, '2' => 4800, '3' => 6080, '4' => 1490)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4685, '2' => 5430, '3' => 6880, '4' => 1685)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5210, '2' => 6045, '3' => 7655, '4' => 1875)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5725, '2' => 6640, '3' => 8410, '4' => 2060)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6230, '2' => 7225, '3' => 9150, '4' => 2240)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6725, '2' => 7795, '3' => 9875, '4' => 2415)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7210, '2' => 8360, '3' => 10585, '4' => 2590)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7685, '2' => 8910, '3' => 11285, '4' => 2765)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8155, '2' => 9455, '3' => 11975, '4' => 2930)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8620, '2' => 9995, '3' => 12655, '4' => 3100)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9075, '2' => 10520, '3' => 13325, '4' => 3260)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9525, '2' => 11045, '3' => 13985, '4' => 3425)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9970, '2' => 11560, '3' => 14640, '4' => 3585)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10410, '2' => 12075, '3' => 15290, '4' => 3745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10850, '2' => 12580, '3' => 15930, '4' => 3900)),

                ),

                '7' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2135, '2' => 875, '3' => 1235, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3715, '2' => 1520, '3' => 2145, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5140, '2' => 2105, '3' => 2970, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6465, '2' => 2645, '3' => 3740, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 7730, '2' => 3165, '3' => 4470, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 8945, '2' => 3660, '3' => 5170, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10120, '2' => 4140, '3' => 5850, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11260, '2' => 4610, '3' => 6510, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 12370, '2' => 5065, '3' => 7155, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 13460, '2' => 5510, '3' => 7780, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 14525, '2' => 5945, '3' => 8400, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 15575, '2' => 6375, '3' => 9005, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 16605, '2' => 6795, '3' => 9600, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 17620, '2' => 7210, '3' => 10185, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 18620, '2' => 7620, '3' => 10765, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 19605, '2' => 8025, '3' => 11335, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 20580, '2' => 8425, '3' => 11895, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 21540, '2' => 8820, '3' => 12455, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 22495, '2' => 9210, '3' => 13005, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 23435, '2' => 9595, '3' => 13550, '4' => 2380)),

                ),

                '8' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1125, '2' => 1590, '3' => 735, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1960, '2' => 2770, '3' => 1275, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2710, '2' => 3835, '3' => 1765, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3410, '2' => 4825, '3' => 2225, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4075, '2' => 5770, '3' => 2660, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4715, '2' => 6675, '3' => 3075, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5335, '2' => 7550, '3' => 3480, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5940, '2' => 8400, '3' => 3870, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6525, '2' => 9230, '3' => 4255, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7100, '2' => 10045, '3' => 4625, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7660, '2' => 10840, '3' => 4995, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8215, '2' => 11620, '3' => 5355, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8755, '2' => 12390, '3' => 5710, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9290, '2' => 13145, '3' => 6055, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9820, '2' => 13890, '3' => 6400, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10340, '2' => 14625, '3' => 6740, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10850, '2' => 15355, '3' => 7075, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11360, '2' => 16070, '3' => 7405, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11860, '2' => 16780, '3' => 7730, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12360, '2' => 17485, '3' => 8055, '4' => 1445)),

                ),


                '11' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 765, '2' => 625, '3' => 480, '4' => 440)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1330, '2' => 1090, '3' => 835, '4' => 765)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1840, '2' => 1505, '3' => 1155, '4' => 1060)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2320, '2' => 1895, '3' => 1455, '4' => 1335)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2770, '2' => 2265, '3' => 1740, '4' => 1595)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3210, '2' => 2620, '3' => 2015, '4' => 1845)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3630, '2' => 2965, '3' => 2275, '4' => 2085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4040, '2' => 3300, '3' => 2535, '4' => 2320)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4435, '2' => 3625, '3' => 2785, '4' => 2550)),

                    array('time_consume' => 74880, 'resources' => array('1' => 4825, '2' => 3945, '3' => 3030, '4' => 2775)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5210, '2' => 4255, '3' => 3270, '4' => 2995)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5585, '2' => 4565, '3' => 3505, '4' => 3210)),

                    array('time_consume' => 96480, 'resources' => array('1' => 5955, '2' => 4865, '3' => 3735, '4' => 3425)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6320, '2' => 5160, '3' => 3965, '4' => 3635)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6675, '2' => 5455, '3' => 4190, '4' => 3840)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7030, '2' => 5745, '3' => 4410, '4' => 4045)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7380, '2' => 6030, '3' => 4630, '4' => 4245)),

                    array('time_consume' => 132480, 'resources' => array('1' => 7725, '2' => 6310, '3' => 4845, '4' => 4445)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8065, '2' => 6590, '3' => 5060, '4' => 4640)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8405, '2' => 6865, '3' => 5275, '4' => 4835)),

                ),

                '12' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1115, '2' => 590, '3' => 795, '4' => 440)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1940, '2' => 1025, '3' => 1385, '4' => 765)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2685, '2' => 1420, '3' => 1915, '4' => 1060)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3380, '2' => 1790, '3' => 2410, '4' => 1335)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4040, '2' => 2140, '3' => 2880, '4' => 1595)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4675, '2' => 2475, '3' => 3335, '4' => 1845)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5290, '2' => 2800, '3' => 3770, '4' => 2085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5885, '2' => 3115, '3' => 4195, '4' => 2320)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6465, '2' => 3420, '3' => 4610, '4' => 2550)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7035, '2' => 3725, '3' => 5015, '4' => 2775)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7595, '2' => 4020, '3' => 5415, '4' => 2995)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8140, '2' => 4305, '3' => 5805, '4' => 3210)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8680, '2' => 4590, '3' => 6190, '4' => 3425)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9210, '2' => 4875, '3' => 6565, '4' => 3635)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9730, '2' => 5150, '3' => 6940, '4' => 3840)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10245, '2' => 5420, '3' => 7305, '4' => 4045)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10755, '2' => 5690, '3' => 7670, '4' => 4245)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11260, '2' => 5960, '3' => 8030, '4' => 4445)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11755, '2' => 6220, '3' => 8380, '4' => 4640)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12250, '2' => 6480, '3' => 8735, '4' => 4835)),

                ),

                '13' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1010, '2' => 940, '3' => 1390, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1760, '2' => 1635, '3' => 2420, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2430, '2' => 2265, '3' => 3345, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3060, '2' => 2850, '3' => 4215, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3660, '2' => 3405, '3' => 5035, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4235, '2' => 3940, '3' => 5830, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4790, '2' => 4460, '3' => 6595, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5330, '2' => 4960, '3' => 7335, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5860, '2' => 5450, '3' => 8060, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6375, '2' => 5930, '3' => 8770, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6880, '2' => 6400, '3' => 9465, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7375, '2' => 6860, '3' => 10150, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7860, '2' => 7315, '3' => 10820, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8340, '2' => 7765, '3' => 11480, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8815, '2' => 8205, '3' => 12130, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9280, '2' => 8640, '3' => 12775, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9745, '2' => 9065, '3' => 13410, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10200, '2' => 9490, '3' => 14035, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10650, '2' => 9910, '3' => 14655, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11095, '2' => 10325, '3' => 15270, '4' => 7140)),

                ),

                '14' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1220, '2' => 800, '3' => 550, '4' => 510)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2125, '2' => 1395, '3' => 960, '4' => 890)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2940, '2' => 1925, '3' => 1325, '4' => 1230)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3700, '2' => 2425, '3' => 1665, '4' => 1545)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4420, '2' => 2900, '3' => 1995, '4' => 1850)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5115, '2' => 3355, '3' => 2305, '4' => 2140)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5785, '2' => 3795, '3' => 2610, '4' => 2420)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6440, '2' => 4220, '3' => 2905, '4' => 2690)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7075, '2' => 4640, '3' => 3190, '4' => 2960)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7700, '2' => 5050, '3' => 3470, '4' => 3220)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8310, '2' => 5450, '3' => 3745, '4' => 3475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8905, '2' => 5840, '3' => 4015, '4' => 3725)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9495, '2' => 6225, '3' => 4280, '4' => 3970)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10075, '2' => 6605, '3' => 4540, '4' => 4210)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10645, '2' => 6980, '3' => 4800, '4' => 4450)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11210, '2' => 7350, '3' => 5055, '4' => 4685)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11770, '2' => 7715, '3' => 5305, '4' => 4920)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12320, '2' => 8080, '3' => 5555, '4' => 5150)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12865, '2' => 8435, '3' => 5800, '4' => 5375)),

                    array('time_consume' => 146880, 'resources' => array('1' => 13400, '2' => 8790, '3' => 6040, '4' => 5605)),

                ),

                '15' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1345, '2' => 995, '3' => 1115, '4' => 345)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2340, '2' => 1730, '3' => 1940, '4' => 595)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3240, '2' => 2395, '3' => 2685, '4' => 825)),

                    array('time_consume' => 31680, 'resources' => array('1' => 4075, '2' => 3015, '3' => 3380, '4' => 1040)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4875, '2' => 3605, '3' => 4040, '4' => 1240)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5640, '2' => 4170, '3' => 4675, '4' => 1435)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6380, '2' => 4720, '3' => 5290, '4' => 1625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 7100, '2' => 5250, '3' => 5885, '4' => 1810)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7800, '2' => 5770, '3' => 6465, '4' => 1985)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8485, '2' => 6280, '3' => 7035, '4' => 2160)),

                    array('time_consume' => 82080, 'resources' => array('1' => 9160, '2' => 6775, '3' => 7595, '4' => 2330)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9820, '2' => 7265, '3' => 8140, '4' => 2500)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10470, '2' => 7745, '3' => 8680, '4' => 2665)),

                    array('time_consume' => 103680, 'resources' => array('1' => 11110, '2' => 8215, '3' => 9210, '4' => 2830)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11740, '2' => 8685, '3' => 9730, '4' => 2990)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12360, '2' => 9145, '3' => 10245, '4' => 3145)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12975, '2' => 9600, '3' => 10755, '4' => 3305)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13580, '2' => 10045, '3' => 11260, '4' => 3460)),

                    array('time_consume' => 139680, 'resources' => array('1' => 14180, '2' => 10490, '3' => 11755, '4' => 3610)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14775, '2' => 10930, '3' => 12250, '4' => 3765)),

                ),

                '16' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1085, '2' => 1235, '3' => 1185, '4' => 240)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1885, '2' => 2150, '3' => 2065, '4' => 420)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2610, '2' => 2975, '3' => 2860, '4' => 580)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3285, '2' => 3745, '3' => 3595, '4' => 730)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3925, '2' => 4475, '3' => 4300, '4' => 870)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4540, '2' => 5180, '3' => 4975, '4' => 1005)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5140, '2' => 5860, '3' => 5630, '4' => 1140)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5720, '2' => 6520, '3' => 6265, '4' => 1265)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6285, '2' => 7160, '3' => 6880, '4' => 1390)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6835, '2' => 7790, '3' => 7485, '4' => 1515)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7375, '2' => 8410, '3' => 8080, '4' => 1635)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7910, '2' => 9015, '3' => 8665, '4' => 1750)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8430, '2' => 9610, '3' => 9235, '4' => 1870)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8945, '2' => 10200, '3' => 9800, '4' => 1980)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9455, '2' => 10780, '3' => 10355, '4' => 2095)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9955, '2' => 11350, '3' => 10905, '4' => 2205)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10450, '2' => 11915, '3' => 11445, '4' => 2315)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10940, '2' => 12470, '3' => 11980, '4' => 2425)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11425, '2' => 13020, '3' => 12510, '4' => 2530)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11900, '2' => 13565, '3' => 13035, '4' => 2635)),

                ),

                '17' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2365, '2' => 735, '3' => 885, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 4120, '2' => 1275, '3' => 1540, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5700, '2' => 1765, '3' => 2125, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 7175, '2' => 2225, '3' => 2680, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 8575, '2' => 2660, '3' => 3200, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 9925, '2' => 3075, '3' => 3705, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 11225, '2' => 3480, '3' => 4190, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 12490, '2' => 3870, '3' => 4660, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 13725, '2' => 4255, '3' => 5125, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 14935, '2' => 4625, '3' => 5575, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 16115, '2' => 4995, '3' => 6015, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 17280, '2' => 5355, '3' => 6450, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 18420, '2' => 5710, '3' => 6875, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 19545, '2' => 6055, '3' => 7295, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 20655, '2' => 6400, '3' => 7710, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 21750, '2' => 6740, '3' => 8115, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 22830, '2' => 7075, '3' => 8520, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 23900, '2' => 7405, '3' => 8920, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 24955, '2' => 7730, '3' => 9315, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 26000, '2' => 8055, '3' => 9705, '4' => 2380)),

                ),

                '18' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1065, '2' => 1415, '3' => 735, '4' => 95)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1855, '2' => 2465, '3' => 1275, '4' => 170)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2570, '2' => 3410, '3' => 1765, '4' => 235)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3235, '2' => 4295, '3' => 2225, '4' => 295)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3865, '2' => 5135, '3' => 2660, '4' => 350)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4470, '2' => 5940, '3' => 3075, '4' => 405)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5060, '2' => 6720, '3' => 3480, '4' => 460)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5630, '2' => 7475, '3' => 3870, '4' => 510)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6185, '2' => 8215, '3' => 4255, '4' => 560)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6730, '2' => 8940, '3' => 4625, '4' => 610)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7265, '2' => 9645, '3' => 4995, '4' => 660)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7785, '2' => 10340, '3' => 5355, '4' => 705)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8300, '2' => 11025, '3' => 5710, '4' => 750)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8810, '2' => 11700, '3' => 6055, '4' => 800)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9310, '2' => 12365, '3' => 6400, '4' => 845)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9800, '2' => 13020, '3' => 6740, '4' => 890)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10290, '2' => 13665, '3' => 7075, '4' => 930)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10770, '2' => 14305, '3' => 7405, '4' => 975)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11245, '2' => 14935, '3' => 7730, '4' => 1020)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11720, '2' => 15565, '3' => 8055, '4' => 1060)),

                ),


                '21' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 585, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 1020, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 1410, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 1775, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 2120, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 2455, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 2775, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 3090, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 3395, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 3690, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 3985, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 4270, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 4555, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 4830, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 5105, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 5375, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 5645, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 5905, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 6170, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 6425, '4' => 4065)),

                ),

                '22' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1080, '2' => 1150, '3' => 1495, '4' => 580)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1880, '2' => 2000, '3' => 2605, '4' => 1010)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2600, '2' => 2770, '3' => 3600, '4' => 1395)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3275, '2' => 3485, '3' => 4530, '4' => 1760)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3915, '2' => 4165, '3' => 5420, '4' => 2100)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4530, '2' => 4820, '3' => 6270, '4' => 2430)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5125, '2' => 5455, '3' => 7090, '4' => 2750)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5700, '2' => 6070, '3' => 7890, '4' => 3060)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6265, '2' => 6670, '3' => 8670, '4' => 3365)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6815, '2' => 7255, '3' => 9435, '4' => 3660)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7355, '2' => 7830, '3' => 10180, '4' => 3950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7885, '2' => 8395, '3' => 10915, '4' => 4235)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8405, '2' => 8950, '3' => 11635, '4' => 4515)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8920, '2' => 9495, '3' => 12345, '4' => 4790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9425, '2' => 10035, '3' => 13045, '4' => 5060)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9925, '2' => 10570, '3' => 13740, '4' => 5330)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10420, '2' => 11095, '3' => 14420, '4' => 5595)),

                    array('time_consume' => 132480, 'resources' => array('1' => 10905, '2' => 11610, '3' => 15095, '4' => 5855)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11385, '2' => 12125, '3' => 15765, '4' => 6115)),

                    array('time_consume' => 146880, 'resources' => array('1' => 11865, '2' => 12635, '3' => 16425, '4' => 6370)),

                ),

                '23' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 645, '2' => 575, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1125, '2' => 1000, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1555, '2' => 1385, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1955, '2' => 1745, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2335, '2' => 2085, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2705, '2' => 2410, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3060, '2' => 2725, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 3405, '2' => 3035, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3740, '2' => 3335, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 4070, '2' => 3630, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 4390, '2' => 3915, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 4710, '2' => 4200, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 5020, '2' => 4475, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 5325, '2' => 4750, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 5630, '2' => 5020, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 5925, '2' => 5285, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 6220, '2' => 5545, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 6515, '2' => 5805, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 6800, '2' => 6065, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 7085, '2' => 6315, '3' => 1870, '4' => 2415)),

                ),

                '24' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1275, '2' => 1625, '3' => 905, '4' => 290)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2220, '2' => 2830, '3' => 1575, '4' => 505)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3070, '2' => 3915, '3' => 2180, '4' => 700)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3865, '2' => 4925, '3' => 2745, '4' => 880)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4620, '2' => 5890, '3' => 3280, '4' => 1050)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5345, '2' => 6815, '3' => 3795, '4' => 1215)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6050, '2' => 7710, '3' => 4295, '4' => 1375)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6730, '2' => 8575, '3' => 4775, '4' => 1530)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7395, '2' => 9425, '3' => 5250, '4' => 1680)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8045, '2' => 10255, '3' => 5710, '4' => 1830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8680, '2' => 11065, '3' => 6165, '4' => 1975)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9310, '2' => 11865, '3' => 6605, '4' => 2115)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9925, '2' => 12650, '3' => 7045, '4' => 2255)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10530, '2' => 13420, '3' => 7475, '4' => 2395)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11125, '2' => 14180, '3' => 7900, '4' => 2530)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11715, '2' => 14935, '3' => 8315, '4' => 2665)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12300, '2' => 15675, '3' => 8730, '4' => 2795)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12875, '2' => 16410, '3' => 9140, '4' => 2930)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13445, '2' => 17135, '3' => 9540, '4' => 3060)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14005, '2' => 17850, '3' => 9940, '4' => 3185)),

                ),

                '25' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1310, '2' => 1205, '3' => 1080, '4' => 500)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2280, '2' => 2100, '3' => 1880, '4' => 870)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3155, '2' => 2900, '3' => 2600, '4' => 1205)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3970, '2' => 3655, '3' => 3275, '4' => 1515)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4745, '2' => 4365, '3' => 3915, '4' => 1810)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5495, '2' => 5055, '3' => 4530, '4' => 2095)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6215, '2' => 5715, '3' => 5125, '4' => 2370)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6915, '2' => 6360, '3' => 5700, '4' => 2640)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7595, '2' => 6990, '3' => 6265, '4' => 2900)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8265, '2' => 7605, '3' => 6815, '4' => 3155)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8920, '2' => 8205, '3' => 7355, '4' => 3405)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9565, '2' => 8795, '3' => 7885, '4' => 3650)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10195, '2' => 9380, '3' => 8405, '4' => 3890)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10820, '2' => 9950, '3' => 8920, '4' => 4130)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11435, '2' => 10515, '3' => 9425, '4' => 4365)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12040, '2' => 11075, '3' => 9925, '4' => 4595)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12635, '2' => 11625, '3' => 10420, '4' => 4825)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13230, '2' => 12170, '3' => 10905, '4' => 5050)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13815, '2' => 12705, '3' => 11385, '4' => 5270)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14390, '2' => 13240, '3' => 11865, '4' => 5495)),

                ),

                '26' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1200, '2' => 1480, '3' => 1640, '4' => 450)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2090, '2' => 2575, '3' => 2860, '4' => 785)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2890, '2' => 3565, '3' => 3955, '4' => 1085)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3640, '2' => 4485, '3' => 4975, '4' => 1365)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4350, '2' => 5365, '3' => 5950, '4' => 1630)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5030, '2' => 6205, '3' => 6885, '4' => 1885)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5690, '2' => 7020, '3' => 7785, '4' => 2135)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6335, '2' => 7810, '3' => 8665, '4' => 2375)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6960, '2' => 8585, '3' => 9520, '4' => 2610)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7570, '2' => 9340, '3' => 10360, '4' => 2840)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8170, '2' => 10080, '3' => 11180, '4' => 3065)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8760, '2' => 10805, '3' => 11985, '4' => 3285)),

                    array('time_consume' => 96480, 'resources' => array('1' => 9340, '2' => 11520, '3' => 12775, '4' => 3500)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9910, '2' => 12225, '3' => 13560, '4' => 3715)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10475, '2' => 12915, '3' => 14325, '4' => 3925)),

                    array('time_consume' => 118080, 'resources' => array('1' => 11030, '2' => 13600, '3' => 15085, '4' => 4135)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11575, '2' => 14275, '3' => 15835, '4' => 4340)),

                    array('time_consume' => 132480, 'resources' => array('1' => 12115, '2' => 14945, '3' => 16575, '4' => 4545)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12655, '2' => 15605, '3' => 17310, '4' => 4745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 13185, '2' => 16260, '3' => 18035, '4' => 4945)),

                ),

                '27' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2250, '2' => 1330, '3' => 835, '4' => 230)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3915, '2' => 2315, '3' => 1455, '4' => 400)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5420, '2' => 3200, '3' => 2015, '4' => 550)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6820, '2' => 4025, '3' => 2535, '4' => 690)),

                    array('time_consume' => 38880, 'resources' => array('1' => 8155, '2' => 4815, '3' => 3030, '4' => 825)),

                    array('time_consume' => 46080, 'resources' => array('1' => 9435, '2' => 5570, '3' => 3510, '4' => 955)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10670, '2' => 6300, '3' => 3970, '4' => 1085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11875, '2' => 7010, '3' => 4415, '4' => 1205)),

                    array('time_consume' => 67680, 'resources' => array('1' => 13050, '2' => 7705, '3' => 4850, '4' => 1325)),

                    array('time_consume' => 74880, 'resources' => array('1' => 14195, '2' => 8380, '3' => 5280, '4' => 1440)),

                    array('time_consume' => 82080, 'resources' => array('1' => 15320, '2' => 9045, '3' => 5695, '4' => 1555)),

                    array('time_consume' => 89280, 'resources' => array('1' => 16425, '2' => 9695, '3' => 6110, '4' => 1665)),

                    array('time_consume' => 96480, 'resources' => array('1' => 17510, '2' => 10340, '3' => 6510, '4' => 1775)),

                    array('time_consume' => 103680, 'resources' => array('1' => 18580, '2' => 10970, '3' => 6910, '4' => 1885)),

                    array('time_consume' => 110880, 'resources' => array('1' => 19635, '2' => 11595, '3' => 7300, '4' => 1995)),

                    array('time_consume' => 118080, 'resources' => array('1' => 20675, '2' => 12205, '3' => 7690, '4' => 2100)),

                    array('time_consume' => 125280, 'resources' => array('1' => 21705, '2' => 12815, '3' => 8070, '4' => 2205)),

                    array('time_consume' => 132480, 'resources' => array('1' => 22720, '2' => 13415, '3' => 8450, '4' => 2305)),

                    array('time_consume' => 139680, 'resources' => array('1' => 23725, '2' => 14005, '3' => 8820, '4' => 2410)),

                    array('time_consume' => 146880, 'resources' => array('1' => 24720, '2' => 14595, '3' => 9190, '4' => 2510)),

                ),

                '28' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1135, '2' => 1710, '3' => 770, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1980, '2' => 2975, '3' => 1340, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2735, '2' => 4115, '3' => 1850, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3445, '2' => 5180, '3' => 2330, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4120, '2' => 6190, '3' => 2785, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4765, '2' => 7165, '3' => 3220, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5390, '2' => 8105, '3' => 3645, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6000, '2' => 9015, '3' => 4055, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6590, '2' => 9910, '3' => 4455, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7170, '2' => 10780, '3' => 4850, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7740, '2' => 11635, '3' => 5230, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8300, '2' => 12470, '3' => 5610, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8845, '2' => 13295, '3' => 5980, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9385, '2' => 14110, '3' => 6345, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9920, '2' => 14910, '3' => 6705, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10445, '2' => 15700, '3' => 7060, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10965, '2' => 16480, '3' => 7410, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11480, '2' => 17250, '3' => 7760, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11985, '2' => 18015, '3' => 8100, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12485, '2' => 18765, '3' => 8440, '4' => 1445)),

                ),


                '100' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 940, '2' => 800, '3' => 1250, '4' => 370)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1635, '2' => 1395, '3' => 2175, '4' => 645)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2265, '2' => 1925, '3' => 3010, '4' => 890)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2850, '2' => 2425, '3' => 3790, '4' => 1120)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3405, '2' => 2900, '3' => 4530, '4' => 1340)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3940, '2' => 3355, '3' => 5240, '4' => 1550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4460, '2' => 3795, '3' => 5930, '4' => 1755)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4960, '2' => 4220, '3' => 6600, '4' => 1955)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5450, '2' => 4640, '3' => 7250, '4' => 2145)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5930, '2' => 5050, '3' => 7885, '4' => 2335)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6400, '2' => 5450, '3' => 8510, '4' => 2520)),

                    array('time_consume' => 89280, 'resources' => array('1' => 6860, '2' => 5840, '3' => 9125, '4' => 2700)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7315, '2' => 6225, '3' => 9730, '4' => 2880)),

                    array('time_consume' => 103680, 'resources' => array('1' => 7765, '2' => 6605, '3' => 10325, '4' => 3055)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8205, '2' => 6980, '3' => 10910, '4' => 3230)),

                    array('time_consume' => 118080, 'resources' => array('1' => 8640, '2' => 7350, '3' => 11485, '4' => 3400)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9065, '2' => 7715, '3' => 12060, '4' => 3570)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9490, '2' => 8080, '3' => 12620, '4' => 3735)),

                    array('time_consume' => 139680, 'resources' => array('1' => 9910, '2' => 8435, '3' => 13180, '4' => 3900)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10325, '2' => 8790, '3' => 13730, '4' => 4065)),

                ),

                '101' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 800, '2' => 1010, '3' => 1320, '4' => 650)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1395, '2' => 1760, '3' => 2300, '4' => 1130)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1925, '2' => 2430, '3' => 3180, '4' => 1565)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2425, '2' => 3060, '3' => 4000, '4' => 1970)),

                    array('time_consume' => 38880, 'resources' => array('1' => 2900, '2' => 3660, '3' => 4785, '4' => 2355)),

                    array('time_consume' => 46080, 'resources' => array('1' => 3355, '2' => 4235, '3' => 5535, '4' => 2725)),

                    array('time_consume' => 53280, 'resources' => array('1' => 3795, '2' => 4790, '3' => 6260, '4' => 3085)),

                    array('time_consume' => 60480, 'resources' => array('1' => 4220, '2' => 5330, '3' => 6965, '4' => 3430)),

                    array('time_consume' => 67680, 'resources' => array('1' => 4640, '2' => 5860, '3' => 7655, '4' => 3770)),

                    array('time_consume' => 74880, 'resources' => array('1' => 5050, '2' => 6375, '3' => 8330, '4' => 4100)),

                    array('time_consume' => 82080, 'resources' => array('1' => 5450, '2' => 6880, '3' => 8990, '4' => 4425)),

                    array('time_consume' => 89280, 'resources' => array('1' => 5840, '2' => 7375, '3' => 9635, '4' => 4745)),

                    array('time_consume' => 96480, 'resources' => array('1' => 6225, '2' => 7860, '3' => 10275, '4' => 5060)),

                    array('time_consume' => 103680, 'resources' => array('1' => 6605, '2' => 8340, '3' => 10900, '4' => 5370)),

                    array('time_consume' => 110880, 'resources' => array('1' => 6980, '2' => 8815, '3' => 11520, '4' => 5675)),

                    array('time_consume' => 118080, 'resources' => array('1' => 7350, '2' => 9280, '3' => 12130, '4' => 5975)),

                    array('time_consume' => 125280, 'resources' => array('1' => 7715, '2' => 9745, '3' => 12735, '4' => 6270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 8080, '2' => 10200, '3' => 13330, '4' => 6565)),

                    array('time_consume' => 139680, 'resources' => array('1' => 8435, '2' => 10650, '3' => 13920, '4' => 6855)),

                    array('time_consume' => 146880, 'resources' => array('1' => 8790, '2' => 11095, '3' => 14500, '4' => 7140)),

                ),

                '102' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1150, '2' => 1220, '3' => 1670, '4' => 720)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2000, '2' => 2125, '3' => 2910, '4' => 1255)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2770, '2' => 2940, '3' => 4020, '4' => 1735)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3485, '2' => 3700, '3' => 5060, '4' => 2185)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4165, '2' => 4420, '3' => 6050, '4' => 2610)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4820, '2' => 5115, '3' => 7000, '4' => 3020)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5455, '2' => 5785, '3' => 7920, '4' => 3415)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6070, '2' => 6440, '3' => 8815, '4' => 3800)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6670, '2' => 7075, '3' => 9685, '4' => 4175)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7255, '2' => 7700, '3' => 10535, '4' => 4545)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7830, '2' => 8310, '3' => 11370, '4' => 4905)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8395, '2' => 8905, '3' => 12190, '4' => 5255)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8950, '2' => 9495, '3' => 13000, '4' => 5605)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9495, '2' => 10075, '3' => 13790, '4' => 5945)),

                    array('time_consume' => 110880, 'resources' => array('1' => 10035, '2' => 10645, '3' => 14575, '4' => 6285)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10570, '2' => 11210, '3' => 15345, '4' => 6615)),

                    array('time_consume' => 125280, 'resources' => array('1' => 11095, '2' => 11770, '3' => 16110, '4' => 6945)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11610, '2' => 12320, '3' => 16865, '4' => 7270)),

                    array('time_consume' => 139680, 'resources' => array('1' => 12125, '2' => 12865, '3' => 17610, '4' => 7590)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12635, '2' => 13400, '3' => 18345, '4' => 7910)),

                ),

                '103' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 540, '2' => 610, '3' => 170, '4' => 220)),

                    array('time_consume' => 17280, 'resources' => array('1' => 940, '2' => 1060, '3' => 295, '4' => 385)),

                    array('time_consume' => 24480, 'resources' => array('1' => 1300, '2' => 1470, '3' => 410, '4' => 530)),

                    array('time_consume' => 31680, 'resources' => array('1' => 1635, '2' => 1850, '3' => 515, '4' => 665)),

                    array('time_consume' => 38880, 'resources' => array('1' => 1955, '2' => 2210, '3' => 615, '4' => 795)),

                    array('time_consume' => 46080, 'resources' => array('1' => 2265, '2' => 2560, '3' => 715, '4' => 920)),

                    array('time_consume' => 53280, 'resources' => array('1' => 2560, '2' => 2895, '3' => 805, '4' => 1045)),

                    array('time_consume' => 60480, 'resources' => array('1' => 2850, '2' => 3220, '3' => 895, '4' => 1160)),

                    array('time_consume' => 67680, 'resources' => array('1' => 3130, '2' => 3540, '3' => 985, '4' => 1275)),

                    array('time_consume' => 74880, 'resources' => array('1' => 3405, '2' => 3850, '3' => 1075, '4' => 1390)),

                    array('time_consume' => 82080, 'resources' => array('1' => 3675, '2' => 4155, '3' => 1160, '4' => 1500)),

                    array('time_consume' => 89280, 'resources' => array('1' => 3940, '2' => 4455, '3' => 1240, '4' => 1605)),

                    array('time_consume' => 96480, 'resources' => array('1' => 4205, '2' => 4750, '3' => 1325, '4' => 1710)),

                    array('time_consume' => 103680, 'resources' => array('1' => 4460, '2' => 5040, '3' => 1405, '4' => 1815)),

                    array('time_consume' => 110880, 'resources' => array('1' => 4715, '2' => 5325, '3' => 1485, '4' => 1920)),

                    array('time_consume' => 118080, 'resources' => array('1' => 4960, '2' => 5605, '3' => 1560, '4' => 2020)),

                    array('time_consume' => 125280, 'resources' => array('1' => 5210, '2' => 5885, '3' => 1640, '4' => 2120)),

                    array('time_consume' => 132480, 'resources' => array('1' => 5455, '2' => 6160, '3' => 1715, '4' => 2220)),

                    array('time_consume' => 139680, 'resources' => array('1' => 5695, '2' => 6430, '3' => 1790, '4' => 2320)),

                    array('time_consume' => 146880, 'resources' => array('1' => 5930, '2' => 6700, '3' => 1870, '4' => 2415)),

                ),

                '104' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1315, '2' => 1060, '3' => 815, '4' => 285)),

                    array('time_consume' => 17280, 'resources' => array('1' => 2290, '2' => 1845, '3' => 1415, '4' => 500)),

                    array('time_consume' => 24480, 'resources' => array('1' => 3170, '2' => 2555, '3' => 1960, '4' => 690)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3990, '2' => 3215, '3' => 2465, '4' => 870)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4770, '2' => 3840, '3' => 2945, '4' => 1040)),

                    array('time_consume' => 46080, 'resources' => array('1' => 5520, '2' => 4445, '3' => 3410, '4' => 1200)),

                    array('time_consume' => 53280, 'resources' => array('1' => 6245, '2' => 5030, '3' => 3860, '4' => 1360)),

                    array('time_consume' => 60480, 'resources' => array('1' => 6950, '2' => 5595, '3' => 4295, '4' => 1515)),

                    array('time_consume' => 67680, 'resources' => array('1' => 7635, '2' => 6150, '3' => 4715, '4' => 1665)),

                    array('time_consume' => 74880, 'resources' => array('1' => 8310, '2' => 6690, '3' => 5130, '4' => 1810)),

                    array('time_consume' => 82080, 'resources' => array('1' => 8965, '2' => 7220, '3' => 5540, '4' => 1950)),

                    array('time_consume' => 89280, 'resources' => array('1' => 9610, '2' => 7740, '3' => 5940, '4' => 2095)),

                    array('time_consume' => 96480, 'resources' => array('1' => 10250, '2' => 8250, '3' => 6330, '4' => 2230)),

                    array('time_consume' => 103680, 'resources' => array('1' => 10875, '2' => 8755, '3' => 6715, '4' => 2365)),

                    array('time_consume' => 110880, 'resources' => array('1' => 11490, '2' => 9250, '3' => 7100, '4' => 2500)),

                    array('time_consume' => 118080, 'resources' => array('1' => 12100, '2' => 9740, '3' => 7475, '4' => 2635)),

                    array('time_consume' => 125280, 'resources' => array('1' => 12700, '2' => 10225, '3' => 7845, '4' => 2765)),

                    array('time_consume' => 132480, 'resources' => array('1' => 13295, '2' => 10705, '3' => 8215, '4' => 2895)),

                    array('time_consume' => 139680, 'resources' => array('1' => 13885, '2' => 11175, '3' => 8575, '4' => 3025)),

                    array('time_consume' => 146880, 'resources' => array('1' => 14465, '2' => 11645, '3' => 8935, '4' => 3150)),

                ),

                '105' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 990, '2' => 1145, '3' => 1450, '4' => 355)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1720, '2' => 1995, '3' => 2525, '4' => 620)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2380, '2' => 2755, '3' => 3490, '4' => 855)),

                    array('time_consume' => 31680, 'resources' => array('1' => 2995, '2' => 3470, '3' => 4395, '4' => 1075)),

                    array('time_consume' => 38880, 'resources' => array('1' => 3580, '2' => 4150, '3' => 5255, '4' => 1285)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4140, '2' => 4800, '3' => 6080, '4' => 1490)),

                    array('time_consume' => 53280, 'resources' => array('1' => 4685, '2' => 5430, '3' => 6880, '4' => 1685)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5210, '2' => 6045, '3' => 7655, '4' => 1875)),

                    array('time_consume' => 67680, 'resources' => array('1' => 5725, '2' => 6640, '3' => 8410, '4' => 2060)),

                    array('time_consume' => 74880, 'resources' => array('1' => 6230, '2' => 7225, '3' => 9150, '4' => 2240)),

                    array('time_consume' => 82080, 'resources' => array('1' => 6725, '2' => 7795, '3' => 9875, '4' => 2415)),

                    array('time_consume' => 89280, 'resources' => array('1' => 7210, '2' => 8360, '3' => 10585, '4' => 2590)),

                    array('time_consume' => 96480, 'resources' => array('1' => 7685, '2' => 8910, '3' => 11285, '4' => 2765)),

                    array('time_consume' => 103680, 'resources' => array('1' => 8155, '2' => 9455, '3' => 11975, '4' => 2930)),

                    array('time_consume' => 110880, 'resources' => array('1' => 8620, '2' => 9995, '3' => 12655, '4' => 3100)),

                    array('time_consume' => 118080, 'resources' => array('1' => 9075, '2' => 10520, '3' => 13325, '4' => 3260)),

                    array('time_consume' => 125280, 'resources' => array('1' => 9525, '2' => 11045, '3' => 13985, '4' => 3425)),

                    array('time_consume' => 132480, 'resources' => array('1' => 9970, '2' => 11560, '3' => 14640, '4' => 3585)),

                    array('time_consume' => 139680, 'resources' => array('1' => 10410, '2' => 12075, '3' => 15290, '4' => 3745)),

                    array('time_consume' => 146880, 'resources' => array('1' => 10850, '2' => 12580, '3' => 15930, '4' => 3900)),

                ),

                '106' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 2135, '2' => 875, '3' => 1235, '4' => 215)),

                    array('time_consume' => 17280, 'resources' => array('1' => 3715, '2' => 1520, '3' => 2145, '4' => 375)),

                    array('time_consume' => 24480, 'resources' => array('1' => 5140, '2' => 2105, '3' => 2970, '4' => 520)),

                    array('time_consume' => 31680, 'resources' => array('1' => 6465, '2' => 2645, '3' => 3740, '4' => 655)),

                    array('time_consume' => 38880, 'resources' => array('1' => 7730, '2' => 3165, '3' => 4470, '4' => 785)),

                    array('time_consume' => 46080, 'resources' => array('1' => 8945, '2' => 3660, '3' => 5170, '4' => 910)),

                    array('time_consume' => 53280, 'resources' => array('1' => 10120, '2' => 4140, '3' => 5850, '4' => 1030)),

                    array('time_consume' => 60480, 'resources' => array('1' => 11260, '2' => 4610, '3' => 6510, '4' => 1145)),

                    array('time_consume' => 67680, 'resources' => array('1' => 12370, '2' => 5065, '3' => 7155, '4' => 1255)),

                    array('time_consume' => 74880, 'resources' => array('1' => 13460, '2' => 5510, '3' => 7780, '4' => 1365)),

                    array('time_consume' => 82080, 'resources' => array('1' => 14525, '2' => 5945, '3' => 8400, '4' => 1475)),

                    array('time_consume' => 89280, 'resources' => array('1' => 15575, '2' => 6375, '3' => 9005, '4' => 1580)),

                    array('time_consume' => 96480, 'resources' => array('1' => 16605, '2' => 6795, '3' => 9600, '4' => 1685)),

                    array('time_consume' => 103680, 'resources' => array('1' => 17620, '2' => 7210, '3' => 10185, '4' => 1790)),

                    array('time_consume' => 110880, 'resources' => array('1' => 18620, '2' => 7620, '3' => 10765, '4' => 1890)),

                    array('time_consume' => 118080, 'resources' => array('1' => 19605, '2' => 8025, '3' => 11335, '4' => 1990)),

                    array('time_consume' => 125280, 'resources' => array('1' => 20580, '2' => 8425, '3' => 11895, '4' => 2090)),

                    array('time_consume' => 132480, 'resources' => array('1' => 21540, '2' => 8820, '3' => 12455, '4' => 2190)),

                    array('time_consume' => 139680, 'resources' => array('1' => 22495, '2' => 9210, '3' => 13005, '4' => 2285)),

                    array('time_consume' => 146880, 'resources' => array('1' => 23435, '2' => 9595, '3' => 13550, '4' => 2380)),

                ),

                '107' => array(

                    array('time_consume' => 10080, 'resources' => array('1' => 1125, '2' => 1590, '3' => 735, '4' => 130)),

                    array('time_consume' => 17280, 'resources' => array('1' => 1960, '2' => 2770, '3' => 1275, '4' => 230)),

                    array('time_consume' => 24480, 'resources' => array('1' => 2710, '2' => 3835, '3' => 1765, '4' => 315)),

                    array('time_consume' => 31680, 'resources' => array('1' => 3410, '2' => 4825, '3' => 2225, '4' => 400)),

                    array('time_consume' => 38880, 'resources' => array('1' => 4075, '2' => 5770, '3' => 2660, '4' => 475)),

                    array('time_consume' => 46080, 'resources' => array('1' => 4715, '2' => 6675, '3' => 3075, '4' => 550)),

                    array('time_consume' => 53280, 'resources' => array('1' => 5335, '2' => 7550, '3' => 3480, '4' => 625)),

                    array('time_consume' => 60480, 'resources' => array('1' => 5940, '2' => 8400, '3' => 3870, '4' => 695)),

                    array('time_consume' => 67680, 'resources' => array('1' => 6525, '2' => 9230, '3' => 4255, '4' => 765)),

                    array('time_consume' => 74880, 'resources' => array('1' => 7100, '2' => 10045, '3' => 4625, '4' => 830)),

                    array('time_consume' => 82080, 'resources' => array('1' => 7660, '2' => 10840, '3' => 4995, '4' => 895)),

                    array('time_consume' => 89280, 'resources' => array('1' => 8215, '2' => 11620, '3' => 5355, '4' => 960)),

                    array('time_consume' => 96480, 'resources' => array('1' => 8755, '2' => 12390, '3' => 5710, '4' => 1025)),

                    array('time_consume' => 103680, 'resources' => array('1' => 9290, '2' => 13145, '3' => 6055, '4' => 1085)),

                    array('time_consume' => 110880, 'resources' => array('1' => 9820, '2' => 13890, '3' => 6400, '4' => 1150)),

                    array('time_consume' => 118080, 'resources' => array('1' => 10340, '2' => 14625, '3' => 6740, '4' => 1210)),

                    array('time_consume' => 125280, 'resources' => array('1' => 10850, '2' => 15355, '3' => 7075, '4' => 1270)),

                    array('time_consume' => 132480, 'resources' => array('1' => 11360, '2' => 16070, '3' => 7405, '4' => 1330)),

                    array('time_consume' => 139680, 'resources' => array('1' => 11860, '2' => 16780, '3' => 7730, '4' => 1390)),

                    array('time_consume' => 146880, 'resources' => array('1' => 12360, '2' => 17485, '3' => 8055, '4' => 1445)),

                ),

            ),


            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 3, '22' => 1),

            'levels' => array(


                array(

                    'value' => 102, 'time_consume' => 2000, 'cp' => 2, 'people_inc' => 4,

                    'resources' => array('1' => 260, '2' => 420, '3' => 820, '4' => 260)

                ),


                array(

                    'value' => 104, 'time_consume' => 2620, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 330, '2' => 540, '3' => 1050, '4' => 330)

                ),


                array(

                    'value' => 106, 'time_consume' => 3340, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 430, '2' => 690, '3' => 1340, '4' => 430)

                ),


                array(

                    'value' => 108, 'time_consume' => 4170, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 550, '2' => 880, '3' => 1720, '4' => 550)

                ),


                array(

                    'value' => 110, 'time_consume' => 5140, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 700, '2' => 1130, '3' => 2200, '4' => 700)

                ),


                array(

                    'value' => 112, 'time_consume' => 6260, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 890, '2' => 1440, '3' => 2820, '4' => 890)

                ),


                array(

                    'value' => 114, 'time_consume' => 7570, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 1140, '2' => 1850, '3' => 3610, '4' => 1140)

                ),


                array(

                    'value' => 116, 'time_consume' => 9080, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 1460, '2' => 2360, '3' => 4620, '4' => 1460)

                ),


                array(

                    'value' => 118, 'time_consume' => 10830, 'cp' => 10, 'people_inc' => 3,

                    'resources' => array('1' => 1870, '2' => 3030, '3' => 5910, '4' => 1870)

                ),


                array(

                    'value' => 120, 'time_consume' => 12860, 'cp' => 12, 'people_inc' => 3,

                    'resources' => array('1' => 2400, '2' => 3870, '3' => 7560, '4' => 2400)

                ),


                array(

                    'value' => 122, 'time_consume' => 15220, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 3070, '2' => 4960, '3' => 9680, '4' => 3070)

                ),


                array(

                    'value' => 124, 'time_consume' => 17950, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 3930, '2' => 6350, '3' => 12390, '4' => 3930)

                ),


                array(

                    'value' => 126, 'time_consume' => 21130, 'cp' => 21, 'people_inc' => 3,

                    'resources' => array('1' => 5030, '2' => 8120, '3' => 15860, '4' => 5030)

                ),


                array(

                    'value' => 128, 'time_consume' => 24810, 'cp' => 26, 'people_inc' => 3,

                    'resources' => array('1' => 6440, '2' => 10400, '3' => 20300, '4' => 6440)

                ),


                array(

                    'value' => 130, 'time_consume' => 29080, 'cp' => 31, 'people_inc' => 3,

                    'resources' => array('1' => 8240, '2' => 13310, '3' => 25990, '4' => 8240)

                ),


                array(

                    'value' => 132, 'time_consume' => 34030, 'cp' => 37, 'people_inc' => 4,

                    'resources' => array('1' => 10550, '2' => 17040, '3' => 33260, '4' => 10550)

                ),


                array(

                    'value' => 134, 'time_consume' => 39770, 'cp' => 44, 'people_inc' => 4,

                    'resources' => array('1' => 13500, '2' => 21810, '3' => 42580, '4' => 13500)

                ),


                array(

                    'value' => 136, 'time_consume' => 46440, 'cp' => 53, 'people_inc' => 4,

                    'resources' => array('1' => 17280, '2' => 27910, '3' => 54500, '4' => 17280)

                ),


                array(

                    'value' => 138, 'time_consume' => 54170, 'cp' => 64, 'people_inc' => 4,

                    'resources' => array('1' => 22120, '2' => 35730, '3' => 69760, '4' => 22120)

                ),


                array(

                    'value' => 140, 'time_consume' => 63130, 'cp' => 77, 'people_inc' => 4,

                    'resources' => array('1' => 28310, '2' => 45730, '3' => 89290, '4' => 28310)

                ),


            )

        ),


        '14' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('16' => 15),

            'levels' => array(


                array(

                    'value' => 110, 'time_consume' => 3500, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 1750, '2' => 2250, '3' => 1530, '4' => 240)

                ),


                array(

                    'value' => 120, 'time_consume' => 4360, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 2240, '2' => 2880, '3' => 1960, '4' => 305)

                ),


                array(

                    'value' => 130, 'time_consume' => 5360, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 2865, '2' => 3685, '3' => 2505, '4' => 395)

                ),


                array(

                    'value' => 140, 'time_consume' => 6510, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 3670, '2' => 4720, '3' => 3210, '4' => 505)

                ),


                array(

                    'value' => 150, 'time_consume' => 7860, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 4700, '2' => 6040, '3' => 4105, '4' => 645)

                ),


                array(

                    'value' => 160, 'time_consume' => 9410, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 6015, '2' => 7730, '3' => 5255, '4' => 825)

                ),


                array(

                    'value' => 170, 'time_consume' => 11220, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 7695, '2' => 9895, '3' => 6730, '4' => 1055)

                ),


                array(

                    'value' => 180, 'time_consume' => 13320, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 9850, '2' => 12665, '3' => 8615, '4' => 1350)

                ),


                array(

                    'value' => 190, 'time_consume' => 15750, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 12610, '2' => 16215, '3' => 11025, '4' => 1730)

                ),


                array(

                    'value' => 200, 'time_consume' => 18570, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 16140, '2' => 20755, '3' => 14110, '4' => 2215)

                ),


                array(

                    'value' => 210, 'time_consume' => 21840, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 20660, '2' => 26565, '3' => 18065, '4' => 2835)

                ),


                array(

                    'value' => 220, 'time_consume' => 25630, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 26445, '2' => 34000, '3' => 23120, '4' => 3625)

                ),


                array(

                    'value' => 230, 'time_consume' => 30030, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 33850, '2' => 43520, '3' => 29595, '4' => 4640)

                ),


                array(

                    'value' => 240, 'time_consume' => 35140, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 43330, '2' => 55705, '3' => 37880, '4' => 5940)

                ),


                array(

                    'value' => 250, 'time_consume' => 41060, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 55460, '2' => 71305, '3' => 48490, '4' => 7605)

                ),


                array(

                    'value' => 260, 'time_consume' => 47930, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 70990, '2' => 91270, '3' => 62065, '4' => 9735)

                ),


                array(

                    'value' => 270, 'time_consume' => 55900, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 90865, '2' => 116825, '3' => 79440, '4' => 12460)

                ),


                array(

                    'value' => 280, 'time_consume' => 65140, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 116305, '2' => 149540, '3' => 101685, '4' => 15950)

                ),


                array(

                    'value' => 290, 'time_consume' => 75860, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 148875, '2' => 191410, '3' => 130160, '4' => 20415)

                ),


                array(

                    'value' => 300, 'time_consume' => 88300, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 190560, '2' => 245005, '3' => 166600, '4' => 26135)

                ),


            )

        ),


        '15' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 4000, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 70, '2' => 40, '3' => 60, '4' => 20)

                ),


                array(

                    'value' => 96, 'time_consume' => 2620, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 90, '2' => 50, '3' => 75, '4' => 25)

                ),


                array(

                    'value' => 93, 'time_consume' => 3354, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 115, '2' => 65, '3' => 100, '4' => 35)

                ),


                array(

                    'value' => 90, 'time_consume' => 4172, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 145, '2' => 85, '3' => 125, '4' => 40)

                ),


                array(

                    'value' => 86, 'time_consume' => 5122, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 190, '2' => 105, '3' => 160, '4' => 55)

                ),


                array(

                    'value' => 83, 'time_consume' => 6290, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 240, '2' => 135, '3' => 205, '4' => 70)

                ),


                array(

                    'value' => 80, 'time_consume' => 7590, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 310, '2' => 175, '3' => 265, '4' => 90)

                ),


                array(

                    'value' => 77, 'time_consume' => 9100, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 395, '2' => 225, '3' => 340, '4' => 115)

                ),


                array(

                    'value' => 75, 'time_consume' => 10883, 'cp' => 10, 'people_inc' => 2,

                    'resources' => array('1' => 505, '2' => 290, '3' => 430, '4' => 145)

                ),


                array(

                    'value' => 72, 'time_consume' => 12786, 'cp' => 12, 'people_inc' => 2,

                    'resources' => array('1' => 645, '2' => 370, '3' => 555, '4' => 185)

                ),


                array(

                    'value' => 69, 'time_consume' => 15194, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 825, '2' => 470, '3' => 710, '4' => 235)

                ),


                array(

                    'value' => 67, 'time_consume' => 18028, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 1060, '2' => 605, '3' => 905, '4' => 300)

                ),


                array(

                    'value' => 64, 'time_consume' => 21074, 'cp' => 21, 'people_inc' => 2,

                    'resources' => array('1' => 1355, '2' => 775, '3' => 1160, '4' => 385)

                ),


                array(

                    'value' => 62, 'time_consume' => 24968, 'cp' => 26, 'people_inc' => 2,

                    'resources' => array('1' => 1735, '2' => 990, '3' => 1485, '4' => 495)

                ),


                array(

                    'value' => 60, 'time_consume' => 29112, 'cp' => 31, 'people_inc' => 2,

                    'resources' => array('1' => 2220, '2' => 1270, '3' => 1900, '4' => 635)

                ),


                array(

                    'value' => 58, 'time_consume' => 33950, 'cp' => 37, 'people_inc' => 3,

                    'resources' => array('1' => 2840, '2' => 1625, '3' => 2435, '4' => 810)

                ),


                array(

                    'value' => 56, 'time_consume' => 39568, 'cp' => 44, 'people_inc' => 3,

                    'resources' => array('1' => 3635, '2' => 2075, '3' => 3115, '4' => 1040)

                ),


                array(

                    'value' => 54, 'time_consume' => 46125, 'cp' => 53, 'people_inc' => 3,

                    'resources' => array('1' => 4650, '2' => 2660, '3' => 3990, '4' => 1330)

                ),


                array(

                    'value' => 52, 'time_consume' => 53777, 'cp' => 64, 'people_inc' => 3,

                    'resources' => array('1' => 5955, '2' => 3405, '3' => 5105, '4' => 1700)

                ),


                array(

                    'value' => 50, 'time_consume' => 62750, 'cp' => 77, 'people_inc' => 3,

                    'resources' => array('1' => 7620, '2' => 4355, '3' => 6535, '4' => 2180)

                ),


            )

        ),


        '16' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 110, '2' => 160, '3' => 90, '4' => 0)

                ),


                array(

                    'value' => 0, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 140, '2' => 205, '3' => 115, '4' => 90)

                ),


                array(

                    'value' => 0, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 180, '2' => 260, '3' => 145, '4' => 115)

                ),


                array(

                    'value' => 0, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 230, '2' => 335, '3' => 190, '4' => 145)

                ),


                array(

                    'value' => 0, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 295, '2' => 430, '3' => 240, '4' => 190)

                ),


                array(

                    'value' => 0, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 380, '2' => 550, '3' => 310, '4' => 240)

                ),


                array(

                    'value' => 0, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 485, '2' => 705, '3' => 395, '4' => 310)

                ),


                array(

                    'value' => 0, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 620, '2' => 900, '3' => 505, '4' => 395)

                ),


                array(

                    'value' => 0, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 795, '2' => 1155, '3' => 650, '4' => 505)

                ),


                array(

                    'value' => 0, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 1015, '2' => 1475, '3' => 830, '4' => 645)

                ),


                array(

                    'value' => 0, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 1300, '2' => 1890, '3' => 1065, '4' => 825)

                ),


                array(

                    'value' => 0, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 1660, '2' => 2420, '3' => 1360, '4' => 1060)

                ),


                array(

                    'value' => 0, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 2130, '2' => 3095, '3' => 1740, '4' => 1355)

                ),


                array(

                    'value' => 0, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 2725, '2' => 3960, '3' => 2230, '4' => 1735)

                ),


                array(

                    'value' => 0, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 3485, '2' => 5070, '3' => 2850, '4' => 2220)

                ),


                array(

                    'value' => 0, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 4460, '2' => 6490, '3' => 3650, '4' => 2840)

                ),


                array(

                    'value' => 0, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 5710, '2' => 8310, '3' => 4675, '4' => 3635)

                ),


                array(

                    'value' => 0, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 7310, '2' => 10635, '3' => 5980, '4' => 4650)

                ),


                array(

                    'value' => 0, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 9360, '2' => 13610, '3' => 7655, '4' => 5955)

                ),


                array(

                    'value' => 0, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 11980, '2' => 17420, '3' => 9800, '4' => 7620)

                ),


            )

        ),


        '17' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 3, '10' => 1, '11' => 1),

            'levels' => array(


                array(

                    'value' => 1, 'time_consume' => 1800, 'cp' => 4, 'people_inc' => 4,

                    'resources' => array('1' => 80, '2' => 70, '3' => 120, '4' => 70)

                ),


                array(

                    'value' => 2, 'time_consume' => 2390, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 100, '2' => 90, '3' => 155, '4' => 90)

                ),


                array(

                    'value' => 3, 'time_consume' => 3070, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 130, '2' => 115, '3' => 195, '4' => 115)

                ),


                array(

                    'value' => 4, 'time_consume' => 3860, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 170, '2' => 145, '3' => 250, '4' => 145)

                ),


                array(

                    'value' => 5, 'time_consume' => 4780, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 215, '2' => 190, '3' => 320, '4' => 190)

                ),


                array(

                    'value' => 6, 'time_consume' => 5840, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 275, '2' => 240, '3' => 410, '4' => 240)

                ),


                array(

                    'value' => 7, 'time_consume' => 7080, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 350, '2' => 310, '3' => 530, '4' => 310)

                ),


                array(

                    'value' => 8, 'time_consume' => 8510, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 450, '2' => 395, '3' => 675, '4' => 395)

                ),


                array(

                    'value' => 9, 'time_consume' => 10170, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 575, '2' => 505, '3' => 865, '4' => 505)

                ),


                array(

                    'value' => 10, 'time_consume' => 12100, 'cp' => 19, 'people_inc' => 3,

                    'resources' => array('1' => 740, '2' => 645, '3' => 1105, '4' => 645)

                ),


                array(

                    'value' => 11, 'time_consume' => 14340, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 945, '2' => 825, '3' => 1415, '4' => 825)

                ),


                array(

                    'value' => 12, 'time_consume' => 16930, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 1210, '2' => 1060, '3' => 1815, '4' => 1060)

                ),


                array(

                    'value' => 13, 'time_consume' => 19940, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 1545, '2' => 1355, '3' => 2320, '4' => 1355)

                ),


                array(

                    'value' => 14, 'time_consume' => 23430, 'cp' => 39, 'people_inc' => 3,

                    'resources' => array('1' => 1980, '2' => 1735, '3' => 2970, '4' => 1735)

                ),


                array(

                    'value' => 15, 'time_consume' => 27480, 'cp' => 46, 'people_inc' => 3,

                    'resources' => array('1' => 2535, '2' => 2220, '3' => 3805, '4' => 2220)

                ),


                array(

                    'value' => 16, 'time_consume' => 32180, 'cp' => 55, 'people_inc' => 4,

                    'resources' => array('1' => 3245, '2' => 2840, '3' => 4870, '4' => 2840)

                ),


                array(

                    'value' => 17, 'time_consume' => 37620, 'cp' => 67, 'people_inc' => 4,

                    'resources' => array('1' => 4155, '2' => 3635, '3' => 6230, '4' => 3635)

                ),


                array(

                    'value' => 18, 'time_consume' => 43940, 'cp' => 80, 'people_inc' => 4,

                    'resources' => array('1' => 5315, '2' => 4650, '3' => 7975, '4' => 4650)

                ),


                array(

                    'value' => 19, 'time_consume' => 51270, 'cp' => 96, 'people_inc' => 4,

                    'resources' => array('1' => 6805, '2' => 5955, '3' => 10210, '4' => 5955)

                ),


                array(

                    'value' => 20, 'time_consume' => 59780, 'cp' => 115, 'people_inc' => 4,

                    'resources' => array('1' => 8710, '2' => 7620, '3' => 13065, '4' => 7620)

                ),


            )

        ),


        '18' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 1),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 2000, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 180, '2' => 130, '3' => 150, '4' => 80)

                ),


                array(

                    'value' => 0, 'time_consume' => 2620, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 230, '2' => 165, '3' => 190, '4' => 100)

                ),


                array(

                    'value' => 3, 'time_consume' => 3340, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 295, '2' => 215, '3' => 245, '4' => 130)

                ),


                array(

                    'value' => 6, 'time_consume' => 4170, 'cp' => 8, 'people_inc' => 2,

                    'resources' => array('1' => 375, '2' => 275, '3' => 315, '4' => 170)

                ),


                array(

                    'value' => 10, 'time_consume' => 5140, 'cp' => 10, 'people_inc' => 2,

                    'resources' => array('1' => 485, '2' => 350, '3' => 405, '4' => 215)

                ),


            )

        ),


        '19' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('16' => 1, '15' => 3),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 4,

                    'resources' => array('1' => 210, '2' => 140, '3' => 260, '4' => 120)

                ),


                array(

                    'value' => 90, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 270, '2' => 180, '3' => 335, '4' => 155)

                ),


                array(

                    'value' => 81, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 345, '2' => 230, '3' => 425, '4' => 195)

                ),


                array(

                    'value' => 73, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 440, '2' => 295, '3' => 545, '4' => 250)

                ),


                array(

                    'value' => 66, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 565, '2' => 375, '3' => 700, '4' => 320)

                ),


                array(

                    'value' => 59, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 720, '2' => 480, '3' => 895, '4' => 410)

                ),


                array(

                    'value' => 53, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 925, '2' => 615, '3' => 1145, '4' => 530)

                ),


                array(

                    'value' => 48, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 1180, '2' => 790, '3' => 1465, '4' => 675)

                ),


                array(

                    'value' => 43, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 1515, '2' => 1010, '3' => 1875, '4' => 865)

                ),


                array(

                    'value' => 39, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 1935, '2' => 1290, '3' => 2400, '4' => 1105)

                ),


                array(

                    'value' => 35, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 2480, '2' => 1655, '3' => 3070, '4' => 1415)

                ),


                array(

                    'value' => 31, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 3175, '2' => 2115, '3' => 3930, '4' => 1815)

                ),


                array(

                    'value' => 28, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 4060, '2' => 2710, '3' => 5030, '4' => 2320)

                ),


                array(

                    'value' => 25, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 5200, '2' => 3465, '3' => 6435, '4' => 2970)

                ),


                array(

                    'value' => 23, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 6655, '2' => 4435, '3' => 8240, '4' => 3805)

                ),


                array(

                    'value' => 21, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 4,

                    'resources' => array('1' => 8520, '2' => 5680, '3' => 10545, '4' => 4870)

                ),


                array(

                    'value' => 19, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 4,

                    'resources' => array('1' => 10905, '2' => 7270, '3' => 13500, '4' => 6230)

                ),


                array(

                    'value' => 17, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 4,

                    'resources' => array('1' => 13955, '2' => 9305, '3' => 17280, '4' => 7975)

                ),


                array(

                    'value' => 15, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 4,

                    'resources' => array('1' => 17865, '2' => 11910, '3' => 22120, '4' => 10210)

                ),


                array(

                    'value' => 14, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 4,

                    'resources' => array('1' => 22865, '2' => 15245, '3' => 28310, '4' => 13065)

                ),


            )

        ),


        '20' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('12' => 3, '22' => 5),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 2200, 'cp' => 2, 'people_inc' => 5,

                    'resources' => array('1' => 260, '2' => 140, '3' => 220, '4' => 100)

                ),


                array(

                    'value' => 90, 'time_consume' => 2850, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 335, '2' => 180, '3' => 280, '4' => 130)

                ),


                array(

                    'value' => 81, 'time_consume' => 3610, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 425, '2' => 230, '3' => 360, '4' => 165)

                ),


                array(

                    'value' => 73, 'time_consume' => 4490, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 545, '2' => 295, '3' => 460, '4' => 210)

                ),


                array(

                    'value' => 66, 'time_consume' => 5500, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 700, '2' => 375, '3' => 590, '4' => 270)

                ),


                array(

                    'value' => 59, 'time_consume' => 6680, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 895, '2' => 480, '3' => 755, '4' => 345)

                ),


                array(

                    'value' => 53, 'time_consume' => 8050, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 1145, '2' => 615, '3' => 970, '4' => 440)

                ),


                array(

                    'value' => 48, 'time_consume' => 9640, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 1465, '2' => 790, '3' => 1240, '4' => 565)

                ),


                array(

                    'value' => 43, 'time_consume' => 11480, 'cp' => 10, 'people_inc' => 3,

                    'resources' => array('1' => 1875, '2' => 1010, '3' => 1585, '4' => 720)

                ),


                array(

                    'value' => 39, 'time_consume' => 13620, 'cp' => 12, 'people_inc' => 3,

                    'resources' => array('1' => 2400, '2' => 1290, '3' => 2030, '4' => 920)

                ),


                array(

                    'value' => 35, 'time_consume' => 16100, 'cp' => 15, 'people_inc' => 4,

                    'resources' => array('1' => 3070, '2' => 1655, '3' => 2595, '4' => 1180)

                ),


                array(

                    'value' => 31, 'time_consume' => 18980, 'cp' => 18, 'people_inc' => 4,

                    'resources' => array('1' => 3930, '2' => 2115, '3' => 3325, '4' => 1510)

                ),


                array(

                    'value' => 28, 'time_consume' => 22310, 'cp' => 21, 'people_inc' => 4,

                    'resources' => array('1' => 5030, '2' => 2710, '3' => 4255, '4' => 1935)

                ),


                array(

                    'value' => 25, 'time_consume' => 26180, 'cp' => 26, 'people_inc' => 4,

                    'resources' => array('1' => 6435, '2' => 3465, '3' => 5445, '4' => 2475)

                ),


                array(

                    'value' => 23, 'time_consume' => 30670, 'cp' => 31, 'people_inc' => 4,

                    'resources' => array('1' => 8240, '2' => 4435, '3' => 6970, '4' => 3170)

                ),


                array(

                    'value' => 21, 'time_consume' => 35880, 'cp' => 37, 'people_inc' => 4,

                    'resources' => array('1' => 10545, '2' => 5680, '3' => 8925, '4' => 4055)

                ),


                array(

                    'value' => 19, 'time_consume' => 41920, 'cp' => 44, 'people_inc' => 4,

                    'resources' => array('1' => 13500, '2' => 7270, '3' => 11425, '4' => 5190)

                ),


                array(

                    'value' => 17, 'time_consume' => 48930, 'cp' => 53, 'people_inc' => 4,

                    'resources' => array('1' => 17280, '2' => 9305, '3' => 14620, '4' => 6645)

                ),


                array(

                    'value' => 15, 'time_consume' => 57060, 'cp' => 64, 'people_inc' => 4,

                    'resources' => array('1' => 22120, '2' => 11910, '3' => 18715, '4' => 8505)

                ),


                array(

                    'value' => 14, 'time_consume' => 66490, 'cp' => 77, 'people_inc' => 4,

                    'resources' => array('1' => 28310, '2' => 15245, '3' => 23955, '4' => 10890)

                ),


            )

        ),


        '21' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('22' => 10, '15' => 5),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 3000, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 460, '2' => 510, '3' => 600, '4' => 320)

                ),


                array(

                    'value' => 90, 'time_consume' => 3780, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 590, '2' => 655, '3' => 770, '4' => 410)

                ),


                array(

                    'value' => 81, 'time_consume' => 4680, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 755, '2' => 835, '3' => 985, '4' => 525)

                ),


                array(

                    'value' => 73, 'time_consume' => 5730, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 965, '2' => 1070, '3' => 1260, '4' => 670)

                ),


                array(

                    'value' => 66, 'time_consume' => 6950, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 1235, '2' => 1370, '3' => 1610, '4' => 860)

                ),


                array(

                    'value' => 59, 'time_consume' => 8360, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 1580, '2' => 1750, '3' => 2060, '4' => 1100)

                ),


                array(

                    'value' => 53, 'time_consume' => 10000, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 2025, '2' => 2245, '3' => 2640, '4' => 1405)

                ),


                array(

                    'value' => 48, 'time_consume' => 11900, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 2590, '2' => 2870, '3' => 3380, '4' => 1800)

                ),


                array(

                    'value' => 43, 'time_consume' => 14110, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 3315, '2' => 3675, '3' => 4325, '4' => 2305)

                ),


                array(

                    'value' => 39, 'time_consume' => 16660, 'cp' => 19, 'people_inc' => 2,

                    'resources' => array('1' => 4245, '2' => 4705, '3' => 5535, '4' => 2950)

                ),


                array(

                    'value' => 35, 'time_consume' => 19630, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 5430, '2' => 6020, '3' => 7085, '4' => 3780)

                ),


                array(

                    'value' => 31, 'time_consume' => 23070, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 6950, '2' => 7705, '3' => 9065, '4' => 4835)

                ),


                array(

                    'value' => 28, 'time_consume' => 27060, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 8900, '2' => 9865, '3' => 11605, '4' => 6190)

                ),


                array(

                    'value' => 25, 'time_consume' => 31690, 'cp' => 39, 'people_inc' => 3,

                    'resources' => array('1' => 11390, '2' => 12625, '3' => 14855, '4' => 7925)

                ),


                array(

                    'value' => 23, 'time_consume' => 37060, 'cp' => 46, 'people_inc' => 3,

                    'resources' => array('1' => 14580, '2' => 16165, '3' => 19015, '4' => 10140)

                ),


                array(

                    'value' => 21, 'time_consume' => 43290, 'cp' => 55, 'people_inc' => 3,

                    'resources' => array('1' => 18660, '2' => 20690, '3' => 24340, '4' => 12980)

                ),


                array(

                    'value' => 19, 'time_consume' => 50520, 'cp' => 67, 'people_inc' => 3,

                    'resources' => array('1' => 23885, '2' => 26480, '3' => 31155, '4' => 16615)

                ),


                array(

                    'value' => 17, 'time_consume' => 58900, 'cp' => 80, 'people_inc' => 3,

                    'resources' => array('1' => 30570, '2' => 33895, '3' => 39875, '4' => 21270)

                ),


                array(

                    'value' => 15, 'time_consume' => 68630, 'cp' => 96, 'people_inc' => 3,

                    'resources' => array('1' => 39130, '2' => 43385, '3' => 51040, '4' => 27225)

                ),


                array(

                    'value' => 14, 'time_consume' => 79910, 'cp' => 115, 'people_inc' => 3,

                    'resources' => array('1' => 50090, '2' => 55535, '3' => 65335, '4' => 34845)

                ),


            )

        ),


        '22' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('19' => 3, '15' => 3),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 2000, 'cp' => 5, 'people_inc' => 4,

                    'resources' => array('1' => 220, '2' => 160, '3' => 90, '4' => 40)

                ),


                array(

                    'value' => 0, 'time_consume' => 2620, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 280, '2' => 205, '3' => 115, '4' => 50)

                ),


                array(

                    'value' => 0, 'time_consume' => 3340, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 360, '2' => 260, '3' => 145, '4' => 65)

                ),


                array(

                    'value' => 0, 'time_consume' => 4170, 'cp' => 8, 'people_inc' => 2,

                    'resources' => array('1' => 460, '2' => 335, '3' => 190, '4' => 85)

                ),


                array(

                    'value' => 0, 'time_consume' => 5140, 'cp' => 10, 'people_inc' => 2,

                    'resources' => array('1' => 590, '2' => 430, '3' => 240, '4' => 105)

                ),


                array(

                    'value' => 0, 'time_consume' => 6260, 'cp' => 12, 'people_inc' => 3,

                    'resources' => array('1' => 755, '2' => 550, '3' => 310, '4' => 135)

                ),


                array(

                    'value' => 0, 'time_consume' => 7570, 'cp' => 14, 'people_inc' => 3,

                    'resources' => array('1' => 970, '2' => 705, '3' => 395, '4' => 175)

                ),


                array(

                    'value' => 0, 'time_consume' => 9080, 'cp' => 17, 'people_inc' => 3,

                    'resources' => array('1' => 1240, '2' => 900, '3' => 505, '4' => 225)

                ),


                array(

                    'value' => 0, 'time_consume' => 10830, 'cp' => 21, 'people_inc' => 3,

                    'resources' => array('1' => 1585, '2' => 1155, '3' => 650, '4' => 290)

                ),


                array(

                    'value' => 0, 'time_consume' => 12860, 'cp' => 25, 'people_inc' => 3,

                    'resources' => array('1' => 2030, '2' => 1475, '3' => 830, '4' => 370)

                ),


                array(

                    'value' => 0, 'time_consume' => 15220, 'cp' => 30, 'people_inc' => 3,

                    'resources' => array('1' => 2595, '2' => 1890, '3' => 1065, '4' => 470)

                ),


                array(

                    'value' => 0, 'time_consume' => 17950, 'cp' => 36, 'people_inc' => 3,

                    'resources' => array('1' => 3325, '2' => 2420, '3' => 1360, '4' => 605)

                ),


                array(

                    'value' => 0, 'time_consume' => 21130, 'cp' => 43, 'people_inc' => 3,

                    'resources' => array('1' => 4255, '2' => 3095, '3' => 1740, '4' => 775)

                ),


                array(

                    'value' => 0, 'time_consume' => 24810, 'cp' => 51, 'people_inc' => 3,

                    'resources' => array('1' => 5445, '2' => 3960, '3' => 2230, '4' => 990)

                ),


                array(

                    'value' => 0, 'time_consume' => 29080, 'cp' => 62, 'people_inc' => 3,

                    'resources' => array('1' => 6970, '2' => 5070, '3' => 2850, '4' => 1270)

                ),


                array(

                    'value' => 0, 'time_consume' => 34030, 'cp' => 74, 'people_inc' => 4,

                    'resources' => array('1' => 8925, '2' => 6490, '3' => 3650, '4' => 1625)

                ),


                array(

                    'value' => 0, 'time_consume' => 39770, 'cp' => 89, 'people_inc' => 4,

                    'resources' => array('1' => 11425, '2' => 8310, '3' => 4675, '4' => 2075)

                ),


                array(

                    'value' => 0, 'time_consume' => 46440, 'cp' => 106, 'people_inc' => 4,

                    'resources' => array('1' => 14620, '2' => 10635, '3' => 5980, '4' => 2660)

                ),


                array(

                    'value' => 0, 'time_consume' => 54170, 'cp' => 127, 'people_inc' => 4,

                    'resources' => array('1' => 18715, '2' => 13610, '3' => 7655, '4' => 3405)

                ),


                array(

                    'value' => 0, 'time_consume' => 63130, 'cp' => 127, 'people_inc' => 4,

                    'resources' => array('1' => 23955, '2' => 17420, '3' => 9800, '4' => 4355)

                ),


            )

        ),


        '23' => array(

            'support_multiple' => TRUE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 2, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => $cranny * 100, 'time_consume' => 750, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 40, '2' => 50, '3' => 30, '4' => 10)

                ),


                array(

                    'value' => $cranny * 130, 'time_consume' => 1170, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 50, '2' => 65, '3' => 40, '4' => 15)

                ),


                array(

                    'value' => $cranny * 170, 'time_consume' => 1660, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 65, '2' => 80, '3' => 50, '4' => 15)

                ),


                array(

                    'value' => $cranny * 220, 'time_consume' => 2220, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 85, '2' => 105, '3' => 65, '4' => 20)

                ),


                array(

                    'value' => $cranny * 280, 'time_consume' => 2880, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 105, '2' => 135, '3' => 80, '4' => 25)

                ),


                array(

                    'value' => $cranny * 360, 'time_consume' => 3640, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 135, '2' => 170, '3' => 105, '4' => 35)

                ),


                array(

                    'value' => $cranny * 460, 'time_consume' => 4520, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 175, '2' => 220, '3' => 130, '4' => 45)

                ),


                array(

                    'value' => $cranny * 600, 'time_consume' => 5540, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 225, '2' => 280, '3' => 170, '4' => 55)

                ),


                array(

                    'value' => $cranny * 770, 'time_consume' => 6730, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 290, '2' => 360, '3' => 215, '4' => 70)

                ),


                array(

                    'value' => $cranny * 1000, 'time_consume' => 8110, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 370, '2' => 460, '3' => 275, '4' => 90)

                ),


            )

        ),


        '24' => array(

            'celebrations' => array(

                'small' => array('level' => 1, 'value' => 500, 'time_consume' => 86400, 'resources' => array('1' => 6400, '2' => 6650, '3' => 5940, '4' => 1340)),

                'large' => array('level' => 10, 'value' => 2000, 'time_consume' => 108000, 'resources' => array('1' => 29700, '2' => 33250, '3' => 32000, '4' => 6700))

            ),


            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 10, '22' => 10),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 12500, 'cp' => 6, 'people_inc' => 4,

                    'resources' => array('1' => 1250, '2' => 1110, '3' => 1260, '4' => 600)

                ),


                array(

                    'value' => 0, 'time_consume' => 14800, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 1600, '2' => 1420, '3' => 1615, '4' => 770)

                ),


                array(

                    'value' => 0, 'time_consume' => 17470, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 2050, '2' => 1820, '3' => 2065, '4' => 985)

                ),


                array(

                    'value' => 0, 'time_consume' => 20560, 'cp' => 10, 'people_inc' => 2,

                    'resources' => array('1' => 2620, '2' => 2330, '3' => 2640, '4' => 1260)

                ),


                array(

                    'value' => 0, 'time_consume' => 24150, 'cp' => 12, 'people_inc' => 2,

                    'resources' => array('1' => 3355, '2' => 2980, '3' => 3380, '4' => 1610)

                ),


                array(

                    'value' => 0, 'time_consume' => 28320, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 4295, '2' => 3815, '3' => 4330, '4' => 2060)

                ),


                array(

                    'value' => 0, 'time_consume' => 33150, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 5500, '2' => 4880, '3' => 5540, '4' => 2640)

                ),


                array(

                    'value' => 0, 'time_consume' => 38750, 'cp' => 21, 'people_inc' => 3,

                    'resources' => array('1' => 7035, '2' => 6250, '3' => 7095, '4' => 3380)

                ),


                array(

                    'value' => 0, 'time_consume' => 45250, 'cp' => 26, 'people_inc' => 3,

                    'resources' => array('1' => 9005, '2' => 8000, '3' => 9080, '4' => 4325)

                ),


                array(

                    'value' => 0, 'time_consume' => 52790, 'cp' => 31, 'people_inc' => 3,

                    'resources' => array('1' => 11530, '2' => 10240, '3' => 11620, '4' => 5535)

                ),


                array(

                    'value' => 0, 'time_consume' => 61540, 'cp' => 37, 'people_inc' => 3,

                    'resources' => array('1' => 14755, '2' => 13105, '3' => 14875, '4' => 7085)

                ),


                array(

                    'value' => 0, 'time_consume' => 71690, 'cp' => 45, 'people_inc' => 3,

                    'resources' => array('1' => 18890, '2' => 16775, '3' => 19040, '4' => 9065)

                ),


                array(

                    'value' => 0, 'time_consume' => 83460, 'cp' => 53, 'people_inc' => 3,

                    'resources' => array('1' => 24180, '2' => 21470, '3' => 24370, '4' => 11605)

                ),


                array(

                    'value' => 0, 'time_consume' => 97110, 'cp' => 64, 'people_inc' => 3,

                    'resources' => array('1' => 30950, '2' => 27480, '3' => 31195, '4' => 14855)

                ),


                array(

                    'value' => 0, 'time_consume' => 112950, 'cp' => 77, 'people_inc' => 3,

                    'resources' => array('1' => 39615, '2' => 35175, '3' => 39930, '4' => 19015)

                ),


                array(

                    'value' => 0, 'time_consume' => 131320, 'cp' => 92, 'people_inc' => 4,

                    'resources' => array('1' => 50705, '2' => 45025, '3' => 51110, '4' => 24340)

                ),


                array(

                    'value' => 0, 'time_consume' => 152630, 'cp' => 111, 'people_inc' => 4,

                    'resources' => array('1' => 64905, '2' => 57635, '3' => 65425, '4' => 31155)

                ),


                array(

                    'value' => 0, 'time_consume' => 177350, 'cp' => 127, 'people_inc' => 4,

                    'resources' => array('1' => 83075, '2' => 73770, '3' => 83740, '4' => 39875)

                ),


                array(

                    'value' => 0, 'time_consume' => 206020, 'cp' => 127, 'people_inc' => 4,

                    'resources' => array('1' => 106340, '2' => 94430, '3' => 107190, '4' => 51040)

                ),


                array(

                    'value' => 0, 'time_consume' => 239290, 'cp' => 127, 'people_inc' => 4,

                    'resources' => array('1' => 136115, '2' => 120870, '3' => 137200, '4' => 65335)

                ),


            )

        ),


        '25' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 5, '26' => NULL),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 200, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 580, '2' => 460, '3' => 350, '4' => 180)

                ),


                array(

                    'value' => 0, 'time_consume' => 1020, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 740, '2' => 590, '3' => 450, '4' => 230)

                ),


                array(

                    'value' => 0, 'time_consume' => 2340, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 950, '2' => 755, '3' => 575, '4' => 295)

                ),


                array(

                    'value' => 0, 'time_consume' => 4170, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 1215, '2' => 965, '3' => 735, '4' => 375)

                ),


                array(

                    'value' => 0, 'time_consume' => 6140, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 1555, '2' => 1235, '3' => 940, '4' => 485)

                ),


                array(

                    'value' => 0, 'time_consume' => 8260, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 1995, '2' => 1580, '3' => 1205, '4' => 620)

                ),


                array(

                    'value' => 0, 'time_consume' => 10570, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 2550, '2' => 2025, '3' => 1540, '4' => 790)

                ),


                array(

                    'value' => 0, 'time_consume' => 13080, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 3265, '2' => 2590, '3' => 1970, '4' => 1015)

                ),


                array(

                    'value' => 0, 'time_consume' => 17830, 'cp' => 10, 'people_inc' => 1,

                    'resources' => array('1' => 4180, '2' => 3315, '3' => 2520, '4' => 1295)

                ),


                array(

                    'value' => 1, 'time_consume' => 20860, 'cp' => 12, 'people_inc' => 1,

                    'resources' => array('1' => 5350, '2' => 4245, '3' => 3230, '4' => 1660)

                ),


                array(

                    'value' => 1, 'time_consume' => 25220, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 6845, '2' => 5430, '3' => 4130, '4' => 2125)

                ),


                array(

                    'value' => 1, 'time_consume' => 30950, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 8765, '2' => 6950, '3' => 5290, '4' => 2720)

                ),


                array(

                    'value' => 1, 'time_consume' => 34130, 'cp' => 21, 'people_inc' => 2,

                    'resources' => array('1' => 11220, '2' => 8900, '3' => 6770, '4' => 3480)

                ),


                array(

                    'value' => 1, 'time_consume' => 38810, 'cp' => 26, 'people_inc' => 2,

                    'resources' => array('1' => 14360, '2' => 11390, '3' => 8665, '4' => 4455)

                ),


                array(

                    'value' => 1, 'time_consume' => 42080, 'cp' => 31, 'people_inc' => 2,

                    'resources' => array('1' => 18380, '2' => 14580, '3' => 11090, '4' => 5705)

                ),


                array(

                    'value' => 1, 'time_consume' => 54030, 'cp' => 37, 'people_inc' => 2,

                    'resources' => array('1' => 23530, '2' => 18660, '3' => 14200, '4' => 7300)

                ),


                array(

                    'value' => 1, 'time_consume' => 69770, 'cp' => 44, 'people_inc' => 2,

                    'resources' => array('1' => 30115, '2' => 23885, '3' => 18175, '4' => 9345)

                ),


                array(

                    'value' => 1, 'time_consume' => 76440, 'cp' => 53, 'people_inc' => 2,

                    'resources' => array('1' => 38550, '2' => 30570, '3' => 23260, '4' => 11965)

                ),


                array(

                    'value' => 1, 'time_consume' => 84170, 'cp' => 64, 'people_inc' => 2,

                    'resources' => array('1' => 49340, '2' => 39130, '3' => 29775, '4' => 15315)

                ),


                array(

                    'value' => 2, 'time_consume' => 93130, 'cp' => 77, 'people_inc' => 2,

                    'resources' => array('1' => 63155, '2' => 50090, '3' => 38110, '4' => 19600)

                ),


            )

        ),


        '26' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('18' => 1, '15' => 5, '25' => NULL),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 5000, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 550, '2' => 800, '3' => 750, '4' => 250)

                ),


                array(

                    'value' => 0, 'time_consume' => 6100, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 705, '2' => 1025, '3' => 960, '4' => 320)

                ),


                array(

                    'value' => 0, 'time_consume' => 7380, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 900, '2' => 1310, '3' => 1230, '4' => 410)

                ),


                array(

                    'value' => 0, 'time_consume' => 8860, 'cp' => 10, 'people_inc' => 1,

                    'resources' => array('1' => 1155, '2' => 1680, '3' => 1575, '4' => 525)

                ),


                array(

                    'value' => 0, 'time_consume' => 10570, 'cp' => 12, 'people_inc' => 1,

                    'resources' => array('1' => 1475, '2' => 2145, '3' => 2015, '4' => 670)

                ),


                array(

                    'value' => 0, 'time_consume' => 12560, 'cp' => 15, 'people_inc' => 1,

                    'resources' => array('1' => 1890, '2' => 2750, '3' => 2575, '4' => 860)

                ),


                array(

                    'value' => 0, 'time_consume' => 14880, 'cp' => 18, 'people_inc' => 1,

                    'resources' => array('1' => 2420, '2' => 3520, '3' => 3300, '4' => 1100)

                ),


                array(

                    'value' => 0, 'time_consume' => 17560, 'cp' => 21, 'people_inc' => 1,

                    'resources' => array('1' => 3095, '2' => 4505, '3' => 4220, '4' => 1405)

                ),


                array(

                    'value' => 0, 'time_consume' => 20660, 'cp' => 26, 'people_inc' => 1,

                    'resources' => array('1' => 3965, '2' => 5765, '3' => 5405, '4' => 1800)

                ),


                array(

                    'value' => 0, 'time_consume' => 24270, 'cp' => 31, 'people_inc' => 1,

                    'resources' => array('1' => 5075, '2' => 7380, '3' => 6920, '4' => 2305)

                ),


                array(

                    'value' => 0, 'time_consume' => 28450, 'cp' => 37, 'people_inc' => 2,

                    'resources' => array('1' => 6495, '2' => 9445, '3' => 8855, '4' => 2950)

                ),


                array(

                    'value' => 0, 'time_consume' => 33310, 'cp' => 45, 'people_inc' => 2,

                    'resources' => array('1' => 8310, '2' => 12090, '3' => 11335, '4' => 3780)

                ),


                array(

                    'value' => 0, 'time_consume' => 38940, 'cp' => 53, 'people_inc' => 2,

                    'resources' => array('1' => 10640, '2' => 15475, '3' => 14505, '4' => 4835)

                ),


                array(

                    'value' => 0, 'time_consume' => 45460, 'cp' => 64, 'people_inc' => 2,

                    'resources' => array('1' => 13615, '2' => 19805, '3' => 18570, '4' => 6190)

                ),


                array(

                    'value' => 0, 'time_consume' => 53040, 'cp' => 77, 'people_inc' => 2,

                    'resources' => array('1' => 17430, '2' => 25355, '3' => 23770, '4' => 7925)

                ),


                array(

                    'value' => 0, 'time_consume' => 61830, 'cp' => 92, 'people_inc' => 2,

                    'resources' => array('1' => 22310, '2' => 32450, '3' => 30425, '4' => 10140)

                ),


                array(

                    'value' => 0, 'time_consume' => 72020, 'cp' => 111, 'people_inc' => 2,

                    'resources' => array('1' => 28560, '2' => 41540, '3' => 38940, '4' => 12980)

                ),


                array(

                    'value' => 0, 'time_consume' => 83840, 'cp' => 127, 'people_inc' => 2,

                    'resources' => array('1' => 36555, '2' => 53170, '3' => 49845, '4' => 16615)

                ),


                array(

                    'value' => 0, 'time_consume' => 97550, 'cp' => 127, 'people_inc' => 2,

                    'resources' => array('1' => 46790, '2' => 68055, '3' => 63805, '4' => 21270)

                ),


                array(

                    'value' => 0, 'time_consume' => 113460, 'cp' => 127, 'people_inc' => 2,

                    'resources' => array('1' => 59890, '2' => 87110, '3' => 81670, '4' => 27225)

                ),


            )

        ),


        '27' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 10, '40' => NULL),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 8000, 'cp' => 7, 'people_inc' => 4,

                    'resources' => array('1' => 2880, '2' => 2740, '3' => 2580, '4' => 990)

                ),


                array(

                    'value' => 0, 'time_consume' => 9580, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 3685, '2' => 3505, '3' => 3300, '4' => 1265)

                ),


                array(

                    'value' => 0, 'time_consume' => 11410, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 4720, '2' => 4490, '3' => 4225, '4' => 1620)

                ),


                array(

                    'value' => 0, 'time_consume' => 13540, 'cp' => 14, 'people_inc' => 2,

                    'resources' => array('1' => 6040, '2' => 5745, '3' => 5410, '4' => 2075)

                ),


                array(

                    'value' => 0, 'time_consume' => 16010, 'cp' => 17, 'people_inc' => 2,

                    'resources' => array('1' => 7730, '2' => 7355, '3' => 6925, '4' => 2660)

                ),


                array(

                    'value' => 0, 'time_consume' => 18870, 'cp' => 20, 'people_inc' => 3,

                    'resources' => array('1' => 9895, '2' => 9415, '3' => 8865, '4' => 3400)

                ),


                array(

                    'value' => 0, 'time_consume' => 22180, 'cp' => 23, 'people_inc' => 3,

                    'resources' => array('1' => 12665, '2' => 12050, '3' => 11345, '4' => 4355)

                ),


                array(

                    'value' => 0, 'time_consume' => 26030, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 16215, '2' => 15425, '3' => 14525, '4' => 5575)

                ),


                array(

                    'value' => 0, 'time_consume' => 30500, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 20755, '2' => 19745, '3' => 18590, '4' => 7135)

                ),


                array(

                    'value' => 1, 'time_consume' => 35680, 'cp' => 37, 'people_inc' => 3,

                    'resources' => array('1' => 26565, '2' => 25270, '3' => 23795, '4' => 9130)

                ),


                array(

                    'value' => 1, 'time_consume' => 41690, 'cp' => 45, 'people_inc' => 3,

                    'resources' => array('1' => 9045, '2' => 27635, '3' => 26020, '4' => 9985)

                ),


                array(

                    'value' => 1, 'time_consume' => 48660, 'cp' => 53, 'people_inc' => 3,

                    'resources' => array('1' => 6600, '2' => 34820, '3' => 32785, '4' => 12580)

                ),


                array(

                    'value' => 1, 'time_consume' => 56740, 'cp' => 64, 'people_inc' => 3,

                    'resources' => array('1' => 46115, '2' => 43875, '3' => 41310, '4' => 15850)

                ),


                array(

                    'value' => 1, 'time_consume' => 66120, 'cp' => 77, 'people_inc' => 3,

                    'resources' => array('1' => 58105, '2' => 55280, '3' => 52050, '4' => 19975)

                ),


                array(

                    'value' => 1, 'time_consume' => 77000, 'cp' => 92, 'people_inc' => 3,

                    'resources' => array('1' => 73210, '2' => 69655, '3' => 65585, '4' => 25165)

                ),


                array(

                    'value' => 1, 'time_consume' => 89620, 'cp' => 111, 'people_inc' => 4,

                    'resources' => array('1' => 92245, '2' => 87760, '3' => 82640, '4' => 31710)

                ),


                array(

                    'value' => 1, 'time_consume' => 104260, 'cp' => 133, 'people_inc' => 4,

                    'resources' => array('1' => 116230, '2' => 110580, '3' => 104125, '4' => 39955)

                ),


                array(

                    'value' => 1, 'time_consume' => 121240, 'cp' => 160, 'people_inc' => 4,

                    'resources' => array('1' => 146450, '2' => 139330, '3' => 131195, '4' => 50340)

                ),


                array(

                    'value' => 1, 'time_consume' => 140940, 'cp' => 192, 'people_inc' => 4,

                    'resources' => array('1' => 184530, '2' => 175560, '3' => 165305, '4' => 63430)

                ),


                array(

                    'value' => 1, 'time_consume' => 163790, 'cp' => 230, 'people_inc' => 4,

                    'resources' => array('1' => 232505, '2' => 221205, '3' => 208285, '4' => 79925)

                ),


            )

        ),


        '28' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('17' => 20, '20' => 10),

            'levels' => array(


                array(

                    'value' => 110, 'time_consume' => 3000, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 1400, '2' => 1330, '3' => 1200, '4' => 400)

                ),


                array(

                    'value' => 120, 'time_consume' => 3780, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 1790, '2' => 1700, '3' => 1535, '4' => 510)

                ),


                array(

                    'value' => 130, 'time_consume' => 4680, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 2295, '2' => 2180, '3' => 1965, '4' => 655)

                ),


                array(

                    'value' => 140, 'time_consume' => 5730, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 2935, '2' => 2790, '3' => 2515, '4' => 840)

                ),


                array(

                    'value' => 150, 'time_consume' => 6950, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 3760, '2' => 3570, '3' => 3220, '4' => 1075)

                ),


                array(

                    'value' => 160, 'time_consume' => 8360, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 4810, '2' => 4570, '3' => 4125, '4' => 1375)

                ),


                array(

                    'value' => 170, 'time_consume' => 10000, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 6155, '2' => 5850, '3' => 5280, '4' => 1760)

                ),


                array(

                    'value' => 180, 'time_consume' => 11900, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 7880, '2' => 7485, '3' => 6755, '4' => 2250)

                ),


                array(

                    'value' => 190, 'time_consume' => 14110, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 10090, '2' => 9585, '3' => 8645, '4' => 2880)

                ),


                array(

                    'value' => 200, 'time_consume' => 16660, 'cp' => 19, 'people_inc' => 2,

                    'resources' => array('1' => 12915, '2' => 12265, '3' => 11070, '4' => 3690)

                ),


                array(

                    'value' => 210, 'time_consume' => 19630, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 16530, '2' => 15700, '3' => 14165, '4' => 4720)

                ),


                array(

                    'value' => 220, 'time_consume' => 23070, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 21155, '2' => 20100, '3' => 18135, '4' => 6045)

                ),


                array(

                    'value' => 230, 'time_consume' => 27060, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 27080, '2' => 25725, '3' => 23210, '4' => 7735)

                ),


                array(

                    'value' => 240, 'time_consume' => 31690, 'cp' => 39, 'people_inc' => 3,

                    'resources' => array('1' => 34660, '2' => 32930, '3' => 29710, '4' => 9905)

                ),


                array(

                    'value' => 250, 'time_consume' => 37060, 'cp' => 46, 'people_inc' => 3,

                    'resources' => array('1' => 44370, '2' => 42150, '3' => 38030, '4' => 12675)

                ),


                array(

                    'value' => 260, 'time_consume' => 43290, 'cp' => 55, 'people_inc' => 3,

                    'resources' => array('1' => 56790, '2' => 53950, '3' => 48680, '4' => 16225)

                ),


                array(

                    'value' => 270, 'time_consume' => 50520, 'cp' => 67, 'people_inc' => 3,

                    'resources' => array('1' => 72690, '2' => 69060, '3' => 62310, '4' => 20770)

                ),


                array(

                    'value' => 280, 'time_consume' => 58900, 'cp' => 80, 'people_inc' => 3,

                    'resources' => array('1' => 93045, '2' => 88395, '3' => 79755, '4' => 26585)

                ),


                array(

                    'value' => 290, 'time_consume' => 68630, 'cp' => 96, 'people_inc' => 3,

                    'resources' => array('1' => 119100, '2' => 113145, '3' => 102085, '4' => 34030)

                ),


                array(

                    'value' => 300, 'time_consume' => 79910, 'cp' => 115, 'people_inc' => 3,

                    'resources' => array('1' => 152445, '2' => 144825, '3' => 130670, '4' => 43555)

                ),


            )

        ),


        '29' => array(

            'support_multiple' => FALSE, 'built_in_capital' => FALSE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 3, '2' => 3, '3' => 3, '4' => 3, '6' => 3, '7' => 3),

            'pre_requests' => array('19' => 20),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 4,

                    'resources' => array('1' => 630, '2' => 420, '3' => 780, '4' => 360)

                ),


                array(

                    'value' => 90, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 805, '2' => 540, '3' => 1000, '4' => 460)

                ),


                array(

                    'value' => 81, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1030, '2' => 690, '3' => 1280, '4' => 590)

                ),


                array(

                    'value' => 73, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1320, '2' => 880, '3' => 1635, '4' => 755)

                ),


                array(

                    'value' => 66, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 1690, '2' => 1125, '3' => 2095, '4' => 965)

                ),


                array(

                    'value' => 59, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 2165, '2' => 1445, '3' => 2680, '4' => 1235)

                ),


                array(

                    'value' => 53, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 2770, '2' => 1845, '3' => 3430, '4' => 1585)

                ),


                array(

                    'value' => 48, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 3545, '2' => 2365, '3' => 4390, '4' => 2025)

                ),


                array(

                    'value' => 43, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 4540, '2' => 3025, '3' => 5620, '4' => 2595)

                ),


                array(

                    'value' => 39, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 5810, '2' => 3875, '3' => 7195, '4' => 3320)

                ),


                array(

                    'value' => 35, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 7440, '2' => 4960, '3' => 9210, '4' => 4250)

                ),


                array(

                    'value' => 31, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 9520, '2' => 6345, '3' => 11785, '4' => 5440)

                ),


                array(

                    'value' => 28, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 12185, '2' => 8125, '3' => 15085, '4' => 6965)

                ),


                array(

                    'value' => 25, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 15600, '2' => 10400, '3' => 19310, '4' => 8915)

                ),


                array(

                    'value' => 23, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 19965, '2' => 13310, '3' => 24720, '4' => 11410)

                ),


                array(

                    'value' => 21, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 4,

                    'resources' => array('1' => 25555, '2' => 17035, '3' => 31640, '4' => 14605)

                ),


                array(

                    'value' => 19, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 4,

                    'resources' => array('1' => 32710, '2' => 21810, '3' => 40500, '4' => 18690)

                ),


                array(

                    'value' => 17, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 4,

                    'resources' => array('1' => 41870, '2' => 27915, '3' => 51840, '4' => 23925)

                ),


                array(

                    'value' => 15, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 4,

                    'resources' => array('1' => 53595, '2' => 35730, '3' => 66355, '4' => 30625)

                ),


                array(

                    'value' => 14, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 4,

                    'resources' => array('1' => 68600, '2' => 45735, '3' => 84935, '4' => 39200)

                ),


            )

        ),


        '30' => array(

            'support_multiple' => FALSE, 'built_in_capital' => FALSE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 3, '2' => 3, '3' => 3, '4' => 3, '6' => 3, '7' => 3),

            'pre_requests' => array('20' => 20),

            'levels' => array(


                array(

                    'value' => 100, 'time_consume' => 2200, 'cp' => 2, 'people_inc' => 5,

                    'resources' => array('1' => 780, '2' => 420, '3' => 660, '4' => 300)

                ),


                array(

                    'value' => 90, 'time_consume' => 2850, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 1000, '2' => 540, '3' => 845, '4' => 385)

                ),


                array(

                    'value' => 81, 'time_consume' => 3610, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 1280, '2' => 690, '3' => 1080, '4' => 490)

                ),


                array(

                    'value' => 73, 'time_consume' => 4490, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 1635, '2' => 880, '3' => 1385, '4' => 630)

                ),


                array(

                    'value' => 66, 'time_consume' => 5500, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 2095, '2' => 1125, '3' => 1770, '4' => 805)

                ),


                array(

                    'value' => 59, 'time_consume' => 6680, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 2680, '2' => 1445, '3' => 2270, '4' => 1030)

                ),


                array(

                    'value' => 53, 'time_consume' => 8050, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 3430, '2' => 1845, '3' => 2905, '4' => 1320)

                ),


                array(

                    'value' => 48, 'time_consume' => 9640, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 4390, '2' => 2365, '3' => 3715, '4' => 1690)

                ),


                array(

                    'value' => 43, 'time_consume' => 11480, 'cp' => 10, 'people_inc' => 3,

                    'resources' => array('1' => 5620, '2' => 3025, '3' => 4755, '4' => 2160)

                ),


                array(

                    'value' => 39, 'time_consume' => 13620, 'cp' => 12, 'people_inc' => 3,

                    'resources' => array('1' => 7195, '2' => 3875, '3' => 6085, '4' => 2765)

                ),


                array(

                    'value' => 35, 'time_consume' => 16100, 'cp' => 15, 'people_inc' => 4,

                    'resources' => array('1' => 9210, '2' => 4960, '3' => 7790, '4' => 3540)

                ),


                array(

                    'value' => 31, 'time_consume' => 18980, 'cp' => 18, 'people_inc' => 4,

                    'resources' => array('1' => 11785, '2' => 6345, '3' => 9975, '4' => 4535)

                ),


                array(

                    'value' => 28, 'time_consume' => 22310, 'cp' => 21, 'people_inc' => 4,

                    'resources' => array('1' => 15085, '2' => 8125, '3' => 12765, '4' => 5805)

                ),


                array(

                    'value' => 25, 'time_consume' => 26180, 'cp' => 26, 'people_inc' => 4,

                    'resources' => array('1' => 19310, '2' => 10400, '3' => 16340, '4' => 7430)

                ),


                array(

                    'value' => 23, 'time_consume' => 30670, 'cp' => 31, 'people_inc' => 4,

                    'resources' => array('1' => 24720, '2' => 13310, '3' => 20915, '4' => 9505)

                ),


                array(

                    'value' => 21, 'time_consume' => 35880, 'cp' => 37, 'people_inc' => 4,

                    'resources' => array('1' => 31640, '2' => 17035, '3' => 26775, '4' => 12170)

                ),


                array(

                    'value' => 19, 'time_consume' => 41920, 'cp' => 44, 'people_inc' => 4,

                    'resources' => array('1' => 40500, '2' => 21810, '3' => 34270, '4' => 15575)

                ),


                array(

                    'value' => 17, 'time_consume' => 48930, 'cp' => 53, 'people_inc' => 4,

                    'resources' => array('1' => 51840, '2' => 27915, '3' => 43865, '4' => 19940)

                ),


                array(

                    'value' => 15, 'time_consume' => 57060, 'cp' => 64, 'people_inc' => 4,

                    'resources' => array('1' => 66355, '2' => 35730, '3' => 56145, '4' => 25520)

                ),


                array(

                    'value' => 14, 'time_consume' => 66490, 'cp' => 77, 'people_inc' => 4,

                    'resources' => array('1' => 84935, '2' => 45735, '3' => 71870, '4' => 32665)

                ),


            )

        ),


        '31' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 3, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 70, '2' => 90, '3' => 170, '4' => 70)

                ),


                array(

                    'value' => 6, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 90, '2' => 115, '3' => 220, '4' => 90)

                ),


                array(

                    'value' => 9, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 115, '2' => 145, '3' => 280, '4' => 115)

                ),


                array(

                    'value' => 13, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 145, '2' => 190, '3' => 355, '4' => 145)

                ),


                array(

                    'value' => 16, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 190, '2' => 240, '3' => 455, '4' => 190)

                ),


                array(

                    'value' => 19, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 240, '2' => 310, '3' => 585, '4' => 240)

                ),


                array(

                    'value' => 23, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 310, '2' => 395, '3' => 750, '4' => 310)

                ),


                array(

                    'value' => 27, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 395, '2' => 505, '3' => 955, '4' => 395)

                ),


                array(

                    'value' => 30, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 505, '2' => 650, '3' => 1225, '4' => 505)

                ),


                array(

                    'value' => 34, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 645, '2' => 830, '3' => 1570, '4' => 645)

                ),


                array(

                    'value' => 38, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 825, '2' => 1065, '3' => 2005, '4' => 825)

                ),


                array(

                    'value' => 43, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 1060, '2' => 1360, '3' => 2570, '4' => 1060)

                ),


                array(

                    'value' => 47, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 1,

                    'resources' => array('1' => 1355, '2' => 1740, '3' => 3290, '4' => 1355)

                ),


                array(

                    'value' => 51, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 1,

                    'resources' => array('1' => 1735, '2' => 2230, '3' => 4210, '4' => 1735)

                ),


                array(

                    'value' => 56, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 1,

                    'resources' => array('1' => 2220, '2' => 2850, '3' => 5390, '4' => 2220)

                ),


                array(

                    'value' => 60, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 2840, '2' => 3650, '3' => 6895, '4' => 2840)

                ),


                array(

                    'value' => 65, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 3635, '2' => 4675, '3' => 8825, '4' => 3635)

                ),


                array(

                    'value' => 70, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 4650, '2' => 5980, '3' => 11300, '4' => 4650)

                ),


                array(

                    'value' => 75, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 5955, '2' => 7655, '3' => 14460, '4' => 5955)

                ),


                array(

                    'value' => 81, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 7620, '2' => 9800, '3' => 18510, '4' => 7620)

                ),


            )

        ),


        '32' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('2' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 2, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 120, '2' => 200, '3' => 0, '4' => 80)

                ),


                array(

                    'value' => 4, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 155, '2' => 255, '3' => 0, '4' => 100)

                ),


                array(

                    'value' => 6, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 195, '2' => 330, '3' => 0, '4' => 130)

                ),


                array(

                    'value' => 8, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 250, '2' => 420, '3' => 0, '4' => 170)

                ),


                array(

                    'value' => 10, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 320, '2' => 535, '3' => 0, '4' => 215)

                ),


                array(

                    'value' => 13, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 410, '2' => 685, '3' => 0, '4' => 275)

                ),


                array(

                    'value' => 15, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 530, '2' => 880, '3' => 0, '4' => 350)

                ),


                array(

                    'value' => 17, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 675, '2' => 1125, '3' => 0, '4' => 450)

                ),


                array(

                    'value' => 20, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 865, '2' => 1440, '3' => 0, '4' => 575)

                ),


                array(

                    'value' => 22, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 1105, '2' => 1845, '3' => 0, '4' => 740)

                ),


                array(

                    'value' => 24, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 1415, '2' => 2360, '3' => 0, '4' => 945)

                ),


                array(

                    'value' => 27, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 1815, '2' => 3020, '3' => 0, '4' => 1210)

                ),


                array(

                    'value' => 29, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 1,

                    'resources' => array('1' => 2320, '2' => 3870, '3' => 0, '4' => 1545)

                ),


                array(

                    'value' => 32, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 1,

                    'resources' => array('1' => 2970, '2' => 4950, '3' => 0, '4' => 1980)

                ),


                array(

                    'value' => 35, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 1,

                    'resources' => array('1' => 3805, '2' => 6340, '3' => 0, '4' => 2535)

                ),


                array(

                    'value' => 37, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 4870, '2' => 8115, '3' => 0, '4' => 3245)

                ),


                array(

                    'value' => 40, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 6230, '2' => 10385, '3' => 0, '4' => 4155)

                ),


                array(

                    'value' => 43, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 7975, '2' => 13290, '3' => 0, '4' => 5315)

                ),


                array(

                    'value' => 46, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 10210, '2' => 17015, '3' => 0, '4' => 6805)

                ),


                array(

                    'value' => 49, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 13065, '2' => 21780, '3' => 0, '4' => 8710)

                ),


            )

        ),


        '33' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('3' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 3, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 160, '2' => 100, '3' => 80, '4' => 60)

                ),


                array(

                    'value' => 5, 'time_consume' => 2620, 'cp' => 1, 'people_inc' => 0,

                    'resources' => array('1' => 205, '2' => 130, '3' => 100, '4' => 75)

                ),


                array(

                    'value' => 8, 'time_consume' => 3340, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 260, '2' => 165, '3' => 130, '4' => 100)

                ),


                array(

                    'value' => 10, 'time_consume' => 4170, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 335, '2' => 210, '3' => 170, '4' => 125)

                ),


                array(

                    'value' => 13, 'time_consume' => 5140, 'cp' => 2, 'people_inc' => 0,

                    'resources' => array('1' => 430, '2' => 270, '3' => 215, '4' => 160)

                ),


                array(

                    'value' => 16, 'time_consume' => 6260, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 550, '2' => 345, '3' => 275, '4' => 205)

                ),


                array(

                    'value' => 19, 'time_consume' => 7570, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 705, '2' => 440, '3' => 350, '4' => 265)

                ),


                array(

                    'value' => 22, 'time_consume' => 9080, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 900, '2' => 565, '3' => 450, '4' => 340)

                ),


                array(

                    'value' => 25, 'time_consume' => 10830, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 1155, '2' => 720, '3' => 575, '4' => 430)

                ),


                array(

                    'value' => 28, 'time_consume' => 12860, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 1475, '2' => 920, '3' => 740, '4' => 555)

                ),


                array(

                    'value' => 31, 'time_consume' => 15220, 'cp' => 7, 'people_inc' => 1,

                    'resources' => array('1' => 1890, '2' => 1180, '3' => 945, '4' => 710)

                ),


                array(

                    'value' => 34, 'time_consume' => 17950, 'cp' => 9, 'people_inc' => 1,

                    'resources' => array('1' => 2420, '2' => 1510, '3' => 1210, '4' => 905)

                ),


                array(

                    'value' => 38, 'time_consume' => 21130, 'cp' => 11, 'people_inc' => 1,

                    'resources' => array('1' => 3095, '2' => 1935, '3' => 1545, '4' => 1160)

                ),


                array(

                    'value' => 41, 'time_consume' => 24810, 'cp' => 13, 'people_inc' => 1,

                    'resources' => array('1' => 3960, '2' => 2475, '3' => 1980, '4' => 1485)

                ),


                array(

                    'value' => 45, 'time_consume' => 29080, 'cp' => 15, 'people_inc' => 1,

                    'resources' => array('1' => 5070, '2' => 3170, '3' => 2535, '4' => 1900)

                ),


                array(

                    'value' => 48, 'time_consume' => 34030, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 6490, '2' => 4055, '3' => 3245, '4' => 2435)

                ),


                array(

                    'value' => 52, 'time_consume' => 39770, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 8310, '2' => 5190, '3' => 4155, '4' => 3115)

                ),


                array(

                    'value' => 56, 'time_consume' => 46440, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 10635, '2' => 6645, '3' => 5315, '4' => 3990)

                ),


                array(

                    'value' => 60, 'time_consume' => 54170, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 13610, '2' => 8505, '3' => 6805, '4' => 5105)

                ),


                array(

                    'value' => 64, 'time_consume' => 63130, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 17420, '2' => 10890, '3' => 8710, '4' => 6535)

                ),


            )

        ),


        '34' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => FALSE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 5, '26' => 3),

            'levels' => array(


                array(

                    'value' => 110, 'time_consume' => 2200, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 155, '2' => 130, '3' => 125, '4' => 70)

                ),


                array(

                    'value' => 120, 'time_consume' => 3150, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 200, '2' => 165, '3' => 160, '4' => 90)

                ),


                array(

                    'value' => 130, 'time_consume' => 4260, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 255, '2' => 215, '3' => 205, '4' => 115)

                ),


                array(

                    'value' => 140, 'time_consume' => 5540, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 325, '2' => 275, '3' => 260, '4' => 145)

                ),


                array(

                    'value' => 150, 'time_consume' => 7020, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 415, '2' => 350, '3' => 335, '4' => 190)

                ),


                array(

                    'value' => 160, 'time_consume' => 8750, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 535, '2' => 445, '3' => 430, '4' => 240)

                ),


                array(

                    'value' => 170, 'time_consume' => 10750, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 680, '2' => 570, '3' => 550, '4' => 310)

                ),


                array(

                    'value' => 180, 'time_consume' => 13070, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 875, '2' => 730, '3' => 705, '4' => 395)

                ),


                array(

                    'value' => 190, 'time_consume' => 15760, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 1115, '2' => 935, '3' => 900, '4' => 505)

                ),


                array(

                    'value' => 200, 'time_consume' => 18880, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 1430, '2' => 1200, '3' => 1155, '4' => 645)

                ),


                array(

                    'value' => 210, 'time_consume' => 22500, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 1830, '2' => 1535, '3' => 1475, '4' => 825)

                ),


                array(

                    'value' => 220, 'time_consume' => 26700, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 2340, '2' => 1965, '3' => 1890, '4' => 1060)

                ),


                array(

                    'value' => 230, 'time_consume' => 31570, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 3000, '2' => 2515, '3' => 2420, '4' => 1355)

                ),


                array(

                    'value' => 240, 'time_consume' => 37220, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 3840, '2' => 3220, '3' => 3095, '4' => 1735)

                ),


                array(

                    'value' => 250, 'time_consume' => 43780, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 4910, '2' => 4120, '3' => 3960, '4' => 2220)

                ),


                array(

                    'value' => 260, 'time_consume' => 51380, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 6290, '2' => 5275, '3' => 5070, '4' => 2840)

                ),


                array(

                    'value' => 270, 'time_consume' => 60200, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 8050, '2' => 6750, '3' => 6490, '4' => 3635)

                ),


                array(

                    'value' => 280, 'time_consume' => 70430, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 10300, '2' => 8640, '3' => 8310, '4' => 4650)

                ),


                array(

                    'value' => 290, 'time_consume' => 82300, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 13185, '2' => 11060, '3' => 10635, '4' => 5955)

                ),


                array(

                    'value' => 300, 'time_consume' => 96070, 'cp' => 38, 'people_inc' => 3,

                    'resources' => array('1' => 16880, '2' => 14155, '3' => 13610, '4' => 7620)

                ),


            )

        ),


        '35' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => FALSE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('2' => 1),

            'pre_requests' => array('11' => 20, '16' => 10),

            'levels' => array(


                array(

                    'value' => 1, 'time_consume' => 1800, 'cp' => 1, 'people_inc' => 6,

                    'resources' => array('1' => 1200, '2' => 1400, '3' => 1050, '4' => 2200)

                ),


                array(

                    'value' => 2, 'time_consume' => 2390, 'cp' => 1, 'people_inc' => 3,

                    'resources' => array('1' => 1535, '2' => 1790, '3' => 1345, '4' => 2815)

                ),


                array(

                    'value' => 3, 'time_consume' => 3070, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 1965, '2' => 2295, '3' => 1720, '4' => 3605)

                ),


                array(

                    'value' => 4, 'time_consume' => 3860, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 2515, '2' => 2935, '3' => 2200, '4' => 4615)

                ),


                array(

                    'value' => 5, 'time_consume' => 4780, 'cp' => 2, 'people_inc' => 3,

                    'resources' => array('1' => 3220, '2' => 3760, '3' => 2820, '4' => 5905)

                ),


                array(

                    'value' => 6, 'time_consume' => 5840, 'cp' => 3, 'people_inc' => 4,

                    'resources' => array('1' => 4125, '2' => 4810, '3' => 3610, '4' => 7560)

                ),


                array(

                    'value' => 7, 'time_consume' => 7080, 'cp' => 4, 'people_inc' => 4,

                    'resources' => array('1' => 5280, '2' => 6155, '3' => 4620, '4' => 9675)

                ),


                array(

                    'value' => 8, 'time_consume' => 8510, 'cp' => 4, 'people_inc' => 4,

                    'resources' => array('1' => 6755, '2' => 7880, '3' => 5910, '4' => 12385)

                ),


                array(

                    'value' => 9, 'time_consume' => 10170, 'cp' => 5, 'people_inc' => 4,

                    'resources' => array('1' => 8645, '2' => 10090, '3' => 7565, '4' => 15855)

                ),


                array(

                    'value' => 10, 'time_consume' => 12100, 'cp' => 6, 'people_inc' => 4,

                    'resources' => array('1' => 11070, '2' => 12915, '3' => 9685, '4' => 20290)

                ),


                array(

                    'value' => 11, 'time_consume' => 14340, 'cp' => 7, 'people_inc' => 4,

                    'resources' => array('1' => 14165, '2' => 16530, '3' => 12395, '4' => 25975)

                ),


                array(

                    'value' => 12, 'time_consume' => 16930, 'cp' => 9, 'people_inc' => 4,

                    'resources' => array('1' => 18135, '2' => 21155, '3' => 15865, '4' => 33245)

                ),


                array(

                    'value' => 13, 'time_consume' => 19940, 'cp' => 11, 'people_inc' => 4,

                    'resources' => array('1' => 23210, '2' => 27080, '3' => 20310, '4' => 42555)

                ),


                array(

                    'value' => 14, 'time_consume' => 23430, 'cp' => 13, 'people_inc' => 4,

                    'resources' => array('1' => 29710, '2' => 34660, '3' => 25995, '4' => 54470)

                ),


                array(

                    'value' => 15, 'time_consume' => 27480, 'cp' => 15, 'people_inc' => 4,

                    'resources' => array('1' => 38030, '2' => 44370, '3' => 33275, '4' => 69720)

                ),


                array(

                    'value' => 16, 'time_consume' => 32180, 'cp' => 18, 'people_inc' => 5,

                    'resources' => array('1' => 48680, '2' => 56790, '3' => 42595, '4' => 89245)

                ),


                array(

                    'value' => 17, 'time_consume' => 37620, 'cp' => 22, 'people_inc' => 5,

                    'resources' => array('1' => 62310, '2' => 72690, '3' => 54520, '4' => 114230)

                ),


                array(

                    'value' => 18, 'time_consume' => 43940, 'cp' => 27, 'people_inc' => 5,

                    'resources' => array('1' => 79755, '2' => 93045, '3' => 69785, '4' => 146215)

                ),


                array(

                    'value' => 19, 'time_consume' => 51270, 'cp' => 32, 'people_inc' => 5,

                    'resources' => array('1' => 102085, '2' => 119100, '3' => 89325, '4' => 187155)

                ),


                array(

                    'value' => 20, 'time_consume' => 59780, 'cp' => 38, 'people_inc' => 5,

                    'resources' => array('1' => 130670, '2' => 152445, '3' => 114335, '4' => 239560)

                ),


            )

        ),


        '36' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('3' => 1),

            'pre_requests' => array('16' => 1),

            'levels' => array(


                array(

                    'value' => 10, 'time_consume' => 2000, 'cp' => 1, 'people_inc' => 4,

                    'resources' => array('1' => 100, '2' => 100, '3' => 100, '4' => 100)

                ),


                array(

                    'value' => 20, 'time_consume' => 2320, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 130, '2' => 130, '3' => 130, '4' => 130)

                ),


                array(

                    'value' => 30, 'time_consume' => 2690, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 165, '2' => 165, '3' => 165, '4' => 165)

                ),


                array(

                    'value' => 40, 'time_consume' => 3120, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 210, '2' => 210, '3' => 210, '4' => 210)

                ),


                array(

                    'value' => 50, 'time_consume' => 3620, 'cp' => 2, 'people_inc' => 2,

                    'resources' => array('1' => 270, '2' => 270, '3' => 270, '4' => 270)

                ),


                array(

                    'value' => 60, 'time_consume' => 4200, 'cp' => 3, 'people_inc' => 3,

                    'resources' => array('1' => 345, '2' => 345, '3' => 345, '4' => 345)

                ),


                array(

                    'value' => 70, 'time_consume' => 4870, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 440, '2' => 440, '3' => 440, '4' => 440)

                ),


                array(

                    'value' => 80, 'time_consume' => 5650, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 565, '2' => 565, '3' => 565, '4' => 565)

                ),


                array(

                    'value' => 90, 'time_consume' => 6560, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 720, '2' => 720, '3' => 720, '4' => 720)

                ),


                array(

                    'value' => 100, 'time_consume' => 7610, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 920, '2' => 920, '3' => 920, '4' => 920)

                ),


                array(

                    'value' => 110, 'time_consume' => 8820, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 1180, '2' => 1180, '3' => 1180, '4' => 1180)

                ),


                array(

                    'value' => 120, 'time_consume' => 10230, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 1510, '2' => 1510, '3' => 1510, '4' => 1510)

                ),


                array(

                    'value' => 130, 'time_consume' => 11870, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 1935, '2' => 1935, '3' => 1935, '4' => 1935)

                ),


                array(

                    'value' => 140, 'time_consume' => 13770, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 2475, '2' => 2475, '3' => 2475, '4' => 2475)

                ),


                array(

                    'value' => 150, 'time_consume' => 15980, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 3170, '2' => 3170, '3' => 3170, '4' => 3170)

                ),


                array(

                    'value' => 160, 'time_consume' => 18530, 'cp' => 18, 'people_inc' => 4,

                    'resources' => array('1' => 4055, '2' => 4055, '3' => 4055, '4' => 4055)

                ),


                array(

                    'value' => 170, 'time_consume' => 21500, 'cp' => 22, 'people_inc' => 4,

                    'resources' => array('1' => 5190, '2' => 5190, '3' => 5190, '4' => 5190)

                ),


                array(

                    'value' => 180, 'time_consume' => 24940, 'cp' => 27, 'people_inc' => 4,

                    'resources' => array('1' => 6645, '2' => 6645, '3' => 6645, '4' => 6645)

                ),


                array(

                    'value' => 190, 'time_consume' => 28930, 'cp' => 32, 'people_inc' => 4,

                    'resources' => array('1' => 8505, '2' => 8505, '3' => 8505, '4' => 8505)

                ),


                array(

                    'value' => 200, 'time_consume' => 33550, 'cp' => 38, 'people_inc' => 4,

                    'resources' => array('1' => 10890, '2' => 10890, '3' => 10890, '4' => 10890)

                ),


            )

        ),


        '37' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 3, '16' => 1),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 2300, 'cp' => 1, 'people_inc' => 2,

                    'resources' => array('1' => 700, '2' => 670, '3' => 700, '4' => 240)

                ),


                array(

                    'value' => 0, 'time_consume' => 2670, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 930, '2' => 890, '3' => 930, '4' => 320)

                ),


                array(

                    'value' => 0, 'time_consume' => 3090, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1240, '2' => 1185, '3' => 1240, '4' => 425)

                ),


                array(

                    'value' => 0, 'time_consume' => 3590, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1645, '2' => 1575, '3' => 1645, '4' => 565)

                ),


                array(

                    'value' => 0, 'time_consume' => 4160, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 2190, '2' => 2095, '3' => 2190, '4' => 750)

                ),


                array(

                    'value' => 0, 'time_consume' => 4830, 'cp' => 3, 'people_inc' => 2,

                    'resources' => array('1' => 2915, '2' => 2790, '3' => 2915, '4' => 1000)

                ),


                array(

                    'value' => 0, 'time_consume' => 5600, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 3875, '2' => 3710, '3' => 3875, '4' => 1330)

                ),


                array(

                    'value' => 0, 'time_consume' => 6500, 'cp' => 4, 'people_inc' => 2,

                    'resources' => array('1' => 5155, '2' => 4930, '3' => 5155, '4' => 1765)

                ),


                array(

                    'value' => 0, 'time_consume' => 7540, 'cp' => 5, 'people_inc' => 2,

                    'resources' => array('1' => 6855, '2' => 6560, '3' => 6855, '4' => 2350)

                ),


                array(

                    'value' => 1, 'time_consume' => 8750, 'cp' => 6, 'people_inc' => 2,

                    'resources' => array('1' => 9115, '2' => 8725, '3' => 9115, '4' => 3125)

                ),


                array(

                    'value' => 1, 'time_consume' => 10150, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 12125, '2' => 11605, '3' => 12125, '4' => 4155)

                ),


                array(

                    'value' => 1, 'time_consume' => 11770, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 16125, '2' => 15435, '3' => 16125, '4' => 5530)

                ),


                array(

                    'value' => 1, 'time_consume' => 13650, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 21445, '2' => 20525, '3' => 21445, '4' => 7350)

                ),


                array(

                    'value' => 1, 'time_consume' => 15840, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 28520, '2' => 27300, '3' => 28520, '4' => 9780)

                ),


                array(

                    'value' => 2, 'time_consume' => 18370, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 37935, '2' => 36310, '3' => 37935, '4' => 13005)

                ),


                array(

                    'value' => 2, 'time_consume' => 21310, 'cp' => 18, 'people_inc' => 3,

                    'resources' => array('1' => 50450, '2' => 48290, '3' => 50450, '4' => 17300)

                ),


                array(

                    'value' => 2, 'time_consume' => 24720, 'cp' => 22, 'people_inc' => 3,

                    'resources' => array('1' => 67100, '2' => 64225, '3' => 67100, '4' => 23005)

                ),


                array(

                    'value' => 2, 'time_consume' => 28680, 'cp' => 27, 'people_inc' => 3,

                    'resources' => array('1' => 89245, '2' => 85420, '3' => 89245, '4' => 30600)

                ),


                array(

                    'value' => 2, 'time_consume' => 33260, 'cp' => 32, 'people_inc' => 3,

                    'resources' => array('1' => 118695, '2' => 113605, '3' => 118695, '4' => 40695)

                ),


                array(

                    'value' => 3, 'time_consume' => 38590, 'cp' => 38, 'people_inc' => 3,

                    'resources' => array('1' => 157865, '2' => 151095, '3' => 157865, '4' => 54125)

                ),


            )

        ),


        '38' => array(

            'support_multiple' => TRUE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 10),

            'levels' => array(


                array(

                    'value' => $capacity * 3600, 'time_consume' => 4500, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 650, '2' => 800, '3' => 450, '4' => 200)

                ),


                array(

                    'value' => $capacity * 5100, 'time_consume' => 5370, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 830, '2' => 1025, '3' => 575, '4' => 255)

                ),


                array(

                    'value' => $capacity * 6900, 'time_consume' => 6380, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1065, '2' => 1310, '3' => 735, '4' => 330)

                ),


                array(

                    'value' => $capacity * 9300, 'time_consume' => 7550, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1365, '2' => 1680, '3' => 945, '4' => 420)

                ),


                array(

                    'value' => $capacity * 12000, 'time_consume' => 8910, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1745, '2' => 2145, '3' => 1210, '4' => 535)

                ),


                array(

                    'value' => $capacity * 15000, 'time_consume' => 10490, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 2235, '2' => 2750, '3' => 1545, '4' => 685)

                ),


                array(

                    'value' => $capacity * 18900, 'time_consume' => 12310, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 2860, '2' => 3520, '3' => 1980, '4' => 880)

                ),


                array(

                    'value' => $capacity * 23400, 'time_consume' => 14430, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 3660, '2' => 4505, '3' => 2535, '4' => 1125)

                ),


                array(

                    'value' => $capacity * 28800, 'time_consume' => 16890, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 4685, '2' => 5765, '3' => 3245, '4' => 1440)

                ),


                array(

                    'value' => $capacity * 35400, 'time_consume' => 19740, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 5995, '2' => 7380, '3' => 4150, '4' => 1845)

                ),


                array(

                    'value' => $capacity * 43200, 'time_consume' => 23050, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 7675, '2' => 9445, '3' => 5315, '4' => 2360)

                ),


                array(

                    'value' => $capacity * 52800, 'time_consume' => 26890, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 9825, '2' => 12090, '3' => 6800, '4' => 3020)

                ),


                array(

                    'value' => $capacity * 64200, 'time_consume' => 31340, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 12575, '2' => 15475, '3' => 8705, '4' => 3870)

                ),


                array(

                    'value' => $capacity * 77700, 'time_consume' => 36510, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 16095, '2' => 19805, '3' => 11140, '4' => 4950)

                ),


                array(

                    'value' => $capacity * 93900, 'time_consume' => 42500, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 20600, '2' => 25355, '3' => 14260, '4' => 6340)

                ),


                array(

                    'value' => $capacity * 113700, 'time_consume' => 49450, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 26365, '2' => 32450, '3' => 18255, '4' => 8115)

                ),


                array(

                    'value' => $capacity * 137100, 'time_consume' => 57510, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 33750, '2' => 41540, '3' => 23365, '4' => 10385)

                ),


                array(

                    'value' => $capacity * 165300, 'time_consume' => 66860, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 43200, '2' => 53170, '3' => 29910, '4' => 13290)

                ),


                array(

                    'value' => $capacity * 199200, 'time_consume' => 77700, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 55295, '2' => 68055, '3' => 38280, '4' => 17015)

                ),


                array(

                    'value' => $capacity * 240000, 'time_consume' => 90290, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 70780, '2' => 87110, '3' => 49000, '4' => 21780)

                ),


            )

        ),


        '39' => array(

            'support_multiple' => TRUE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array('15' => 10),

            'levels' => array(


                array(

                    'value' => $capacity * 3600, 'time_consume' => 3500, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 400, '2' => 500, '3' => 350, '4' => 100)

                ),


                array(

                    'value' => $capacity * 5100, 'time_consume' => 4210, 'cp' => 1, 'people_inc' => 1,

                    'resources' => array('1' => 510, '2' => 640, '3' => 450, '4' => 130)

                ),


                array(

                    'value' => $capacity * 6900, 'time_consume' => 5040, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 655, '2' => 820, '3' => 575, '4' => 165)

                ),


                array(

                    'value' => $capacity * 9300, 'time_consume' => 5990, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 840, '2' => 1050, '3' => 735, '4' => 210)

                ),


                array(

                    'value' => $capacity * 12000, 'time_consume' => 7100, 'cp' => 2, 'people_inc' => 1,

                    'resources' => array('1' => 1075, '2' => 1340, '3' => 940, '4' => 270)

                ),


                array(

                    'value' => $capacity * 15000, 'time_consume' => 8390, 'cp' => 3, 'people_inc' => 1,

                    'resources' => array('1' => 1375, '2' => 1720, '3' => 1205, '4' => 345)

                ),


                array(

                    'value' => $capacity * 18900, 'time_consume' => 9880, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 1760, '2' => 2200, '3' => 1540, '4' => 440)

                ),


                array(

                    'value' => $capacity * 23400, 'time_consume' => 11610, 'cp' => 4, 'people_inc' => 1,

                    'resources' => array('1' => 2250, '2' => 2815, '3' => 1970, '4' => 565)

                ),


                array(

                    'value' => $capacity * 28800, 'time_consume' => 13610, 'cp' => 5, 'people_inc' => 1,

                    'resources' => array('1' => 2880, '2' => 3605, '3' => 2520, '4' => 720)

                ),


                array(

                    'value' => $capacity * 35400, 'time_consume' => 15940, 'cp' => 6, 'people_inc' => 1,

                    'resources' => array('1' => 3690, '2' => 4610, '3' => 3230, '4' => 920)

                ),


                array(

                    'value' => $capacity * 43200, 'time_consume' => 18640, 'cp' => 7, 'people_inc' => 2,

                    'resources' => array('1' => 4720, '2' => 5905, '3' => 4130, '4' => 1180)

                ),


                array(

                    'value' => $capacity * 52800, 'time_consume' => 21770, 'cp' => 9, 'people_inc' => 2,

                    'resources' => array('1' => 6045, '2' => 7555, '3' => 5290, '4' => 1510)

                ),


                array(

                    'value' => $capacity * 64200, 'time_consume' => 25410, 'cp' => 11, 'people_inc' => 2,

                    'resources' => array('1' => 7735, '2' => 9670, '3' => 6770, '4' => 1935)

                ),


                array(

                    'value' => $capacity * 77700, 'time_consume' => 29620, 'cp' => 13, 'people_inc' => 2,

                    'resources' => array('1' => 9905, '2' => 12380, '3' => 8665, '4' => 2475)

                ),


                array(

                    'value' => $capacity * 93900, 'time_consume' => 34510, 'cp' => 15, 'people_inc' => 2,

                    'resources' => array('1' => 12675, '2' => 15845, '3' => 11090, '4' => 3170)

                ),


                array(

                    'value' => $capacity * 113700, 'time_consume' => 40180, 'cp' => 18, 'people_inc' => 2,

                    'resources' => array('1' => 16225, '2' => 20280, '3' => 14200, '4' => 4055)

                ),


                array(

                    'value' => $capacity * 137100, 'time_consume' => 46760, 'cp' => 22, 'people_inc' => 2,

                    'resources' => array('1' => 20770, '2' => 25960, '3' => 18175, '4' => 5190)

                ),


                array(

                    'value' => $capacity * 165300, 'time_consume' => 54390, 'cp' => 27, 'people_inc' => 2,

                    'resources' => array('1' => 26585, '2' => 33230, '3' => 23260, '4' => 6645)

                ),


                array(

                    'value' => $capacity * 199200, 'time_consume' => 63240, 'cp' => 32, 'people_inc' => 2,

                    'resources' => array('1' => 34030, '2' => 42535, '3' => 29775, '4' => 8505)

                ),


                array(

                    'value' => $capacity * 240000, 'time_consume' => 73510, 'cp' => 38, 'people_inc' => 2,

                    'resources' => array('1' => 43555, '2' => 54445, '3' => 38110, '4' => 10890)

                ),


            )

        ),


        '40' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => TRUE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1),

            'pre_requests' => array(),

            'levels' => array(


                array(

                    'value' => 0, 'time_consume' => 9000, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 66700, '2' => 69050, '3' => 72200, '4' => 13200)

                ),


                array(

                    'value' => 0, 'time_consume' => 9430, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 68535, '2' => 70950, '3' => 74185, '4' => 13565)

                ),


                array(

                    'value' => 0, 'time_consume' => 9860, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 70420, '2' => 72900, '3' => 76225, '4' => 13935)

                ),


                array(

                    'value' => 0, 'time_consume' => 10300, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 72355, '2' => 74905, '3' => 78320, '4' => 14320)

                ),


                array(

                    'value' => 0, 'time_consume' => 10740, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 74345, '2' => 76965, '3' => 80475, '4' => 14715)

                ),


                array(

                    'value' => 0, 'time_consume' => 11190, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 76390, '2' => 79080, '3' => 82690, '4' => 15120)

                ),


                array(

                    'value' => 0, 'time_consume' => 11650, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 78490, '2' => 81255, '3' => 84965, '4' => 15535)

                ),


                array(

                    'value' => 0, 'time_consume' => 12110, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 80650, '2' => 83490, '3' => 87300, '4' => 15960)

                ),


                array(

                    'value' => 0, 'time_consume' => 12580, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 82865, '2' => 85785, '3' => 89700, '4' => 16400)

                ),


                array(

                    'value' => 0, 'time_consume' => 13060, 'cp' => 0, 'people_inc' => 1,

                    'resources' => array('1' => 85145, '2' => 88145, '3' => 92165, '4' => 16850)

                ),


                array(

                    'value' => 0, 'time_consume' => 13540, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 87485, '2' => 90570, '3' => 94700, '4' => 17315)

                ),


                array(

                    'value' => 0, 'time_consume' => 14030, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 89895, '2' => 93060, '3' => 97305, '4' => 17790)

                ),


                array(

                    'value' => 0, 'time_consume' => 14530, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 92365, '2' => 95620, '3' => 99980, '4' => 18280)

                ),


                array(

                    'value' => 0, 'time_consume' => 15030, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 94905, '2' => 98250, '3' => 102730, '4' => 18780)

                ),


                array(

                    'value' => 0, 'time_consume' => 15540, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 97515, '2' => 100950, '3' => 105555, '4' => 19300)

                ),


                array(

                    'value' => 0, 'time_consume' => 16060, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 100195, '2' => 103725, '3' => 108460, '4' => 19830)

                ),


                array(

                    'value' => 0, 'time_consume' => 16580, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 102950, '2' => 106580, '3' => 111440, '4' => 20375)

                ),


                array(

                    'value' => 0, 'time_consume' => 17120, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 105785, '2' => 109510, '3' => 114505, '4' => 20935)

                ),


                array(

                    'value' => 0, 'time_consume' => 17650, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 108690, '2' => 112520, '3' => 117655, '4' => 21510)

                ),


                array(

                    'value' => 0, 'time_consume' => 18200, 'cp' => 0, 'people_inc' => 2,

                    'resources' => array('1' => 111680, '2' => 115615, '3' => 120890, '4' => 22100)

                ),


                array(

                    'value' => 0, 'time_consume' => 18760, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 114755, '2' => 118795, '3' => 124215, '4' => 22710)

                ),


                array(

                    'value' => 0, 'time_consume' => 19320, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 117910, '2' => 122060, '3' => 127630, '4' => 23335)

                ),


                array(

                    'value' => 0, 'time_consume' => 19890, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 121150, '2' => 125420, '3' => 131140, '4' => 23975)

                ),


                array(

                    'value' => 0, 'time_consume' => 20470, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 124480, '2' => 128870, '3' => 134745, '4' => 24635)

                ),


                array(

                    'value' => 0, 'time_consume' => 21050, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 127905, '2' => 132410, '3' => 138455, '4' => 25315)

                ),


                array(

                    'value' => 0, 'time_consume' => 21650, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 131425, '2' => 136055, '3' => 142260, '4' => 26010)

                ),


                array(

                    'value' => 0, 'time_consume' => 22250, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 135035, '2' => 139795, '3' => 146170, '4' => 26725)

                ),


                array(

                    'value' => 0, 'time_consume' => 22860, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 138750, '2' => 143640, '3' => 150190, '4' => 27460)

                ),


                array(

                    'value' => 0, 'time_consume' => 23480, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 142565, '2' => 147590, '3' => 154320, '4' => 28215)

                ),


                array(

                    'value' => 0, 'time_consume' => 24110, 'cp' => 0, 'people_inc' => 3,

                    'resources' => array('1' => 146485, '2' => 151650, '3' => 158565, '4' => 28990)

                ),


                array(

                    'value' => 0, 'time_consume' => 24750, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 150515, '2' => 155820, '3' => 162925, '4' => 29785)

                ),


                array(

                    'value' => 0, 'time_consume' => 25400, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 154655, '2' => 160105, '3' => 167405, '4' => 30605)

                ),


                array(

                    'value' => 0, 'time_consume' => 26050, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 158910, '2' => 164505, '3' => 172010, '4' => 31450)

                ),


                array(

                    'value' => 0, 'time_consume' => 26720, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 163275, '2' => 169030, '3' => 176740, '4' => 32315)

                ),


                array(

                    'value' => 0, 'time_consume' => 27390, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 167770, '2' => 173680, '3' => 181600, '4' => 33200)

                ),


                array(

                    'value' => 0, 'time_consume' => 28070, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 172380, '2' => 178455, '3' => 186595, '4' => 34115)

                ),


                array(

                    'value' => 0, 'time_consume' => 28770, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 177120, '2' => 183360, '3' => 191725, '4' => 35055)

                ),


                array(

                    'value' => 0, 'time_consume' => 29470, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 181995, '2' => 188405, '3' => 197000, '4' => 36015)

                ),


                array(

                    'value' => 0, 'time_consume' => 30180, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 186995, '2' => 193585, '3' => 202415, '4' => 37005)

                ),


                array(

                    'value' => 0, 'time_consume' => 30910, 'cp' => 0, 'people_inc' => 4,

                    'resources' => array('1' => 192140, '2' => 198910, '3' => 207985, '4' => 38025)

                ),


                array(

                    'value' => 0, 'time_consume' => 31640, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 197425, '2' => 204380, '3' => 213705, '4' => 39070)

                ),


                array(

                    'value' => 0, 'time_consume' => 32380, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 202855, '2' => 210000, '3' => 219580, '4' => 40145)

                ),


                array(

                    'value' => 0, 'time_consume' => 33130, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 208430, '2' => 215775, '3' => 225620, '4' => 41250)

                ),


                array(

                    'value' => 0, 'time_consume' => 33900, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 214165, '2' => 221710, '3' => 231825, '4' => 42385)

                ),


                array(

                    'value' => 0, 'time_consume' => 34670, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 220055, '2' => 227805, '3' => 238200, '4' => 43550)

                ),


                array(

                    'value' => 0, 'time_consume' => 35460, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 226105, '2' => 234070, '3' => 244750, '4' => 44745)

                ),


                array(

                    'value' => 0, 'time_consume' => 36250, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 232320, '2' => 240505, '3' => 251480, '4' => 45975)

                ),


                array(

                    'value' => 0, 'time_consume' => 37060, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 238710, '2' => 247120, '3' => 258395, '4' => 47240)

                ),


                array(

                    'value' => 0, 'time_consume' => 37880, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 245275, '2' => 253915, '3' => 265500, '4' => 48540)

                ),


                array(

                    'value' => 0, 'time_consume' => 38710, 'cp' => 0, 'people_inc' => 5,

                    'resources' => array('1' => 252020, '2' => 260900, '3' => 272800, '4' => 49875)

                ),


                array(

                    'value' => 0, 'time_consume' => 39550, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 258950, '2' => 268075, '3' => 280305, '4' => 51245)

                ),


                array(

                    'value' => 0, 'time_consume' => 40410, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 266070, '2' => 275445, '3' => 288010, '4' => 52655)

                ),


                array(

                    'value' => 0, 'time_consume' => 41270, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 273390, '2' => 283020, '3' => 295930, '4' => 54105)

                ),


                array(

                    'value' => 0, 'time_consume' => 42150, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 280905, '2' => 290805, '3' => 304070, '4' => 55590)

                ),


                array(

                    'value' => 0, 'time_consume' => 43040, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 288630, '2' => 298800, '3' => 312430, '4' => 57120)

                ),


                array(

                    'value' => 0, 'time_consume' => 43940, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 296570, '2' => 307020, '3' => 321025, '4' => 58690)

                ),


                array(

                    'value' => 0, 'time_consume' => 44860, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 304725, '2' => 315460, '3' => 329850, '4' => 60305)

                ),


                array(

                    'value' => 0, 'time_consume' => 45790, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 313105, '2' => 324135, '3' => 338925, '4' => 61965)

                ),


                array(

                    'value' => 0, 'time_consume' => 46730, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 321715, '2' => 333050, '3' => 348245, '4' => 63670)

                ),


                array(

                    'value' => 0, 'time_consume' => 47680, 'cp' => 0, 'people_inc' => 6,

                    'resources' => array('1' => 330565, '2' => 342210, '3' => 357820, '4' => 65420)

                ),


                array(

                    'value' => 0, 'time_consume' => 48650, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 339655, '2' => 351620, '3' => 367660, '4' => 67220)

                ),


                array(

                    'value' => 0, 'time_consume' => 49630, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 348995, '2' => 361290, '3' => 377770, '4' => 69065)

                ),


                array(

                    'value' => 0, 'time_consume' => 50620, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 358590, '2' => 371225, '3' => 388160, '4' => 70965)

                ),


                array(

                    'value' => 0, 'time_consume' => 51630, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 368450, '2' => 381435, '3' => 398835, '4' => 72915)

                ),


                array(

                    'value' => 0, 'time_consume' => 52660, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 378585, '2' => 391925, '3' => 409800, '4' => 74920)

                ),


                array(

                    'value' => 0, 'time_consume' => 53690, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 388995, '2' => 402700, '3' => 421070, '4' => 76985)

                ),


                array(

                    'value' => 0, 'time_consume' => 54740, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 399695, '2' => 413775, '3' => 432650, '4' => 79100)

                ),


                array(

                    'value' => 0, 'time_consume' => 55810, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 410685, '2' => 425155, '3' => 444550, '4' => 81275)

                ),


                array(

                    'value' => 0, 'time_consume' => 56890, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 421980, '2' => 436845, '3' => 456775, '4' => 83510)

                ),


                array(

                    'value' => 0, 'time_consume' => 57990, 'cp' => 0, 'people_inc' => 7,

                    'resources' => array('1' => 433585, '2' => 448860, '3' => 469335, '4' => 85805)

                ),


                array(

                    'value' => 0, 'time_consume' => 59100, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 445505, '2' => 461205, '3' => 482240, '4' => 88165)

                ),


                array(

                    'value' => 0, 'time_consume' => 60230, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 457760, '2' => 473885, '3' => 495505, '4' => 90590)

                ),


                array(

                    'value' => 0, 'time_consume' => 61370, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 470345, '2' => 486920, '3' => 509130, '4' => 93080)

                ),


                array(

                    'value' => 0, 'time_consume' => 62530, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 483280, '2' => 500310, '3' => 523130, '4' => 95640)

                ),


                array(

                    'value' => 0, 'time_consume' => 63710, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 496570, '2' => 514065, '3' => 537520, '4' => 98270)

                ),


                array(

                    'value' => 0, 'time_consume' => 64900, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 510225, '2' => 528205, '3' => 552300, '4' => 100975)

                ),


                array(

                    'value' => 0, 'time_consume' => 66110, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 524260, '2' => 542730, '3' => 567490, '4' => 103750)

                ),


                array(

                    'value' => 0, 'time_consume' => 67330, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 538675, '2' => 557655, '3' => 583095, '4' => 106605)

                ),


                array(

                    'value' => 0, 'time_consume' => 68570, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 553490, '2' => 572990, '3' => 599130, '4' => 109535)

                ),


                array(

                    'value' => 0, 'time_consume' => 69830, 'cp' => 0, 'people_inc' => 8,

                    'resources' => array('1' => 568710, '2' => 588745, '3' => 615605, '4' => 112550)

                ),


                array(

                    'value' => 0, 'time_consume' => 71110, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 584350, '2' => 604935, '3' => 632535, '4' => 115645)

                ),


                array(

                    'value' => 0, 'time_consume' => 72410, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 600420, '2' => 621575, '3' => 649930, '4' => 118825)

                ),


                array(

                    'value' => 0, 'time_consume' => 73720, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 616930, '2' => 638665, '3' => 667800, '4' => 122090)

                ),


                array(

                    'value' => 0, 'time_consume' => 75050, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 633895, '2' => 656230, '3' => 686165, '4' => 125450)

                ),


                array(

                    'value' => 0, 'time_consume' => 76400, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 651330, '2' => 674275, '3' => 705035, '4' => 128900)

                ),


                array(

                    'value' => 0, 'time_consume' => 77770, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 669240, '2' => 692820, '3' => 724425, '4' => 132445)

                ),


                array(

                    'value' => 0, 'time_consume' => 79160, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 687645, '2' => 711870, '3' => 744345, '4' => 136085)

                ),


                array(

                    'value' => 0, 'time_consume' => 80570, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 706555, '2' => 731445, '3' => 764815, '4' => 139830)

                ),


                array(

                    'value' => 0, 'time_consume' => 82000, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 725985, '2' => 751560, '3' => 785850, '4' => 143675)

                ),


                array(

                    'value' => 0, 'time_consume' => 83450, 'cp' => 0, 'people_inc' => 9,

                    'resources' => array('1' => 745950, '2' => 772230, '3' => 807460, '4' => 147625)

                ),


                array(

                    'value' => 0, 'time_consume' => 84910, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 766460, '2' => 793465, '3' => 829665, '4' => 151685)

                ),


                array(

                    'value' => 0, 'time_consume' => 86400, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 787540, '2' => 815285, '3' => 852480, '4' => 155855)

                ),


                array(

                    'value' => 0, 'time_consume' => 87910, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 809195, '2' => 837705, '3' => 875920, '4' => 160140)

                ),


                array(

                    'value' => 0, 'time_consume' => 89440, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 831450, '2' => 860745, '3' => 900010, '4' => 164545)

                ),


                array(

                    'value' => 0, 'time_consume' => 91000, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 854315, '2' => 884415, '3' => 924760, '4' => 169070)

                ),


                array(

                    'value' => 0, 'time_consume' => 92570, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 877810, '2' => 908735, '3' => 950190, '4' => 173720)

                ),


                array(

                    'value' => 0, 'time_consume' => 94170, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 901950, '2' => 933725, '3' => 976320, '4' => 178495)

                ),


                array(

                    'value' => 0, 'time_consume' => 95780, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 926750, '2' => 959405, '3' => 1000000, '4' => 183405)

                ),


                array(

                    'value' => 0, 'time_consume' => 97420, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 952235, '2' => 985785, '3' => 1000000, '4' => 188450)

                ),


                array(

                    'value' => 0, 'time_consume' => 99090, 'cp' => 0, 'people_inc' => 10,

                    'resources' => array('1' => 1000000, '2' => 1000000, '3' => 1000000, '4' => 193630)

                ),


            )

        ),


        '41' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => array('1' => 1),

            'pre_requests' => array('20' => 20, '16' => 10),

            'levels' => array(


                array(

                    'value' => 1, 'time_consume' => 2200, 'cp' => 4, 'people_inc' => 5,

                    'resources' => array('1' => 780, '2' => 420, '3' => 660, '4' => 540)

                ),


                array(

                    'value' => 2, 'time_consume' => 3150, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 1000, '2' => 540, '3' => 845, '4' => 690)

                ),


                array(

                    'value' => 3, 'time_consume' => 4260, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 1280, '2' => 690, '3' => 1080, '4' => 885)

                ),


                array(

                    'value' => 4, 'time_consume' => 5540, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 1635, '2' => 880, '3' => 1385, '4' => 1130)

                ),


                array(

                    'value' => 5, 'time_consume' => 7020, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 2095, '2' => 1125, '3' => 1770, '4' => 1450)

                ),


                array(

                    'value' => 6, 'time_consume' => 8750, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 2680, '2' => 1445, '3' => 2270, '4' => 1855)

                ),


                array(

                    'value' => 7, 'time_consume' => 10750, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 3430, '2' => 1845, '3' => 2905, '4' => 2375)

                ),


                array(

                    'value' => 8, 'time_consume' => 13070, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 4390, '2' => 2365, '3' => 3715, '4' => 3040)

                ),


                array(

                    'value' => 9, 'time_consume' => 15760, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 5620, '2' => 3025, '3' => 4755, '4' => 3890)

                ),


                array(

                    'value' => 10, 'time_consume' => 18880, 'cp' => 19, 'people_inc' => 3,

                    'resources' => array('1' => 7195, '2' => 3875, '3' => 6085, '4' => 4980)

                ),


                array(

                    'value' => 11, 'time_consume' => 22500, 'cp' => 22, 'people_inc' => 4,

                    'resources' => array('1' => 9210, '2' => 4960, '3' => 7790, '4' => 6375)

                ),


                array(

                    'value' => 12, 'time_consume' => 26700, 'cp' => 27, 'people_inc' => 4,

                    'resources' => array('1' => 11785, '2' => 6345, '3' => 9975, '4' => 8160)

                ),


                array(

                    'value' => 13, 'time_consume' => 31570, 'cp' => 32, 'people_inc' => 4,

                    'resources' => array('1' => 15085, '2' => 8125, '3' => 12765, '4' => 10445)

                ),


                array(

                    'value' => 14, 'time_consume' => 37220, 'cp' => 39, 'people_inc' => 4,

                    'resources' => array('1' => 19310, '2' => 10400, '3' => 16340, '4' => 13370)

                ),


                array(

                    'value' => 15, 'time_consume' => 43780, 'cp' => 46, 'people_inc' => 4,

                    'resources' => array('1' => 24720, '2' => 13310, '3' => 20915, '4' => 17115)

                ),


                array(

                    'value' => 16, 'time_consume' => 51380, 'cp' => 55, 'people_inc' => 4,

                    'resources' => array('1' => 31640, '2' => 17035, '3' => 26775, '4' => 21905)

                ),


                array(

                    'value' => 17, 'time_consume' => 60200, 'cp' => 67, 'people_inc' => 4,

                    'resources' => array('1' => 40500, '2' => 21810, '3' => 34270, '4' => 28040)

                ),


                array(

                    'value' => 18, 'time_consume' => 70430, 'cp' => 80, 'people_inc' => 4,

                    'resources' => array('1' => 51840, '2' => 27915, '3' => 43865, '4' => 35890)

                ),


                array(

                    'value' => 19, 'time_consume' => 82300, 'cp' => 96, 'people_inc' => 4,

                    'resources' => array('1' => 66355, '2' => 35730, '3' => 56145, '4' => 45940)

                ),


                array(

                    'value' => 20, 'time_consume' => 96070, 'cp' => 115, 'people_inc' => 4,

                    'resources' => array('1' => 84935, '2' => 45735, '3' => 71870, '4' => 58800)

                ),


            )

        ),


        '42' => array(

            'support_multiple' => FALSE, 'built_in_capital' => TRUE, 'built_in_non_capital' => TRUE, 'built_in_special_only' => FALSE, 'max_lvl_in_non_capital' => NULL,

            'for_tribe_id' => ($gameConfig['settings']['buytroop'] == 1) ? array('1' => 1, '2' => 1, '3' => 1, '4' => 1, '7' => 1) : array(),

            'pre_requests' => array('20' => 5, '15' => 10),

            'levels' => array(


                array(

                    'value' => 20, 'time_consume' => 2200, 'cp' => 4, 'people_inc' => 5,

                    'resources' => array('1' => 780, '2' => 420, '3' => 660, '4' => 540)

                ),


                array(

                    'value' => 19, 'time_consume' => 3150, 'cp' => 4, 'people_inc' => 3,

                    'resources' => array('1' => 1000, '2' => 540, '3' => 845, '4' => 690)

                ),


                array(

                    'value' => 18, 'time_consume' => 4260, 'cp' => 5, 'people_inc' => 3,

                    'resources' => array('1' => 1280, '2' => 690, '3' => 1080, '4' => 885)

                ),


                array(

                    'value' => 17, 'time_consume' => 5540, 'cp' => 6, 'people_inc' => 3,

                    'resources' => array('1' => 1635, '2' => 880, '3' => 1385, '4' => 1130)

                ),


                array(

                    'value' => 16, 'time_consume' => 7020, 'cp' => 7, 'people_inc' => 3,

                    'resources' => array('1' => 2095, '2' => 1125, '3' => 1770, '4' => 1450)

                ),


                array(

                    'value' => 15, 'time_consume' => 8750, 'cp' => 9, 'people_inc' => 3,

                    'resources' => array('1' => 2680, '2' => 1445, '3' => 2270, '4' => 1855)

                ),


                array(

                    'value' => 14, 'time_consume' => 10750, 'cp' => 11, 'people_inc' => 3,

                    'resources' => array('1' => 3430, '2' => 1845, '3' => 2905, '4' => 2375)

                ),


                array(

                    'value' => 13, 'time_consume' => 13070, 'cp' => 13, 'people_inc' => 3,

                    'resources' => array('1' => 4390, '2' => 2365, '3' => 3715, '4' => 3040)

                ),


                array(

                    'value' => 12, 'time_consume' => 15760, 'cp' => 15, 'people_inc' => 3,

                    'resources' => array('1' => 5620, '2' => 3025, '3' => 4755, '4' => 3890)

                ),


                array(

                    'value' => 11, 'time_consume' => 18880, 'cp' => 19, 'people_inc' => 3,

                    'resources' => array('1' => 7195, '2' => 3875, '3' => 6085, '4' => 4980)

                ),


                array(

                    'value' => 10, 'time_consume' => 22500, 'cp' => 22, 'people_inc' => 4,

                    'resources' => array('1' => 9210, '2' => 4960, '3' => 7790, '4' => 6375)

                ),


                array(

                    'value' => 9, 'time_consume' => 26700, 'cp' => 27, 'people_inc' => 4,

                    'resources' => array('1' => 11785, '2' => 6345, '3' => 9975, '4' => 8160)

                ),


                array(

                    'value' => 8, 'time_consume' => 31570, 'cp' => 32, 'people_inc' => 4,

                    'resources' => array('1' => 15085, '2' => 8125, '3' => 12765, '4' => 10445)

                ),


                array(

                    'value' => 7, 'time_consume' => 37220, 'cp' => 39, 'people_inc' => 4,

                    'resources' => array('1' => 19310, '2' => 10400, '3' => 16340, '4' => 13370)

                ),


                array(

                    'value' => 6, 'time_consume' => 43780, 'cp' => 46, 'people_inc' => 4,

                    'resources' => array('1' => 24720, '2' => 13310, '3' => 20915, '4' => 17115)

                ),


                array(

                    'value' => 5, 'time_consume' => 51380, 'cp' => 55, 'people_inc' => 4,

                    'resources' => array('1' => 31640, '2' => 17035, '3' => 26775, '4' => 21905)

                ),


                array(

                    'value' => 4, 'time_consume' => 60200, 'cp' => 67, 'people_inc' => 4,

                    'resources' => array('1' => 40500, '2' => 21810, '3' => 34270, '4' => 28040)

                ),


                array(

                    'value' => 3, 'time_consume' => 70430, 'cp' => 80, 'people_inc' => 4,

                    'resources' => array('1' => 51840, '2' => 27915, '3' => 43865, '4' => 35890)

                ),


                array(

                    'value' => 2, 'time_consume' => 82300, 'cp' => 96, 'people_inc' => 4,

                    'resources' => array('1' => 66355, '2' => 35730, '3' => 56145, '4' => 45940)

                ),


                array(

                    'value' => 1, 'time_consume' => 96070, 'cp' => 115, 'people_inc' => 4,

                    'resources' => array('1' => 84935, '2' => 45735, '3' => 71870, '4' => 58800)

                ),


            )
        )


    )

);