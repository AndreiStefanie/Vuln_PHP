<?php

/**
 * Adapt various symbols to html.
 *
 * @return html adapted string
 */
function sanitizeString($string, $encoding = 'UTF-8')
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401, $encoding);
}

/**
 * Echo the html safe string.
 *
 * @return void
 */
function xecho($string) {
    echo nl2br(sanitizeString($string));
}
