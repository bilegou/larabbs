<?php

use Faker\Generator as Faker;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

$factory->define(App\Models\Topic::class, function (Faker $faker) {

	$sentence = $faker->sentence();
	$text = $faker->text();
	$updated_at = $faker->dateTimeThisMonth();
	$created_at = $faker->dateTimeThisMonth($updated_at);

    return [
     
     	'title'=>$sentence,
     	'body'=>$text,
     	'updated_at'=>$updated_at,
     	'created_at'=>$created_at,
     	'excerpt' => $sentence,
    ];
});
