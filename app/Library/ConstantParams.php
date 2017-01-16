<?php
/**
 * Created by PhpStorm.
 * User: fragger
 * Date: 10/14/16
 * Time: 1:14 PM
 */

namespace App\Library;


class ConstantParams
{

    public static $branches = [
        'CE' => '1',
        'CH' => '2',
        'CSE' => '3',
        'ECE' => '4',
        'EE' => '5',
        'IT' => '6',
        'ME' => '7',
    ];

    public static $branchNames = [
        '1' => 'CE',
        '2' => 'CH',
        '3' => 'CSE',
        '4' => 'ECE',
        '5' => 'EE',
        '6' => 'IT',
        '7' => 'ME',

    ];

    public static $subjects = [
        // Semester 1
        'EAS101' => 'Engg. Phyisics-I',
        'EAS103' => 'Mathematics-I',
        'EAS104' => 'Professional Communication',
        'EAS105' => 'Environment & Ecology',
        'ECS101' => 'Computer Concepts & Programming In C',
        'EME102' => 'Engg. Mechanics',

        

        // Semester 2
        'AUC001' => 'Human Values & Professional Ethics',
        'EAS201' => 'Engg. Physics-II',
        'EAS202' => 'Engg. Chemistry',
        'EAS203' => 'Mathematics-II',
        'EEC201' => 'Electronics Engineering',
        'EEE201' => 'Electrical Engg.',
        'EME201' => 'Manufacturing Processes',

        // Semester 3
        'ECS301' => 'Digital Logic Design',
        'ECS302' => 'Data Structures Using C',
        'ECS303' => 'Discrete Mathematical Structures',
        'ECS304' => 'IT Infrastructure and its Management',
        'EAS301 ' => 'Mathematics-III',
        'EHU301' => 'Industrial Psychology',


        // Semester 4
        'ECS401' => 'Computer Organization',
        'ECS402' => 'Database Management Systems',
        'ECS403' => 'Theory of Automata & Formal Languages',
        'EEC406' => 'Introduction to Microprocessor',
        'EHU402' => 'Industrial Sociology',
        'EOE041' => 'Introduction to Soft Computing (Neural Networks, 
                     Fuzzy Logic and Genetic Algorithm)',



        // Semester 5
        'ECS501' => 'Operating System',
        'ECS502' => 'Design and Analysis of Algorithms',
        'ECS503' => 'Object Oriented Techniques',
        'ECS504' => 'Computer Graphics',
        'ECS505' => 'Graph Theory',
        'EHU501' => 'Engineering & Managerial Economics',



        // Semester 6
        'ECS601' => 'Computer Network',
        'ECS602' => 'Software Engineering',
        'ECS603' => 'Compilier Design',
        'ECS604' => 'Web Technology',
        'EHU601' => 'Industrial Management',
        'EIT505' => 'Information Security and Cyber Laws',



        // Semester 7
        'ECS701' => 'Distributed Systems',
        'ECS702' => 'Digital Image Processing',
        'ELE-OP' => 'Entrepreneurship Development',
        'ELE-2' => 'Data Mining & Data Warehousing',
        'ELE-1' => 'Pattern Recognition',


        // Semester 8
        'ECS801' => 'Artificial Intelligence',
        


    ];


}