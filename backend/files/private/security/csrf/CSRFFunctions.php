<?php
function generateCsrfTokenForForm(string $formId): string
{
    global $csrf;
    if (!isset($csrf)) {
        $csrf = new CsrfToken();
    }

    return $csrf->generateTokenForForm($formId);
}
function getCsrfTokenForForm(string $formId): ?string
{
    global $csrf;
    if (!isset($csrf)) {
        $csrf = new CsrfToken();
    }
    return $csrf->getFormToken($formId);
}

function validateCsrfTokenEsb(string $formId, string $token): bool
{
    global $csrf;
    if (!isset($csrf)) {
        $csrf = new CsrfToken();
    }
    return $csrf->validateToken($formId, $token);
}
