<?php

use ConsolePrettyLog\Background;
use ConsolePrettyLog\Color;
use ConsolePrettyLog\Font;
use ConsolePrettyLog\Line;

require __DIR__ . '/../src/Background.php';
require __DIR__ . '/../src/Color.php';
require __DIR__ . '/../src/Font.php';
require __DIR__ . '/../src/Line.php';

$line = new Line();
$line->columnsSize([null, null, 15, 75, null]);
//$line->separator('|');
//$line->paddingCharacter('.');
//$line->enableDate(false);
//$line->dateFormat("d/m/Y H:i");
$line->textInitial('LOG', [Color::RED, Font::BOLD]);
$line->textInitial('IMPORTANT', [Font::BOLD]);

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
    ->text('Account 35', [Font::BOLD])
    ->text('Payment made successfully', [Color::WHITE, Font::ITALIC])
    ->text('SUCCESS', [Background::GREEN])
    ->print();

$line
    ->text('Account 1', [Font::BOLD])
    ->text('Payment made successfully', [Color::WHITE])
    ->text('INFO', [Background::BLUE])
    ->print();