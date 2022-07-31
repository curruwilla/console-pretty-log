<?php

use ConsolePrettyLog\Background;
use ConsolePrettyLog\Color;
use ConsolePrettyLog\Font;
use ConsolePrettyLog\Line;

require __DIR__ . '/../vendor/autoload.php';

$line = new Line();
$line->columnsSize([15, 75, 10]);
//$line->separator('|');
//$line->paddingCharacter('.');
//$line->enableDate(false);
//$line->dateFormat("d/m/Y H:i");

$line
    ->text('Account 1', [Font::BOLD, Font::ITALIC])
    ->text('Delivery made successfully', [Color::WHITE])
    ->text('Success', [Color::GREEN])
    ->print();

$line
    ->text('Account 2', [Font::BOLD])
    ->text('Opps, something went wrong with the delivery, please see the log', [Color::WHITE])
    ->text('Error', [Background::RED])
    ->print();

$line
    ->text('Account 3', [Font::BOLD])
    ->text('Payment made successfully', [Color::WHITE, Font::ITALIC])
    ->text('SUCCESS', [Background::GREEN])
    ->print();

$line
    ->text('Account 1', [Font::BOLD])
    ->text('Payment made successfully', [Color::WHITE])
    ->text('INFO', [Background::BLUE])
    ->print();