<?php

function mySaveHtml($content)
{
    return Markdown::convertToHtml(e($content));
}