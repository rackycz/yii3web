<?php

namespace App\Web\Shared\Helpers;

class MyHtml
{

    /**
     * Based on the 1st example https://getbootstrap.com/docs/5.3/components/navbar/
     * Changes:
     * - added: data-bs-theme
     * - added: justify-content-end
     * - removed: me-auto from div.navbar-nav
     * @return string
     */
    public static function topMenu(string $menu)
    {
        return <<<HTML
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Yii3 demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            $menu
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
HTML;
    }

    /**
     * Based on the 1st example https://getbootstrap.com/docs/5.3/components/navbar/
     * Changes:
     * - added: data-bs-theme
     * - added: some flex stuff
     * - removed: <button> + a.navbar-brand + <form>
     * @return string
     */
    public static function leftMenu(string $menu)
    {
        return <<<HTML
<nav class="navbar bg-body-tertiary flex-column align-items-stretch p-0" data-bs-theme="dark">
    <div class="d-flex flex-column align-items-stretch w-100 p-3">
        $menu
    </div>
</nav>
HTML;
    }
}
